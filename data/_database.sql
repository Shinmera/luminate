-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 06, 2012 at 10:41 PM
-- Server version: 5.5.28-log
-- PHP Version: 5.4.8

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
-- Table structure for table `ag_adventures`
--

CREATE TABLE IF NOT EXISTS `ag_adventures` (
  `adventureID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `subtitle` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `header` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`adventureID`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ag_chapters`
--

CREATE TABLE IF NOT EXISTS `ag_chapters` (
  `chapterID` int(10) unsigned NOT NULL,
  `AID` int(32) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `header` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`chapterID`,`AID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ag_panels`
--

CREATE TABLE IF NOT EXISTS `ag_panels` (
  `panelID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AID` int(10) unsigned NOT NULL,
  `CID` int(10) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `header` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `tags` text COLLATE utf8_unicode_ci NOT NULL,
  `open` tinyint(1) NOT NULL,
  PRIMARY KEY (`panelID`,`AID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ag_suggestions`
--

CREATE TABLE IF NOT EXISTS `ag_suggestions` (
  `suggestionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AID` int(10) unsigned NOT NULL,
  `PID` int(10) unsigned NOT NULL,
  `text` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  PRIMARY KEY (`suggestionID`,`AID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bl_entries`
--

CREATE TABLE IF NOT EXISTS `bl_entries` (
  `entryID` int(11) NOT NULL AUTO_INCREMENT,
  `FID` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `short` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `subject` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `tags` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `time` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `owner` int(11) NOT NULL,
  PRIMARY KEY (`entryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=60 ;

--
-- Dumping data for table `bl_entries`
--

INSERT IGNORE INTO `bl_entries` (`entryID`, `FID`, `title`, `short`, `subject`, `tags`, `time`, `published`, `owner`) VALUES
(7, 40, 'Punch Nick in the head because h', 'DO IT! >:U\r<br>\r<br>\r<br>Anyway, I don''t have much to say. Gonna work on the site some more today.\r<br>Most of my presents will tumble in later because of delivery shenanigans so I can''t say what kind of sw33t l00t I got yet.\r<br>I''ll VLog about it though as soon as I have everything.\r<br>\r<br>Also, apparently I''m not a good enough programmer to call myself "experienced": http://comments.deviantart.com/4/11322144/1746529207 \r<br>So yeah, apparently making a CMS and Chat Program equals a "Hello World" Program nowadays. Whadda I know.', '', '', '1290078820', 1, 1),
(9, 40, 'Sigh.', 'OK so.\r\nI got some apologies to make, both to you guys and to myself.\r\n\r\n[b]First apology:[/b]\r\nThis one is obvious. I mean. Seriously. I have schedule problems again.\r\nThe Tymoon comic is seriously not being continued as it should be and I&#039;m really sorry for that.\r\nI don&#039;t know why  I can&#039;t even make two comics a week.\r\nAgh. I&#039;ll have to find a way to solve this problem because it&#039;s bugging me really bad.\r\n\r\n[b]Second apology:[/b]\r\nI&#039;ve also been lazy on Spherae as well. I wanted to get shit done with this and finally get it rolling but what do I do?\r\nJackshit.\r\n&gt;.&lt;\r\n\r\n[b]Third apology:[/b]\r\nServer issues and stuff. This isn&#039;t really something I directly should apologize for but I will nevertheless because I&#039;m sort of responsible for making backups. Which I obviously didn&#039;t. So yeah, I&#039;m really sorry for all the lost comments, blogs, bug reports, forum posts and so on. I&#039;m also sorry for the server&#039;s instability lately.\r\n\r\n[b]Fourth apology:[/b]\r\nThis is more for myself but I&#039;ll write about it anyway:\r\nI haven&#039;t done much schoolwork either lately. Which makes me angry since this is of high importance now and I still waste time on useless shit.\r\n\r\nSo yeah. That&#039;s it.\r\nNow I&#039;ll... I&#039;ll just go over to that corner there and be all miserable because I&#039;m so screwed for the Maths exam on Wednesday.\r\nYeah that&#039;s all I got.\r\n\r\n--&gt;Nick away!\r\n\r\n[youtube]uZ_3Zoud3po[/youtube]', '', '', '1290464891', 1, 1),
(10, 40, 'How To Draw', 'Hey guys, here''s the one and only tutorial that teaches you everything about drawing.\r<br>Just follow these simple steps:\r<br>\r<br>You ready? A''right, here goes:\r<br>\r<br>1) Get pencil\r<br>2) Get paper (lots of it)\r<br>3) Sit down\r<br>4) ???\r<br>5) PROFIT!\r<br>', '', '', '1290892312', 1, 1),
(12, 40, 'd-d-d-d-d-d-depression wave!', '[b]I SUCK.[/b]\r\n\r\n[words]\r\n\r\nmore tomorrow.\r\n\r\n[b]. . . [/b]BEGONE!\r\n\r\n[youtube]iRYvuS9OxdA[/youtube]', '', '', '1291415823', 1, 1),
(13, 40, 'Update', '[b]Schedule change:[/b]\r<br>I''ll try (emphasis on try) to make the Ty comic on Monday,Wednesday and Friday again.\r<br>Wish me luck.\r<br>\r<br>[b]Tracker update:[/b]\r<br>The tracker now has bugs, requests, questions and praise tickets.\r<br>\r<br>[b]Wiki update:[/b]\r<br>Fix''d a few bugs, added Categories and Portals, created a few pages and finally added more [[markup]] possibilities.\r<br>\r<br>[b]Paster update:[/b]\r<br>Fix''d a few bugs, made posting public and restyled it.\r<br>\r<br>[b]Gallery update:[/b]\r<br>Made the gallery look nice.\r<br>\r<br>What''s yet to do: [[TY:ToDo]]', '', '', '1291493789', 1, 1),
(15, 40, 'Break.', 'I''m sorry to disappoint again, but a friend of mine convinced me to pause comic creation yet again for some time to concentrate on other stuff.\r<br>Yeah I admit, it wasn''t easy to get the three comics a week done again, with a lot of other stuff going on.\r<br>\r<br>On another note, my mental health hasn''t been the best the previous week long but I''m not gonna whine about that.\r<br>I''ve said the same shit enough times.\r<br>\r<br>Yeah. That''s that I guess.\r<br>Oh right, as to what I want to concentrate on:\r<br>Finding something that''s actually fun for me to do. Some of you might know that I don''t really enjoy making comics or drawing or programming for that matter. As to why I don''t really enjoy doing it or why I do it anyway: I have no idea. I just do it anyway.\r<br>I won''t stop drawing of course, I still got a long way ahead of me and since I already began, why should I stop now?\r<br>\r<br>So yeah, that''s it for now.\r<br>-->Nick away!\r<br>[youtube]http://www.youtube.com/watch?v=nfsb5HUFMQ0[/youtube]', '', '', '1292256710', 1, 1),
(16, 40, 'Christmas status update', 'BLAH BLAH MERRY CHRISTMAS BLAH HAVE A GOOD ONE BLAH BLAH BLAH.\r<br>So now that we got the usual shit out of the way, let''s get down to business.\r<br>\r<br>Christmas has been a lot of stress for me mostly.\r<br>I didn''t finish my presents on schedule and basically everything began to tumble down on me.\r<br>However, now that that''s gone mostly, I''ll hopefully be able to continue my holidays more efficiently.\r<br>I got a shitload of homework to do so I can''t just invest everything for projects. Oh well.\r<br>\r<br>I''ll try to put up the occasional comic now and then, to see how it goes.\r<br>I don''t have any fixed plan or schedule on this so it''ll just happen when I feel like it.\r<br>However, I do have the strong urge to make some Spherae related stuff. It''s really about damn time too for that.\r<br>\r<br>In other projects:\r<br>TinyChum will probably release a short version update along with NexTVersion, a new update manager.\r<br>I started up the project I mentioned earlier, together with gingerale. The project is called [link=code.google.com/p/fruity-rumpus-asshole-factory]Fruity Rumpus Asshole Factory[/link] for now and is a pre-project composed of mini games to test out libraries, concepts and tools. I''ll tell you more about that when it gets interesting.\r<br>\r<br>So yeah, that''s pretty much it for now, enjoy your holidays and have a sweet time!\r<br>\r<br>-->Nick away!\r<br>[youtube]http://www.youtube.com/watch?v=EDdlIa9m5OE[/youtube]\r<br>\r<br>E: before I forget it: I''ll post pictures of the presents I got for my birthday and christmas when I get up.', '', '', '1293246620', 1, 1),
(17, 40, 'PWESENTS', 'OK so, here are most of the presents I got for Christmas and my birthday:\r<br>\r<br>Firstly, I got the Problem Sleuth books...', '[img=/DATA/ps1.jpg]width=750px[/img]\r<br>[img=/DATA/ps2.jpg]width=750px[/img]\r<br>The annotations and forewords really make this worth it''s money.\r<br>\r<br>Then I got a shitload of t-shirts (two are in the wash right now so I can''t show em):\r<br>[img=/DATA/tshirts1.jpg]height=750px[/img]\r<br>[img=/DATA/tshirts2.jpg]width=750px[/img]\r<br>Yep, it''s all dA.\r<br>\r<br>Next, one more t-shirt, a book about love (I have no idea) and some chocolates (I would''ve shown you way more if they hadn''t been eaten yet):\r<br>[img=/DATA/misc.jpg]width=750px[/img]\r<br>\r<br>And finally, the samsung galaxy s:\r<br>[img=/DATA/samsung1.jpg]width=750px[/img]\r<br>[img=/DATA/samsung2.jpg]width=750px[/img]\r<br>\r<br>I also got quite a lot of money (400Chf > 400$ !!), some free tickets for the cinema and a huge box of cookies which have all been eaten by now.\r<br>So yeah, I can''t complain! :V', '', '1293276680', 1, 1),
(20, 40, 'Different, Perfect and Site Cang', 'I am pretty sure that I have all the right to say that I''m different. A bit too different even as it sometimes seems.\r<br>I mean, I''m probably the weirdest guy in our class, I have a weird way of thinking, do weird stuff and generally just act weirdly most of the time.\r<br>Some people may call it "extraordinary" but that just sounds way too positive to me. Sure, sometimes it''s a good aspect to be different and take another way of approaching a problem, but that shouldn''t mean all the time.\r<br>Now, why am I writing all this? Well, possibly just to put out there that I''m realizing all the time just how much I don''t really belong anywhere specific. I''m not an artist, I''m not a programmer, I''m not a musician, I''m not a mathematician or physicist, nor am I a biologist or even scientist in general. I don''t really fit any of these characteristics well enough, nor do I have a similar way of thinking any of the people who belong to one of these groups have....', 'For example, I don''t draw because I like to do so or because it''s fun. It''s just sort of a "project" I''ve made up for myself. I also actually rather hate programming. It''s just interesting to me because it''s abstract, but that''s it. I don''t really like playing music either, or just thinking about scientific topics.\r<br>It''s almost as if I don''t really like doing any of what I do or just fail to capture the emotion.\r<br>This probably sounds like I''m a very cold and emotionless person. That''s not the case, I do laugh and I am happy from time to time, but never really because of the things I work on. \r<br>My happiness solely comes from things other people do. Well, most of the time. Sometimes it creates the exact opposite reaction though, which is what I call an ego crush. \r<br>The ego crush thing seems to be yet another thing nobody else has. I''ve never heard of anyone getting insta-depressions because they see something that they like really much.\r<br>I''m not even sure if it''s just that I like those pieces... I guess it''s kind of a mix out of jealousy, liking and the feeling that I''ll never get to be as good as that. I don''t know, it just seems to be the perfect mix for me to get down.\r<br>\r<br>When I was in primary school, I was pretty much forced to get into the main stream. I got bullied and beaten up all the time, just because I made good marks without the least bit of effort and was interested in stuff I apparently wasn''t supposed to be interested in. But no matter how much I tried to interest myself for the shit the other guys were interested in (football, games, msn), it didn''t change anything. Over time I learned to just deal with the fact that people hate me. \r<br>When I got into the gymnasium (that''s high school for smart kids), things seemed to brighten up a little. But not much and not for long. The first two years, aka. primary gymnasium, didn''t turn out too well either. It was better sure, but still far from good. I was still pretty much an egoist because, hell, that''s what I learned to be. \r<br>When I got into the third year, the classes were mixed anew. It didn''t start out very easy for me either and I started to diverge again from the main stream. I got fed up with trying to interest myself in stuff I didn''t want to be interested in. However, for some reason, things changed. I can surely say that I''m accepted in this class now and that I got some good relationships with some of the people in class. I''m not really trying to hide the fact that I''m not interested in the stuff others primarily do (gaming, going out) but that''s apparently ok.\r<br>I am happy to learn that a lot of people seem to like me, primarily people I know from the internet. It''s what keeps me running. As long as there are people out there who care about me I got a reason to live, a reason to do shit.\r<br>\r<br>This may explain why I do what I do to some extent, but it still doesn''t explain why I don''t have any fun in any of what  I do. I don''t think I''ll be able to answer that question soon, primarily because I don''t know if that problem is fix or if it''s only a long and awful phase I''m going through.\r<br>\r<br>Why am I even writing all of this?\r<br>I don''t know... I don''t even know if I have any chance of getting anyone to read all of this.\r<br>Oh well... I''ll keep on rollin'' either way.\r<br>\r<br>\r<br>[b]Now for another thing I wanted to get loose:[/b]\r<br>Perfect. What does that even mean?\r<br>Let''s get a bit deeper into how you can apply the word.\r<br>If you say "this picture is perfect!" then perfect is applied to a) the picture and b) your opinion.\r<br>This means that perfect is always relative to two contexts. The context it''s coming from and the context it''s applied to. Normally, perfect is coming from the "global" context, or your perception of reality. Your opinion.\r<br>Of course, perfect can never be truly applied to the global context, since it''s impossible to be perfect in every aspect, unless it''s a perfectly defined, abstract construct like maths. The same goes for every context that resembles an exact idea and doesn''t allow variability.\r<br>However, something CAN be perfect if the context it''s coming from allows variability, isn''t an exact idea, but rather a rough concept, a set of rules or guidelines.\r<br>This means that a picture can be perfect anatomy wise, since anatomy allows variability. Anatomy is a set of rules and ideas. Not only one strict idea lone.\r<br>Now to get to my point: If I say "I want to have PERFECT anatomy", responding with "nothing can be perfect" is simply wrong. More so, everything can be perfect if perfect is derived from the right context.\r<br>Let''s make a math analogy here:\r<br>You have the number set {1,2,3,4,5}u[10-20[. Which of the following numbers are perfect?\r<br>a) 2 b) 0 c) 4 d) 10 e) 6 f) 10.41 g) 10+Pi h) 20 i) 3.4212\r<br>Solution:\r<br>a,c,d,f,g are all perfect!\r<br>\r<br>And yet another pointless point has been made! Hooray!\r<br>\r<br>[b]And for the last point I wanted to talk about today:[/b]\r<br>I made some changes to the site, namely these:\r<br>[list]\r<br>[e]Moods for the comics and blog entries. You can now see some different icons which I will choose depending on my mood when I post.\r<br>[e]Blog short/long versions. The front page only shows a part of the blog entry. However, the poster can decide how much to show on the front page.\r<br>[e]Short link list: http://tymoon.eu/short \r<br>[/list]\r<br>Not much but at least something.\r<br>\r<br>[b]And finally, here have a sweet music video:[/b]\r<br>[youtube]http://www.youtube.com/watch?v=FRtd8ArvH_s[/youtube]\r<br>\r<br>-->Nick away!\r<br>', '', '1295748692', 1, 1),
(21, 40, 'Plans plans plans!', 'Well, it''s time to put out plans for the new year.\r<br>These plans are long term plans and can change heavily since I''m more of a guy depending on dynamic reaction to the situation at hand rather than trying to have a fixed future. Still, planning is important and it''s basically just here to tell you guys what I''ll be doing.\r<br>The topics needing discussion are:\r<br>[list]\r<br>[e]Tymoon and general comic schedules\r<br>[e]Pesterchum/TinyChum\r<br>[e]TyNET\r<br>[e]FruityRumpusAssholeFactory\r<br>[e]Side quests\r<br>[e]General arting\r<br>[/list]', '[b][size=18]Tymoon and general comic schedules[/size][/b]\r<br>I will see about getting Tymoon on to a weekly schedule. I think I can manage this mainly because of my huge improvements in technique and style so it should be well withing my limits. I will have to cut down on my other arts projects a bit though.\r<br>Spherae needs a rewrite. Well, of the planned stuff that is. I''ll probably take some day off to write the whole first chapter. A lot of things have become more clear in my mind and I think I can now create a better running plot for this thing.\r<br>Daily will continue whenever I have something to talk about which is hopefully more often. Otherwise I''ll just start abusing it as a sort of talk-to-myself-about-arts thing. I''ll see what happens.\r<br>\r<br>[b][size=18]Pesterchum/TinyChum[/size][/b]\r<br>This is probably what most of you people worry about since it''s my most popular project from what I can guess.\r<br>The planned TC3 version has still a lot to cover. The GUI rewrite will take some time and I honestly can''t give an estimated release date because I don''t really know what problems I''ll encounter on the way.\r<br>What''s for certain though is that TC3 will take over PC. This means that TC3 will be renamed to PC3 again. It is also to be expected that I will drop support for PC2.5 and TC1.5 or earlier entirely to move the project onto one platform instead of diverging ones. I''m not certain about this step yet though as it might create a lot of whining and drama, but it would help a huge lot with compatibility and support.\r<br>I will blog about the development process here.\r<br>\r<br>Armada is working on a port of TC1.5 for the android platform. I''ll see about giving process updates here too about that.\r<br>\r<br>[b][size=18]TyNET[/size][/b]\r<br>TyNET V3 is planned for this summer. I will, again, completely re-write the site to get a cleaner, faster and easier site. It will focus more on the content at hand rather than the weird Web2.0 content offering it has now.\r<br>The codename is "glow" and here''s an incomplete features list:\r<br>[list]\r<br>[e]Comic series -> comic arcs -> comic strips management as in TyNETv2\r<br>[e]A better comment system\r<br>[e]Database upgrade instead of depletion\r<br>[e]Better blogging system with tags, categories and more\r<br>[e]Dynamic pages\r<br>[e]Wiki and paste service will be removed, gallery made private\r<br>[e]Overall dynamic layout with a new style and faster site loading\r<br>[e]General site optimization\r<br>[e]Better poll system\r<br>[e]Tighter combination of services\r<br>[e]Attachment system\r<br>[e]Better security\r<br>[e]Less javascript bullshit\r<br>[e]Optimized database\r<br>[e]Advanced search function\r<br>[/list]\r<br>These are only vague estimates and the change list will probably be extended a lot still.\r<br>\r<br>[b][size=18]Fruity Rumpus Asshole Factory[/size][/b]\r<br>Yeah, remember that project I started with gingerale?\r<br>It''s not doing very well atm because we''re both very busy and haven''t even had the time to look for more members yet.\r<br>I''ll have to talk to gingerale about getting stuff rolling and actually making out a few specifics of the things we had planned to get a better idea of the whole instead of just a blurry mess of nothing.\r<br>\r<br>[b][size=18]Side quests[/size][/b]\r<br>Besides all that, I''ll probably work on some minor stuff like bukkit plugins for the minecraft server and perhaps some android test apps. This has low priority though.\r<br>\r<br>[b][size=18]General arting[/size][/b]\r<br>I will certainly keep up with the streamings. I''ll also keep up with my constant anatomy studies but I think Imma have to take a closer look at character design and style variability very soon. \r<br>This means that I''ll have to force myself to draw more different things instead of the same all the time and work on getting more defined characters with the main goal in mind to get more...\r<br>a) variability = flexibility = more natural look = awesome\r<br>b) defined / distinguishable characters = good characters = characters you can identify even next to a lot of others, even with a change of clothes and so on.\r<br>Yeah.\r<br>\r<br>That''s all I have to say about plans at the moment!\r<br>Just a minor complaint: It''s awfully quiet on this site. I guess it''s mainly my fault though because I don''t advertise enough and don''t produce enough content here, mainly in the comic section which is supposed to be the heart of this site.\r<br>\r<br>--->Nick away!\r<br>\r<br>[youtube]http://www.youtube.com/watch?v=aeXIoBnu2MU[/youtube]\r<br>', '', '1297023870', 1, 1),
(22, 40, 'Not gonna lie,', 'The last few weeks haven''t been good ones for me.\r<br>I don''t think I got much done at all and I have the feeling that I took a huge step backwards in terms of art because of this.\r<br>I''m trying to get back on track somehow, get my life sorted out.\r<br>I guess for me it''s that instead of having to sort out my life out in terms of other people and such, I have to sort it out with myself. I have very high expectations of myself and having so many things going on at once doesn''t help it at all. In the end it just feels like I only get bits and tiny pieces of one of the projects done. However, reducing the amount of projects doesn''t work either. It just makes me depressed because it means giving up on an idea I developed, found time investment worthy and already invested time in.\r<br>If I drop a project, it always feels to me like it was an even bigger waste of time now that I don''t even have the slimmest chance of finishing it or getting anything worthy out of it.\r<br>I guess that''s one of the reasons why I cling myself to whatever I start and just add to the stack and add and add til at some point I realize that the stuff at the bottom died without me noticing. Of course, that always makes me angry about myself.\r<br>I feel like I don''t have any sense of planning or organization. Everything is just a blur of wild things, ripping me from one part to the other.\r<br>\r<br>The worst thing is that resistance seems futile. Any means of adding a schedule or some sort of plan or to do list have only worked for maximum a week til I forgot about it again.\r<br>It''s sort of ironic to think that I can force myself to cling to all these projects but don''t have the slimmest chance of getting myself to cling to a schedule.\r<br>\r<br>Not to mention that I always push school aside, even though it should be way more important to me than [link=Shit nobody cares about]my silly little projects[/link].\r<br>I just hope that you can do better than me.\r<br>Don''t take me as an example.\r<br>You''d ruin your life.\r<br>\r<br>Anyway....\r<br>I know I haven''t posted anything for quite some time now, but I have been drawing some stuff even if it''s very little sadly. I never got around to do it or just didn''t feel like it so yeah... I''ll see that I catch up with that asap.\r<br>I''ll also see that I blog more again, just to have something to spout my mind out at and to distract from my loneliness.\r<br>\r<br>That''s it for now.... have a chill day like cool guy here:\r<br>[youtube]http://www.youtube.com/watch?v=4txVqr1eNwc[/youtube]', '', '', '1299711431', 1, 1),
(23, 40, 'Going downhill since before crea', 'So.\r<br>I''m busy.\r<br>Yeah I know, I always say that but I think it''s pretty much always true too.\r<br>\r<br>As for [i]stuff[/i]: I''m working on TyNETv3. That''s all I''m gonna say. I don''t want to write boring blogs about what I''m gonna do or what not and neither do I want to write more bawwwing or bluh blogs. It has to stop.\r<br>\r<br>SO! Starting now with that, the question of course is, what to write about otherwise.\r<br>I''ll leave out the question if it''s even worth my time because I''d say no, no matter how much I think about it.\r<br>I guess I could talk about what interesting stuff I found on the nets and so on.\r<br>\r<br>Well, for one I started an image gallery where I dump pictures to that I find interesting or funny.\r<br>The gallery can be found here: http://img.tymoon.eu \r<br>\r<br>Then I found some lovely videos on youtube. You can find them at the end of the full length post.\r<br>\r<br>I also found MLP (My Little Pony: Friendship is Magic).\r<br>Now, if you DON''T know about this yet, then you seriously have to go check it out. Despite the name and all, this show really is really good. Here''s the first episode: http://www.youtube.com/watch?v=JeJ6-gN0eB4 \r<br>\r<br>I don''t think I got much else to say, sadly.\r<br>Oh well.\r<br>', 'Youtube videos:\r<br> [link=http://www.youtube.com/watch?v=nk2wViKSh_M]Ultimate Muscle Roller Legend[/link] This is one of the crazier videos. But damn it''s awesome. One of the internet''s must-sees.\r<br>\r<br> [link=http://www.youtube.com/watch?v=9Yrt9qkBQ2Q]Lawnmower Dreams[/link] This is just plain beautiful.\r<br>\r<br> [link=http://tymoon.eu/00968]Mega64: Heavy Rain[/link] For those of you who know the game, this is hilarious.\r<br>\r<br>If you don''t know already, I freaking love Christopher Walken. How can you NOT love this accent? Here''s a list of good impression videos:\r<br> [link=http://www.youtube.com/watch?v=xrUzwdogxnI]Dave Coyne[/link] \r<br> [link=http://www.youtube.com/watch?v=bKKDKAKNH-k]Kevin Spacey[/link] \r<br> [link=http://www.youtube.com/watch?v=8KooaRwGO40]Kevin Spacey again[/link] \r<br> [link=http://www.youtube.com/watch?v=J06BU6Fj6Qs]The Wedge[/link] \r<br> [link=http://tymoon.eu/31C0IM]Eddie Izzard[/link] \r<br>Original Walken awesomesauce: \r<br> [link=http://tymoon.eu/12L481]Walken signs Delilah[/link] \r<br>\r<br>I also found out about Eddie Izzard. He''s a British stand up comedian and I find him to be very very good. Here''s a sample:\r<br> [link=http://tymoon.eu/G7830]Cake or Death?[/link] \r<br>\r<br>These two animations are very cool imo:\r<br> [link=http://www.youtube.com/watch?v=iLC5EKcVFhc]Katan[/link] \r<br> [link=http://www.youtube.com/watch?v=2Ja5rWOPUsI]Rockoons[/link] \r<br>\r<br>And finally, for the minecraft players out there: You should definitely check out this mod here:\r<br> [link=http://www.youtube.com/watch?v=g0Ud9esotDU]Minecraft Portal Mod[/link] ', '', '1301423127', 1, 1),
(24, 40, 'Happy Easter', '[img]http://files.g4tv.com/ImageDb3/267925_S/portal-turret-easter-egg-nugget-from-the-net.jpg[/img]\r<br>Yeah!\r<br>So uhm, it''s been quite a while since I last posted here, ehehe.\r<br>Or even produced any content at all. :/\r<br>Yeah, thing is that I''ve had a pretty big crisis and I just couldn''t get myself back in. I hope I can fix that asap.\r<br>I have been working on TyNETv3 though so at least something is making progress. :/\r<br>\r<br>Now for other things... Portal 2! Yes! I got it, I played it, and in all shortness: It''s awesome.\r<br>No really, it gave me everything I expected from it. A good story, nice puzzles, cool physics tricks and nice graphics.\r<br>I really highly recommend you to buy this game.\r<br>I know this isn''t a review or some shit but I''m no reviewer. It''s just my personal opinion, in case anyone cares.\r<br>Oh, I shouldn''t forget to link to this website here: http://spaaaaaaaaaaaaaaaaaaaaaaaccee.com/ (you''ll only get it if you''ve beaten Portal 2)\r<br>\r<br>Lastly I''ve got some more video updates...\r<br>', '{{noparse}}\r<br>{{linksparse}}\r<br>Can you resist the ogle? http://www.youtube.com/watch?v=4zTFaqLVs6o \r<br>\r<br>Mitchell and Webb are amazing. Here''s some videos:\r<br> http://www.youtube.com/watch?v=I9cP-1kC3So \r<br> http://www.youtube.com/watch?v=i1-bbz3crjw \r<br> http://www.youtube.com/watch?v=mGp4DvFEgh8 \r<br> http://www.youtube.com/watch?v=Jo8FR8GGoJ0 \r<br>\r<br>Oh india, u so crazy: http://www.youtube.com/watch?v=hNXHveyzUvY \r<br>\r<br>Have you heard of Inspector Bonk? http://www.youtube.com/watch?v=OYBZrRCCrXE \r<br>\r<br>Real life lag: http://www.youtube.com/watch?v=ZcP9jiCbakw \r<br>\r<br>Have a portal 2 promotion video: http://www.youtube.com/watch?v=AZMSAzZ76EU \r<br>Oh and another one: http://www.youtube.com/watch?v=Mz0PvYiHjwE \r<br>Here''s some awesome portal music: http://www.youtube.com/watch?v=DQUaEF2bJZ0 \r<br>And this is simply amazing: http://www.youtube.com/watch?v=7_1Ao2cd5BM  \r<br>I think Cave Johnson has something to say about lemons... http://www.youtube.com/watch?v=7mt8I6cvFsM \r<br>This is my favorite MLP episode so far: http://www.youtube.com/watch?v=0L968BfOscU \r<br>\r<br>For fuck''s sake reader, you''re a fucking wizard! http://www.youtube.com/watch?v=0mQaIMYIvYU \r<br>\r<br>I also got a bunch of excellent MLP related stuff:\r<br>PonyCraft 2 is an amazing video: http://www.youtube.com/watch?v=JJbAT1wzS8U \r<br>16-bit theme remix: http://www.youtube.com/watch?v=NDAjEUCiljk \r<br>Awesome 8-bit version of Winter Wrap Up: http://www.youtube.com/watch?v=QPLsQJJUTmE \r<br>The dramatic turn, Twilight Sparkle edition: http://www.youtube.com/watch?v=g0aVCuFHt88 \r<br>All aboard the rape train! http://www.youtube.com/watch?v=A_w-3KcVLhc \r<br>Nope.avi http://www.youtube.com/watch?v=l4op6UoTmpo \r<br>Flutterguy is the best: http://www.youtube.com/watch?v=dw0E67i5Wqs \r<br>Here''s an amzing music / video sync: http://www.youtube.com/watch?v=irQ_d78feMA \r<br>Portal 2 has the Pinkie Sphere: http://www.youtube.com/watch?v=iIejAv3yA7o \r<br>Awesome music and an awesome character combined: http://www.youtube.com/watch?v=oPgW1evNPPQ \r<br>And finally some YTP stuff: http://www.youtube.com/watch?v=Z7uWBHrhX3M \r<br>\r<br>PRETENTIOUS HIPSTER BULLSHIT: http://www.youtube.com/watch?v=tTptllKOGH8 \r<br>I just fucking love this voice: http://www.youtube.com/watch?v=WssU1yiuNX0 \r<br>\r<br>Go Animate! is cancer. http://www.youtube.com/watch?v=k3Pjgs9I9Ns \r<br>\r<br>Now I''ve got some wonderful Myst music: http://www.youtube.com/watch?v=T4XUqkpZjk0 \r<br>Kadish''s Gallery is beautiful too: http://www.youtube.com/watch?v=v68DNA-Sb6E \r<br>\r<br>HA\r<br>HA\r<br>HA\r<br>HA\r<br>STAYIN'' ALIVE!\r<br> http://www.youtube.com/watch?v=I_izvAbhExY \r<br>\r<br>And some more awesome music: http://www.youtube.com/watch?v=ZzvAejdK83E \r<br>\r<br>Last but not least, the minecraft interstate video: http://www.youtube.com/watch?v=p8Rt2A28yHk \r<br>\r<br>And that''s all!\r<br>Have fun watching', '', '1303633627', 1, 1),
(25, 40, 'I has a happy', 'Just giving a quick heads up because these ridiculously old blog entries are also ridiculously out of date.\r<br>The short version is: I am one happy person.\r<br>I''m a bit stressed out, but I am happy. No more depressions, no more bawwing.\r<br>This is about what I feel like most of the time nowadays:\r<br>[img=http://img.tymoon.eu/bronies/13084238001687.jpg]style="max-width:100%;"[/img]', '', '', '1307806205', 1, 1),
(26, 34, 'Transcend', 'About time I talk about this.\r\n\r\nWell, right off the bat: What the hell is this?\r\nIn all simplicity: It&#039;s a project of mine to program a game, including engine and graphics, from scratch.\r\n\r\nThis project is, at the same time, also an obligatory school project, called the \\&quot;Matura-Arbeit\\&quot;.\r\nIt will play a part in the final score of my graduation.\r\n\r\nNow, what is the \\&quot;Matura-Arbeit\\&quot;?\r\nHere in Switzerland, you have to complete an individual project of your own, about a topic that is pretty much free to choose, for the graduation. Depending on what kind of project that is (scientific, artistic, combination), you have to write an essay of 7-20 pages. \r\nThat&#039;s surprisingly little, I know. And I could easily fill 20 pages with the programming stuff alone.\r\nEither way, you also have to hold a dissertation about it at the beginning of the second school semester, which will also influence the mark you&#039;re getting for it. You also get two teachers that should accompany you in the process of the project and help you with stuff you don&#039;t know about.\r\n\r\nThe topic I chose was to simply build a game from scratch.\r\nSounds easy, right?\r\nJust put in some OpenGL commands, plaster some animations onto the screen and build your level and with that your game.\r\n\r\nWell. The truth is far from that.', 'First you have to build a game engine that handles all your resources, objects, user input and graphical output.\r\nThis means that you need to capture the keyboard and mouse, translate that data and pass it to the relevant objects that need this input like the player or the GUI objects or whatnot.\r\nOh and of course, you need to make that GUI yourself too. Meaning you need to program your own Button, TextField, Label, TextArea, etc. classes.\r\n\r\nNext you need to keep track of your objects in the game&#039;s world.\r\nWhich means that you need to divide the objects up into types. From simple tiles over blocks to complex entities.\r\nThese types need to be able to share data between each other, so you need to be able to address them from another object out.\r\n\r\nThen of course resources. The program has to be smart about this; load a resource only once and then share it between objects that use it. The loading has to be dynamic and you need to be able to load resources or change resources on the fly. The resources need to be stored in a smart format.\r\nThis is especially important for the world format, so you can build your levels easily.\r\n\r\nFinally, graphical output means being able to control OpenGL and it&#039;s resources accordingly and being able to use them easily from other classes out, minimizing the amount of native calls in top-level objects.\r\n\r\nAnd while all that is handled, it should also be fast enough to run.\r\nDoesn&#039;t sound so easy anymore, right?\r\n\r\nAnd that&#039;s only the beginning.\r\nAfter that, you got your basic engine done.\r\nNext comes the actual work of programming the individual types. Making the player interact with other objects, adding fast and precise collision detection, getting entities to interact with the player, making the player react differently, giving the player a good feel of control and balancing out the abilities and difficulties.\r\n\r\nAnd then...\r\nYou&#039;ll probably want to start creating the graphics for the objects, so they can be displayed well in the game itself.\r\nThis will be especially painful, since many of your background graphics need to be tileable. Meaning that the same texture needs to fit next to itself in all directions well, while not getting ugly or boring on the eye if it fills enough of the screen.\r\nMore so, you&#039;ll have to create animations for your player&#039;s and entities&#039; movements that look fluent, simple and at the same time detailed enough not to get tiring on the eye.\r\n\r\nAND THEN...\r\nYou&#039;ll maybe want to think about adding sound. This&#039;ll be a huge problem if you have no fucking clue about that whatsoever, like I do.\r\nThe sound effects need to be easily recognizable for what they are and again, shouldn&#039;t be boring. The music needs to be loopable, possibly catchy or at least calming.\r\n(I&#039;ll worry about this last)\r\n\r\nAANNDD THEEEENNNNNN.....!\r\nYou&#039;ll FINALLY be able to create the game itself.\r\nWow, eh? You thought this would come way sooner. Well, it doesn&#039;t really.\r\nOnly now you have all the necessary resources ready to actually start writing the game and it&#039;s story itself.\r\nBe aware that this brings up problems like level design, repetition vs. uniqueness and most of all... testing.\r\nYou need to keep the levels fresh, but still be able to repeat your designs enough to save resources, while keeping it all interesting for the player.\r\nThe instructions should be clear to the player, but at the same time the puzzle elements of the game shouldn&#039;t be blatantly obvious.\r\nYou need to challenge the player and not overdo it, because otherwise it&#039;ll quickly get stamped off as \\&quot;impossible\\&quot;, \\&quot;bullshit\\&quot;, \\&quot;boring\\&quot; or whatever floats your boat.\r\nThen the game should provide enough play time or if possible, even have replay values of some kind that make the player pick the game up again or at least remember it for something else than being utter shit.\r\nWhile you design your game, you need to test it over and over again, possibly even ask other people out to test it for you, gather feedback all the time, correct mistakes and bugs in the engine. A very tiring, repetitive, time consuming process, no programmer should underestimate.\r\n\r\nAAAAAAAAAAAAAAAAAND THEN.......\r\nYou&#039;re finally done. You have your game.\r\nAnd you realize that it&#039;s still not finished.\r\nIt could be so much better.\r\n\r\nThat is... if you&#039;re not interrupted by the deadline in the middle of the process, which is something that&#039;s extremely likely to happen.\r\nWell, in my case and according to my calculations at least.\r\nIn this case, it means: Loads of sleepless nights by your coke bottle and daydreams haunting you about failure and the always closing up deadline.\r\n\r\nAll this while you also have to work for school, exams, homework and more things.\r\nAnd you&#039;re not getting paid a penny for it.\r\n\r\nSOUNDS NICE, DOESN&#039;T IT?\r\n\r\nWell, since I&#039;m not really the sad bawwing pessimist teenager that I was anymore, I won&#039;t leave the blog post on this note.\r\n[img=http://img.tymoon.eu/bronies/13084237991339.jpg]width=300px[/img]\r\n\r\nSeriously. If you can create a game from scratch?\r\nWow.\r\nI would be impressed.\r\nSeriously impressed. Because man, that is a HUGE task.\r\nAnd even if you don&#039;t finish it? So what. You tried and took that huge task upon yourself.\r\nWhat you produced is still impressive and I&#039;d raise my hat for that.\r\nReally, if you make a game, you can be proud of yourself. And I mean that with all honesty.\r\nI hate the word \\&quot;proud\\&quot; and use it extremely rarely. You can really believe me when I use it.\r\n\r\nUsually games are made in large teams of experts, all concentrated on one field of the task.\r\nThey&#039;re all getting PAID for it. They all have years of experience and training on the matter.\r\n\r\nAll that aside, still. Wow, you made a game! A game you can extend any develop in any direction you want, any time you want.\r\nAnd I am 100% sure that if you put effort into it, there will be people who like it. \r\n\r\nI seriously can&#039;t even begin to describe how impressive it is to actually being able to say with honesty that you made your very own game.\r\nThere&#039;s just so much about it that is amazing.\r\n\r\nSo what do I say?\r\nIf you&#039;re interested in programming a game, go for it.\r\nNo matter if you have no knowledge of any of the fields.\r\nNo matter if you have other things to do in the meantime.\r\nNo matter if you think you suck balls.\r\nNo matter if your douchebag friends tell you you suck balls.\r\nNo matter if you like shows for little girls.\r\nNo matter if you think this article got way too cheesy.\r\nJust.\r\nFucking.\r\nGo for it.\r\n\r\nIf you don&#039;t finish it, you&#039;ll still have gathered experience and will have an easier start the next time you try.\r\n\r\n\r\nAnd that&#039;s all I&#039;m going to say.\r\nI&#039;ll keep this blog active with updates about Transcend/Matura itself to show you what progress I make.\r\nIt&#039;s also an effort in hopes of bringing this site up on content again.\r\n\r\n--&gt;Nick over and out.\r\n\r\nOh and:\r\nHave another MLP inspirational poster:\r\n[img]http://img.tymoon.eu/bronies/13084237991079.jpg[/img]', '', '1310576353', 1, 1),
(27, 34, 'The Daily Transcend #1', 'Well here we go.\r\nThe first of the hopefully daily following updates about Transcend.\r\n\r\nThere&#039;s a lot of catching up to do of course and there&#039;s already a lot of things done.\r\nTechnically, you could already make a game with this.\r\n\r\nSo, let&#039;s dig right in.', 'First off, the base Engine is pretty stable and almost done.\r\nI&#039;m currently working on a particle system to create fancy effects like fire and so on.\r\nIt&#039;ll still be some time til I got that done and going smoothly though so don&#039;t expect it to be finished tomorrow.\r\n\r\nSo, what&#039;s working so far?\r\n-You can control a player that reacts to the environment with simple collision.\r\n-The world format is specified and the game can load/save from/to it without problems.\r\n-Input is completed, which means that all things with the Keyboard and Mouse are handled.\r\n-Resource management and control is also in place, as well as dynamic loading.\r\n-There&#039;s an in-game editor that allows you to manipulate the world on the fly.\r\n-The game automatically converts spritesheets into animations or texture tiles.\r\n-It can also handle audio by either playing a constant background music or by placing specific Emitters that fade the sound depending on the player&#039;s distance.\r\n-There&#039;s an in-game GUI system for buttons, text fields and so on.\r\n\r\nI won&#039;t really talk about what I did today, as most was just boring setup work for the particles, but rather just show some pretty pictures:\r\n[img=http://shinmera.tymoon.eu/public/transcend-0.03-0.jpg]style=&#039;max-width:100%&#039;[/img]\r\n[img=http://shinmera.tymoon.eu/public/transcend-0.03-1.jpg]style=&#039;max-width:100%&#039;[/img]\r\n[img=http://shinmera.tymoon.eu/public/transcend-0.03-2.jpg]style=&#039;max-width:100%&#039;[/img]\r\nRainbow Dash only serves as a placeholder here, the game won&#039;t be MLP related. All the other graphics are (badly) drawn by myself.\r\n\r\nThere&#039;s so much I could talk about that I don&#039;t know where to begin, nor if any of it is really worth mentioning or just plain boring so I won&#039;t say anything anymore at all.\r\nIf you want to know something about a specific part of the engine so far, post away and I&#039;ll gladly answer in detail.', '', '1310675523', 1, 1),
(28, 34, 'The Daily Transcend #2', 'Honestly, I haven&#039;t done much today.\r\nI&#039;m still busy with re-organizing my room and I haven&#039;t had much desire to work on the particle system.\r\nThere&#039;s still some stuff that I got ready though.', 'Have a screenshot:\r\n[img=http://shinmera.tymoon.eu/public/transcend-0.03-3.jpg]style=\\&quot;max-width:100%\\&quot;[/img]\r\nThere&#039;s still plenty of problems with the coloring of the particles and I need to find a clever way of implementing particle forces into the world system.\r\nBut what bothers me most at the moment is that the friggen text area gives me plenty of bugs and crashes the game before I can save the world, making the adding of Emitters (which require special arguments) a pain in the ass.\r\nI&#039;ll have to revise that tomorrow.\r\n\r\nI&#039;ll also have to find a way to optimize that particle system because it gets slow real quick with only little amount of particles around.\r\n\r\nYeah, other than that I&#039;ve done basically nothing today, which is quite sad.\r\nI hope I can do a lot more tomorrow.\r\nI also definitely need more sleep.\r\n\r\nHere&#039;s a promise though: I&#039;ll write a longer and more in-depth blog entry tomorrow to make up for the short news today.\r\nAnyway.\r\n\r\n--&gt;Nick over and out.\r\n[img]http://img.tymoon.eu/bronies/13084238001668.jpg[/img]', '', '1310762241', 1, 1),
(29, 34, 'The Daily Transcend #3', 'Remember how I said I&#039;d do a lot more today?\r\nAhahaha, oh you silly nick.\r\nsilly silly nick...\r\n...\r\nFUCK.\r\n\r\nOk so, I won&#039;t make any excuses here, I just didn&#039;t do much at all today.\r\nI got no one to blame but myself.\r\n\r\nI got two little things done though and some concepts...', 'Which are merely (almost) fixing the Text Area bugs (it&#039;s still a bit wonky for some reason but at least doesn&#039;t crash anymore) and implementing proper color multiplying for the particles.\r\n\r\nI also added some more particle types and tried to figure out how to set the opacity of a texture, but that didn&#039;t get far.\r\nGoogle didn&#039;t help me on that topic either. It looks like it&#039;s something OGL does pretty badly.\r\n[img]http://shinmera.tymoon.eu/public/transcend-0.03-4.jpg[/img]\r\nI couldn&#039;t figure out a way to optimize the whole deal either, so the system still slows down everything by quite a bit.\r\nFurther limiting the particle amount and adding a minimum life and size regulation might help it tough so I got stuff to test.\r\n\r\n\r\nI&#039;ve also been thinking about the entities/enemies and the combat system a bit more.\r\nSo far it looks like I&#039;ll be using black figures with white grin mouths as enemies so that I can save on animation time while still maintaining some level of coolness for the enemies.\r\nThe combat system looks like it&#039;ll be playable in two forms.\r\nForm a) being for lesser enemies, which can be defeated immediately by just attacking them or jumping on them.\r\nForm b) being for bigger enemies, which opens a sort of \\&quot;battle zone\\&quot; that locks off the region you can walk in as soon as you touch the enemy. Sort of in the style of Okami.\r\n[img=http://shinmera.tymoon.eu/public/enemy%20b1%20concept.png]style=\\&quot;max-width:100%\\&quot;[/img]\r\nI&#039;ve also fixated myself on 6 definite forms for the player:\r\nHuman, Mouse, Eagle, Dolphin, Cat, Pony.\r\nEach of them have their unique ability and differing strength sets.\r\nI haven&#039;t decided on a look for them yet, but I&#039;ll have to decide on that asap.\r\n\r\nAnd that&#039;s sadly all I got to say for today.\r\nYeah, I hope there&#039;ll be more tomorrow.\r\n\r\n--&gt;Nick over and out.\r\n[img]http://img.tymoon.eu/bronies/derpy/13101899293685.jpg[/img]', '', '1310862165', 1, 1),
(30, 34, 'The Daily Transcend #4', 'So, on this day...\r\nI did some animation work, added the first Entity class and tweaked some stuff in the file format and the editor.\r\nOther than that I had a busy IRL day so again, didn&#039;t get to do much. Sadly.\r\nI baked muffins though, so that&#039;s a plus.\r\n\r\nAnyway, on to the news.', 'The animation part turned out to be easier than expected, but it got boring pretty fast.\r\nHere&#039;s the spritesheet for the first enemy I got so far (I&#039;m not sure if I want to switch over to the monochrome color set yet):\r\n[img=http://shinmera.tymoon.eu/public/enemy_b1.png]style=\\&quot;max-width:100%\\&quot;[/img]\r\nAt this point it&#039;s probably a good idea to talk about how my program actually handles animation and general texturing.\r\nFirst off, you need to differentiate between two kinds of texture types.\r\nA) Tiles\r\nB) Sprites\r\n\r\nTiles are non-animated, static, tiled textures that are simply looped over a surface until everything is filled.\r\nThese tiles are used for backgrounds and general blocks that are basically static and don&#039;t need to move or change during gameplay. As such, the actual texture images are rather small and simple. The problematic part of the texture drawing process is that you need to make them tileable. Meaning that the bottom has to join up to the top of the image flawlessly, as well as the left to the right side. This can get to be a big problem because it limits your drawing capacity and you need to watch out that it doesn&#039;t get boring on the eye if tiled many many times.\r\n\r\nSprites however are animated, dynamic, single-instance textures that cannot be tiled. They are loaded into a texture as a spritesheet, which is sub-sequenced into equally sized squares. The program then always only draws a portion of the whole texture (one of the squares).\r\nIn my example, it works as follows:\r\nEvery row of images represents an animation \\&quot;reel\\&quot;. That way I can store multiple animations into one single file.\r\nWhenever an animation reel starts, the program refers to a pre-set array that defines the starting square of the animation. Every animation step, it moves one square farther to the right until it reaches the pre-set ending point.\r\nIt then looks up the pre-set loop array and sees if the value is positive or negative. If it&#039;s negative, it switches the reel to the defined value. If it&#039;s positive, it stays in the same reel but switches the currently displayed square.\r\nThat way you can have animations loop (f.e. walking, running, idle) as well as flawlessly join up with each other.\r\n\r\nSo yeah, that&#039;s that with animations.\r\nThe first entity isn&#039;t exactly exciting, It just transforms from the blob into the figure when the player gets near enough and then starts following you. Yeah.\r\nBrilliant, eh?\r\n\r\nAnyway, with this in place I&#039;ll soon be able to test out my EHS (Event Handling System) that regulates inter-entity events.\r\nThat should give some interesting stuff to experiment.\r\nWell, provided it actually works.\r\n\r\nI&#039;m confident though.\r\n\r\n--&gt;Nick over and out.\r\n\r\n[img=http://img.tymoon.eu/bronies/13102120127622.jpg]style=\\&quot;max-width:100%\\&quot;[/img]', '', '1310943035', 1, 1),
(31, 34, 'The Daily Transcend #5', 'Whoop. Time for an update!\r\n\r\nSo today I got to test the event system I put in place and thought a bit more about enemy classes and the general game style.\r\nI&#039;ll probably discuss more of that tomorrow when a friend of mine comes over and we can have a closer look at everything together.\r\n\r\nAnyway, let&#039;s dive right into... stuff....', 'I&#039;ve decided to make the enemies monochromatic, so pure black and white only.\r\nI&#039;ll re-make the animation for the first enemy too since I&#039;m not really pleased with it&#039;s looks.\r\n\r\nI got to test the Event System, which works pretty nicely and will allow me to make some nice stuff.\r\nHave a schematic:\r\n[img]http://shinmera.tymoon.eu/public/shitty%20schematic.png[/img]\r\n[s]It looks more complicated than it is but I&#039;m too tired to say anything more right now\r\n2:30AM god damn.\r\nI&#039;ll continue this blog tomorrow.\r\n\r\nGood night.[/s]\r\nI&#039;m back and ready to explain this mess. I also updated the shitty schematic for a proper UML diagram.\r\nAgain, it looks much more complicated than it really is.\r\nFrom an object POV, you simply implement an EventListener and register yourself at the EventHandler.\r\nThis means that you tell the EventHandler: \\&quot;Hey, I&#039;m here and I&#039;d like to hear, if one of the following events happens: ...\\&quot;\r\nNow if any object triggers an event, the EventHandler searches through his list of listeners. If any of the listeners is registered for the triggered event, it calls the listener&#039;s function and gives it the necessary arguments.\r\n\r\nJust a basic Event oriented System. Nothing special, but pretty useful.\r\nI&#039;ll talk about the game related stuff in the next update.\r\n\r\n--&gt;Nick over and out.\r\n[img]http://img.tymoon.eu/bronies/13084238001788.gif[/img]', '', '1311035551', 1, 1);
INSERT IGNORE INTO `bl_entries` (`entryID`, `FID`, `title`, `short`, `subject`, `tags`, `time`, `published`, `owner`) VALUES
(32, 34, 'The Daily Transcend #7', 'But Nick, there isn&#039;t a #6?!\r\nYeah, whatever. Screw you.\r\nI was busy, ok?\r\n\r\nAnyway, ITB: Gameplay concepts and ideas.\r\n(The actually interesting stuff for you)', 'So. I just lost my previous huge wall of text to the void so I don&#039;t know if I&#039;ll remember all I had written before...\r\n\r\nThe most important part is probably, what kind of genre is this game anyway?\r\nWell, not an easy question to answer since it contains elements of three different genres.\r\nThe first being obviously J&amp;R or Platformers, since the game is based on 2D platform elements.\r\nThe second being a sort of Action Adventure, because there are small puzzles integrated into the game, as well as action sequences that don&#039;t rely on platforming.\r\nAnd the last being an RPG element, because you can control different players that vary in abilities and looks.\r\nPersonally I&#039;d say Action Adventure is the closest fit of the three.\r\n\r\nNow, how is the world built up?\r\nIt&#039;s not your generic Side-scroller, that&#039;s for sure. The world could be closer described by some sort of disk.\r\nMeaning that it expands in all two dimensions about equally.\r\nThis would normally lead to the problem of letting the player go to stages for which he&#039;s not ready yet with his abilities.\r\nHowever, in Transcend, this issue is avoided by inserting puzzles and player-stage requirement passages.\r\nMeaning that even though the way to every goal is already there, you can&#039;t always go through that way because you don&#039;t possess the right abilities (yet).\r\n\r\nThese abilities are in actuality morphing forms.\r\nThe character you play can morph into different forms that have different abilities, properties and looks.\r\n[img]http://tymoon.eu/images/gallery/563reeva-human-design-1.png[/img]\r\nYou can gain new morphing forms by defeating the end bosses of a stage in the world.\r\nWith the new form, you can then travel new ways and discover new areas that weren&#039;t accessible before.\r\nFor example, the mouse form allows you to go through small passages and is very quick in movement, however, it is very weak in combat.\r\n\r\nNow, this of course wouldn&#039;t be of any concern if you could switch between forms limitlessly.\r\nHowever, in order to switch the form, you need to pay morph points. You can gain these points by defeating enemies or, if you&#039;re desperate, by transferring life points.\r\n\r\nThe combat itself can take the form of three different types.\r\nA) Free-Run: You can attack enemies on the run and also just escape them by running away. This only works for lesser enemies.\r\nB) Sealed: Bigger enemies or doors place a seal onto the world. Meaning that your fighting ground is limited to the currently visible area. You can&#039;t escape this seal unless you either defeat the enemies or die.\r\nC) Finals: End bosses have their own extra type. In this, the view zooms out and you get to see a way larger part of the world as usual. However, as with Sealed fights, you can&#039;t escape the area.\r\n\r\nEnemies will consistently re-spawn in the world, however you don&#039;t always have to fight them, especially as soon as you gain the Air form, which allows you to fly.\r\nKeep in mind though, that you always need to gather enough morph points for the puzzle elements and possible unavoidable fights.\r\n\r\nNow, I won&#039;t tell you anything about the game&#039;s story itself, so I don&#039;t spoil anything.\r\n(I also haven&#039;t got much written down yet anyway.)\r\n\r\nYeah, that&#039;s it for now.\r\nI hope I can keep you entertained with these blog entries and that all this isn&#039;t too boring for you...\r\nOtherwise it&#039;d be pretty pointless to make these, right?\r\n\r\n--&gt;Nick over and out.\r\n\r\n[img]http://img.tymoon.eu/bronies/13108389519253.jpg[/img]', '', '1311280951', 1, 1),
(33, 34, 'The Somewhat Scheduled Transcend', 'Blarghlhargbl.\r\n\r\nUhm.\r\nIt&#039;s been some time.\r\nSo this is probably going to be a big update.\r\nHaven&#039;t written it yet so I don&#039;t know.\r\nBrb, writing the full blog post...\r\n\r\n\r\n\r\nYeah, it&#039;s a big one.\r\nITB: Talk about 2D platform AI (Artificial Intelligence), polygons, collision detection and GUI elements.', 'Alright, so.\r\nOne of the main topics I&#039;ve worked on is enemy AI.\r\nAI is always a huge problem, because it&#039;s a huge topic, very complex and needs a lot of consideration and work to get right.\r\nI&#039;m not going to use complex AI things, because it would consume too much time and wouldn&#039;t have too much impact.\r\n\r\nThe main AI aspect I have to solve is that the enemy needs to find a way to the player.\r\nUsually, for things like this, path finding algorithms are used like A*.\r\nHowever, those algorithms don&#039;t work well in a 2D platforming environment, because they can&#039;t find a valid way through...\r\nlet&#039;s say this:\r\n[img]http://shinmera.tymoon.eu/public/figure%201.png[/img]\r\nThe pink way is what A* or a similar path finding algorithm would see.\r\nIt simply looks for the shortest way.\r\nThis would be OK for a top down environment, or for flying enemies.\r\nHowever, in a 2D platforming environment, this is unsuitable.\r\nThe green way is what would be correct.\r\nNow, how do we approach a problem like this?\r\n[img]http://shinmera.tymoon.eu/public/figure%202.png[/img]\r\nTake a look at this figure.\r\nThe only way to solve this is by trial and error approach.\r\nThe AI first checks all possible actions.\r\nIn our first case, this would be to go LEFT, DOWN RIGHT or UP RIGHT.\r\nEach of the cases give a new distance to the goal.\r\nThe first logical thing to do is to choose the way that brings us closer to the goal.\r\nAfter the AI made it&#039;s move, it reconsiders it&#039;s possibilities and repeats the same process every time the possibilities change.\r\nHowever, just choosing the one that brings you closest isn&#039;t always the right way.\r\nConsider this:\r\n[img]http://shinmera.tymoon.eu/public/figure%203.png[/img]\r\nAs you can clearly see, the first choice, C, wouldn&#039;t get us anywhere.\r\nSo we&#039;re forced to introduce randomness.\r\nIntentional errors to, as contradictory as it sounds, increase the chance of doing the right thing.\r\nBut still, even with this, it&#039;s not very likely that the AI will find the right way.\r\nIt requires a lot of fine tuning and a huge lot of extra things to do, like secondary detectors, or trying to pre-calculate the outcome of a choice and so on.\r\nI&#039;m using a very simple approach here, but I&#039;ll probably improve it a bit still.\r\n\r\nHave a screenshot:\r\n[img]http://shinmera.tymoon.eu/public/enemy%20AI%20demo.PNG[/img]\r\n\r\n\r\nNow, the next thing I&#039;m going to talk about is polygons and collision detection.\r\nI suppose you know what polygons are, so we&#039;ll get right to the collision detection aspect.\r\n\r\nThe principle behind collision detection with polygons is simple.\r\nTake a look at this figure:\r\n[img]http://shinmera.tymoon.eu/public/figure%205.png[/img]\r\nBasically what you do is follows:\r\nCast a ray from the point you want to check for collision out into a random direction.\r\nCheck the ray for intersections with every line segment of the polygon.\r\nIf there&#039;s an even number of intersections, the point lies outside of the polygon, otherwise inside.\r\nThis is a pretty neat and easy to understand principle.\r\nAnd it always works.\r\n\r\nThe \\&quot;problem\\&quot; is more the intersection between ray and line itself.\r\nTo determine the exact collision point between line segment and ray, you expand both to a full, infinite line.\r\nYou then check the intersection point of the two, which will give you two values.\r\nt being the position on the ray and u being the position on the line.\r\nIf t is negative, it would lie behind the ray point and can thus be automatically rejected.\r\nIf u is negative or bigger than one, the point lies outside the line segment and can be rejected too.\r\nTo determine the closest intersection point, you simply always save the t value and as you go through the segments, check if t is smaller.\r\nThe smallest t is the closest intersection point.\r\n\r\nWith this method, you can precisely determine the collision point between any possible 2D shape and a point.\r\nHere, have a screenshot:\r\n[img=http://shinmera.tymoon.eu/public/complex%20demo.PNG]style=\\&quot;max-width:100%\\&quot;[/img]\r\n\r\nThe problem I haven&#039;t solved yet is the texturing of the polygon.\r\nThis is mainly due to the problem that I can&#039;t draw polygons the conventional way.\r\nOpenGL only supports drawing of convex polygons out of the box.\r\n(Convex polygons are polygons that have nothing that bulges inwards)\r\nTo draw concave polygons correctly, you need to use Tesselation.\r\nTesselation basically tears apart the polygon and puts it together as fragments of concave polygons.\r\nThis however, makes texturing a bit problematic and I haven&#039;t found an explanation or way on how to do it yet.\r\n\r\nI&#039;ll figure it out eventually.\r\nSomehow.\r\n\r\nI&#039;ve also been working on the GUI elements some more.\r\nSo far I&#039;ve implemented Buttons, Labels, Text Fields, Text Areas, Images, Lists, Scrollable Lists, Radio Buttons, Toggle Buttons and Panels.\r\nIt&#039;s quite annoying and quite some work to re-make all the GUI elements yourself.\r\nI&#039;m pretty sure there are GUI libraries for OpenGL/Java out there somewhere, but that&#039;s not what \\&quot;making your engine from scratch\\&quot; is.\r\n\r\nThere&#039;s nothing really fancy to show about the GUI yet, I have to integrate it into the game state loading system first.\r\nI think I&#039;ll be able to show some sort of tech demo soon enough.\r\nI&#039;ll also see that I can get this blog back up to a regular schedule.\r\nHolding things back just makes them even worse to work back up on later.\r\n\r\nAnd with that, I think I&#039;m done for now.\r\n\r\n--&gt;Nick over and out!\r\n\r\n[img=http://img.tymoon.eu/bronies/13113188427883.jpg]style=\\&quot;max-width:400px;\\&quot;[/img]', '', '1313752028', 1, 1),
(34, 34, 'The Daily Transcend #XI', 'Well I promised to write an update yesterday, so that&#039;s what I&#039;ll do.\r\nIn this blog entry I&#039;ll primarily write about general software design process and the profits of scripting.\r\nIt&#039;s more theoretical and there won&#039;t be much talk about Transcend itself, but I think it&#039;ll still be interesting.', 'Alright, first, the software design process isn&#039;t an easy one.\r\nYou can&#039;t really schedule it well because you can&#039;t design the software in one go without any problems.\r\nTo get an appropriate schedule, you need to divide up your work into cycles.\r\nThese cycles usually include a planning phase, a coding phase and a testing phase.\r\n\r\nThe first trick now is to try to get as many cycles as possible, as fast as possible.\r\nWith every cycle, you refine your product and get errors, mistakes and flaws out.\r\nIf you do few cycles, you&#039;re going mostly by luck, because you can&#039;t test and fix a lot.\r\n\r\nThe second trick is to try to divide the project up into different smaller cycles, that each only consider a subset of the whole.\r\nThat way you can make sure to concentrate on the individual aspects, instead of having to jump around all the time.\r\n\r\nThis becomes even more important when you not only have to deal with coding, but also art, music, design and so on.\r\nYou need to balance everything out as well as possible to get a good chance at succeeding (in time),\r\n\r\nHave a figure:\r\n[img]http://4.bp.blogspot.com/_orZlfcV2GjA/TPaJ5QkhiVI/AAAAAAAAACs/Sk3qL_vRSd0/s1600/sdlc2.jpg[/img]\r\nThe only thing differing from this in the usual game development process is that the release step only comes after (hopefully) many cycle iterations.\r\n\r\n\r\nNow, of course, if you have a deadline, you&#039;ll want to increase the development speed as much as possible.\r\nHowever, since game design is largely based on trial and error, planning things is difficult and it&#039;ll never work from the first try.\r\nMost likely you&#039;ll have to test hundreds of different settings until you get a good enough formula that&#039;s actually fun to play.\r\nUsually, every time you change something, you need to recompile the game, start it, go to the place to test, try it, close it again, change the code and begin the cycle anew.\r\nThis is very slow, especially when the project is big.\r\nThe solution to this problem is scripting. Meaning the game engine has some sort of scripting interface, with which the game mechanics are built.\r\nScript languages are of course, slower because they either need to be wrapped into another language entirely or be accessed through an interface and be executed at runtime. This shouldn&#039;t be a problem though, since this is hardly noticeable on modern computers.\r\nThe advantage of this system is that you can change the mechanics/settings during runtime almost completely, saving you a lot of time.\r\n\r\nSince I can&#039;t use any other libraries than LWJGL (and Slick-Util), I had to program my own scripting language.\r\nHere&#039;s a sample of what a script file looks like:\r\n\r\n#NexT/script\r\n//statements are separated by line breaks\r\n//comments can be // for single line or /* */ for multi line\r\n//define global script variables\r\nint a=10\r\ndouble b=0.5\r\nstring c=\\&quot;test\\&quot;\r\nboolean d = true\r\n\r\ndefun myFunction{\r\n double temp = (+ 5 a (getRandom 0 100 ) (sin b ) ) //mathematical expressions are based on LISP syntax\r\n return temp //you can only return variables\r\n}\r\n\r\ndefun getRandom{ //arguments are not specified, can be accessed by &#36;n\r\n int temp = (+ (floor (* (rand) &#36;1 ) ) &#36;0 )\r\n return temp\r\n}\r\n\r\n\r\nOf course, it&#039;s very limited and a syntactical nightmare, but it does the job (I wanted to get this done quickly).\r\nYou can access variables and functions from the engine out or from the script functions themselves.\r\nThe engine automatically looks for changes on the script files and reloads them.\r\nThis makes it really easy to try out things that affect the gameplay.\r\nCombined with the in-game world editor, this creates a pretty sweet game creation suite.\r\n\r\nAnyway, I guess that&#039;s it for now.\r\nOh, before I forget it: The editor will be available in the final release, but you&#039;ll have to change a constant in the advanced dialogue of the settings window.\r\n\r\n--&gt;Nick over and out.\r\n[img=http://img.tymoon.eu/bronies/13144401444397.jpg]style=&#039;max-width:500px&#039;[/img]', '', '1314618595', 1, 1),
(35, 35, 'Version 3', 'Finally! About time the old V2 got replaced.\r\nI am so glad to introduce the new, all completely re-coded V3!\r\nIt&#039;s faster, more secure, cleaner, more extensible, bigger, better.\r\n\r\nThere&#039;s still some finetuning that needs to be done before I&#039;ll open up registrations again, but I figure that won&#039;t take me too long.\r\nMainly some online testing and data migration from V2.\r\nAnd some small things with the design that I&#039;m not satisfied with yet.', '--&gt;Nick over and out\r\n\r\n[img]http://img.tymoon.eu/bronies/13159951716971.jpg[/img]', 'V3,new,upgrade,site,oh,my,gog,finally', '1316211911', 1, 1),
(36, 34, 'The Daily Transcend XI', '[size=5][b][font=Monospace]It&#039;s been a looong time...\r\nHow have you been?\r\n\r\nI&#039;ve been really busy being dead...\r\nYou know, after my work MURDERED ME![/font][/b][/size]', '/GLaDOS\r\n\r\nSo anyway, I&#039;ve been really busy and have sadly neglected my blogging intentions...\r\nI wish I would blog more, but then again... soooooooo many hot irons in the fire.\r\n\r\nEither way, I guess it&#039;s time for an update, eh?\r\nWell, I have to admit that I&#039;ve been slacking quite a bit lately.\r\nI&#039;ve been more and more reluctant to work on this, and it makes me all the more sad to see the many hours of wasted time I could&#039;ve invested for this project.\r\nBut let&#039;s [url=http://www.youtube.com/watch?v=WlBiLNN1NhQ]look on the bright side[/url] here, eh?\r\n\r\nAlright, so what [i]have[/i] I got done?\r\nFor a first thing, I finally got started with animating. Most of the main character&#039;s first form&#039;s actions are complete:\r\n[spoiler][img]http://shinmera.tymoon.eu/public/player0.png[/img][/spoiler]\r\nStill missing from this are getting hit, dying and transforming.\r\nI&#039;ll still have to finish those as well as a complete sheet for a second form before December.\r\n\r\nThen, I also finished the main menu and the introduction room.\r\n[spoiler][img]http://shinmera.tymoon.eu/public/transcend%20menu.JPG[/img][/spoiler]\r\n[spoiler][img]http://shinmera.tymoon.eu/public/transcend%20intro.JPG[/img][/spoiler]\r\n\r\nOne big concern that is still the music.\r\nI should have at least two tracks done, one for the main menu and one for the first world.\r\nI hope I can find some kind of way to get this done, if not I&#039;ll have to fall back to existing music, which I&#039;d rather avoid.\r\nAt the moment I&#039;m aiming for something of string/classical music combined with something more modern. I&#039;m not quite sure, but a lot of things would fit.\r\n\r\n\r\nI&#039;ll try to update this here more frequently, but I can&#039;t promise anything.\r\nEspecially now that I got lots of other exams going too and I can&#039;t reveal too much anyway, informations for new blog entries are often spare.\r\nSo yeah.\r\n\r\n--&gt;Nick over and out.\r\n[center][img]http://img.tymoon.eu/bronies/13186648009571.png[/img][/center]', 'Transcend, Game, Development, Design, Bullshit, Ahaha, Oh, Wow', '1320510408', 1, 1),
(37, 36, 'STRESS STRESS OH GOG', '[img]http://tymoon.eu/data/gallery/1322613881-stress.png[/img]\r\nI&#039;ll update this when it&#039;s over.', '', 'Quick,Quicker,Quickest,Quickestest', '1322613926', 1, 1),
(38, 36, 'Status Log', 'Well, the stress is over. \r\nFor now at least.\r\n\r\nSo yes. Time for an update, there&#039;s been quite some things going that I haven&#039;t talked about.', 'The first thing I notice when writing something like this up is that I should really write a fix schedule for my projects, because it&#039;s so hard to keep track of everything and I&#039;m sure it&#039;d be more convenient for everyone else too if they knew what I was doing when and how long I&#039;d still have to work on something.\r\n\r\nSince I started my post with what I&#039;m going to do, I&#039;ll talk about that now so I don&#039;t forget about it.\r\nAs most of you should know, my main internet residence nowadays is called [url=http://stevenmagnet.tymoon.eu]Stevenchan[/url].\r\nIt&#039;s an imageboard that&#039;s currently running on a software called Kusaba X. However, this software is a piece of garbage, is terribly written, horrible to maintain and especially a nightmare to extend or modify.\r\nThat&#039;s the main reason why I came up with the plan of writing my own imageboard software, as a module for TyNET. [url=http://chan.tymoon.eu]And so I did.[/url]\r\nIt&#039;s fully functional and most of the main features are in place. There&#039;s still some stuff missing that I want to get ready though, like f.e. a Threadwatcher, the possibility to merge boards and... ah, just [url=http://chan.tymoon.eu/stc/threads/108.php]read for yourself[/url].\r\n\r\nI&#039;ve been less and less satisfied with the TyNETv3 core itself too lately. I&#039;m thinking about rewriting main parts of it and migrating to v4 (yes, already).\r\nBut before that happens I still have to plan it out better and think things over so that I actually have a system that works well and doesn&#039;t create an immense overhead like v3 does now.\r\nAdditionally to the Chan module, a HUB module is in the works. It&#039;ll provide informations for the status of my projects, changelogs, and so on. Probably also a timeline and schedule to keep things organized and in place.\r\nIt&#039;ll take some time til I finish that though as the Chan module takes priority over that.\r\n\r\nOh. I should probably also mention that [url=http://tymoon.eu/user/register]registrations[/url] are open now (and have been for weeks, just forgot to notify).\r\nI also brought the TyNET IRC server back up. You can reach it on irc.tymoon.eu:6667 . It&#039;s mainly used for the Stevenchan users to chat chat around. I blocked the #pesterchum channel to avoid having annoying people on there that for some reason still use clients that have been abandoned for almost a year now. On that note: Any kind of support tickets or requests about Pesterchum will be ignored by me.\r\nI have nothing to do with the project anymore and I don&#039;t have the time to bother either.\r\n\r\nI&#039;m planning on learning some more about other languages too, especially LISP. It&#039;s always been an intriguing language to me and I would love to actually know how to use it well. Maybe I&#039;ll get a book on it, I&#039;ll have to see.\r\nIf anyone can program in LISP and reads this: Can you suggest anything to me?\r\n\r\nThat should clear everything up about the programming and technical stuff.\r\nAs for artistic things, I&#039;m continuing work on my [url=http://stevenmagnet.tymoon.eu/fab/res/8324.html]Re-Boot[/url] adventure. I hope I can get the once-a-week schedule back up, but I can&#039;t promise anything since I have to save up more and more time for school.\r\nMost likely I&#039;ll even have to put all my projects on hold at some point in spring due to the finals that I have to concentrate on. I&#039;ll post an update on that as soon as I can make the situation out better.\r\nOther artistic works will continue to tumble in every now and then, hopefully more.\r\nI&#039;m trying to switch over to (Arch) linux at the moment. MyPaint seems to be a good substitute for SAI so far, but there&#039;s still some bumps that should be fixed for me to feel comfortable with it.\r\nSo yeah. How much I&#039;ll do and especially how it&#039;ll look will probably shift a lot in the coming time.\r\nStay put though, I&#039;m sure it&#039;ll turn out just fine.\r\n\r\nOne minor thing that isn&#039;t artistic or technical that I just remembered is that I would love to pick learning Japanese back up. I&#039;m not sure if I can find the time for that, but maybe, just maybe.\r\nI&#039;ll have to see.\r\n\r\n\r\nNow for the things that have already happened.\r\n\r\nI finally finished my Matura project. This measn that yes, Transcend is finished. And it also means that Transcend isn&#039;t finished. How about that?\r\nWell, the document and the official school project are done. It&#039;s all sent in, I can&#039;t change that anymore. However, the project itself is far from done.\r\nI&#039;ll continue working on this game until I finish it or in some way end up forgetting about it. I hope the latter doesn&#039;t happen. After all, I put a huge lot of work into this and it does have some potential, so I might as well get it over with.\r\nIf you want to read the document about it, you can download it [url=http://shinmera.tymoon.eu/public/matura.pdf]here[/url]. Be warned though, it&#039;s in German so I doubt any of you can actually read it. And if you can, I&#039;m not even sure if you&#039;ll understand half of what I&#039;m blabbing about in there.\r\nOh well.\r\nSo yes, I&#039;ll probably also post some more blog entries about it in the future, if anything interesting or noteworthy happens with it.\r\n\r\nI turned 18 years old. So yes, on the 18.11.2011 I reached the age at which people get called adults for no reason.\r\nI also get to pay taxes and vote for things I don&#039;t care about. Hooray!\r\nReally though, I only gained disadvantages, except for maybe being able to pay with PayPal or some shit.\r\nAh well, I&#039;ll deal with it.\r\n\r\nThen I got a new machine. About damn time too, my old one was just way too loud, too slow and all around getting dusty and cluttered.\r\nBut I didn&#039;t just get some new machine, it&#039;s quite a beauty too. Look at all that power:\r\n\r\nIntel Xeon CPU W3680 @ 3.33GHz, 6 cores.\r\n12GB DDR3 ECC RAM\r\n1TB HDD, 120GB SSD\r\nNVidia GeForce GT 520, 1GB\r\n\r\nAnd it&#039;s completely silent too. Seriously, I can&#039;t stress this enough. It&#039;s a real pleasure for the ears because you barely ever hear the fans, even if you don&#039;t have any music running.\r\nOf course, the graphics card isn&#039;t the best thing, but it&#039;s a lot better than my previous one and that was already good enough, so I&#039;m really not complaining.\r\nThe SSD is amazingly fast and everything runs beautifully well.\r\nThe case isn&#039;t anything special either, but I never gave a damn about the looks. Computers are here to work and that&#039;s what I want them to be able to do.\r\nSo yes. It&#039;s awesome. It did come with a price though... 2&#039;500Chf, which is approx. 2&#039;700&#36;. And I have to pay at least half of it.\r\nAh well, at least my money is spent well.\r\n\r\nAs mentioned earlier in the post, I also started using Linux more extensively.\r\nActually, I&#039;m using it as my primary OS at the moment, and only very rarely switch to windows, f.e. to use livestream (for the Stevenchan movie nights) or something.\r\nThe distribution I&#039;m currently running is called Arch Linux and it&#039;s suiting me pretty damn well.\r\nIt&#039;s a slim distro that requires some manual setup and configuration to get running, but it&#039;s well worth the effort imo.\r\nI&#039;m also using a tiling window manager instead of a desktop environment now, specifically wmfs.\r\nThe concept of twms really appeals to me and I love working with them. I really prefer them over des like Gnome, KDE or XFCE.\r\nHere&#039;s a screenshot of my desktop:\r\n[img]http://shinmera.tymoon.eu/public/screenshot.jpg[/img]\r\n\r\nSo yeah. I think this about covers most of it pretty well.\r\nI hope you all had a great time too and have a merry winter time.\r\n\r\n~Nick\r\n[center][img]http://shinmera.tymoon.eu/public/pout.jpg[/img][/center]', 'Update,Transcend,Computer,TyNET,Stevenchan,Birthday,Hub,IRC,Netw', '1323101855', 1, 1),
(39, 35, 'There be news.', 'Let&#039;s start off with an old and boring question: Why am I even writing these blog entries?\r\nHell if I know! I doubt anyone even gives a shit, including me.\r\nWhich means I&#039;ll write it either way.\r\n\r\nBut I digress, on to the news.', 'First regarding TyNET:\r\n\r\nThe software has barely been out (released on 17 Sep 2011) and I&#039;m already working on the next version.\r\nWhy am I doing this?\r\nWell, to be honest, v3&#039;s design is awful.\r\nThere&#039;s many many flaws, especially in its INIT design, which makes the framework a slow piece of slimey, dripping garbage.\r\nv4 has a custom INIT design, specifically created to be slick and fast, while maintaining v3&#039;s flexibility:\r\n[img]http://shinmera.tymoon.eu/public/TyNETv4-Startup.jpg[/img]\r\nThis new INIT system should be a lot faster and take quite a chunk of server load away.\r\nFor this to work, the CORE needs to be remade from scratch and the modules need to be reworked or rewritten completely to become compatible.\r\n\r\nAside from the new CORE, it&#039;ll introduce a hooks system, which allows modules to dynamically integrate into each other and extend functionality where it needs to be.\r\n[img]http://shinmera.tymoon.eu/public/TyNET4-trigger.png[/img]\r\n\r\nNext to that I&#039;m also working on concepts for some kind of automatic layout system that would allow for easy designs and better post styling. But that&#039;s for later.\r\n\r\n\r\nAnd then there&#039;s school...\r\nWhich will start again on Monday, so I&#039;ll surely have loads of fun with that.\r\nEven less time for my things, how great.\r\n\r\nNow uh, I will have to focus more on school since the finals are gonna come up, so yeah.\r\nEven less content from me than usual I guess.\r\n\r\n\r\nUh yeah. Maybe I&#039;ll have more to say on this later on.\r\n\r\n--&gt;Nick away.\r\n[center][img]http://shinmera.tymoon.eu/public/101941%20-%20artist%20karzahnii%20Christmas%20derpy_hooves%20earmuffs%20fire%20mail%20scarf.png[/img][/center]', 'TyNETv4, school', '1325959478', 1, 1),
(40, 36, 'Limitation', 'I need to limit what I want to do so I can increase productivity and stop wasting so much fucking time.\r\nAnd I&#039;ll keep this short so I don&#039;t waste my fucking time explaini', 'Basically I decided to follow the rule of three projects max.\r\nThese three projects are and will be for at least the next two months:\r\n\r\n[b]Re-Boot[/b]\r\nMy webcomic-adventure over on [url=http://chan.tymoon.eu]Stevenchan[/url].\r\nIt&#039;s set in a sci-fi bio-tech facility with robots, machinery, cryogenics, strange mental manipulations and more.\r\nYou can check it out here: [url]http://chan.tymoon.eu/fab/threads/27560.php[/url]\r\n\r\n[b]Transcend[/b]\r\nThe game project I blogged about quite a couple of times already.\r\nI&#039;ll have to work on this quite extensively again and actually make it [i]be[/i] something instead of just ideas in my head and some odd java game engine.\r\n\r\n[b]Tarka[/b]\r\nA new project I launched with a friend of mine, Mithent.\r\nTarka is the general name for a couple of smaller projects and the initial idea behind the whole thing is to simple collaborate, get to know how to work in teams, learn new stuff from each other and mainly try to create something fun or useful for both of us.\r\nThe first sub-project is already a go, called Tarkali. It&#039;s an android mex (Mobile EXecutable, because App sounds shitey), with the intention of providing extensive List management. From our brainstormings we got some pretty neat ideas like linking lists or tasks to locations, having a to-do list on the lockscreen, the ability to share lists in teams and more.\r\n\r\nI&#039;ll give updates on those projects as they go.\r\nEverything else (TyNETv4, TyNET/HUB, Tymoon, Spherae, Seamca, etc.) is put on hold for now.\r\n\r\nAnd that&#039;s all.\r\n--&gt;Nick away!\r\n[center][img]http://img.tymoon.eu/suiseiseki/ohhai.jpg[/img][/center]', 'Projects,Transcend,Tarka,Tarkali,Re-Boot,Reboot,Well,Thats,Life,', '1326714981', 1, 1),
(41, 55, 'A Note About Versioning', 'This is mostly a note to myself, but I want to keep it somewhere, so I might as well publish it.\r\nWhat&#039;s described here is the way NexT projects are going to be versioned by from now on.', 'The syntax goes basically as follows:\r\n[size=4][center]Name-Write.Version.Step[.Build][|SubprojectName-SubprojectVersion.SubprojectStep][-Platform]-Status[/center][/size]\r\nExplanation of names:\r\nWrite: The rewrite revision. This should preferably stay at 1.\r\nVersion: Version number depending on the set milestones.\r\nStep: Sub version number chosen at will, indicating progress.\r\nBuild: Release build number (optional).\r\nSubprojectName: The name of the subproject.\r\nSubprojectVersion: Ditto.\r\nSubprojectStep: Ditto.\r\nPlatform: The platform specific build, if there is any platform dependency (optional).\r\nStatus:\r\n- Î± Early development (Unstable)\r\n- Î² Late development (Unstable)\r\n- Î³ Stable release\r\n- Ï‰ Final release, may or may not be stable, project may or may not be finished.\r\n\r\nAs an example, this here is running TyNET-3.4.8-Linux/Debian-Î² as of the time of writing.', 'TymoonNexT,NexT,versioning,projects', '1330378838', 1, 1),
(42, 36, 'I Suck At Projects', 'Ok so, I just want to say this. I suck at doing projects.\r\nI really do. There&#039;s many reasons why, some of which I&#039;m going to write down here.\r\n[comment] [/comment]', '[b]1. Massively Short Attention Span[/b]\r\nI have an extremely short attention span and as soon as a project gets a bit too tedious or I hit a problem that I don&#039;t want to solve, I might push the project away for weeks, months or maybe even abandon it altogether, in favour of something else.\r\nThis is bad because it makes me used to simply having an alternative instead of actually fighting a problem and just gettting that shit done. It is also bad because it means that I often just won&#039;t get a project done at all and in the end, pretty much all progress is lost to nothing.\r\nSounds terrible? It is! And it&#039;s a real shame as well.\r\nI&#039;m really a spoiled kid that way. I&#039;ve almost always had the luxury of just not doing something if I don&#039;t feel like it and still getting away with it somehow.\r\nThis is a very bad character trait of mine and one I should change.\r\n\r\n[b]2. Failure At Grasping the Problems[/b]\r\nI often seem to be working on the wrong things. I spend way too much time doing something that has very little effect or is only &quot;extra schmanko&quot;.\r\nIn effect this obviously follows that I get a neat system with all sorts of tricks, but in the end it&#039;s useless because the important aspects are wonky, broken or completely missing.\r\n\r\n[b]3. API-Oriented Design for the Wrong Reasons[/b]\r\nThis one&#039;s a bit more cryptic, but allow me to explain.\r\nWhen I design a system for something, like f.e. TyNET-CORE or Transcend&#039;s engine, I always try to make it as flexible as possible. The end-programmer should be able to do as much as possible with it and still be supported by the framework/API. This isn&#039;t a bad thing by all means! Hell, more flexibility is always good!\r\nBut it isn&#039;t when it&#039;s done for the wrong reasons or at the wrong places. And this is something I seem to be doing often. I [i]over[/i]-design and want to make things too flexible, or at least flexible in places where it isn&#039;t really needed. So what does this end up with?\r\nWell, again, I spend way too much time doing the wrong things. API design is time expensive. It needs documentation, planning, a huge variety of interfaces, abstraction layers and function sets that allow flexibility and power to be interleaved. And, of course, I end up not doing actual work that would give some interesting and showcase-able products, but rather do internal things that only I can get boners for, since most likely nobody else is ever going to use any of the turdpiles I make.\r\nSo yeah, I should really learn to overcome my fear of sometime creating one-way things and only extend API-level functions later on, once the necessary stuff is running.\r\n\r\n[b]4. I Am Shit at Communication[/b]\r\nI often fail to communicate my actual intentions and goals, what I want to have as a product. What I want to do and want to have others do.\r\nThis is probably mainly because I don&#039;t know myself and frankly don&#039;t really want to know. Most of the time I just go along, doing whatever the hell, without outlining a fixed roadmap or long-term goals or anything that would help established an actual &quot;course of action&quot;. This also goes back to me not wanting to do one specific things and wanting to be able to do whatever the hell when I feel like it.\r\nAnd naturally, I have always been a rather secluded person and often prefer to work on my own than letting others take some work off me.\r\nI have to admit as well, that I have a fear of others being incompetent. I learned to rely on myself when shit comes to matters and if anything does severely go wrong, at least I only have myself to blame.\r\nDe facto: I am not good at co-operation or teamwork.\r\n\r\n[b]5. Hurry, Hurry, Hurry![/b]\r\nI want to get things done fast. If I don&#039;t get things done fast, I get mad at myself for being a slacker and a useless piece of shit for wasting precious time of my life. The problem here arises from the effect that I rush to do things, often without planning them out well, resulting in a bad design and overall rubbish result.\r\nFrom there on, there&#039;s two ways to go: A) Rewrite it B) Fix the problems. Both are awful ways for differing reasons.\r\nA is bad because it means a tremendous amount of work and mostly doing the same again. It basically means all the previous things were for nothing.\r\nB is bad because it&#039;s tedious. This goes again into point 1.\r\n\r\n[b]6. I Waste Time[/b]\r\nThis seems a bit contradictory with point 5, but it&#039;s not. Here&#039;s the deal: I should simultaneously cut down on wasting my time with doing things that have no point (watching stupid videos, reading tumblr stuff) AND just take it slower with work. This would either lead to the same productivity rate, but with time can only mean an [i]increase[/i] in productivity, since I&#039;ll be more relaxed at work, waste less time being distracted and generally make less faults.\r\nThis is one of the points I wish to combat the most.\r\nAll the others are of course very important as well, but this one just bugs me on a daily basis, because I have to watch myself be an idiot for so many hours.\r\n\r\n\r\nSo yeah. I&#039;ll stop here, although I&#039;m sure I could find more reasons.\r\nOf course, someone will barge in again and proclaim that I don&#039;t suck at all and some points are negligible or even wrong, but man, this is my own impression.\r\nFor example, I hear a lot of people say that I work a lot, which is bullshit. I don&#039;t work a lot, I merely make it seem like a lot. I could do so much [i]more[/i] and I am certainly not satisfied with the amount I&#039;m doing now. The other fact is that most people are lazy faggots as well, only increasing the illusion of me being a hard worker. I don&#039;t give a shit if Billy McDumbass does almost nothing all day long, that doesn&#039;t make it any more right for me to sit around doing nothing.\r\n\r\nAnd that&#039;s all I have to say on this for now.', 'Projects,TyNET,Transcend,Teamwork', '1330381798', 1, 1),
(43, 55, 'TyNETv4 Structure', 'In this I&#039;ll explain a bit about the base structure incorporated into TyNETv4.\r\n[comment] [/comment]', 'The first thing to say is that v4 comes in three &quot;package spaces&quot;.\r\n\r\n[size=4][b]CORE/BASE[/b][/size]\r\nThis provides the main framework and structure to even run TyNET. It is absolutely required.\r\nIn more detail, here&#039;s a list of all the things it does:\r\n- Provide the INIT sequence\r\n-- Sets constants and variables\r\n-- Establishes Database connection\r\n-- Parses the URL\r\n-- Loads the correct modules\r\n- A toolkit class to provide essential functions and shortcuts.\r\n- A database abstraction layer to allow for easy and safe queries.\r\n- The folder structure required to store and modify locally saved data.\r\n- Provide a module loading and handling framework.\r\n- Provide the hooks framework to allow simple and dynamic module interaction.\r\n\r\n[size=4][b]ESSENTIAL[/b][/size]\r\nThis package space basically makes TyNET &quot;usable&quot;. Sure, it&#039;ll run on the CORE alone just fine and with whatever modules you want, but these here are modules and classes that provide some very basic and essential use settings and abilities, like administrating modules themselves, managing user accounts and providing additional simplification tools.\r\nHere&#039;s a more complete list:\r\n- Admin: Module for the administration of the CORE.\r\n- User: Module for the management of user accounts. This does [i]not[/i] include a front-end!\r\n- Theme: Module to run TyNETv4 themes and allow easy output generation.\r\n- Useful libraries to allow connection to other services like Akismet, Google Analytics and Re-Captcha.\r\n- A set of classes for generating layouts.\r\n\r\n[size=4][b]USERSPACE[/b][/size]\r\nThis package space consists mainly of modules that actually provide end-user interaction. Without any of these modules, the site would not be usable for anything.\r\nHowever, none of these packages are essential or necessary at all (unless direct dependency is specified).\r\nHere&#039;s an incomplete list of so far existing or planned modules:\r\n- Crystalview: Statistics gathering and chart generation.\r\n- Display: An extensive gallery module.\r\n- Feeder: Simple RSS feed generation API.\r\n- Fenfire: Comment system API. Provides nothing by itself, but allows easy comment integration.\r\n- Filer: File uploading API.\r\n- LightUp: BBCode and general post markup translater.\r\n- Liroli: User group supplement. Users can create and join groups, groups can be assigned special privileges.\r\n- LumiHub: A project hub and bug tracker.\r\n- Neon: A user front-end that allows account registration, logging and user management.\r\n- Purplish: Kusaba-like imageboard software combining static and dynamic content creation.\r\n- Reader: Simple blogging software with multi-user environment.\r\n- Reflector: Server file manager.\r\n- Shortcut: Easy URL-shortening.\r\n- Spectacles: A dynamic search engine, including a search API for other modules to tie int\r\n\r\n\r\nThe hooks system and dynamic loading of modules allows for complex, but yet simple to program and maintain interaction between modules, immensely amplifying their effectiveness.\r\nSeriously, it&#039;ll make my life a huge lot easier and a lot less messy.\r\n\r\nAnyway, that&#039;s all for now I guess.\r\nI&#039;ll call back once CORE and ESSENTIAL are stable.', 'TyNETv4,TyNET,Projects', '1330556977', 1, 1),
(44, 38, 'I Am a Sad Person', 'Alternative title: &quot;I Am a Happy Person&quot;.\r\n[comment] Oh boy here we go again. [/comment]', 'It&#039;s weird.\r\nWhenever I feel sad or depressed, it seems to me like I&#039;m more depressed than happy.\r\nHowever, when I feel happy, it seems as if I was happy most of the time and sadness was just &quot;an annoying depression thing that comes up sometimes&quot;.\r\nI don&#039;t know why I feel happy sometimes, and I don&#039;t know why I feel sad sometimes either.\r\nOf course, this can&#039;t be the whole truth.\r\n\r\nI always try to filter out some reasons as to why I am in either state of mind, but I never truly figure out why.\r\nThere&#039;s simple reasons for why I am sad: Lack of appreciation, lack of attention, self critique, bad grades, inability to master something, saying or doing wrong things and so on. I&#039;m sure you&#039;re familiar with most of these feelings and impressions yourself.\r\nOn the other hand, there&#039;s also simply reasons for why I am happy: Being able to converse with people I like, generally having an easy life, learning exciting and new things, good grades, being able to overcome and obstacle, helping others etcetera. Here as well, I am sure you know all these feelings.\r\nAnd it just seems logical that they might explain my phases or moods, right?\r\nBut here&#039;s the thing: Almost all of the reasons for why I am sad seem silly. I shouldn&#039;t care about not getting enough attention and just do my thing. Self-critique shouldn&#039;t be taken with much weight at all either, bad grades can happen to anyone and it could&#039;ve been an accident, you encounter obstacles you can&#039;t overcome on the first try all the time, saying or doing wrong things is also something everyone does, just because we&#039;re a bit stupid.\r\nThese are all extremely trivial reasons.\r\n\r\nSo why the hell do I get sad and depressed over them? They could all be easily disregarded without losing much of value for doing so.\r\nBut yet [i]I just can&#039;t do it[/i]. I&#039;m still trapped in this ridiculous impression that these things are worth worrying about and have the weight I give them.\r\nWhenevr I get sad, I quickly reach the question &quot;Well why do you get depressed then?&quot;\r\nTo which the answer is most of the time something like this:\r\n&quot;You are a perfectionist and you want to be able to do [i]everything[/i] completely right, without flaws and without failure. The reason why you give such simple things so much weight is because for you, even the slightest mistake is devastating. It could mean the end of the world, right? What are people going to think of you, now that you messed up this simple anatomy sketch? Surely they must be disgusted at your miserable display. How [i]could[/i] you! Or look at these grades! My goodness, and people say you&#039;re intelligent? Don&#039;t make me laugh!&quot;\r\nAnd so on, going to the point where everything I touch looks like a turd.\r\n\r\nThe solution seems so simple. No, it [i]is[/i] so simple.\r\n[img=http://img.tymoon.eu/lol/13131839861070.jpg]class=flRight[/img]\r\nJust do it. Depression is the ultimate productivity killer. It makes me un-inspired, demotivated and just overall a sad guy nobody wants to really give a shit about, because nobody likes whiners. There is nothing to gain from being sad.\r\nI am by no means saying that there&#039;s something wrong with critiquing myself!\r\nThere however is something terribly wrong with getting sad about making mistakes and generally having [i]imperfections[/i]. In order to analyse and fix problems properly, I need to have a clear mind. A mind that isn&#039;t filled with idiotic thoughts and only works at a productivity rate so slim even a cat would be disgusted by it. \r\nDespite all of this, I often can&#039;t &quot;just do it&quot; and get on with my work.\r\nWhy is this?\r\n\r\nI don&#039;t know. I really just don&#039;t.\r\nAdditionally, all the trivial reasons I gave before don&#039;t really seem like a satisfactory answer for the sadness I feel. As I said, they feel [i]silly[/i] and look like stupid reasons for me to get sad about.\r\nSo yes. I don&#039;t know why I&#039;m sad.\r\n\r\nThe same goes for happiness. I can be ridiculously cheery sometimes, for no good reason. Of course, I would really prefer to stay happy rather than be sad, but that doesn&#039;t explain the reason for the happiness any more. (Side note for the retards who like to say &quot;It isn&#039;t just black and white, Nick!1! There&#039;s differing grades of happiness and sadness!&quot;: [spoiler]\r\nOk, look let&#039;s take this nice gradient here:\r\n[img]http://shinmera.tymoon.eu/public/feelinggradient.png[/img]\r\nOh wow! It almost looks like you could separate it into two big categories and use those to simplify the explanation process!\r\nWhy does this work? Because you&#039;re never exactly in the middle. You are always tending to one side or the other, in which case it only makes sense to describe things with words that categorize either side. These words and words like it that can have differing degrees of appliance (actually, all words) are [i]never[/i] meant as absolutes, unless specifically stated to be.\r\nOf course, you can talk about things that are &quot;pretty much in the middle&quot;, but then you&#039;d analyse a third state and not only two, which would introduce a different kind of gradient and a different kind of explanation.\r\nAnyway, rant over.\r\n[/spoiler] )\r\n\r\nSo yes.\r\nI don&#039;t know why and it bugs the living hell out of me.\r\nI want to be working, rather than having my mind thinking about the why all the time.\r\nHonestly, I doubt anyone can answer this question. But that makes it all the more silly to think about it in the first place again.\r\nAh, if only feelings weren&#039;t so damn inconvenient so often. Makes me think again about how imperfect and unoptimized evolution&#039;s product called &quot;humanity&quot; is, but I digress.\r\n\r\nAnother thing I just can&#039;t find a satisfactory answer for: Why the hell did I write this blog entry?', 'Mood,Happy,Sad,Why,What,The,Hell,Am,I,Doing,With,My,Life', '1330634327', 1, 1);
INSERT IGNORE INTO `bl_entries` (`entryID`, `FID`, `title`, `short`, `subject`, `tags`, `time`, `published`, `owner`) VALUES
(45, 55, 'TyNETv4 INIT Process', 'I think I&#039;ve talked about this before, but here&#039;s the process of initialisation in a bit more detail.', 'Let&#039;s start off with a graphic. Don&#039;t worry, it looks needlessly complicated at first, but it&#039;s quite simple and I&#039;ll explain it all in detail.\r\n[img]http://shinmera.tymoon.eu/public/luminate-INIT.png[/img]\r\nFirst comes the user of course, who requests some page from the server.\r\nApache takes this request and checks the URL. If it does not reference an existing file on the server, it passes it to mod_rewrite.\r\nThis module allows me to make non-existent pages exist. It does that by &quot;rewriting&quot; the URL and sending the data to another page that I can specify.\r\nIn the case of TyNET, all urls get redirected to decoder.php . Since it&#039;s a PHP file, apache will start up php and execute the file.\r\nThis first calls config.php, which sets up some required base constants like database credentials, directory paths and so on. It also loads a few base classes and opens the database connection.\r\nAfter that, the decoder translates the url into a usable format that TyNET operate on.\r\nSimply, this means that the subdomain specifies which module to load and the rest of the path is used as arguments for the module.\r\nAfter this, the triggerHook function of the Loader class is called, with CORE as calling module and the subdomain as hook name. That way, modules can register whatever subdomains they want.\r\nThe loader then checks all hooks that have been registered for the calling module and loads the modules associated with the hook.\r\nThe loadModule function then checks if the module class file exists, if so, it checks if an instance of the module has already been created.\r\nIf so, it simply returns the reference to the module and bails out. If not, it loads the class file, creates a new instance, saves the instance as a global variable (using the short variable as name), loads all the associated hooks from the db and stores them in the module, loads all modules from the required array, calls the moduleLoaded hook and finally returns a reference to the module instance.\r\nSince all modules are stored globally, they can easily be referenced from anywhere throughout the framework.\r\nI know global variables can cause a huge mess, but I don&#039;t use them for anything other than storing modules, so there shouldn&#039;t ever be any conflicts that aren&#039;t the fault of the module creator anyway.\r\nEither way, once the module is loaded, the triggerHooks function calls the function the module associated with the specific hook.\r\n\r\nLet&#039;s make an example.\r\nThe user requests the following page: http://blog.tymoon.eu/category/Bullshit\r\nThere is no such folder or file on the server, so it gets passed to the decoder. The decoder sees the trigger &quot;blog&quot; and calls the Loader function triggerHook with the following arguments: hook=&quot;INITblog&quot;,source=&quot;CORE&quot;,destination=&quot;&quot;,args=&#36;URLargs\r\n(So yes, the CORE is basically a module by itself as well!)\r\nThe Loader now checks all the hooks that are associated with &quot;INITblog&quot;. Usually this would be a module that provides a blog of some sort.\r\nWe&#039;ll take the module from the USERSPACE for this example: Reader. It registered a hook as follows: hook=&quot;INITblog&quot; source=&quot;CORE&quot; destination=&quot;Reader&quot; function=&quot;parseURL&quot;\r\nReader is loaded (as well as all modules it depends on) in and the function &quot;parseURL&quot; is called with the arguments from the decoder along.\r\nFrom there on it depends pretty much entirely on the module itself for what happens.\r\nUsually it&#039;ll depend on the Theme module and call its openPage and closePage functions, which create a theme and base layout, which allows for extremely easy page generation, as well as uniform layout throughout the site, if so desired.\r\nI&#039;ll get to the theme system another time though.\r\n\r\nSo yeah, I hope I could explain how the system works.\r\nYou&#039;ll probably notice that this system can easily create quite a large dependency tree, which could invite quite a few problems with sloppy programming.\r\nI doubt anyone (besides me) will write modules for this in the future though, so I don&#039;t think it&#039;s much of a problem. And even so, it could be easily resolved.\r\n\r\nA small note to finish off: Modules can register whatever hooks they want and offer whatever hooks they want. This allows a lot of flexibility, since modules can extend each other without having to fear much trouble of compatibility and so on.\r\n\r\nAnyway, yes, I am pretty damn happy with this system and I think it&#039;s quite a good solution.\r\n\r\nEDIT: Some changes to the system have been made that aren&#039;t reflected in the graphic above. Mainly the module class required array doesn&#039;t need to include the source path anymore.', 'TyNETv4,INIT,projects', '1330693477', 1, 1),
(46, 36, 'Faggotry', '09:51 #Stevenchan &lt; Malakin&gt; talk about faggotry and what it means to you\r\n\r\nWell ok.', 'I guess, first we&#039;ll talk about\r\n[b][size=3]Faggotry as an insult[/size][/b]\r\nI think calling someone a faggot is a brilliant insult. This is for multiple reasons.\r\nThe first reason is simply that the insult is so [i]lame[/i]. It really means pretty much nothing.\r\nThis makes it incredibly easy to identify people who easily get offended (and offend them even more for it). \r\nOn the other hand it also almost serves as a term of appreciation for people who know that it&#039;s one of the weakest insults.\r\nPeople on Stevenchan even pride themselves of being called faggots, if I may use that as an extreme example.\r\nIt&#039;s just lovely.\r\n\r\n[b][size=3]Faggotry as a &quot;way to be&quot;[/size][/b]\r\nPersonally I couldn&#039;t give less of a shit about people being actual faggots, or in other words: gay.\r\nSeriously, all that noise about gay people, gay marriage and whatever the fuck is going on at the moment more often annoys me than anything. Just let people be faggots if they are. \r\nI just don&#039;t want to care.\r\n\r\n[b][size=3]Pretending to be a faggot[/size][/b]\r\nThis is something I find to be an almost never ending pit of hilarity. I really don&#039;t quite know why.\r\nIt&#039;s probably just because it&#039;s so easy to create awkward scenarios with it and awkward is funny, especially when it comes to something as silly as this.\r\nEveryone who knows me a bit most likely also knows about the stuff I do with my friend Mithent (He&#039;s a great guy btw).\r\nI can&#039;t really figure out why, but every time I draw some kind of gay/lesbian/whatever picture of &quot;us&quot;, I just have to smirk because of the absurdity of it all.\r\n\r\nOn that note, I might as well say something about my actual orientation when it comes to sexuality.\r\nI am, for what I can figure out, asexual, with a demiromantic orientation. Meaning I have no interest in sexual intercourse, but do have a desire for romantic relationships, primarily ones that already have an emotional connection beforehand. \r\nOr in other words: I don&#039;t want to start anything with anyone who isn&#039;t already my friend. The gender doesn&#039;t really matter for me, by what I can figure out so far.\r\n\r\n[b][size=3]Final thoughts[/size][/b]\r\nFaggot and faggotry are great words. Use them often and use them for fun.\r\n\r\n[img]http://shinmera.tymoon.eu/public/faggot-flash.gif[/img]\r\n\r\nEdit: Totally forgot about the wordfilter changing g[comment] [/comment]ay to fabulous. Brilliant!', 'Faggotry,Why,Am,I,Writing,This,Again', '1330939183', 1, 1),
(47, 55, 'TyNETv4 Theme System', 'I promised to talk about the theming system, even if nobody gives the slightest fuck about it, so that&#039;s what I&#039;ll do.\r\n[comment]Trufax: Nobody really cares. Ever.[/comment]', 'The theming system comes packaged as a module, so it could be re-made in a better way by someone else, without requiring any hacking. Which is another brilliant thing about the hooks/module system.\r\nBut anyway, the ESSENTIAL packagespace theming module works quite simply for other people to use it.\r\nBasically all you need to do is this:\r\n[code]\r\nglobal &#36;t; //Access the global theme instance.\r\n&#36;t-&gt;loadTheme(&#36;themeName); //Optional, if not used, the default theme gets loaded.\r\n&#36;t-&gt;openPage(&#36;pageTitle); //Automatically creates the header, menubar and everything.\r\necho(&quot;This text is inside the content area&quot;);\r\n&#36;t-&gt;closePage(); //AUtomatically closes the content area, creates the footer and closes the document.\r\n[/code]\r\nThat&#039;s all. I am not making this process completely automatic because if I did, it would make hacking it extremely difficult and it wouldn&#039;t allow for text-only output or API output, which is something that can be quite profitable to have.\r\nAnother thing that it handles automatically is output compression and header-ahead. With the latter I mean that it automatically flushes the output once the header is written, which sends it to the client, decreasing the time the user has to wait to see a page.\r\n\r\nNow, how is a theme made?\r\nA theme package [i]requires[/i] the following files:\r\nconfig.conf\r\nheader.php\r\nfooter.php\r\n(The latter two can be named whatever you want though)\r\nAdditionally you can provide a folder for images, css files and javascript files that will get automatically loaded.\r\nA sample config file would look like this:\r\n[code]\r\nname:Super Special Awesome Theme\r\nversion:1.0\r\nauthor:Shinmera\r\ndescription:This is the BEST THEME.\r\nimages:images\r\nheader:header.php\r\nfooter:footer.php\r\ncss:awesome.css\r\n[/code]\r\nThe name, version, author and description are optional parameters you can use to be displayed in the theme manager.\r\nThemes can easily be installed. You simply need to place a folder with all theme files into the themes folder in the root directory.\r\n\r\nNow for the actual process in more detail.\r\nWhat happens during the whole process?\r\n\r\n1: The global_header file is included. It does the following:\r\n--1: Write the html page to be compatible with XHTML 1.0 transitional, including character set and all.\r\n--2: Open the page &lt;head&gt; block with the specified title for the title tag.\r\n--3: Link all necessary CSS files required by the theme.\r\n--4: Link the jQuery library.\r\n--5: Include the meta page that describes various meta tags that may help increase SEO.\r\n--6: Close the &lt;head&gt; block and open the &lt;body&gt; block.\r\n2: The header file specified by the theme is included. This usually writes the page header and opens a content block.\r\n\r\n3: The module writes all the content it needs to write.\r\n\r\n4: The footer file specified by the theme is included. This usually writes the page footer and closes the content block.\r\n5: The global_footer file is included. It does the following:\r\n--1: Link all the javascript files specified by the theme.\r\n--2: Link the jQueryUI library.\r\n--3: Close the &lt;body&gt; block.\r\n--4: Close the &lt;html&gt; block.\r\n\r\n6: The module can do whatever it wants, except write to the output.\r\n\r\nAfter that all the functions unwind and it all falls back down to decoder.php, which then induces a final flush of all data, closes the database connection and lets php die.\r\n\r\nThe inclusion of the jQuery and jQueryUI libraries seems a bit wasteful at first, but it pretty much levels out in the end.\r\nFirst of all, jQuery itself is extremely useful and is used almost everywhere I write js code. Second of all, jQueryUI provides a load of extensions for jQuery that make certain actions very easy. Third of all, they are linked to static google api URLs, which means they get downloaded over a CDN and can be/probably already are cached by the browser, so the speed impact is pretty much zero when it comes to loading files. Sure, the browser needs to execute the js as well, but that&#039;s almost negligible nowadays.\r\nFor extreme performance, you can always write your own Theming module or a specific page (As the Purplish (Chan) module does f.e.).\r\n\r\nIn the end, the Theming process isn&#039;t very complex and really nothing fancy, so I don&#039;t really pride myself with it.\r\nBut, it&#039;s still a relatively good system and easy to use.\r\nSo ya.\r\n\r\nThat&#039;s it.\r\n\r\nOh I forgot to mention: The Theming system triggers a couple of hooks that other modules can attach to, to provide better extensibility.\r\nThese hooks are: header-begin, header-end, body-begin, body-end.\r\nOf course, you can always also trigger a hook from within your theme header/footer.', 'TyNETv4,Themes,Theming,Essential,Projects', '1331046606', 1, 1),
(48, 34, 'Transcend Update', 'SSSSOOOO!\r\nIt&#039;s been quite a long time since I last talked about this, so here we are with a couple of updates about the project.\r\nI got some screenshots to deliver, a couple of new ideas for game mechanics and more stuff about the story to talk about.\r\nLet&#039;s get to it!\r\n[comment]\r\n[/comment]', 'Alright, so. The engine is in a mostly stable condition now. The collision system is still... well, old and thus kinda broken at times.\r\nIt&#039;ll stay that way until CAPE[sup]*[/sup] is finished.\r\nI mean, it DOES work, but it has some weird bugs in a few rare occasions.\r\nSo yes, CAPE isn&#039;t finished yet and there&#039;s still a lot to do there, but I&#039;m very excited about getting it done, so I&#039;m quite sure it&#039;ll come along nicely.\r\nAs for the main engine itself, there&#039;s been some rather large changes in the way things are done.\r\nI wrote my own texture loader that also supports deferred loading and is quite flexible. It could be a bit faster though, but I won&#039;t look for loading time optimization right now.\r\nAnother change is that I switched to optimized list classes and re-structured the world format a bit. All in all it should render faster.\r\nOne thing that I haven&#039;t done yet, but will change today is something @Malakin suggested to me, which should speed up drawing time some more if it works as intended.\r\nI also introduced a resizeable frame mode, using a standard AWT frame as OGL container. This introduces a problem with my GUI system, in that it is currently of fixed form and doesn&#039;t adjust well to a changing frame size. I don&#039;t quite know how to solve that problem efficiently yet, so if you have suggestions, please tell me, I&#039;d love to hear about it!\r\n\r\nOne of the major problems I&#039;ve had recently was the texture wrapping problem that OpenGL has. Some textures randomly warp from one edge to the other, which produces ugly lines on transparent textures. The solution to that would be to generate the textures with clamping on. However, that doesn&#039;t quite work as of now since the system doesn&#039;t know if the loaded texture is going to be used in a tiling mode or not.\r\nI&#039;ll have to see about fixing that somehow.\r\nMy first approach with rendering everything to an FBO doesn&#039;t seem to work, but it would also be a bad idea since it could produce huge textures. I think I&#039;ll go for an automatic texture tiling detection instead, which requires a couple of changes in the way textures are loaded and displayed though, but I&#039;m sure I can figure something sweet out.\r\n\r\nAs for the graphics and textures themselves, I re-worked most of them and got a nicer set done now. There&#039;s still a huge lot to be done though and I&#039;ll really have to spend more time creating environment textures.\r\nThe problem is that it&#039;s a very tedious thing to do, especially since I don&#039;t have much experience, which means I have to remake a texture quite a couple of times until I get it somewhat decent.\r\nAh well, I&#039;ll get better with time, I just wish I were more patient about it.\r\n\r\nI&#039;m thinking of deepening the story and making it a lot darker.\r\nOne of the main ideas is that the towns I planned can be turned into ghost towns if all the people have been killed.\r\nThe other main idea behind it is that all the monsters you fight are actually humans that were infected with a disease spread by the king.\r\nI might even introduce a kind of inventory system so you can buy items and trade things and so on.\r\nAs for how the &quot;infection&quot; is handled. Well, the ink infects a certain area. If you defeat enough enemies in the area, it becomes temporarily healed.\r\nMeaning it&#039;ll turn back to the ink state after some time, until you defeat the end boss of the world section.\r\nI&#039;m also planning on putting in a swamp area that is permanently cursed. It would be really interesting to see how the different abilities of the characters come in handy there and how i could add separate ways to travel around it.\r\nSo yeah. Exciting stuff going on!\r\n\r\nNew game mechanics include movable blocks, horizontal platforms that can push the player around and switches.\r\nI&#039;ll have to expand on those and find a good solution to incorporating them into the editor. Probably through my event system.\r\nYeah that&#039;ll work. Hooray for coming up with solutions while writing about the problems!\r\n\r\nAnd now, have a couple of screenshots:\r\n[img]http://shinmera.tymoon.eu/public/transcend%20fight.png[/img]\r\n[img]http://shinmera.tymoon.eu/public/transcend%20desert.png[/img]\r\n[img]http://shinmera.tymoon.eu/public/transcend%20form.png[/img]\r\n[img]http://shinmera.tymoon.eu/public/transcend%20editor.png[/img]\r\n\r\nA rather large thing to announce is this:\r\nI&#039;m looking for other people to join me on Transcend! Yeah, that&#039;s right, I&#039;m finally trying to open myself more to the public.\r\nIf you&#039;re interested in playtesting or even contributing some code or artwork to the project yourself, contact me and we&#039;ll figure something out.\r\n\r\n\r\nSo yes, I think that&#039;s all for now. \r\nI&#039;d love to hear some feedback or thoughts on this from you, so please, write a comment.\r\n\r\n[sup]*[/sup][spoiler]\r\nIn case I forgot to mention what CAPE is sometime, let me explain again:\r\nCAPE stands for Collision And Physics Engine and is an extension to the Transcend engine. It features time-perfect collision detection and realistic rigid body reaction.\r\nOr well, that&#039;s what it WILL feature once it&#039;s done. Right now I&#039;ve got collision detection between circles and lines and the main collision detection loop done. Yay, lots of work![/spoiler]', 'Transcend, Game, Development, Design, Story, Collaboration, Team', '1332337321', 1, 1),
(49, 55, 'TyNETv4 Update', 'I thought I&#039;d write an update here since I&#039;ve made quite the progress.\r\nBASE and ESSENTIAL packagespaces are finished, as well as the Neon User module.\r\nI also have some new plans and other stuff to talk about.\r\n[comment]\r\n[/comment]', 'Well then.\r\nThe BASE system is done and running well. \r\nThis includes url deparsing, module loading, hook triggering, user authentication and user permission control.\r\nTHE ESSENTIAL provides an Administration module to manage modules, hooks, global settings and access other module&#039;s settings.\r\nIt also provides the Themes module that I&#039;ve talked about in a previous blog entry and a User management module that allows administrators to control user accounts, add profile fields and create permission groups.\r\nThe Themes module also includes an online Theme editor that allows for quick changes to the sourcecode of theme files.\r\n\r\nAgain, the BASE and ESSENTIAL spaces don&#039;t provide any user front-end, but only back-end.\r\nThe only front-end module that I have finished so far is Neon, which gives a user front-end.\r\nThis is to say, it adds user profiles, user account management and a friend system. \r\nHere&#039;s a screenshot of what a profile looks like with the default theme:\r\n[img]http://shinmera.tymoon.eu/public/Screenshot%20-%2003262012%20-%2010:48:24%20PM.png[/img]\r\nAnd here&#039;s one of the user settings pages:\r\n[img]http://shinmera.tymoon.eu/public/Screenshot%20-%2003262012%20-%2010:48:57%20PM.png[/img]\r\nA bit sparse, I know. However, this&#039;ll be spiced up in no time once I finish more modules.\r\n\r\nAs for what&#039;s up ahead right now...\r\nI won&#039;t be working on Liroli (The user group module) right now as I don&#039;t see it as necessary or important.\r\nI&#039;ll start with porting over the comment module from v3 and then the gallery module.\r\nOnce more, I&#039;ll update as soon as I have something worth mentioning done.\r\n\r\nAside from new modules, there&#039;s a few minor things that still need to be done for BASE and ESSENTIAL.\r\nMainly it&#039;s testing the new package format and the automated installation procedure. I myself won&#039;t be needing that installation any soon, but I thought it would be a good addition if I ever decide to release it to the public.\r\nOther than that, the only ToDo annotation I could find in the sourcecode is exclusion permissions, so that certain permissions can be blocked.\r\nI&#039;ll see how that works out.\r\n\r\nI&#039;m also thinking of putting up a beta version so that I can get some beta testers early in the process, before I move on and have to go over everything again later because I&#039;m a derp.\r\n\r\nAnother improvement for the module system just came to mind for me, which would be a priority field, loading hooks depending on their priority from the list. That might be quite a good idea actually.\r\nI&#039;ll also really have to re-check everything and make sure it&#039;s sealed off tightly. At the moment I&#039;m just not sure if I added in all the necessary permission checks and user input sanitisation.\r\nI guess a testing phase is the best course of action at the moment.\r\n\r\nAnyway, That&#039;s all I have to say for the moment.', 'TyNETv4,Base,Essential,Neon', '1332928126', 1, 1),
(50, 39, 'Java Programming Setup', 'So, since I&#039;m starting a Java course over the internets, here&#039;s a blog entry to guide you through the setup steps to get ready for Java programming.', 'Alright, the setup is quite different depending on your operating system, so I divided into three categories, starting with windows, which most if not all of you will be using.\r\nThere&#039;s a part at the end of this blog that everyone should follow though.\r\n\r\n[b][size=5]WINDOWS[/size][/b]\r\nWe will be using the latest JDK1.6 version, which can be downloaded [url=http://www.oracle.com/technetwork/java/javase/downloads/jdk-6u31-download-1501634.html]here[/url]. JDK Stands for Java Development Kit.\r\nSome of you might know that there&#039;s already the JDK1.7 out, but since it&#039;s still a bit unstable, underdistributed and offers nothing new for us, we&#039;ll stay with good &#039;ol 1.6.\r\n\r\nWe won&#039;t be starting with an IDE out, and if you don&#039;t even know what that is, all the better.\r\nNext, download [url=http://download.tuxfamily.org/notepadplus/6.0/npp.6.0.Installer.exe]Notepad++[/url].\r\n\r\nInstall the two programs, but remember to copy the path to the JDK installation directory into your clipboard or well, just remember it.\r\nAfter both are installed, open the explorer and visit the JDK directory. In there should be a bin directory with a load of files and other stuff.\r\nCopy the path to this directory and open up the control panel.\r\nSet it to show all the things as icons, and open up &quot;System&quot;. Click &quot;Advanced System Settings&quot;.\r\nGo to &quot;Environment Variables...&quot;. It should look something like this:\r\n[img]http://windows7update.s3.amazonaws.com/wp-content/uploads/2011/12/Microsoft-Windows-7-environment-variables.jpg[/img]\r\nIn the lower list, search for &quot;Path&quot;, select it and press &quot;Edit...&quot;\r\nA new popup should show and in the Value field, go to the absolute end of it with the cursor. Append a semicolon if there isn&#039;t one already.\r\nNext, paste in the path to the JDK bin directory. Confirm with ok and all the other dialogues as well.\r\n\r\nWhat you did just now is tell windows to look in that directory for executables when using the command line.\r\nThis allows us to use applications like javac and javaw from the command line, without having to type in the full path.\r\n\r\nAnd... that&#039;s pretty much it! Now, skip on ahead to the GENERAL part for the setup.\r\n\r\n[b][size=5]LINUX[/size][/b]\r\nHere, you&#039;ll mostly have to check your distribution specific package manager.\r\nNowadays, it&#039;s mostly an icedtea or openjdk package, since the Sun JDK has been abandoned.\r\nSearch for the openjdk6 package or similar. Simply searching jdk or java might help as well.\r\nOtherwise, get a text editor of your choice. Novices might refer to gedit or kate, experienced people to emacs or vim.\r\n\r\nAnd that&#039;s all already. The path should be updated automatically.\r\n\r\n[b][size=5]MAC OS X[/size][/b]\r\nFor this, you&#039;ll have to either check your Mac OS X installation DVD or search for a JDK download online.\r\nI won&#039;t get into it, since I don&#039;t remember how to do it, but it should be fairly straightforward.\r\n\r\nAs for a text editor, TextWrangler is a good choice for now.\r\n\r\n[b][size=5]GENERAL[/size][/b]\r\nAfter installing your OS-specific JDK stuff and getting a neat editor, it&#039;s time to prepare our project folder and make sure everything&#039;s running well.\r\n\r\nFirst, create a JAVA folder at a location of your choice. In here, we&#039;ll make subfolders for each project and everything else.\r\nInside it, create a folder called IntroTest or something like that.\r\nNow, open up your text editor and paste the following into it:\r\n[code]\r\npublic class Test{\r\n    public static void main(String[] args){\r\n        System.out.println(&quot;Hello to the world, the universe and everything beyond!&quot;);\r\n    }\r\n}\r\n[/code]\r\nYou don&#039;t need to understand what it does at the moment, we&#039;ll get into it in the tutorial.\r\nSave this file as Test.java inside the IntroTest folder. Make absolutely sure to name it that way and nothing else, keep the capitalization and everything, or Java will complain.\r\nOnce you got it saved, open up the command prompt or terminal.\r\nWindows V/7 users: Start menu, search for: cmd , hit enter. A black window with some text should appear.\r\nLinux users: Open your terminal emulator of choice.\r\nMac OS X users: Open the Terminal app, which is somewhere in your applications folder.\r\n\r\nWhat you&#039;re seeing now will in the future be referred to as the terminal.\r\nIt&#039;s a purely text driven console, that allows you to do some very effective work though.\r\nEither way, here&#039;s some of the basic commands you need to remember:\r\nWINDOWS-SPECIFIC: dir Lists all files in the current directory.\r\nLINUX/MAC-SPECIFIC: ls Lists all files in the current directory.\r\nGENERAL:\r\n[code]\r\ncd [i]path[/i]          Changes the current path you are in. The path can be read left to the cursor.\r\njavac [i]file.java[/i]     Compiles the file you specified into a file.class that can be executed.\r\njava [i]file[/i]          Runs the specified class file/program.\r\n[/code]\r\n\r\nTo the left of the blinking cursor you&#039;ll see some information as to A: Where you are and B: Who you are.\r\nThis depends a bit on your setup, but usually you&#039;ll have the current path and your username displayed in the prompt.\r\nFor linux or mac, let&#039;s assume it shows:\r\nlinus@linuz: ~ %\r\nThis means, I&#039;m user linus on the machine linuz in the directory ~ (Which is my user home directory). Of course, your output might differ from this.\r\nFor windows, it&#039;ll probably show something like:\r\nC:\\Users\\linus\\&gt;\r\nThis means I&#039;m currently on the path C:\\Users\\linus\\\r\n\r\nWith cd, as mentioned before, you can switch the directory. When starting up the command prompt, you first always need to switch to our JAVA directory that we created earlier on.\r\nF.e. if you created it in your home directory, simply type [i]cd JAVA[/i] and hit enter.\r\nIt should show something like ~/JAVA or C:\\Users\\linus\\JAVA now. If you placed it in any other directory, switch to that.\r\nIf you need to change to a directory above the current one, use [i]cd ..[/i] (yes, that&#039;s two dots.) In case you&#039;re lost, use [i]ls[/i] or [i]dir[/i] to give you a list of the files in your current folder, which might give you a clue.\r\nOnce you&#039;re in the JAVA directory, type [i]cd IntroTest[/i] and then [i]javac Test.java[/i] . This should compile our file into something we can run.\r\nOnce it&#039;s done, type in [i]java Test[/i], which should show [i]Hello to the world, the universe and everything beyond![/i] .\r\nIf that all works, then you have successfully set up your environment and are ready to code!\r\nIf not, check that you did everything right and ask me for help or write a comment here.', 'Java,Tutorial,Programming,Setup', '1333635135', 1, 1),
(51, 55, 'Functional Extensive Markup', 'In the modern web 2.0 user interaction is everywhere.\r\nOne of the most important things a user can do is to contribute content.\r\nAnd this content comes, most of the time, in form of text.\r\nHowever, there are certain ways to enhance the plain text experience and make the text more appealing, make it portray more information and just generally make it easier/better to read.', 'The problem comes pretty much immediately.\r\nHow do you let the user enhance his text?\r\nThe most direct approach would be to simply let the user input HTML, since the website is going to be HTML as well, so there&apos;s no need to parse it.\r\nHowever, HTML has some glaring issues. The first being that you usually don&apos;t want the user to have unlimited control over what he can do. After all, he could easily screw up the entire website layout with some messy HTML code. So you need to sanitize it. HTML sanitization is a url(http://stackoverflow.com/questions/1732348/regex-match-open-tags-except-xhtml-self-contained-tags/1732454#1732454){very messy topic} and  is really not an easy task.\r\nThere are libraries out for that though, so you could simply use one of those and get your sane and safe code delivered pronto.\r\nHowever, the sanitization isn&apos;t the only problem HTML input faces. The second is that it&apos;s not the easiest thing to learn.\r\nThis may sound a bit ridiculous (it does to me), but again, the first principle of webdesign, b{Don&apos;t Make Me Think} gives some heavy problems here, since the user has to learn HTML first.\r\nThe third problem is that HTML is, simply, terrible to read. It doesn&apos;t look nice and the forest of tags can quickly become very frustrating to look at.\r\n\r\nSo HTML might not be the best choice after all. What else do we have?\r\nMany forums and message boards use BBCode, which is very similar to HTML, but offers a more streamlined, simple and sane approach.\r\nBBcode can be learned pretty quickly and has tags that make sense to the user.\r\nParsing is pretty easy as well, since you can use simple regex to do most of the work (This approach has its flaws, but it holds water for most things).\r\nThe problem of sanity is pretty much eliminated altogether since you can select a specific few tags to parse and make sure you parse them correctly.\r\nThe remaining problem, while it&apos;s not as bad as with HTML, is the "weight" of the markup. It just doesn&apos;t look easy on the eyes.\r\n\r\nAlright, BBCode is better, but still suffers from some of the same flaws as HTML.\r\nThere&apos;s got to be another way though, right? And there is. The next widely used approach is url(http://daringfireball.net/projects/markdown/syntax){Markdown}.\r\nMarkdown is very different to the previous two and tries to give more readability.\r\nIt has some pretty handy features and is very easy on the eyes, but you really need to learn what which characters do and well, how to get around in the text.\r\nLuckily for markdown, many of the formatting characters are intuitive, but it&apos;s still a hassle to get used to it, especially when you&apos;re used to tagged markup.\r\nSo markup trades ease-of-learning for simplicity and it does a very good job at it.\r\n\r\nHowever nice Markdown is, it&apos;s not appealing to me for the simple fact that I still often find myself confused about what is what and in question have to hit up the syntax instead of getting a simple clue from a tag about what it does with your text.\r\nMarkdown also suffers from the fact that it isn&apos;t as easy to extend, since the amount of choices you have is very limited.\r\nIn BBCode for example you can simply add more tags at will, specify a certain HTML equivalent and voilÃ , you got more markup choices.\r\nOf course, I wager that there&apos;s more markup systems out there that I&apos;m not aware of. If you know one, tell me! I&apos;d be interested to know!\r\n\r\nSince none of these choices are very appealing to me, what I decided to do is make my own markup language, FEM. It orientates itself on argol-style function calls. That is to say, it kinda looks like code in a way.\r\nWhich of course makes it very appealing to me as a programmer (I however can&apos;t shake the feeling that this markup language must have been already made in some fashion).\r\nSo, what&apos;s the ups and downs for FEM?\r\nFEM takes the concept of BBCode markup and strips some of its heaviness off, as well as some other slight nuisances.\r\nLet&apos;s take a look by example. Here&apos;s a standard HTML formatted text:\r\ncode{\r\n&lt;strong&gt;&lt;font size="6"&gt;&lt;u&gt;So Lemme Tell You Something&lt;/u&gt;&lt;/font&gt;&lt;/strong&gt;\r\n&lt;img class="floatLeft" src="http://stevenarch.tymoon.eu/fab/thumb/130963228092s.png" /&gt;\r\n&lt;p&gt;\r\n  This is a load of &lt;strong&gt;bollocks&lt;/strong&gt; &lt;em&gt;especially&lt;/em&gt; since you have absolutely&lt;br /&gt;\r\n  &lt;strong&gt;NO&lt;/strong&gt; idea what you&apos;re talking about. Seriously, just shut the fuck up.&lt;br /&gt;\r\n  I am telling you for the last time. &lt;strong&gt;&lt;em&gt;SHUT THE FUCK UP, SHINMERA, YOU DON&apos;T KNOW ANYTHING!!1!1&lt;/em&gt;&lt;/strong&gt;\r\n&lt;/p&gt;\r\n}\r\nAnd the equivalent in (possible) FEMarkup (which is parsed to the exact same thing above):\r\ncode{!{\r\nb{size(6){u{So Lemme Tell You Something}}}\r\nimg(left){http://stevenarch.tymoon.eu/fab/thumb/130963228092s.png}\r\np{\r\n  This is a load of b#bollocks i#especially since you have absolutely\r\n  b#NO idea what you&apos;re talking about. Seriously, just shut the fuck up.\r\n  I am telling you for the last time. b{i{SHUT THE FUCK UP, SHINMERA, YOU DON&apos;T KNOW ANYTHING!!1!1}}\r\n}\r\n}!}\r\nI&apos;m saying possible here since you can define the tags however you want.\r\nSo what&apos;s the difference?\r\nThe first thing to mention is simply that the text is enclosed in brackets, rather than tags.\r\nThis also eliminates the closing tags. There really is just no need for them anyway.\r\nIn fact, specific closing tags will only add problems, like so:\r\ncode{ &lt;strong&gt; &lt;em&gt; This is invalid! &lt;/strong&gt; &lt;/em&gt;}\r\nThe only benefit they have is a little more clarity to mark your blocks with, but honestly, that&apos;s hardly an argument as long as proper indentation is used.\r\nThe next thing to note is the way arguments for the tags are specified. And that&apos;s simply by including them in a list inside regular brackets after the tag.\r\nSo the HTML equivalent of \r\ncode{ &lt;a href="http://lol.com"&gt;Whatever&lt;/a&gt;}\r\nIs simply (for example) this:\r\ncode{!{ url(http://lol.com){Whatever}}!}\r\nThere&apos;s also support for multiple arguments, for a hypothetical function like this:\r\ncode{!{ quote(username,postID){Quotetext}}!}\r\n\r\nThere&apos;s also a way to escape formatting (which isn&apos;t really possible in HTML, unless you replace every occurrence of &lt; and &gt; so they don&apos;t get parsed):\r\ncode{ !{This is how you can format things: i{Makes me italic} and b{Makes me bold} try it.}! }\r\n\r\nSo yeah, it&apos;s a pretty neat system and I like it quite a lot.\r\nI don&apos;t know how easy to learn/read it is for other people, but I hope it&apos;s not much more difficult for them than it is for me.\r\nOf course, this isn&apos;t as clean as markdown, but it&apos;s still quite a step forward from HTML/BBcode.\r\nFEM will be the default markup language for TyNETv4. I have already implemented a parser for it.\r\n\r\nOne thing I didn&apos;t mention so far are WYSIWYG editors that are making themselves more and more present throughout the web.\r\nThe reason why I didn&apos;t mention them is because they still use a markup in the background and I personally hate WYSIWYG editors.\r\nMy hate mainly comes from the fact that it&apos;s often less WYSIWYG than you&apos;d like and I simply prefer to see code and be able to type how I want the text to be instead of clicking around.\r\nOf course, for regular users a WYSIWYG is probably the best choice. I might make one for FEM if I get really really bored some day, but I doubt it.', 'Markup,BBCode,HTML,Markdown,FEM,FunctionEM', '1334335480', 1, 1),
(52, 34, 'Transcend in Detail - Part 1: MainFrame and INIT Sequence', 'In an effort to give some more insight into the actual details and workings of the Transcend Engine, instead of only touching on the surface of things and talking about my intentions, I will start this series of blog entries. I&apos;ll go through as many aspects of the engine as possible, with as little code used as I can.\r\n\r\nThe first thing to talk about is the MainFrame and the engine&apos;s INIT sequence. What does this even mean?', 'The MainFrame is the "global" part of the engine. It stores all necessary global variables that need to be shared between resources, contains the main render and update loops, as well as the INIT sequence. The INIT sequence takes care of loading all the required resources, setting the appropriate OpenGL flags, opening the render window, loading the game configuration constants and setting up the input system.\r\nSo let&apos;s take a closer look at these two.\r\n\r\nb{size(16){Main Frame}}\r\nThe main purpose of this is to give all other classes access to important data. It sort of serves as a "globals" storage. Now, I know having global variables is bad and can easily lead to spaghetti code. However, sharing resources is an absolute necessity in a game engine. Objects need to be able to communicate with each other and passing in every needed object into the constructor of a new object is just ugly as well. The thread access problem (multiple objects accessing the same thing at the same time) is mostly avoided by the fact that the objects should only change themselves, but not others. If they need to affect another object as well, they need to call out an event trigger. I&apos;ll get to the event system in another blog post though. For the moment, remember that I am not happy with globals either, but they&apos;re very hard to avoid in such a scenario (At least take comfort in the fact that most of the objects are also constants).\r\nNow let&apos;s take a look at what the MainFrame actually contains.\r\ncode{\r\nDEFAULT_DIM     Array of default frame dimensions.\r\nDISPLAY_WIDTH   Current frame width.\r\nDISPLAY_HEIGHT  Current frame height.\r\nDISPLAY_ASPECT  Current aspect ratio.\r\nCLEARCOLOR      Background clear colour.\r\nACSIZE          Anti-aliasing factor.\r\nFPS             Frames Per Second.\r\nUPS             Updates Per Second.\r\nBasedir         Base directory that contains all game data.\r\nConst           An object storing all the variables in the game configuration.\r\nFileStorage     This is an indexer that automatically searches through the basedir and indexes all files for easy access.\r\nTFrame          The new Frame (since 1.65) that renders the OpenGL view.\r\nWorld           An object containing all World instances. It also handles loading and saving from/to a tw file, as well as game states.\r\nInputEventHandler Handles all input events, such as Mouse and Keyboard and redirects them to the registered listeners.\r\nEventHandler    Handles all game events.\r\nCamera          Camera object that controls the current view. Can be attached to paths or objects in the world.\r\nEditor          The Editor pane that allows in-game modification.\r\nTexturePool     A pool giving access to all necessary textures and handles loading thereof. Also supports deferred texture loading (since 1.65).\r\nTextureRenderer An experimental class to render the scenery into a texture for later use and manipulation (since 1.65). Does not work properly yet.\r\nSoundPool       Another pool class to store sound files.\r\nFontPool        This is the last pool, in this case to store font objects that are needed to render text inside OpenGL.\r\nScriptManager   Imported from the NexT project, this stores and preloads the scripts needed for execution of the game.\r\nPlayer          The main player instance.\r\nMenu            A GPanel instance holding all other menu panels. I&apos;ll get to the GUI system in another blog post.\r\nHud             Same as for the menu, but instead holding the HUD objects.\r\nGameLog         This instance in particular displays all log events in the game as well, as long as debugging is activated (since 1.65).\r\nLoader          An important part of the system that takes care of all the deferred loading and displays a loading screen when necessary.\r\nUpdater         A simple thread class that contains the world update loop.\r\n}\r\nI hope the short explanation for each sums up their general purpose well enough. I&apos;ll get back to some of those in later posts. I should mention that I am by far not satisfied with how this is structured. Much of it is just sort of "thrown together" over time and I should get to refactoring things. This blog post actually already made me refactor a couple of things, so I&apos;ll probably clean it up some more as I go through the individual parts.\r\n\r\nb{size(16){INIT Sequence}}\r\nThe INIT sequence is just a fancy description for the startup process. There&apos;s 6 different states as of 1.65, which are described as follows:\r\ncode{\r\nINIT-0 The &apos;static&apos; part of the INIT handles all objects that get initialized and globalised. It makes sure that the LWJGL libraries get loaded, launches the indexer, loads the configuration and creates the Frame.\r\nINIT-1 This mode prepares all remaining objects and creates the OpenGL interfaces, such as the Keyboard, Mouse and Display. It also shows the DisplayModeChooser, which allows the user to change game settings.\r\nINIT-2 Finally, this part calls the initGame and initGL functions, launches the update loop and makes the Frame visible.\r\nUSER-0 After INIT-2, USER-0 is entered, which is the lower user level. This mode should be absolutely quiet, unless some kind of error happens.\r\nUSER-1 In USER-1, all resource loading is handled, it is the &apos;Loader level&apos;.\r\nDOWN-0 The first part of the shutdown sequence, which should clean up the OpenGL interfaces and dispose of as much as possible.\r\nDOWN-1 Finally, System.exit(0) is called. If this ever spews an error, oh my.\r\n}\r\nCurrently these modes are hard-coded in and simply follow the code flow. I am planning on creating an actual sequence class that should automate this process, as well as handle all kinds of different errors that might occur during the run. As I said before, there&apos;s a lot of refactoring to be done until I can jump to version 1.7.', 'Transcend, Game, Development, Design, INIT, Main, Frame', '1335961107', 1, 1);
INSERT IGNORE INTO `bl_entries` (`entryID`, `FID`, `title`, `short`, `subject`, `tags`, `time`, `published`, `owner`) VALUES
(53, 34, 'Transcend in Detail - Part 2: Input and Event Systems', 'For this part, let&apos;s take a look at the probable second most important aspect, which is actually getting input from the user and exchanging information between objects.\r\nThere&apos;s multiple ways to do this. Transcend uses the LWJGL Keyboard and Mouse classes to grab input. Since 1.65 it would also be possible to use Java&apos;s AWT/Swing event handlers.\r\nSo, let&apos;s see how this works then!', 'b{size(16){Input Event Handling}}\r\nBefore we get into event exchange internally, we&apos;ll take a look at how it works with user input.\r\nThe main class to look at for this is the InputEventHandler and the associated KeyboardListener and MouseListener classes. This system is built after the Java AWT/Swing event systems. What this means in specific is that if an object wants to know about new stuff happening at the keyboard or mouse, it needs to implement the according Listener interface. This interface provides the necessary functions that will get called once the associated event occurs. In the case of the KeyboardListener, that would be:\r\ncode{\r\n  public void keyType(int key);\r\n  public void keyPressed(int key);\r\n  public void keyReleased(int key);\r\n}\r\nkeyType gets triggered once the key is first pressed. keyReleased obviously once the key is released. keyPressed gets called once every update for as long as the given key is still being held down. Of course, keyPressed isn&apos;t really necessary since that could be emulated with a boolean and the update() function, but it&apos;s something that they event system keeps track of for you. However, once you implemented the interface in your class, you aren&apos;t quite done yet. Just as it is with Java&apos;s EventListeners, you still first need to register it in the InputEventHandler so that it knows to send events to it.\r\nA complete example would look something like this:\r\ncode{\r\n  class MyIEHEvaluator implements KeyboardListener{\r\n    public MyIEHEvaluator(){\r\n      ...\r\n      MainFrame.ieh.addKeyboardListener(this);\r\n    }\r\n    ...\r\n    public void keyType(int key){...}\r\n    public void keyPressed(int key){...}\r\n    public void keyReleased(int key){...}\r\n  }\r\n}\r\nAnd that&apos;s all it really needs. The key integer that gets transferred can be looked up in LWJGL Keyboard class. So if you need to check what kind of key it is, you can perform a simple comparison like so:\r\ncode{ if(key==Keyboard.KEY_DOWN){...}}\r\nHowever, once I started building the GUI system, I realized that that&apos;s not quite enough. Apparently the key mapping is not accurate and some of the keys don&apos;t go according to what the Keyboard class says they should be or they flat out don&apos;t exist in the class. This is why I had to implement a keyboard layout extension for the IEH. It supplies the function &apos;parseKeyToText&apos;, which tries to parse the key according to what is defined in a keyboard layout map. Currently I only got the one for my own swiss keyboard layout done, since I haven&apos;t had the time to do it for any other layouts. Of course, I&apos;ll have to do that sometime. As well. Either way, here&apos;s the swiss map:\r\ncode{\r\n#KEYBOARDLAYOUT CONFIG FILE\r\n0:&lt; &gt; \\\r\n2:1 + \r\n3:2 " @\r\n4:3 * #\r\n5:4 \r\n6:5 % \r\n7:6 & \r\n8:7 / |\r\n9:8 ( \r\n10:9 ) \r\n11:0 = \r\n12:- _ \r\n13:= =\r\n26:&apos; ? \r\n27:^ ` ~\r\n39:Ã¼ Ã¨ [\r\n40:Ã¶ Ã© {\r\n41:&#36; ! ]\r\n43:Ã¤ Ã  }\r\n51:, ; \r\n52:. : \r\n146:: :\r\n147:_ _\r\n}\r\nIt is built up by the essential structure of KEY_INTEGER:normal_char shift_char alt_gr_char . Which means that the LWJGL integer key of 0 corresponds to a &lt; with no modifiers, to a &gt; if shift is pressed and to \\ if altgr is active. I noticed some further problems though, which is that mysteriously, some keys seem to correspond to the same integer value, even though they show different characters. This is quite odd and I haven&apos;t found any explanation or workaround for this behaviour. I hope the LWJGL guys fix this sometime soon or I&apos;ll have to switch to an AWT powered system after all. I&apos;ll also probably add some sort of system to make the creation of layouts available to the user as well.\r\n\r\nThere is one last thing the IEH is good for, which is secondary keyboard layouts, which describe the actual buttons used to play. This is so that the user can choose between layouts and even define his own, if he so pleases. Just the same, I still need to implement a GUI for this so that the user actually has something to modify it with, other than changing config files with a text editor. The way this layout file is built up is very simple:\r\ncode{\r\n#KEYBOARDLAYOUT CONFIG FILE\r\nLEFT:30\r\nRIGHT:32\r\nUP:17\r\nDOWN:31\r\nJUMP:57\r\nATTACK:200\r\nSWITCH:15\r\nMAP:16\r\nUSE:18\r\nRUN:42\r\n}\r\nThis is the &apos;righty&apos; layout, which uses the AD keys to walk, up-arrow to attack, space to jump and shift to run. Internally you simply have to call the getPlayerKey function to get the associated integer value. EG:\r\ncode{ if(key==MainFrame.ieh.getPlayerKey(&apos;LEFT&apos;)){...}}\r\nSo there&apos;s that.\r\n\r\nThere is currently no support for gamepads or joysticks, but I&apos;ll surely add this at a later point.\r\n\r\nb{size(16){Event Handling}}\r\nNow for the event handling system. This comes in handy for internal programming, where it&apos;s important to keep classes as isolated from each other as possible.\r\nThis can be very difficult to do properly in an environment where interaction is one of the core elements. The approach with events brings the advantage of isolation, independence and flexibility, but the disadvantage of loss in speed. But now let&apos;s take a look at how this actually works.\r\nVery similar to the Input system, this works by implementing an interface, in this case the EventListener and then registering this interface. Example:\r\ncode{\r\n  class MyEHEvaluator implements EventListener{\r\n    public MyEHEvaluator(){\r\n      ...\r\n      MainFrame.eh.registerEvent(Event.PLAYER_ATTACK,0,this);\r\n    }\r\n    ...\r\n    public void onEvent(int event,int identifier,HashMap&lt;String,String&gt; arguments){...}\r\n    public void onAnonymousEvent(int event,HashMap&lt;String,String&gt; arguments){...}\r\n  }\r\n}\r\nHowever, unlike with the Input system, this time you need to specifically tell the EventHandler, which event you want to listen for. You can also add in a priority (in this case 0). The higher the priority, the earlier you get to hear of the event. The event types are defined in the Event class and are a simple integer constant. This is to avoid confusion. You can still trigger or register for unknown events, but it might lead to compatibility issues. There&apos;s two ways to trigger events, one is anonymously, the other is from a world object out. Every instance in the game world gets a unique worldID (I&apos;ll get to that in a later post), that can be used to retrieve the appropriate object from the world. Triggering an anonymous event works like so:\r\ncode{MainFrame.eh.triggerAnonymousEvent(Event.PLAYER_ATTACK,null);}\r\nThe null in this case is the empty arguments HashMap. Sadly, since Java doesn&apos;t support rest or keyword arguments, you need to create a HashMap every time you want to send some arguments along the way. Triggering a non-anonymous event works similarly:\r\ncode{MainFrame.eh.triggerEvent(Event.PLAYER_ATTACK,this.wID,null);}\r\nNote that this has to be an object that extends the BElement class (To which I&apos;ll get later on as well). You can also use the EventHandler to trigger events for specific objects in the world, although that would defy the openness of the system.\r\n\r\n\r\nThere&apos;s some optimization and cleanup to be done in both systems that I should get to sometime later. It&apos;s not a pressing issue at the moment, but there&apos;s certainly still things to be done.\r\nSo yeah, cool I suppose.\r\nNext time: World storage, loading and everything to do with the actual game world.', 'Transcend, Game, Development, Design, Event, Handling, Input, Ke', '1336052230', 1, 1),
(54, 34, 'Transcend in Detail - Part 3: Game World and Entities', 'Now for something a bit more interesting than the last two entries. Actually storing the game world and accessing the data related to it is probably the most important task in the whole system.\r\nSo how does it work in Transcend?', 'There&apos;s three classes that we need to take a look at: World, WorldLoader and Element.\r\n\r\nb{size(16){Element Class}}\r\nEvery entity in the game world has to be derived off the Element class. Which is to say that it has to extend the Element class in one way or another. It brings all the base properties and functions all objects should have at a minimum. There&apos;s a couple of other classes that are important as well and extend the Element class, such as Block, Entity and Tile. Every object in the world extends one of these three and is categorized as such. The Element class itself extends yet another class, the BElement. I&apos;ll explain the reason for this later on. Now, let&apos;s take a look at the BElement first.\r\ncode{\r\npublic class BElement{\r\n    public int wID = -1;\r\n    public String name = "element";\r\n    public double x=0,y=0,z=0;\r\n    public int w=0,h=0;\r\n\r\n    public void init(){}\r\n    public void draw(){}\r\n    public void update(){}\r\n    ...\r\n    public void setOptions(HashMap&lt;String,String&gt; options){...}\r\n    public HashMap getOptions(){...}\r\n    public boolean checkInside(double ax,double ay,double aw,double ah){...}\r\n    public Vector getCollisionPoint(Ray r){...}\r\n}\r\n}\r\nThe wID is, as stated in the previous entry, the unique ID that this element is referenced by in the World. The name string is a simple description, which will be used in the Editor later, and finally we have the coordinates and dimensions of the object (note that Transcend focuses on 2D games).\r\nThe first three functions are pretty important. We have to use an init function instead of a constructor here since we cannot safely know the object&apos;s wID before it is created, so this function gets called once the wID is set and the object is added into the World&apos;s object list. The draw function gets called for every draw step and the update function for every update tick.\r\nThe setOptions function is used to set the specific properties that are read out of the world file. This reduces the amount of code used in the world builder, since it relies on the class to do it for itself and it reduces the dependency. The getOptions function does the reverse, so we can simply read out the necessary variables to save the world again. Finally we have two collision specific functions. checkInside simply checks if a region is inside this object&apos;s bounding box. getCollisionPoint goes a step further and returns a precisely calculated collision point, at which the ray hits the bounding box.\r\n\r\nNow let&apos;s see what the Element class adds:\r\ncode{\r\npublic class Element extends BElement{\r\n    public Animation drawable = new Animation();\r\n    protected double solid=1.0;\r\n    protected double health=100; \r\n    ...\r\n    public void draw(){drawable.draw((int)x,(int)y,w,h);}\r\n    public Element check(double ax,double ay,double solidity){...}\r\n    ....\r\n\r\n}\r\n}\r\nDoesn&apos;t look like much, but there isn&apos;t very much to add ontop of a most basic element anyway. What we have here is an Animation instance, which I&apos;ll get to in detail another time. For now, just note that it automatically handles all texture drawing operations, as well as animation. The draw function gets overridden accordingly to directly utilize the drawable. We have another collision related function, which in this case checks if anything in the world is inside the bounding box and if so, returns the first found object. If not it returns null.\r\nThere&apos;s a couple of dots here, those simply signify shortcut functions and get/set-ers. Nothing interesting.\r\n\r\nSo yes, basically, every object in the scene possesses a coordinate, a dimension, a drawable and a couple of collision functions. Not too fancy, but it&apos;s a bit too general, so we&apos;ll take a closer look at what the three classes I mentioned earlier do.\r\ncode{\r\npublic class Entity extends Element{\r\n    public static final int STATUS_NONE = 0x0;\r\n    public static final int STATUS_IDLE = 0x1;\r\n    public static final int STATUS_MOVE = 0x2;\r\n    public static final int STATUS_JUMP = 0x3;\r\n    public static final int STATUS_ATTACK = 0x4;\r\n    public static final int STATUS_DEFEND = 0x5;\r\n    public static final int STATUS_DIE = 0xF;\r\n\r\n    public double atk,def,vx,vy;\r\n    public int status=STATUS_NONE;\r\n    ...\r\n    public void update(){drawable.update();}\r\n}\r\n\r\npublic class Block extends Element{\r\n    protected boolean moveable;\r\n    ...\r\n}\r\n\r\npublic class Tile extends BElement{\r\n    protected Animation drawable = new Animation();\r\n    ...\r\n    public void draw(){\r\n        drawable.draw((int)x,(int)y,w,h);\r\n        if(z&lt;0){\r\n            new Color(0f,0f,0f,(float)(-z/7.0)).bind();\r\n            AbstractGraph.glRectangle2d(x, y, w, h);\r\n            new Color(1f,1f,1f,(float)(z/10.0)).bind();\r\n            AbstractGraph.glRectangle2d(x, y, w, h);\r\n        }\r\n    }\r\n}\r\n}\r\nThe Entity class has probably the most interesting extensions. It provides a couple of status constants, attack and defense stats and some velocity variables. Also note that it overrides the update function to call the drawable&apos;s update function, which will advance the animation by a frame if needed. If you want to use the engine for something different or add other properties to the entities (movable objects) in the game, you&apos;d have to change things here so that it&apos;s equally preserved and doesn&apos;t result in casting hell.\r\nWell, there really isn&apos;t much to say about the Block class. Most blocks are fixed anyway, so the moveable variable isn&apos;t be needed a lot.\r\nThe Tile seems a bit more interesting again. Instead of extending the Element class, it goes for the BElement. This rids it of a couple of unnecessary functions and variables, as well as differentiates it from the rest, as it shows that it isn&apos;t something to interact with. It is purely background/foreground. However, it still needs to be able to display something, so we need another Animation instance. The draw function in this also automatically adds a little shadow, depending on how far back the tile is positioned, which creates a quite nice, automatic depth effect.\r\n\r\nb{size(16){World Class}}\r\nStoring the objects is quite straight forward. There&apos;s a master ID list that holds all uIDs that are still present. Then there&apos;s three maps (id -&gt; object) to store the three different kind of objects. To extend functionality, there&apos;s also another map which stores string -&gt; id references so you can assign a name to a certain object, which allows for interaction defined in the world config. In order to speed things up and get some better performance out of it, we aren&apos;t using the standard ArrayList / HashMap classes, but rather the classes provided by the Trove java library. It is designed for faster access and primitive type maps/lists. Additionally, it also allows to iterate over a list by specifying a procedure class, which speeds up the drawing and updating functions as well. All functions are synchronized, so that there won&apos;t be any mess-ups with the multi-threaded update and draw loops. Here are the most important functions:\r\ncode{\r\npublic class World{\r\n    ...\r\n    public int addBlock(Block block){...}\r\n    public int addEntity(Entity entity){...}\r\n    public int addTile(Tile tile){...}\r\n    public int addBlock(Block block,String name){...}\r\n    ...\r\n    public boolean isBlock(int wID){...}\r\n    ...\r\n    public BElement getByID(int wID){...}\r\n    public BElement getByName(String name){...}\r\n    public void delByID(int wID){...}\r\n    ...\r\n    public void update(){...}\r\n    public void draw(){...}\r\n\r\n    final class UpdateProcedure implements TObjectProcedure{...}\r\n    final class DrawProcedure implements TObjectProcedure{...}\r\n}\r\n}\r\nBasic adding, deleting, checking and whatnot. The Update- and DrawProcedure are used, as I mentioned before, for Trove to speed up iterating a tiny bit. They are used in the update and draw functions respectively. These two functions get called by the according MainFrame loops.\r\n\r\nStorage is nothing really fancy, but there you have it.\r\n\r\n\r\nb{size(16){WorldLoader Class}}\r\nAnd now we finally get to the WorldLoader class which is responsible for loading and saving a world, as well as loading and saving game states. The most interesting part is the world format itself, although it too, is very simple and straight forward. Here&apos;s an example:\r\ncode{\r\n    #!transcend world file\r\n    world{\r\n        bgc: 0,0,255\r\n        camera: follow 1.0\r\n    }\r\n    player{\r\n        x: 25\r\n        y: 64\r\n    }\r\n    \r\n    BlankBlock{\r\n        x: -64\r\n        y: 0\r\n        w: 128\r\n        h: 64\r\n    }\r\n\r\n    Tile{\r\n        x: -64\r\n        y: 0\r\n        w: 128\r\n        h: 64\r\n        tex: wood\r\n    }\r\n}\r\nThis generates a world with blue background, a camera on zoom factor 1 that follows the player, the player positioned at 25,64 and a block at -64,0,64,64 with a texture overlaid. So yeah, the base structure is the entity name, followed by brackets and then a list of arguments. I might optimize and change this form sometime later. Probably also gonna add a basic gzip compression. Anyway, the amount of arguments doesn&apos;t really matter, as long as the x,y,w and h parameters are always there (for entities that is). Unnecessary arguments will simply get ignored.\r\nWhat the WorldLoader does in specific is very simple. It goes through the file, separates it into blocks, transforms the arguments into a HashMap, creates the required instance, calls its setOptions function and finally uses the World&apos;s appropriate add function to inject it into the scene. We&apos;ll get deeper into the loading process at a later point.\r\nTo save the game state, it simply calls the player&apos;s getStateData function, which returns a HashMap of properties. This is then saved with gzip compression inside the saves directory. Loading a save state is pretty much just the reverse of it. Simple!\r\nThere&apos;s one last thing that I forgot to mention. If an unknown Entitiy is being referenced in the file, the WorldLoader searches for classes in the blocks directory and if it can find one that corresponds to it, it dynamically loads this class into the virtual machine (using some reflection hackery) and then creates the appropriate instance. This would allow for dynamic extension of objects without having to change the entire Transcend source.\r\n\r\nWell, that&apos;s it for now, I&apos;m not sure what I&apos;ll write about tomorrow, probably the GUI system, since that&apos;s another large part of the whole thing.', 'Transcend, Game, Development, Design, World, Format, Entities', '1336167444', 1, 1),
(55, 36, 'Hello I Am Social Life Let Me Rape Your Time', 'img{http://johns-jokes.com/afiles/images/you-read-my-door-mat-that-is-enough-social-interaction-for-today.jpg}\r\nHumans are social creatures by nature. We are trained, from our childhood up and throughout our entire human life, to interact with others and to be socially active.', 'Now, of course, some are more invested in relationships and interaction than others, but we all still rely on other people to support us and exchange thoughts and ideas with us. Our ability to get along and help each other is an extremely strong point of our species. We have reached an immensely high point of interactivity, probably more so than any other animal on earth.\r\nHowever, as is with all things, it also comes with a drawback.\r\nKeeping relationships up and stable takes time. Increasingly so, the closer you get to the other person. The more people you know, the more time you spend interacting with them and the more time you lose that you could actually spend working. Now, of course, in most cases this isn&apos;t really much of a problem. Most people have fixed working hours, during which they work. The rest of the time is usually spent doing other chores, providing for oneself (eating, sleeping, etc) or doing social things like chatting, doing activities with others and so on. This is totally ok and understandable. After all, you already spent all the rest of your time working or doing other things. \r\nIt does, however, become a problem as soon as you aren&apos;t getting as much out of the time you put into social activities as you should. Obviously, you are at a problem of optimization. Something is taking up time and is giving you nothing in return, or way too little to be worth your time. The biggest waste of time, in all circumstances, is simply waiting. Waiting for people to arrive somewhere, waiting for something to happen, waiting for anything. You probably spend multiple accumulated hours of just waiting every day. The waiting time and "risk of waiting" is especially high when it comes to anything related to other people. Why? Simple, because if you do something yourself, you could always decide what to do, pretty much immediately. Whenever someone else is involved, you need to wait for them to respond, wait for them to make up their mind, wait for them to do x and y and probably also z.\r\nObviously, these waiting times are a low price to pay for what you get in return, usually. But here&apos;s where the real problem comes in: What if you don&apos;t know what you get in return? What if you are just waiting for something to happen, without being certain that it might even happen at all or if, when something does happen, it is of actual profit to you? In this case, the price of waiting is too high. You are effectively wasting your time doing nothing, with expectation of probably getting nothing. Why would anyone ever do such a crazy thing? Quite simply, it happens when we start depending on people too much, or depend on them for something they cannot always provide. This problem is alarmingly high when it comes to the internet.\r\nThe internet has an immense amount of stuff to offer. So many interesting and new things for you to learn, wonder and gander at. But it&apos;s always also a gamble. Do you want to take the risk of wasting your time looking for something, but not coming up with anything interesting? Do you want to take the risk of wasting your time waiting for something to happen on one of your thousand news ffeeds, facebook status bars, twitter timelines and whatever the hell the latest thing is? You could be using that time to do something productive, or do something that you know you could enjoy or that would benefit you, like f.e. studying, reading a good book, creating something, optimizing your daily routine or doing some work ahead of time.\r\n\r\nFor me, this gamble has been getting worse and worse lately I feel. I&apos;ve been wasting huge amounts of time waiting for people to come online and talk with me, waiting for something interesting to happen on forums, waiting for people to respond to my (almost) fruitless efforts at giving something back to humanity, etc. I have become a i{slacker}. It seems that this generation of humans, my generation, is full of slackers and it&apos;s i{normal} for people to do effectively nothing for large amounts of time. So who cares, right? Well, unfortunately, I care. I care a huge lot. At least, about how much I do, not so much about how others do. I have the odd habit of allowing others to slack (although not too much either), but never myself. I am a workaholic after all and I see it as only justified to give my best for the people I respect. Which means: Not wasting mine or your time with doing nothing of value.\r\nSo then. What now? Am I going to abandon every way of human contact and interaction? Am I going to devote all of my time to work? Hell no, I wouldn&apos;t be writing this blog entry if I did. I am, however, going to tone down my activity on Time Waster 3000 websites and my availability on chat related things. So, effectively, I won&apos;t be around as much. I&apos;ll still push out content as much as I can, but I probably won&apos;t be responding immediately or read all of the stuff that happens. The primary concerns of this being #Stevenchan, Twitter and EDF.\r\n\r\nAs a final note, I&apos;d like to mention that it depends on you for how much time you want to spend with social activities and how much you want to spend actually doing productive things. Finding the optimum for both is very tricky and takes a lot of time and effort. I hope you and I can get there eventually. For now, focus on improvement.', 'Activities, Chat, Internet, Time, Human, Interaction', '1336507738', 1, 1),
(56, 38, 'Why', 'This time I won&apos;t be asking the question though, I&apos;ll be answering it!\r\nyoutube{http://www.youtube.com/watch?v=kdhhQhqi_AE}', 'The question is simply: Why am I doing what I&apos;m doing?\r\nA very basic thing and yet something I could never really answer.dawdad\r\nI might not be able to answer it sufficiently yet either, but I think I at least found some kind of proposal for a solution.\r\n\r\nQuite simply, the reason why I&apos;m doing things seems to be one of self-worth. I believe that, simply by doing things, I&apos;ll gain some kind of value. How well I finish my tasks and how much reception they get from others indicates how much worth I am. Obviously, this requires me to do as many things as I can, as good as I can and as quickly as I can.\r\nThis establishment of self-worth does have some wide effects though. For example, it means that I automatically assume that people will judge me on it, even if I am the only one with an idea of this value. The result of this is that I assume people will think well/badly of me, even if they don&apos;t. Pulling this further also shows that I anticipate this reaction of people and am quickly disappointed by the lack of response towards what I&apos;m doing.\r\nThis expectation can only lead to a bad mood on the long run, mainly because I know that I work a huge lot, but I also don&apos;t get very much response for what I&apos;m doing. This is normal. And it&apos;s bad of me to think that I should somehow receive more attention than anyone else.\r\nStill, it seems to be a necessity dictated by the way I do things. I&apos;m not sure if there&apos;s a way for me to avoid this or not, but it would sure be nice if I didn&apos;t depend on getting attention from others as much.\r\nSame with this blog entry obviously. I expect people to respond to it, since I&apos;ve put effort into writing it, but most likely I won&apos;t get anything.\r\nThe danger in this is that with no response I think that nobody even read it, which isn&apos;t true either. 90%(Â±9%) of the people will read something but not respond to it, so with a tiny readership I really shouldn&apos;t be as snooty as to expect responses.\r\n\r\nSo I won&apos;t.\r\nI&apos;ll just put this out here for... whoever gives a shit about shit nobody cares about.\r\nMaybe I can bring out a blog post of actual value next week once the finals are over.\r\nHow about some more of that XHTML/CSS tutorial? Yeah that&apos;d work I suppose.\r\nAt least people read that.\r\nAnd there I go again.\r\n\r\nimg{http://img.tymoon.eu/img//Wallpapers/Shinmera/0FsCo.jpg}', 'Why,Am,I,Doing,Things,Answers', '1340489031', 1, 1),
(57, 36, 'About Work and Ideas', 'imgbox(right,:maxwidth 33){http://gallery.tymoon.eu/data/uploads/display/src/shinmera/misc/198-2012092103.png.png}\r\nIf you url(http://twitter.com/Shinmera){follow} even the least bit of what I do, you probably know that I&apos;m now working 100% as an intern at a url(http://www.planta-gmbh.ch/){software company}. The first days of my work were quite exciting, I got to continue the work that I had begun on an earlier instance, when I worked for them for a single week. That was about half a year ago. It was a good work to do and I actually did things, albeit I certainly wasn&apos;t at my optimal capacity. Now that I&apos;ve worked there for almost a month, things have changed a little. ', 'I went from being excited to feeling useless, because I felt like the things I did could be done much quicker and without having to steal other people&apos;s valuable time by someone else, so I didn&apos;t think I had much purpose by being there. I&apos;ve gotten over this now that I&apos;ve grown a bit more accustomed to things and know my way around a bit better (and therefore need to ask less), but there&apos;s still just so many things that I can&apos;t do on my own and all my work has to be reviewed by one of my co-workers again (and only one, since there&apos;s only one senior programmer in-house). So yes... it&apos;s getting better, slowly, but still. I&apos;m beginning to become useful. At least I hope so.\r\n\r\nThere is, however, one problem that has hit me hard the last few days. I got into a sort of productivity hole and just had immense difficulty to get myself to do anything. I just kept on url(http://www.joelonsoftware.com/articles/fog0000000339.html){reading articles} and sort of clicking my way around the internet, hoping I would find the will to get to work and make myself useful. This is doubly bad, because on one hand I already feel awful for not having any motivation, and on the other hand it&apos;s bad for the company and everyone working with me because I&apos;m hindering them by being slow. My time wasn&apos;t completely wasted though, just as the figures that stand around in everyone&apos;s room still serve the purpose of dust collectors, I collected information from articles in my head. Now, arguably the information I gathered is much url(http://tinyurl.com/cz62s6t){more valuable than the dust on a figma}, and I do have to say that the articles I read are very interesting and very good reads. In fact, url(http://www.joelonsoftware.com/articles/fog0000000069.html){you should read them as well}. But still, I&apos;d rather have done some things for the company. I think the only successful recipe to get out of this disaster is just pure willpower. The question remains however how you can build up that initial required willpower so you can finally smash your face on your keyboard and type out those mangled strings of information that keep the company alive somehow. The way that works for me is to try to completely severe all connections to the work that I have and just think of something entirely different. Or at least just of an unrelated context. This works because the brain is obviously still well capable of thought and processing, but it&apos;s just stuck on this one thing and can&apos;t continue, just the same as those pesky modal url(http://shinmera.tymoon.eu/public/nt/){dialog boxes} that keep popping up until you decide to just kill the application and do something else.\r\n\r\nThe way I eventually solved it (I only figured out the above solution by analysing what I did today), was by thinking of ways to improve TyNET and my workflow of programming in general, as inspired by the articles. This makes sense, because I was getting annoying thoughts about things to do during work the previous days and I found them to be very intrusive and wished they would go away. But no, hold on, they really shouldn&apos;t! Those little thoughts that jump in about something completely unrelated all of a sudden are worth more than the time you would&apos;ve saved by not thinking about them. They&apos;re probably even worth a hundred times more than that. These glimpses are so valuable, because they&apos;re usually completely new ideas or ways to think about a problem you had with something earlier on. If you dismiss them, you might never come up with them again because you already dismissed them as stupid. It&apos;s much better to quickly write it down, think about it for a minute or two and see how much you can wring out of it until you&apos;re done and then switch back to your regular work. It doesn&apos;t lose you much valuable time because you&apos;re done with it quickly and can remind yourself later, as you wrote it down. It also doesn&apos;t disrupt your mind any more than the thought already did. Trying to dismiss it forcefully might actually throw you out of concept even more than just letting it slide, since you&apos;ll be sitting there just a tiny bit more annoyed, instead of happier because you gained new insight.\r\n\r\nSo, that&apos;s what I started doing today and boy, it has already helped me a lot. I came up with a couple of important things that I need to change about TyNET and especially Reader (the blog module). The HUB module, which is my future project and bug tracker is also back in the planning phase and I need to get that done next. I&apos;ve gone on for way too long without a proper way of tracking bugs and reporting the status of my projects.\r\nTime to change!', '', '1348918490', 1, 1),
(58, 36, 'About Bastion', 'The world is a quite marvellous. Marvellous, because it has so many things to offer, both for the url(http://jinzhan.deviantart.com/art/Afternoon-Tea-Time-260324890){really disturbingly unpleasant things} that scare you to the point that not even the dust clouds undearneath your bed appear threatening; as well as the sort of url(http://www.sublimetext.com/){stuff you fall in love with immediately} and want to spend the rest of your life with. How these things balance each other doesn&apos;t really depend on what happens to us (despite what some people would like to believe), but rather on what you make out of it and how important you deem them. Regardless of whether you have a shitty day because you just stubbed your toe into the corner of a wall with the might of a wrestler or if your day is extremely good because you just url(http://www.youtube.com/watch?v=H3JwS4RcYyk){won the lottery}, I&apos;m not here to talk about attitudes today. I&apos;ll just lose a few words about something that I&apos;ve recently fallen in love with.\r\ncenter{img(:title Oh mother...){http://wallpoper.com/images/00/39/73/39/bastion_00397339.jpg}}', 'i{}\r\nfloat(right){youtube{http://www.youtube.com/watch?v=AhS5occh_Fk}}\r\nBastion is a game developed by url(http://supergiantgames.com/){Supergiant Games}, yet another indie team. I&apos;m having a hard time describing what makes this game as good as I think it is, because it all just fits so well together that I can&apos;t really find a place to start. Despite this, I suppose I&apos;ll just start with the most special feature of Bastion, which is the narrator. Throughout the game you listen to a narrator that gives you back-story and additional information about what&apos;s currently going on. The narrative adapts to the way you play and the things you do, which helps a lot to get immersed into the game. What Supergiant got absolutely spot-on with the narrator, is a way to provide lots of story into the game, without disturbing its flow at all. You never have to stop and it&apos;s never intrusive, so you can keep on playing and listen to the fantastic voice as you go.\r\nurl(http://www.youtube.com/watch?v=AhS5occh_Fk){Just give it a try}.\r\n\r\nIsn&apos;t that just the kind of voice you&apos;d like to hear when camping out in the desert, sitting around a campfire and thinking about all the matters in the world, both good and bad; just pondering with your thoughts and letting them float through the starry night sky.\r\nOk, granted, that scenario is pretty damn unlikely for a city-face like me, but we&apos;ll imagine for now.\r\n\r\nimgbox(left,:maxwidth 50){http://cdn.pastemagazine.com/www/articles/2011/07/27/Bastion_092010_00031.jpeg}\r\nI find Bastion to be another game like Okami. It gets the artwork, gameplay and soundtrack just perfectly matched up. As such, this both means that the game would only be half as good if any of the things were missing, but it also means that they all are fantastic. The soundtrack has some really spectacular tunes, especially the ending theme, the artwork is lovely and often times just stunning to look at and the game plays really well, keeps you interested and deeply invested in the story, characters and developments. \r\n\r\nAt its core, the game is an action RPG, the RPG elements mainly consisting of the weapons you choose to fight and eventually level up. Another element are spirits, which give you passive bonuses during the game. There&apos;s also a variety of items scattered throughout the world that give you more insight about the backstory, once you talk to the Narrator about them. The story itself plays after an event referred to as the "Calamity", which tore the world to pieces and killed almost everyone in the process. As a survivor, you travel around the individual pieces of the world that have been shot up into the sky and try to find the cores that keep these parts from falling apart. With the cores, you can improve the strength of your new home, the Bastion. I won&apos;t say any more about it though, as it would just ruin so much of it, if I tried to retell it in any other way than the original way it was intended to be heard.\r\n\r\nfloat(right){youtube{http://www.youtube.com/watch?v=GDflVhOpS4E}}\r\nBastion does have its own set of problems of course. Just the same as the food that lands on your plate in a five star super luxury gourmét restaurant is extremely good and close to perfect, there&apos;s always some wanker out there who&apos;ll find a way to complain about i{something}. So, obviously, there are some complaints I have about this game, despite it being such a delicious dish. My first complaint is about the linux version of it. This might just be my machine, my setup or whatever the hell I&apos;m doing wrong, but it sometimes just freezes completely, and since it captures the mouse and keyboard, I can&apos;t do anything with my machine anymore, except press the power button. The freezes appear to be completely random and I have no clue what&apos;s causing them, but yeah. It was quite frustrating because it ripped me out of such a grand experience every time. Don&apos;t worry though, all the other complaints I have actually concern the game, so I&apos;ll stop my excessive geekery, at least for now. My other complaint is that the collision detection for the objects and ground is only sub-optimal. Often times there&apos;s places where I didn&apos;t think I could stand on, but could and other places that showed the reverse phenomenon. The hitboxes of the enemies are sometimes a bit weird as well, so I&apos;d wager the collision detection system could&apos;ve used some more work. Aside from that, I have nothing to complain about this game at all. Yep, that&apos;s it already!\r\n\r\nNow, the url(http://store.supergiantgames.com/){Supergiant Store} has some sweet Bastion merchandise to offer, of which I bought the collection pack. Here&apos;s some pictures that look worse than if they were shot by a camera from 15 years ago, but they&apos;ll have to do. It&apos;s late and my room is about as well lit as a cave in minecraft, so I apologize for the quality.\r\nimg{http://www.tymoon.eu/data/uploads/filer/38-IMG_20120926_185515.jpg}\r\nimg{http://www.tymoon.eu/data/uploads/filer/39-IMG_20120926_185532.jpg}\r\nimg{http://www.tymoon.eu/data/uploads/filer/40-IMG_20120926_185612.jpg}\r\nimg{http://www.tymoon.eu/data/uploads/filer/41-IMG_20120926_185705.jpg}', '', '1348918744', 1, 1),
(59, 38, 'Test entry', 'TEST', 'TEST', ',WWW', '1350132013', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bl_folders`
--

CREATE TABLE IF NOT EXISTS `bl_folders` (
  `folderID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`folderID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=56 ;

--
-- Dumping data for table `bl_folders`
--

INSERT IGNORE INTO `bl_folders` (`folderID`, `title`, `text`) VALUES
(34, 'Transcend', 'This category is for progress updates for the game project Transcend.\r\nIt discusses the game design process on a more distant level, so no coding knowledge is required.'),
(35, 'Site Updates', 'Reports about TyNET''s status.'),
(36, 'Casual', 'Casual updates about things going about in real life.'),
(38, 'Rants', 'Sometimes I like to rant about things that get me steamed up for some reason.\r\nI hope I can keep this either to a minimum or at least interesting.'),
(39, 'Tech', 'Technology, oh technology... what hath thou donest with us?'),
(40, 'Misc', 'Everything that doesn''t quite fit anywhere else.'),
(55, 'TymoonNexT', 'Global notices about TymoonNexT and associated projects.');

-- --------------------------------------------------------

--
-- Table structure for table `ch_bans`
--

CREATE TABLE IF NOT EXISTS `ch_bans` (
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `period` int(10) unsigned NOT NULL,
  `reason` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `appeal` text COLLATE utf8_unicode_ci NOT NULL,
  `mute` tinyint(1) NOT NULL DEFAULT '0',
  `PID` int(10) unsigned NOT NULL,
  `folder` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_boards`
--

CREATE TABLE IF NOT EXISTS `ch_boards` (
  `boardID` bigint(20) NOT NULL AUTO_INCREMENT,
  `folder` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `subject` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `maxfilesize` int(11) NOT NULL,
  `maxpages` int(11) NOT NULL,
  `postlimit` int(11) NOT NULL,
  `filetypes` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `defaulttheme` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `options` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`boardID`),
  UNIQUE KEY `folder` (`folder`),
  UNIQUE KEY `title` (`title`),
  KEY `options` (`options`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ch_boards`
--

INSERT IGNORE INTO `ch_boards` (`boardID`, `folder`, `title`, `subject`, `maxfilesize`, `maxpages`, `postlimit`, `filetypes`, `defaulttheme`, `options`) VALUES
(1, 'iou', 'I Love You', '', 15000, 10, 150, 'image/png;image/gif;image/jpeg', '', 't'),
(2, 'second', 'second', '', 15000, 10, 150, 'image/png;image/jpeg;image/gif', '', 't');

-- --------------------------------------------------------

--
-- Table structure for table `ch_categories`
--

CREATE TABLE IF NOT EXISTS `ch_categories` (
  `title` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `order` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ch_categories`
--

INSERT IGNORE INTO `ch_categories` (`title`, `order`) VALUES
('Test', '1,2');

-- --------------------------------------------------------

--
-- Table structure for table `ch_filetypes`
--

CREATE TABLE IF NOT EXISTS `ch_filetypes` (
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `mime` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `preview` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`title`),
  UNIQUE KEY `mime` (`mime`),
  UNIQUE KEY `mime_2` (`mime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_frontpage`
--

CREATE TABLE IF NOT EXISTS `ch_frontpage` (
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `classes` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ch_frontpage`
--

INSERT IGNORE INTO `ch_frontpage` (`title`, `text`, `classes`) VALUES
('Breaking News', 'img{http://img.tymoon.eu/img//Wallpapers/Shinmera/0FsCo.jpg}', 'fullwidth'),
('Welcome', 'Welcome to Stevenchan!', '');

-- --------------------------------------------------------

--
-- Table structure for table `ch_hits`
--

CREATE TABLE IF NOT EXISTS `ch_hits` (
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `BID` int(11) NOT NULL,
  `PID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ch_hits`
--

INSERT IGNORE INTO `ch_hits` (`ip`, `time`, `BID`, `PID`) VALUES
('127.0.0.1', 1341390560, 1, 0),
('127.0.0.1', 1341390561, 1, 0),
('127.0.0.1', 1341391189, 2, 0),
('127.0.0.1', 1341391191, 1, 0),
('127.0.0.1', 1341391588, 1, 0),
('127.0.0.1', 1341392076, 1, 0),
('127.0.0.1', 1341392472, 1, 0),
('127.0.0.1', 1341392481, 1, 1),
('127.0.0.1', 1341392582, 2, 0),
('127.0.0.1', 1341392613, 2, 0),
('127.0.0.1', 1341392613, 2, 0),
('127.0.0.1', 1341392614, 2, 0),
('127.0.0.1', 1341392614, 2, 0),
('127.0.0.1', 1341392615, 2, 0),
('127.0.0.1', 1341392615, 2, 0),
('127.0.0.1', 1341392616, 2, 0),
('127.0.0.1', 1341392616, 2, 0),
('127.0.0.1', 1341392617, 2, 0),
('127.0.0.1', 1341392617, 2, 0),
('127.0.0.1', 1341392617, 2, 0),
('127.0.0.1', 1341392618, 2, 0),
('127.0.0.1', 1341392618, 2, 0),
('127.0.0.1', 1341392618, 2, 0),
('127.0.0.1', 1341392619, 2, 0),
('127.0.0.1', 1341392619, 2, 0),
('127.0.0.1', 1341392619, 2, 0),
('127.0.0.1', 1341394948, 1, 0),
('127.0.0.1', 1341394958, 1, 0),
('127.0.0.1', 1341396050, 1, 0),
('127.0.0.1', 1341396056, 1, 0),
('127.0.0.1', 1341396063, 1, 0),
('127.0.0.1', 1341396121, 1, 0),
('127.0.0.1', 1341396129, 1, 0),
('127.0.0.1', 1341396144, 1, 0),
('127.0.0.1', 1341396437, 2, 0),
('127.0.0.1', 1341399946, 2, 0),
('127.0.0.1', 1341399971, 2, 0),
('127.0.0.1', 1341400913, 1, 1),
('127.0.0.1', 1341401687, 1, 0),
('127.0.0.1', 1341401691, 2, 0),
('127.0.0.1', 1341406892, 0, 0),
('127.0.0.1', 1341407115, 0, 0),
('127.0.0.1', 1341407140, 0, 0),
('127.0.0.1', 1341407179, 0, 0),
('127.0.0.1', 1341407221, 0, 0),
('127.0.0.1', 1341407234, 0, 0),
('127.0.0.1', 1341407261, 0, 0),
('127.0.0.1', 1341407296, 0, 0),
('127.0.0.1', 1341407335, 0, 0),
('127.0.0.1', 1341407343, 0, 0),
('127.0.0.1', 1341407352, 0, 0),
('127.0.0.1', 1341407353, 0, 0),
('127.0.0.1', 1341407358, 0, 0),
('127.0.0.1', 1341407359, 0, 0),
('127.0.0.1', 1341407369, 0, 0),
('127.0.0.1', 1341407370, 0, 0),
('127.0.0.1', 1341407376, 0, 0),
('127.0.0.1', 1341407387, 0, 0),
('127.0.0.1', 1341407405, 0, 0),
('127.0.0.1', 1341407416, 0, 0),
('127.0.0.1', 1341407424, 0, 0),
('127.0.0.1', 1341407440, 0, 0),
('127.0.0.1', 1341407447, 0, 0),
('127.0.0.1', 1341407456, 0, 0),
('127.0.0.1', 1341407468, 0, 0),
('127.0.0.1', 1341407474, 0, 0),
('127.0.0.1', 1341408284, 0, 0),
('127.0.0.1', 1341408294, 0, 0),
('127.0.0.1', 1341408333, 0, 0),
('127.0.0.1', 1341408346, 0, 0),
('127.0.0.1', 1341408358, 0, 0),
('127.0.0.1', 1341408368, 0, 0),
('127.0.0.1', 1341408374, 0, 0),
('127.0.0.1', 1341408388, 0, 0),
('127.0.0.1', 1341408426, 0, 0),
('127.0.0.1', 1341408479, 0, 0),
('127.0.0.1', 1341408490, 0, 0),
('127.0.0.1', 1341408497, 0, 0),
('127.0.0.1', 1341408521, 1, 0),
('127.0.0.1', 1341408557, 1, 0),
('127.0.0.1', 1341408560, 1, 0),
('127.0.0.1', 1341408635, 1, 0),
('127.0.0.1', 1341408681, 1, 0),
('127.0.0.1', 1341408737, 1, 0),
('127.0.0.1', 1341408797, 1, 0),
('127.0.0.1', 1341409023, 1, 0),
('127.0.0.1', 1341409047, 0, 0),
('127.0.0.1', 1341409078, 1, 0),
('127.0.0.1', 1341409154, 1, 0),
('127.0.0.1', 1341409161, 1, 0),
('127.0.0.1', 1341409185, 1, 0),
('127.0.0.1', 1341409208, 1, 0),
('127.0.0.1', 1341409258, 1, 0),
('127.0.0.1', 1341409290, 1, 0),
('127.0.0.1', 1341409411, 1, 0),
('127.0.0.1', 1341409444, 1, 0),
('127.0.0.1', 1341409468, 1, 0),
('127.0.0.1', 1341409487, 1, 0),
('127.0.0.1', 1341409493, 1, 0),
('127.0.0.1', 1341409504, 1, 0),
('127.0.0.1', 1341409584, 1, 0),
('127.0.0.1', 1341409595, 1, 0),
('127.0.0.1', 1341409602, 1, 0),
('127.0.0.1', 1341409612, 1, 0),
('127.0.0.1', 1341409627, 1, 0),
('127.0.0.1', 1341409638, 1, 0),
('127.0.0.1', 1341409646, 1, 0),
('127.0.0.1', 1341409662, 1, 0),
('127.0.0.1', 1341409666, 1, 0),
('127.0.0.1', 1341409743, 1, 0),
('127.0.0.1', 1341409775, 1, 0),
('127.0.0.1', 1341409781, 0, 0),
('127.0.0.1', 1341409807, 0, 0),
('127.0.0.1', 1341409818, 0, 0),
('127.0.0.1', 1341409852, 0, 0),
('127.0.0.1', 1341409873, 0, 0),
('127.0.0.1', 1341409911, 0, 0),
('127.0.0.1', 1341409923, 0, 0),
('127.0.0.1', 1341409936, 0, 0),
('127.0.0.1', 1341409978, 0, 0),
('127.0.0.1', 1341409993, 0, 0),
('127.0.0.1', 1341410003, 0, 0),
('127.0.0.1', 1341410013, 0, 0),
('127.0.0.1', 1341410045, 0, 0),
('127.0.0.1', 1341410068, 1, 1),
('127.0.0.1', 1341410081, 1, 1),
('127.0.0.1', 1341410085, 1, 0),
('127.0.0.1', 1341410094, 1, 0),
('127.0.0.1', 1341414620, 0, 0),
('127.0.0.1', 1341414624, 1, 1),
('127.0.0.1', 1341414637, 1, 1),
('127.0.0.1', 1341414648, 1, 1),
('127.0.0.1', 1341414732, 1, 1),
('127.0.0.1', 1341414734, 1, 1),
('127.0.0.1', 1341414853, 1, 1),
('127.0.0.1', 1341414861, 1, 1),
('127.0.0.1', 1341414910, 1, 1),
('127.0.0.1', 1341414932, 1, 1),
('127.0.0.1', 1341415004, 1, 1),
('127.0.0.1', 1341415075, 1, 1),
('127.0.0.1', 1341415080, 1, 1),
('127.0.0.1', 1341415100, 1, 1),
('127.0.0.1', 1341415157, 1, 1),
('127.0.0.1', 1341415182, 1, 1),
('127.0.0.1', 1341415213, 1, 1),
('127.0.0.1', 1341415281, 1, 1),
('127.0.0.1', 1341415343, 1, 1),
('127.0.0.1', 1341415351, 1, 1),
('127.0.0.1', 1341415367, 1, 1),
('127.0.0.1', 1341415388, 1, 1),
('127.0.0.1', 1341415398, 1, 1),
('127.0.0.1', 1341415431, 1, 1),
('127.0.0.1', 1341415436, 1, 1),
('127.0.0.1', 1341415439, 1, 1),
('127.0.0.1', 1341415441, 1, 1),
('127.0.0.1', 1341415456, 1, 1),
('127.0.0.1', 1341415477, 1, 1),
('127.0.0.1', 1341415480, 1, 1),
('127.0.0.1', 1341415521, 1, 1),
('127.0.0.1', 1341415542, 1, 1),
('127.0.0.1', 1341415598, 1, 1),
('127.0.0.1', 1341415613, 1, 1),
('127.0.0.1', 1341415656, 1, 1),
('127.0.0.1', 1341415665, 1, 1),
('127.0.0.1', 1341415857, 1, 1),
('127.0.0.1', 1341415866, 1, 1),
('127.0.0.1', 1341415912, 1, 1),
('127.0.0.1', 1341416966, 1, 0),
('127.0.0.1', 1341416973, 1, 0),
('127.0.0.1', 1341417003, 1, 0),
('127.0.0.1', 1341417004, 1, 0),
('127.0.0.1', 1341417005, 1, 0),
('127.0.0.1', 1341417025, 1, 0),
('127.0.0.1', 1341417029, 1, 0),
('127.0.0.1', 1341427048, 1, 0),
('127.0.0.1', 1341437539, 1, 0),
('127.0.0.1', 1341487515, 0, 0),
('127.0.0.1', 1341487521, 1, 0),
('127.0.0.1', 1341487537, 2, 0),
('127.0.0.1', 1341522097, 0, 0),
('127.0.0.1', 1341522108, 2, 0),
('127.0.0.1', 1341522110, 1, 0),
('127.0.0.1', 1341522126, 1, 0),
('127.0.0.1', 1341522180, 1, 0),
('127.0.0.1', 1341522184, 1, 0),
('127.0.0.1', 1341522204, 1, 0),
('127.0.0.1', 1341522222, 1, 0),
('127.0.0.1', 1341522236, 1, 0),
('127.0.0.1', 1341522240, 1, 0),
('127.0.0.1', 1341522266, 1, 0),
('127.0.0.1', 1341522280, 1, 0),
('127.0.0.1', 1341522298, 1, 0),
('127.0.0.1', 1341522348, 1, 0),
('127.0.0.1', 1341522512, 1, 0),
('127.0.0.1', 1341522515, 1, 0),
('127.0.0.1', 1341522523, 1, 0),
('127.0.0.1', 1341522549, 1, 0),
('127.0.0.1', 1341522623, 1, 0),
('127.0.0.1', 1341522669, 1, 0),
('127.0.0.1', 1341522703, 1, 0),
('127.0.0.1', 1341522897, 1, 0),
('127.0.0.1', 1341523103, 1, 0),
('127.0.0.1', 1341536254, 1, 0),
('127.0.0.1', 1341587621, 1, 0),
('127.0.0.1', 1341610442, 0, 0),
('127.0.0.1', 1341661780, 0, 0),
('127.0.0.1', 1341661782, 1, 0),
('127.0.0.1', 1341661784, 1, 1),
('127.0.0.1', 1341661790, 1, 1),
('127.0.0.1', 1341661796, 1, 1),
('127.0.0.1', 1341661805, 1, 1),
('127.0.0.1', 1341661814, 1, 1),
('127.0.0.1', 1341661872, 1, 1),
('127.0.0.1', 1341662008, 1, 1),
('127.0.0.1', 1341662887, 1, 1),
('127.0.0.1', 1341662900, 1, 1),
('127.0.0.1', 1341662951, 1, 1),
('127.0.0.1', 1341662967, 1, 1),
('127.0.0.1', 1341663017, 1, 1),
('127.0.0.1', 1341663093, 1, 1),
('127.0.0.1', 1341663142, 1, 1),
('127.0.0.1', 1341663204, 1, 1),
('127.0.0.1', 1341663213, 1, 1),
('127.0.0.1', 1341663246, 1, 1),
('127.0.0.1', 1341663304, 1, 1),
('127.0.0.1', 1341663356, 1, 1),
('127.0.0.1', 1341663372, 1, 1),
('127.0.0.1', 1341663468, 1, 1),
('127.0.0.1', 1341663491, 1, 1),
('127.0.0.1', 1341663515, 1, 1),
('127.0.0.1', 1341663519, 1, 1),
('127.0.0.1', 1341663548, 1, 1),
('127.0.0.1', 1341663559, 1, 1),
('127.0.0.1', 1341663565, 1, 1),
('127.0.0.1', 1341663580, 1, 1),
('127.0.0.1', 1341663683, 1, 1),
('127.0.0.1', 1341663719, 1, 1),
('127.0.0.1', 1341663765, 1, 1),
('127.0.0.1', 1341663799, 1, 1),
('127.0.0.1', 1341663812, 1, 0),
('127.0.0.1', 1341663840, 1, 1),
('127.0.0.1', 1341663845, 1, 1),
('127.0.0.1', 1341663865, 1, 1),
('127.0.0.1', 1341663872, 1, 1),
('127.0.0.1', 1341663878, 1, 0),
('127.0.0.1', 1341663879, 1, 1),
('127.0.0.1', 1341663883, 1, 1),
('127.0.0.1', 1341663899, 1, 1),
('127.0.0.1', 1341663903, 1, 1),
('127.0.0.1', 1341663916, 1, 1),
('127.0.0.1', 1341663925, 1, 1),
('127.0.0.1', 1341663932, 1, 0),
('127.0.0.1', 1341663934, 1, 1),
('127.0.0.1', 1341663942, 1, 1),
('127.0.0.1', 1341663965, 1, 1),
('127.0.0.1', 1341663970, 1, 1),
('127.0.0.1', 1341663978, 1, 1),
('127.0.0.1', 1341663990, 1, 1),
('127.0.0.1', 1341663997, 1, 1),
('127.0.0.1', 1341664001, 1, 0),
('127.0.0.1', 1341664002, 1, 1),
('127.0.0.1', 1341664009, 1, 1),
('127.0.0.1', 1341664015, 1, 0),
('127.0.0.1', 1341664034, 1, 0),
('127.0.0.1', 1341664052, 1, 0),
('127.0.0.1', 1341664067, 1, 0),
('127.0.0.1', 1341664077, 1, 1),
('127.0.0.1', 1341664079, 1, 0),
('127.0.0.1', 1341664083, 1, 1),
('127.0.0.1', 1341664086, 1, 1),
('127.0.0.1', 1341664098, 1, 1),
('127.0.0.1', 1341664112, 1, 1),
('127.0.0.1', 1341664125, 1, 0),
('127.0.0.1', 1341664128, 1, 1),
('127.0.0.1', 1341664130, 1, 0),
('127.0.0.1', 1341664217, 1, 0),
('127.0.0.1', 1341664248, 1, 0),
('127.0.0.1', 1341664262, 1, 0),
('127.0.0.1', 1341664279, 1, 0),
('127.0.0.1', 1341664343, 1, 0),
('127.0.0.1', 1341664359, 1, 0),
('127.0.0.1', 1341664362, 1, 0),
('127.0.0.1', 1341664376, 1, 0),
('127.0.0.1', 1341664400, 1, 1),
('127.0.0.1', 1341664404, 1, 1),
('127.0.0.1', 1341664410, 1, 1),
('127.0.0.1', 1341664430, 1, 1),
('127.0.0.1', 1341664483, 1, 1),
('127.0.0.1', 1341664488, 1, 1),
('127.0.0.1', 1341664493, 1, 1),
('127.0.0.1', 1341664520, 1, 1),
('127.0.0.1', 1341664524, 1, 1),
('127.0.0.1', 1341664548, 1, 1),
('127.0.0.1', 1341664551, 1, 1),
('127.0.0.1', 1341664555, 1, 1),
('127.0.0.1', 1341664557, 1, 1),
('127.0.0.1', 1341664559, 1, 1),
('127.0.0.1', 1341664562, 1, 1),
('127.0.0.1', 1341664615, 1, 1),
('127.0.0.1', 1341664621, 1, 1),
('127.0.0.1', 1341664624, 1, 1),
('127.0.0.1', 1341664638, 1, 1),
('127.0.0.1', 1341664974, 1, 1),
('127.0.0.1', 1341665001, 1, 1),
('127.0.0.1', 1341665011, 1, 1),
('127.0.0.1', 1341665048, 1, 1),
('127.0.0.1', 1341665072, 1, 1),
('127.0.0.1', 1341665168, 1, 1),
('127.0.0.1', 1341665245, 1, 1),
('127.0.0.1', 1341665264, 1, 1),
('127.0.0.1', 1341665301, 1, 1),
('127.0.0.1', 1341665326, 1, 1),
('127.0.0.1', 1341665342, 1, 1),
('127.0.0.1', 1341665349, 1, 1),
('127.0.0.1', 1341665389, 1, 1),
('127.0.0.1', 1341665411, 1, 1),
('127.0.0.1', 1341665429, 1, 1),
('127.0.0.1', 1341665561, 1, 1),
('127.0.0.1', 1341665665, 1, 1),
('127.0.0.1', 1341665690, 1, 1),
('127.0.0.1', 1341665736, 1, 1),
('127.0.0.1', 1341665747, 1, 1),
('127.0.0.1', 1341665830, 1, 1),
('127.0.0.1', 1341665839, 1, 1),
('127.0.0.1', 1341665869, 1, 1),
('127.0.0.1', 1341665884, 1, 1),
('127.0.0.1', 1341665901, 1, 1),
('127.0.0.1', 1341665941, 1, 1),
('127.0.0.1', 1341665963, 1, 1),
('127.0.0.1', 1341665991, 1, 1),
('127.0.0.1', 1341666019, 1, 1),
('127.0.0.1', 1341666149, 1, 1),
('127.0.0.1', 1341666212, 1, 1),
('127.0.0.1', 1341666230, 1, 1),
('127.0.0.1', 1341666344, 1, 1),
('127.0.0.1', 1341666588, 1, 1),
('127.0.0.1', 1341666617, 1, 1),
('127.0.0.1', 1341666663, 1, 1),
('127.0.0.1', 1341666672, 1, 1),
('127.0.0.1', 1341666678, 1, 1),
('127.0.0.1', 1341666680, 1, 1),
('127.0.0.1', 1341666683, 1, 1),
('127.0.0.1', 1341666686, 1, 1),
('127.0.0.1', 1341666688, 1, 1),
('127.0.0.1', 1341666693, 1, 1),
('127.0.0.1', 1341666698, 1, 1),
('127.0.0.1', 1341666703, 1, 1),
('127.0.0.1', 1341666708, 1, 1),
('127.0.0.1', 1341666714, 1, 1),
('127.0.0.1', 1341666719, 1, 1),
('127.0.0.1', 1341666721, 1, 1),
('127.0.0.1', 1341666739, 1, 1),
('127.0.0.1', 1341666767, 1, 1),
('127.0.0.1', 1341666779, 1, 1),
('127.0.0.1', 1341666795, 1, 1),
('127.0.0.1', 1341666806, 1, 1),
('127.0.0.1', 1341666856, 1, 1),
('127.0.0.1', 1341666920, 1, 1),
('127.0.0.1', 1341666925, 1, 1),
('127.0.0.1', 1341666975, 1, 1),
('127.0.0.1', 1341666978, 1, 1),
('127.0.0.1', 1341666986, 1, 1),
('127.0.0.1', 1341666997, 1, 1),
('127.0.0.1', 1341667024, 1, 1),
('127.0.0.1', 1341667042, 1, 1),
('127.0.0.1', 1341667071, 1, 1),
('127.0.0.1', 1341667105, 1, 1),
('127.0.0.1', 1341667120, 1, 1),
('127.0.0.1', 1341667125, 1, 1),
('127.0.0.1', 1341667129, 1, 1),
('127.0.0.1', 1341667136, 1, 1),
('127.0.0.1', 1341667152, 1, 1),
('127.0.0.1', 1341667165, 1, 1),
('127.0.0.1', 1341667170, 1, 1),
('127.0.0.1', 1341667184, 1, 1),
('127.0.0.1', 1341667221, 1, 1),
('127.0.0.1', 1341667241, 1, 1),
('127.0.0.1', 1341667245, 1, 1),
('127.0.0.1', 1341667253, 1, 1),
('127.0.0.1', 1341667272, 1, 1),
('127.0.0.1', 1341667290, 1, 1),
('127.0.0.1', 1341667338, 1, 1),
('127.0.0.1', 1341667354, 1, 1),
('127.0.0.1', 1341667380, 1, 1),
('127.0.0.1', 1341667419, 1, 1),
('127.0.0.1', 1341667480, 1, 1),
('127.0.0.1', 1341667501, 1, 1),
('127.0.0.1', 1341667528, 1, 1),
('127.0.0.1', 1341667533, 1, 1),
('127.0.0.1', 1341667544, 1, 1),
('127.0.0.1', 1341667545, 1, 1),
('127.0.0.1', 1341667548, 1, 0),
('127.0.0.1', 1341667549, 1, 1),
('127.0.0.1', 1341667566, 1, 0),
('127.0.0.1', 1341667567, 1, 0),
('127.0.0.1', 1341667569, 1, 0),
('127.0.0.1', 1341667575, 1, 0),
('127.0.0.1', 1341667587, 1, 0),
('127.0.0.1', 1341667591, 1, 1),
('127.0.0.1', 1341667595, 1, 1),
('127.0.0.1', 1341667730, 1, 1),
('127.0.0.1', 1341667759, 1, 1),
('127.0.0.1', 1341667767, 1, 0),
('127.0.0.1', 1341667774, 1, 0),
('127.0.0.1', 1341667783, 1, 0),
('127.0.0.1', 1341667792, 1, 0),
('127.0.0.1', 1341667816, 1, 0),
('127.0.0.1', 1341667819, 1, 1),
('127.0.0.1', 1341667824, 1, 1),
('127.0.0.1', 1341667886, 1, 1),
('127.0.0.1', 1341667891, 1, 1),
('127.0.0.1', 1341667904, 1, 1),
('127.0.0.1', 1341667910, 1, 0),
('127.0.0.1', 1341667915, 1, 0),
('127.0.0.1', 1341667930, 1, 1),
('127.0.0.1', 1341667932, 1, 0),
('127.0.0.1', 1341667968, 1, 0),
('127.0.0.1', 1341678321, 1, 1),
('127.0.0.1', 1341678479, 1, 1),
('127.0.0.1', 1341678480, 1, 1),
('127.0.0.1', 1341678516, 1, 1),
('127.0.0.1', 1341678521, 1, 1),
('127.0.0.1', 1341678531, 1, 0),
('127.0.0.1', 1341678533, 1, 1),
('127.0.0.1', 1341678561, 1, 1),
('127.0.0.1', 1341678587, 1, 1),
('127.0.0.1', 1341678647, 1, 1),
('127.0.0.1', 1341678693, 1, 1),
('127.0.0.1', 1341678817, 1, 1),
('127.0.0.1', 1341678826, 1, 0),
('127.0.0.1', 1341688683, 0, 0),
('127.0.0.1', 1341688685, 1, 0),
('127.0.0.1', 1341688840, 1, 0),
('127.0.0.1', 1341688843, 1, 1),
('127.0.0.1', 1341688849, 1, 1),
('127.0.0.1', 1341921580, 0, 0),
('127.0.0.1', 1341921582, 1, 0),
('127.0.0.1', 1341921589, 2, 0),
('127.0.0.1', 1341921590, 1, 0),
('127.0.0.1', 1341921599, 1, 0),
('127.0.0.1', 1341921604, 1, 0),
('127.0.0.1', 1341921678, 1, 0),
('127.0.0.1', 1341921737, 1, 0),
('127.0.0.1', 1341921780, 1, 1),
('127.0.0.1', 1341921787, 1, 1),
('127.0.0.1', 1341921813, 1, 1),
('127.0.0.1', 1341921829, 1, 1),
('127.0.0.1', 1341921852, 1, 1),
('127.0.0.1', 1341921858, 1, 1),
('127.0.0.1', 1341921885, 1, 1),
('127.0.0.1', 1341921893, 1, 1),
('127.0.0.1', 1341921941, 1, 1),
('127.0.0.1', 1341921982, 1, 1),
('127.0.0.1', 1341922001, 1, 1),
('127.0.0.1', 1341922029, 1, 1),
('127.0.0.1', 1341924632, 1, 0),
('127.0.0.1', 1341924634, 1, 1),
('127.0.0.1', 1341924639, 1, 1),
('127.0.0.1', 1341924673, 1, 1),
('127.0.0.1', 1341924736, 0, 0),
('127.0.0.1', 1341924741, 1, 0),
('127.0.0.1', 1341925347, 1, 0),
('127.0.0.1', 1341925349, 1, 1),
('127.0.0.1', 1341925353, 1, 1),
('127.0.0.1', 1341925373, 1, 1),
('127.0.0.1', 1341925383, 1, 1),
('127.0.0.1', 1341925393, 1, 1),
('127.0.0.1', 1341925403, 0, 0),
('127.0.0.1', 1341925405, 1, 0),
('127.0.0.1', 1341925408, 1, 0),
('127.0.0.1', 1341925418, 1, 1),
('127.0.0.1', 1341925428, 1, 1),
('127.0.0.1', 1341925438, 1, 1),
('127.0.0.1', 1341925448, 1, 1),
('127.0.0.1', 1341925458, 1, 1),
('127.0.0.1', 1341925468, 1, 1),
('127.0.0.1', 1341925478, 1, 1),
('127.0.0.1', 1341925488, 1, 1),
('127.0.0.1', 1341925498, 1, 1),
('127.0.0.1', 1341925508, 1, 1),
('127.0.0.1', 1341925518, 1, 1),
('127.0.0.1', 1341925528, 1, 1),
('127.0.0.1', 1341925538, 1, 1),
('127.0.0.1', 1341925548, 1, 1),
('127.0.0.1', 1341925558, 1, 1),
('127.0.0.1', 1341925568, 1, 1),
('127.0.0.1', 1341925572, 1, 1),
('127.0.0.1', 1341925575, 1, 1),
('127.0.0.1', 1341925576, 1, 1),
('127.0.0.1', 1341925577, 1, 1),
('127.0.0.1', 1341925577, 1, 1),
('127.0.0.1', 1341925577, 1, 1),
('127.0.0.1', 1341925578, 1, 1),
('127.0.0.1', 1341925578, 1, 1),
('127.0.0.1', 1341925578, 1, 1),
('127.0.0.1', 1341925578, 1, 1),
('127.0.0.1', 1341925578, 1, 1),
('127.0.0.1', 1341925578, 1, 1),
('127.0.0.1', 1341925579, 1, 1),
('127.0.0.1', 1341925579, 1, 1),
('127.0.0.1', 1341925579, 1, 1),
('127.0.0.1', 1341925590, 1, 1),
('127.0.0.1', 1341925600, 1, 1),
('127.0.0.1', 1341925610, 1, 1),
('127.0.0.1', 1341925620, 1, 1),
('127.0.0.1', 1341925630, 1, 1),
('127.0.0.1', 1341925640, 1, 1),
('127.0.0.1', 1341925650, 1, 1),
('127.0.0.1', 1341925660, 1, 1),
('127.0.0.1', 1341925670, 1, 1),
('127.0.0.1', 1341925680, 1, 1),
('127.0.0.1', 1341925690, 1, 1),
('127.0.0.1', 1341925700, 1, 1),
('127.0.0.1', 1341925710, 1, 1),
('127.0.0.1', 1341925720, 1, 1),
('127.0.0.1', 1341925731, 1, 1),
('127.0.0.1', 1341925734, 1, 1),
('127.0.0.1', 1341925740, 0, 0),
('127.0.0.1', 1341925742, 1, 1),
('127.0.0.1', 1341925751, 1, 1),
('127.0.0.1', 1341925845, 1, 1),
('127.0.0.1', 1341925847, 1, 0),
('127.0.0.1', 1341925850, 1, 1),
('127.0.0.1', 1341925856, 1, 0),
('127.0.0.1', 1341926970, 1, 0),
('127.0.0.1', 1341926976, 1, 0),
('127.0.0.1', 1341927104, 1, 0),
('127.0.0.1', 1341927108, 1, 1),
('127.0.0.1', 1341927169, 1, 1),
('127.0.0.1', 1341927184, 1, 0),
('127.0.0.1', 1341927302, 1, 1),
('127.0.0.1', 1341927318, 1, 0),
('127.0.0.1', 1341943610, 1, 0),
('127.0.0.1', 1341993208, 1, 0),
('127.0.0.1', 1342035391, 0, 0),
('127.0.0.1', 1342092726, 0, 0),
('127.0.0.1', 1342092729, 1, 0),
('127.0.0.1', 1342092735, 2, 0),
('127.0.0.1', 1342092737, 1, 0),
('127.0.0.1', 1342092738, 1, 1),
('127.0.0.1', 1342092775, 1, 0),
('127.0.0.1', 1342093496, 1, 1),
('127.0.0.1', 1342106040, 0, 0),
('127.0.0.1', 1342106080, 0, 0),
('127.0.0.1', 1342106087, 1, 0),
('127.0.0.1', 1342106384, 1, 0),
('127.0.0.1', 1342106433, 1, 0),
('127.0.0.1', 1342106531, 1, 0),
('127.0.0.1', 1342106532, 0, 0),
('127.0.0.1', 1342106533, 1, 0),
('127.0.0.1', 1342106550, 1, 0),
('127.0.0.1', 1342106551, 1, 0),
('127.0.0.1', 1342106558, 1, 0),
('127.0.0.1', 1342106598, 1, 0),
('127.0.0.1', 1342106600, 1, 0),
('127.0.0.1', 1342106600, 1, 0),
('127.0.0.1', 1342106600, 1, 0),
('127.0.0.1', 1342106743, 1, 0),
('127.0.0.1', 1342106777, 1, 1),
('127.0.0.1', 1342106795, 1, 1),
('127.0.0.1', 1342106804, 1, 1),
('127.0.0.1', 1342106892, 1, 1),
('127.0.0.1', 1342106944, 1, 1),
('127.0.0.1', 1342106955, 1, 1),
('127.0.0.1', 1342107010, 1, 0),
('127.0.0.1', 1342107019, 1, 0),
('127.0.0.1', 1342107027, 1, 0),
('127.0.0.1', 1342107057, 1, 0),
('127.0.0.1', 1342107072, 1, 0),
('127.0.0.1', 1342107087, 1, 0),
('127.0.0.1', 1342107688, 1, 0),
('127.0.0.1', 1342107710, 1, 0),
('127.0.0.1', 1342107757, 1, 0),
('127.0.0.1', 1342107774, 1, 0),
('127.0.0.1', 1342107800, 1, 0),
('127.0.0.1', 1342107806, 1, 0),
('127.0.0.1', 1342107816, 1, 0),
('127.0.0.1', 1342107833, 1, 0),
('127.0.0.1', 1342107856, 1, 0),
('127.0.0.1', 1342107873, 1, 0),
('127.0.0.1', 1342107910, 1, 0),
('127.0.0.1', 1342107943, 1, 0),
('127.0.0.1', 1342107971, 1, 0),
('127.0.0.1', 1342123132, 0, 0),
('127.0.0.1', 1342123163, 0, 0),
('127.0.0.1', 1342123197, 0, 0),
('127.0.0.1', 1342123208, 0, 0),
('127.0.0.1', 1342123239, 0, 0),
('127.0.0.1', 1342123257, 0, 0),
('127.0.0.1', 1342123274, 0, 0),
('127.0.0.1', 1342123362, 0, 0),
('127.0.0.1', 1342123380, 0, 0),
('127.0.0.1', 1342123392, 0, 0),
('127.0.0.1', 1342123399, 0, 0),
('127.0.0.1', 1342123712, 2, 0),
('127.0.0.1', 1342123713, 1, 0),
('127.0.0.1', 1342123728, 1, 0),
('127.0.0.1', 1342123736, 1, 0),
('127.0.0.1', 1342123742, 1, 1),
('127.0.0.1', 1342123821, 1, 0),
('127.0.0.1', 1342123830, 1, 0),
('127.0.0.1', 1342131934, 1, 0),
('127.0.0.1', 1342131944, 1, 0),
('127.0.0.1', 1342131964, 1, 0),
('127.0.0.1', 1342132136, 1, 0),
('127.0.0.1', 1342132736, 0, 0),
('127.0.0.1', 1342133101, 0, 0),
('127.0.0.1', 1342133105, 0, 0),
('127.0.0.1', 1342133107, 0, 0),
('127.0.0.1', 1342133108, 0, 0),
('127.0.0.1', 1342133126, 0, 0),
('127.0.0.1', 1342133128, 0, 0),
('127.0.0.1', 1342133161, 1, 0),
('127.0.0.1', 1342133167, 1, 1),
('127.0.0.1', 1342133190, 1, 0),
('127.0.0.1', 1342133225, 1, 0),
('127.0.0.1', 1342133227, 1, 0),
('127.0.0.1', 1342133229, 1, 0),
('127.0.0.1', 1342133230, 1, 0),
('127.0.0.1', 1342133231, 1, 0),
('127.0.0.1', 1342133231, 1, 0),
('127.0.0.1', 1342133231, 1, 0),
('127.0.0.1', 1342133297, 1, 0),
('127.0.0.1', 1342133323, 0, 0),
('127.0.0.1', 1342133327, 1, 0),
('127.0.0.1', 1342133342, 1, 0),
('127.0.0.1', 1342133665, 1, 0),
('127.0.0.1', 1342133698, 0, 0),
('127.0.0.1', 1342133857, 2, 0),
('127.0.0.1', 1342133859, 1, 0),
('127.0.0.1', 1342133862, 1, 0),
('127.0.0.1', 1342133865, 1, 0),
('127.0.0.1', 1342133896, 1, 0),
('127.0.0.1', 1342133908, 1, 0),
('127.0.0.1', 1342133952, 1, 0),
('127.0.0.1', 1342133959, 1, 0),
('127.0.0.1', 1342133971, 1, 0),
('127.0.0.1', 1342133973, 1, 0),
('127.0.0.1', 1342133976, 1, 0),
('127.0.0.1', 1342133991, 1, 0),
('127.0.0.1', 1342134014, 1, 0),
('127.0.0.1', 1342134024, 1, 0),
('127.0.0.1', 1342134028, 1, 0),
('127.0.0.1', 1342134058, 0, 0),
('127.0.0.1', 1342134086, 0, 0),
('127.0.0.1', 1342134128, 0, 0),
('127.0.0.1', 1342134129, 0, 0),
('127.0.0.1', 1342134130, 0, 0),
('127.0.0.1', 1342134131, 0, 0),
('127.0.0.1', 1342134131, 0, 0),
('127.0.0.1', 1342134353, 0, 0),
('127.0.0.1', 1342134367, 0, 0),
('127.0.0.1', 1342134505, 0, 0),
('127.0.0.1', 1342134535, 0, 0),
('127.0.0.1', 1342134536, 0, 0),
('127.0.0.1', 1342134538, 0, 0),
('127.0.0.1', 1342134545, 0, 0),
('127.0.0.1', 1342134551, 0, 0),
('127.0.0.1', 1342134552, 0, 0),
('127.0.0.1', 1342134570, 0, 0),
('127.0.0.1', 1342134582, 0, 0),
('127.0.0.1', 1342134599, 0, 0),
('127.0.0.1', 1342134611, 0, 0),
('127.0.0.1', 1342134646, 0, 0),
('127.0.0.1', 1342134678, 0, 0),
('127.0.0.1', 1342134858, 2, 0),
('127.0.0.1', 1342134860, 1, 0),
('127.0.0.1', 1342134909, 1, 0),
('127.0.0.1', 1342135035, 1, 0),
('127.0.0.1', 1342135055, 1, 0),
('127.0.0.1', 1342135086, 1, 0),
('127.0.0.1', 1342135094, 1, 0),
('127.0.0.1', 1342135104, 1, 0),
('127.0.0.1', 1342135113, 0, 0),
('127.0.0.1', 1342135159, 0, 0),
('127.0.0.1', 1342135163, 1, 0),
('127.0.0.1', 1342135168, 1, 0),
('127.0.0.1', 1342135193, 1, 0),
('127.0.0.1', 1342135201, 1, 0),
('127.0.0.1', 1342135366, 1, 0),
('127.0.0.1', 1342135377, 1, 0),
('127.0.0.1', 1342135392, 1, 0),
('127.0.0.1', 1342135398, 1, 0),
('127.0.0.1', 1342135417, 1, 0),
('127.0.0.1', 1342135577, 0, 0),
('127.0.0.1', 1342135578, 1, 0),
('127.0.0.1', 1342135581, 1, 0),
('127.0.0.1', 1342135594, 0, 0),
('127.0.0.1', 1342136491, 0, 0),
('127.0.0.1', 1342136493, 1, 0),
('127.0.0.1', 1342136526, 1, 0),
('127.0.0.1', 1342136555, 1, 0),
('127.0.0.1', 1342136561, 1, 0),
('127.0.0.1', 1342136626, 1, 0),
('127.0.0.1', 1342136631, 1, 0),
('127.0.0.1', 1342136922, 1, 0),
('127.0.0.1', 1342136925, 1, 0),
('127.0.0.1', 1342136928, 1, 0),
('127.0.0.1', 1342136931, 1, 0),
('127.0.0.1', 1342137427, 1, 0),
('127.0.0.1', 1342137429, 1, 0),
('127.0.0.1', 1342137506, 1, 0),
('127.0.0.1', 1342137507, 1, 0),
('127.0.0.1', 1342137508, 1, 0),
('127.0.0.1', 1342137508, 1, 0),
('127.0.0.1', 1342137508, 1, 0),
('127.0.0.1', 1342137509, 1, 0),
('127.0.0.1', 1342137509, 1, 0),
('127.0.0.1', 1342138058, 1, 0),
('127.0.0.1', 1342138059, 1, 0),
('127.0.0.1', 1342138060, 1, 0),
('127.0.0.1', 1342138085, 1, 0),
('127.0.0.1', 1342138087, 1, 0),
('127.0.0.1', 1342138089, 1, 0),
('127.0.0.1', 1342138091, 1, 0),
('127.0.0.1', 1342138092, 1, 0),
('127.0.0.1', 1342138395, 1, 0),
('127.0.0.1', 1342173262, 0, 0),
('127.0.0.1', 1342173265, 2, 0),
('127.0.0.1', 1342341465, 0, 0),
('127.0.0.1', 1342341467, 2, 0),
('127.0.0.1', 1342341569, 2, 0),
('127.0.0.1', 1342341572, 2, 0),
('127.0.0.1', 1342341573, 2, 0),
('127.0.0.1', 1342341574, 2, 0),
('127.0.0.1', 1342341578, 2, 0),
('127.0.0.1', 1342341579, 2, 0),
('127.0.0.1', 1342341579, 2, 0),
('127.0.0.1', 1342341580, 2, 0),
('127.0.0.1', 1342341580, 2, 0),
('127.0.0.1', 1342341581, 2, 0),
('127.0.0.1', 1342341786, 2, 0),
('127.0.0.1', 1342341789, 2, 0),
('127.0.0.1', 1342341797, 2, 0),
('127.0.0.1', 1342341799, 2, 0),
('127.0.0.1', 1342341978, 2, 0),
('127.0.0.1', 1342341981, 1, 0),
('127.0.0.1', 1342341996, 1, 0),
('127.0.0.1', 1342425965, 0, 0),
('127.0.0.1', 1342425967, 2, 0),
('127.0.0.1', 1342425970, 1, 0),
('127.0.0.1', 1343057427, 0, 0),
('127.0.0.1', 1343057429, 2, 0),
('127.0.0.1', 1343057431, 1, 0),
('127.0.0.1', 1343057503, 0, 0),
('127.0.0.1', 1343057505, 1, 0),
('127.0.0.1', 1343057552, 1, 0),
('127.0.0.1', 1343057606, 1, 0),
('127.0.0.1', 1343057658, 1, 0),
('127.0.0.1', 1343057770, 1, 0),
('127.0.0.1', 1343057914, 1, 0),
('127.0.0.1', 1343057930, 1, 0),
('127.0.0.1', 1343057936, 1, 0),
('127.0.0.1', 1343057945, 1, 0),
('127.0.0.1', 1343057992, 1, 0),
('127.0.0.1', 1343057998, 1, 0),
('127.0.0.1', 1343057999, 1, 0),
('127.0.0.1', 1343058018, 1, 0),
('127.0.0.1', 1343058072, 1, 0),
('127.0.0.1', 1343058086, 1, 0),
('127.0.0.1', 1343058099, 1, 0),
('127.0.0.1', 1343058116, 1, 0),
('127.0.0.1', 1343058485, 1, 0),
('127.0.0.1', 1343058537, 1, 0),
('127.0.0.1', 1343058610, 1, 0),
('127.0.0.1', 1343058623, 1, 0),
('127.0.0.1', 1343058643, 1, 0),
('127.0.0.1', 1343058645, 1, 0),
('127.0.0.1', 1343058652, 1, 0),
('127.0.0.1', 1343058654, 1, 0),
('127.0.0.1', 1343058668, 1, 0),
('127.0.0.1', 1343058680, 1, 0),
('127.0.0.1', 1343058740, 1, 0),
('127.0.0.1', 1343058799, 1, 0),
('127.0.0.1', 1343058892, 1, 0),
('127.0.0.1', 1343058909, 1, 0),
('127.0.0.1', 1343058947, 1, 0),
('127.0.0.1', 1343058973, 1, 0),
('127.0.0.1', 1343058993, 1, 0),
('127.0.0.1', 1343059038, 1, 0),
('127.0.0.1', 1343059141, 1, 0),
('127.0.0.1', 1343059153, 1, 0),
('127.0.0.1', 1343059172, 1, 0),
('127.0.0.1', 1343059192, 1, 0),
('127.0.0.1', 1343059222, 1, 0),
('127.0.0.1', 1343059336, 1, 0),
('127.0.0.1', 1343059440, 1, 0),
('127.0.0.1', 1343059453, 1, 0),
('127.0.0.1', 1343059454, 1, 0),
('127.0.0.1', 1343059455, 1, 0),
('127.0.0.1', 1343059455, 1, 0),
('127.0.0.1', 1343059455, 1, 0),
('127.0.0.1', 1343059455, 1, 0),
('127.0.0.1', 1343059455, 1, 0),
('127.0.0.1', 1343059455, 1, 0),
('127.0.0.1', 1343059455, 1, 0),
('127.0.0.1', 1343059456, 1, 0),
('127.0.0.1', 1343059456, 1, 0),
('127.0.0.1', 1343059471, 1, 0),
('127.0.0.1', 1343059538, 1, 0),
('127.0.0.1', 1343059921, 0, 0),
('127.0.0.1', 1343059925, 2, 0),
('127.0.0.1', 1343059927, 1, 0),
('127.0.0.1', 1343074590, 1, 0),
('127.0.0.1', 1343108189, 1, 0),
('127.0.0.1', 1343114639, 1, 0),
('127.0.0.1', 1343114663, 1, 0),
('127.0.0.1', 1343114676, 1, 0),
('127.0.0.1', 1343114693, 1, 0),
('127.0.0.1', 1343114700, 1, 0),
('127.0.0.1', 1343136554, 1, 0),
('127.0.0.1', 1343859338, 0, 0),
('127.0.0.1', 1343859382, 0, 0),
('127.0.0.1', 1343859440, 0, 0),
('127.0.0.1', 1343859461, 0, 0),
('127.0.0.1', 1343859472, 0, 0),
('127.0.0.1', 1343859481, 1, 0),
('127.0.0.1', 1343859503, 1, 1),
('127.0.0.1', 1343859521, 1, 1),
('127.0.0.1', 1343859537, 1, 1),
('127.0.0.1', 1343859563, 1, 0),
('127.0.0.1', 1343859586, 1, 0),
('127.0.0.1', 1343859626, 1, 0),
('127.0.0.1', 1343859646, 1, 0),
('127.0.0.1', 1343859658, 1, 0),
('127.0.0.1', 1343859692, 1, 0),
('127.0.0.1', 1343859709, 1, 0),
('127.0.0.1', 1343859720, 1, 0),
('127.0.0.1', 1343859737, 1, 0),
('127.0.0.1', 1343859746, 1, 0),
('127.0.0.1', 1343859796, 1, 0),
('127.0.0.1', 1343859807, 1, 1),
('127.0.0.1', 1343859823, 1, 1),
('127.0.0.1', 1343859870, 1, 1),
('127.0.0.1', 1343859884, 1, 1),
('127.0.0.1', 1343859895, 1, 1),
('127.0.0.1', 1343859934, 1, 1),
('127.0.0.1', 1343859956, 1, 0),
('127.0.0.1', 1343859985, 1, 0),
('127.0.0.1', 1343860009, 1, 0),
('127.0.0.1', 1343860076, 1, 0),
('127.0.0.1', 1343860120, 1, 0),
('127.0.0.1', 1343860129, 1, 1),
('127.0.0.1', 1343860150, 1, 1),
('127.0.0.1', 1343860230, 1, 1),
('127.0.0.1', 1343860255, 1, 1),
('127.0.0.1', 1343860273, 1, 1),
('127.0.0.1', 1343860277, 1, 1),
('127.0.0.1', 1343860302, 1, 0),
('127.0.0.1', 1343860370, 1, 0),
('127.0.0.1', 1343860745, 0, 0),
('127.0.0.1', 1343860748, 1, 0),
('127.0.0.1', 1343860814, 1, 0),
('127.0.0.1', 1343860833, 1, 0),
('127.0.0.1', 1343860871, 1, 0),
('127.0.0.1', 1343860888, 1, 0),
('127.0.0.1', 1343860943, 1, 0),
('127.0.0.1', 1343860962, 1, 0),
('127.0.0.1', 1343860970, 1, 0),
('127.0.0.1', 1343860986, 1, 1),
('127.0.0.1', 1343861006, 1, 0),
('127.0.0.1', 1343861017, 1, 1),
('127.0.0.1', 1343861026, 1, 0),
('127.0.0.1', 1343861031, 1, 1),
('127.0.0.1', 1343861036, 1, 0),
('127.0.0.1', 1343861061, 1, 0),
('127.0.0.1', 1343861079, 1, 0),
('127.0.0.1', 1343861090, 1, 0),
('127.0.0.1', 1343861112, 1, 0),
('127.0.0.1', 1343861125, 1, 0),
('127.0.0.1', 1343861149, 1, 0),
('127.0.0.1', 1343861160, 1, 0),
('127.0.0.1', 1343861168, 1, 0),
('127.0.0.1', 1343861193, 1, 0),
('127.0.0.1', 1343861197, 1, 0),
('127.0.0.1', 1343861200, 1, 1),
('127.0.0.1', 1343861207, 1, 1),
('127.0.0.1', 1343861213, 1, 1),
('127.0.0.1', 1343861245, 1, 1),
('127.0.0.1', 1343861249, 1, 0),
('127.0.0.1', 1343861252, 1, 1),
('127.0.0.1', 1343861366, 1, 0),
('127.0.0.1', 1343861369, 1, 1),
('127.0.0.1', 1343861371, 1, 0),
('127.0.0.1', 1343861372, 1, 0),
('127.0.0.1', 1343861374, 1, 1),
('127.0.0.1', 1343861381, 1, 0),
('127.0.0.1', 1343861406, 1, 1),
('127.0.0.1', 1343861411, 1, 0),
('127.0.0.1', 1343861412, 1, 1),
('127.0.0.1', 1343861489, 1, 1),
('127.0.0.1', 1343861492, 1, 0),
('127.0.0.1', 1343861492, 1, 0),
('127.0.0.1', 1343861493, 1, 0),
('127.0.0.1', 1343861497, 1, 0),
('127.0.0.1', 1343861567, 1, 0),
('127.0.0.1', 1343861571, 1, 0),
('127.0.0.1', 1343861577, 1, 1),
('127.0.0.1', 1343861580, 1, 1),
('127.0.0.1', 1343861583, 1, 0),
('127.0.0.1', 1343861598, 1, 0),
('127.0.0.1', 1343861600, 1, 1),
('127.0.0.1', 1343861640, 1, 1),
('127.0.0.1', 1343861641, 1, 1),
('127.0.0.1', 1343861719, 1, 1),
('127.0.0.1', 1343861724, 1, 0),
('127.0.0.1', 1343861726, 1, 1),
('127.0.0.1', 1343861736, 1, 0),
('127.0.0.1', 1343861749, 1, 0),
('127.0.0.1', 1343861753, 1, 1),
('127.0.0.1', 1343861755, 1, 0),
('127.0.0.1', 1343861786, 1, 0),
('127.0.0.1', 1343861800, 1, 0),
('127.0.0.1', 1343861809, 1, 0),
('127.0.0.1', 1343861830, 1, 0),
('127.0.0.1', 1343861842, 1, 0),
('127.0.0.1', 1343861854, 1, 0),
('127.0.0.1', 1343861866, 1, 0),
('127.0.0.1', 1343861877, 1, 0),
('127.0.0.1', 1343861898, 1, 0),
('127.0.0.1', 1343861917, 1, 0),
('127.0.0.1', 1343861993, 1, 0),
('127.0.0.1', 1343862052, 1, 0),
('127.0.0.1', 1343862170, 1, 0),
('127.0.0.1', 1343862178, 1, 1),
('127.0.0.1', 1343862184, 1, 0),
('127.0.0.1', 1343862305, 1, 0),
('127.0.0.1', 1343890752, 1, 0),
('127.0.0.1', 1344070455, 0, 0),
('127.0.0.1', 1344070522, 0, 0),
('127.0.0.1', 1344071015, 0, 0),
('127.0.0.1', 1344071058, 0, 0),
('127.0.0.1', 1344071069, 0, 0),
('127.0.0.1', 1344071096, 0, 0),
('127.0.0.1', 1344071136, 0, 0),
('127.0.0.1', 1344071167, 0, 0),
('127.0.0.1', 1344071188, 0, 0),
('127.0.0.1', 1344071218, 0, 0),
('127.0.0.1', 1344071254, 0, 0),
('127.0.0.1', 1344071263, 0, 0),
('127.0.0.1', 1344071340, 0, 0),
('127.0.0.1', 1344071347, 0, 0),
('127.0.0.1', 1344071425, 0, 0),
('127.0.0.1', 1344071446, 0, 0),
('127.0.0.1', 1344071499, 0, 0),
('127.0.0.1', 1344071524, 0, 0),
('127.0.0.1', 1344071559, 0, 0),
('127.0.0.1', 1344071614, 0, 0),
('127.0.0.1', 1344071642, 0, 0),
('127.0.0.1', 1344071654, 0, 0),
('127.0.0.1', 1344071663, 0, 0),
('127.0.0.1', 1344071730, 0, 0),
('127.0.0.1', 1344071769, 0, 0),
('127.0.0.1', 1344071811, 0, 0),
('127.0.0.1', 1344071819, 0, 0),
('127.0.0.1', 1344071831, 0, 0),
('127.0.0.1', 1344071864, 0, 0),
('127.0.0.1', 1344071885, 0, 0),
('127.0.0.1', 1344071908, 0, 0),
('127.0.0.1', 1344071924, 0, 0),
('127.0.0.1', 1344071934, 0, 0),
('127.0.0.1', 1344072075, 0, 0),
('127.0.0.1', 1344072101, 0, 0),
('127.0.0.1', 1344072114, 0, 0),
('127.0.0.1', 1344072130, 0, 0),
('127.0.0.1', 1344072165, 1, 0),
('127.0.0.1', 1344072174, 1, 0),
('127.0.0.1', 1344072176, 1, 0),
('127.0.0.1', 1344072177, 1, 0),
('127.0.0.1', 1344072225, 1, 0),
('127.0.0.1', 1344072233, 1, 0),
('127.0.0.1', 1344072290, 1, 0),
('127.0.0.1', 1344072331, 0, 0),
('127.0.0.1', 1344072355, 0, 0),
('127.0.0.1', 1344072376, 0, 0),
('127.0.0.1', 1344072387, 0, 0),
('127.0.0.1', 1344072404, 1, 0),
('127.0.0.1', 1344072406, 1, 0),
('127.0.0.1', 1344072442, 1, 0),
('127.0.0.1', 1344072451, 1, 0),
('127.0.0.1', 1344072454, 0, 0),
('127.0.0.1', 1344072474, 0, 0),
('127.0.0.1', 1344072483, 0, 0),
('127.0.0.1', 1344072492, 0, 0),
('127.0.0.1', 1344072501, 0, 0),
('127.0.0.1', 1344072514, 0, 0),
('127.0.0.1', 1344072524, 0, 0),
('127.0.0.1', 1344072541, 0, 0),
('127.0.0.1', 1344072553, 0, 0),
('127.0.0.1', 1344072563, 0, 0),
('127.0.0.1', 1344072591, 0, 0),
('127.0.0.1', 1344072645, 0, 0),
('127.0.0.1', 1344072661, 0, 0),
('127.0.0.1', 1344072694, 0, 0),
('127.0.0.1', 1344072701, 2, 0),
('127.0.0.1', 1344072876, 2, 0),
('127.0.0.1', 1344072879, 1, 0),
('127.0.0.1', 1344072885, 1, 0),
('127.0.0.1', 1344072907, 1, 0),
('127.0.0.1', 1344072933, 1, 0),
('127.0.0.1', 1344072943, 1, 0),
('127.0.0.1', 1344073044, 1, 0),
('127.0.0.1', 1344073053, 1, 0),
('127.0.0.1', 1344073085, 1, 0),
('127.0.0.1', 1344073125, 1, 0),
('127.0.0.1', 1344073141, 1, 0),
('127.0.0.1', 1344073167, 1, 0),
('127.0.0.1', 1344073302, 1, 0),
('127.0.0.1', 1344073318, 1, 0),
('127.0.0.1', 1344073367, 1, 0),
('127.0.0.1', 1344073456, 1, 0),
('127.0.0.1', 1344073482, 1, 0),
('127.0.0.1', 1344073499, 1, 0),
('127.0.0.1', 1344073529, 1, 0),
('127.0.0.1', 1344073546, 1, 0),
('127.0.0.1', 1344073557, 1, 0),
('127.0.0.1', 1344073603, 1, 0),
('127.0.0.1', 1344073617, 1, 0),
('127.0.0.1', 1344073637, 1, 0),
('127.0.0.1', 1344073645, 1, 0),
('127.0.0.1', 1344073681, 1, 0),
('127.0.0.1', 1344073694, 1, 0),
('127.0.0.1', 1344073705, 1, 0),
('127.0.0.1', 1344073722, 1, 0),
('127.0.0.1', 1344073731, 1, 0),
('127.0.0.1', 1344073768, 1, 0),
('127.0.0.1', 1344073841, 1, 0),
('127.0.0.1', 1344073858, 1, 0),
('127.0.0.1', 1344073902, 1, 0),
('127.0.0.1', 1344073924, 1, 0),
('127.0.0.1', 1344073945, 1, 0),
('127.0.0.1', 1344073961, 1, 0),
('127.0.0.1', 1344073992, 1, 0),
('127.0.0.1', 1344073999, 0, 0),
('127.0.0.1', 1344074048, 0, 0),
('127.0.0.1', 1344074067, 1, 0),
('127.0.0.1', 1344074080, 0, 0),
('127.0.0.1', 1344074129, 0, 0),
('127.0.0.1', 1344074137, 0, 0),
('127.0.0.1', 1344074179, 0, 0),
('127.0.0.1', 1344074203, 0, 0),
('127.0.0.1', 1344074207, 1, 0),
('127.0.0.1', 1344074961, 0, 0),
('127.0.0.1', 1344074967, 0, 0),
('127.0.0.1', 1344074990, 0, 0),
('127.0.0.1', 1344075027, 0, 0),
('127.0.0.1', 1344075126, 0, 0),
('127.0.0.1', 1344075143, 0, 0),
('127.0.0.1', 1344076055, 0, 0),
('127.0.0.1', 1344076056, 1, 0),
('127.0.0.1', 1344076058, 1, 0),
('127.0.0.1', 1344076062, 1, 0),
('127.0.0.1', 1344076118, 1, 0),
('127.0.0.1', 1344076131, 1, 0),
('127.0.0.1', 1344076176, 1, 0),
('127.0.0.1', 1344076183, 1, 0),
('127.0.0.1', 1344076204, 1, 0),
('127.0.0.1', 1344076210, 1, 0),
('127.0.0.1', 1344076211, 1, 0),
('127.0.0.1', 1344076248, 1, 0),
('127.0.0.1', 1344076254, 1, 0),
('127.0.0.1', 1344076255, 1, 0),
('127.0.0.1', 1344076264, 1, 0),
('127.0.0.1', 1344076272, 0, 0),
('127.0.0.1', 1344076290, 0, 0),
('127.0.0.1', 1344076301, 0, 0),
('127.0.0.1', 1344076331, 0, 0),
('127.0.0.1', 1344076338, 0, 0),
('127.0.0.1', 1344076353, 0, 0),
('127.0.0.1', 1344076395, 0, 0),
('127.0.0.1', 1344076408, 0, 0),
('127.0.0.1', 1344076418, 0, 0),
('127.0.0.1', 1344076431, 0, 0),
('127.0.0.1', 1344076433, 0, 0),
('127.0.0.1', 1344076439, 0, 0),
('127.0.0.1', 1344076501, 0, 0),
('127.0.0.1', 1344076521, 0, 0),
('127.0.0.1', 1344076553, 0, 0),
('127.0.0.1', 1344076555, 0, 0),
('127.0.0.1', 1344076576, 0, 0),
('127.0.0.1', 1344076578, 0, 0),
('127.0.0.1', 1344076589, 0, 0),
('127.0.0.1', 1344076597, 0, 0),
('127.0.0.1', 1344076598, 0, 0),
('127.0.0.1', 1344076598, 0, 0),
('127.0.0.1', 1344076598, 0, 0),
('127.0.0.1', 1344076598, 0, 0),
('127.0.0.1', 1344076599, 0, 0),
('127.0.0.1', 1344076599, 0, 0),
('127.0.0.1', 1344076610, 0, 0),
('127.0.0.1', 1344076615, 0, 0),
('127.0.0.1', 1344076660, 0, 0),
('127.0.0.1', 1344076680, 0, 0),
('127.0.0.1', 1344076685, 0, 0),
('127.0.0.1', 1344076702, 0, 0),
('127.0.0.1', 1344076711, 0, 0),
('127.0.0.1', 1344076716, 1, 0),
('127.0.0.1', 1344076743, 1, 0),
('127.0.0.1', 1344076761, 1, 0),
('127.0.0.1', 1344076806, 1, 0),
('127.0.0.1', 1344076816, 1, 0),
('127.0.0.1', 1344076826, 1, 0),
('127.0.0.1', 1344076851, 1, 0),
('127.0.0.1', 1344076861, 1, 0),
('127.0.0.1', 1344076866, 1, 0),
('127.0.0.1', 1344076883, 1, 0),
('127.0.0.1', 1344076894, 1, 0),
('127.0.0.1', 1344076902, 1, 0),
('127.0.0.1', 1344076921, 1, 0),
('127.0.0.1', 1344076955, 1, 0),
('127.0.0.1', 1344077000, 1, 0),
('127.0.0.1', 1344077008, 1, 0),
('127.0.0.1', 1344077017, 1, 0),
('127.0.0.1', 1344077043, 1, 0),
('127.0.0.1', 1344077058, 1, 0),
('127.0.0.1', 1344077073, 1, 0),
('127.0.0.1', 1344077098, 1, 0),
('127.0.0.1', 1344077104, 1, 0),
('127.0.0.1', 1344077120, 1, 0),
('127.0.0.1', 1344077133, 1, 0),
('127.0.0.1', 1344077161, 1, 0),
('127.0.0.1', 1344077163, 1, 0),
('127.0.0.1', 1344077173, 1, 0),
('127.0.0.1', 1344077186, 1, 0),
('127.0.0.1', 1344077219, 1, 0),
('127.0.0.1', 1344077221, 1, 0),
('127.0.0.1', 1344077222, 1, 0),
('127.0.0.1', 1344077236, 1, 0),
('127.0.0.1', 1344077242, 1, 0),
('127.0.0.1', 1344077264, 1, 0),
('127.0.0.1', 1344077276, 1, 0),
('127.0.0.1', 1344077280, 0, 0),
('127.0.0.1', 1344077282, 1, 0),
('127.0.0.1', 1344077284, 1, 0),
('127.0.0.1', 1344077298, 1, 0),
('127.0.0.1', 1344077362, 1, 0),
('127.0.0.1', 1344077371, 1, 0),
('127.0.0.1', 1344077400, 1, 0),
('127.0.0.1', 1344077445, 1, 0),
('127.0.0.1', 1344077477, 1, 0),
('127.0.0.1', 1344077498, 1, 0),
('127.0.0.1', 1344077509, 1, 0),
('127.0.0.1', 1344077529, 1, 0),
('127.0.0.1', 1344077539, 1, 0),
('127.0.0.1', 1344077547, 1, 0),
('127.0.0.1', 1344077577, 1, 0),
('127.0.0.1', 1344077625, 1, 0),
('127.0.0.1', 1344077638, 1, 0),
('127.0.0.1', 1344077645, 1, 0),
('127.0.0.1', 1344077653, 1, 0),
('127.0.0.1', 1344077679, 1, 0),
('127.0.0.1', 1344077696, 1, 0),
('127.0.0.1', 1344077745, 1, 0),
('127.0.0.1', 1344077760, 1, 0),
('127.0.0.1', 1344077767, 1, 0),
('127.0.0.1', 1344077779, 1, 0),
('127.0.0.1', 1344077806, 1, 0),
('127.0.0.1', 1344077807, 1, 0),
('127.0.0.1', 1344077813, 1, 0),
('127.0.0.1', 1344077815, 1, 0),
('127.0.0.1', 1344077818, 1, 0),
('127.0.0.1', 1344077823, 1, 0),
('127.0.0.1', 1344077865, 1, 0),
('127.0.0.1', 1344077883, 1, 0),
('127.0.0.1', 1344077903, 1, 0),
('127.0.0.1', 1344077920, 1, 0),
('127.0.0.1', 1344077952, 1, 0),
('127.0.0.1', 1344077963, 1, 0),
('127.0.0.1', 1344077980, 1, 0),
('127.0.0.1', 1344078022, 1, 0),
('127.0.0.1', 1344078040, 1, 0),
('127.0.0.1', 1344078049, 1, 0),
('127.0.0.1', 1344078066, 1, 0),
('127.0.0.1', 1344078075, 1, 0),
('127.0.0.1', 1344078085, 1, 0),
('127.0.0.1', 1344078100, 1, 0),
('127.0.0.1', 1344078111, 1, 0),
('127.0.0.1', 1344078124, 1, 0),
('127.0.0.1', 1344078134, 1, 0),
('127.0.0.1', 1344078144, 1, 0),
('127.0.0.1', 1344078337, 1, 0),
('127.0.0.1', 1344078347, 1, 0),
('127.0.0.1', 1344078376, 1, 0),
('127.0.0.1', 1344078388, 1, 0),
('127.0.0.1', 1344078478, 1, 0),
('127.0.0.1', 1344078496, 1, 0),
('127.0.0.1', 1344078511, 1, 0),
('127.0.0.1', 1344078519, 1, 0),
('127.0.0.1', 1344078538, 1, 0),
('127.0.0.1', 1344078566, 1, 0),
('127.0.0.1', 1344078614, 1, 0),
('127.0.0.1', 1344078622, 1, 0),
('127.0.0.1', 1344078623, 1, 0),
('127.0.0.1', 1344078634, 1, 0),
('127.0.0.1', 1344078638, 1, 0),
('127.0.0.1', 1344078648, 1, 0),
('127.0.0.1', 1344078660, 1, 0),
('127.0.0.1', 1344078664, 1, 0),
('127.0.0.1', 1344078670, 1, 0),
('127.0.0.1', 1344078679, 1, 0),
('127.0.0.1', 1344078705, 1, 0),
('127.0.0.1', 1344078738, 1, 0),
('127.0.0.1', 1344078776, 1, 0),
('127.0.0.1', 1344078793, 1, 0),
('127.0.0.1', 1344078825, 1, 0),
('127.0.0.1', 1344078868, 1, 0),
('127.0.0.1', 1344078886, 1, 0),
('127.0.0.1', 1344078902, 1, 0),
('127.0.0.1', 1344078921, 1, 0),
('127.0.0.1', 1344079050, 1, 0),
('127.0.0.1', 1344079107, 1, 0),
('127.0.0.1', 1344079121, 1, 0),
('127.0.0.1', 1344079150, 1, 0),
('127.0.0.1', 1344079194, 1, 0),
('127.0.0.1', 1344079204, 1, 0),
('127.0.0.1', 1344079232, 1, 0),
('127.0.0.1', 1344079242, 1, 0),
('127.0.0.1', 1344079273, 1, 0),
('127.0.0.1', 1344079294, 1, 0),
('127.0.0.1', 1344079310, 1, 0),
('127.0.0.1', 1344079334, 1, 0),
('127.0.0.1', 1344079354, 1, 0),
('127.0.0.1', 1344079366, 1, 0),
('127.0.0.1', 1344079421, 1, 0),
('127.0.0.1', 1344079429, 1, 0),
('127.0.0.1', 1344079437, 1, 0),
('127.0.0.1', 1344079439, 1, 0),
('127.0.0.1', 1344079453, 1, 0),
('127.0.0.1', 1344079464, 1, 0),
('127.0.0.1', 1344079473, 1, 0),
('127.0.0.1', 1344079488, 1, 0),
('127.0.0.1', 1344079495, 1, 0),
('127.0.0.1', 1344079539, 1, 1),
('127.0.0.1', 1344079546, 1, 1),
('127.0.0.1', 1344079581, 1, 1),
('127.0.0.1', 1344079606, 1, 1),
('127.0.0.1', 1344079625, 1, 1),
('127.0.0.1', 1344079631, 1, 1),
('127.0.0.1', 1344079653, 1, 1),
('127.0.0.1', 1344079674, 1, 1),
('127.0.0.1', 1344079684, 1, 1),
('127.0.0.1', 1344079685, 1, 1),
('127.0.0.1', 1344079694, 1, 1),
('127.0.0.1', 1344079727, 1, 1),
('127.0.0.1', 1344079737, 1, 1),
('127.0.0.1', 1344079745, 1, 1),
('127.0.0.1', 1344079753, 1, 1),
('127.0.0.1', 1344079773, 1, 1),
('127.0.0.1', 1344079799, 1, 0),
('127.0.0.1', 1344079818, 1, 1),
('127.0.0.1', 1344079850, 1, 1),
('127.0.0.1', 1344079866, 1, 1),
('127.0.0.1', 1344079883, 1, 1),
('127.0.0.1', 1344079884, 1, 1),
('127.0.0.1', 1344079885, 1, 1),
('127.0.0.1', 1344079921, 1, 1),
('127.0.0.1', 1344079961, 1, 1),
('127.0.0.1', 1344079962, 1, 1),
('127.0.0.1', 1344079968, 1, 1),
('127.0.0.1', 1344079976, 1, 1),
('127.0.0.1', 1344080003, 1, 0),
('127.0.0.1', 1344080027, 1, 1),
('127.0.0.1', 1344080035, 1, 0),
('127.0.0.1', 1344080041, 1, 1),
('127.0.0.1', 1344080052, 1, 0),
('127.0.0.1', 1344080073, 1, 0),
('127.0.0.1', 1344080085, 1, 0),
('127.0.0.1', 1344080162, 1, 0),
('127.0.0.1', 1344080178, 1, 0),
('127.0.0.1', 1344080186, 1, 0),
('127.0.0.1', 1344080202, 1, 0),
('127.0.0.1', 1344080207, 1, 0),
('127.0.0.1', 1344080221, 1, 0),
('127.0.0.1', 1344080228, 1, 0),
('127.0.0.1', 1344080243, 1, 0),
('127.0.0.1', 1344080254, 1, 0),
('127.0.0.1', 1344080258, 1, 0),
('127.0.0.1', 1344080273, 1, 0),
('127.0.0.1', 1344080460, 1, 0),
('127.0.0.1', 1344080474, 1, 0),
('127.0.0.1', 1344080481, 1, 0),
('127.0.0.1', 1344080503, 1, 0),
('127.0.0.1', 1344080530, 1, 0),
('127.0.0.1', 1344080538, 1, 0),
('127.0.0.1', 1344080551, 1, 1),
('127.0.0.1', 1344080556, 1, 0),
('127.0.0.1', 1344080612, 1, 0),
('127.0.0.1', 1344080660, 1, 0),
('127.0.0.1', 1344080678, 1, 0),
('127.0.0.1', 1344080683, 1, 0),
('127.0.0.1', 1344080695, 1, 0),
('127.0.0.1', 1344080709, 1, 0),
('127.0.0.1', 1344080755, 1, 0),
('127.0.0.1', 1344080763, 1, 0),
('127.0.0.1', 1344080780, 1, 0),
('127.0.0.1', 1344080811, 1, 0),
('127.0.0.1', 1344080813, 0, 0),
('127.0.0.1', 1344080833, 1, 0),
('127.0.0.1', 1344080847, 1, 0),
('127.0.0.1', 1344080853, 0, 0),
('127.0.0.1', 1344080867, 0, 0),
('127.0.0.1', 1344080870, 1, 0),
('127.0.0.1', 1344080882, 1, 0),
('127.0.0.1', 1344080905, 1, 0),
('127.0.0.1', 1344080914, 1, 0),
('127.0.0.1', 1344080919, 0, 0),
('127.0.0.1', 1344080925, 1, 0),
('127.0.0.1', 1344081006, 1, 0),
('127.0.0.1', 1344081024, 1, 0),
('192.168.1.13', 1344081230, 0, 0),
('192.168.1.13', 1344081251, 0, 0),
('192.168.1.13', 1344081263, 1, 0),
('127.0.0.1', 1344081512, 0, 0),
('127.0.0.1', 1344081514, 1, 0),
('192.168.1.13', 1344081520, 1, 0),
('192.168.1.13', 1344081526, 0, 0),
('192.168.1.13', 1344081550, 1, 0),
('192.168.1.13', 1344081616, 1, 0),
('127.0.0.1', 1344081777, 1, 1),
('127.0.0.1', 1344081780, 1, 0),
('127.0.0.1', 1344082138, 1, 0),
('127.0.0.1', 1344082142, 1, 0),
('127.0.0.1', 1344082230, 1, 0),
('127.0.0.1', 1344082232, 0, 0),
('127.0.0.1', 1344082304, 0, 0),
('127.0.0.1', 1344082341, 0, 0),
('127.0.0.1', 1344082350, 0, 0),
('192.168.1.13', 1344082369, 1, 0),
('127.0.0.1', 1344082393, 0, 0),
('192.168.1.13', 1344082399, 1, 0),
('192.168.1.13', 1344082453, 1, 0),
('192.168.1.13', 1344082497, 1, 0),
('192.168.1.13', 1344082519, 1, 0),
('192.168.1.13', 1344082543, 1, 0),
('192.168.1.13', 1344082553, 1, 0),
('192.168.1.13', 1344082577, 1, 0),
('192.168.1.13', 1344082589, 1, 0),
('192.168.1.13', 1344082626, 1, 0),
('192.168.1.13', 1344082632, 0, 0),
('192.168.1.13', 1344082652, 0, 0),
('127.0.0.1', 1344082690, 0, 0),
('127.0.0.1', 1344082695, 1, 0),
('192.168.1.13', 1344082704, 0, 0),
('192.168.1.13', 1344082740, 1, 0),
('192.168.1.13', 1344082775, 1, 0),
('192.168.1.13', 1344082785, 1, 0),
('192.168.1.13', 1344082814, 1, 0),
('192.168.1.13', 1344082828, 0, 0),
('192.168.1.13', 1344082837, 0, 0),
('192.168.1.13', 1344082843, 1, 0),
('192.168.1.13', 1344082868, 1, 1),
('192.168.1.13', 1344082934, 1, 1),
('192.168.1.13', 1344082984, 0, 0),
('192.168.1.13', 1344083010, 0, 0),
('192.168.1.13', 1344083024, 0, 0),
('192.168.1.13', 1344083104, 0, 0),
('127.0.0.1', 1344083375, 0, 0),
('127.0.0.1', 1344083387, 0, 0),
('127.0.0.1', 1344083422, 0, 0),
('127.0.0.1', 1344083467, 0, 0),
('127.0.0.1', 1344083468, 1, 0),
('192.168.1.13', 1344083479, 1, 0),
('192.168.1.13', 1344083506, 1, 0),
('192.168.1.13', 1344083513, 1, 0),
('192.168.1.13', 1344083519, 1, 0),
('192.168.1.13', 1344083539, 1, 0),
('127.0.0.1', 1344084645, 0, 0),
('127.0.0.1', 1344084651, 1, 0),
('127.0.0.1', 1344089790, 0, 0),
('127.0.0.1', 1344163202, 0, 0),
('127.0.0.1', 1344164552, 0, 0),
('127.0.0.1', 1344164554, 2, 0),
('127.0.0.1', 1344164556, 1, 0),
('127.0.0.1', 1344164558, 1, 1),
('127.0.0.1', 1344164565, 1, 1),
('127.0.0.1', 1344164570, 1, 0),
('127.0.0.1', 1344164597, 1, 1),
('127.0.0.1', 1344164654, 1, 0),
('127.0.0.1', 1344164662, 1, 0),
('127.0.0.1', 1344164669, 1, 0),
('127.0.0.1', 1344166521, 0, 0),
('127.0.0.1', 1344166524, 2, 0),
('127.0.0.1', 1344167023, 0, 0),
('127.0.0.1', 1344167026, 1, 0),
('127.0.0.1', 1344167036, 1, 0),
('127.0.0.1', 1344167041, 1, 1),
('127.0.0.1', 1344274208, 0, 0),
('127.0.0.1', 1344274211, 1, 0),
('127.0.0.1', 1344274223, 1, 0),
('127.0.0.1', 1344274231, 1, 1),
('127.0.0.1', 1344274299, 1, 1),
('127.0.0.1', 1344274304, 1, 1),
('127.0.0.1', 1344274321, 1, 1),
('127.0.0.1', 1344274344, 1, 1),
('127.0.0.1', 1344274376, 1, 1),
('127.0.0.1', 1344274427, 1, 1),
('127.0.0.1', 1344274451, 1, 1),
('127.0.0.1', 1344274475, 1, 1),
('127.0.0.1', 1344274921, 1, 1),
('127.0.0.1', 1344274992, 1, 1),
('127.0.0.1', 1344275001, 1, 1),
('127.0.0.1', 1344275066, 1, 1),
('127.0.0.1', 1344275088, 1, 1),
('127.0.0.1', 1344275109, 1, 1),
('127.0.0.1', 1344275112, 1, 1),
('127.0.0.1', 1344275121, 1, 1),
('127.0.0.1', 1344275125, 1, 1),
('127.0.0.1', 1344275146, 1, 1),
('127.0.0.1', 1344275187, 1, 1),
('127.0.0.1', 1344275249, 1, 1),
('127.0.0.1', 1344275270, 1, 1),
('127.0.0.1', 1344275308, 1, 1),
('127.0.0.1', 1344276010, 1, 1),
('127.0.0.1', 1344276069, 1, 1),
('127.0.0.1', 1344276120, 1, 1),
('127.0.0.1', 1344276141, 1, 1),
('127.0.0.1', 1344276232, 1, 1),
('127.0.0.1', 1344276354, 1, 1),
('127.0.0.1', 1344276622, 1, 1),
('127.0.0.1', 1344276662, 1, 1),
('127.0.0.1', 1344276805, 1, 1),
('127.0.0.1', 1344276836, 1, 1),
('127.0.0.1', 1344276867, 1, 1),
('127.0.0.1', 1344276901, 1, 1),
('127.0.0.1', 1344276912, 1, 1),
('127.0.0.1', 1344276930, 1, 1),
('127.0.0.1', 1344277107, 1, 1),
('127.0.0.1', 1344277153, 1, 1),
('127.0.0.1', 1344277296, 1, 1),
('127.0.0.1', 1344277317, 1, 1),
('127.0.0.1', 1344277359, 1, 1),
('127.0.0.1', 1344277452, 1, 1),
('127.0.0.1', 1344277466, 1, 0),
('127.0.0.1', 1344277471, 1, 1),
('127.0.0.1', 1344277485, 1, 1),
('127.0.0.1', 1344277504, 1, 1),
('127.0.0.1', 1344277562, 1, 1),
('127.0.0.1', 1344277577, 1, 1),
('127.0.0.1', 1344277585, 1, 1),
('127.0.0.1', 1344277599, 1, 1),
('127.0.0.1', 1344277655, 1, 1),
('127.0.0.1', 1344462161, 0, 0),
('127.0.0.1', 1344462165, 1, 0),
('127.0.0.1', 1344462166, 1, 0),
('127.0.0.1', 1344462175, 1, 0),
('127.0.0.1', 1344462179, 1, 0),
('127.0.0.1', 1344462189, 1, 0),
('127.0.0.1', 1344462191, 1, 0),
('127.0.0.1', 1344462255, 1, 0),
('127.0.0.1', 1344463245, 1, 0),
('127.0.0.1', 1344463481, 1, 0),
('127.0.0.1', 1344463584, 0, 0),
('127.0.0.1', 1344463590, 1, 0),
('127.0.0.1', 1344463610, 1, 0),
('127.0.0.1', 1344463699, 1, 0),
('127.0.0.1', 1344463830, 0, 0),
('127.0.0.1', 1344463832, 1, 0),
('127.0.0.1', 1344463858, 1, 0),
('127.0.0.1', 1344464110, 1, 0),
('127.0.0.1', 1344464121, 1, 0),
('127.0.0.1', 1344464177, 1, 0),
('127.0.0.1', 1344464185, 1, 0),
('127.0.0.1', 1344464217, 1, 0),
('127.0.0.1', 1344464237, 1, 0),
('127.0.0.1', 1344464240, 1, 0),
('127.0.0.1', 1344464966, 1, 0),
('127.0.0.1', 1344465005, 1, 0),
('127.0.0.1', 1344465009, 1, 0),
('127.0.0.1', 1344465012, 1, 0),
('127.0.0.1', 1344466216, 0, 0),
('127.0.0.1', 1344466220, 1, 0),
('127.0.0.1', 1344466231, 1, 0),
('127.0.0.1', 1344466276, 1, 0),
('127.0.0.1', 1344466311, 1, 0),
('127.0.0.1', 1344466485, 1, 0),
('127.0.0.1', 1344466493, 1, 0),
('127.0.0.1', 1344466502, 1, 0),
('127.0.0.1', 1344466514, 1, 0),
('127.0.0.1', 1344466517, 1, 0),
('127.0.0.1', 1344466521, 1, 0),
('127.0.0.1', 1344466527, 1, 0),
('127.0.0.1', 1344466634, 1, 0),
('127.0.0.1', 1344466650, 1, 0),
('127.0.0.1', 1344466714, 1, 0),
('127.0.0.1', 1344466727, 1, 0),
('127.0.0.1', 1344466737, 1, 0),
('127.0.0.1', 1344466742, 1, 0),
('127.0.0.1', 1344466747, 1, 0),
('127.0.0.1', 1344467104, 1, 0),
('127.0.0.1', 1347473806, 0, 0),
('127.0.0.1', 1347473809, 1, 0),
('127.0.0.1', 1347473827, 1, 0),
('127.0.0.1', 1347473859, 1, 0),
('127.0.0.1', 1347473862, 1, 24),
('127.0.0.1', 1347473882, 1, 0),
('127.0.0.1', 1347473922, 1, 24),
('127.0.0.1', 1347473946, 1, 24),
('127.0.0.1', 1347473954, 1, 24),
('127.0.0.1', 1347474288, 1, 24),
('127.0.0.1', 1347474581, 1, 24),
('127.0.0.1', 1347474585, 1, 24),
('127.0.0.1', 1347474599, 0, 0),
('127.0.0.1', 1347474600, 2, 0),
('127.0.0.1', 1347484562, 0, 0),
('127.0.0.1', 1347484564, 1, 0),
('127.0.0.1', 1347484620, 1, 0),
('127.0.0.1', 1347485006, 1, 0),
('127.0.0.1', 1347485009, 1, 0),
('127.0.0.1', 1347485010, 1, 0),
('127.0.0.1', 1347485046, 1, 0),
('127.0.0.1', 1347485551, 0, 0),
('127.0.0.1', 1347485580, 0, 0),
('127.0.0.1', 1347485611, 0, 0),
('127.0.0.1', 1347485631, 0, 0),
('127.0.0.1', 1347485673, 0, 0),
('127.0.0.1', 1347485732, 0, 0),
('127.0.0.1', 1347485749, 0, 0),
('127.0.0.1', 1347485791, 0, 0),
('127.0.0.1', 1347485797, 0, 0),
('127.0.0.1', 1348695120, 0, 0),
('127.0.0.1', 1348919438, 0, 0),
('127.0.0.1', 1348919445, 1, 0),
('127.0.0.1', 1348919457, 1, 24),
('127.0.0.1', 1348919476, 1, 24),
('127.0.0.1', 1348919706, 1, 0),
('127.0.0.1', 1348919730, 1, 0),
('127.0.0.1', 1348919738, 1, 24),
('127.0.0.1', 1348919747, 1, 0),
('127.0.0.1', 1348919756, 1, 24),
('127.0.0.1', 1348919788, 1, 24),
('127.0.0.1', 1350294394, 0, 0),
('127.0.0.1', 1350294421, 0, 0),
('127.0.0.1', 1350294422, 1, 0),
('127.0.0.1', 1350294426, 1, 24),
('127.0.0.1', 1350294533, 1, 0),
('127.0.0.1', 1350294585, 1, 24),
('127.0.0.1', 1350294634, 1, 0),
('127.0.0.1', 1350294694, 1, 0),
('127.0.0.1', 1350294709, 1, 0),
('127.0.0.1', 1350294727, 1, 0),
('127.0.0.1', 1350294756, 1, 0),
('127.0.0.1', 1350295062, 1, 0),
('127.0.0.1', 1350295087, 1, 0),
('127.0.0.1', 1350295220, 1, 0),
('127.0.0.1', 1350295277, 1, 0),
('127.0.0.1', 1350295284, 1, 0),
('127.0.0.1', 1350295315, 1, 0),
('127.0.0.1', 1350295349, 1, 0),
('127.0.0.1', 1350295456, 1, 0),
('127.0.0.1', 1350295473, 1, 0),
('127.0.0.1', 1350295530, 1, 0),
('127.0.0.1', 1350295536, 1, 0),
('127.0.0.1', 1350295568, 1, 0),
('127.0.0.1', 1350295572, 1, 0),
('127.0.0.1', 1350295684, 1, 0),
('127.0.0.1', 1350295694, 1, 0),
('127.0.0.1', 1350295696, 1, 0),
('127.0.0.1', 1350295699, 1, 0),
('127.0.0.1', 1350374410, 0, 0),
('127.0.0.1', 1350374412, 1, 24),
('127.0.0.1', 1350374418, 1, 24),
('127.0.0.1', 1350470939, 0, 0),
('127.0.0.1', 1350471177, 0, 0),
('127.0.0.1', 1350471181, 0, 0),
('127.0.0.1', 1350471182, 0, 0),
('127.0.0.1', 1350471184, 1, 0),
('127.0.0.1', 1350471497, 0, 0),
('127.0.0.1', 1350471543, 1, 0),
('127.0.0.1', 1350471567, 1, 0),
('127.0.0.1', 1350471705, 1, 0),
('127.0.0.1', 1350471714, 1, 22),
('127.0.0.1', 1350471715, 1, 22),
('127.0.0.1', 1350471716, 1, 22),
('127.0.0.1', 1350471720, 1, 1),
('127.0.0.1', 1350471726, 1, 24),
('127.0.0.1', 1350472082, 1, 0),
('127.0.0.1', 1350472088, 1, 1),
('127.0.0.1', 1350472094, 1, 24),
('127.0.0.1', 1350472100, 1, 22),
('127.0.0.1', 1350472180, 1, 0),
('127.0.0.1', 1350472183, 1, 1),
('127.0.0.1', 1350472188, 1, 0),
('127.0.0.1', 1350472804, 1, 0),
('127.0.0.1', 1350472828, 1, 0),
('127.0.0.1', 1350472934, 1, 0),
('127.0.0.1', 1350472945, 1, 24),
('127.0.0.1', 1350472950, 1, 0),
('127.0.0.1', 1350472991, 1, 24),
('127.0.0.1', 1350473064, 1, 0),
('127.0.0.1', 1350473077, 1, 24),
('127.0.0.1', 1350473080, 1, 0),
('127.0.0.1', 1350473818, 1, 0),
('127.0.0.1', 1350473912, 1, 0),
('127.0.0.1', 1350473940, 1, 0),
('127.0.0.1', 1350474160, 1, 0),
('127.0.0.1', 1350474571, 1, 0),
('127.0.0.1', 1350475934, 0, 0),
('127.0.0.1', 1350475936, 1, 0),
('127.0.0.1', 1350475949, 1, 0),
('127.0.0.1', 1350475954, 1, 0),
('127.0.0.1', 1350476094, 1, 0),
('127.0.0.1', 1350476112, 1, 0),
('127.0.0.1', 1350815525, 0, 0),
('127.0.0.1', 1350815527, 1, 0),
('127.0.0.1', 1350815534, 1, 0),
('127.0.0.1', 1350815547, 1, 0);
INSERT IGNORE INTO `ch_hits` (`ip`, `time`, `BID`, `PID`) VALUES
('127.0.0.1', 1350815570, 1, 0),
('127.0.0.1', 1350815724, 1, 0),
('127.0.0.1', 1350815972, 1, 0),
('127.0.0.1', 1350815974, 1, 0),
('127.0.0.1', 1350815975, 1, 0),
('127.0.0.1', 1350815975, 1, 0),
('127.0.0.1', 1350815975, 1, 0),
('127.0.0.1', 1350816011, 1, 0),
('127.0.0.1', 1350816054, 1, 0),
('127.0.0.1', 1350816058, 1, 0),
('127.0.0.1', 1350816174, 1, 0),
('127.0.0.1', 1350816238, 1, 0),
('127.0.0.1', 1350816244, 1, 0),
('127.0.0.1', 1350816328, 1, 0),
('127.0.0.1', 1350816398, 1, 0),
('127.0.0.1', 1351938090, 0, 0),
('127.0.0.1', 1352226189, 0, 0),
('127.0.0.1', 1352226192, 1, 0),
('127.0.0.1', 1352226211, 1, 0),
('127.0.0.1', 1352226222, 1, 0),
('127.0.0.1', 1352226236, 1, 0),
('127.0.0.1', 1352226246, 1, 0),
('127.0.0.1', 1352226258, 1, 0),
('127.0.0.1', 1352226272, 1, 0),
('127.0.0.1', 1352226285, 1, 0),
('127.0.0.1', 1352226342, 1, 0),
('127.0.0.1', 1352226352, 1, 0),
('127.0.0.1', 1352226452, 1, 0),
('127.0.0.1', 1352226595, 1, 0),
('127.0.0.1', 1352226634, 1, 0),
('127.0.0.1', 1352226640, 1, 0),
('127.0.0.1', 1352226723, 1, 0),
('127.0.0.1', 1352226747, 1, 0),
('127.0.0.1', 1352226898, 1, 0),
('127.0.0.1', 1352226909, 1, 0),
('127.0.0.1', 1352227142, 1, 0),
('127.0.0.1', 1352227171, 1, 0),
('127.0.0.1', 1352227434, 1, 0),
('127.0.0.1', 1352227456, 1, 0),
('127.0.0.1', 1352227630, 1, 0),
('127.0.0.1', 1352227668, 1, 0),
('127.0.0.1', 1352227707, 1, 0),
('127.0.0.1', 1352227744, 1, 0),
('127.0.0.1', 1352227771, 1, 0),
('127.0.0.1', 1352227911, 1, 0),
('127.0.0.1', 1352227947, 1, 0),
('127.0.0.1', 1352228022, 1, 0),
('127.0.0.1', 1352228067, 1, 0),
('127.0.0.1', 1352228152, 1, 0),
('127.0.0.1', 1352228179, 1, 0),
('127.0.0.1', 1352229639, 1, 0),
('127.0.0.1', 1352229657, 1, 0),
('127.0.0.1', 1352229682, 1, 0),
('127.0.0.1', 1352229700, 1, 0),
('127.0.0.1', 1352229752, 1, 0),
('127.0.0.1', 1352229766, 1, 0),
('127.0.0.1', 1352229802, 1, 0),
('127.0.0.1', 1352229813, 1, 0),
('127.0.0.1', 1352229816, 1, 0),
('127.0.0.1', 1352229822, 1, 0),
('127.0.0.1', 1352229956, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ch_posts`
--

CREATE TABLE IF NOT EXISTS `ch_posts` (
  `postID` bigint(20) NOT NULL AUTO_INCREMENT,
  `BID` bigint(20) NOT NULL,
  `PID` bigint(20) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `trip` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `subject` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `bumptime` int(11) NOT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `fileorig` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `filedim` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `filesize` int(11) NOT NULL,
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `options` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`BID`,`postID`),
  KEY `PID` (`PID`),
  KEY `options` (`options`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ch_posts`
--

INSERT IGNORE INTO `ch_posts` (`postID`, `BID`, `PID`, `name`, `mail`, `trip`, `title`, `subject`, `time`, `bumptime`, `password`, `file`, `fileorig`, `filedim`, `filesize`, `ip`, `options`) VALUES
(9, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;1', 1341924797, 1341924797, 'mMr4uFjQzmDundefined](9', '134192479738027.jpg', 'cc029.jpg', '1920x1080', 87906, '127.0.0.1', 'p'),
(7, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;1', 1341678583, 1341678583, 'mMr4uFjQzmDundefined](9', '134167858380404.jpg', 'cc025.jpg', '1920x1080', 175186, '127.0.0.1', 'p'),
(8, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;1', 1341924754, 1341924754, 'mMr4uFjQzmDundefined](9', '134192475434498.jpg', 'cc029.jpg', '1920x1080', 87906, '127.0.0.1', 'p'),
(15, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;10\r\n&gt;&gt;12\r\n&gt;&gt;12', 1341926970, 1341926970, 'mMr4uFjQzmDundefined](9', '134192697082022.png', 'cc061.png', '704x400', 847865, '127.0.0.1', 'p'),
(16, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;14\r\nFucking shit.', 1341926991, 1341926991, 'mMr4uFjQzmDundefined](9', '134192699190020.jpg', 'cc032.jpg', '864x480', 35203, '127.0.0.1', 'p'),
(17, 1, 1, '', '', '!C.C./tYwhI', '', 'Fucking bug.', 1341927013, 1341927013, 'mMr4uFjQzmDundefined](9', '134192701378307.jpg', 'cc044.jpg', '640x446', 35910, '127.0.0.1', 'p'),
(18, 1, 1, '', '', '!C.C./tYwhI', '', '', 1341927104, 1341927104, 'mMr4uFjQzmDundefined](9', '134192710475671.png', 'cc124.png', '560x362', 141703, '127.0.0.1', 'p'),
(19, 1, 1, 'LOLDONGS', '', '!C.C./tYwhI', 'PENIS', '&gt;&gt;18\r\nFinally!', 1341927318, 1341927318, 'mMr4uFjQzmDundefined](9', '134192731821813.jpg', 'Code.Geass-.Lelouch.of.the.Rebellion.416898.jpg', '451x640', 96043, '127.0.0.1', 'm,p'),
(21, 1, 1, '', '', '', '', '', 1344164601, 1344164601, ',Mlu-Pbc7ZDe8[]', '134416460131402.jpg', '3f0124cc9e24a12c9a20614a4fd0fdd6.jpg', '240x320', 26700, '127.0.0.1', 'p'),
(22, 1, 0, '', '', '', '', '', 1344164658, 1344164658, ',Mlu-Pbc7ZDe8[]', '134416465863088.jpg', '3e2020c5e5c6b31ae565d6b72c69b556.jpg', '600x600', 187504, '127.0.0.1', 'p'),
(13, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;10', 1341926783, 1341926783, 'mMr4uFjQzmDundefined](9', '134192678269346.jpg', 'cc015.jpg', '705x502', 50878, '127.0.0.1', 'p'),
(14, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;10\r\n&gt;&gt;12', 1341926857, 1341926857, 'mMr4uFjQzmDundefined](9', '134192685752486.png', 'cc061.png', '704x400', 847865, '127.0.0.1', 'p'),
(12, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;1', 1341925341, 1341925341, 'mMr4uFjQzmDundefined](9', '134192534163760.jpg', 'cc029.jpg', '1920x1080', 87906, '127.0.0.1', 'p'),
(10, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;1', 1341924937, 1341924937, 'mMr4uFjQzmDundefined](9', '134192493714294.jpg', 'cc029.jpg', '1920x1080', 87906, '127.0.0.1', 'p'),
(11, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;1', 1341925250, 1341925250, 'mMr4uFjQzmDundefined](9', '134192525064266.jpg', 'cc029.jpg', '1920x1080', 87906, '127.0.0.1', 'p'),
(4, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;2\r\n&gt;&gt;3\r\nStatistics!', 1341389734, 1341389734, 'mMr4uFjQzmDundefined](9', '134138973446503.jpg', 'cc019.jpg', '640x440', 58560, '127.0.0.1', 'p'),
(5, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;4\r\nQuite so.\r\nIt&apos;s looking really fancy though!\r\nI&apos;m a bit scared of the hits statistics counter causing too much bandwidth, but I hope it&apos;ll be alright.\r\nBlah.', 1341392072, 1341392072, 'mMr4uFjQzmDundefined](9', '134139207213289.jpg', 'cc026.jpg', '1920x1080', 85592, '127.0.0.1', 'p'),
(6, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;5\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eu lacus in libero ornare viverra. Donec volutpat viverra varius. In quis ornare ligula. Maecenas cursus sodales odio, vel semper purus tempor ac. Suspendisse porttitor, justo sit amet molestie lacinia, mi urna vehicula ipsum, id ultrices est nibh non nibh. Nullam convallis enim vel tellus lobortis lacinia. Pellentesque vitae nulla magna, ut lacinia quam. Pellentesque dapibus sapien sit amet lorem ultrices egestas. Duis tincidunt, eros vitae lobortis placerat, tortor nulla tincidunt lacus, nec sagittis massa metus ac nisi.\r\n\r\nMauris fringilla, libero sit amet dictum facilisis, felis arcu vestibulum justo, vitae accumsan sem justo vel nunc. Aenean aliquam, dui ac gravida porta, sem est mattis tellus, eu pulvinar odio nunc quis est. Cras feugiat sodales lacus sed egestas. Pellentesque eleifend fermentum arcu fermentum mattis. Nam eu dictum elit. Donec placerat scelerisque massa quis pretium. Suspendisse potenti. Nam et orci diam. Donec sed nulla ut velit ultricies dictum quis viverra leo. Nunc id arcu eget ligula blandit semper sed eu nisl. In lacinia mattis lorem et euismod. Phasellus pretium sapien id est mollis vestibulum.\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eu lacus in libero ornare viverra. Donec volutpat viverra varius. In quis ornare ligula. Maecenas cursus sodales odio, vel semper purus tempor ac. Suspendisse porttitor, justo sit amet molestie lacinia, mi urna vehicula ipsum, id ultrices est nibh non nibh. Nullam convallis enim vel tellus lobortis lacinia. Pellentesque vitae nulla magna, ut lacinia quam. Pellentesque dapibus sapien sit amet lorem ultrices egestas. Duis tincidunt, eros vitae lobortis placerat, tortor nulla tincidunt lacus, nec sagittis massa metus ac nisi.\r\n\r\nMauris fringilla, libero sit amet dictum facilisis, felis arcu vestibulum justo, vitae accumsan sem justo vel nunc. Aenean aliquam, dui ac gravida porta, sem est mattis tellus, eu pulvinar odio nunc quis est. Cras feugiat sodales lacus sed egestas. Pellentesque eleifend fermentum arcu fermentum mattis. Nam eu dictum elit. Donec placerat scelerisque massa quis pretium. Suspendisse potenti. Nam et orci diam. Donec sed nulla ut velit ultricies dictum quis viverra leo. Nunc id arcu eget ligula blandit semper sed eu nisl. In lacinia mattis lorem et euismod. Phasellus pretium sapien id est mollis vestibulum.', 1341410079, 1341410079, 'mMr4uFjQzmDundefined](9', '', '', '', 0, '127.0.0.1', 'p'),
(1, 1, 0, '', '', '!C.C./tYwhI', '', 'spoiler{penis}', 1341352091, 1344164667, 'mMr4uFjQzmDundefined](9', '134135209197876.jpg', 'cc014.jpg', '225x350', 115538, '127.0.0.1', 'p'),
(2, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;1', 1341352099, 1341352099, 'mMr4uFjQzmDundefined](9', '134135209986965.png', 'cc018.png', '400x225', 142460, '127.0.0.1', 'p'),
(3, 1, 1, '', '', '!C.C./tYwhI', '', '&gt;&gt;2', 1341356078, 1341356078, 'mMr4uFjQzmDundefined](9', '', '', '', 0, '127.0.0.1', 'p'),
(20, 1, 1, '', '', '', '', '', 1344164570, 1344164570, ',Mlu-Pbc7ZDe8[]', '134416457047224.jpg', '3f0124cc9e24a12c9a20614a4fd0fdd6.jpg', '240x320', 26700, '127.0.0.1', 'p'),
(23, 1, 1, '', '', '', '', '&gt;&gt;1', 1344164667, 1344164667, ',Mlu-Pbc7ZDe8[]', '134416466699101.jpg', '4d1a2241b624f9e15e0ed062d2bc44f2.jpg', '372x400', 86599, '127.0.0.1', 'p'),
(24, 1, 0, '', '', '', 'Test', 'Test', 1347473859, 1350815534, ',Mlu-Pbc7ZDe8[]', '134747385958508.jpg', 'commander full speed ahead.jpg', '453x688', 79810, '127.0.0.1', 'p'),
(25, 1, 24, '', '', '', '', 'awda', 1347473881, 1347473881, ',Mlu-Pbc7ZDe8[]', '', '', '', 0, '127.0.0.1', 'p'),
(26, 1, 24, '', 'noko', '', '', 'adawd', 1347473954, 1347473954, ',Mlu-Pbc7ZDe8[]', '', '', '', 0, '127.0.0.1', 'p'),
(27, 1, 24, '', 'noko', '', '', '', 1347474585, 1347474585, ',Mlu-Pbc7ZDe8[]', '134747458541847.jpg', 'now let me explain my point.jpg', '602x561', 37953, '127.0.0.1', 'p'),
(28, 1, 24, '', 'noko', '', '', 'i&apos;m trying to update it more, unfortunately sometimes things come up and i can&apos;t do that. i&apos;ll try to\r\nstick to a post a day or every other day (provided i don&apos;t get writer&apos;s block again) \r\n\r\ni don&apos;t know how to dm, as i don&apos;t play d&d so i have any experience with what they do... \r\ni assume you&apos;re talking about the back n forthing in the timeline? now that the story&apos;s more into the swing of\r\nthings and the backstory as to why the world is the way it is has been told, there&apos;s going to be a lot less jumping\r\nin the timeline. \r\nunfortunately, when i do write the backstory i enjoy fleshing out the world, so the backstory posts will probably stay\r\nthe same length as they are when they do occur.', 1348919476, 1348919476, ',Mlu-Pbc7ZDe8[]', '', '', '', 0, '127.0.0.1', 'p'),
(29, 1, 24, '', 'noko', '', '', '&gt;&gt;lol\r\n&gt;whatever\r\ndicks\r\n&gt;penis', 1348919788, 1348919788, ',Mlu-Pbc7ZDe8[]', '', '', '', 0, '127.0.0.1', 'p'),
(30, 1, 24, '', 'noko', '', '', '&gt;&gt;24\r\nbla', 1350294585, 1350294585, ',Mlu-Pbc7ZDe8[]', '', '', '', 0, '127.0.0.1', 'p'),
(31, 1, 24, '', '', '', '', '&gt;&gt;24\r\nbla', 1350294633, 1350294633, ',Mlu-Pbc7ZDe8[]', '', '', '', 0, '127.0.0.1', 'p'),
(32, 1, 24, '', '', '', '', 'This thread has been moved to &gt;&gt;iou/24', 1350815534, 1350815534, ',Mlu-Pbc7ZDe8[]', '', '', '', 0, '127.0.0.1', 'p'),
(33, 1, 0, '', '', '', '', '', 1352226211, 1352226211, ',Mlu-Pbc7ZDe8[]', '135222621080618.gif', '95791311e960d7d1b4b651a488321e20.gif', '600x1740', 176198, '127.0.0.1', 'p'),
(34, 1, 0, '', '', '', '', '', 1352226222, 1352226222, ',Mlu-Pbc7ZDe8[]', '135222622287210.jpg', '1317290752_copypasta.jpg', '750x600', 37862, '127.0.0.1', 'p'),
(35, 1, 0, '', '', '', '', '', 1352226236, 1352226236, ',Mlu-Pbc7ZDe8[]', '135222623537905.jpeg', '1318851550515.jpeg', '450x300', 45486, '127.0.0.1', 'p'),
(36, 1, 0, '', '', '', '', '', 1352226246, 1352226246, ',Mlu-Pbc7ZDe8[]', '135222624682336.jpeg', '1315557054303.jpeg', '800x600', 350762, '127.0.0.1', 'p'),
(37, 1, 0, '', '', '', '', '', 1352226258, 1352226258, ',Mlu-Pbc7ZDe8[]', '135222625886524.jpeg', '1330490629280.jpeg', '500x385', 67884, '127.0.0.1', 'p'),
(38, 1, 0, '', '', '', '', '', 1352226272, 1352226272, ',Mlu-Pbc7ZDe8[]', '135222627246777.png', '1341168819423.png', '580x1752', 137416, '127.0.0.1', 'p'),
(39, 1, 0, '', '', '', '', '', 1352226285, 1352226285, ',Mlu-Pbc7ZDe8[]', '135222628515531.jpg', '1341003322355.jpg', '470x332', 41815, '127.0.0.1', 'p'),
(40, 1, 0, '', '', '', '', '', 1352226352, 1352226352, ',Mlu-Pbc7ZDe8[]', '135222635217741.jpg', '1216340939471.jpg', '592x285', 26178, '127.0.0.1', 'p');

-- --------------------------------------------------------

--
-- Table structure for table `ch_reports`
--

CREATE TABLE IF NOT EXISTS `ch_reports` (
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `reason` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `PID` int(10) unsigned NOT NULL,
  `folder` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `derpy_messages`
--

CREATE TABLE IF NOT EXISTS `derpy_messages` (
  `messageID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `recipient` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'm',
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No Subject',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `time` int(8) unsigned NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `derpy_messages`
--

INSERT IGNORE INTO `derpy_messages` (`messageID`, `sender`, `recipient`, `type`, `title`, `text`, `time`, `read`) VALUES
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
  `folder` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pictures` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`folder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `display_folders`
--

INSERT IGNORE INTO `display_folders` (`folder`, `text`, `pictures`) VALUES
('shinmera', 'Artwork done by Nicolas Hafner, aka. Shinmera.', ' ,199'),
('shinmera/3D-CG', 'Sometimes I do 3D computer graphic artwork.', '129,128,112,57'),
('shinmera/characters', 'Drawings of characters I created and designed myself.', '186,183,178,177,176,174,172,171,170,169,165,164,160,157,149,148,118,113,77,72,71,39,38,36,35,31,30,27'),
('shinmera/characters/re-boot', 'Re-Boot design sketches.', '143,162,161,141,140,117,110,109,108,107,106,105,94,89,82,70,69,49'),
('shinmera/misc', 'Things that just don&apos;t fit anywhere else.', '194,189,185,184,181,179,134,133,132,131,130,126,124,123,121,120,116,115,114,56,34,33,25,24,104,64,195'),
('shinmera/MLP', 'I had a phase during which I drew a lot of artwork related to MLP:FiM.', '173,167,159,151,142,138,122,119,98,23,22,97,91,90,88,87,86,85,84,83,81,80,79,78,76,75,73,68,63,62,61,60,59,58,55,54,53,52,51,50,48,47,46,45,44,43,42,41,40,74'),
('shinmera/sketches', 'Sketchwork and practice', '193,192,191,190,188,187,182,180,168,166,163,158,156,155,154,153,150,147,146,145,139,137,136,135,125,96,95,93,92,67,66,65,37,29,28,152'),
('shinmera/transcend', 'Drawings and concept art for my game project.', '99,100,101,102,103,175');

-- --------------------------------------------------------

--
-- Table structure for table `display_pictures`
--

CREATE TABLE IF NOT EXISTS `display_pictures` (
  `pictureID` int(11) NOT NULL AUTO_INCREMENT,
  `folder` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) NOT NULL,
  `tags` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pictureID`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=200 ;

--
-- Dumping data for table `display_pictures`
--

INSERT IGNORE INTO `display_pictures` (`pictureID`, `folder`, `title`, `text`, `time`, `tags`, `filename`, `user`) VALUES
(22, 'shinmera/MLP', 'Science!', 'A drawing for Mithent.', 1316361255, 'Twilight,Science,Mithent,Gift,Sci-Fi', '1316262988-science.png', 'Shinmera'),
(23, 'shinmera/MLP', 'Human Twilight Ohshit', 'Still not really satisfied with the result.\r\nAnd I&#039;m also still a bit wonky with the strokes so it&#039;ll take me some time to get used to drawing human faces.', 1316361256, 'Human,Twilight,Reaction Pic', '1316265245-twilight human ohshit.png', 'Shinmera'),
(24, 'shinmera/misc', 'LIFDOFF', 'And there he goess..................\r\nhe has LIFDOFF', 1316350810, 'Lifdoff,SBaHJ', '1316350810-444LIFDOFF.jpg', 'Shinmera'),
(25, 'shinmera/misc', 'Sweet Cat and Hella Kaz', 'Oh boy here we go.', 1316350931, 'SBaHJ,Stairs', '1316350931-448SWEET CAT AND HELLA KAZ.jpg', 'Shinmera'),
(27, 'shinmera/characters', 'Siena Avatar', 'I think even though this is fairly old already, it still looks pretty good.', 1316351847, 'Siena,Avatar', '1316351847-450Siena avatar 3 combined shading.png', 'Shinmera'),
(28, 'shinmera/sketches', 'Roo head', 'One of the better Kangaroo practice sketches.', 1316351920, 'Kangaroo,', '1316351920-451roo again.png', 'Shinmera'),
(29, 'shinmera/sketches', 'Nique Redesign Sketches', 'I still like this sketch.', 1316351998, 'Nique,Redesign,Spherae', '1316351998-452nique redesign sketches.png', 'Shinmera'),
(30, 'shinmera/characters', 'Wolf - Anja Free Time', 'I dunno lol.', 1316352079, 'Wallpaper,Anja', '1316352079-458wolf - anja free time bg.png', 'Shinmera'),
(31, 'shinmera/characters', 'Katja', 'This reminds me to practice anatomy again sometime.', 1316352135, 'Anatomy Practice,Katja', '1316352135-459katja.png', 'Shinmera'),
(33, 'shinmera/misc', 'OMNOM', 'NOM', 1316352310, 'Food', '1316352310-464omnomnom.png', 'Shinmera'),
(34, 'shinmera/misc', 'So Baked', 'You really don&amp;#039;t know.', 1316352389, 'Baked,Muffin', '1316352389-465oh man you dont even know.png', 'Shinmera'),
(35, 'shinmera/characters', 'Katja Window', 'Eeeeh. I still don&#039;t really like this.', 1316352813, '', '1316352813-467katja window3.png', 'Shinmera'),
(36, 'shinmera/characters', 'Katja Window - Anatomy', 'The anatomy behind it.', 1316353120, 'Anatomy Practice,Katja', '1316353120-470katja window4.png', 'Shinmera'),
(37, 'shinmera/sketches', 'Haters Gonna Hate', 'LA LA LA LAAAA', 1316353162, 'Haters', '1316353162-495haters gonna hate.png', 'Shinmera'),
(38, 'shinmera/characters', 'Sigh', '*sign*', 1316353394, '', '1316353394-499sign-again.png', 'Shinmera'),
(39, 'shinmera/characters', 'Style Tests', 'This one of the tests shortly before the breakdown.', 1316353462, 'Style Test,Practice', '1316353462-507style tests.png', 'Shinmera'),
(40, 'shinmera/MLP', 'Hot Day', 'I hate hot days.', 1316353843, 'Derpy,Hooves', '1316353843-529hawt derpy.png', 'Shinmera'),
(41, 'shinmera/MLP', 'Screw This', 'SCREW IT ALL.', 1316353877, 'Derpy,Hooves', '1316353877-530derpy screw this.png', 'Shinmera'),
(42, 'shinmera/MLP', 'Bow', 'Thank you, thank you.', 1316353905, 'Derpy,Hooves', '1316353905-531derpy bow.png', 'Shinmera'),
(43, 'shinmera/MLP', 'Derpy &amp; Dinky Sleeping', 'D&#039;awwwwwwwwww.', 1316353954, 'Derpy,Dinky,Hooves', '1316353954-532derpy dinky sleeping.png', 'Shinmera'),
(44, 'shinmera/MLP', 'Derpy &amp; Dinky Wheee', 'Wheeeeeeeee', 1316353984, 'Derpy,Dinky,Hooves', '1316353984-533derpy dinky wheeeee.png', 'Shinmera'),
(45, 'shinmera/MLP', 'Maths All The Way', 'Selina quite likes maths.\r\nMaths all the way.', 1316354050, 'Selina,Maths,Wallpaper', '1316354050-534selina maths all the way.png', 'Shinmera'),
(46, 'shinmera/MLP', 'Derpy &amp; Dinky Hug', 'Mid air hugs.', 1316354104, 'Derpy,Dinky,Hooves,Wallpaper', '1316354104-535derpy dinky happiness hug bg.png', 'Shinmera'),
(47, 'shinmera/MLP', 'The Doctor Derpy', 'Crossover, yay!', 1316354188, 'Derpy,Hooves,Doctor,Whooves,Dr.,Who,Wallpaper', '1316354188-536derpy whooves.png', 'Shinmera'),
(48, 'shinmera/MLP', 'Coffee', 'Slurp.', 1316354241, 'Derpy,Hooves', '1316354241-537huge derpy with muffin coffee.png', 'Shinmera'),
(49, 'shinmera/characters/re-boot', 'Cecilia', 'I still really like this picture.', 1316354291, 'Cecilia', '1316354291-538cecilia.png', 'Shinmera'),
(50, 'shinmera/MLP', 'Super Derpy', 'Danananananana Derpman!', 1316354488, 'Derpy,Hooves', '1316354488-539super derpy.png', 'Shinmera'),
(51, 'shinmera/MLP', 'Nap Time', 'More d&#039;awwww.', 1316354784, 'Derpy,Dinky,Hooves', '1316354784-543derpy dinky nap time.png', 'Shinmera'),
(52, 'shinmera/MLP', 'Derpy Jones and the Fate of Muff', 'Da da da daaaa, da da daaaa!\r\nDa da da daaaa, da da da da daa!', 1316354858, 'Derpy,Hooves', '1316354858-545derpy  jones and the fate of muffin.png', 'Shinmera'),
(53, 'shinmera/MLP', 'DJ DAV-3', 'DAVE, IS THIS YOU?', 1316355011, 'Dave,Homestuck,MSPA,DJ,PON-3', '1316355011-547DJ DAV-3.png', 'Shinmera'),
(54, 'shinmera/MLP', 'Dash Motorcycle', 'Yeah I suck at drawing any kind of vehicle.', 1316355088, 'Rainbow,Dash,Motorcycle', '1316355088-548dash bike.png', 'Shinmera'),
(55, 'shinmera/MLP', 'Snuggle', 'D&#039;awwww', 1316355124, 'Bon Bon,Lyra', '1316355124-552bon bon lyra snuggle.png', 'Shinmera'),
(56, 'shinmera/misc', 'Crossover Time', 'Why the hell not.', 1316355166, 'John,Jade,Homestuck,MSPA,MLP', '1316355166-553crossover time.png', 'Shinmera'),
(57, 'shinmera/3D-CG', 'GODDAMN', 'Rendering things takes too long.', 1316355206, 'Rendering,3D,CG', '1316355206-556goddamn.jpg', 'Shinmera'),
(58, 'shinmera/MLP', 'Feeling Lucky, Punk?', 'EH?', 1316355252, 'Derpy,Hooves,Western', '1316355252-557derpy western.png', 'Shinmera'),
(59, 'shinmera/MLP', 'Nibble', 'Marshmallow Pony.\r\nDelicious.', 1316355286, 'Rarity,MLP,LOLWUT', '1316355286-558rarity nibble.png', 'Shinmera'),
(60, 'shinmera/MLP', 'Tea Time', 'Tea is good.', 1316355333, 'Derpy,Hooves,Twilight,Sparkle,Bon Bon', '1316355333-559discussion.png', 'Shinmera'),
(61, 'shinmera/MLP', 'The Doctor and the Cube', 'HUG', 1316356057, 'Dr.,Doctor,Who,Companion,Cube,Crossover', '1316356057-560doctor hugging companion cube.png', 'Shinmera'),
(62, 'shinmera/MLP', 'IDK', 'Minty requested something and I drew it.', 1316356792, 'Minty,Phoenix,Wright,LOLWUT', '1316356792-564mintys request uhm yeah idk.png', 'Shinmera'),
(63, 'shinmera/MLP', 'Bed', 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZzzzzzzzzzZZZZZzz\r\nZZZzzzZzzzzzzzzzzzzzzzzzzzzzZZZZZZZzzzzzzzzzzzzzzzz', 1316356840, 'Derpy,Hooves', '1316356840-565derpy bed.png', 'Shinmera'),
(64, 'shinmera/misc', 'Its Been Some Time', 'This was the first time I drew furries again after the breakdown.', 1316356896, 'Cecilia', '1316356896-566its been some time.png', 'Shinmera'),
(65, 'shinmera/sketches', 'Bar', '&#039;sup ladies?', 1316356924, '', '1316356924-568bar.jpg', 'Shinmera'),
(66, 'shinmera/sketches', 'Above', 'The world above.', 1316357004, '', '1316357004-569above.jpg', 'Shinmera'),
(67, 'shinmera/sketches', 'Hmm', 'Mmh?', 1316357043, '', '1316357043-570hmm.jpg', 'Shinmera'),
(68, 'shinmera/MLP', 'Derpy, You&#039;re A Wizard', 'I&#039;m a what?', 1316357091, 'Derpy,Hooves,You,Are,A,Wizard,Harry', '1316357091-572derpy wizard.png', 'Shinmera'),
(69, 'shinmera/characters/re-boot', 'Cecilia Ugh', 'Ugh. Damn adventures all the time.', 1316357132, 'Cecilia', '1316357132-573cecilia ugh.png', 'Shinmera'),
(70, 'shinmera/characters/re-boot', 'Cecilia Uuuuh', 'I DON&#039;T KNOW NOTHIN&#039; ABOUT ANYTHING!', 1316357161, 'Cecilia', '1316357161-574cecilia uuuuhhh.png', 'Shinmera'),
(71, 'shinmera/characters', 'RAEG', '!!', 1316357184, '', '1316357184-575RAGE.png', 'Shinmera'),
(72, 'shinmera/characters', 'Tee Hee', 'Your faggotry amuses me.', 1316357202, '', '1316357202-576your faggotry amuses me.png', 'Shinmera'),
(73, 'shinmera/MLP', 'YEAH!', 'FUCK YEHA!', 1316357231, 'Rainbow,Dash,OH YEAH!', '1316357231-578dash yeah.png', 'Shinmera'),
(74, 'shinmera/MLP', 'Meh', 'Oh boy, did someone dress badly?\r\nI am sooooooooooooo sorry, Rarity.', 1316357285, 'Rarity,MLP', '1316357285-579rarity meh.png', 'Shinmera'),
(75, 'shinmera/MLP', 'Smile', '...', 1316357324, 'Fluttershy,MLP', '1316357324-580flutter smile.png', 'Shinmera'),
(76, 'shinmera/MLP', 'Hmm', 'Hmmmm.\r\nI require additional time to grasp an understanding of this quite marvellous idea, fellow chaps.', 1316357401, 'Derpy,Hooves', '1316357401-581derpy hmmmm.png', 'Shinmera'),
(77, 'shinmera/characters', 'Smirk', 'Hee hee heee', 1316357427, 'Cecilia', '1316357427-583lets try this again.png', 'Shinmera'),
(78, 'shinmera/MLP', 'Colgate', 'Brush your teeth every day.', 1316357462, 'Colgate,MLP,Brushin Erryday', '1316357462-584colgate.png', 'Shinmera'),
(79, 'shinmera/MLP', 'Octavia', 'Engage lurk mode!', 1316357487, 'Octavia,MLP', '1316357487-585octavia.png', 'Shinmera'),
(80, 'shinmera/MLP', 'BEST PONY', 'It&#039;s so obvious, isn&#039;t it?', 1316357516, 'Derpy,Hooves,Best,Pony,Fact', '1316357516-586best pony.png', 'Shinmera'),
(81, 'shinmera/MLP', 'Scarf', 'I love scarves. \r\nScarves are stylish and comfortable.', 1316357554, 'Derpy,Hooves,Scarf', '1316357554-587derpy scarf.png', 'Shinmera'),
(82, 'shinmera/characters/re-boot', 'Green Fields', 'Smells like spring.', 1316357580, 'Cecilia', '1316357580-588cecilia green field.png', 'Shinmera'),
(83, 'shinmera/MLP', 'Expressive Pose', 'Quite.', 1316357605, 'Rainbow,Dash', '1316357605-589rd expressive pose.png', 'Shinmera'),
(84, 'shinmera/MLP', 'Rainstorm', 'I love rain.', 1316357630, 'Derpy,Hooves,Rain', '1316357630-590rainstorm.png', 'Shinmera'),
(85, 'shinmera/MLP', 'Coast', 'Oh gog, the kitsch!', 1316357702, 'Derpy,Dinky,Hooves,Coast', '1316357702-591derpy dinky coast.png', 'Shinmera'),
(86, 'shinmera/MLP', 'COME AT ME BRO', 'K.O.', 1316357810, 'Derpy,Hooves', '1316357810-592COME AT ME BRO.png', 'Shinmera'),
(87, 'shinmera/MLP', 'I SAID NO', 'BONK', 1316357835, 'Derpy,Hooves,Exploitable', '1316357835-593I SAID NO.png', 'Shinmera'),
(88, 'shinmera/MLP', 'Yeah Rite', 'Yeeeeaaaaahriiiiiiiiiiiiiiiiight...', 1316359194, 'Derpy,Hooves', '1316359194-594derpy avatar.png', 'Shinmera'),
(89, 'shinmera/characters/re-boot', 'Wind', 'I like wind.\r\nEspecially soft wind in fall.', 1316359240, 'Cecilia,Wind', '1316359240-595cecilia wind.png', 'Shinmera'),
(90, 'shinmera/MLP', 'Poniessss', 'Some random sketches OHGOG.', 1316359333, 'MLP,Twilight Sprakle,Pinkie Pie,Applejack,Fluttershy,Princess Celestia,Derpy Hooves,Carrot Top', '1316359333-596sketches.png', 'Shinmera'),
(91, 'shinmera/MLP', 'Aliens, I&#039;m telling you!', 'They&#039;re on to us!', 1316360935, 'Derpy Hooves,Carrot Top', '1316360935-597later.png', 'Shinmera'),
(92, 'shinmera/sketches', 'Giggle', 'heee', 1316360967, '', '1316360967-598giggle.png', 'Shinmera'),
(93, 'shinmera/sketches', 'Anatomy', 'Anatomy oh Anatomy, what hath thou donest?', 1316361020, 'Anatomy Practice', '1316361020-599meh.png', 'Shinmera'),
(94, 'shinmera/characters/re-boot', 'Shading Test', 'New shading method, yay!', 1316361091, 'Cecilia,Shading,Test', '1316361091-600cecilia shading test.png', 'Shinmera'),
(95, 'shinmera/sketches', 'Heads', 'The heads are rolling.', 1316361158, 'Heads,Practice,Human', '1316361158-602heads.png', 'Shinmera'),
(96, 'shinmera/sketches', 'Heads 2', 'MORE HEADS!', 1316361208, 'Heads,Practice,Human,Rapeface', '1316361208-604heads.png', 'Shinmera'),
(97, 'shinmera/MLP', 'Twilight Sparkle', 'More shading practice, yay.', 1316361254, 'Twilight Sparkle,Shading', '1316361254-605twilight sparkle new shade.png', 'Shinmera'),
(98, 'shinmera/MLP', 'Love', '&lt;3', 1316556237, 'Derpy Hooves,I,Love,Y&#039;all', '1316556237-love.png', 'Shinmera'),
(99, 'shinmera/transcend', 'Transcend Human Form Logo', 'The logo for the human form on the form selection screen.\r\nThe \\&quot;waves\\&quot; background won&#039;t be in the game, I added that because it just looks better.\r\n\r\nAnd yeah, you play a gal in the game.', 1316965143, 'Human,Transcend,Form,Selection', '1316966583-formhumanlogo.png', 'Shinmera'),
(100, 'shinmera/transcend', 'Transcend Eagle Form Logo', 'Another form.', 1316979578, '', '1316979578-formeaglelogo.png', 'Shinmera'),
(101, 'shinmera/transcend', 'Transcend Pony Form Logo', 'Yay pony form!', 1316979638, '', '1316979638-formponylogo.png', 'Shinmera'),
(102, 'shinmera/transcend', 'Transcend Dolphin Form Logo', 'I like dolphins.', 1316979691, '', '1316979691-formdolphinlogo.png', 'Shinmera'),
(103, 'shinmera/transcend', 'Transcend Mouse Form Logo', 'I can&#039;t draw mice.', 1316979753, '', '1316979753-formmouselogo.png', 'Shinmera'),
(104, 'shinmera/misc', 'Anon', 'lolidunno', 1317325117, 'Anonymous,Anonymette', '1317325117-anon.png', 'Shinmera'),
(105, 'shinmera/characters/re-boot', 'Where Did I Lose Your Love pt 1', 'Where did I lose your love \r\nYou&#039;ll always be the question in my heart \r\nHow could I make you stay \r\nI still regret the night you walked away \r\nWhat we shared was not enough \r\nWhere did I lose your love', 1317330086, 'Journey,Revelation,Cecilia,ReBoot', '1317330086-cecilia_sing.png', 'Shinmera'),
(106, 'shinmera/characters/re-boot', 'Where Did I Lose Your Love pt 2', 'Never saw it coming \r\nYou left so suddenly \r\nWhat was here that scared you \r\nYou want what I couldn&#039;t see \r\nEmotions time cannot erase \r\nI still see your face', 1317330115, 'Journey,Revelation,Cecilia,ReBoot', '1317330115-cecilia_sing_2.png', 'Shinmera'),
(107, 'shinmera/characters/re-boot', 'Where Did I Lose Your Love pt 3', 'Where did I lose your love \r\nYou&#039;ll always be the question in my heart \r\nHow could I make you stay \r\nI still regret the night you walked away \r\nWhat we shared was not enough \r\nWhere did I lose your love', 1317330138, 'Journey,Revelation,Cecilia,ReBoot', '1317330138-cecilia_sing_3.png', 'Shinmera'),
(108, 'shinmera/characters/re-boot', 'Where Did I Lose Your Love pt 4', 'So far from each other \r\nSo close to happiness \r\nI&#039;ll be there to remind you \r\nLove forgives but never forgets \r\nEmotions time cannot erase \r\nI still see your face', 1317330160, 'Journey,Revelation,Cecilia,ReBoot', '1317330160-cecilia_sing_4.png', 'Shinmera'),
(109, 'shinmera/characters/re-boot', 'Where Did I Lose Your Love pt 5', 'Where did I lose your love \r\nYou&#039;ll always be the question in my heart \r\nHow could I make you stay \r\nI still regret the night you walked away', 1317330180, 'Journey,Revelation,Cecilia,ReBoot', '1317330180-cecilia_sing_5.png', 'Shinmera'),
(110, 'shinmera/characters/re-boot', 'Where Did I Lose Your Love pt 6', 'How could I make you stay \r\nWhere did I lose your love \r\nStill regret the night you walked away \r\nOh, oh, yeah~', 1317330205, 'Journey,Revelation,Cecilia,ReBoot', '1317330205-cecilia_sing_6.png', 'Shinmera'),
(112, 'shinmera/3D-CG', 'Cubes', 'Cuuuuuuuuuuubes.', 1317585784, 'This,shit&#039;s,HD,yo', '1317585784-cubes.png', 'Shinmera'),
(113, 'shinmera/characters', 'Malakin and Nick', 'Stubble partners in crime.\r\nAww yeeeeee.', 1317674234, 'WTF,is,that,muffin,doing,there', '1317674234-mala_and_nick.png', 'Shinmera'),
(114, 'shinmera/misc', 'Minty', 'Minty from Stevenchan.', 1318512695, 'Stevenchan,Human,Faggot', '1318512695-minty.png', 'Shinmera'),
(115, 'shinmera/misc', 'Sparkling Limeade', 'Sparkling Limeade (Lime) from Stevenchan', 1318512738, 'Sparkling,Lime,Limeade,Stevenchan,Human,Faggot', '1318512738-lime.png', 'Shinmera'),
(116, 'shinmera/misc', 'Suckyhooves', 'Suckyhooves from Stevenchan.', 1318512777, 'Sucky,Hooves,Stevenchan,Human,Faggot', '1318512777-sucky.png', 'Shinmera'),
(117, 'shinmera/characters/re-boot', 'Experiments', 'Tried out some stuff.\r\nNot sure about the result...\r\nIt&#039;s more on the negative side though.', 1318544954, 'Cecilia', '1318544954-experiments.png', 'Shinmera'),
(118, 'shinmera/characters', 'Huh?', 'I really like this for some reason.\r\nAlso, yes I do realize all of my busts tend to have too long necks.\r\nI have to admit that I just like it better like this though.', 1318548002, 'Cecilia', '1318548002-huh.png', 'Shinmera'),
(119, 'shinmera/MLP', 'Like A Boss', 'Pony in a suit,\r\nLIKE A BAWSSSS!\r\n\r\nSigning contracts,\r\nLIKE A BAWSSSS!\r\n\r\nWearing glasses upside down,\r\nLIKE A BAWSSSS!', 1318625863, 'Derpy,Hooves,Like,A,Bawss', '1318625863-like_a_boss.png', 'Shinmera'),
(120, 'shinmera/misc', 'BLUH HORSE', 'DRAWING HORSES\r\nHOW DO', 1318669597, 'lolidunnno,bluh,mspa', '1318669597-BLUH_HORSE.png', 'Shinmera'),
(121, 'shinmera/misc', 'I Don&#039;t Fucking Know', 'This was a request by LostThyme on dA, a semi-realistic pony.\r\nI really don&#039;t know.', 1318671499, 'Horse,Bluh,What', '1318671499-i_dont_fucking_know.png', 'Shinmera'),
(122, 'shinmera/MLP', 'Dinner For A Gentlemagnet', '[i]Fabulous.[/i]', 1319018379, 'Fabulous,Dinner,Steven,Magnet,Stevenchan,Tophat,Tux,Classy', '1319018379-steven_dinner.png', 'Shinmera'),
(123, 'shinmera/misc', 'Cony', 'For Cony The Pony from Stevenchan.', 1319121363, 'Stevenchan,Human,Faggot', '1319121363-cony.png', 'Shinmera'),
(124, 'shinmera/misc', 'Haackula', 'For Haackula from Stevenchan.', 1319121395, 'Stevenchan,Human,Faggot', '1319121395-haackula.png', 'Shinmera'),
(125, 'shinmera/sketches', 'EVERYTHING IS COMPLETELY FINE', 'I PROMISE!\r\nEHEHEHEHE....', 1319149604, 'Thumbs,Up,Oh,God,Someone,Please,Help,Me', '1319149604-EVERYTHING_IS_COMPLETELY_FINE.png', 'Shinmera'),
(126, 'shinmera/misc', 'Sky', 'Transcend menu screen background.\r\nI like it.', 1319398252, 'Transcend,Clouds,Earth', '1319398252-menu.png', 'Shinmera'),
(128, 'shinmera/3D-CG', 'Crystal', 'Yeah I&#039;ve done this before, but it&#039;s a nice render setup and I wanted to test the performance of this new machine.\r\nIt&#039;s nice.', 1319896848, 'Myst;Revelation;Spire;Device', '1319896848-huge_crystal_Kopie.png', 'Shinmera'),
(130, 'shinmera/misc', 'Gentle Harvester', 'I just love Suisei Seki&#039;s design.\r\nI&#039;m not sure about this pic though.\r\nI like some parts of it very much, others not so.\r\nEh.\r\nGood enough I suppose.\r\n\r\nDesu.', 1320001227, 'Desu,Desu,Desu,Desu,Desu,Desu,Desu,Desu,Desu,Desu,Desu,Desu,Suisei,Seki,Rozen,Maiden,Desu', '1320001227-suisei_seki_shaded.png', 'Shinmera'),
(131, 'shinmera/misc', 'BroWny', 'For BroWny from Stevenchan.', 1320006386, 'Stevenchan,Human,Faggot', '1320006386-browny.png', 'Shinmera'),
(132, 'shinmera/misc', 'Suisei Seki Garden Colored', 'Shading and moar has yet to be done.\r\nIt&#039;s too late to finish it at the moment, so I&#039;ll continue this later.\r\nDesu.', 1320364456, 'Suisei,Seki,Garden,Rozen,Maiden,Preview', '1320364456-suisei_seki_garden_sketch.png', 'Shinmera'),
(133, 'shinmera/misc', 'Suisei Seki Garden', 'All these fancy effects!\r\nOh my!\r\nDesu~', 1320538700, 'Suisei,Seki,Garden,Rozen,Maiden,So,Many,Effects,Desu', '1320538700-suisei_seki_garden.png', 'Shinmera'),
(134, 'shinmera/misc', 'Ashy Boy', 'For Ash from Stevenchan.', 1320586700, 'Stevenchan,Human,Faggot', '1320586700-ashy_boy.png', 'Shinmera'),
(135, 'shinmera/sketches', 'Oh gog shit deadlines!', 'I&#039;m loving MyPaint so far though.', 1322613881, 'Ffffuuu', '1322613881-stress.png', 'Shinmera'),
(136, 'shinmera/sketches', 'YAY DONE', 'Yaaaayyyy!\r\nI still love MyPaint.', 1322620776, 'Sure,Smells,Like,Generic,Animu,Expression,In,Here', '1322620776-yay_done.png', 'Shinmera'),
(137, 'shinmera/sketches', 'Wtf is that', 'I srsly need to get better.', 1323104074, 'Suisei,Seki', '1323104074-wtfisthat_sketch.jpg', 'Shinmera'),
(138, 'shinmera/MLP', 'OOh', 'How long has it been since my last derpy drawing?\r\nQuite long.\r\nOh well.', 1324255189, 'Derpy,Hooves,Painted', '1324255189-oooh.png', 'Shinmera'),
(139, 'shinmera/sketches', 'I wonder', 'This one turned out rather good actually, but scans always look terrible.\r\nDrawn while getting my hair done.', 1324500132, 'Sketchy,I,Hate,Scanners', '1324500132-scan.jpg', 'Shinmera'),
(140, 'shinmera/characters/re-boot', 'Syna', 'I really really like this drawing.', 1325025345, 'Syna,Re-Boot,Awesome', '1325025345-208.png', 'Shinmera'),
(141, 'shinmera/characters/re-boot', 'Your System Has Stopped Caring A', 'Sending SIGTERM..... [OK]\r\nSending SIGKILL..... [OK]\r\nSystem is going down for halt NOW!', 1325884345, 'Syna,Re-Boot,Header', '1325884345-header.png', 'Shinmera'),
(142, 'shinmera/MLP', 'Huh', 'Tried a lineless style for once.\r\nLooks kinda cool I guess.', 1326131243, 'Derpy,Hooves', '1326131857-huh.png', 'Shinmera'),
(143, 'shinmera/characters/re-boot', 'Humanized', 'Cecilia, Lockpick and Syna humanized in my newly discovered style.\r\nLooks pretty damn neat I have to say!', 1327013779, 'Cecilia,Lockpick,Syna,ReBoot,Re-Boot,Human', '1327013779-humanized.png', 'Shinmera'),
(145, 'shinmera/sketches', 'smile', 'Idunnolol', 1327528818, 'Suisei,Seki,Desu', '1327528818-desuuu.png', 'Shinmera'),
(146, 'shinmera/sketches', 'Mh', 'Mh.', 1327532183, 'Meh,Cecilia', '1327532183-mh.png', 'Shinmera'),
(147, 'shinmera/sketches', 'Human Derpy Practice', 'And once again it&#039;s time to practice: Female faces without female stereotypes.\r\nWhich is one of the hardest things to practice period.\r\nAnyway.\r\nEnjoy.', 1327709108, 'Derpy,Hooves,Human', '1327709108-human_derpy_practice.png', 'Shinmera'),
(148, 'shinmera/characters', 'Shinmera &amp; Mithent Lost In The City', 'For Mithent.', 1327880989, 'Shinmera,Mithent,Twilight,Sparkle,Derpy,Hooves,Humanised', '1327880989-Twi_Derps.png', 'Shinmera'),
(149, 'shinmera/characters', 'Shading Experiments', 'I outlined, coloured and shaded the sketch I uploaded sometime back.\r\nYeah, just tried out different stuff with shading mostly.\r\nCan&#039;t really say much about how it turned out... I don&#039;t know myself.', 1328359762, 'Cecilia,Shading,Test', '1328359762-mh.png', 'Shinmera'),
(150, 'shinmera/sketches', 'Expressionssssss', 'Oh boy.', 1328369816, 'Derpy, Humanized, Lockpick, Re-Boot, Syna', '1328369816-derpy_expression_sketches.png', 'Shinmera'),
(151, 'shinmera/MLP', 'Hug', 'For my love.', 1328403229, 'Twilight, Derpy, Humanized, Hug, Mithent, Shinmera', '1328403229-mith_shin_hug.png', 'Shinmera'),
(152, 'shinmera/sketches', 'Anatomy Practice', 'Yup.', 1328657174, 'Derpy, Humanized, Anatomy', '1328657174-anatomy_practice_3.png', 'Shinmera'),
(153, 'shinmera/sketches', 'Jumpy Yay', 'Mmmmmmmmmmmmmmmmmmmmmmmmmmoar anatomy!', 1328735987, 'Derpy, Humanized, Anatomy', '1328735987-jumpy_yay.png', 'Shinmera'),
(154, 'shinmera/sketches', 'Miiiithyyyyyyy', 'Maaaaaaan I&#039;m so booooreeeeed!', 1328887104, 'Shinmera, Derpy, Humanized', '1328887104-no_construction.png', 'Shinmera'),
(155, 'shinmera/sketches', 'Chair', 'Chair.', 1329188429, 'Derpy, Humanized', '1329188429-well_uuuh.png', 'Shinmera'),
(156, 'shinmera/sketches', 'Watching Movies', 'Aww.', 1329188475, 'Derpy, Twilight, Humanized, Mithent, Shinmera', '1329188475-watching_movies_together.png', 'Shinmera'),
(157, 'shinmera/characters', 'Valentine&#039;s 2012', 'For Mithent, my love.', 1329230290, 'Mithent, Shinmera, Valentine', '1329269039-valentine.png', 'Shinmera'),
(158, 'shinmera/sketches', 'Foreverlasting', 'Derp, I no good painting.', 1329349235, 'Shit', '1329349235-foreverlasting.png', 'Shinmera'),
(159, 'shinmera/MLP', 'Gingy', 'Forgot to upload this.\r\nIt was to wish gingy a speedy recovery from his illness and operation.', 1329442637, 'Gingerale, Derpy', '1329442637-for_gingy.png', 'Shinmera'),
(160, 'shinmera/characters', 'Oh Hey', 'I originally wanted to make this more kinky, but whatever.', 1330128189, 'Mithent, Shinmera', '1330128318-oh_hey.png', 'Shinmera'),
(161, 'shinmera/characters/re-boot', 'Sandra Concept Art 1', 'Oh my, who could this be???', 1330874576, 'Sandra,ReBoot,Concept Art', '1330874576-sandra-concept-1.png', 'Shinmera'),
(162, 'shinmera/characters/re-boot', 'Marcus Concept Art 1', 'And who&#039;s this?', 1330880700, 'Marcus,ReBoot,Concept Art', '1330880700-marcus-concept-1.png', 'Shinmera'),
(163, 'shinmera/sketches', 'Experimental', 'I don&#039;t know, I just drew something.\r\nI might even like the [url=http://shinmera.tymoon.eu/public/experimental%20idunno.png]unshaded sketch[/url] better...', 1330901535, 'Experiment', '1330901591-experimental_idunno.png', 'Shinmera'),
(164, 'shinmera/characters', 'Good Night, Sweetheart', '(^3^) ~â™¥', 1330904820, 'Shinmera,Mithent,Fabulous', '1330904820-good_night_sweetheart.png', 'Shinmera'),
(165, 'shinmera/characters', 'So Alone', ':C', 1330993392, 'Shinmera,Mithent,So,Alone', '1330993392-so_alone.png', 'Shinmera'),
(166, 'shinmera/sketches', 'Cute Or Something', 'For Mala.', 1331071340, 'Idk,Cute,I,Guess', '1331071340-idk_really.png', 'Shinmera'),
(167, 'shinmera/MLP', 'Quick Derpy Drawing', 'About 2 minutes.\r\nI like it.', 1331232959, 'Derpy,Hooves', '1331232959-quick_derpy_draw.png', 'Shinmera'),
(168, 'shinmera/sketches', 'HAACHK', 'OH GOD, AM I GONNA BE ALRIGHT??\r\n\r\nDrew this jff and very quickly.\r\nTurned out surprisingly well, considering the lack of... well everything I usually do when trying to construct anything remotely anatomically accurate.', 1331233849, 'Shinmera,Oh,God,What', '1331233849-haachk.png', 'Shinmera'),
(169, 'shinmera/characters', 'Oh My', '~~~', 1331239161, 'Shinmera,Mithent,Oh,My,Hug', '1331239161-oh_my.png', 'Shinmera'),
(170, 'shinmera/characters', 'Idunnolol', 'I&#039;m just trying shit out...', 1331327616, '', '1331327616-av.png', 'Shinmera'),
(171, 'shinmera/characters', 'Hmm', 'I still dunno.', 1331329138, '', '1331329138-av2.png', 'Shinmera'),
(172, 'shinmera/characters', 'Derpy Hair Restyled', 'So I wondered, what does Derpy&#039;s hair look like if it couldn&#039;t defy physics?', 1331513030, 'Derpy', '1331513030-derpy_hair_restyled.png', 'Shinmera'),
(173, 'shinmera/MLP', 'Mocha Delight', 'For !Troll on Stevenchan.', 1332036665, 'Mocha,Delight', '1332036665-mocha_for_trolley_boy_or_something_id.png', 'Shinmera'),
(174, 'shinmera/characters', 'Mona Blush', 'Meet Mona.\r\n\r\nHi Mona.', 1332036791, 'Mona,Monika,Monika Jade Kalan,Hahaha,Uuuuh,...,Well,Bye,Again,I,Guess', '1332036791-Mona_blush.png', 'Shinmera'),
(175, 'shinmera/transcend', 'Transcend Artwork', 'Yay, artwork!\r\n\r\nEdit:\r\nDoesn&#039;t really look that good on a white background, haha.', 1332108428, 'Transcend, Game, Development, Design', '1332108428-artwork_01.png', 'Shinmera'),
(176, 'shinmera/characters', 'A New Land', '&quot;Come with me, Mithent and we shall create a land of pure fabulosity!&quot;', 1332282295, 'Fabulous, Mithent, Shinmera', '1332282295-a_new_land.png', 'Shinmera'),
(177, 'shinmera/characters', 'Anna', 'Well hello there!\r\n\r\nThis character belongs to @Mithent .', 1332375824, 'Mithent, Anna, Persona', '1332375824-Anna.png', 'Shinmera'),
(178, 'shinmera/characters', 'Strip', 'For Cony from Stevenchan', 1332546878, 'Mona,Monika,Strip,Ahahaha,Oh,Wow,Cony,Comic', '1332546878-strip.png', 'Shinmera'),
(179, 'shinmera/misc', 'Smile', '&quot;My mom once told me that I should smile when I&#039;m sad and everything will be alright.&quot;', 1332701919, 'Smile,Sad,Cry,Experimental', '1332701919-smile_.png', 'Shinmera'),
(180, 'shinmera/sketches', 'Mona', 'A copy from my journal.\r\nI quite like how this turned out.\r\nSurprisingly good actually.', 1332803069, 'Ooooh,Journal,Entries,What&#039;cha,Writing,Nick,?', '1332803069-out.jpg', 'Shinmera'),
(181, 'shinmera/misc', 'Nara', 'Nara for Malakin.\r\n\r\nHere ya go, you faggot.\r\nI want a fully fleshed out character asap.', 1332896133, 'Nara,Malakin,Character,Whatever,Man,This,Looks,Pretty,Good', '1332896133-Nara.png', 'Shinmera'),
(182, 'shinmera/sketches', 'Thinking About University Degrees', 'I get all scared about this.', 1333059352, 'Oh,Gog,What,Do,I,Dooooooo', '1333059436-degrees.png', 'Shinmera'),
(183, 'shinmera/characters', 'Mona Avatar', 'Just a quick avatar I drew for some reason.\r\nDoesn&#039;t actually look too good on small size, so I might make a better one soon.', 1333405052, 'Mona,Monika,Monika Jade Kalan,Avatar', '1333405052-mona_avatar.png', 'Shinmera'),
(184, 'shinmera/misc', 'Sketch For Anon', 'Eeeeh.\r\nI really really really need to do the following two things:\r\n1) Get back to actually practicing instead of just drawing.\r\n2) Get back to traditional art.', 1335359981, 'Practice,Anon,Eh', '1335359981-sketch_for_anon_eh.jpg', 'Shinmera'),
(185, 'shinmera/misc', 'Khanzer', 'For Khanzer on EDF.', 1336214434, 'Khanzer, EDF, Encyclopedia, Dramatica, Forum', '1336214434-khanzer.png', 'Shinmera'),
(186, 'shinmera/characters', 'MonAnna Avatar', 'Yaaay.', 1337017417, 'Mona, Monika, Jade, Kalan, Anna, Shinmera, Mithent', '1337017417-avatar_monanna.png', 'Shinmera'),
(187, 'shinmera/sketches', 'Practicceee', 'Meh?\r\nMeh.\r\nMMMh.\r\nMeeehhhmm...\r\n\r\nHm.\r\n\r\nMeh.', 1337988044, 'Anna, Mona, Kalan', '1337988044-practiccce.png', 'Shinmera'),
(188, 'shinmera/sketches', 'Best Drawing I&#039;ve Made in a While', 'It&#039;s a realistic portrayal of the hardships an average man of the modern society has to obey and of all the social standards that oppress him and derange his character into something very unlike his true nature.', 1339452641, 'Modern, Art', '1339452641-best_drawing_Ive_made_in_a_while.png', 'Shinmera'),
(189, 'shinmera/misc', 'Mala and Shin', 'It&#039;s us!', 1339456173, 'Malakin,Seagoon,Kieran,Nick,Shinmera', '1339456173-mala_and_shin.png', 'Shinmera'),
(190, 'shinmera/sketches', 'Sketchy', 'Idunno.', 1339456379, 'Idunno', '1339456379-sketchy.png', 'Shinmera'),
(191, 'shinmera/sketches', 'Reading', 'The challenge here was to have fun drawing it and not to use colours.\r\nI should try to simplify it some more so that it doesn&#039;t look too crowded.\r\nHm.', 1342388488, 'Suiseiseki,Desu,Black,White,Sketch', '1342388488-reading.png', 'Shinmera'),
(192, 'shinmera/sketches', 'I Did It!', 'I finally got back into drawing!', 1342482306, 'Suiseiseki,Desu,Black,White,Sketch', '1342482306-I..._I_did_it.png', 'Shinmera'),
(193, 'shinmera/sketches', 'Sketchadoodle doo', 'My pessimism drives me to describe this as a piece of shit, but I&#039;m trying my best to ignore that.', 1342779180, 'Digital,Pencil,Realism,Portrait', '1342779180-sketch_sketchadoodle_doo.png', 'Shinmera'),
(194, 'shinmera/misc', 'Rain', 'Worthless and alone.\r\nThat&#039;s how I feel right now.', 1343169116, '', '1343169116-rain.png', 'Shinmera'),
(199, 'shinmera', 'aa', 'aaaa', 1351715407, '', '199-IMG_20121031_082730..jpeg', 'Shinmera');

-- --------------------------------------------------------

--
-- Table structure for table `fenfire_comments`
--

CREATE TABLE IF NOT EXISTS `fenfire_comments` (
  `commentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `FID` int(11) unsigned NOT NULL,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `level` tinyint(4) unsigned NOT NULL,
  `moderation` tinyint(1) NOT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Dumping data for table `fenfire_comments`
--

INSERT IGNORE INTO `fenfire_comments` (`commentID`, `FID`, `username`, `mail`, `text`, `time`, `level`, `moderation`) VALUES
(15, 90, 'Someone Else', 'nigger', 'LOL UR GHEY FOR MITHENT', 1333924316, 0, 0),
(16, 90, 'Shinmera', 'nhafner@gmx.ch', 'IKNORITE', 1333924490, 1, 0),
(17, 90, 'Shinmera', 'nhafner@gmx.ch', 'TEST', 1334818953, 0, 0),
(18, 90, 'Shinmera', 'nhafner@gmx.ch', 'ANOTHER TEST', 1334818969, 0, 0),
(19, 91, 'Shinmera', 'nhafner@gmx.ch', 'TESTOR', 1334820601, 0, 0),
(20, 91, 'Shinmera', 'nhafner@gmx.ch', 'TEST2', 1334820811, 0, 0),
(21, 91, 'Shinmera', 'nhafner@gmx.ch', 'AAAAAAA', 1334823218, 1, 0),
(22, 91, 'Shinmera', 'nhafner@gmx.ch', 'WHOOO', 1334823785, 2, 0),
(23, 91, 'Shinmera', 'nhafner@gmx.ch', 'MITHEEENT', 1334824258, 0, 0),
(24, 94, 'Shinmera', 'nhafner@gmx.ch', 'LOL PENIZ', 1341442309, 0, 0),
(25, 105, 'Shinmera', 'nhafner@gmx.ch', 'BLARGH', 1351882894, 0, 0),
(26, 105, 'Huehuehue', 'aaa@cyz.ch', 'lol whatever man, you&apos;re just stupid anyway.', 1351892894, 1, 0),
(27, 105, 'Shinmera', 'nhafner@gmx.ch', 'Hah waaaaw.', 1351894113, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fenfire_folders`
--

CREATE TABLE IF NOT EXISTS `fenfire_folders` (
  `folderID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `order` text COLLATE utf8_unicode_ci,
  `open` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`folderID`),
  UNIQUE KEY `module` (`module`,`path`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=147 ;

--
-- Dumping data for table `fenfire_folders`
--

INSERT IGNORE INTO `fenfire_folders` (`folderID`, `module`, `path`, `order`, `open`) VALUES
(89, 'CORE', 'INDEX', ';0;9;1;0;7;10;8;2;3;4;5;6;11;12;13;14', 1),
(90, 'Neon', 'Shinmera', ';15;16;17;18', 1),
(91, 'User', 'Shinmera', ';19;20;21;22;23', 1),
(92, 'User', 'Faggot', NULL, 1),
(93, 'User', 'McDick', NULL, 1),
(94, 'Reader', 'p/57', ';24', 1),
(95, 'Reader', 'f', NULL, 1),
(96, 'Reader', 'p/57-This is a test  blog entry', NULL, 1),
(97, 'Reader', '57', NULL, 1),
(98, 'Reader', 'p/58', NULL, 1),
(99, 'Reader', 'p/56', NULL, 1),
(100, 'Reader', 'p/55', NULL, 1),
(101, 'Reader', 'p/54', NULL, 1),
(102, 'Reader', 'p/53', NULL, 1),
(103, 'Reader', 'p/52', NULL, 1),
(104, 'Reader', 'p/51', NULL, 1),
(105, 'Reader', 'p/59', ';25;26;27', 1),
(106, 'User', 'loldongs', NULL, 1),
(107, 'User', 'nonPrivileged', NULL, 1),
(108, 'Reader', 'p/50', NULL, 1),
(109, 'Reader', 'p/49', NULL, 1),
(110, 'Reader', 'p/48', NULL, 1),
(111, 'Reader', 'p/47', NULL, 1),
(112, 'Reader', 'p/46', NULL, 1),
(113, 'Reader', 'p/45', NULL, 1),
(114, 'Reader', 'p/44', NULL, 1),
(115, 'Reader', 'p/43', NULL, 1),
(116, 'Reader', 'p/42', NULL, 1),
(117, 'Reader', 'p/41', NULL, 1),
(118, 'Reader', 'p/40', NULL, 1),
(119, 'Reader', 'p/39', NULL, 1),
(120, 'Reader', 'p/38', NULL, 1),
(121, 'Reader', 'p/37', NULL, 1),
(122, 'Reader', 'p/36', NULL, 1),
(123, 'Reader', 'p/35', NULL, 1),
(124, 'Reader', 'p/34', NULL, 1),
(125, 'Reader', 'p/33', NULL, 1),
(126, 'Reader', 'p/32', NULL, 1),
(127, 'Reader', 'p/31', NULL, 1),
(128, 'Reader', 'p/30', NULL, 1),
(129, 'Reader', 'p/29', NULL, 1),
(130, 'Reader', 'p/28', NULL, 1),
(131, 'Reader', 'p/27', NULL, 1),
(132, 'Reader', 'p/26', NULL, 1),
(133, 'Reader', 'p/25', NULL, 1),
(134, 'Reader', 'p/24', NULL, 1),
(135, 'Reader', 'p/23', NULL, 1),
(136, 'Reader', 'p/22', NULL, 1),
(137, 'Reader', 'p/21', NULL, 1),
(138, 'Reader', 'p/20', NULL, 1),
(139, 'Reader', 'p/17', NULL, 1),
(140, 'Reader', 'p/16', NULL, 1),
(141, 'Reader', 'p/15', NULL, 1),
(142, 'Reader', 'p/13', NULL, 1),
(143, 'Reader', 'p/12', NULL, 1),
(144, 'Reader', 'p/10', NULL, 1),
(145, 'Reader', 'p/9', NULL, 1),
(146, 'Reader', 'p/7', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fi_files`
--

CREATE TABLE IF NOT EXISTS `fi_files` (
  `fileID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `owner` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`fileID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lightup_suites`
--

CREATE TABLE IF NOT EXISTS `lightup_suites` (
  `name` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `module` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lightup_suites`
--

INSERT IGNORE INTO `lightup_suites` (`name`, `module`) VALUES
('default', 'CORE'),
('extra', 'CORE'),
('plus', 'CORE'),
('pro', 'CORE');

-- --------------------------------------------------------

--
-- Table structure for table `lightup_tags`
--

CREATE TABLE IF NOT EXISTS `lightup_tags` (
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `suite` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
  `tag` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `tagcode` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `deftag` text COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `limit` int(11) NOT NULL DEFAULT '-1',
  `order` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lightup_tags`
--

INSERT IGNORE INTO `lightup_tags` (`name`, `suite`, `tag`, `tagcode`, `deftag`, `description`, `limit`, `order`) VALUES
('Bold', 'default', 'b', 'b{@}', 'deftag(b){\r\n    tag(strong){print{content}}\r\n}', 'Bold text', -1, 0),
('Center', 'default', 'center', 'center{@}', 'deftag(center){\r\n  tag(center){print{content}}\r\n}', 'Center stuff', -1, NULL),
('Code', 'extra', 'code', 'code($STRI$){@}', 'deftag(code,lang STRI FALSE){\r\n  tag(code){\r\n    div(:class codehead){echo{Code:}print{lang}}\r\n    tag(pre){print{content}}}\r\n}', 'Markup tag for code.', -1, 0),
('Color', 'plus', 'color', 'color(&#36;Choose a colour|color&#36;){@}', 'deftag(color,color STRI true){\r\n    tag(span,:style color: get{color}){ print{content}}\r\n}', 'Change the font colour', -1, 4),
('Floater', 'default', 'float', 'float(right){@}', 'deftag(float,dir STRI true){ \r\n  div(:style display:inline-block;margin:5px;float:get{dir}){print{content}}\r\n}', 'Float', -1, NULL),
('Image', 'plus', 'img', 'img{@}', 'deftag(img,alt TEXT false,title TEXT false,maxwidth INTE100 false 100){\r\n    tag(img,:style max-width:get{maxwidth}%,:extra alt="get{alt}" title="get{title}" src="get{content}"){}\r\n}', 'Insert an image', -1, 5),
('Imgbox', 'plus', 'imgbox', 'imgbox(right){@}', 'deftag(imgbox,float STRI true,caption TEXT false,maxwidth INTE100 false 50,alt TEXT false,title TEXT false){\r\n  div(:style float:get{float};max-width:get{maxwidth}%;position:relative;margin:5px;clear:both){\r\n    tag(img,:extra alt="get{alt}" title="get{title}" src="get{content}"){}\r\n    tag(br){}\r\n    tag(em){print{caption}}\r\n  }\r\n}', 'Image Box', -1, NULL),
('Italic', 'default', 'i', 'i{@}', 'deftag(i){\r\n    tag(em){print{content}}\r\n}', 'Italic text', -1, 1),
('Quote', 'extra', 'quote', 'quote(&#36;user|string&#36;,&#36;urls|url&#36;){@}', 'deftag(quote,user STRI false,url URLS false){\r\n div(quote){\r\n   tag(h5){\r\n    if(get{user},false,!=){echo{By: }url(http://user.linuz.com/Luminate/get{user}){print{user}}}\r\n    if(get{url},false,!=){url(get{url}){echo{↗}}}\r\n   }\r\n   tag(p){print{content}}\r\n  }\r\n}', 'Quote a post', -1, 11),
('Rainbow', 'default', 'rainbow', 'rainbow{@}', 'deftag(rainbow){\r\n   set(c,0){''#FF0000''}\r\n   set(c,1){''#FF7F00''}\r\n   set(c,2){''#FFFF00''}\r\n   set(c,3){''#00FF00''}\r\n   set(c,4){''#0000FF''}\r\n   tag(div,:style padding-top:40px;){\r\n    each(c){\r\n     tag(div,:style color: get{item};margin-top:-40px;font-size:40pt;text-align:center;){\r\n       print{content}\r\n     }\r\n    }\r\n   }\r\n}', 'Rainbow Shit', -1, 0),
('Size', 'plus', 'size', 'size(&#36;Enter the font size|number&#36;){@}', 'deftag(size,size INTE46 false 12){\r\n   tag(span,:style font-size: get{size} pt;){print{content}}\r\n}', 'Change the font size', -1, 8),
('Spoiler', 'plus', 'spoiler', 'spoiler{@}', 'deftag(spoiler,open TEXT false Open,close TEXT false Close){\r\n  set(*spoilers){math(get{*spoilers},1){add}}\r\n  tag(div,:class spoiler,:extra id="get{*spoilers}"){\r\n    tag(button){print{open}}\r\n    div(:style display:none){print{content}}\r\n    tag(span,:class open,:style display:none){print{open}}\r\n    tag(span,:class close,:style display:none){print{close}}\r\n  }\r\n}', 'Spoiler text', -1, 0),
('Url', 'plus', 'url', 'url(&#36;Enter the URL|url&#36;){@}', 'deftag(url,url URLS false NIL,title TEXT false,target TEXT false _blank){\r\n    if(get{url},''NIL'',==){set(url){get{content}}}\r\n    tag(a,:extra href="get{url}" title="get{title}" target="get{target}"){print{content}}\r\n}', 'Insert a hyperlink', -1, 9),
('Youtube', 'extra', 'youtube', 'youtube{video code}', 'deftag(youtube,width INTE800 false 400,height INTE600 false 280,start INTE false,end INTE false,list TEXT false,listType STRI false list,loop BOOL false 0,autoplay BOOL false 0){\r\n  urlargs{get{content}}\r\n  set(id){get(v){params}}\r\n  if(get{loop},true){set(loop){1}}\r\n  if(get{autoplay},true){set(autoplay){1}}\r\n  tag(iframe,:force 1,:class youtube-player,:extra type="text/html" width="get{width}" height="get{height}" frameborder="0" src="http://www.youtube.com/embed/get{id}?start=get{start}&end=get{end}&list=get{list}&listType=get{listType}&loop=get{loop}&autoplay=get{autoplay}"){echo{#}}\r\n}', 'Insert a youtube video', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lore_actions`
--

CREATE TABLE IF NOT EXISTS `lore_actions` (
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `args` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `editor` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lore_actions`
--

INSERT IGNORE INTO `lore_actions` (`title`, `type`, `action`, `args`, `reason`, `editor`, `time`) VALUES
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
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'o',
  `revision` int(11) NOT NULL,
  `current` text COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'o',
  PRIMARY KEY (`title`,`type`),
  FULLTEXT KEY `current` (`current`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lore_articles`
--

INSERT IGNORE INTO `lore_articles` (`title`, `type`, `revision`, `current`, `time`, `status`) VALUES
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
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `article` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`title`,`article`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lore_categories`
--

INSERT IGNORE INTO `lore_categories` (`title`, `article`) VALUES
('cat', 'Index'),
('cat', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `lore_revisions`
--

CREATE TABLE IF NOT EXISTS `lore_revisions` (
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `revision` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `editor` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`title`,`type`,`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `lore_revisions`
--

INSERT IGNORE INTO `lore_revisions` (`title`, `type`, `revision`, `text`, `editor`, `time`) VALUES
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
  `title` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `subject` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ms_hooks`
--

CREATE TABLE IF NOT EXISTS `ms_hooks` (
  `source` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `hook` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `function` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`source`,`hook`,`destination`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ms_hooks`
--

INSERT IGNORE INTO `ms_hooks` (`source`, `hook`, `destination`, `function`) VALUES
('CORE', 'HITDOMAIN', 'CORE', 'printTimePassed'),
('CORE', 'HITadmin', 'Admin', 'displayPage'),
('CORE', 'HITlogin', 'Neon', 'displayLogin'),
('Admin', 'PANELdisplay', 'User', 'displayPanel'),
('Admin', 'ADMINUser', 'User', 'displayAdminPage'),
('Admin', 'PANELdisplay', 'Themes', 'displayPanel'),
('Admin', 'ADMINThemes', 'Themes', 'displayAdminPage'),
('CORE', 'HITapi', 'CORE', 'apiCall'),
('CORE', 'APIThemes', 'Themes', 'displayAPI'),
('Admin', 'ADMINMenu', 'Menu', 'displayAdmin'),
('CORE', 'HITuser', 'Neon', 'displayMainPage'),
('Themes', 'buildMenu', 'Menu', 'buildMenu'),
('Admin', 'ADMINNavbar', 'User', 'adminNavbar'),
('Admin', 'ADMINNavbar', 'Themes', 'adminNavbar'),
('Admin', 'PANELdisplay', 'Menu', 'displayPanel'),
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
('CORE', 'HITgallery', 'Display', 'displayPage'),
('CORE', 'APIdisplayManageSave', 'Display', 'displayAPISaveData'),
('CORE', 'APIdisplayManageDelete', 'Display', 'displayAPIDeletePicture'),
('Admin', 'PANELdisplay', 'Purplish', 'displayAdminPanel'),
('Admin', 'ADMINChan', 'Purplish', 'displayAdmin'),
('CORE', 'APIchan', 'Purplish', 'displayAPI'),
('CORE', 'HITchan', 'Purplish', 'displayPage'),
('Admin', 'PANELdisplay', 'Reader', 'displayPanel'),
('CORE', 'HITblog', 'Reader', 'displayPage'),
('Reader', 'entryFoot', 'Fenfire', 'commentSection'),
('Admin', 'ADMINReader', 'Reader', 'displayAdmin'),
('CORE', 'APIfiler', 'Filer', 'displayAPI'),
('Reader', 'editor', 'Filer', 'displayPopupFiler'),
('Admin', 'PANELdisplay', 'Filer', 'displayPanel'),
('Admin', 'ADMINFiler', 'Filer', 'displayAdmin'),
('Admin', 'PANELdisplay', 'Layouter', 'displayPanel'),
('Admin', 'ADMINLayouter', 'Layouter', 'displayAdmin'),
('Admin', 'PANELdisplay', 'Display', 'displayPanel'),
('Admin', 'ADMINDisplay', 'Display', 'displayAdmin'),
('Themes', 'buildMenu', 'Neon', 'buildMenu'),
('Themes', 'buildMenu', 'Admin', 'buildMenu'),
('CORE', 'HIToffline', 'CORE', 'offline'),
('Neon', 'profileSettings', 'Twitch', 'displayUserPanel'),
('Neon', 'TwitterSave', 'Twitch', 'displayUserSave'),
('CORE', 'APItwitter', 'Twitch', 'apiTwitterReturn'),
('Display', 'POST', 'Twitch', 'universalPostHook'),
('Reader', 'POST', 'Twitch', 'universalPostHook'),
('CORE', 'HIThub', 'Hub', 'displayPage');

-- --------------------------------------------------------

--
-- Table structure for table `ms_links`
--

CREATE TABLE IF NOT EXISTS `ms_links` (
  `linkID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PID` int(10) unsigned NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `style` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `auth` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`linkID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `ms_links`
--

INSERT IGNORE INTO `ms_links` (`linkID`, `PID`, `order`, `title`, `link`, `description`, `style`, `auth`) VALUES
(2, 0, 15, 'Wiki', 'http://wiki.HOST', '', '', ''),
(3, 0, 0, 'Blog', 'http://blog.HOST', 'News and Articles', '', ''),
(4, 0, 5, 'Gallery', 'http://gallery.HOST', '', '', ''),
(5, 4, 0, 'Upload', 'http://gallery.HOSTupload', 'Upload New Pictures', '', 'display.folder.*'),
(6, 4, 1, 'Manage', 'http://gallery.HOSTmanage', 'Manage Your Submissions', '', 'display.folder.*'),
(7, 3, 0, 'Folders', 'http://blog.HOSTf', 'Show Article Folders', '', ''),
(8, 3, 1, 'Submit', 'http://blog.HOSTe', 'Submit a New Entry', '', 'reader.folder.*'),
(9, 0, 20, 'Chan', 'http://chan.HOST', 'Stevenchan', '', ''),
(10, 0, 25, 'Links', '#', '', '', ''),
(11, 10, 1, 'Imagedump', 'http://img.tymoon.eu', 'A large collection of images from the internet', '', ''),
(12, 10, 4, 'Argh', 'http://argh.tymoon.eu', 'Argh.', '', ''),
(13, 10, 2, 'StevenArch', 'http://stevenarch.tymoon.eu', 'Stevenchan 1 Archive', '', ''),
(14, 10, 5, 'Kick', 'http://scr.tymoon.eu/kick', 'Kick Shinmera', '', ''),
(15, 10, 3, 'Movie Night', 'http://tymoon.eu/MovieNight', 'Stevenchan Movie Night', '', ''),
(16, 10, 6, 'Did You Know', 'http://shinmera.tymoon.eu/public/nt', 'Did you know... about Windows NT', '', ''),
(17, 10, 0, 'Shinmera', 'http://shinmera.com', 'Shinmera&apos;s personal website', '', ''),
(18, 0, 10, 'Projects', 'http://hub.HOST', 'Project HUB and Bug Tracker', '', ''),
(19, 18, 5, 'Submit Ticket', 'http://hub.HOSTticket/submit', 'Submit a Bug Ticket  or Request', '', ''),
(20, 18, 0, 'Project List', 'http://hub.HOSTproject', 'View Project List', '', ''),
(21, 18, 2, 'My Dashboard', 'http://hub.HOSTdashboard', 'View Assigned Bugs and Projects', '', 'hub.dashboard');

-- --------------------------------------------------------

--
-- Table structure for table `ms_log`
--

CREATE TABLE IF NOT EXISTS `ms_log` (
  `logID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subject` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `user` int(64) NOT NULL,
  PRIMARY KEY (`logID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=134 ;

--
-- Dumping data for table `ms_log`
--

INSERT IGNORE INTO `ms_log` (`logID`, `subject`, `time`, `user`) VALUES
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
(29, 'Hook CORE::HITgallery =&gt; Display::displayPage added.', 1338717230, 1),
(30, 'Hook CORE::APIdisplayManageSave =&gt; Display::displayAPISaveData added.', 1339849199, 1),
(31, 'Hook CORE::APIdisplayManageDelete =&gt; Display::displayAPIDeletePicture added.', 1339849241, 1),
(32, 'Updated user @1', 1340204720, 1),
(33, 'Updated permissions for @1', 1340204724, 1),
(34, 'Updated permissions for @1', 1340204729, 1),
(35, 'Updated permissions for @1', 1340204733, 1),
(36, 'Updated permissions for @1', 1340204738, 1),
(37, 'Updated user @2', 1340204742, 1),
(38, 'Updated permissions for @2', 1340204750, 1),
(39, 'Updated user @1', 1340204754, 1),
(40, 'Updated permissions for @1', 1340204759, 1),
(41, 'Updated user @3', 1340204937, 1),
(42, 'Updated permissions for @3', 1340204941, 1),
(43, 'Updated user @2', 1340204952, 1),
(44, 'Updated permissions for @2', 1340204955, 1),
(45, 'Updated permissions for @2', 1340205004, 1),
(46, 'Updated permissions for @2', 1340205185, 1),
(47, 'Updated permissions for @2', 1340205191, 1),
(48, 'Updated permissions for @2', 1340205200, 1),
(49, 'Updated permissions for @2', 1340205229, 1),
(50, 'Updated permissions for @2', 1340205231, 1),
(51, 'Updated permissions for @2', 1340220510, 1),
(52, 'Updated permissions for @2', 1340220523, 1),
(53, 'Updated permissions for @2', 1340220534, 1),
(54, 'Module &lsquo;Purplish&lsquo; added.', 1340362169, 1),
(55, 'Hook Admin::PANELdisplay =&gt; Purplish::displayPanel added.', 1340363561, 1),
(56, 'Hook Admin::PANELdisplay =&gt; Purplish::displayPanel deleted.', 1340364253, 1),
(57, 'Hook Admin::PANELdisplay =&gt; Purplish::displayAdminPanel added.', 1340364276, 1),
(58, 'Hook Admin::ADMINChan =&gt; Purplish::displayAdmin added.', 1340364397, 1),
(59, 'Hook CORE::APIchan =&gt; Purplish::displayAPI added.', 1340369102, 1),
(60, 'Hook CORE::HITchan =&gt; Purplish::displayPage added.', 1340402878, 1),
(61, 'Option key &lsquo;chan_title&lsquo; (s) added with value &lsquo;Stevenchan&lsquo;.', 1340404296, 1),
(62, 'Updated user @1', 1341319123, 1),
(63, 'Updated user @1', 1341319136, 1),
(64, 'Updated user @1', 1341319182, 1),
(65, 'Updated user @1', 1341319565, 1),
(66, 'Updated user @1', 1341319571, 0),
(67, 'Module &apos;Reader&apos; added.', 1341437318, 1),
(68, 'Hook Admin::PANELdisplay =&gt; Reader::displayPanel added.', 1341437346, 1),
(69, 'Hook CORE::HITblog =&gt; Reader::displayPage added.', 1341437358, 1),
(70, 'Hook Reader::entryFoot =&gt; Fenfire::commentSection added.', 1341442088, 1),
(71, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1341442309, 1),
(72, 'Hook Ace::ADMINReader =&gt; Reader::displayAdmin added.', 1341445868, 1),
(73, 'Hook Ace::ADMINReader =&gt; Reader::displayAdmin deleted.', 1341445881, 1),
(74, 'Hook Admin::ADMINReader =&gt; Reader::displayAdmin added.', 1341445891, 1),
(75, 'Module &apos;Filer&apos; added.', 1342542752, 1),
(76, 'Hook CORE::APIfiler =&gt; Filer::displayAPI added.', 1342542811, 1),
(77, 'Hook Reader::editor =&gt; Filer::displayPopupFiler added.', 1342546828, 1),
(78, 'Hook Admin::PANELdisplay =&gt; Filer::displayPanel added.', 1342547673, 1),
(79, 'Hook Admin::ADMINFiler =&gt; Filer::displayAdmin added.', 1342547687, 1),
(80, 'Module &apos;Layouter&apos; added.', 1342865848, 1),
(81, 'Module &apos;Layouter&apos; added.', 1342865852, 1),
(82, 'Hook Admin::PANELdisplay =&gt; Layouter::displayPanel added.', 1342865879, 1),
(83, 'Hook Admin::ADMINLayouter =&gt; Layouter::displayAdmin added.', 1342866000, 1),
(84, 'Hook Admin::PANELdisplay =&gt; Display::displayPanel added.', 1343171848, 1),
(85, 'Hook Admin::ADMINDisplay =&gt; Display::displayAdmin added.', 1343171861, 1),
(86, 'Option key &apos;display_default_gallery&apos; (s) added with value &apos;shinmera&apos;.', 1343175243, 1),
(87, 'User "loldongs" (nhafner@gmx.ch) registered.', 1343820234, 0),
(88, 'User "nonPrivileged" (null@null.com) registered.', 1344089966, 0),
(89, 'Updated user @5', 1344090042, 1),
(90, 'Deleted user &apos;5&apos;', 1344090044, 1),
(91, 'Updated user @4', 1344090048, 1),
(92, 'Updated user @6', 1344090057, 1),
(93, 'Updated permissions for @6', 1344090064, 1),
(94, 'Updated user @6', 1344090076, 1),
(95, 'Updated permissions for @6', 1344090091, 1),
(96, 'Updated user @6', 1344090158, 1),
(97, 'Updated permissions for @6', 1344090162, 1),
(98, 'Updated user @6', 1344090415, 1),
(99, 'Updated user @6', 1344090416, 1),
(100, 'Updated user @6', 1344090417, 1),
(101, 'Updated user @6', 1344090418, 1),
(102, 'Updated user @6', 1344090418, 1),
(103, 'Updated user @6', 1344090418, 1),
(104, 'Updated user @6', 1344090423, 1),
(105, 'Updated user @6', 1344090427, 1),
(106, 'Hook Themes::buildMenu =&gt; Lore::buildMenu added.', 1344091603, 1),
(107, 'Module &apos;Menu&apos; added.', 1344094251, 1),
(108, 'Hook Ace::ADMINMenu =&gt; Menu::displayAdmin added.', 1344094326, 1),
(109, 'Hook Ace::ADMINMenu =&gt; Menu::displayAdmin deleted.', 1344094329, 1),
(110, 'Hook Admin::ADMINMenu =&gt; Menu::displayAdmin added.', 1344094346, 1),
(111, 'Hook Admin::PANELdisplay =&gt; Menu::displayPanel added.', 1344094360, 1),
(112, 'Hook Themes::buildMenu =&gt; Menu::buildMenu added.', 1344101238, 1),
(113, 'Hook Themes::buildMenu =&gt; Neon::buildMenu added.', 1344102049, 1),
(114, 'Hook Themes::buildMenu =&gt; Admin::buildMenu added.', 1344102070, 1),
(115, 'Hook CORE::HIToffline =&gt; CORE::offline added.', 1344153529, 1),
(116, 'Option key &apos;offline&apos; (b) added with value &apos;1&apos;.', 1344153540, 1),
(117, 'Hook CORE::HIToffline =&gt; CORE::offline added.', 1344157325, 1),
(118, 'Module &apos;Twitch&apos; added.', 1350120367, 1),
(119, 'Hook Neon::profileSettings =&gt; Twitch::displayUserPanel added.', 1350120421, 1),
(120, 'Hook Neon::TwitterSave =&gt; Twitch::displayUserSave added.', 1350120452, 1),
(121, 'Option key &apos;twitch_consumer_key&apos; (s) added with value &apos;fzGWcOhR1baeFocC4WKKA&apos;.', 1350129375, 1),
(122, 'Option key &apos;twitch_consumer_secret&apos; (s) added with value &apos;Kz527nJ2jn3AzfplttnedDEBOywhyJcGSByhFP5NUQM&apos;.', 1350129388, 1),
(123, 'Hook CORE::APItwitter =&gt; Twitch::apiTwitterReturn added.', 1350129643, 1),
(124, 'Hook Display::POST =&gt; Twitch::universalPostHook added.', 1350131961, 1),
(125, 'Hook Reader::POST =&gt; Twitch::universalPostHook added.', 1350131971, 1),
(126, 'Option key &apos;chan_fileloc_extern&apos; changed to &apos;http://linuz.com/TyNET/data/chan/&apos;.', 1350472880, 1),
(127, 'Module &apos;Hub&apos; added.', 1350479564, 1),
(128, 'Module &apos;Ticker&apos; added.', 1350479597, 1),
(129, 'Hook CORE::HIThub =&gt; Hub::displayPage added.', 1350479610, 1),
(130, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1351882894, 1),
(131, 'Comment from Huehuehue (aaa@cyz.ch) for  added.', 1351892894, 0),
(132, 'Comment from Shinmera (nhafner@gmx.ch) for  added.', 1351894113, 1),
(133, 'User "AnotherTestAccount" (shinmera@tymoon.eu) registered.', 1351944286, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ms_modules`
--

CREATE TABLE IF NOT EXISTS `ms_modules` (
  `name` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `subject` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ms_modules`
--

INSERT IGNORE INTO `ms_modules` (`name`, `subject`) VALUES
('Ace', 'Provides a simple interface for the Ace text editor component.'),
('Admin', 'Allows administration of CORE models and provides an interface for module specific configuration pages.'),
('Auth', 'Provides a secure session and authentication system, as well as permission management.'),
('CORE', 'This is the CORE module, providing the INIT system.'),
('Derpy', 'Derpy Mail, a private user message system.'),
('Display', 'A gallery module with per-user gallery support.'),
('Fenfire', 'Provides a simple comment system.'),
('Filer', 'A simple file upload API.'),
('Hub', 'The hub module provides a large project management system.'),
('Layouter', 'A layout engine to create dynamic layouts and abolish the need for complicated themes.'),
('LightUp', 'BBCode and text formatting system '),
('Liroli', 'Public user groups'),
('Lore', 'Wiki'),
('Menu', 'Menu manager.'),
('Neon', 'Provides user front-end.'),
('Purplish', 'Purplish is a kusaba-like imageboard software.'),
('Reader', 'A simple blogging system.'),
('Test', 'To run test suites.'),
('Themes', 'A simple theming system, making page construction very simple.'),
('Ticker', 'Ticker is a bug ticket tracker API and has no front-end by itself. It is best integrated into the HUB.'),
('Twitch', 'Twitter library.'),
('User', 'Allows for user management and supplies AUTH login/logout functions.');

-- --------------------------------------------------------

--
-- Table structure for table `ms_options`
--

CREATE TABLE IF NOT EXISTS `ms_options` (
  `key` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ms_options`
--

INSERT IGNORE INTO `ms_options` (`key`, `value`, `type`) VALUES
('akismet_key', '9fce28faba37', 's'),
('avatar_maxdim', '150', 'i'),
('avatar_maxsize', '500', 'i'),
('chan_defaultamount', '5', 'i'),
('chan_fileloc_extern', 'http://linuz.com/TyNET/data/chan/', 's'),
('chan_frontposts', '20', 'i'),
('chan_maxlines', '20', 's'),
('chan_online', '1', 's'),
('chan_opthumbsize', '200', 's'),
('chan_posttimeout', '5', 'i'),
('chan_theme', 'light.css', 's'),
('chan_thumbsize', '125', 's'),
('chan_title', 'Stevenchan', 's'),
('chan_tpp', '15', 's'),
('chan_trips', '', 's'),
('cookie_life_h', '8544', 'i'),
('default_theme', 'default', 's'),
('display_default_gallery', 'shinmera', 's'),
('idiots', 'retards', 's'),
('metakeys', 'Tymoon;TymoonNET;NexT;TymoonNexT;Shinmera;Nicolas;Hafner;Stevenchan', 'l'),
('offline', '1', 'b'),
('recaptcha_key_private', '6LeYH7wSAAAAAMyEpHJzu0HScC6hqm6CyV7WPVMG', 's'),
('recaptcha_key_public', '6LeYH7wSAAAAADyB1R9ooRPtxFSTCUcnL5dO6dr8', 's'),
('salt1', 'MLPisAveryAWESOMEshow', 's'),
('salt2', 'you_have_GOT_to_be_shittong_me', 's'),
('salt3', '23scUOBa38@#J(&)2h1linDV(03uBNX:', 's'),
('sitename', 'TyNET', 's'),
('sysop_mail', 'admin@xprog.ch', 's'),
('twitch_consumer_key', 'fzGWcOhR1baeFocC4WKKA', 's'),
('twitch_consumer_secret', 'Kz527nJ2jn3AzfplttnedDEBOywhyJcGSByhFP5NUQM', 's'),
('twitch_return', 'user.linuz.com/TyNET/panel?tab=Twitter', 's');

-- --------------------------------------------------------

--
-- Table structure for table `ms_timer`
--

CREATE TABLE IF NOT EXISTS `ms_timer` (
  `IP` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `action` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`IP`,`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ms_timer`
--

INSERT IGNORE INTO `ms_timer` (`IP`, `time`, `action`) VALUES
('127.0.0.1', 1352226352, 'chan_post'),
('127.0.0.1', 1341015121, 'chan_report'),
('127.0.0.1', 1351894113, 'comment'),
('127.0.0.1', 1351715407, 'displayEdit'),
('127.0.0.1', 1335395795, 'sendmessage'),
('127.0.0.1', 0, 'visit'),
('127.0.0.1', 0, 'visit:'),
('127.0.0.1', 1352237955, 'visit:1'),
('127.0.0.1', 1343824183, 'visit:5'),
('127.0.0.1', 1344091244, 'visit:6'),
('127.0.0.1', 1351945698, 'visit:7');

-- --------------------------------------------------------

--
-- Table structure for table `neon_friends`
--

CREATE TABLE IF NOT EXISTS `neon_friends` (
  `uID1` int(11) unsigned NOT NULL,
  `uID2` int(11) unsigned NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'r',
  PRIMARY KEY (`uID1`,`uID2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ti_actions`
--

CREATE TABLE IF NOT EXISTS `ti_actions` (
  `actionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TID` int(10) unsigned NOT NULL,
  `action` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `creator` int(10) unsigned NOT NULL,
  PRIMARY KEY (`actionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ti_projects`
--

CREATE TABLE IF NOT EXISTS `ti_projects` (
  `projectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PID` int(10) unsigned NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `visibility` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`projectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ti_teams`
--

CREATE TABLE IF NOT EXISTS `ti_teams` (
  `PID` int(10) unsigned NOT NULL,
  `UID` int(10) unsigned NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`PID`,`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ti_tickets`
--

CREATE TABLE IF NOT EXISTS `ti_tickets` (
  `ticketID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PID` int(10) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `creator` int(10) unsigned NOT NULL,
  `assignee` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ticketID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tw_data`
--

CREATE TABLE IF NOT EXISTS `tw_data` (
  `userID` int(10) unsigned NOT NULL,
  `token` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tw_data`
--

INSERT IGNORE INTO `tw_data` (`userID`, `token`, `secret`, `active`) VALUES
(0, '', '', 0),
(1, '126658019-ZdNErtQONBO2bPXzNQDCV3snia1UcI9WKHDzJeJk', 'gO5YdHPf3K8NeT8snvU6ZnVJdNBqEKgWVaxVYm7stao', 0),
(7, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ud_fields`
--

CREATE TABLE IF NOT EXISTS `ud_fields` (
  `varname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `default` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 's',
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  `displayed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`varname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ud_fields`
--

INSERT IGNORE INTO `ud_fields` (`varname`, `title`, `default`, `type`, `editable`, `displayed`) VALUES
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
  `varname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `userID` int(11) unsigned NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`varname`,`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ud_field_values`
--

INSERT IGNORE INTO `ud_field_values` (`varname`, `userID`, `value`) VALUES
('birthdate', 1, '18.11.1993'),
('web', 1, 'http://shinmera.com'),
('aboutuser', 1, 'I make stuff.\r\nSome of it is kinda cool.\r\n\r\nVery important trufax about me:\r\nMithent is my waifu.'),
('userfirstname', 1, 'Nicolas'),
('userlastname', 1, 'Hafner'),
('userlastname', 7, ''),
('userfirstname', 7, ''),
('birthdate', 7, '1993-11-08'),
('aboutuser', 7, '');

-- --------------------------------------------------------

--
-- Table structure for table `ud_groups`
--

CREATE TABLE IF NOT EXISTS `ud_groups` (
  `title` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ud_groups`
--

INSERT IGNORE INTO `ud_groups` (`title`, `permissions`) VALUES
('root', '*.*'),
('Unregistered', 'base.*\r\nuser.profile.*');

-- --------------------------------------------------------

--
-- Table structure for table `ud_permissions`
--

CREATE TABLE IF NOT EXISTS `ud_permissions` (
  `UID` bigint(20) unsigned NOT NULL,
  `tree` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ud_permissions`
--

INSERT IGNORE INTO `ud_permissions` (`UID`, `tree`) VALUES
(1, ''),
(2, ''),
(6, 'display.folder.shinmera.misc.*');

-- --------------------------------------------------------

--
-- Table structure for table `ud_users`
--

CREATE TABLE IF NOT EXISTS `ud_users` (
  `userID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `displayname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Registered',
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'u',
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `displayname` (`displayname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `ud_users`
--

INSERT IGNORE INTO `ud_users` (`userID`, `username`, `mail`, `password`, `secret`, `displayname`, `filename`, `group`, `status`, `time`) VALUES
(1, 'Shinmera', 'nhafner@gmx.ch', '3c5d02ca7ea61d658edd147e733816719a55aff62fcca3d84093bfd6a8df013c9ebe3db18563576989e40f27b41165401d85e856f68f6a6f06760e423a0cd9b4', '6pz5HhD9142RA5n1Ft476191vhPi2ZG', 'しんめら', '/Shinmera-gahh4.png', 'root', 'a', 0),
(2, 'McDick', 'lol@dongs.com', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e', 'b6kyP3l53rQZ3u73gX8oNvAi02G7gPH', 'Dicks', '', 'Unregistered', 'i', 0),
(3, 'Faggot', 'shinmera@tymoon.eu', 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e', 'J73Xq6HrgF081Ql130e8v8l3349Jc06', 'Faggot', '', 'Registered', 'a', 1335212007),
(4, '', '', '73c3aadab0213b673178d108fabc96436fa17fc4895d5b8c638e1b534c3533886e85eb534121d7615b97daa176c59d99d372d8f8c0441ebeca757814c313b31a', '7jckAbg3o5GIE9msd37P6aCjZg39qXP', '', '', '', 'i', 1335432455),
(6, 'nonPrivileged', 'null@null.com', 'cde7d4050c4b0a7f0a1f95111e67573d480255e11ff409285ca5a043b8fbeb297670ce29ce532aaca4f3b459faea86c5d0b383e75d33ab276b813c38031ff749', 'ch1c7w8O33E9EIc75F7IUaDnR0p5JW1', 'nonPrivileged', '', 'Unregistered', 'a', 1344089966),
(7, 'AnotherTestAccount', 'shinmera@tymoon.eu', '43c1a9c3161f6ae9adbf4fdc67350dd5343957c9a3d7e567c5cc23ea860f4b35057c13ec09456becfeb623b10894fcf69c2be53ee7fb15b0fbc6ebe179a4bcc3', '54pU21fU8XEu02OVLuM00Dc6KJxb6U6', 'AnotherTestAccount', NULL, 'Unregistered', 'a', 1351944286);
