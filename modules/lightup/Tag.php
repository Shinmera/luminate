<? 
class Tag{
    var $name;
    var $tag;
    var $suite;
    var $tagcode;
    var $deftag;
    var $description;
    var $limit=-1;
    var $order=0;
    var $function=null;
    var $args=array();
    
    function __construct($tag,$deftag,$suite='default'){
        $this->name=$tag;
        $this->tag=$tag;
        $this->suite=$suite;
        $this->tagcode=$tag.'(){@}';
        $this->deftag=$deftag;
        $this->description=$tag.' tag';
        
        $this->parseDeftag($deftag);
    }
    
    function parseDeftag(){
        global $k,$lightup;
        $deftag = str_replace('$','&dollar;',$this->deftag);
        $args  = substr($deftag,strpos($deftag,'(')+1);
        $args  = substr($args,0,strpos($args,')'));
        $args  = explode(',',$args);
        $temp = strpos($deftag,')');if($temp===FALSE)$temp=0;
        $block = substr($deftag,strpos($deftag,'{',$temp)+1);
        $block = substr($block,0,strlen($block)-1);
        
        if(count($args)==0||!is_array($args))return;
        if(trim($block)=='')return;
        echo('<br />DEFT: '.$deftag);
        
        //Create arguments list
        $this->tag=$args[0];
        if(count($args)>1){
            $args = array_slice($args, 1);
            foreach($args as $arg){
                $arg=explode(' ',$arg); //NAME TYPE REQUIRED DEFAULT
                switch(count($arg)){
                    case 0:break;
                    case 1:$arg[1]='TEXT';
                    case 2:$arg[2]='false';
                    case 3:$arg[3]='';
                    default:
                        $name = $k->sanitizeString($arg[0]);
                        
                        if(!in_array(strtoupper($arg[1]),array('TEXT','STRI','URLS','MAIL','DATE','INTE'))){
                            if(substr($arg[1],0,4)!='INTE')$type='TEXT';
                            else                           $type=strtoupper($arg[1]);
                        }else $type=strtoupper($arg[1]);
                        if(in_array(strtolower($arg[2]),array('true','1','yes')))$required=TRUE;
                        else                                                      $required=FALSE;
                        $default = implode(' ',array_slice($arg,3));
                        break;
                }
                $this->args[$name]=array('name'=>$name,'type'=>$type,'required'=>$required,'default'=>$default);
            }
        }
        
        $function = '$r="";$v=&$args;
                     $v["content"]=$content;';
        //Parse the body
        //We only need to parse the special tags like div, tag, if and loop.
        //The rest can just be returned and will automatically be parsed by the main loop once its inserted.
        //Regardless, we do sadly need yet another loop, similar to the main one.
        $text = &$block;
        $pointer = 0;
        
        while($pointer<strlen($text)){
            $nextOpen = strpos($text,"{",$pointer);
            $nextClose = strpos($text,"}",$pointer);
            $curLen = strlen($text);
            
            if($nextClose==FALSE||$nextOpen==FALSE)break;
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
                        $tagStart = $lightup->findTagStart($text, $curLen, $argsStart-1); 
                        $tag =  substr($text,$tagStart ,$argsStart-$tagStart-1);
                        $args = substr($text,$argsStart,$argsEnd-$argsStart);
                        $args = explode(",",$args);
                    }
                }else{
                    $tagStart = $lightup->findTagStart($text, $curLen, $nextOpen);
                    $tag = substr($text,$tagStart,$nextOpen-$tagStart);
                }
                $tag = strtolower($tag);
                
                //Find closing tag
                $level=1;
                $endTagPos=strpos($text,'}',$nextOpen+1);
                $staTagPos=strpos($text,'{',$nextOpen+1);
                while($level>0){
                    if($staTagPos!==FALSE&&$staTagPos<$endTagPos){
                        $level++;
                        $endTagPos=strpos($text,'}',$staTagPos+2);
                        $staTagPos=strpos($text,'{',$staTagPos+2);
                    }
                    if($endTagPos!==FALSE&&($endTagPos<$staTagPos||$staTagPos===FALSE)){
                        $level--;
                        if($level!=0){
                            $endTagPos=strpos($text,'}',$endTagPos+1);
                            $staTagPos=strpos($text,'{',$endTagPos+1);
                        }else break;
                    }
                }
                $content = substr($text,$nextOpen+1,$endTagPos-$nextOpen-1);
                
                if(array_key_exists($tag,$lightup->Stags)){
                    $parsedTag=$lightup->Stags[$tag]->parse($content,$args);

                    $text = $lightup->replaceRegion($text,$tagStart,$endTagPos+1,$parsedTag);

                    if(substr($parsedTag,0,2)=='if'||substr($parsedTag,0,3)=='for') $pointer=strpos($text,'{',$tagStart)+1;
                    else $pointer=$tagStart;
                    echo('<br />TRES: '.htmlspecialchars($parsedTag));
                }else{
                    $text = $lightup->replaceRegion($text,$endTagPos,$endTagPos+1,'$r.=\'}\';');
                    $text = $lightup->replaceRegion($text,$tagStart,$argsEnd+2,'$r.=\''.$tag.'('.implode(',',$args).'){\'');
                    
                    $pointer = $tagStart+strlen('$r.=\''.$tag.'('.implode(',',$args).'){\'')+1;
                    echo('<br />TRES: '.htmlspecialchars('$r.=\''.$tag.'('.implode(',',$args).'){\''));
                }
            }else{
                $pointer++;
            }
            ob_flush();flush();
        }
        
        $function.=$text.'return $r;';
        echo('<br />FUNC: '.htmlspecialchars($function).'<br />');
        $this->function = create_function('$content,$args', $function);
        
        return TRUE;
    }
    
    function parse($content,$args){
        $args = $this->checkArguments($args);
        if($args===FALSE)return FALSE;
        
        if($this->function===null)$this->parseDeftag();
        if($this->function===FALSE)          return FALSE;
        if(!function_exists($this->function))return FALSE;
        $func = $this->function;
        $content = $func($content,$args);
        
        return $content;
    }
    
    function makeVarsInArgs(&$args){
        foreach($args as $key => $val){
            $args[$key]=$this->makeVarsInString($val);
        }
    }
    
    function makeVarsInString($str){
        return preg_replace('`\$v\[\"([-A-Z0-9]*)\"\]`is','\'.\0.\'',$str);
    }
    
    function checkArguments($arguments){
        global $k;
        $vals = array();
        $i=0;
        foreach($arguments as $arg){
            $arg = trim($arg);
            if(substr($arg,0,1)==':'){
                $key = trim(strtolower(substr($arg,1,strpos($arg,' '))));
                if(array_key_exists($key, $this->args)){
                    $arg = substr($arg,strlen($key)+2);
                    $type=$this->args[$key]['type'];
                }else{$type="TEXT";}
            }else{
                $keys = array_keys($this->args);
                $argc = $this->args[$keys[$i]];
                $key = trim($argc['name']);
                $type= $argc['type'];
           }
            $argumentOK=false;
            switch($type){
                case 'TEXT':$argumentOK=true;$arg=str_replace('{','&lbrace;',str_replace('}','&rbrace;',$arg));break;
                case 'STRI':$argumentOK=($k->sanitizeString($arg,"\-_#")==$arg);break;
                case 'URLS':$argumentOK=$k->checkURLValidity($arg);             break;
                case 'MAIL':$argumentOK=$k->checkMailVailidity($arg);           break;
                case 'DATE':$argumentOK=$k->checkDateVailidity($arg);           break;
                case 'INTE':$argumentOK=is_numeric($arg);                       break;
                default:
                    if(substr($type,0,4)=="INTE")
                            $argumentOK=(is_numeric($arg)&&(int)$arg<=(int)substr($type,4));
                    break;
            }
            
            if(!$argumentOK){
                if($this->args[$key]['required']===FALSE)$arg=$this->args[$key]['default'];
                else                                     return FALSE;
            }else            $vals[$key]=$arg;
            
            $i++;
        }
        
        foreach($this->args as $name => $arg){
            if(!array_key_exists($name, $vals)&&$arg['required']==TRUE)return FALSE;
            if(!array_key_exists($name, $vals)&&$vals[$name]=='')$vals[$name]=$arg['default'];
        }
        
        return $vals;
    }
}
?>
