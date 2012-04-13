<?php

class LightUp /*extends Module*/{
public static $name="LightUp";
public static $version=2.8;
public static $short='lightup';
public static $required=array();
public static $hooks=array("foo");

    var $tags = null;
    
    function __construct(){
        //$tags = DataModel::getData("lightup_tags","SELECT * FROM lightup_tags");
    }
    
    function fixslashes(&$s){
        $s = str_replace("\\'","'",$s);
        $s = str_replace('\\"','"',$s);
        $s = str_replace('\\\\','\\',$s);
    }

    function deparse($args){
        $s = $args['text'];
        $s = str_ireplace("\n","<br />",$s);
        fixslashes($s);
        if($args['formatted'])$s = parseFuncEM($s);
        if($args['allowRaw'])$s = preg_replace_callback("`\[html\](.+?)\[/html\]`is",array(&$this, 'reparseHTML'), $s);
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

    //TODO: Parse sugar tags
    //TODO: Make dynamic
    //TODO: Optimization
    function parseFuncEM($text){
        $text = " ".str_replace('$','&dollar;',trim($text)); //To prevent the opening tag from failing to be recognized
                                                             //and users from tampering with the arguments.
        if(strlen($text)==1)return $text;
        
        //Fix unbalanced brackets
        $balance=substr_count($text,"{")-substr_count($text,"}");
        if($balance>0)$text.=str_repeat("}", $balance);
        
        $endTags = array();
        $tags = array("image"   =>array("<img src='","' />"),
                      "url"     =>array('<a href="$URL$">','</a>'),
                      "b"       =>array('<strong>','</strong>'),
                      "i"       =>array('<em>','</em>'),
                      "left"    =>array('<div style="text-align:left">','</div>'),
                      "right"   =>array('<div style="text-align:right">','</div>'),
                      "center"  =>array('<div style="text-align:center">','</div>'),
                      "color"   =>array('<span style="color:$$">','</span>'));
        $nextNoparse = true;
        $pointer = 0;
        
        while($pointer<strlen($text)){
            //Check for noparse sections.
            if($nextNoparse!==FALSE){
                $nextNoparse = strpos($text,"!{",$pointer)+2;
                if($nextNoparse!==FALSE){
                    $pointer = strpos($text,"}!",$nextNoparse)+2;
                }
            }
            
            $nextOpen = strpos($text,"{",$pointer);
            $nextClose = strpos($text,"}",$pointer);
            $curLen = strlen($text);

            if($nextClose==FALSE||($nextOpen==FALSE&&count($endTags)==0))
                break;
            
            //echo("NO: ".$nextOpen." NC: ".$nextClose." P: ".$pointer." T: ".  htmlspecialchars($text)." <br />");
            if($nextOpen<$nextClose&&$nextOpen!==FALSE){
                $pointer=$nextOpen+1;
                
                $tag = "";
                $args=array();
                $argsEnd=strpos($text,")",$nextOpen-2);
                $tagStart = 0;
                //Filter out the tag name and arguments, if any.
                if($argsEnd<=$nextOpen&&$argsEnd!==FALSE){ //This means we have arguments.
                    $argsStart = strrpos($text,"(",-1*($curLen-$argsEnd))+1;
                    //To make sure that we have an unbroken arguments list.
                    if($argsStart!==FALSE){
                        $tagStart = max(array(
                            strrpos($text," ",-1*($curLen-$argsStart+1)),
                            strrpos($text,"{",-1*($curLen-$argsStart+1)),
                            strrpos($text,"\n",-1*($curLen-$argsStart+1)),
                            strrpos($text,">",-1*($curLen-$argsStart+1))
                        ))+1; 
                        $tag =  substr($text,$tagStart ,$argsStart-$tagStart-1);
                        $args = substr($text,$argsStart,$argsEnd-$argsStart);
                        $args = explode(",",$args);
                    }
                }else{
                    $tagStart = max(array(
                        strrpos($text," ",-1*($curLen-$nextOpen+1)),
                        strrpos($text,"{",-1*($curLen-$nextOpen+1)),
                        strrpos($text,"\n",-1*($curLen-$nextOpen+1)),
                        strrpos($text,">",-1*($curLen-$nextOpen+1))
                    ))+1; 
                    $tag = substr($text,$tagStart,$nextOpen-$tagStart);
                }
                
                //Parse the opening tag and insert it if it exists.
                if(array_key_exists($tag,$tags)){
                    array_push($endTags,$tags[$tag][1]);
                    $parsedTag=$this->parseStartTag($tags[$tag][0],$args);
                    $text = $this->replaceRegion($text,$tagStart,$nextOpen+1,$parsedTag);
                }
            
            //We're facing an end tag. Simply pop off the latest tag from the stack.
            }else if($nextClose!==FALSE&&count($endTags)>0){
                $pointer=$nextClose+1;
                $text = $this->replaceRegion($text,$nextClose,$nextClose+1,array_pop($endTags));
            }else{
                $pointer++;
            }
        }
        //Remove noparse tags, trim the text and we're done.
        return str_replace(array("!{","}!"),"",trim($text));
    }
    
    function replaceRegion($string,$start,$end,$insert){
        $begin = substr($string,0,$start);
        $end = substr($string,$end);
        return $begin.$insert.$end;
    }
}?>


<form action="#" method="post">
    <textarea name="text" style="width:100%;height:200px;"><?=$_POST['text']?></textarea><br />
    <input type="submit" value="Parse" />
</form>

<?
$p = new LightUp();
$time = explode(' ',microtime());
$time = $time[1]+$time[0];

$text = nl2br($p->parseFuncEM($_POST['text']));

$ntime = explode(' ',microtime());
$ntime = $ntime[1]+$ntime[0];

echo($text."<br /><br />T: ".($ntime-$time)."s");
?>