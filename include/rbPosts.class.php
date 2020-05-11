<?php

class rbPosts
{

	public static function modifyBlog($title, $content,$content_markup, $password, $displaymode = 0, $isshow = true, $type, $renewtime, $tid, $arrTags, $reNotify, $is_page = false)
	{
		global $gId, $rsskey;
		$pTid = intval($tid);
		$owner = dbRs("SELECT ownerid FROM zb_contentpages WHERE id = $pTid");

		if ($owner != $gId) {
			return -1;
		}
		$datetime = $renewtime ? "NOW()" : "datetime";
		dbQuery(
			"UPDATE zb_contentpages SET 
				title=:title, 
				content=:content, 
				content_markup=:content_markup,
				password=:password, 
				displaymode=:displaymode, 
				isshow=:isshow, 
				is_page=:is_page, 
				type=:type, 
				datetime=$datetime WHERE id=:pTid",
			[
				"title" => $title,
				"content" => $content,
				'content_markup' => $content_markup,
				"password" => $password,
				"displaymode" => $displaymode,
				"isshow" => $isshow ? 1 : 0,
				"is_page" => $is_page ? 1 : 0,
				"type" => intval($type),
				"pTid" => $pTid
			]
		);

		//dbQuery("UPDATE `zb_user` SET lastcpid = ".safe($_POST['tid'])." WHERE id = $gId");
		dbQuery("UPDATE `zb_user` SET lastcpid = (SELECT max(id) FROM zb_contentpages WHERE ownerid = $gId AND isshow=1) WHERE id = $gId");
		clearTag($pTid, 2);
		if ($reNotify) {
			foreach ($arrTags as $tag) {

				insertTagAndNotify($tag, $pTid, "你關注的<strong>%number%</strong> 在Realblog 有文章修改: $title", 2);
			}
		} else {
			insertTag($arrTags, $pTid, 2);
		}


		cacheVoid($rsskey . $gId);
		return $pTid;
	}

	public static function isBlogExists($ownerid, $title)
	{
		$title = safe($title);
		$ownerid = intval($ownerid);
		$c = dbRs("SELECT count(1) FROM zb_contentpages WHERE ownerid = $ownerid AND title = '{$title}'");
		return intval($c) > 0;
	}
	public static function newBlog($ownerid, $title, $content, $content_markup, $password, $isshow = true, $displaymode = 0, $type, $arrTags, $is_page = false)
	{

		$ownerid = intval($ownerid);
		$lastcpid = dbQuery(
			"INSERT INTO zb_contentpages (ownerid, title, content, content_markup, password, isshow, is_page, displaymode, type, datetime) 
			VALUES (:ownerid, :title, :content,:content_markup, :password, :isshow, :is_page, :displaymode, :type, NOW())",
			[

				'ownerid' => $ownerid,
				'title' => $title,
				'content' => $content,
				'content_markup' => $content_markup,
				'password' => $password,
				'isshow' => $isshow ? 1 : 0,
				'is_page' => $is_page ? 1 : 0,
				'displaymode' => intval($displaymode),
				'type' => intval($type)
			]
		);


		dbQuery("UPDATE `zb_user` SET lastcpid = $lastcpid WHERE id = $ownerid");


		foreach ($arrTags as $tag) {
			insertTagAndNotify($tag, $lastcpid, "你關注的<strong>%number%</strong> 在Realblog 有新文章: $title", 2);
		}


		return $lastcpid;
	}
	public static function deleteBlog($tid, $zid)
	{
		global $rsskey;
		$tid = intval($tid);
		dbQuery("DELETE FROM zb_contentpages WHERE id={$tid}");
		dbQuery("UPDATE `zb_user` SET lastcpid = (SELECT max(id) FROM zb_contentpages WHERE ownerid = $zid AND isshow=1) WHERE id = $zid");

		cacheVoid($rsskey . $zid);
	}
}
