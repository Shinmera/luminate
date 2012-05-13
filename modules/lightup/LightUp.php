<?php
class LightUp  extends Module{
public static $name="LightUp";
public static $version=2.8;
public static $short='lightup';
public static $required=array();
public static $hooks=array("foo");

    var $tags = null;
    
    function __construct(){include('Tag.php');}
    
    function displayApiPage(){
        $text=$this->deparse(array("text"=>$_POST['text'],"formatted"=>true,"allowRaw"=>false));
        echo($text['text']);
    }
    function displayApiOrderPage(){
        global $l,$k,$c;$a=$l->loadModule("Auth");if(!$a->check("lightup.admin.tags"))die("Not authorized.");
        if($_POST['order']=="")die("No order received!");
        $orders = explode(",",$_POST['order']);
        foreach($orders as $order){
            $order=explode(":",$order);
            $c->query("UPDATE lightup_tags SET `order`=? WHERE name=?",array($order[1],$order[0]));
        }
        die("Order saved!");
    }
    
    function displayPanel(){
        global $k,$a;
        ?><li>LightUp
            <ul class="menu">
                <? if($a->check("lightup.admin.tags")){ ?>
                <a href="<?=$k->url("admin","LightUp/tags")?>"><li>Tag Management</li></a><? } ?>
                <? if($a->check("lightup.admin.suites")){ ?>
                <a href="<?=$k->url("admin","LightUp/suites")?>"><li>Suite Management</li></a><? } ?>
                <? if($a->check("lightup.admin.tags")){ ?>
                <a href="<?=$k->url("admin","LightUp/create")?>"><li>Tag Creator</li></a><? } ?>
            </ul>
        </li><?
    }
    
    function displayAdminPage(){
        global $params,$c,$k,$a,$MODULECACHE;
        if(!$a->check("lightup.admin.panel"))return;
        include(MODULEPATH.$MODULECACHE['LightUpAdmin']);
        switch($params[1]){
            case 'suites':displaySuitesAdminPage();break;
            case 'create':displayTagCreatorPage();break;
            default:      displayTagsAdminPage();break;
        }
    }
    
    function getTags($taglist){
        if($this->codes==null){
            $codes = DataModel::getData("lightup_tags","SELECT name,tagcode,description,suite FROM lightup_tags ORDER BY `order`,suite,tag");
            $this->codes = array();
            if($codes!=null){foreach($codes as $code){
                $this->codes[]=array($code->name,$code->description,$code->tagcode,$code->suite);
            }}
        }
        return array_merge($taglist,$this->codes);
    }
    
    function loadCode(){
        if($this->tags==null){
            $tags = DataModel::getData("lightup_tags","SELECT tag,deftag,suite,`limit` FROM lightup_tags");
            $this->tags = array();
            if($tags!=null){foreach($tags as $tag){
                $tagc = new Tag($tag->tag,$tag->deftag,$tag->suite);
                $this->tags[$tag->tag]=$tagc;
            }}
        }
    }
    
    function addTag($tag,$deftag,$suite='default'){
        $tagc = new Tag($tag,$deftag,$suite);
        $this->tags[$tag]=$tagc;
    }

    function deparse($args){
        $this->loadCode();
        
        $s = $args['text'];
        $s = str_ireplace("\n","<br />",$s);
        $s = $this->fixslashes($s);
        if($args['allowRaw']) $s = preg_replace_callback("`html\{(.+?)\}html`is",array(&$this, 'reparseHTML'), $s);
        if($args['formatted'])$s = $this->parseFuncEM($s);
        //Following regex parses urls without parsing existing ones.
        //Copied from http://stackoverflow.com/questions/287144/need-a-good-regex-to-convert-urls-to-links-but-leave-existing-links-alone
        $s = preg_replace( '`(?<![\{\}"\'>])\b(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-Z0-9+&@#/%=~_|$?!:,.]*[A-Z0-9+&@#/%=~_|$]`is',
                           '<a href="\0" target="_blank">\0'.$c->o['link_symbol'].'</a>', $s );
        $s = preg_replace( '`@([-A-Z0-9._-]*)`is', '<a href="'.Toolkit::url("user","").'\1" target="_blank">@\1</a>',$s);
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
        foreach($this->tags as $name=>$tag){
            $FEMSTRAW=$tag;
            $text=preg_replace_callback('`'.$name.'#([-A-Z0-9+&@#/%=~_|$?!:,.]+)`is',array(&$this,'parseFuncEMShortTagsCallback') , $text, $tag->limit);
        }
        unset($FEMSTRAW);
        return $text;
    }
    function parseFuncEMShortTagsCallback($matches){
        global $FEMSTRAW;
        $tag = $this->parseStartTag($FEMSTRAW);
        if($tag!==FALSE)return $tag.$matches[1];
        else            return $matches[0];
    }

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
                //FIXME: a{b{}}
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
                    
                    if($tagCounter[$tag]<=$tags[$tag]->limit||$tags[$tag]->limit<0){
                        
                        //Find closing tag
                        $level=1;
                        $endTagPos=strpos($text,'}',$nextOpen+1);
                        $staTagPos=strpos($text,'{',$nextOpen+1);
                        while($level>0){
                            if($staTagPos!==FALSE&&$staTagPos<$endTagPos){
                                $level++;
                                $endTagPos=strpos($text,'}',$staTagPos+1);
                                $staTagPos=strpos($text,'{',$staTagPos+1);
                            }
                            if($endTagPos!==FALSE&&($endTagPos<$staTagPos||$staTagPos===FALSE)){
                                $level--;
                                if($level!=0){
                                    $endTagPos=strpos($text,'}',$endTagPos+1);
                                    $staTagPos=strpos($text,'{',$endTagPos+1);
                                }
                            }
                        }
                        //Get content and parse
                        $content = substr($text,$nextOpen+1,$endTagPos-$nextOpen-1);
                        $parsedTag=$tags[$tag]->parse($content,$args);
                        
                        if($parsedTag!==FALSE){
                            $text = $this->replaceRegion($text,$tagStart,$endTagPos+1,$parsedTag);
                        }else{
                            $text = $this->replaceRegion($text,$endTagPos,$endTagPos+1,'&rbrace;');
                        }
                        $pointer=$tagStart;
                        echo('<br />REST: '.$this->replaceRegion($text,$pointer,$pointer+1,'#'));
                    }else{
                        $text = $this->replaceRegion($text,$endTagPos-1,$endTagPos,'&rbrace;');
                    }
                }
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