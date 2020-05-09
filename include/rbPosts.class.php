<?php
	
	class rbPosts{
		
		public static function safe2($str){
			return '"'.safe($str).'"';
		}
		
		public static function modifyBlog($title,$content,$password,$displaymode=0,$isshow=true,$type,$renewtime,$tid,$arrTags,$reNotify,$is_page=false){
			global $gId,$rsskey;
			$pTid = intval($tid);
			$owner = dbRs("SELECT ownerid FROM zb_contentpages WHERE id = $pTid");
			
			if($owner != $gId){return -1;}
			
			$updateSQL = sprintf("UPDATE zb_contentpages SET title=%s, content=%s, password=%s, displaymode=%s, isshow=%s, is_page=%s, type=%s, datetime=%s WHERE id=%s",
			safe2($title),
			safe2($content),
			safe2($password),
			safe2($displaymode),
			$isshow?1:0,
			$is_page?1:0,
			intval($type),
			$renewtime?"NOW()":"datetime",
			$pTid);
			dbQuery($updateSQL);
			
			//dbQuery("UPDATE `zb_user` SET lastcpid = ".safe($_POST['tid'])." WHERE id = $gId");
			dbQuery("UPDATE `zb_user` SET lastcpid = (SELECT max(id) FROM zb_contentpages WHERE ownerid = $gId AND isshow=1) WHERE id = $gId");
			clearTag($pTid,2);
			if($reNotify){
				foreach($arrTags as $tag){
					
					insertTagAndNotify($tag,$pTid,"你關注的<strong>%number%</strong> 在Realblog 有文章修改: $title",2);
					
				}
			}else{
				insertTag($arrTags,$pTid,2);
			}
			
			
			cacheVoid($rsskey.$gId);
			return $pTid;
		}
		
		public static function isBlogExists($ownerid,$title){
			$title = safe($title);
			$ownerid = intval($ownerid);
			$c = dbRs("SELECT count(1) FROM zb_contentpages WHERE ownerid = $ownerid AND title = '{$title}'");
			return intval($c)>0;
		}
		public static function newBlog($ownerid,$title,$content,$password,$isshow=true,$displaymode=0,$type,$arrTags,$is_page=false){
			
			$insertSQL = sprintf("INSERT INTO zb_contentpages (ownerid, title, content,password, isshow, is_page, displaymode, type, datetime) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
			intval($ownerid),self::safe2($title),self::safe2($content),self::safe2($password),$isshow?1:0,$is_page?1:0,intval($displaymode),intval($type),"NOW()");
			
			$lastcpid=dbQuery($insertSQL);
			
			
			dbQuery("UPDATE `zb_user` SET lastcpid = $lastcpid WHERE id = $ownerid");
			
			
			foreach($arrTags as $tag){
				insertTagAndNotify($tag,$lastcpid,"你關注的<strong>%number%</strong> 在Realblog 有新文章: $title",2);
				}
				
			
			return $lastcpid;
		}
		public static function deleteBlog($tid,$zid){
			global $rsskey;
			$tid=intval($tid);
			dbQuery("DELETE FROM zb_contentpages WHERE id={$tid}");
			dbQuery("UPDATE `zb_user` SET lastcpid = (SELECT max(id) FROM zb_contentpages WHERE ownerid = $zid AND isshow=1) WHERE id = $zid");
			
			cacheVoid($rsskey.$zid);
			
		}
	}	