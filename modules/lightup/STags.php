<?
class DIVTag extends Tag{
    function parse($content,$args){
        $this->args = array('class'=>array('name'=>'class','type'=>'TEXT','required'=>false,'default'=>''),
                            'style'=>array('name'=>'style','type'=>'TEXT','required'=>false,'default'=>''));
        $args = $this->checkArguments($args);
        if($args===FALSE)return FALSE;
        $content = '$r.=\'<div class="'.$args['class'].'" style="'.$args['style'].'">'.$content.'</div>\';';
        
        return preg_replace('`get\{([-A-Z0-9]*)\}`is','\'.\0.\'',$content);
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
        if(trim($content)=='')
            $content='$r.=\'<'.$args['tag'].' class="'.$args['class'].'" style="'.$args['style'].'" '.$args['extra'].' />\';';
        else
            $content='$r.=\'<'.$args['tag'].' class="'.$args['class'].'" style="'.$args['style'].'" '.$args['extra'].' >'.$content.'</'.$args['tag'].'>\';';
    
        return preg_replace('`get\{([-A-Z0-9]*)\}`is','\'.\0.\'',$content);
    }
}

class IFTag extends Tag{
    function parse($content,$args){
        
    }
}

class LOOPTag extends Tag{
    function parse($content,$args){
        
    }
}

class SETTag extends Tag{
    function parse($content,$args){
        return '$v["'.$args[0].'"]="'.$content.'";';
    }
}

class GETTag extends Tag{
    function parse($content,$args){
        return '$v["'.$content.'"]';
    }
}

class ECHOTag extends Tag{
    function parse($content,$args){
        $content = '$r.=\''.str_replace("'",'&apos;',$content).'\';';
        return preg_replace('`get\{([-A-Z0-9]*)\}`is','\'.\0.\'',$content);
    }
}
?>