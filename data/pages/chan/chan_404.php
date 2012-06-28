<? header('HTTP/1.0 404 Not Found'); ?>
<? global $c;?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>404, Item Not Found!</title>
    <link rel="icon" type="image/png" href="<?=DATAPATH?>images/404.png" />
    <? include(PAGEPATH.'/meta.php'); ?>
    <link rel='stylesheet' type='text/css' href='<?=DATAPATH?>css/chanspecial.css' />
</head>
<? if(BUFFER)ob_flush();flush();
$dir = opendir(ROOT.IMAGEPATH.'chan/404/');$images = array();
while(($file=readdir($dir))!==FALSE){
    if($file!='.'&&$file!='..')
        $images[]=IMAGEPATH.'chan/404/'.$file;
}closedir($dir);
?>
<body>
    <img src="<?=$images[mt_rand(0,count($images)-1)]?>" alt=" " class="header" />
    <h1><?=$c->o['chan_title']?> 404 - Item not found.</h1>
    <div id="content">
        <a id="return" href="<?=PROOT?>" title="Return to the front page">Return</a>
        <article>
            <blockquote>
                <h2>Sorry, what you are looking for apparently doesn't exist!</h2>
                Maybe you mistyped the URL or it simply expired and the content got deleted.<br />
                If that's the case, we're sorry. Maybe you can still find it through the 
                <a href="http://wayback.archive.org/web/*/http://chan.<?=HOST.$_SERVER['REQUEST_URI']?>">Wayback Machine</a>?
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