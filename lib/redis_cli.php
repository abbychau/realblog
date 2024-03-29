<?php

/**
 * Very simple Redis implementation, all commands passed in cli format
 * Add commands via cmd ( $command [, $variable1 [, $variable2 ] ] ) method
 * Fire commands via get () o set () methods ( first one will return output, usefull for get operations )
 *
 * Usage:
 * $redis = new redis_cli ( '127.0.0.1', 6379 );
 * $redis -> cmd ( 'SET', 'foo', 'bar' ) -> set ();
 * $foo = $redis -> cmd ( 'GET', 'foo' ) -> get ();
 *
 * $redis -> cmd ( 'HSET', 'hash', 'foo', 'bar' ) -> cmd ( 'HSET', 'hash', 'abc', 'def' ) -> set ();
 * $vals = $redis -> cmd ( 'HVALS', 'hash' ) -> get ();
 *
 * $redis -> cmd ( 'KEYS', 'online*' );
 * $total_online = $redis -> get_len ();
 *
 * Based on http://redis.io/topics/protocol
 */
class redis_cli {
	const INTEGER = ':';
	const INLINE = '+';
	const BULK = '$';
	const MULTI_BULK = '*';
	const ERROR = '-';
	const NL = "\r\n";
	
	private $handle = null;
	private $host;
	private $port;
	private $silent_fail;
	
	private $commands = array();
	
	//Timeout for stream, 30 seconds
	private $timeout = 30;
	
	//Timeout for socket connection
	private $connect_timeout = 3;
	
	//Use this with extreme caution
	private $force_reconnect = false;
	
	//Error handling, debug info
	private $last_used_command = '';
	
	//Error handling function, use set_error_function method ()
	private $error_function = null;
	
	public function __construct($host = false, $port = false, $silent_fail = false, $timeout = 60) {
		if($host && $port) {
			$this->connect($host, $port, $silent_fail, $timeout);
		}
	}
	
	//Main method to establish connection
	public function connect($host = '127.0.0.1', $port = 6379, $silent_fail = false, $timeout = 60) {
		$this->host        = $host;
		$this->port        = $port;
		$this->silent_fail = $silent_fail;
		$this->timeout     = $timeout;
		
		if($silent_fail) {
			$this->handle = @fsockopen($host, $port, $errno, $errstr, $this->connect_timeout);
			
			if(!$this->handle) {
				$this->handle = false;
			}
		} else {
			$this->handle = fsockopen($host, $port, $errno, $errstr, $this->connect_timeout);
		}
		
		if(is_resource($this->handle)) {
			stream_set_timeout($this->handle, $this->timeout);
		}
	}
	
	public function reconnect() {
		$this->__destruct();
		$this->connect($this->host, $this->port, $this->silent_fail);
	}
	
	public function __destruct() {
		if(is_resource($this->handle)) {
			fclose($this->handle);
		}
	}
	
	//Returns all commands array
	public function commands() {
		return $this->commands;
	}
	

	
	//Used to push many commands at once, almost always for setting something
	public function set() {
		if(!$this->handle) {
			return false;
		}
		
		//Total size of commands
		$size     = $this->exec();
		$response = array();
		
		for($i = 0; $i < $size; $i++) {
			$response[] = $this->get_response();
		}
		
		if($this->force_reconnect) {
			$this->reconnect();
		}
		
		return $response;
	}
	
	//Used to get command response
	public function get() {
		if(!$this->handle) {
			return false;
		}
		
		$return = false;
		
		if($this->exec()) {
			$return = $this->get_response();
			
			if($this->force_reconnect) {
				$this->reconnect();
			}
			
		}
		
		return $return;
	}
	
	//Used to get length of the returned array. Most useful with `Keys` command
	public function get_len() {
		if(!$this->handle) {
			return false;
		}
		
		$return = null;
		
		if($this->exec()) {
			$char = fgetc($this->handle);
			
			if($char == self::BULK) {
				$return = sizeof($this->bulk_response());
			} elseif($char == self::MULTI_BULK) {
				$return = sizeof($this->multi_bulk_response());
			}
			
			if($this->force_reconnect) {
				$this->reconnect();
			}
		}
		
		return $return;
	}
	
	//Forces to reconnect after every get() or set(). Use this with extreme caution
	public function set_force_reconnect($flag) {
		$this->force_reconnect = $flag;
		return $this;
	}
	
	//Used to parse single command single response
	private function get_response() {
		$return = false;
		
		$char = fgetc($this->handle);
		
		switch($char) {
			case self::INLINE:
				$return = $this->inline_response();
				break;
			case self::INTEGER:
				$return = $this->integer_response();
				break;
			case self::BULK:
				$return = $this->bulk_response();
				break;
			case self::MULTI_BULK:
				$return = $this->multi_bulk_response();
				break;
			case self::ERROR:
				$return = $this->error_response();
				break;
		}
		
		return $return;
	}
	
	//For inline responses only
	private function inline_response() {
		return trim(fgets($this->handle));
	}
	
	//For integer responses only
	private function integer_response() {
		return ( int ) trim(fgets($this->handle));
	}
	
	//For error responses only
	private function error_response() {
		$error = fgets($this->handle);
		
		if($this->error_function) {
			call_user_func($this->error_function, $error . '(' . $this->last_used_command . ')');
		}
		
		return false;
	}
	
	//For bulk responses only
	private function bulk_response() {
		$return = trim(fgets($this->handle));
		
		if($return === '-1') {
			$return = null;
		} else {
			$return = $this->read_bulk_response($return);
		}
		
		return $return;
	}
	
	//For multi bulk responses only
	private function multi_bulk_response() {
		$size   = trim(fgets($this->handle));
		$return = null;

		if($size !== '-1') {
			$return = array();
			
			for($i = 0; $i < $size; $i++) {
				$tmp = trim(fgets($this->handle));
				
				if($tmp === '-1') {
					$return[] = null;
				} else {
					$return[] = $this->read_bulk_response($tmp);
				}
			}
		}
		
		return $return;
	}
	
	//Sends command to the redis
	private function exec() {
		$size = sizeof($this->commands);
		
		if($size < 1) {
			return null;
		}
		
		if($this->error_function) {
			$this->last_used_command = str_replace(self::NL, '\\r\\n', implode(';', $this->commands));
		}
		
		$command = implode(self::NL, $this->commands) . self::NL;
		fwrite($this->handle, $command);
		
		$this->commands = array();
		return $size;
	}
	
	//Bulk response reader
	private function read_bulk_response($tmp) {
		$response = null;
		
		$read = 0;
		$size = ((strlen($tmp) > 1 && substr($tmp, 0, 1) === self::BULK) ? substr($tmp, 1) : $tmp);
		
		while($read < $size) {
			$diff = $size - $read;
			
			$block_size = $diff > 8192 ? 8192 : $diff;
			
			$response .= fread($this->handle, $block_size);
			$read += $block_size;
		}
		
		fgets($this->handle);
		
		return $response;
	}
	
	public function set_error_function($func) {
		$this->error_function = $func;
	}
	//Used to push single command to queue
	public function cmd() {
		if(!$this->handle) {
			return $this;
		}
		
		$args = func_get_args();
		
		$output = '*' . count($args) . self::NL;
		
		foreach($args as $arg) {
			$output .= '$' . strlen($arg) . self::NL . $arg . self::NL;
		}
		
		$this->commands[] = $output;
		
		return $this;
	}	
	public function exists($key) {
		return $this->cmd("EXISTS", $key)->get();
	}
	
	public function setTimeout($key, $time) {
		return $this->cmd("EXPIRE", $key, $time)->get();
	}
	
	public function getValue($key) {
		return $this->cmd("GET", $key)->get();
	}
	public function delete($key) {
		return $this->cmd("DEL", $key)->get();
	}
	
	public function setValue($key, $val) {
		return $this->cmd("SET", $key, $val)->set();
	}


	//SET
	public function sAdd($key, $item){
		return $this->cmd("SADD", $key, $item)->set();
	}
	public function setAdd($key, $item){
		return $this->cmd("SADD", $key, $item)->set();
	}
	public function sMembers($key){
		return $this->cmd("SMEMBERS", $key)->get();
	}
	public function setHasMember($key,$item){
		return ($this->cmd("SISMEMBER", $key, $item)->get() == "1");
	}
	public function setRemoveMember($key,$item){
		return $this->cmd("SREM", $key,$item)->set();
	}
	//QUEUE
	public function queueAdd($queueName,$item){
		return $this->cmd("LPUSH", $queueName, $item)->set();
	}
	public function queueGet($queueName){
		return $this->cmd("RPOP", $queueName)->get();
	}
}