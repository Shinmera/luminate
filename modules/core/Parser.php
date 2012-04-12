<?php
class Parser extends Module{
public static $name="Parser";
public static $version=2.8;
public static $short='p';
public static $required=array();
public static $hooks=array("foo");
    
    function fixslashes(&$s){
        $s = str_replace("\\'","'",$s);
        $s = str_replace('\\"','"',$s);
        $s = str_replace('\\\\','\\',$s);
    }

    function deparse($args){
        $s = $args['text'];
        $s = str_ireplace("\n","<br />",$s);
        fixslashes($s);
        if($args['formatted'])parseBBCode($s);
        if($args['allowRaw'])$s=preg_replace_callback("`\[html\](.+?)\[/html\]`is",array(&$this, 'reparseHTML'), $s);
        $args['text']=$s;
        return $args;
    }
    
    function parseBBCode(&$s){
        $s=$this->balanceTags($s);
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

    function BBCodeDeParse($s,$level=99,$blacktags=array()){
        if(strlen($s)>1){
            global $c,$l;
            $c->loadBBCode();
            for($j=0;$j<10&&strpos($s,"[")!==FALSE;$j++){
                for($i=0;$i<count($c->msBCode);$i++){
                    if($level>=$c->msBAdmin[$i]&&!in_array($c->msBRaw[$i],$blacktags))
                        $s = preg_replace($c->msBCode[$i], $c->msBHTML[$i], $s);
                }
            }
            for($i=0;$i<count($c->msMID);$i++){
                $s = preg_replace("`\[".$c->msMTitle[$i]."=(.+?)\](.+?)\[/".$c->msMTitle[$i]."\]`is","<a href='".PROOT.$c->msMSub[$i]."/byID/\\1'>\\2</a>",$s);
            }
            //We need to handle the hooks sequentially here.
            $s = $l->triggerHookSequentially("bbcodeParse",$this,$s);
        }
        return($s);
    }

    function stripBBCode($s){
        return preg_replace("`\[(.+?)\]`is","",$s);
    }

    function limitLength($s,$length=300){
        $s=substr($s,0,$length);
        $pos=strrpos($s," ");
        if($pos>0)$s=substr($s,0,$pos);
        else{
            $pos=strrpos($s,"]");
            if($pos>0)$s=substr($s,0,$pos);
            else $s=substr($s,0,$length);
        }
        return $s;
    }

    function limitLines($s,$maxlines=20,$c="\n"){
        global $k;
        if($maxlines<2||$maxlines!="")$maxlines=20;
        if(substr_count($s,$c)<=$maxlines)return $s;
        return substr($s,0,$k->strnposr($s,$c,$maxlines)).'...'.$c."[Cut short at this point]";
    }

    function automaticLines($s,$linelength=150,$nl=""){
        if(strpos($s,"<br />")!==FALSE)$nl="<br />";else $nl="\n";
        $s=$s.$nl;
        $pointer=0;
        while($pointer!==FALSE){
            $next = strpos($s,$nl,$pointer+1);
            if($next-$pointer>$linelength&&!$next===FALSE){
                $next=$pointer+$linelength;
                $temp=strrpos(substr($s,$pointer+strlen($nl),$linelength),' ');
                if($temp!==FALSE)$next=$pointer+$temp+1;
                $s=substr($s,0,$next).$nl.substr($s,$next);
                $next+=strlen($nl);
            }
            $pointer=$next;
        }
        return $s;
    }

    function balanceTags( $text ) {
        $tagstack = array(); $stacksize = 0; $tagqueue = ''; $newtext = '';
        $single_tags = array('br', 'hr', 'img', 'input');
        $nestable_tags = array('blockquote', 'div', 'span', 'font');

        $text = str_replace('< !--', '<    !--', $text);
        # WP bug fix for LOVE <3 (and other situations with '<' before a number)
        $text = preg_replace('#<([0-9]{1})#', '&lt;$1', $text);

        while (preg_match("/<(\/?\w*)\s*([^>]*)>/",$text,$regex)) {
            $newtext .= $tagqueue;

            $i = strpos($text,$regex[0]);
            $l = strlen($regex[0]);

            $tagqueue = '';
            if ( isset($regex[1][0]) && '/' == $regex[1][0] ) { // End Tag
                $tag = strtolower(substr($regex[1],1));
                // if too many closing tags
                if($stacksize <= 0) {
                    $tag = '';
                }
                else if ($tagstack[$stacksize - 1] == $tag) { // found closing tag
                    $tag = '</' . $tag . '>';
                    array_pop ($tagstack);
                    $stacksize--;
                } else { // closing tag not at top, search for it
                    for ($j=$stacksize-1;$j>=0;$j--) {
                        if ($tagstack[$j] == $tag) {
                            for ($k=$stacksize-1;$k>=$j;$k--){
                                $tagqueue .= '</' . array_pop ($tagstack) . '>';
                                $stacksize--;
                            }
                            break;
                        }
                    }
                    $tag = '';
                }
            } else { // Begin Tag
                $tag = strtolower($regex[1]);

                // If self-closing or '', don't do anything.
                if((substr($regex[2],-1) == '/') || ($tag == '')) {
                }
                elseif ( in_array($tag, $single_tags) ) {
                    $regex[2] .= '/';
                } else {
                    // If the top of the stack is the same as the tag we want to push, close previous tag
                    if (($stacksize > 0) && !in_array($tag, $nestable_tags) && ($tagstack[$stacksize - 1] == $tag)) {
                        $tagqueue = '</' . array_pop ($tagstack) . '>';
                        $stacksize--;
                    }
                    $stacksize = array_push ($tagstack, $tag);
                }

                // Attributes
                $attributes = $regex[2];
                if($attributes) {
                    $attributes = ' '.$attributes;
                }
                $tag = '<'.$tag.$attributes.'>';
                //If already queuing a close tag, then put this tag on, too
                if ($tagqueue) {
                    $tagqueue .= $tag;
                    $tag = '';
                }
            }
            $newtext .= substr($text,0,$i) . $tag;
            $text = substr($text,$i+$l);
        }

        $newtext .= $tagqueue;
        $newtext .= $text;

        while($x = array_pop($tagstack)) {
            $newtext .= '</' . $x . '>'; // Add remaining tags to close
        }

        $newtext = str_replace("< !--","<!--",$newtext);
        $newtext = str_replace("<    !--","< !--",$newtext);

        return $newtext;
    }
}?>
