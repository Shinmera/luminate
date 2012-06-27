<? global $c;
$bans = DataModel::getData('ch_bans','SELECT * FROM ch_bans WHERE ip LIKE ? AND mute=0',array($_SERVER['REMOTE_ADDR']));
$c->query('DELETE FROM ch_bans WHERE ip=? AND (time+period)<? AND period>0',array($_SERVER['REMOTE_ADDR'],time()));

if($bans!=null){?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Crime Scene</title>
    <link rel="icon" type="image/png" href="<?=DATAPATH?>images/banned.png" />
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
        
        #content textarea{box-sizing: border-box;width:100%;min-height:100px;}
    </style>
    <link rel='stylesheet' type='text/css' href='<?=DATAPATH?>css/chanpost.css' />
</head>
<? ob_flush();flush();
$dir = opendir(ROOT.IMAGEPATH.'chan/ban/');$images = array();
while(($file=readdir($dir))!==FALSE){
    if($file!='.'&&$file!='..')
        $images[]=IMAGEPATH.'chan/ban/'.$file;
}closedir($dir);
?>
<body>
    <img src="<?=$images[mt_rand(0,count($images)-1)]?>" alt=" " class="header" />
    <h1>You have been banned from <?=$c->o['chan_title']?>!</h1>
    <div id="content">
        <? 
        if(!is_array($bans))$bans=array($bans);
        $appeal='';
        foreach($bans as $ban){
            if($ban->appeal!=''){$appeal=$ban->appeal;}
        }
        if($_POST['appeal']!=''){
            if($appeal!='')die('You already submitted an appeal to your bans.');
            else{
                $c->query('UPDATE ch_bans SET appeal=? WHERE ip=?',array($_POST['appeal'],$_SERVER['REMOTE_ADDR']));
                $appeal=$_POST['appeal'];
            }
        }
        ?>
        <h2>Issue<?=(count($bans)>1)? 's' : ''?>:</h2>
        <article>
            <ul>
                <? foreach($bans as $ban){ ?>
                <li>
                    On <?=Toolkit::toDate($ban->time)?>. 
                    <? if(time()>$ban->time+$ban->period&&$ban->period>0)$ban->period=time()-$ban->time; ?>
                    <?=($ban->period>0) ? 'Time remaining: '.Toolkit::toLiteralTime($ban->period-(time()-$ban->time)) : 'Until The end of time'?>. 
                    Reason: <?=$ban->reason?>
                </li>
                <? } ?>
            </ul>
        </article>
        <h2>The ban is associated with the following post<?=(count($bans)>1)? 's' : ''?>:</h2>
        <div class="posts">
            <? foreach($bans as $ban){
                include(ROOT.DATAPATH.'chan/'.$ban->folder.'/posts/'.$ban->PID.'.php');
            } ?>
        </div>
        <? if($appeal==''){ ?>
            <h2>Submit an appeal:</h2>
            <form action="#" method="post">
                <textarea name="appeal" required placeholder="..."></textarea><br />
                <input type="submit" value="Submit Appeal" />
            </form>
        <? }else if($appeal!='You cannot appeal to this ban.'){ ?>
            <h2>Your appeal message:</h2>
            <blockquote>
                <?=$appeal?>
            </blockquote>
        <? }else{ ?>
            <h2><?=$appeal?></h2>
        <? } ?>
    </div>
</body>
    <? die();
} ?>