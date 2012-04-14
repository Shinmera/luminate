<? if(!ob_start("ob_gzhandler")) ob_start();
try{
    include("config.php");
    $k->checkShitBrowser();

    //PARSE URL
    if(PROOT!=="/")$param=str_replace(PROOT,"",$_SERVER['REQUEST_URI']);
    else           $param=substr($_SERVER['REQUEST_URI'],1);

    $param=urldecode($param);
    if(strpos($param,"?")!==FALSE)$param=substr($param,0,strpos($param,"?"));
    $param=trim($param);
    $params=explode("/",$param);
    if($params[0]=="")$site="index";else $site=$params[0];
    if($params[0]==PROOT)$params=array_slice($params, 1);
    $domain=str_replace(HOST,"",$_SERVER['HTTP_HOST']);
    if($domain!="")$domain=strtolower(substr($domain,0,strlen($domain)-1));
    
    if($params[0]=="api"){ //API hack
        $domain="api";
        $params=array_slice($params, 1);
    }
    define("DOMAIN",$domain);
    
    $l->triggerHook("HIT".DOMAIN,"CORE",array($params),array(),true);

    ob_end_flush();
    flush();
    $c->close();

    $l->triggerHook("END",$CORE,array($k->getMicrotime()));
    
}catch(Exception $e){Toolkit::err("Error Code: ".$e->getCode()."<br>Error Message: ".$e->getMessage()."<br>Strack Trace: <br>".$e->getTraceAsString());}
?>
