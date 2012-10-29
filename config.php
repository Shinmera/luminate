<? if(!defined("DECODER"))die("Invalid access.");
if(!defined("INIT")||$force_reload){
    //set start time and init flag
    $time = explode(' ',microtime());
    $time = $time[1]+$time[0];
    define("STARTTIME",$time);
    define("INIT",TRUE);
    //automatic host detection
    if(substr_count($_SERVER['SERVER_NAME'],'.')==1)define("HOST",$_SERVER['SERVER_NAME']);
    else                                            define("HOST",substr($_SERVER['SERVER_NAME'],strpos($_SERVER['SERVER_NAME'],'.')+1));
    //automatic root detection
    define("ROOT",rtrim($_SERVER['DOCUMENT_ROOT'],'/'));
    define("PROOT",str_replace('//','','/'.str_replace(ROOT,'',dirname(__FILE__)).'/'));
    define("TROOT",ROOT.PROOT);
    define("NODOMAIN","http://".HOST.PROOT);
    //shortcuts
    define("THEMEPATH",PROOT."themes/");
    define("DATAPATH",PROOT."data/");
    define("MODULEPATH",TROOT."modules/");
    define("TEMPLATEPATH",PAGEPATH.'templates/');
    define("IMAGEPATH",DATAPATH."images/");
    define("TEMPPATH",ROOT.DATAPATH."temp/");
    define("PAGEPATH",ROOT.DATAPATH."pages/");
    define("CALLABLESPATH",ROOT.DATAPATH."callables/");
    define("AVATARPATH",IMAGEPATH."avatars/");
    define("HEADERPATH",IMAGEPATH."headers/");
    //auth variables
    define('COOKIE_DOMAIN',HOST);
    define('COOKIE_PATH',PROOT);
    define('COOKIE_AUTH','authcook');
    define('SECRET_KEY','dk;l1894!851éds-fghjg4lui:è3afàzgq_f4fá.');
    //mysql information
    define("SQLUSER","tymoon");
    define("SQLPASS","DtuyAEuNesZMhLNc");
    define("SQLDB","tymoonD");
    //other bs
    define("SYSTEMNAMES","system,tynet,tymoonnet,admin,root,mod,moderator");
    define("TRUSTEDIPS","188.154.8.12 192.168.0.1 127.0.0.1 0.0.0.1");
    define("SYSOPMAIL","admin@tymoon.eu");
    //buffering and compressing
    if(extension_loaded('gzip'))define("COMPRESS",TRUE);
    else                        define("COMPRESS",FALSE);
    define("BUFFER",TRUE);
    //cloudflare module remote IP fix
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    //set base runtime variables
    $SUPERIORPATH="";
    $DOMINATINGMODULE="";
    $MODULES=array();
    $MODULECACHE=unserialize(file_get_contents(CALLABLESPATH.'modulecache'));
    //load core classes
    if(!class_exists("Module")){    require_once(MODULEPATH.'module.php');}
    if(!class_exists("DataModel")){ require_once(CALLABLESPATH.'DataModel.php');}
    if(!class_exists("Loader")){    require_once(TROOT.'loader.php');}              $l = new Loader();
    if(!class_exists("Toolkit")){   require_once(CALLABLESPATH.'toolkit.php');}     $k = new Toolkit();
    //load core modules
    $l->loadModule('Sqlloader');
    $c->connect(SQLUSER,SQLPASS,SQLDB);
    $l->loadModule('Core');
    //$k->generateModuleCache();
    //print_r(get_defined_constants(true)['user']);
} ?>
