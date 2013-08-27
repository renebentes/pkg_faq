-- 1.0
CREATE TABLE IF NOT EXISTS `#__faq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `images` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `xreference` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- 1.2
ALTER TABLE `#__faq` CHANGE COLUMN `state` `published` tinyint(3) NOT NULL DEFAULT '0';

-- 1.3
ALTER TABLE `#__faq` DROP COLUMN `xreference`;
ALTER TABLE `#__faq` DROP COLUMN `featured`;

-- 1.4
ALTER TABLE `#__faq` ADD COLUMN `writer` text NOT NULL AFTER `metadata`;

-- 1.5
CREATE TABLE IF NOT EXISTS `#__faq_rating` (
  `faq_id` int(10) unsigned NOT NULL DEFAULT '0',
  `vote_up` int(10) unsigned NOT NULL DEFAULT '0',
  `vote_down` int(10) unsigned NOT NULL DEFAULT '0',
  `last_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`faq_id`),
  FOREIGN KEY (`faq_id`) REFERENCES `#__faq` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `#__faq` CHANGE COLUMN `title` NOT NULL DEFAULT '';