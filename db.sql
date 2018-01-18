--
-- MySQL 5.5.58
-- Thu, 18 Jan 2018 19:10:18 +0000
--

CREATE TABLE `members` (
   `id` int(11) not null auto_increment,
   `member_name` varchar(255),
   `channel_id` varchar(255),
   `photo` longtext,
   `credit` int(11),
   `access_token` longtext,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;
