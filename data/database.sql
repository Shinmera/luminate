-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 09, 2012 at 04:15 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

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
-- Table structure for table `display_folders`
--

CREATE TABLE IF NOT EXISTS `display_folders` (
  `folder` varchar(128) NOT NULL,
  `pictures` text NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`folder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `display_pictures`
--

CREATE TABLE IF NOT EXISTS `display_pictures` (
  `pictureID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder` varchar(128) NOT NULL,
  `title` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `tags` varchar(256) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `user` varchar(32) NOT NULL,
  PRIMARY KEY (`pictureID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `tagcode` varchar(512) NOT NULL,
  `deftag` text NOT NULL,
  `description` varchar(64) DEFAULT NULL,
  `limit` int(11) NOT NULL DEFAULT '-1',
  `order` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lightup_tags`
--

INSERT INTO `lightup_tags` (`name`, `suite`, `tag`, `tagcode`, `deftag`, `description`, `limit`, `order`) VALUES
('Bold', 'default', 'b', 'b{@}', 'deftag(b){\r\n    tag(strong){print{content}}\r\n}', 'Bold text', -1, 0),
('Color', 'plus', 'color', 'color(&#36;Choose a colour|color&#36;){@}', 'deftag(color,color STRI true){\r\n    tag(span,:style color: get{color}){ print{content}}\r\n}', 'Change the font colour', -1, 4),
('Image', 'plus', 'img', 'img{@}', 'deftag(img,alt TEXT false,title TEXT false){\r\n    tag(img,:extra alt="get{alt}" title="get{title}" src="get{content}"){}\r\n}', 'Insert an image', -1, 5),
('Italic', 'default', 'i', 'i{@}', 'deftag(i){\r\n    tag(em){print{content}}\r\n}', 'Italic text', -1, 1),
('Quote', 'extra', 'quote', 'quote(&#36;user|string&#36;,&#36;urls|url&#36;){@}', 'deftag(quote,user STRI false,url URLS false){\r\n div(quote){\r\n   tag(h5){\r\n    if(get{user},false,!=){echo{By: }url(http://user.linuz.com/Luminate/get{user}){print{user}}}\r\n    if(get{url},false,!=){url(get{url}){echo{↗}}}\r\n   }\r\n   tag(p){print{content}}\r\n  }\r\n}', 'Quote a post', -1, 11),
('Size', 'plus', 'size', 'size(&#36;Enter the font size|number&#36;){@}', 'deftag(size,size INTE46 false 12){\r\n   tag(span,:style font-size: get{size} pt;){print{content}}\r\n}', 'Change the font size', -1, 8),
('Url', 'plus', 'url', 'url(&#36;Enter the URL|url&#36;){@}', 'deftag(url,url URLS false NIL,title TEXT false,target TEXT false _blank){\r\n    if(get{url},''NIL'',==){set(url){get{content}}}\r\n    tag(a,:extra href="get{url}" title="get{title}" target="get{target}"){print{content}}\r\n}', 'Insert a hyperlink', -1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `lore_actions`
--

CREATE TABLE IF NOT EXISTS `lore_actions` (
  `title` varchar(128) NOT NULL,
  `type` varchar(1) NOT NULL,
  `action` varchar(16) NOT NULL,
  `args` varchar(32) NOT NULL,
  `reason` varchar(256) NOT NULL,
  `editor` varchar(32) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lore_actions`
--

INSERT INTO `lore_actions` (`title`, `type`, `action`, `args`, `reason`, `editor`, `time`) VALUES
('Index', '', 'edit', '1', 'Need an index page.', 'Shinmera', 1336374874),
('Index', '', 'status', 'l', 'Locked it.', 'Shinmera', 1336374889),
('test', '', 'edit', '1', 'TESTING FOR TEST PURPOSES', 'Shinmera', 1336596327),
('test', '', 'edit', '2', 'Category.', 'Shinmera', 1336597146),
('cat', '', 'edit', '1', 'cat.', 'Shinmera', 1336597240),
('cat', '', 'type', 'c', 'Category.', 'Shinmera', 1336597249),
('Index', '', 'edit', '2', 'CAAAT!', 'Shinmera', 1336597470),
('cat', '', 'edit', '2', 'TOOT', 'Shinmera', 1336598646),
('cat', '', 'edit', '3', ':I', 'Shinmera', 1336598732),
('cat', '', 'edit', '4', ':I', 'Shinmera', 1336598752),
('lol', '', 'type', 'a', 'Removed unnecessary empty args', 'Shinmera', 1337872363),
('lol', '', 'edit', '2', 'Removed unnecessary empty args', 'Shinmera', 1337872363),
('lol', '', 'edit', '3', 'WHoops, I accidentally the type.', 'Shinmera', 1337872459),
('xDD', '', 'edit', '1', 'Shit article to test template inclusions', 'Shinmera', 1337872801),
('lol', '', 'type', 't', ':I', 'Shinmera', 1337874944),
('lol', '', 'edit', '4', ':I', 'Shinmera', 1337874944),
('xDD', '', 'edit', '2', 'More shit', 'Shinmera', 1337876131),
('lol', '', 'edit', '5', 'Update', 'Shinmera', 1337876293),
('xDD', '', 'edit', '3', 'Update', 'Shinmera', 1337876342),
('xDD', '', 'edit', '4', 'Derp', 'Shinmera', 1337876361),
('lol', '', 'edit', '6', 'Updated sidescroll', 'Shinmera', 1338040026),
('xDD', '', 'edit', '5', 'Duuh', 'Shinmera', 1338040054),
('lol', '', 'edit', '7', 'fixed sidescroll shit', 'Shinmera', 1338040632),
('lol', '', 'edit', '8', 'fixed sidescroll shit', 'Shinmera', 1338040656),
('xDD', '', 'edit', '6', 'Dicks', 'Shinmera', 1338041033),
('xDD', '', 'edit', '7', 'And so I did.', 'Shinmera', 1338041240),
('xDD', '', 'edit', '8', 'a', 'Shinmera', 1338041271),
('xDD', '', 'edit', '9', 'da', 'Shinmera', 1338041375),
('footnotes', '', 'edit', '1', 'Initial ', 'Shinmera', 1338045840),
('footnotes', '', 'edit', '2', 'Update to new standards', 'Shinmera', 1338046177),
('xDD', '', 'edit', '10', 'LOL', 'Shinmera', 1338047380),
('lol', '', 'edit', '9', 'Fixed rainbow', 'Shinmera', 1338047596),
('lol', '', 'edit', '10', 'Fixed rainbow', 'Shinmera', 1338049137),
('xDD', '', 'edit', '11', 'Fixed.', 'Shinmera', 1338154081),
('footnotes', '', 'edit', '3', 'TEST', 'Shinmera', 1338219605),
('footnotes', '', 'edit', '3', 'TEST', 'Shinmera', 1338219691),
('footnotes', '', 'edit', '3', 'TEST', 'Shinmera', 1338219731),
('footnotes', '', 'edit', '3', 'TEST', 'Shinmera', 1338219753),
('footnotes', '', 'edit', '4', 'TEST', 'Shinmera', 1338219835),
('', '', 'type', 'a', 'Initial', 'Shinmera', 1338224117),
('', '', 'edit', '1', 'Initial', 'Shinmera', 1338224117),
('', 'f', 'edit', '1', 'Initial', 'Shinmera', 1338224265),
('', 'f', 'edit', '1', 'Initial', 'Shinmera', 1338224405),
('love', 'f', 'edit', '1', 'Initial', 'Shinmera', 1338225812),
('love', 'f', 'edit', '2', 'Pix fix.', 'Shinmera', 1338226035),
('love', 'f', 'edit', '3', 'l', 'Shinmera', 1338226991),
('love', 'f', 'edit', '4', 'Pic update', 'Shinmera', 1338240744),
('love', 'f', 'edit', '5', '..', 'Shinmera', 1338240933),
('love', 'f', 'edit', '6', 'FINALLY GOD', 'Shinmera', 1338241399);

-- --------------------------------------------------------

--
-- Table structure for table `lore_articles`
--

CREATE TABLE IF NOT EXISTS `lore_articles` (
  `title` varchar(128) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'o',
  `revision` int(11) NOT NULL,
  `current` text NOT NULL,
  `time` int(11) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'o',
  PRIMARY KEY (`title`,`type`),
  FULLTEXT KEY `current` (`current`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lore_articles`
--

INSERT INTO `lore_articles` (`title`, `type`, `revision`, `current`, `time`, `status`) VALUES
('Index', 'a', 2, 'Welcome to the TymoonNET wiki.\r\nThis wiki has some quite fantastic articles.\r\nYeah.\r\n\r\n\r\nWell actually, it would have if this site had any users.\r\nLOL.\r\n\r\n{category:cat}', 1336374874, 'l'),
('test', 'a', 2, 'LOLWUT [Derp|THIS] IS A SHITTY TEST PAGE\r\n#!history\r\n{category:cat}', 1336596327, 'o'),
('cat', 'c', 4, '#!noparse\r\nDERRRPYYYY &gt;cat|CATEGORYYYYY&lt; YAAAAAAAAAAAAAAAAAAAAAAAY\r\n[test this stuff!]', 1336597240, 'o'),
('lol', 't', 10, 'deftag(rainbow){\r\n   set(c,0){&#039;#FF0000&#039;}\r\n   set(c,1){&#039;#FF7F00&#039;}\r\n   set(c,2){&#039;#FFFF00&#039;}\r\n   set(c,3){&#039;#00FF00&#039;}\r\n   set(c,4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    each(c){\r\n     tag(div,:style color: get{item};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll,scale INTE20 false 10,left BOOL false 0){\r\n   set(pos){&#039;right:0&#039;}\r\n   if(get{left},true){set(pos){&#039;left:0&#039;}}\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;get{pos};bottom:0;z-index:100;width:get{scale}%;){}\r\n}', 0, 'o'),
('xDD', 'a', 11, '#!include:lol\r\n#!include:footnotes\r\nalertbox([You] should do something about it footnote{Or just kill yourself. That works too.}){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{████████████}\r\nsidescroll(15){http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}\r\n\r\nhttp://lol.com\r\nwww.tymoon.eu\r\nhttp://youtube.com/?v=DiCKS\r\nLOL\r\nWut\r\nfootnotes{}', 1337872801, 'o'),
('footnotes', 't', 4, 'deftag(footnote){\r\n  set(*footnotecount){math(get{*footnotecount},1){+}}\r\n  set(*footnotetext,get{*footnotecount}){get{content}}\r\n  tag(sup){\r\n    echo{[}\r\n    tag(a,:extra href=&quot;#foot-get{*footnotecount}&quot;){print{*footnotecount}}\r\n    echo{]}\r\n  }\r\n}\r\n\r\ndeftag(footnotetext,n INTE true){\r\n  set(*footnotetext,get{n}){get{content}}\r\n}\r\n\r\ndeftag(footnotes){\r\n  div(footnotes){\r\n    each(*footnotetext){\r\n      div(footnote){\r\n        tag(sup){\r\n          echo{[}\r\n            tag(a,:extra href=&quot;#foot-get{pos}&quot;){print{pos}}\r\n            echo{]}\r\n        }\r\n        print{item}\r\n      }\r\n    }\r\n  }\r\n}', 1338045840, 'o'),
('', 'a', 1, 'Heee heee.', 1338224117, 'o'),
('', 'f', 1, 'Hee heeee', 1338224265, 'o'),
('love', 'f', 6, 'Hee heee :I', 1338225812, 'o');

-- --------------------------------------------------------

--
-- Table structure for table `lore_categories`
--

CREATE TABLE IF NOT EXISTS `lore_categories` (
  `title` varchar(128) NOT NULL,
  `article` varchar(128) NOT NULL,
  PRIMARY KEY (`title`,`article`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lore_categories`
--

INSERT INTO `lore_categories` (`title`, `article`) VALUES
('cat', 'Index'),
('cat', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `lore_revisions`
--

CREATE TABLE IF NOT EXISTS `lore_revisions` (
  `title` varchar(128) NOT NULL,
  `type` varchar(1) NOT NULL,
  `revision` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `editor` varchar(32) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`title`,`type`,`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `lore_revisions`
--

INSERT INTO `lore_revisions` (`title`, `type`, `revision`, `text`, `editor`, `time`) VALUES
('Index', '', 1, 'Welcome to the TymoonNET wiki.\r\nThis wiki has some quite fantastic articles.\r\nYeah.\r\n\r\n\r\nWell actually, it would have if this site had any users.\r\nLOL.', 'Shinmera', 1336374874),
('test', '', 1, 'LOLWUT THIS IS A SHITTY TEST PAGE\r\n#!history', 'Shinmera', 1336596327),
('test', '', 2, 'LOLWUT [Derp|THIS] IS A SHITTY TEST PAGE\r\n#!history\r\n{category:cat}', 'Shinmera', 1336597146),
('cat', '', 1, 'DERRRPYYYY CATEGORYYYYY YAAAAAAAAAAAAAAAAAAAAAAAY', 'Shinmera', 1336597240),
('Index', '', 2, 'Welcome to the TymoonNET wiki.\r\nThis wiki has some quite fantastic articles.\r\nYeah.\r\n\r\n\r\nWell actually, it would have if this site had any users.\r\nLOL.\r\n\r\n{category:cat}', 'Shinmera', 1336597470),
('cat', '', 2, '#!noparse\r\nDERRRPYYYY &gt;CATEGORYYYYY|cat&lt; YAAAAAAAAAAAAAAAAAAAAAAAY\r\n[test this stuff!]', 'Shinmera', 1336598646),
('cat', '', 3, '#!noparse\r\nDERRRPYYYY &gt;cat|CATEGORYYYYY&lt; YAAAAAAAAAAAAAAAAAAAAAAAY\r\n[test this stuff!]', 'Shinmera', 1336598732),
('cat', '', 4, '#!noparse\r\nDERRRPYYYY &gt;cat|CATEGORYYYYY&lt; YAAAAAAAAAAAAAAAAAAAAAAAY\r\n[test this stuff!]', 'Shinmera', 1336598752),
('lol', '', 2, 'deftag(rainbow){\r\n   set(0){&#039;#FF0000&#039;}\r\n   set(1){&#039;#FF7F00&#039;}\r\n   set(2){&#039;#FFFF00&#039;}\r\n   set(3){&#039;#00FF00&#039;}\r\n   set(4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    loop(5){\r\n     set(color){get{get{loop}}}\r\n     tag(div,:style color: get{color};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll){\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;right:0;bottom:20px;){}\r\n}', 'Shinmera', 1337872364),
('lol', '', 3, 'deftag(rainbow){\r\n   set(0){&#039;#FF0000&#039;}\r\n   set(1){&#039;#FF7F00&#039;}\r\n   set(2){&#039;#FFFF00&#039;}\r\n   set(3){&#039;#00FF00&#039;}\r\n   set(4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    loop(5){\r\n     set(color){get{get{loop}}}\r\n     tag(div,:style color: get{color};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll){\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;right:0;bottom:20px;){}\r\n}', 'Shinmera', 1337872459),
('xDD', '', 1, '{include:lol}\r\n\r\nsidescroll{http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}', 'Shinmera', 1337872801),
('lol', '', 4, 'deftag(rainbow){\r\n   set(0){&#039;#FF0000&#039;}\r\n   set(1){&#039;#FF7F00&#039;}\r\n   set(2){&#039;#FFFF00&#039;}\r\n   set(3){&#039;#00FF00&#039;}\r\n   set(4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    loop(5){\r\n     set(color){get{get{loop}}}\r\n     tag(div,:style color: get{color};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll){\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;right:0;bottom:20px;){}\r\n}', 'Shinmera', 1337874945),
('xDD', '', 2, '#!include:lol\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{██████████████}\r\nsidescroll{http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}', 'Shinmera', 1337876132),
('lol', '', 5, 'deftag(rainbow){\r\n   set(0){&#039;#FF0000&#039;}\r\n   set(1){&#039;#FF7F00&#039;}\r\n   set(2){&#039;#FFFF00&#039;}\r\n   set(3){&#039;#00FF00&#039;}\r\n   set(4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    loop(5){\r\n     set(color){get{get{loop}}}\r\n     tag(div,:style color: get{color};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll){\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;right:0;bottom:20px;z-index:100){}\r\n}', 'Shinmera', 1337876293),
('xDD', '', 3, '#!include:lol\r\nalert([you] should do something about it){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{██████████████}\r\nsidescroll{http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}', 'Shinmera', 1337876342),
('xDD', '', 4, '#!include:lol\r\nalertbox([you] should do something about it){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{██████████████}\r\nsidescroll{http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}', 'Shinmera', 1337876361),
('lol', '', 6, 'deftag(rainbow){\r\n   set(0){&#039;#FF0000&#039;}\r\n   set(1){&#039;#FF7F00&#039;}\r\n   set(2){&#039;#FFFF00&#039;}\r\n   set(3){&#039;#00FF00&#039;}\r\n   set(4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    loop(5){\r\n     set(color){get{get{loop}}}\r\n     tag(div,:style color: get{color};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll,scale INTE100 false 100,left BOOL false 0){\r\n   set(size){&#039;width:get{scale}%;height:get{scale}%;&#039;}\r\n   set(pos){&#039;right:0;&#039;}\r\n   if(get{left},true){set(pos){&#039;left:0;&#039;}}\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;get{pos}bottom:20px;z-index:100;get{size}){}\r\n}', 'Shinmera', 1338040026),
('xDD', '', 5, '#!include:lol\r\nalertbox([you] should do something about it){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{██████████████}\r\nsidescroll(20){http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}', 'Shinmera', 1338040054),
('lol', '', 7, 'deftag(rainbow){\r\n   set(0){&#039;#FF0000&#039;}\r\n   set(1){&#039;#FF7F00&#039;}\r\n   set(2){&#039;#FFFF00&#039;}\r\n   set(3){&#039;#00FF00&#039;}\r\n   set(4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    loop(5){\r\n     set(color){get{get{loop}}}\r\n     tag(div,:style color: get{color};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll,scale INTE20 false 10,left BOOL false 0){\r\n   set(pos){&#039;right:0&#039;}\r\n   if(get{left},true){set(pos){&#039;left:0&#039;}}\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;get{pos};bottom:20px;z-index:100;width:get{scale}%;){}\r\n}', 'Shinmera', 1338040632),
('lol', '', 8, 'deftag(rainbow){\r\n   set(0){&#039;#FF0000&#039;}\r\n   set(1){&#039;#FF7F00&#039;}\r\n   set(2){&#039;#FFFF00&#039;}\r\n   set(3){&#039;#00FF00&#039;}\r\n   set(4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    loop(5){\r\n     set(color){get{get{loop}}}\r\n     tag(div,:style color: get{color};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll,scale INTE20 false 10,left BOOL false 0){\r\n   set(pos){&#039;right:0&#039;}\r\n   if(get{left},true){set(pos){&#039;left:0&#039;}}\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;get{pos};bottom:0;z-index:100;width:get{scale}%;){}\r\n}', 'Shinmera', 1338040656),
('xDD', '', 6, '#!include:lol\r\nalertbox([you] should do something about it){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{██████████████}\r\nsidescroll(10){http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}', 'Shinmera', 1338041033),
('xDD', '', 7, '#!include:lol\r\nalertbox([you] should do something about it){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{██████████████}\r\nsidescroll(15){http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}\r\n\r\nhttp://lol.com\r\nwww.tymoon.eu\r\nhttp://youtube.com/?v=DiCKS', 'Shinmera', 1338041240),
('xDD', '', 8, '#!include:lol\r\nalertbox([you] should do something about it){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{██████████████}\r\nsidescroll(15){http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}\r\n\r\nhttp://lol.com\r\nwww.tymoon.eu\r\nhttp://youtube.com/?v=DiCKS\r\nLOL\r\nWut', 'Shinmera', 1338041271),
('xDD', '', 9, '#!include:lol\r\nalertbox([you] should do something about it){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{█L█O█L█ █R█A█N█D█U█M█B█}\r\nsidescroll(15){http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}\r\n\r\nhttp://lol.com\r\nwww.tymoon.eu\r\nhttp://youtube.com/?v=DiCKS\r\nLOL\r\nWut', 'Shinmera', 1338041375),
('footnotes', '', 1, 'deftag(footnote){\r\n  set(*footnotecount){math(get{*footnotecount},1){+}}\r\n  set(*footnotetext,get{*footnotecount}){get{content}}\r\n  tag(sup){\r\n    echo{[}\r\n    tag(a,:extra href=&quot;#foot-get{*footnotecount}&quot;){print{*footnotecount}}\r\n    echo{]}\r\n  }\r\n}\r\n\r\ndeftag(footnotetext,n INTE true){\r\n  set(*footnotetext,get{n}){get{content}}\r\n}\r\n\r\ndeftag(footnotes){\r\n  set(i){0}\r\n  div(footnotes){\r\n    each(*footnotetext){\r\n      set(i){math(get{i},1){+}}\r\n      div(footnote){\r\n        tag(sup){\r\n          echo{[}\r\n            tag(a,:extra href=&quot;#foot-get{i}&quot;){print{i}}\r\n            echo{]}\r\n        }\r\n        print{item}\r\n      }\r\n    }\r\n  }\r\n}', 'Shinmera', 1338045840),
('footnotes', '', 2, 'deftag(footnote){\r\n  set(*footnotecount){math(get{*footnotecount},1){+}}\r\n  set(*footnotetext,get{*footnotecount}){get{content}}\r\n  tag(sup){\r\n    echo{[}\r\n    tag(a,:extra href=&quot;#foot-get{*footnotecount}&quot;){print{*footnotecount}}\r\n    echo{]}\r\n  }\r\n}\r\n\r\ndeftag(footnotetext,n INTE true){\r\n  set(*footnotetext,get{n}){get{content}}\r\n}\r\n\r\ndeftag(footnotes){\r\n  div(footnotes){\r\n    each(*footnotetext){\r\n      div(footnote){\r\n        tag(sup){\r\n          echo{[}\r\n            tag(a,:extra href=&quot;#foot-get{pos}&quot;){print{pos}}\r\n            echo{]}\r\n        }\r\n        print{item}\r\n      }\r\n    }\r\n  }\r\n}', 'Shinmera', 1338046177),
('xDD', '', 10, '#!include:lol\r\n#!include:footnotes\r\nalertbox([you] should do something about it footnote{Or just kill yourself. That works too.}){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{█L█O█L█ █R█A█N█D█U█M█B█}\r\nsidescroll(15){http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}\r\n\r\nhttp://lol.com\r\nwww.tymoon.eu\r\nhttp://youtube.com/?v=DiCKS\r\nLOL\r\nWut\r\nfootnotes{}', 'Shinmera', 1338047380),
('lol', '', 9, 'deftag(rainbow){\r\n   set(c,0){&#039;#FF0000&#039;}\r\n   set(c,1){&#039;#FF7F00&#039;}\r\n   set(c,2){&#039;#FFFF00&#039;}\r\n   set(c,3){&#039;#00FF00&#039;}\r\n   set(c,4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    loop(5){\r\n     tag(div,:style color: get(pos){c};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll,scale INTE20 false 10,left BOOL false 0){\r\n   set(pos){&#039;right:0&#039;}\r\n   if(get{left},true){set(pos){&#039;left:0&#039;}}\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;get{pos};bottom:0;z-index:100;width:get{scale}%;){}\r\n}', 'Shinmera', 1338047596),
('lol', '', 10, 'deftag(rainbow){\r\n   set(c,0){&#039;#FF0000&#039;}\r\n   set(c,1){&#039;#FF7F00&#039;}\r\n   set(c,2){&#039;#FFFF00&#039;}\r\n   set(c,3){&#039;#00FF00&#039;}\r\n   set(c,4){&#039;#0000FF&#039;}\r\n   tag(div,:style padding-top:40px;){\r\n    each(c){\r\n     tag(div,:style color: get{item};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}\r\n\r\ndeftag(alertbox,subtext TEXT false){\r\n  div(box,:style margin:20px){\r\n    tag(img,:extra src=&quot;http://www.nativevillage.org/Archives/2011%20Archives/DEC%202011%20News/warning-animated.gif&quot;,:style float:left){}\r\n    div(:style text-align:center; font-size: 18pt; font-weight: bold;){echo{Alert: }print{content}}\r\n    div(:style margin-left: 20%;margin-right:20%;){print{subtext}}\r\n    tag(br,:class clear){}\r\n  }\r\n}\r\n\r\ndeftag(sidescroll,scale INTE20 false 10,left BOOL false 0){\r\n   set(pos){&#039;right:0&#039;}\r\n   if(get{left},true){set(pos){&#039;left:0&#039;}}\r\n   tag(img,:extra src=&quot;get{content}&quot;,:style position:fixed;get{pos};bottom:0;z-index:100;width:get{scale}%;){}\r\n}', 'Shinmera', 1338049137),
('xDD', '', 11, '#!include:lol\r\n#!include:footnotes\r\nalertbox([You] should do something about it footnote{Or just kill yourself. That works too.}){This page is shit}\r\nrainbow{SIXTY NIGGERS!}\r\nrainbow{████████████}\r\nsidescroll(15){http://img.tymoon.eu/img/suiseiseki/Crossover/a34063f4b25b4aaaf21e5c8d3b01767e.jpg}\r\n\r\nhttp://lol.com\r\nwww.tymoon.eu\r\nhttp://youtube.com/?v=DiCKS\r\nLOL\r\nWut\r\nfootnotes{}', 'Shinmera', 1338154081),
('footnotes', '', 3, 'deftag(footnote){\r\n  set(*footnotecount){math(get{*footnotecount},1){+}}\r\n  set(*footnotetext,get{*footnotecount}){get{content}}\r\n  tag(sup){\r\n    echo{[}\r\n    tag(a,:extra href=&quot;#foot-get{*footnotecount}&quot;){print{*footnotecount}}\r\n    echo{]}\r\n  }\r\n}\r\n\r\ndeftag(footnotetext,n INTE true){\r\n  set(*footnotetext,get{n}){get{content}}\r\n}\r\n\r\ndeftag(footnotes){\r\n  div(footnotes){\r\n    each(*footnotetext){\r\n      div(footnote){\r\n        tag(sup){\r\n          echo{[}\r\n            tag(a,:extra href=&quot;#foot-get{pos}&quot;){print{pos}}\r\n            echo{]}\r\n        }\r\n        print{item}\r\n      }\r\n    }\r\n  }\r\n}', 'Shinmera', 1338219605),
('footnotes', 't', 4, 'deftag(footnote){\r\n  set(*footnotecount){math(get{*footnotecount},1){+}}\r\n  set(*footnotetext,get{*footnotecount}){get{content}}\r\n  tag(sup){\r\n    echo{[}\r\n    tag(a,:extra href=&quot;#foot-get{*footnotecount}&quot;){print{*footnotecount}}\r\n    echo{]}\r\n  }\r\n}\r\n\r\ndeftag(footnotetext,n INTE true){\r\n  set(*footnotetext,get{n}){get{content}}\r\n}\r\n\r\ndeftag(footnotes){\r\n  div(footnotes){\r\n    each(*footnotetext){\r\n      div(footnote){\r\n        tag(sup){\r\n          echo{[}\r\n            tag(a,:extra href=&quot;#foot-get{pos}&quot;){print{pos}}\r\n            echo{]}\r\n        }\r\n        print{item}\r\n      }\r\n    }\r\n  }\r\n}', 'Shinmera', 1338219835),
('', 'a', 1, 'Heee heee.', 'Shinmera', 1338224117),
('', 'f', 1, 'Hee heeee', 'Shinmera', 1338224265),
('love', 'f', 1, '&lt;3', 'Shinmera', 1338225812),
('love', 'f', 2, '&lt;3', 'Shinmera', 1338226035),
('love', 'f', 3, 'Hee heee', 'Shinmera', 1338226991),
('love', 'f', 4, 'Hee heee :3', 'Shinmera', 1338240744),
('love', 'f', 5, 'Hee heee', 'Shinmera', 1338240933),
('love', 'f', 6, 'Hee heee :I', 'Shinmera', 1338241399);

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
('CORE', 'HITtest', 'Test', 'runTests'),
('Admin', 'PANELdisplay', 'Admin', 'displayPanel'),
('Admin', 'ADMINAdmin', 'Admin', 'displayAdminPage'),
('CORE', 'HITwiki', 'Lore', 'displayPage'),
('CORE', 'APIlightupCUSTOM', 'LightUp', 'displayApiCustomParse'),
('CORE', 'APILoreParse', 'Lore', 'displayApiParse'),
('CORE', 'HITgallery', 'Display', 'displayPage');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

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
(24, 'Hook CORE::HITtest =&gt; Test::runTests added.', 1335636165, 1),
(25, 'Module &#039;Lore&#039; added.', 1336373676, 1),
(26, 'Hook CORE::HITwiki =&gt; Lore::displayPage added.', 1336373734, 1),
(27, 'Hook CORE::APIlightupCUSTOM =&gt; LightUp::displayApiCustomParse added.', 1337761216, 1),
(28, 'Hook CORE::APILoreParse =&gt; Lore::displayApiParse added.', 1338148726, 1),
(29, 'Hook CORE::HITgallery =&gt; Display::displayPage added.', 1338717230, 1);

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
('Lore', 'Wiki'),
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
