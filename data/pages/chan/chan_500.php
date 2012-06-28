<? header('HTTP/1.0 500 denied'); ?>
<? global $c;?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>500, Permission Denied!</title>
    <link rel="icon" type="image/png" href="<?=DATAPATH?>images/500.png" />
    <? include(PAGEPATH.'/meta.php'); ?>
    <link rel='stylesheet' type='text/css' href='<?=DATAPATH?>css/chanspecial.css' />
</head>
<? if(BUFFER)ob_flush();flush();
$dir = opendir(ROOT.IMAGEPATH.'chan/500/');$images = array();
while(($file=readdir($dir))!==FALSE){
    if($file!='.'&&$file!='..')
        $images[]=IMAGEPATH.'chan/500/'.$file;
}closedir($dir);
?>
<body>
    <img src="<?=$images[mt_rand(0,count($images)-1)]?>" alt=" " class="header" />
    <h1><?=$c->o['chan_title']?> E500 - Permission denied.</h1>
    <div id="content">
        <a id="return" href="<?=PROOT?>" title="Return to the front page">Return</a>
        <article>
            <blockquote>
                <h2>Hm, it looks like we can't allow you to see this!</h2>
                Perhaps you simply forgot to <a href="<?=Toolkit::url('login')?>">log in</a>?<br />
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
</html>
<? if(BUFFER)ob_end_flush();flush();die(); ?>