<?
class CORE extends Module{
public static $name="CORE";
public static $version="4.3.2-Linux/Debian-α";
public static $short='CORE';
public static $required=array();
public static $hooks=array("foo");

function __construct(){}

function printTimePassed(){
    global $k,$l,$SUPERIORPATH;
    $l->loadModule("Auth");
    $t = $l->loadModule("Themes");
    
    $t->openPage("INDEX");
    $SUPERIORPATH="INDEX";
    
    echo('<h2 style="text-align:center;font-size:36pt;">Welcome to TyNET, Electric Boogaloo Part 4</h2>');
    echo('<div style="text-align:center;"><img style="box-shadow: 0px 0px 50px #FFF;" src="http://img.tymoon.eu/img//bronies/tumblr_lyjy38QdW81qbfyjbo1_1280.jpeg" alt="lol" /></div>');
    
    $t->closePage();
}

function offline(){
    include(PAGEPATH.'offline.php');
}

function page(){
    global $params,$l;
    $t = $l->loadModule("Themes");
    $t->openPage($params[0]);
    
    if(file_exists(PAGEPATH.$params[0]))include(PAGEPATH.$params[0]);
    else if(file_exists(PAGEPATH.$params[0].'.php'))include(PAGEPATH.$params[0].'.php');
    else if(file_exists(PAGEPATH.$params[0].'.html'))include(PAGEPATH.$params[0].'.html');
    else include(PAGEPATH.'404.php');
    
    $t->closePage();
}

function apiCall(){
    global $l,$site,$params;
    if($site=="api"){
        $site = $params[0];
        $params = array_slice($params, 1);
    }
    if($site=='time'){echo(time());die();}
    $l->triggerHook("API".$site,$this);
}

}
?>
