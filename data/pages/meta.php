<? global $c,$t; ?>
<meta http-equiv="description" content="<?=$c->o['metadescription']?>" />
<meta http-equiv="content-language" content="en-US,en-GB,en" />
<meta http-equiv="author" content="Shinmera" />
<meta http-equiv="publisher" content="TymoonNET NexT" />
<meta http-equiv="copyright" content="<?=date("Y")?> TymoonNET/NexT" />
<meta http-equiv="distribution" content="Global" />
<meta http-equiv="keywords" content="<?=$c->o['metakeys']?>" />
<meta http-equiv="robots" content="ALL" />

<meta name="content-type" content="text/html; charset=UTF-8" />
<meta name="description" content="<?=$c->o['metadescription']?>" />
<meta name="content-language" content="en-US,en-GB,en" />
<meta name="author" content="Shinmera" />
<meta name="publisher" content="TymoonNET NexT" />
<meta name="copyright" content="<?=date("Y")?> TymoonNET/NexT" />
<meta name="distribution" content="Global" />
<meta name="keywords" content="<?=$c->o['metakeys']?>" />
<meta name="robots" content="ALL" />

<link rel="alternate" type="application/rss+xml" title="All RSS feeds" href="http://feeds.feedburner.com/Tymoon" />
<link rel="alternate" type="application/rss+xml" title="RSS: Blog" href="<? echo($host.$proot); ?>rss.php?CID=blog" />
<link rel="alternate" media="handheld" href="http://mobile.tymoon.eu" />
<? for($i=0;$i<count($c->coCID);$i++){
	echo('<link rel="alternate" type="application/rss+xml" title="RSS: '.$c->coCTitle[$i].'" href="'.$host.$proot.'rss.php?CID='.$c->coCID[$i].'" />');
} ?>
