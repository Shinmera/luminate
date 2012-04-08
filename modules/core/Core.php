<?
class CORE extends Module{
public static $name="CORE";
public static $version="4.0.5-Linux/Debian-α";
public static $short='CORE';
public static $required=array();
public static $hooks=array("foo");

function __construct(){}

function printTimePassed(){
    global $k,$l,$SUPERIORPATH;
    ?><center>
        <div style='display:inline-block;padding:5px;border-radius:5px;box-shadow: 0px 0px 10px #000;'>
            <?=$k->getTimeElapsed()?>s<br />
            <?=CORE::$version?>
        </div>
    </center><?
    $t = $l->loadModule("Themes");
    $fenfire = $l->loadModule("Fenfire");
    
    $t->openPage("INDEX");
    $SUPERIORPATH="INDEX";
    $fenfire->commentList();
    $fenfire->commentBox();
    $t->closePage();
}

function apiCall(){
    global $l,$site,$params;
    if($site=="api"){
        $site = $params[0];
        $params = array_slice($params, 1);
    }
    $l->triggerHook("API".$site,$this);
}

}
?>
