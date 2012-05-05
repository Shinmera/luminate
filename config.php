<? if(!defined("INIT")||$force_reload){
$time = explode(' ',microtime());
$time = $time[1]+$time[0];
define("STARTTIME",$time);
define("INIT",TRUE);
//local reference variables
//define("HOST","linuz.com");
if(substr_count($_SERVER['SERVER_NAME'],'.')==1)define("HOST",$_SERVER['SERVER_NAME']);
else                                            define("HOST",substr($_SERVER['SERVER_NAME'],strpos($_SERVER['SERVER_NAME'],'.')+1));
define("ROOT","/var/www");
define("PROOT","/Luminate/");
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
define("NO_ACCESS",'<div class="large" style="text-align:center;">You are not authorized to view this page.</div>');

$SUPERIORPATH="";
$DOMINATINGMODULE="";
$MODULES=array();
$MODULECACHE=unserialize(file_get_contents(CALLABLESPATH.'modulecache'));
if(!class_exists("Module")){    require_once(MODULEPATH.'module.php');}
if(!class_exists("DataModel")){ require_once(CALLABLESPATH.'DataModel.php');}
if(!class_exists("Loader")){    require_once(TROOT.'loader.php');}              $l = new Loader();
if(!class_exists("Toolkit")){   require_once(CALLABLESPATH.'toolkit.php');}     $k = new Toolkit();
$l->loadModule('Sqlloader');
$c->connect(SQLUSER,SQLPASS,SQLDB);
$l->loadModule('Core');
//$k->generateModuleCache();
}
?>
