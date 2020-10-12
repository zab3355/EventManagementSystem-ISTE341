
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `attendee`;
CREATE TABLE `attendee` (
  `idattendee` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int(11) DEFAULT NULL,
  PRIMARY KEY (`idattendee`),
  KEY `role_idx` (`role`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `attendee_event`;
CREATE TABLE `attendee_event` (
  `event` int(11) NOT NULL,
  `attendee` int(11) NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event`,`attendee`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `attendee_session`;
CREATE TABLE `attendee_session` (
  `session` int(11) NOT NULL,
  `attendee` int(11) NOT NULL,
  PRIMARY KEY (`session`,`attendee`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `idevent` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `datestart` datetime NOT NULL,
  `dateend` datetime NOT NULL,
  `numberallowed` int(11) NOT NULL,
  `venue` int(11) NOT NULL,
  PRIMARY KEY (`idevent`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `manager_event`;
CREATE TABLE `manager_event` (
  `event` int(11) NOT NULL,
  `manager` int(11) NOT NULL,
  PRIMARY KEY (`event`,`manager`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `projectComments`;
CREATE TABLE `projectComments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `comment` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `projectComments` (`id`, `name`, `comment`) VALUES
(56,	'Zach',	'Project 1 Test'),
(62,	'Bryan',	'cool'),
(63,	'Bryan',	'test'),
(64,	'Phil',	'alert(&#34;hi&#34;);');

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `idsession` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `numberallowed` int(11) NOT NULL,
  `event` int(11) NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  PRIMARY KEY (`idsession`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- 2020-06-27 01:37:45