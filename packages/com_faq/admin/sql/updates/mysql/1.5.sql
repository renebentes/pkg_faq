CREATE TABLE IF NOT EXISTS `#__faq_rating` (
  `faq_id` int(10) unsigned NOT NULL DEFAULT '0',
  `vote_up` int(10) unsigned NOT NULL DEFAULT '0',
  `vote_down` int(10) unsigned NOT NULL DEFAULT '0',
  `last_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`faq_id`),
  FOREIGN KEY (`faq_id`) REFERENCES `#__faq` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `#__faq` CHANGE COLUMN `title` NOT NULL DEFAULT '';