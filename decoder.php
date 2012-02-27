<? if(!ob_start("ob_gzhandler")) ob_start();

include("/var/www/config.php");
$k->checkShitBrowser();
$banned=$k->checkBanned($_SERVER['REMOTE_ADDR']);
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
}

//PARSE URL
if(PROOT!=="/")$param=str_replace(PROOT,"",$_SERVER['REQUEST_URI']);else $param=substr($_SERVER['REQUEST_URI'],1);
if(strpos($param,"?")!=FALSE)$param=substr($param,0,strpos($param,"?"));
$param=trim(urldecode($param));
$params=explode("/",$param);
if($params[0]=="")$site="index";else $site=$params[0];
$domain=str_replace(HOST,"",$_SERVER['HTTP_HOST']);
if($domain!=""){
    $domain=substr($domain,0,strlen($domain)-1);
    if($domain=="www")$domain="";
    if($domain=="main")$domain="";
    if($domain=="stevenmagnet")$domain="chan";
    if($domain=="stevenchan")$domain="chan";
    define("DOMAIN",$domain);
}

$mod=$c->getData('SELECT `moduleID`,`title`,`index` FROM ms_modules WHERE subdomain LIKE ? AND activated=1 LIMIT 1',array('%'.DOMAIN.'%'));
if(count($mod)==0){
    if(file_exists(PAGEPATH.$site.".php"))include(PAGEPATH.$site.".php");
    else                                       include(PAGEPATH."404.php");
}else{
    include(TROOT."loader.php");
    $m = Loader::loadModule($mod[0]['index'],$mod[0]['title'],$mod[0]['moduleID']);
    $m->displayPage();
}

ob_end_flush();
flush();
$c->close();
die();
?>
