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
        global $k,$plevel;
        $plevel=0;
        
        $deftag = str_replace('$','&dollar;',$this->deftag);
        
        if(strpos($deftag,'(')===FALSE||strpos($deftag,')')===FALSE||
           strpos($deftag,'{')===FALSE||strpos($deftag,'}')===FALSE)return false;
        if(substr_count($deftag,'{')!=substr_count($deftag,'}'))
            throw new Exception('Brackets are unbalanced.');
        
        $args  = substr($deftag,strpos($deftag,'(')+1);
        $args  = substr($args,0,strpos($args,')'));
        $args  = explode(',',$args);
        $temp = strpos($deftag,')');if($temp===FALSE)$temp=0;
        $block = substr($deftag,strpos($deftag,'{',$temp)+1);
        $block = substr($block,0,strlen($block)-1);
        
        if(count($args)==0||!is_array($args))throw new Exception('Tag name missing.');
        if(trim($block)=='')throw new Exception('Empty body.');
        
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
                        
                        if(!in_array(strtoupper($arg[1]),array('TEXT','STRI','URLS','MAIL','DATE','INTE','BOOL'))){
                            if(substr($arg[1],0,4)!='INTE')$type='TEXT';
                            else                           $type=strtoupper($arg[1]);
                        }else $type=strtoupper($arg[1]);
                        if(in_array(strtolower($arg[2]),array('true','1','yes')))$required=TRUE;
                        else                                                     $required=FALSE;
                        $default = implode(' ',array_slice($arg,3));
                        break;
                }
                $this->args[$name]=array('name'=>$name,'type'=>$type,'required'=>$required,'default'=>$default);
            }
        }
        
        $function = 'global $_g,$a;$r="";$v=&$args;
                     $v["content"][0]=$content;'."\n";
        
        $block = $this->parseDeftagRecursively($block);
        
        $function.=$block."\n".' return $r;';
        
        //echo('<br />DEFT: '.$deftag); //DEBUG
        //echo('<br />FUNC: '.htmlspecialchars($function).'<br />'); //DEBUG
        $this->function = @create_function('$content,$args', $function);
        if($this->function===FALSE)throw new Exception(htmlspecialchars ($function));
        
        return TRUE;
    }
    
    function parseDeftagRecursively($text){
        global $lightup,$plevel;$plevel++;
        if(substr_count($text,'{')!=substr_count($text,'}'))throw new Exception('BAILOUT!');
        if(substr_count($text,'{')==0)return trim($text);
        
        $final = '';
        $pointer = 0;
        while($pointer<strlen($text)){
            $nextOpen = strpos($text,"{",$pointer);
            $nextClose = strpos($text,"}",$pointer);
            $curLen = strlen($text);
            
            if($nextClose==FALSE||$nextOpen==FALSE)break;
            if($nextOpen<$nextClose&&$nextOpen!==FALSE){
                $pointer=$nextOpen+1;
                
                $tag = '';
                $args=array();
                $argsEnd=strpos($text,')',$nextOpen-2);
                $tagStart = 0;
                //Filter out the tag name and arguments, if any.
                if($argsEnd<=$nextOpen&&$argsEnd!==FALSE){
                    $argsStart = strrpos($text,'(',-1*($curLen-$argsEnd))+1;
                    if($argsStart!==FALSE){
                        $tagStart = $lightup->findTagStart($text, $curLen, $argsStart-1); 
                        $tag =  substr($text,$tagStart ,$argsStart-$tagStart-1);
                        $args = substr($text,$argsStart,$argsEnd-$argsStart);
                        $args = explode(",",$args);
                    }
                }else{
                    $tagStart = $lightup->findTagStart($text, $curLen, $nextOpen-1);
                    $tag = substr($text,$tagStart,$nextOpen-$tagStart);
                }
                $tag = strtolower($tag);
                
                $endTagPos = $lightup->findClosingTag($text, $nextOpen);
                $content = substr($text,$nextOpen+1,$endTagPos-$nextOpen-1);
                $content = $this->parseDeftagRecursively(' '.$content);
                
                if(array_key_exists($tag,$lightup->Stags)){
                    $parsedTag=$lightup->Stags[$tag]->parse($content,$args);
                    
                    if($tag!='get'){$final.=$parsedTag;} //FIXME: Cheap fix to simply check for no-get... find a proper fix sometime.
                    $text = $lightup->replaceRegion($text,$tagStart,$endTagPos+1,$parsedTag);

                    $pointer = $tagStart+strlen($parsedTag);
                    //echo('<br />'.str_repeat('&nbsp;',$plevel*2).'RESP: '.htmlspecialchars($parsedTag)); //DEBUG
                }else{
                    $parsedTag = $lightup->Stags['echo']->parse(' '.$tag.'('.implode(',',$args).'){','').$content.' $r.=\'}\';';
                    $final.=$parsedTag;
                    
                    $pointer = $tagStart+strlen($parsedTag);
                    //echo('<br />'.str_repeat('&nbsp;',$plevel*2).'RESF: '.htmlspecialchars($parsedTag)); //DEBUG
                }
            }else{
                $pointer++;
            }
        }$plevel--;
        if($final!='')return trim($final);
        else          return trim($text);
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
        $str = preg_replace('`\$_g\[\"(\*[-A-Z0-9]*)\"\]\[[0-9]*\]`is','\'.\0.\'',$str);
        return preg_replace('`\$v\[\"([-A-Z0-9]*)\"\]\[[0-9]*\]`is','\'.\0.\'',$str);
    }
    
    function checkArguments($arguments){
        global $k;
        if(count($this->args)==0)return array();
        
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
                case 'STRI':$argumentOK=($k->sanitizeString($arg,'\-_#${}[]"')==$arg);break;
                case 'URLS':$argumentOK=$k->checkURLValidity($arg);             break;
                case 'MAIL':$argumentOK=$k->checkMailVailidity($arg);           break;
                case 'DATE':$argumentOK=$k->checkDateVailidity($arg);           break;
                case 'INTE':$argumentOK=is_numeric($arg);                       break;
                case 'BOOL':$argumentOK=($arg=='0'||$arg=='1'||$arg=='true'||$arg=='false');break;
                default:
                    if(substr($type,0,4)=="INTE")
                            $argumentOK=(is_numeric($arg)&&(int)$arg<=(int)substr($type,4));
                    break;
            }
            
            if(!$argumentOK){
                if($this->args[$key]['required']===FALSE)$arg=$this->args[$key]['default'];
                else                                     return FALSE;
            }else            $vals[$key][0]=$arg;
            
            $i++;
        }
        
        foreach($this->args as $name => $arg){
            if(!array_key_exists($name, $vals)&&$arg['required']==TRUE)return FALSE;
            if(!array_key_exists($name, $vals)&&$vals[$name]=='')$vals[$name][0]=$arg['default'];
        }
        
        return $vals;
    }
}
?>
