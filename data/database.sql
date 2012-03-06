-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 06, 2012 at 10:23 PM
-- Server version: 5.5.21
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tymoonD`
--

-- --------------------------------------------------------

--
-- Table structure for table `ms_categories`
--

CREATE TABLE IF NOT EXISTS `ms_categories` (
  `categoryID` bigint(20) NOT NULL AUTO_INCREMENT,
  `MID` int(11) NOT NULL,
  `PID` bigint(20) NOT NULL,
  `title` varchar(35) NOT NULL,
  `subject` text NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ms_hooks`
--

CREATE TABLE IF NOT EXISTS `ms_hooks` (
  `source` varchar(64) NOT NULL,
  `hook` varchar(64) NOT NULL,
  `destination` varchar(64) NOT NULL,
  `function` varchar(64) NOT NULL,
  PRIMARY KEY (`source`,`hook`,`destination`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ms_hooks`
--

INSERT INTO `ms_hooks` (`source`, `hook`, `destination`, `function`) VALUES
('CORE', 'HITDOMAIN', 'CORE', 'printTimePassed'),
('CORE', 'HITadmin', 'Admin', 'displayPage');

-- --------------------------------------------------------

--
-- Table structure for table `ms_log`
--

CREATE TABLE IF NOT EXISTS `ms_log` (
  `logID` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text NOT NULL,
  `time` varchar(16) NOT NULL,
  `user` int(64) NOT NULL,
  PRIMARY KEY (`logID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ms_modules`
--

CREATE TABLE IF NOT EXISTS `ms_modules` (
  `name` varchar(35) NOT NULL,
  `subject` text NOT NULL,
  `activated` tinyint(1) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ms_options`
--

CREATE TABLE IF NOT EXISTS `ms_options` (
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(1) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_options`
--

INSERT INTO `ms_options` (`key`, `value`, `type`) VALUES
('akismet_key', '9fce28faba37', 's'),
('avatar_maxdim', '150', 'i'),
('avatar_maxsize', '500', 'i'),
('cookie_life_h', '8544', 'i'),
('default_theme', 'default', 's'),
('metakeys', 'Shinmera, NexT, Nick, Nicolas Hafner, Nicolas, Hafner, comic, webcomic, blog, projects, Tymoon, TyNET, TymoonNET, gallery, forum, Stevenchan, programming, art, drawing', 'l'),
('recaptcha_key_private', '6LeYH7wSAAAAAMyEpHJzu0HScC6hqm6CyV7WPVMG', 's'),
('recaptcha_key_public', '6LeYH7wSAAAAADyB1R9ooRPtxFSTCUcnL5dO6dr8', 's'),
('salt1', 'MLPisAveryAWESOMEshow', 's'),
('salt2', 'you_have_GOT_to_be_shittong_me', 's'),
('salt3', '23scUOBa38@#J(&)2h1linDV(03uBNX:', 's'),
('sitename', 'TyNET', 's'),
('sysop_mail', 'admin@xprog.ch', 's');

-- --------------------------------------------------------

--
-- Table structure for table `ms_timer`
--

CREATE TABLE IF NOT EXISTS `ms_timer` (
  `IP` varchar(16) NOT NULL,
  `time` int(11) NOT NULL,
  `action` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ud_fields`
--

CREATE TABLE IF NOT EXISTS `ud_fields` (
  `fieldID` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(35) NOT NULL,
  `varname` varchar(32) NOT NULL,
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  `displayed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldID`),
  UNIQUE KEY `varname` (`varname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `ud_groups`
--

CREATE TABLE IF NOT EXISTS `ud_groups` (
  `groupID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(35) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`groupID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ud_permissions`
--

CREATE TABLE IF NOT EXISTS `ud_permissions` (
  `UID` bigint(20) NOT NULL,
  `base` varchar(32) NOT NULL,
  `tree` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ud_users`
--

CREATE TABLE IF NOT EXISTS `ud_users` (
  `userID` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `mail` varchar(35) NOT NULL,
  `password` varchar(128) NOT NULL,
  `secret` text NOT NULL,
  `displayname` varchar(32) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `group` bigint(20) NOT NULL,
  `status` varchar(64) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `displayname` (`displayname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uf_field_values`
--

CREATE TABLE IF NOT EXISTS `uf_field_values` (
  `fieldID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`fieldID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
