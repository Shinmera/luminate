<?
class Editor{
    var $availableTags = null;
    
    //TAG: array(name,title,tag) 
    //Tag format: $DESCRIPTION$ Inserts a queried variable with DESCRIPTION as query text.
    //            @             Placeholder for selected text. If nothing is selected, replaced by query.
    function getSimpleToolbar($textareaname,$suites=array("default")){
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
        ?><form id="editor" action="<?=$postPath?>" method="post" class="editor tinyeditor">
            <? if($unRegistered&&$a->user==null){ ?>
                <label>Username: </label>   <input type="text" maxlength="32" name="username" />
                <label>Mail: </label>       <input type="email" maxlength="32" name="mail" />
            <? } ?>
            <textarea id="<?=$formname?>" name="text"><?=$_POST['text']?></textarea><br />
            <input type="hidden" name="action" value="<?=$action?>" />
            <input type="submit" value="Submit" />
        </form>  
        <?
    }
}

class SimpleEditor extends Editor{
    function __construct($postPath="#",$action="submit",$formname="editor"){
        ?><form id="<?=$formname?>" action="<?=$postPath?>" method="post" class="editor simpleeditor">
            <? $this->getSimpleToolbar($formname."txt"); ?>
            <textarea name="text" id="<?=$formname?>txt"><?=$_POST['text']?></textarea>
            <div id="preview" class="preview"></div><br />
            <input type="hidden" name="action" value="<?=$action?>" />
            <input type="submit" value="Submit" /><input type="submit" value="Preview" id="previewbutton" />
        </form>
        <script type="text/javascript">
            $().ready(function(){
                $("#<?=$formname?> #previewbutton").click(function(){
                    if($(this).attr("value")=="Preview"){
                        $("#<?=$formname?> textarea").css({display:"none"});
                        $("#preview").css({display:"inline-block",
                                           width:$("#<?=$formname?> textarea").width()+"px",
                                           height:$("#<?=$formname?> textarea").height()+"px"});
                        $("#preview").html("Please wait...");
                        
                        $.post("<?=PROOT?>api/parse", $("#editor").serialize(), function(data){
                            $("#preview").html(data);
                            $("#previewbutton").attr("value","Edit");
                        });
                    }else{
                        $("#<?=$formname?> textarea").css("display","inline-block");
                        $("#preview").css("display","none");
                        $("#previewbutton").attr("value","Preview");
                    }
                    return false;
                });
            });
        </script>
        <?
    }
}

class FullEditor extends Editor{
    
    
}
?>