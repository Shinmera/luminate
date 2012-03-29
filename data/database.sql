-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 29, 2012 at 10:30 PM
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
-- Table structure for table `fenfire_comments`
--

CREATE TABLE IF NOT EXISTS `fenfire_comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `FID` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `mail` varchar(32) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `moderation` tinyint(1) NOT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fenfire_folders`
--

CREATE TABLE IF NOT EXISTS `fenfire_folders` (
  `folderID` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL,
  `path` varchar(64) NOT NULL,
  `order` text NOT NULL,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`folderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
('CORE', 'HITadmin', 'Admin', 'displayPage'),
('CORE', 'HITlogin', 'Neon', 'displayLogin'),
('Admin', 'PANELdisplay', 'User', 'displayPanel'),
('Admin', 'ADMINUser', 'User', 'displayAdminPage'),
('Admin', 'PANELdisplay', 'Themes', 'displayPanel'),
('Admin', 'ADMINThemes', 'Themes', 'displayAdminPage'),
('CORE', 'HITapi', 'CORE', 'apiCall'),
('CORE', 'APIThemes', 'Themes', 'displayAPI'),
('Themes', 'buildMenu', 'Admin', 'buildMenu'),
('CORE', 'HITuser', 'Neon', 'displayMainPage'),
('Themes', 'buildMenu', 'Neon', 'buildMenu'),
('Admin', 'ADMINNavbar', 'User', 'adminNavbar'),
('Admin', 'ADMINNavbar', 'Themes', 'adminNavbar'),
('Themes', 'buildMenu', 'Display', 'buildMenu'),
('Admin', 'ADMINFenfire', 'Fenfire', 'displayAdminPage'),
('Admin', 'PANELdisplay', 'Fenfire', 'displayPanel'),
('Admin', 'ADMINNavbar', 'Fenfire', 'adminNavbar');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ms_log`
--

INSERT INTO `ms_log` (`logID`, `subject`, `time`, `user`) VALUES
(1, 'Log cleared.', '1332795359', 1),
(2, 'Added field &#039;userfirstname&#039;', '1332799137', 1),
(3, 'Added field &#039;userlastname&#039;', '1332799151', 1),
(4, 'Updated user @1', '1332801088', 1),
(5, 'Module &#039;Liroli&#039; added.', '1332884737', 1),
(6, 'Module &#039;Display&#039; added.', '1332896480', 1),
(7, 'Hook Themes::buildMenu =&gt; Display::buildMenu added.', '1332896505', 1),
(8, 'Module &#039;Fenfire&#039; added.', '1332937555', 1),
(9, 'Hook Admin::ADMINFenfire =&gt; Fenfire::displayAdminPage added.', '1332937673', 1),
(10, 'Hook Admin::PANELdisplay =&gt; Fenfire::displayPanel added.', '1332937689', 1),
(11, 'Hook Admin::ADMINNavbar =&gt; Fenfire::adminNavbar added.', '1332937781', 1);

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

INSERT INTO `ms_modules` (`name`, `subject`) VALUES
('Ace', 'Provides a simple interface for the Ace text editor component.'),
('Admin', 'Allows administration of CORE models and provides an interface for module specific configuration pages.'),
('Auth', 'Provides a secure session and authentication system, as well as permission management.'),
('CORE', 'This is the CORE module, providing the INIT system.'),
('Display', 'A gallery module with per-user gallery support.'),
('Fenfire', 'Provides a simple comment system.'),
('Liroli', 'Public user groups'),
('Neon', 'Provides user front-end.'),
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

INSERT INTO `ms_options` (`key`, `value`, `type`) VALUES
('akismet_key', '9fce28faba37', 's'),
('avatar_maxdim', '150', 'i'),
('avatar_maxsize', '500', 'i'),
('cookie_life_h', '8544', 'i'),
('default_theme', 'default', 's'),
('idiots', 'retards', 's'),
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
  `action` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_timer`
--

INSERT INTO `ms_timer` (`IP`, `time`, `action`) VALUES
('127.0.0.1', 1332009384, 'visit:'),
('127.0.0.1', 1332798331, 'visit'),
('127.0.0.1', 1333023204, 'visit:1');

-- --------------------------------------------------------

--
-- Table structure for table `neon_friends`
--

CREATE TABLE IF NOT EXISTS `neon_friends` (
  `uID1` int(11) NOT NULL,
  `uID2` int(11) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'r',
  PRIMARY KEY (`uID1`,`uID2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ud_fields`
--

CREATE TABLE IF NOT EXISTS `ud_fields` (
  `varname` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  `default` text NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 's',
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  `displayed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`varname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ud_fields`
--

INSERT INTO `ud_fields` (`varname`, `title`, `default`, `type`, `editable`, `displayed`) VALUES
('web', 'Website', ' ', 'u', 1, 1),
('aboutuser', 'About', ' ', 't', 1, 1),
('birthdate', 'Birthdate', ' ', 'd', 1, 1),
('userfirstname', 'First name', ' ', 's', 1, 1),
('userlastname', 'Last name', ' ', 's', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ud_field_values`
--

CREATE TABLE IF NOT EXISTS `ud_field_values` (
  `varname` varchar(32) NOT NULL,
  `userID` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`varname`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ud_field_values`
--

INSERT INTO `ud_field_values` (`varname`, `userID`, `value`) VALUES
('birthdate', 1, '18.11.1993'),
('web', 1, 'http://shinmera.com'),
('aboutuser', 1, 'I make stuff.\r\nSome of it is kinda cool.\r\n\r\nVery important trufax about me:\r\nMithent is my waifu.'),
('userfirstname', 1, 'Nicolas'),
('userlastname', 1, 'Hafner');

-- --------------------------------------------------------

--
-- Table structure for table `ud_groups`
--

CREATE TABLE IF NOT EXISTS `ud_groups` (
  `title` varchar(35) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ud_groups`
--

INSERT INTO `ud_groups` (`title`, `permissions`) VALUES
('root', '*.*'),
('Unregistered', 'base.*\r\nuser.profile.*');

-- --------------------------------------------------------

--
-- Table structure for table `ud_permissions`
--

CREATE TABLE IF NOT EXISTS `ud_permissions` (
  `UID` bigint(20) NOT NULL,
  `tree` text NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ud_permissions`
--

INSERT INTO `ud_permissions` (`UID`, `tree`) VALUES
(1, '');

-- --------------------------------------------------------

--
-- Table structure for table `ud_users`
--

CREATE TABLE IF NOT EXISTS `ud_users` (
  `userID` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `mail` varchar(35) NOT NULL,
  `password` varchar(128) NOT NULL,
  `secret` varchar(128) NOT NULL,
  `displayname` varchar(32) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `group` varchar(32) NOT NULL,
  `status` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `displayname` (`displayname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ud_users`
--

INSERT INTO `ud_users` (`userID`, `username`, `mail`, `password`, `secret`, `displayname`, `filename`, `group`, `status`, `time`) VALUES
(1, 'Shinmera', 'nhafner@gmx.ch', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e', 'wwhatever', 'Mona', '/Shinmera-gahh4.png', 'root', 'a', 0),
(2, 'McDick', 'lol@dongs.com', '0a24d5ec1aedfb705ed8f67a4cbccac8c0262640eae7b2a72052b4378dd576c665d413689f9538e06d6217d605ad80ece53142f44e209a6cbe66d60ff0a502f3', 'b6kyP3l53rQZ3u73gX8oNvAi02G7gPH', 'Dicks', '', 'Unregistered', 'i', 1332582153);
