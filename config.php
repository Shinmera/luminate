<? if(!defined("INIT")||$force_reload){
$time = explode(' ',microtime());
$time = $time[1]+$time[0];
define("STARTTIME",$time);
define("VERSION","4.0.2-Linux/Debianα");
define("INIT",TRUE);
//local reference variables
define("HOST","linuz.com");
define("ROOT","/var/www");
define("PROOT","/Luminate/");
define("TROOT",ROOT.PROOT);
//shortcuts
define("THEMEPATH",PROOT."themes/");
define("DATAPATH",PROOT."data/");
define("MODULEPATH",TROOT."modules/");
define("TEMPLATEPATH",PAGEPATH.'templates/');
define("AVATARPATH",IMAGEPATH."avatars/");
define("HEADERPATH",IMAGEPATH."headers/");
define("IMAGEPATH",DATAPATH."images/");
define("TEMPPATH",DATAPATH."temp/");
define("PAGEPATH",ROOT.DATAPATH."pages/");
define("CALLABLESPATH",ROOT.DATAPATH."callables/");
//auth variables
define('COOKIE_DOMAIN',HOST);
define('COOKIE_PATH',PROOT);
define('COOKIE_AUTH','authcook');
define('SECRET_KEY','dk;l1894!851éds-fghjg4lui:è3afàzgq_f4fá.');
//mysql information
define("SQLUSER","tymoon");
define("SQLPASS","XprogOdbc1");
define("SQLDB","tymoonD");
//other bs
define("SYSTEMNAMES","system,tynet,tymoonnet,admin,root,mod,moderator");

if(!class_exists("Module")){    require_once(MODULEPATH.'module.php');}
if(!class_exists("API")){       require_once(TROOT.'api.php');}                 $api=new API();
if(!class_exists("Loader")){    require_once(TROOT.'loader.php');}              $l = new Loader();
if(!class_exists("Toolkit")){   require_once(CALLABLESPATH.'toolkit.php');}     $k = new Toolkit();
$l->loadModule('core', 'sqlloader');
$c->connect(SQLUSER,SQLPASS,SQLDB);
}
?>
