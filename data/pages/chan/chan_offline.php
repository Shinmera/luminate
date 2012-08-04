<? global $c,$l;?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Offline!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
    <link rel="icon" type="image/png" href="<?=DATAPATH?>images/offline.png" />
    <? include(PAGEPATH.'/meta.php'); ?>
    <link rel='stylesheet' type='text/css' href='<?=DATAPATH?>css/chanspecial.css' />
</head>
<? if(BUFFER)ob_flush();flush();
$dir = opendir(ROOT.IMAGEPATH.'chan/offline/');$images = array();
while(($file=readdir($dir))!==FALSE){
    if($file!='.'&&$file!='..')
        $images[]=IMAGEPATH.'chan/offline/'.$file;
}closedir($dir);
?>
<body>
    <img src="<?=$images[mt_rand(0,count($images)-1)]?>" alt=" " class="header" />
    <h1><?=$c->o['chan_title']?> is currently offline.</h1>
    <div id="content">
        <a id="return" href="<?=PROOT?>" title="Return to the front page">Return</a>
        <article>
            <blockquote>
                <h2>Don't worry though, this is done on purpose!</h2>
                Most likely the administrators are currently working on something and everything will be back up soon.<br />
                Until then, please bear with us and remain calm.
                <? $l->triggerParse('offline','Purplish') ?>
            </blockquote>
        </article>
    </div>
    <div id="footer">
        <? global $CORE,$c; ?>
        &copy;2010-<?=date("Y")?> TymoonNexT, all rights reserved.<br />
        Running TyNET-<?=$CORE::$version?><br />
        Page generated in <?=Toolkit::getTimeElapsed();?>s using <?=$c->queries?> queries.
    </div>
</body>
<? if(BUFFER)ob_end_flush();flush();die(); ?>