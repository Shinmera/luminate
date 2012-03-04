<?
class CORE extends Module{
public static $name="CORE";
public static $version="4.0.2-Linux/Debian-α";
public static $short='CORE';
public static $required=array();

function __construct(){}

function printTimePassed(){
    global $k;
    ?><center><div style='display:inline-block;padding:5px;border-radius:5px;box-shadow: 0px 0px 10px #000;'><?=$k->getTimeElapsed()?>s</div></center><?
}

}
?>
