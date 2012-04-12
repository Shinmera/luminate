<?
class Editor{
    var $availableTags = null;
    
    //TAG: array(name,title,tag) 
    //Tag format: $DESCRIPTION$ Inserts a queried variable with DESCRIPTION as query text.
    //            @             Placeholder for selected text. If nothing is selected, replaced by query.
    function getSimpleToolbar($textareaname){
        global $l; 
        if($this->availableTags==null)
            $this->availableTags = $l->triggerHookSequentially("GETtags","CORE",array());
        echo('<ul class="toolbar">');
        foreach($this->availableTags as $tag){
            echo('<li><img title="'.$tag[1].'" alt="'.$tag[0].'" class="icon" src="'.DATAPATH.'images/icons/'.$tag[0].'.png" tag="'.$tag[2].'" /></li>');
        }
        echo('</ul>');
        ?><script type="text/javascript">
            $().ready(function(){
                $(".toolbar .icon").each(function(){
                    $(this).click(function(){
                        insertAdv($("#<?=$textareaname?>"),$(this).attr("tag"));
                    });
                });
            });
        </script><?
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
            <textarea id="<?=$formname?>" name="text"><?=$_POST['text']?></textarea><br />
            <input type="hidden" name="action" value="<?=$action?>" />
            <input type="submit" value="Submit" />
        </form><?
    }
}

class SimpleEditor extends Editor{
    function __construct($postPath="#",$action="submit",$formname="editor"){
        ?><form name="editor" action="<?=$postPath?>" method="post" class="editor tinyeditor">
            <? $this->getSimpleToolbar($formname); ?>
            <textarea id="<?=$formname?>" name="text"><?=$_POST['text']?></textarea><br />
            <input type="hidden" name="action" value="<?=$action?>" />
            <input type="submit" value="Submit" />
        </form><?
    }
}

class FullEditor extends Editor{
    
    
}
?>