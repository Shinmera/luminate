<? global $c;?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Offline!</title>
    <link rel="icon" type="image/png" href="<?=DATAPATH?>images/offline.png" />
    <? include(PAGEPATH.'/meta.php'); ?>
    <style>
        html{
            font-family:Arial;
            font-size:10pt;
            background: #555;
        }
        
        body{
            margin: 5% 10% 0 10%;
            position:relative;
        }
        
        h2{
            margin:0;padding:0;
            color: #FFF;
            text-shadow: 0 0 3px #000;
        }
        h1{
            margin:0;padding:0;
            text-align:center;
            text-shadow: 0 0 3px #000;
            font-size: 26pt;
            color: #FFF;
        }
        
        img.header{
            margin: 0 auto -20px auto;
            display:block;
            border: 1px solid #000;
            max-width:100%;
            max-height:100%;
        }
        
        #content{
            margin-top:10px;
            background: #AAA;
            padding:5px;
            border-radius: 5px;
            box-shadow: 0 0 50px #000;
            position:relative;
            border: 1px solid #888;
        }
        
    </style>
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
        <article>
            <blockquote>
                <h2>Don't worry though, this is done on purpose!</h2>
                Most likely the administrators are currently working on something and everything will be back up soon.<br />
                Until then, please bear with us and remain calm.
            </blockquote>
        </article>
    </div>
</body>
<? if(BUFFER)ob_end_flush();flush();die(); ?>