<?php

class rbPosts
{

	public static function modifyBlog($title, $content, $content_markup, $password, $display_mode = 0, 
	$is_show = true, $type=1, $renewtime=null, $tid=0, $arrTags=[], $reNotify=false, $is_page = false)
	{
		global $gId, $rsskey, $redisNative;
		$pTid = intval($tid);
		$owner = dbRs("SELECT user_id FROM zb_contentpages WHERE id = $pTid");

		if ($owner != $gId) {
			return -1;
		}
		$create_time = $renewtime ? "NOW()" : "create_time";
		dbQuery(
			"UPDATE zb_contentpages SET 
				title=:title, 
				content=:content, 
				content_markup=:content_markup,
				password=:password, 
				display_mode=:display_mode, 
				is_show=:is_show, 
				is_page=:is_page, 
				type=:type, 
				create_time=$create_time WHERE id=:pTid",
			[
				"title" => $title,
				"content" => $content,
				'content_markup' => $content_markup,
				"password" => $password,
				"display_mode" => $display_mode,
				"is_show" => $is_show ? 1 : 0,
				"is_page" => $is_page ? 1 : 0,
				"type" => intval($type),
				"pTid" => $pTid
			]
		);

		dbQuery("UPDATE `zb_user` SET last_content_page_id = (SELECT max(id) FROM zb_contentpages WHERE user_id = $gId AND is_show=1) WHERE id = $gId");
		clearTag($pTid, 2);
		if ($reNotify) {
			foreach ($arrTags as $tag) {

				insertTagAndNotify($tag, $pTid, "你關注的<strong>%number%</strong> 在Realblog 有文章修改: $title", 2);
			}
		} else {
			insertTag($arrTags, $pTid, 2);
		}

		$redisNative->hDel($rsskey . $gId);
		// cacheVoid($rsskey . $gId);
		return $pTid;
	}

	public static function isBlogExists($user_id, $title)
	{
		$c = dbRs(
			"SELECT count(1) FROM zb_contentpages WHERE user_id = :owenerid AND title = :title",
			['user_id' => intval($user_id), 'title' => $title]
		);
		return intval($c) > 0;
	}
	public static function newBlog($user_id, $title, $content, $content_markup, $password, $is_show, $display_mode, $type, $arrTags, $is_page)
	{
		$is_show = $is_show??true;
		$display_mode=$display_mode??0;
		$is_page=$is_page??false;
		$user_id = intval($user_id);
		$last_content_page_id = dbQuery(
			"INSERT INTO zb_contentpages (user_id, title, content, content_markup, password, 
			is_show, is_page, display_mode, content_type_id, create_time) 
			VALUES (:user_id, :title, :content,:content_markup, :password, :is_show, :is_page, :display_mode, :type, NOW())",
			[

				'user_id' => $user_id,
				'title' => $title,
				'content' => $content,
				'content_markup' => $content_markup,
				'password' => $password,
				'is_show' => $is_show ? 1 : 0,
				'is_page' => $is_page ? 1 : 0,
				'display_mode' => intval($display_mode),
				'type' => intval($type)
			]
		);


		dbQuery("UPDATE `zb_user` SET last_content_page_id = $last_content_page_id WHERE id = $user_id");


		foreach ($arrTags as $tag) {
			insertTagAndNotify($tag, $last_content_page_id, "你關注的<strong>%number%</strong> 在Realblog 有新文章: $title", 2);
		}


		return $last_content_page_id;
	}
	public static function deleteBlog($tid, $zid)
	{
		global $rsskey,$redisNative;
		$tid = intval($tid);
		dbQuery("DELETE FROM zb_contentpages WHERE id={$tid}");
		dbQuery("UPDATE `zb_user` SET last_content_page_id = (SELECT max(id) FROM zb_contentpages WHERE user_id = $zid AND is_show=1) WHERE id = $zid");
		$redisNative->hDel($rsskey, $zid);
		// cacheVoid($rsskey . $zid);
	}
}
