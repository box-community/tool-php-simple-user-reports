CREATE TABLE `tokens` (
  `varName` varchar(200) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`varName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tokens` (`varName`, `value`)
VALUES
	('access_token', ''),
	('refresh_token', '');


CREATE TABLE `user_stats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  `modified_at` varchar(255) DEFAULT NULL,
  `space_amount` varchar(255) DEFAULT NULL,
  `space_used` bigint(20) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `timestamp` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `service_stats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `totalUsers` bigint(20) DEFAULT NULL,
  `totalStorage` bigint(20) DEFAULT NULL,
  `timestamp` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;