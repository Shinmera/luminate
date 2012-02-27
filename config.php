<? if(!defined("INIT")||$force_reload){
$time = explode(' ',microtime());
$time = $time[1]+$time[0];
define("STARTTIME",$time);
define("VERSION",4.00);
define("INIT",TRUE);
//local reference variables
define("HOST","netbook.me");
define("ROOT","/var/www");
define("PROOT","/");
define("TROOT",ROOT.PROOT);
//shortcuts
define("THEMEPATH",PROOT."themes/");
define("TEMPLATEPATH",PAGEPATH.'templates/');
define("DATAPATH",PROOT."data/");
define("IMAGEPATH",DATAPATH."images/");
define("TEMPPATH",DATAPATH."temp/");
define("AVATARPATH",IMAGEPATH."avatars/");
define("HEADERPATH",IMAGEPATH."headers/");
define("MODULEPATH",TROOT."modules/");
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
define("SQLDB","tymoonC");
//other bs
define("SYSTEMNAMES","system,tynet,tymoonnet");

if(!class_exists("Module")){    require_once(MODULEPATH.'module.php');}
if(!class_exists("API")){       require_once(TROOT.'api.php');}                 $api=new API();
if(!class_exists("Loader")){    require_once(TROOT.'loader.php');}              $l = new Loader();
if(!class_exists("Toolkit")){   require_once(CALLABLESPATH.'toolkit.php');}     $k = new Toolkit();
$l->loadModule('core/sqlloader.php', 'Sqlloader');
$c->connect(SQLUSER,SQLPASS,SQLDB);
}
?>
