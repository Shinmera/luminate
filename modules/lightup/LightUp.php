<?php
class LightUp /*extends Module*/{
public static $name="LightUp";
public static $version=2.8;
public static $short='lightup';
public static $required=array();
public static $hooks=array("foo");

    var $tagsLoaded = false;
    var $tags = array("image"   =>array('<img alt="$STRI|image$" title="$TEXT|$" class="$STRI|$" src="','" />',2),
                      "url"     =>array('<a href="$URLS$" title="$TEXT|$" target="$STRI|_self$" >','</a>',10),
                      "b"       =>array('<strong>','</strong>',-1),
                      "i"       =>array('<em>','</em>',-1),
                      "u"       =>array('<u>','</u>',-1),
                      "left"    =>array('<div style="text-align:left">','</div>',-1),
                      "right"   =>array('<div style="text-align:right">','</div>',-1),
                      "center"  =>array('<div style="text-align:center">','</div>',-1),
                      "color"   =>array('<span style="color:$STRI|red$">','</span>',-1),
                      "size"    =>array('<span style="font-size:$INTE48$pt">','</span>',-1));
    var $codesLoaded = false;
    var $codes= array(
                      array("b",      'Bold text',            'b{@}',                       'default'),
                      array("i",      'Italic text',          'i{@}',                       'default'),
                      array("u",      'Underline text',       'u{@}',                       'default'),
                      array("size",   'Change text size',     'size($Enter the font size (0-48)|number$){@}','plus'),
                      array("color",  'Color text',           'color($Enter a color|color$){@}','plus'),
                      array("left",   'Align left',           'left{@}',                    'plus'),
                      array("right",  'Align right',          'right{@}',                   'plus'),
                      array("center", 'Center text',          'center{@}',                  'plus'),
                      array("url",    'Insert a link',        'url($Enter the URL|url$){@}','plus'),
                      array("image",  'Insert an image',      'image{@}',                   'plus'),
                      array("noparse",'Avoid parsing',        '!{@}!',                      'pro'));
    
    function __construct(){}
    
    function displayApiPage(){
        $text=$this->deparse(array("text"=>$_POST['text'],"formatted"=>true,"allowRaw"=>false));
        echo($text['text']);
    }
    
    function displayPanel(){
        global $k,$a;
        ?><div class="box">
            <div class="title">LightUp</div>
            <ul class="menu">
                <? if($a->check("lightup.admin.tags")){ ?>
                <a href="<?=$k->url("admin","LightUp/tags")?>"><li>Tag Management</li></a><? } ?>
                <? if($a->check("lightup.admin.suites")){ ?>
                <a href="<?=$k->url("admin","LightUp/suites")?>"><li>Suite Management</li></a><? } ?>
                <? if($a->check("lightup.admin.tags")){ ?>
                <a href="<?=$k->url("admin","LightUp/create")?>"><li>Tag Creator</li></a><? } ?>
            </ul>
        </div><?
    }
    
    function displayAdminPage(){
        global $params,$c,$k,$a,$MODULECACHE;
        if(!$a->check("lightup.admin.panel"))return;
        include(MODULEPATH.$MODULECACHE['LightUpAdmin']);
        $this->displayPanel();
        switch($params[1]){
            case 'suites':displaySuitesAdminPage();break;
            case 'create':displayTagCreatorPage();break;
            default:      displayTagsAdminPage();break;
        }
    }
    
    function getTags($taglist){
        if(/*$this->codes==null*/$this->codesLoaded==false){
            $codes = DataModel::getData("lightup_tags","SELECT name,tagcode,description,suite FROM lightup_tags");
            if($codes!=null){foreach($codes as $code){
                $this->codes[]=array($code->name,$code->description,$code->tagcode,$code->suite);
            }}
            $this->codesLoaded=true;
        }
        return array_merge($taglist,$this->codes);
    }

    function deparse($args){
        if(/*$this->tags==null*/$this->tagsLoaded==false){
            $tags = DataModel::getData("lightup_tags","SELECT tag,femcode,`limit` FROM lightup_tags");
            if($tags!=null){foreach($tags as $tag){
                $femcodeparts = explode("@",str_replace("&lt;","<",str_replace("&gt;",">",$tag->femcode)));
                $this->tags[$tag->tag]=array($femcodeparts[0],$femcodeparts[1],$tag->limit);
            }}
            $this->tagsLoaded=true;
        }
        
        $s = $args['text'];
        $s = str_ireplace("\n","<br />",$s);
        $s = $this->fixslashes($s);
        if($args['allowRaw']) $s = preg_replace_callback("`html\{(.+?)\}html`is",array(&$this, 'reparseHTML'), $s);
        if($args['formatted'])$s = $this->parseFuncEM($s);
        //Following regex parses urls without parsing existing ones.
        //Copied from http://stackoverflow.com/questions/287144/need-a-good-regex-to-convert-urls-to-links-but-leave-existing-links-alone
        $s = preg_replace( '`(?<![\{\}"\'>])\b(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-Z0-9+&@#/%=~_|$?!:,.]*[A-Z0-9+&@#/%=~_|$]`is', '<a href="\0" target="_blank">\0</a>', $s );
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
    //Return FALSE if the arguments are invalid.
    function parseStartTag($tagRaw,$arguments=array()){
        global $k;
        
        if(strpos($tagRaw,"$")!==FALSE){
            $parts = $this->getParts($tagRaw,"$");
            
            if($parts[1]=="")$tagRaw=$parts[0].$arguments[0].$parts[2];
            else{
                if(strpos($parts[1],"|")!==FALSE){
                    $parts[1]=explode("|",$parts[1]);
                    if($arguments[0]=="")$arguments[0]=$parts[1][1]; //Default value
                    $parts[1]=$parts[1][0];
                }
                $argumentOK=false;
                switch($parts[1]){
                    case 'TEXT':$argumentOK=true;break;
                    case 'STRI':$argumentOK=($k->sanitizeString($arguments[0],"\-_")==$arguments[0]);break;
                    case 'URLS':$argumentOK=$k->checkURLValidity($arguments[0]);break;
                    case 'MAIL':$argumentOK=$k->checkMailVailidity($arguments[0]);break;
                    case 'DATE':$argumentOK=$k->checkDateVailidity($arguments[0]);break;
                    case 'INTE':$argumentOK=is_numeric($arguments[0]);break;
                    default:
                        if(substr($parts[1],0,4)=="INTE")
                                $argumentOK=(is_numeric($arguments[0])&&(int)$arguments[0]<=(int)substr($parts[1],4));
                        break;
                }
                if($argumentOK)$tagRaw=$parts[0].$arguments[0].$parts[2];
                else           return FALSE;
            }
            $arguments = array_splice($arguments, 1); //Pop one element from the stack.
            
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
    
    function parseFuncEMShortTags($text){
        global $FEMSTRAW;
        foreach($this->tags as $tag=>$raw){
            $FEMSTRAW=$raw;
            $text=preg_replace_callback('`'.$tag.'#([-A-Z0-9+&@#/%=~_|$?!:,.]+)`is',array(&$this,'parseFuncEMShortTagsCallback') , $text, $raw[2]);
        }
        unset($FEMSTRAW);
        return $text;
    }
    function parseFuncEMShortTagsCallback($matches){
        global $FEMSTRAW;
        $tag = $this->parseStartTag($FEMSTRAW[0]);
        if($tag!==FALSE)return $tag.$matches[1].$FEMSTRAW[1];
        else            return $matches[0];
    }

    //TODO: Optimization
    function parseFuncEM($text){
        $text = " ".str_replace('$','&dollar;',trim($text)); //To prevent the opening tag from failing to be recognized
                                                             //and users from tampering with the arguments.
        if(strlen($text)==1)return $text;
        
        $text = $this->fixBracketBalance($text);
        $text = $this->parseFuncEMShortTags($text);
        
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
                if($nextNoparse!==FALSE&&$nextNoparse==$nextOpen-1&&$nextNoparse<$nextClose){
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
                        $tagStart = $this->findTagStart($text, $curLen, $argsStart); 
                        $tag =  substr($text,$tagStart ,$argsStart-$tagStart-1);
                        $args = substr($text,$argsStart,$argsEnd-$argsStart);
                        $args = explode(",",$args);
                    }
                }else{
                    $tagStart = $this->findTagStart($text, $curLen, $nextOpen);
                    $tag = substr($text,$tagStart,$nextOpen-$tagStart);
                }
                $tag = strtolower($tag);
                
                if(array_key_exists($tag,$tags)){
                    if(!array_key_exists($tag,$tagCounter))$tagCounter[$tag]=1;
                    else                                   $tagCounter[$tag]++;
                    
                    if($tagCounter[$tag]<=$tags[$tag][2]||$tags[$tag][2]<0){
                        $parsedTag=$this->parseStartTag($tags[$tag][0],$args);
                        
                        if($parsedTag!==FALSE){
                            array_push($endTags,$tags[$tag][1]);
                            $text = $this->replaceRegion($text,$tagStart,$nextOpen+1,$parsedTag);
                        }else{
                            array_push($endTags,"&rbrace;");
                        }
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