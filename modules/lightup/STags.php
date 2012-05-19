<?
class DIVTag extends Tag{
    function parse($content,$args){
        $this->args = array('class'=>array('name'=>'class','type'=>'TEXT','required'=>false,'default'=>''),
                            'style'=>array('name'=>'style','type'=>'TEXT','required'=>false,'default'=>''));
        $args = $this->checkArguments($args);
        if($args===FALSE)return FALSE;
        $this->makeVarsInArgs($args);
        
        $content = '$r.=\'<div class="'.$args['class'].'" style="'.$args['style'].'">\';'.$content.'$r.=\'</div>\';';
        return $content;
    }
}

class TAGTag extends Tag{
    function parse($content,$args){
        $this->args = array('tag'=>array('name'=>'tag','type'=>'STRI','required'=>true,'default'=>''),
                            'class'=>array('name'=>'class','type'=>'TEXT','required'=>false,'default'=>''),
                            'style'=>array('name'=>'style','type'=>'TEXT','required'=>false,'default'=>''),
                            'extra'=>array('name'=>'extra','type'=>'TEXT','required'=>false,'default'=>''));
        $args = $this->checkArguments($args);
        if($args===FALSE)return FALSE;
        $this->makeVarsInArgs($args);
        
        if(trim($content)=='')
            $content='$r.=\'<'.$args['tag'].' class="'.$args['class'].'" style="'.$args['style'].'" '.$args['extra'].' />\';';
        else
            $content='$r.=\'<'.$args['tag'].' class="'.$args['class'].'" style="'.$args['style'].'" '.$args['extra'].' >\';'.$content.'$r.=\'</'.$args['tag'].'>\';';
        return $content;
    }
}

class IFTag extends Tag{
    function parse($content,$args){
        switch(count($args)){
            case 0:return FALSE;break;
            case 1:$args[1]='TRUE';
            case 2:$args[2]='==';
            case 3:break;
            default:return FALSE;break;
        }
        switch($args[2]){
            case '==':
            case '!=':
            case '<=':
            case '>=':
            case '>':
            case '<':
                break;
            case 'like':
                $args[0]='strtolower(trim('.$args[0].'))';
                $args[1]='strtolower(trim('.$args[1].'))';
                $args[2]='==';
                break;
            case '!like':
                $args[0]='strtolower(trim('.$args[0].'))';
                $args[1]='strtolower(trim('.$args[1].'))';
                $args[2]='!=';
                break;
            default:
                return FALSE;
        }
        $content='if('.$args[0].' '.$args[2].' '.$args[1].'){ '.$content.' }';
        return $content;
    }
}

class LOOPTag extends Tag{
    function parse($content,$args){
        global $k;
        switch(count($args)){
            case 0:return FALSE;break;
            case 1:$args[1]=$args[0];$args[0]=0;
            case 2:$args[2]=1;
            case 3:$args[3]='loop';
            default:
                if(!is_numeric($args[0])&&substr($args[0],0,4)!='$v["')return FALSE;
                if(!is_numeric($args[1])&&substr($args[1],0,4)!='$v["')return FALSE;
                if(!is_numeric($args[2])&&substr($args[2],0,4)!='$v["')return FALSE;
                $args[3]=$k->sanitizeString($args[3]);if($args[3]=='') return FALSE;
                break;
        }
        $content = 'for($v["'.$args[3].'"]= '.$args[0].' ;$v["'.$args[3].'"]< '.$args[1].' ;$v["'.$args[3].'"]+= '.$args[2].' ){ '.$content.' }';
        return $content;
    }
}

class SETTag extends Tag{
    function parse($content,$args){
        if(substr($content,0,4)=='get{')
                return '$v["'.$args[0].'"]='.$content.';';
        return '$v["'.$args[0].'"]=\''.$content.'\';';
    }
}

class GETTag extends Tag{
    function parse($content,$args){
        if(trim($content)=='')return FALSE;
        return '$v["'.$content.'"]';
    }
}

class PRINTTag extends Tag{
    function parse($content,$args){
        if(trim($content)=='')return FALSE;
        return '$r.= $v["'.$content.'"];';
    }
}

class ECHOTag extends Tag{
    function parse($content,$args){
        if(trim($content)=='')return FALSE;
        $content = $this->makeVarsInString($content);
        return '$r.=\''.$content.'\';';
    }
}
?>