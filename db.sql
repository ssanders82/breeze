
CREATE TABLE `tbl_group` (
  `group_id` int(10) unsigned NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tbl_person` (
  `person_id` int(10) unsigned NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`person_id`),
  UNIQUE KEY `person_id_UNIQUE` (`person_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `tbl_person_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `tbl_group` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
