<?php

class LightUpStream  extends Module{
public static $name="LightUpStream";
public static $version=3.21;
public static $short='lightupstream';
public static $required=array();
public static $hooks=array("foo");


    function deparse($args){
        $this->loadCode();
        
        $s = &$args['text'];
        $s = $this->fixslashes($s);
        if($args['allowRaw']) $s = preg_replace_callback("`html\{(.+?)\}html`is",array(&$this, 'reparseHTML'), $s);
        if($args['formatted']){
            if(isset($args['suites']))$s = $this->parseFuncEM($s,$args['suites']);
            else                      $s = $this->parseFuncEM($s);
        }
        //Following regex attempts to parse urls without parsing existing ones.
        //Copied from http://stackoverflow.com/questions/287144/need-a-good-regex-to-convert-urls-to-links-but-leave-existing-links-alone
        $s = preg_replace( '`(?<![\{\}"\'/>])\b(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-Z0-9+&@#/%=~_|$?!:,.]*[A-Z0-9+&@#/%=~_|$]`is',
                           '<a href="\0" target="_blank">\0'.$c->o['link_symbol'].'</a>', $s );
        $s = preg_replace( '`@([-A-Z0-9._-]*)`is', '<a href="'.Toolkit::url("user","").'\1" target="_blank">@\1</a>',$s);
        $s = str_ireplace("\n","<br />",$s);
        $args['text']=$s;
        return $args;
    }
    
    function fixslashes($s){
        $s = str_replace("\\'","'",$s);
        $s = str_replace('\\"','"',$s);
        $s = str_replace('\\\\','\\',$s);
        return $s;
    }

    function reparseHTML($matches){
        $s = str_ireplace("<br />","",$matches[1]);
        $s = str_ireplace("&gt;",">",$s);
        $s = str_ireplace("&lt;","<",$s);
        $s = str_ireplace("&#36;","$",$s);
        $s = str_ireplace("&#039;","'",$s);
        $s = str_ireplace("&lsquo;","'",$s);
        $s = str_ireplace("&quot;",'"',$s);
        return $s;
    }

    function parseFuncEM($s,$suites = array("*")){
        
        
        
        
        return $s;
    }

}
?>