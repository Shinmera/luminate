<?php

class LightUp /*extends Module*/{
public static $name="LightUp";
public static $version=2.8;
public static $short='lightup';
public static $required=array();
public static $hooks=array("foo");

    var $tags = array("image"   =>array('<img src="','" />',1),
                      "url"     =>array('<a href="$URL$">','</a>',10),
                      "b"       =>array('<strong>','</strong>',-1),
                      "i"       =>array('<em>','</em>',-1),
                      "left"    =>array('<div style="text-align:left">','</div>',-1),
                      "right"   =>array('<div style="text-align:right">','</div>',-1),
                      "center"  =>array('<div style="text-align:center">','</div>',-1),
                      "color"   =>array('<span style="color:$$pt">','</span>',-1),
                      "size"    =>array('<span style="font-size:$$">','</span>',-1));
    
    function __construct(){
        //$tags = DataModel::getData("lightup_tags","SELECT * FROM lightup_tags");
    }
    
    function fixslashes($s){
        $s = str_replace("\\'","'",$s);
        $s = str_replace('\\"','"',$s);
        $s = str_replace('\\\\','\\',$s);
        return $s;
    }

    function deparse($args){
        $s = $args['text'];
        $s = str_ireplace("\n","<br />",$s);
        $s = $this->fixslashes($s);
        if($args['allowRaw']) $s = preg_replace_callback("`html\{(.+?)\}html`is",array(&$this, 'reparseHTML'), $s);
        if($args['formatted'])$s = $this->parseFuncEM($s);
        //Following regex parses urls without parsing existing <a>s.
        //Copied from http://stackoverflow.com/questions/287144/need-a-good-regex-to-convert-urls-to-links-but-leave-existing-links-alone
        $s = preg_replace( '`(?<![\{\}"\'>])\b(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-Z0-9+&@#/%=~_|$?!:,.]*[A-Z0-9+&@#/%=~_|$]`is', '<a href="\0" target="_blank">\0</a>', $s );
        $args['text']=$s;
        return $args;
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
    
    
    //We use this instead of a split since we need to parse this sequentially.
    function getParts($text,$delim){
        $parts=array();
        $parts[0] = substr($text,0,strpos($text,$delim));
        $text =     substr($text,strpos($text,$delim)+1);
        $parts[1] = substr($text,0,strpos($text,$delim));
        $parts[2] = substr($text,strpos($text,$delim)+1);
        return $parts;
    }
    
    //This function parses a beginning tag with the included text into what we need.
    function parseStartTag($tagRaw,$arguments=array()){
        if(strpos($tagRaw,"$")!==FALSE){
            $parts = $this->getParts($tagRaw,"$");
            $tagRaw=$parts[0].$arguments[0].$parts[2];
            /*if($parts[1]!=="")
            else{
                switch($parts[1]){
                    //types, etc. If not matched, default to abort parsing and return the base shit.
                }
            }*/
            array_splice($arguments, 1); //Pop one element from the stack.
            
            return $this->parseStartTag($tagRaw,$arguments); //Needs more iterations.
        }else return $tagRaw;
    }
    
    function fixBracketBalance($text,$bA="{",$bB="}"){
        $balance=substr_count($text,$bA)-substr_count($text,$bB);
        if($balance>0)$text.=str_repeat($bB, $balance);
        return $text;
    }
    
    function findTagStart($text,$curLen,$open){
        return max(array(
            strrpos($text," ",-1*($curLen-$open+1)),
            strrpos($text,"{",-1*($curLen-$open+1)),
            strrpos($text,"\n",-1*($curLen-$open+1)),
            strrpos($text,">",-1*($curLen-$open+1))
        ))+1;
    }

    //TODO: Parse sugar tags
    //TODO: Make dynamic
    //TODO: Optimization
    function parseFuncEM($text){
        $text = " ".str_replace('$','&dollar;',trim($text)); //To prevent the opening tag from failing to be recognized
                                                             //and users from tampering with the arguments.
        if(strlen($text)==1)return $text;
        
        $text = fixBracketBalance($text);
        
        $endTags = array();
        $tagCounter = array();
        $tags = &$this->tags;
        $nextNoparse = true;
        $pointer = 0;
        
        while($pointer<strlen($text)){
            $nextOpen = strpos($text,"{",$pointer);
            $nextClose = strpos($text,"}",$pointer);
            $curLen = strlen($text);
            
            //Check for noparse sections.
            if($nextNoparse!==FALSE){
                $nextNoparse = strpos($text,"!{",$pointer);
                if($nextNoparse!==FALSE&&$nextNoparse==$nextOpen-1){
                    $nextNoparse+=2;
                    $pointer = strpos($text,"}!",$nextNoparse)+2;
                    $nextOpen = strpos($text,"{",$pointer);
                    $nextClose = strpos($text,"}",$pointer);
                }
            }

            if($nextClose==FALSE||($nextOpen==FALSE&&count($endTags)==0))break;
            
            if($nextOpen<$nextClose&&$nextOpen!==FALSE){
                $pointer=$nextOpen+1;
                
                $tag = "";
                $args=array();
                $argsEnd=strpos($text,")",$nextOpen-2);
                $tagStart = 0;
                //Filter out the tag name and arguments, if any.
                if($argsEnd<=$nextOpen&&$argsEnd!==FALSE){
                    $argsStart = strrpos($text,"(",-1*($curLen-$argsEnd))+1;
                    if($argsStart!==FALSE){
                        $tagStart = $this->findTagStart($text, $curLen, $tagStart); 
                        $tag =  substr($text,$tagStart ,$argsStart-$tagStart-1);
                        $args = substr($text,$argsStart,$argsEnd-$argsStart);
                        $args = explode(",",$args);
                    }
                }else{
                    $tagStart = $this->findTagStart($text, $curLen, $nextOpen);
                    $tag = substr($text,$tagStart,$nextOpen-$tagStart);
                }
                
                if(array_key_exists($tag,$tags)){
                    if(!array_key_exists($tag,$tagCounter))$tagCounter[$tag]=1;
                    else                                   $tagCounter[$tag]++;
                    
                    if($tagCounter[$tag]<=$tags[$tag][2]||$tags[$tag][2]<0){
                        array_push($endTags,$tags[$tag][1]);
                        $parsedTag=$this->parseStartTag($tags[$tag][0],$args);
                        $text = $this->replaceRegion($text,$tagStart,$nextOpen+1,$parsedTag);
                    }else{
                        //If the limit is exceeded, push a simple closing tag to prevent the stack from being screwed up.
                        array_push($endTags,"&rbrace;");
                    }
                }
            
            }else if($nextClose!==FALSE&&count($endTags)>0){
                $pointer=$nextClose+1;
                $text = $this->replaceRegion($text,$nextClose,$nextClose+1,array_pop($endTags));
            }else{
                $pointer++;
            }
        }
        
        return str_replace(array("!{","}!"),"",trim($text));
    }
    
    function replaceRegion($string,$start,$end,$insert){
        $begin = substr($string,0,$start);
        $end = substr($string,$end);
        return $begin.$insert.$end;
    }
    
    function insertAt($string,$pos,$insert){
        $begin = substr($string,0,$pos);
        $end = substr($string,$pos);
        return $begin.$insert.$end;
    }
}?>


<form action="#" method="post">
    <textarea name="text" style="width:100%;height:100px;"><?=$_POST['text']?></textarea><br />
    <input type="submit" value="Parse" />
</form>

<?
$p = new LightUp();
$time = explode(' ',microtime());
$time = $time[1]+$time[0];

$text = $p->deparse(array("text"=>$_POST['text'],"formatted"=>true,"allowRaw"=>false));

$ntime = explode(' ',microtime());
$ntime = $ntime[1]+$ntime[0];

echo($text['text']."<br /><br />T: ".($ntime-$time)."s");
?>