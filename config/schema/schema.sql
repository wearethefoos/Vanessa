#Vanessa sql generated on: 2010-12-13 10:59:45 : 1292234385

DROP TABLE IF EXISTS `acos`;
DROP TABLE IF EXISTS `activities`;
DROP TABLE IF EXISTS `aros`;
DROP TABLE IF EXISTS `aros_acos`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `join_student_groups`;
DROP TABLE IF EXISTS `preferences`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `security_logs`;
DROP TABLE IF EXISTS `solutions`;
DROP TABLE IF EXISTS `student_groups`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `students_courses`;
DROP TABLE IF EXISTS `users`;


CREATE TABLE `acos` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`parent_id` int(10) DEFAULT NULL,
	`model` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`foreign_key` int(10) DEFAULT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`lft` int(10) DEFAULT NULL,
	`rght` int(10) DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `parent_id` (`parent_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `activities` (
	`id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'The Id',
	`course_id` int(10) NOT NULL,
	`name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`teacher` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`room` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`max_participants` int(2) NOT NULL COMMENT 'Maximum number students that may take part.',
	`min_participants` int(2) NOT NULL COMMENT 'Minium number of students taking part.',	PRIMARY KEY  (`id`),
	KEY `course_id` (`course_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=InnoDB;

CREATE TABLE `aros` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`parent_id` int(10) DEFAULT NULL,
	`model` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`foreign_key` int(10) DEFAULT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`lft` int(10) DEFAULT NULL,
	`rght` int(10) DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `parent_id` (`parent_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `aros_acos` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`aro_id` int(10) NOT NULL,
	`aco_id` int(10) NOT NULL,
	`_create` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL,
	`_read` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL,
	`_update` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL,
	`_delete` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL,	PRIMARY KEY  (`id`),
	UNIQUE KEY `ARO_ACO_KEY` (`aro_id`, `aco_id`),
	KEY `aco_id` (`aco_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `courses` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
	`user_id` int(11) DEFAULT NULL,
	`amount_of_preferences` int(11) DEFAULT 4 NOT NULL,	PRIMARY KEY  (`id`),
	KEY `user_id` (`user_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=InnoDB;

CREATE TABLE `join_student_groups` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`student_id` int(11) NOT NULL,
	`student_group_id` int(11) NOT NULL,	PRIMARY KEY  (`id`),
	KEY `student_id` (`student_id`),
	KEY `student_group_id` (`student_group_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=InnoDB;

CREATE TABLE `preferences` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`student_group_id` int(11) NOT NULL,
	`activity_id` int(11) NOT NULL,
	`unwantedness` int(11) NOT NULL,	PRIMARY KEY  (`id`),
	KEY `student_group_id` (`student_group_id`),
	KEY `activity_group_id` (`activity_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=InnoDB;

CREATE TABLE `roles` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`name` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`description` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,	PRIMARY KEY  (`id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=InnoDB;

CREATE TABLE `security_logs` (
	`id` int(64) NOT NULL AUTO_INCREMENT,
	`user_id` int(10) DEFAULT 1,
	`username` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`login_status` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`log_time` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
	`sec_msg` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `user_id` (`user_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=MyISAM;

CREATE TABLE `solutions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`course_id` int(11) NOT NULL,
	`student_id` int(11) NOT NULL,
	`unwantedness` int(11) NOT NULL,
	`activity_id` int(11) NOT NULL,
	`created` datetime NOT NULL,
	`modified` datetime NOT NULL,	PRIMARY KEY  (`id`),
	KEY `course_id` (`course_id`),
	KEY `student_id` (`student_id`),
	KEY `activity_id` (`activity_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=MyISAM;

CREATE TABLE `student_groups` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`course_id` int(11) NOT NULL,	PRIMARY KEY  (`id`),
	KEY `course_id` (`course_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=InnoDB;

CREATE TABLE `students` (
	`id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'Key value. (can sometimes agree with college kaart)',
	`coll_kaart` int(8) NOT NULL COMMENT 'College kaart number.',
	`ldap_uid` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'UID for student in LDAP (usually agrees (numerically) with college kaart number)',	PRIMARY KEY  (`id`),
	KEY `inx_coll_kaart` (`coll_kaart`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=InnoDB;

CREATE TABLE `students_courses` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`student_id` int(10) NOT NULL,
	`course_id` int(10) NOT NULL,
	`comment` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `student_id` (`student_id`, `course_id`),
	KEY `course_id` (`course_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=InnoDB;

CREATE TABLE `users` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`student_id` int(10) DEFAULT NULL,
	`username` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`phone_number` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
	`sex` int(4) DEFAULT NULL,
	`role_id` int(10) DEFAULT NULL,
	`activated` int(4) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`updated` datetime DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `role_id` (`role_id`),
	KEY `username` (`username`),
	KEY `password` (`password`),
	KEY `student_id` (`student_id`))	DEFAULT CHARSET=utf8,
	COLLATE=utf8_general_ci,
	ENGINE=InnoDB;

