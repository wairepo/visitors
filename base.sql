CREATE DATABASE `visitor` DEFAULT CHARACTER SET utf8;


CREATE TABLE `visitor`.`admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `role` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `visitor`.`blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `visitor`.`block_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `occupant_name` varchar(100) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `occupancy` int(2) DEFAULT 8,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unit_uniquex` (`block_id`,`level`,`unit`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `visitor`.`visitor_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `checkin` varchar(45) NOT NULL,
  `checkout` varchar(45) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `visitor_id` (`visitor_id`),
  KEY `start_date` (`checkin`),
  KEY `end_date` (`checkout`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `visitor`.`visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `nric_no` char(3) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_nric` (`phone`,`nric_no`),
  KEY `phone` (`phone`),
  KEY `nric` (`nric_no`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

