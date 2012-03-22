<? global $c,$t ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title><?=PAGETITLE?> - <?=$c->o['sitename']?></title>
    
    <? for($i=0;$i<count($t->css);$i++){echo("<link rel='stylesheet' type='text/css' href='".THEMEPATH.$t->tname."/".$t->css[$i]."' />\n");}?>
    <link rel='stylesheet' type='text/css' href='null.css' id='dynstyle' />
    <link rel="icon" type="image/png" href="<?=DATAPATH?>images/favicon.png" />
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?=DATAPATH?>js/jquery.js"><\/script>')</script>
    <script type="text/javascript" src="<?=DATAPATH?>js/jquery.cookie.js"></script>
    <? include(PAGEPATH.'/meta.php'); ?>
</head>
<? ob_flush();flush(); ?>
<body>