<? global $c,$t ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=PAGETITLE?> - <?=$c->o['sitename']?></title>
    
    <? for($i=0;$i<count($t->css);$i++){echo("<link rel='stylesheet' type='text/css' href='".THEMEPATH.$t->tname."/".$t->css[$i]."' />\n");}?>
    <link rel='stylesheet' type='text/css' href='null.css' id='dynstyle' />
    
    <? /*<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>*/ ?>
    <script type="text/javascript" src="<?=DATAPATH?>js/jquery.js"></script>
    <script type="text/javascript" src="<?=DATAPATH?>js/jquery.cookie.js"></script>
    <? include(PAGEPATH.'/meta.php'); ?>
</head>
<? ob_flush();flush(); ?>
<body>