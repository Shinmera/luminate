<?
class Editor{
    var $availableTags = null;
    var $extrafields = array();
    var $postPath = "#";
    var $action = "submit";
    var $formname = "editor";
    var $suites = array("default","plus");
    var $style = "";
    
    function __construct($postPath="#",$action="submit",$formname="editor",$suites=array("default","plus")){
        $this->postPath=$postPath;$this->action=$action;
        $this->formname=$formname;$this->suites=$suites;
    }
    
    function addTextField($name,$label="",$value="",$type="text",$arguments="",$style=""){
        if($label!=="")$label='<label>'.$label.'</label>';
        $this->extrafields[]=$label.'<input type="'.$type.'" name="'.$name.'" value="'.$value.'" style="'.$style.'" '.$arguments.' />';
    }
    
    function addCheckbox($name,$label,$checked=false,$arguments="",$style=""){
        if($checked)$checked='checked="checked"';else $checked="";
        $this->extrafields[]='<input type="checkbox" name="'.$name.'" stlye="'.$style.'" '.$checked.' '.$arguments.' />'.$label;
    }
    
    function addDropDown($name,$choices,$label="",$selected="",$arguments="",$style=""){
        if($label!=="")$label='<label>'.$label.'</label>';
        $select=$label.'<select name="'.$name.'" style="'.$style.'" '.$arguments.' >';
        foreach($choices as $c){
            if($c==$selected)$selected='selected="selected"';else $selected='';
            $select.='<option value="'.$c.'" '.$selected.' />';
        }
        $this->extrafields[]=$select.'</select>';
    }
    
    function addCustom($html){
        $this->extrafields[]=$html;
    }
    
    //TAG: array(name,title,tag) 
    //Tag format: $DESCRIPTION$ Inserts a queried variable with DESCRIPTION as query text.
    //            @             Placeholder for selected text. If nothing is selected, replaced by query.
    function getSimpleToolbar(){
        global $l; 
        if($this->availableTags==null)
            $this->availableTags = $l->triggerHookSequentially("GETtags","CORE",array());
        
        echo('<ul class="toolbar">');
        foreach($this->availableTags as $tag){
            if(in_array($tag[3],$this->suites))
                echo('<li><img title="'.$tag[1].'" alt="'.$tag[0].'" class="icon" src="'.DATAPATH.'images/icons/'.$tag[0].'.png" tag="'.$tag[2].'" /></li>');
        }
        echo('</ul>');
        ?><script type="text/javascript">
            $().ready(function(){
                $(".toolbar .icon").each(function(){
                    $(this).click(function(){
                        insertAdv($("#<?=$this->formname?>txt"),$(this).attr("tag"));
                    });
                });
            });
        </script><?
    }
}

class TinyEditor extends Editor{
    function show($unRegistered=false) {
        global $a;
        ?><form id="editor" action="<?=$this->postPath?>" method="post" class="editor tinyeditor">
            <? if($unRegistered&&$a->user==null){ ?>
                <label>Username: </label>   <input type="text" maxlength="32" name="username" required />
                <label>Mail: </label>       <input type="email" maxlength="32" name="mail" required />
            <? } ?>
            <?=implode("",$this->extrafields)?>
            <textarea id="<?=$this->formname?>" name="text" required ><?=$_POST['text']?></textarea><br />
            <input type="hidden" name="action" value="<?=$this->action?>" />
            <input type="submit" value="Submit" />
        </form>  
        <?
    }
}

class SimpleEditor extends Editor{
    function show(){
        ?><form id="<?=$this->formname?>" action="<?=$this->postPath?>" method="post" class="editor simpleeditor" style="<?=$this->style?>">
            <?=implode("<br />",$this->extrafields)?>
            <? $this->getSimpleToolbar(); ?>
            <textarea name="text" id="<?=$this->formname?>txt" required><?=$_POST['text']?></textarea>
            <div id="preview" class="preview"></div><br />
            <input type="hidden" name="action" value="<?=$this->action?>" />
            <input type="submit" value="Submit" /><input type="submit" value="Preview" id="previewbutton" />
        </form>
        <script type="text/javascript">
            $().ready(function(){
                $("#<?=$this->formname?> #previewbutton").click(function(){
                    if($(this).attr("value")=="Preview"){
                        $("#<?=$this->formname?> textarea").css({display:"none"});
                        $("#preview").css({display:"inline-block",
                                           width:$("#<?=$this->formname?> textarea").width()+"px",
                                           height:$("#<?=$this->formname?> textarea").height()+"px"});
                        $("#preview").html("Please wait...");
                        
                        $.post("<?=PROOT?>api/parse", $("#editor").serialize(), function(data){
                            $("#preview").html(data);
                            $("#previewbutton").attr("value","Edit");
                        });
                    }else{
                        $("#<?=$this->formname?> textarea").css("display","inline-block");
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
?>