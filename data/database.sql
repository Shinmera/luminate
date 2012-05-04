-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 30, 2012 at 02:50 PM
-- Server version: 5.5.23-log
-- PHP Version: 5.3.11

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
-- Table structure for table `derpy_messages`
--

CREATE TABLE IF NOT EXISTS `derpy_messages` (
  `messageID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(32) NOT NULL,
  `recipient` varchar(32) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'm',
  `title` varchar(64) NOT NULL DEFAULT 'No Subject',
  `text` text NOT NULL,
  `time` int(8) unsigned NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `derpy_messages`
--

INSERT INTO `derpy_messages` (`messageID`, `sender`, `recipient`, `type`, `title`, `text`, `time`, `read`) VALUES
(1, 'Shinmera', 'Shinmera', 'm', 'No Subject', 'A', 2012, 1),
(2, 'Shinmera', 'Shinmera', 'm', 'Re: No Subject', 'TESTER', 2012, 1),
(3, 'Shinmera', 'Shinmera', 'm', 'Re: Re: No Subject', 'Whatever man, fuck your shit.', 1334569759, 1),
(4, 'Shinmera', '', 'o', 'Hello you two', 'Heeeeyyyyy\r\nHow&#039;s it gooiiin&#039;', 1335395795, 1),
(5, 'Shinmera', '', 'o', 'Re: ', 'How are you doing tonight, darling?\r\nI&#039;m sorry for all the bugs, I should have really tested with an account that doesn&#039;t have all the privs first.\r\nI hope I can fix it all soon tomorrow.', 1335395939, 1),
(6, 'Shinmera', 'Shinmera', 'm', 'DICKS', 'img{http://img.tymoon.eu/img//bronies/derpy/13084238311013.jpg}', 1335396763, 1),
(7, 'Shinmera', 'Shinmera', 'o', 'DICKS', 'img{http://img.tymoon.eu/img//bronies/derpy/13084238311013.jpg}', 1335396763, 1),
(8, 'Shinmera', 'Shinmera', 'm', 'Re: ', 'DDD', 1335432690, 1),
(9, 'Shinmera', 'Faggot', 'm', 'Re: ', 'DDD', 1335432690, 0),
(10, 'Shinmera', '', 'o', 'Re: ', 'DDD', 1335432690, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fenfire_comments`
--

CREATE TABLE IF NOT EXISTS `fenfire_comments` (
  `commentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `FID` int(11) unsigned NOT NULL,
  `username` varchar(32) NOT NULL,
  `mail` varchar(32) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `level` tinyint(4) unsigned NOT NULL,
  `moderation` tinyint(1) NOT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `fenfire_comments`
--

INSERT INTO `fenfire_comments` (`commentID`, `FID`, `username`, `mail`, `text`, `time`, `level`, `moderation`) VALUES
(15, 90, 'Someone Else', 'nigger', 'LOL UR GHEY FOR MITHENT', 1333924316, 0, 0),
(16, 90, 'Shinmera', 'nhafner@gmx.ch', 'IKNORITE', 1333924490, 1, 0),
(17, 90, 'Shinmera', 'nhafner@gmx.ch', 'TEST', 1334818953, 0, 0),
(18, 90, 'Shinmera', 'nhafner@gmx.ch', 'ANOTHER TEST', 1334818969, 0, 0),
(19, 91, 'Shinmera', 'nhafner@gmx.ch', 'TESTOR', 1334820601, 0, 0),
(20, 91, 'Shinmera', 'nhafner@gmx.ch', 'TEST2', 1334820811, 0, 0),
(21, 91, 'Shinmera', 'nhafner@gmx.ch', 'AAAAAAA', 1334823218, 1, 0),
(22, 91, 'Shinmera', 'nhafner@gmx.ch', 'WHOOO', 1334823785, 2, 0),
(23, 91, 'Shinmera', 'nhafner@gmx.ch', 'MITHEEENT', 1334824258, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fenfire_folders`
--

CREATE TABLE IF NOT EXISTS `fenfire_folders` (
  `folderID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL,
  `path` varchar(64) NOT NULL,
  `order` text,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`folderID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

--
-- Dumping data for table `fenfire_folders`
--

INSERT INTO `fenfire_folders` (`folderID`, `module`, `path`, `order`, `open`) VALUES
(89, 'CORE', 'INDEX', ';0;9;1;0;7;10;8;2;3;4;5;6;11;12;13;14', 1),
(90, 'Neon', 'Shinmera', ';15;16;17;18', 1),
(91, 'User', 'Shinmera', ';19;20;21;22;23', 1),
(92, 'User', 'Faggot', NULL, 1),
(93, 'User', 'McDick', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lightup_suites`
--

CREATE TABLE IF NOT EXISTS `lightup_suites` (
  `name` varchar(16) NOT NULL,
  `module` varchar(32) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lightup_suites`
--

INSERT INTO `lightup_suites` (`name`, `module`) VALUES
('default', 'CORE'),
('extra', 'CORE'),
('plus', 'CORE'),
('pro', 'CORE');

-- --------------------------------------------------------

--
-- Table structure for table `lightup_tags`
--

CREATE TABLE IF NOT EXISTS `lightup_tags` (
  `name` varchar(32) NOT NULL,
  `suite` varchar(16) NOT NULL DEFAULT 'standard',
  `tag` varchar(16) NOT NULL,
  `femcode` varchar(256) NOT NULL,
  `tagcode` varchar(128) NOT NULL,
  `description` varchar(64) DEFAULT NULL,
  `limit` int(11) NOT NULL DEFAULT '-1',
  `order` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lightup_tags`
--

INSERT INTO `lightup_tags` (`name`, `suite`, `tag`, `femcode`, `tagcode`, `description`, `limit`, `order`) VALUES
('Bold', 'default', 'b', '&lt;strong&gt;@&lt;/strong&gt;', 'b{@}', 'Bold text', -1, 0),
('Center', 'plus', 'center', '&lt;div class=&quot;center&quot;&gt;@&lt;/div&gt;', 'center{@}', 'Center the text', -1, 3),
('Color', 'plus', 'color', '&lt;span style=&quot;color:&#36;STRI|red&#36;&gt;@&lt;/span&gt;', 'color(&#36;Choose a colour|color&#36;){@}', 'Change the font colour', -1, 4),
('Image', 'plus', 'image', '&lt;img alt=&quot;&#36;STRI|image&#36;&quot; title=&quot;&#36;TEXT|&#36;&quot; class=&quot;&#36;STRI|&#36;&quot; src=&quot;@&quo', 'img{@}', 'Insert an image', -1, 5),
('Italic', 'default', 'i', '&lt;em&gt;@&lt;/em&gt;', 'i{@}', 'Italic text', -1, 1),
('Left', 'plus', 'left', '&lt;div class=&quot;left&quot;&gt;@&lt;/div&gt;', 'left{@}', 'Align left', -1, 6),
('Noparse', 'pro', '!', 'COMPILED', '!{@}!', 'Stop text from being parsed', -1, 12),
('Paragraph', 'extra', 'p', '&lt;p&gt;@&lt;/p&gt;', 'p{@}', 'Create a text paragraph', -1, 10),
('Quote', 'extra', 'quote', '&lt;div class=&quot;quote&quot;&gt;&lt;h5&gt;Quote &#36;STRI|&#36; &#36;STRI|&#36;&lt;/h5&gt;@&lt;/div&gt;', 'quote{@}', 'Quote a post', -1, 11),
('Right', 'plus', 'right', '&lt;div class=&quot;right&quot;&gt;@&lt;/div&gt;', 'right{@}', 'Align right', -1, 7),
('Size', 'plus', 'size', '&lt;span style=&quot;font-size:&#36;INTE36|18&#36;pt&quot;&gt;@&lt;/span&gt;', 'size(&#36;Enter the font size|number&#36;){@}', 'Change the font size', -1, 8),
('Underline', 'default', 'u', '&lt;u&gt;@&lt;/u&gt;', 'u{@}', 'Underline text', -1, 2),
('Url', 'plus', 'url', '&lt;a href=&quot;&#36;URLS&#36;&quot; title=&quot;&#36;TEXT|&#36;&quot; target=&quot;&#36;STRI|_self&#36;&quot; &gt;@&lt;/a&gt;', 'url(&#36;Enter the URL|url&#36;){@}', 'Insert a hyperlink', -1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `lore_articles`
--

CREATE TABLE IF NOT EXISTS `lore_articles` (
  `title` varchar(128) NOT NULL,
  `revision` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `editor` varchar(32) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'o',
  `portal` varchar(128) NOT NULL,
  `categories` text NOT NULL,
  PRIMARY KEY (`title`,`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lore_categories`
--

CREATE TABLE IF NOT EXISTS `lore_categories` (
  `title` varchar(128) NOT NULL,
  `revision` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `editor` varchar(32) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'o',
  PRIMARY KEY (`title`,`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lore_files`
--

CREATE TABLE IF NOT EXISTS `lore_files` (
  `title` varchar(128) NOT NULL,
  `revision` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `filename` varchar(128) NOT NULL,
  `editor` varchar(32) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'o',
  PRIMARY KEY (`title`,`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lore_portals`
--

CREATE TABLE IF NOT EXISTS `lore_portals` (
  `title` varchar(128) NOT NULL,
  `revision` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `editor` varchar(32) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'o',
  PRIMARY KEY (`title`,`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lore_users`
--

CREATE TABLE IF NOT EXISTS `lore_users` (
  `editor` varchar(32) NOT NULL,
  `revision` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`editor`,`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
('Admin', 'ADMINNavbar', 'Fenfire', 'adminNavbar'),
('CORE', 'APISubmitComment', 'Fenfire', 'submitCommentForm'),
('CORE', 'APIparse', 'LightUp', 'displayApiPage'),
('Neon', 'PROFILEpage', 'Fenfire', 'commentSection'),
('User', 'buildMenu', 'Derpy', 'buildMenu'),
('User', 'SETTINGSNavbar', 'Derpy', 'userNavbar'),
('User', 'SETTINGSMessages', 'Derpy', 'displayMessagesPage'),
('CORE', 'PARSE', 'LightUp', 'deparse'),
('CORE', 'GETtags', 'LightUp', 'getTags'),
('Admin', 'PANELdisplay', 'LightUp', 'displayPanel'),
('Admin', 'ADMINLightUp', 'LightUp', 'displayAdminPage'),
('CORE', 'APILightUpTagOrder', 'LightUp', 'displayApiOrderPage'),
('Fenfire', 'POST', 'Derpy', 'handlePostHook'),
('CORE', 'APINOTIFICATIONdelete', 'Derpy', 'handleAPINotificationDelete'),
('CORE', 'APIUSERsearch', 'User', 'apiUserSearch'),
('CORE', 'HITtest', 'Test', 'runTests');

-- --------------------------------------------------------

--
-- Table structure for table `ms_log`
--

CREATE TABLE IF NOT EXISTS `ms_log` (
  `logID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subject` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `user` int(64) NOT NULL,
  PRIMARY KEY (`logID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `ms_log`
--

INSERT INTO `ms_log` (`logID`, `subject`, `time`, `user`) VALUES
(1, 'Log cleared.', 2012, 1),
(2, 'Updated user @1', 2012, 1),
(3, 'Updated user @1', 2012, 1),
(4, 'Updated user @1', 2012, 1),
(5, 'Updated user @1', 2012, 1),
(6, 'Updated user @1', 2012, 1),
(7, 'Updated user @1', 2012, 1),
(8, 'Updated user @1', 2012, 1),
(9, 'Updated user @1', 2012, 1),
(10, 'Hook Fenfire::POST =&gt; Derpy::handlePostHook added.', 1334818939, 1),
(11, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1334818954, 1),
(12, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1334818969, 1),
(13, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1334820602, 1),
(14, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1334820811, 1),
(15, 'Hook CORE::APINOTIFICATIONdelete =&gt; Derpy::handleAPINotificationDelete added.', 1334822921, 1),
(16, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1334823218, 1),
(17, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1334823785, 1),
(18, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1334824258, 1),
(19, 'Updated user @3', 1335432434, 1),
(20, 'Updated permissions for @3', 1335432447, 1),
(21, 'Added user @4', 1335432455, 1),
(22, 'Hook CORE::APIUSERsearch =&gt; User::apiUserSearch added.', 1335434322, 1),
(23, 'Module &#039;Test&#039; added.', 1335636138, 1),
(24, 'Hook CORE::HITtest =&gt; Test::runTests added.', 1335636165, 1);

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
('Derpy', 'Derpy Mail, a private user message system.'),
('Display', 'A gallery module with per-user gallery support.'),
('Fenfire', 'Provides a simple comment system.'),
('LightUp', 'BBCode and text formatting system '),
('Liroli', 'Public user groups'),
('Neon', 'Provides user front-end.'),
('Test', 'To run test suites.'),
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
  `time` int(10) unsigned NOT NULL,
  `action` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_timer`
--

INSERT INTO `ms_timer` (`IP`, `time`, `action`) VALUES
('127.0.0.1', 0, 'visit:'),
('127.0.0.1', 0, 'visit'),
('127.0.0.1', 0, 'visit:1'),
('127.0.0.1', 0, 'comment'),
('127.0.0.1', 1335395795, 'sendmessage');

-- --------------------------------------------------------

--
-- Table structure for table `neon_friends`
--

CREATE TABLE IF NOT EXISTS `neon_friends` (
  `uID1` int(11) unsigned NOT NULL,
  `uID2` int(11) unsigned NOT NULL,
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
  `userID` int(11) unsigned NOT NULL,
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
  `UID` bigint(20) unsigned NOT NULL,
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
  `userID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `mail` varchar(35) NOT NULL,
  `password` varchar(128) NOT NULL,
  `secret` varchar(32) NOT NULL,
  `displayname` varchar(32) NOT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `group` varchar(32) NOT NULL DEFAULT 'Registered',
  `status` varchar(1) NOT NULL DEFAULT 'u',
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `displayname` (`displayname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ud_users`
--

INSERT INTO `ud_users` (`userID`, `username`, `mail`, `password`, `secret`, `displayname`, `filename`, `group`, `status`, `time`) VALUES
(1, 'Shinmera', 'nhafner@gmx.ch', '9c9b7260d5e4d1fa396a1255ea82f0a879559c28f64a93d397ccaf2fcef3f09322ac23ff72095f24a4c99bb55696cfdafc409f39fcdcfda9b11da460f9bd5ae5', 'wwhatever', 'Mona', '/Shinmera-gahh4.png', 'root', 'a', 0),
(2, 'McDick', 'lol@dongs.com', '0a24d5ec1aedfb705ed8f67a4cbccac8c0262640eae7b2a72052b4378dd576c665d413689f9538e06d6217d605ad80ece53142f44e209a6cbe66d60ff0a502f3', 'b6kyP3l53rQZ3u73gX8oNvAi02G7gPH', 'Dicks', '', 'Unregistered', 'i', 0),
(3, 'Faggot', 'shinmera@tymoon.eu', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e', 'J73Xq6HrgF081Ql130e8v8l3349Jc06', 'Faggot', '', 'Registered', 'a', 1335212007),
(4, '', '', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e', '45KM04407K1i23J57U42fap5E1Tk823', '', '', '', 'i', 1335432455);
