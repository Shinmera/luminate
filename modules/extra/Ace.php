<? class Ace{
public static $name="Ace";
public static $author="NexT";
public static $version=1.00;
public static $short='ace';
public static $required=array();
public static $hooks=array("foo");

function getAceEditor($formname,$mode,$content="",$style=""){
    ?>
    <div name="contents" id="editor" style="<?=$style?>"><?=htmlspecialchars($content)?></div>
    <textarea name="<?=$formname?>" id="editorHolder"style="display:none;"></textarea>
    <div style="<?=$style?>">&nbsp;</div>

    <script src="<?=DATAPATH?>js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?=DATAPATH?>js/ace/mini_require.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?=DATAPATH?>js/ace/mode-<?=$mode?>.js" type="text/javascript" charset="utf-8"></script>
    <script>
    var editor;
    var ace = window.__ace_shadowed__;
    $(document).ready(function() {
        editor = ace.edit("editor");
        var mode = ace.require("ace/mode/<?=$mode?>").Mode;
        editor.getSession().setMode(new mode());
        
    });
    function transferToTextarea(){
        $("#editorHolder").html(editor.getSession().getValue());
    }
    </script>
    <?
}

}
?>
