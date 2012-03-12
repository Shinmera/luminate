-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 13, 2012 at 12:10 AM
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

REPLACE INTO `ms_hooks` (`source`, `hook`, `destination`, `function`) VALUES
('CORE', 'HITDOMAIN', 'CORE', 'printTimePassed'),
('CORE', 'HITadmin', 'Admin', 'displayPage'),
('CORE', 'HITlogin', 'User', 'displayLogin'),
('Admin', 'PANELdisplay', 'User', 'displayPanel'),
('Admin', 'ADMINUser', 'User', 'displayAdminPage');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ms_log`
--

REPLACE INTO `ms_log` (`logID`, `subject`, `time`, `user`) VALUES
(1, 'Log cleared.', '1331569674', 1),
(2, 'Option key &#039;idiots&#039; deleted.', '1331571299', 1),
(3, 'Hook Admin::PANELdisplay =&gt; User::displayPanel added.', '1331572321', 1),
(4, 'Hook Admin::ADMINUser =&gt; User::displayAdminPage added.', '1331582924', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ms_modules`
--

CREATE TABLE IF NOT EXISTS `ms_modules` (
  `name` varchar(35) NOT NULL,
  `subject` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_modules`
--

REPLACE INTO `ms_modules` (`name`, `subject`) VALUES
('Admin', 'Allows administration of CORE models and provides an interface for module specific configuration pages.'),
('Auth', 'Provides a secure session and authentication system, as well as permission management.'),
('CORE', 'This is the CORE module, providing the INIT system.'),
('Parser', 'Used to provide an extensive bbcode system.'),
('Themes', 'A simple theming system, making page construction very simple.'),
('User', 'Allows for user management and supplies AUTH login/logout functions.');

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

REPLACE INTO `ms_options` (`key`, `value`, `type`) VALUES
('akismet_key', '9fce28faba37', 's'),
('avatar_maxdim', '150', 'i'),
('avatar_maxsize', '500', 'i'),
('cookie_life_h', '8544', 'i'),
('default_theme', 'default', 's'),
('metakeys', 'Tymoon;TymoonNET;NexT;TymoonNexT;Shinmera;Nicolas;Hafner;Stevenchan', 'l'),
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

--
-- Dumping data for table `ms_timer`
--

REPLACE INTO `ms_timer` (`IP`, `time`, `action`) VALUES
('127.0.0.1', 1331593780, 'visit:');

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
-- Table structure for table `ud_field_values`
--

CREATE TABLE IF NOT EXISTS `ud_field_values` (
  `fieldID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`fieldID`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

--
-- Dumping data for table `ud_permissions`
--

REPLACE INTO `ud_permissions` (`UID`, `base`, `tree`) VALUES
(1, '*', '*'),
(1, '*', '*');

-- --------------------------------------------------------

--
-- Table structure for table `ud_users`
--

CREATE TABLE IF NOT EXISTS `ud_users` (
  `userID` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `mail` varchar(35) NOT NULL,
  `password` varchar(128) NOT NULL,
  `secret` varchar(32) NOT NULL,
  `displayname` varchar(32) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `group` bigint(20) NOT NULL,
  `status` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `displayname` (`displayname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ud_users`
--

REPLACE INTO `ud_users` (`userID`, `username`, `mail`, `password`, `secret`, `displayname`, `filename`, `group`, `status`, `time`) VALUES
(1, 'Shinmera', 'nhafner@gmx.ch', '9c9b7260d5e4d1fa396a1255ea82f0a879559c28f64a93d397ccaf2fcef3f09322ac23ff72095f24a4c99bb55696cfdafc409f39fcdcfda9b11da460f9bd5ae5', 'wwhatever', 'Shinmera', '', 1, 'a', 0);
