<?
try{
    define("DECODER",TRUE);
    include('config.php');
    if(BUFFER){if(COMPRESS){if(!ob_start('ob_gzhandler')) ob_start();}}
    Toolkit::checkShitBrowser();

    //Strip PROOT if included.
    if(strpos($_SERVER['REQUEST_URI'], PROOT) === 0)$param=substr($_SERVER['REQUEST_URI'], strlen(PROOT));
    else                                            $param=substr($_SERVER['REQUEST_URI'],1);

    //Decode parameters (directory path)
    $param=trim(urldecode($param));
    if(strpos($param,'?')!==FALSE)$param=substr($param,0,strpos($param,'?'));
    $params=explode('/',$param);

    //Determine active subdomain
    $domain=str_replace(HOST,'',$_SERVER['HTTP_HOST']);
    if($domain!='')$domain=strtolower(substr($domain,0,strlen($domain)-1));
    
    //Api special hack to avoid the AJAX domain issue.
    if($params[0]=='api' || $params[0]=='_api'){ //API hack
        $domain='api';
        $param=substr($param, strlen($params[0])+1);
        $params=array_slice($params, 1);
    }
    //Redirect on special underscore directory:
    if(substr($params[0], 0, 1) == '_'){
        $domain=substr($params[0], 1);
        $param=substr($param, strlen($params[0])+1);
        header('Location: http://'.$domain.'.'.HOST.PROOT.$param);
        $c->close();
        ob_end_flush();
        flush();
        die();
    }

    //Default to index if no subdomain
    if($params[0]=='')$site='index';else $site=$params[0];
    //Override to offline if in offline mode.
    if($c->o['offline']==='1'&&$domain!='admin'&&
       strpos(TRUSTEDIPS,$_SERVER['REMOTE_ADDR'])===FALSE)$domain="offline";

    define('DOMAIN',$domain);
    define('FULLURL',$domain.'.'.HOST.$_SERVER['REQUEST_URI']);
    define('PARAM',$param);

    $l->triggerHook('HIT'.DOMAIN,'CORE',array($params),array(),true);
    
    //No hook registered for the domain, call default.
    if($DOMINATINGMODULE=='')
        $l->triggerHook('HITDOMAIN','CORE',array($params),array(),true);

    //Cleanup
    if(BUFFER)ob_end_flush();
    flush();
    $c->close();
    $l->triggerHook('END',$CORE,array($k->getMicrotime()));

}catch(Exception $e){
    Toolkit::handle_exception($e);
    include(PAGEPATH.'500.php');
}
?>
