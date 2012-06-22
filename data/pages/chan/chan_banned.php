<? global $c;
$bans = DataModel::getData('ch_bans','SELECT * FROM ch_bans WHERE ip LIKE ?',array($_SERVER['REMOTE_ADDR']));
$c->query('DELETE FROM ch_bans WHERE ip=? AND time<?',array($_SERVER['REMOTE_ADDR'],time()));

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
            background: #000;
        }
        
        body{
            background: #FFF;
            margin: 5% 20% 0 20%;
            padding:5px;
            border-radius: 5px;
            box-shadow: 0 0 50px #00EEFF;
            position:relative;
        }
        
        h1,h2{margin:0;padding:0;}
        h1{text-align:center;}
        
        img{
            position:absolute;
            right:-100px;
            top: 20px;
            background: #000;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #FFF;
            box-shadow: 0 0 10px #FFF;
        }
    </style>
</head>
<? ob_flush();flush(); ?>
<body>
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
        <img src="http://data2.tymoon.eu/fab/thumbs/134040250592420.jpg" alt=" " />
        <h1>You have been banned from <?=$c->o['chan_title']?>!</h1>
        <h2>Reason(s):</h2>
        <article>
            <ul>
                <? foreach($bans as $ban){ ?>
                    <li>Until <?=Toolkit::toDate($ban->time)?> : <?=$ban->reason?></li>
                <? } ?>
            </ul>
        </article>
        <h2>The ban is associated with the following post(s):</h2>
        <div class="posts">
            <? foreach($bans as $ban){
                @include(ROOT.DATAPATH.'chan/'.$ban->folder.'/posts/'.$ban->PID.'.php');
            } ?>
        </div>
        <? if($appeal==''){ ?>
            <h2>Submit an appeal:</h2>
            <form action="#" method="post">
                <textarea name="appeal" required placeholder="..."></textarea><br />
                <input type="submit" value="Submit Appeal" />
            </form>
        <? }else{ ?>
            <h2>Your appeal message:</h2>
            <blockquote>
                <?=$appeal?>
            </blockquote>
    <? } ?>
</body>
    <? die();
} ?>