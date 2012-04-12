<?
class Editor{
    var $availableTags = null;
    
    function getSimpleToolbar(){
        global $l; //TAG: array(name,title,begin,end)
        if($this->availableTags==null)
            $this->availableTags = $l->triggerSequentialHook("CORE","GETtags",array());
        echo('<ul class="toolbar">');
        foreach($this->availableTags as $tag){
            echo('<li><img title="'.$tag[1].'" class="icon" src="'.DATAPATH.'images/icons/'.$tag[0].'" /></li>');
        }
        echo('</ul>');
    }
}

class TinyEditor extends Editor{
    function __construct($postPath="#",$action="submit",$formname="editor",$unRegistered=false) {
        global $a;
        ?><form name="editor" action="<?=$postPath?>" method="post" class="editor tinyeditor">
            <? if($unRegistered&&$a->user==null){ ?>
                <label>Username: </label>   <input type="text" maxlength="32" name="username" />
                <label>Mail: </label>       <input type="email" maxlength="32" name="mail" />
            <? } ?>
            <textarea name="text"><?=$_POST['text']?></textarea>
            <input type="hidden" name="action" value="<?=$action?>" />
            <input type="submit" value="Submit" />
        </form><?
    }
}

class SimpleEditor extends Editor{
    function __construct($postPath="#",$action="submit",$formname="editor",$unRegistered=false){
        
    }
}

class FullEditor extends Editor{
    
    
}
?>
