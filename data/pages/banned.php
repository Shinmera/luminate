<? include("../../config.php"); ?>
<? if($_POST['varappeal']!=""){
    $IP=$k->checkBanned($_SERVER['REMOTE_ADDR']);
    if($IP!=false&&$c->msBAppeal[array_search($IP,$c->msBIP)]=="true"){
            $c->query("UPDATE ms_banned SET appeal=? WHERE IP=?",array($p->enparse($_POST['varappeal']),$IP));
            $banned[4]=$p->enparse($_POST['varappeal']);
    }
}?>
<html>
<head>
<title>B&.</title>
<style type='text/css'>
body,html{
    margin-left:auto;
    margin-right:auto;
    width:1000px;
    font-family:Arial;
}
.content{
    background-color:#AAA;
    width:100%;
    min-height:200px;
    padding:10px;
    margin-top:50px;
    border-radius:5px;
    box-shadow: 0px 0px 5px #000;
}
.banimage{
    position:relative;
    right:-10px;
    top:-30px;
    float:right;
}
.banimage img{
    border-radius: 0px 5px 0px 0px;
}
.heading{
    background-color:black;
    color:red;
    margin-left:-10px;
    margin-right:-10px;
    padding-left:10px;
    font-weight:bold;
    box-shadow: 0px 0px 5px #000;
    text-shadow: 0px 0px 5px #FF0000;
}
.banreason{
    margin-top:10px;
}
.appeal{
    padding:10px;
    margin-top:10px;
}
</style>
</head>
<body>
<div class="content">
<? if($banned[0]==true){ ?>
    <div class="heading"><marquee>You are B&.</marquee></div>
    <div class="banimage"><img src='<?=PROOT?>images/banned.png' alt=''></div>
    <br />
    <span style='font-size:16pt;'>Your IP (<?=$_SERVER['REMOTE_ADDR']?>) has been B& for the following reason:</span><br />
    <blockquote class="banreason"><?=$banned[3]?></blockquote><br />
    <? if($banned[2]==-1) echo("Your ban is permanent."); else echo("Your ban ends on ".date("D M j G:i:s T Y",$banned[2]).".");?><br />
    <br />
    <? if($banned[4]=="false"){ ?>You may NOT appeal to this ban.
    <? }else if($banned[4]=="true"){ ?>
        You may issue an appeal to this ban by using the post box below.<br />
        <form action="#" method="POST">
        <textarea style='width:100%;height:150px;' name='varappeal'></textarea>
        <input type="submit" value="Submit">
        </form>
    <? }else{ ?>
        You have already appealed to this ban. This is the text you submitted:<br />
        <blockquote class="appeal"><?=$p->deparseAll($banned[4])?></blockquote>
    <? } ?>
<? }else{ ?>
    <div class="heading">What are you doing here?</div>
<? } ?>
</div>
</body>
</html>
