

DROP TABLE IF EXISTS `zb_contenttype`;
DROP TABLE IF EXISTS `zb_comment`;
DROP TABLE IF EXISTS `zb_contentpages`;
DROP TABLE IF EXISTS `zb_tag_owner`;
DROP TABLE IF EXISTS `zb_user`;
DROP TABLE IF EXISTS `zm_tags`;
DROP TABLE IF EXISTS `zm_tags_entry`;



-- zb_user
CREATE TABLE `zb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `blogname` varchar(255) NOT NULL,
  `slogan` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `last_content_page_id` int(11) NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blacklisted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- zb_contenttype
CREATE TABLE `zb_contenttype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- zb_comment
CREATE TABLE `zb_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` MEDIUMTEXT NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `content_page_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


drop table if exists `zb_contentpages`;
-- zb_contentpages
CREATE TABLE `zb_contentpages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `content_markup` MEDIUMTEXT NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `is_show` tinyint(1) NOT NULL DEFAULT 1,
  `is_page` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `comment_count` int(11) NOT NULL DEFAULT 0,
  `display_mode` tinyint(1) NOT NULL DEFAULT 0,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- zb_tag_owner
CREATE TABLE `zb_tag_owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


drop table if exists `zm_tags`;
-- zm_tags
CREATE TABLE `zm_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `searched_count` int(11) NOT NULL DEFAULT 0,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

drop table if exists `zm_tags_entry`;
-- zm_tags_entry
CREATE TABLE `zm_tags_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `entry_type` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `zb_user` (`id`, `username`, `password`, `blogname`, `slogan`, `email`, 
`last_content_page_id`) VALUES
(1, 'abbychau', 'aassddff', 'abbychau', 'abbychau', 'abbychau@gmail.com', NULL);