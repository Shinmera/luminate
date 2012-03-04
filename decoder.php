<? if(!ob_start("ob_gzhandler")) ob_start();

include("config.php");
$k->checkShitBrowser();
/*$banned=$k->checkBanned($_SERVER['REMOTE_ADDR']);
if(is_array($banned)){
    $banned[0]=true;
    $banned[1]=$banned['IP'];
    $banned[2]=$banned['time'];
    $banned[3]=$banned['reason'];
    $banned[4]=$banned['appeal'];
    if($banned['reason']!="/mute"){
        include(PAGEPATH.'banned.php');
        die();
    }
}*/

//PARSE URL
if(PROOT!=="/")$param=str_replace(PROOT,"",$_SERVER['REQUEST_URI']);else $param=substr($_SERVER['REQUEST_URI'],1);
if(strpos($param,"?")!=FALSE)$param=substr($param,0,strpos($param,"?"));
$param=trim(urldecode($param));
$params=explode("/",$param);
if($params[0]=="")$site="index";else $site=$params[0];
if($params[0]==PROOT)$params=array_slice($params, 1);
$domain=str_replace(HOST,"",$_SERVER['HTTP_HOST']);
if($domain!=""){
    $domain=strtolower(substr($domain,0,strlen($domain)-1));
    define("DOMAIN",$domain);
}

$l->triggerHook("HIT".DOMAIN,$CORE,array($params));

ob_end_flush();
flush();
$c->close();

$l->triggerHook("END",$CORE,array($k->getMicrotime()));
die();
?>
