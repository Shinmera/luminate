<?
class Admin extends Module{
public static $name="Admin";
public static $version=2.01;
public static $short='acp';
public static $required=array("Themes","Auth");
    
function __construct(){
}

function displayPage($params){
    global $a,$t,$site;
    if($site=="index")$site="Panel";
    $t->openPage("Administration");
    $this->displayNavbar();
    
    if($a->check('admin.panel')){
        switch($site){
            case 'Options': $this->displayOptionsPage();break;
            case 'Log':     $this->displayLogPage();break;
            case 'Modules': $this->displayModulesPage();break;
            case 'Hooks':   $this->displayHooksPage();break;
            default:        $this->displayPanelPage();break;
        }
    }else{
        echo("<center>You are not authorized to view this page.</center>");
    }
    $t->closePage();
}

function displayNavbar(){
    global $site,$k;
    $pages=array('Panel','Options','Log','Modules','Hooks');
    ?><div id='pageNav'>
        <div class="description">Administration</div>
        <div class="tabs">
            <? foreach($pages as $page){
                if($page==$site)echo('<a href="'.$k->url("admin",$page).'" class="tab activated">'.$page.'</a>');
                else            echo('<a href="'.$k->url("admin",$page).'" class="tab">'.$page.'</a>');
            } ?>
        </div>
    </div><?
}

function displayPanelPage(){
    global $l;
    $l->triggerHook("panelPage",$this);
}

function displayOptionsPage(){
    global $k,$c;
    if($_POST['action']=="Add"){
        $c->query("INSERT INTO ms_options VALUES(?,?,?)",array($_POST['key'],$_POST['value'],$_POST['type']));
        $err[0]="Key added.";
    }
    if($_POST['action']=="Save"){
        $options = DataModel::getData("ms_options", "SELECT `key`,`value` FROM ms_options");
        foreach($options as $o){
            if(is_array($_POST['val'.$o->key]))$_POST['val'.$o->key]=implode(";",$_POST['val'.$o->key]);
            if($_POST['act'.$o->key]=="delete")
                $c->query("DELETE FROM ms_options WHERE `key`=?",array($o->key));
            else if($_POST['val'.$o->key]!=$o->value)
                $c->query("UPDATE ms_options SET `value`=? WHERE `key`=?",array($_POST['val'.$o->key],$o->key));
        }
        $err[1]="Data saved.";
    }
    
    $options = DataModel::getData("ms_options", "SELECT `key`,`value`,`type` FROM ms_options");
    ?>
    <form action="#" method="post" class="box">
        Add a new key: 
        <input type="text" name="key" placeholder="Key" />
        <input type="text" name="value" placeholder="Value" />
        <select name="type">
            <option value="s" >String</option>
            <option value="i" >Number</option>
            <option value="l" >List</option>
            <option value="t" >Text</option>
        </select>
        <input type="submit" name="action" value="Add" /><span style="color:red;"><?=$err[0]?></span>
    </form>
    <form action="#" method="post" class="box"><?
    foreach($options as $o){
        echo('<div class="datarow">'.$o->key.'<div class="flRight">');
        switch($o->type){
            case 'i':echo('<input type="text" class="number" name="val'.$o->key.'" value="'.$o->value.'" />');break;
            case 's':echo('<input type="text" class="string" name="val'.$o->key.'" value="'.$o->value.'" />');break;
            case 't':echo('<textarea type="text" class="text" name="val'.$o->key.'">'.$o->value.'</textarea>');break;
            case 'l':$vals=explode(";",$o->value);$k->interactiveList("val".$o->key,$vals,$vals,$vals,true);break;
        }
        echo('<select name="act"'.$o->key.'><option value="edit">Edit</option><option value="delete">Delete</otpion></select>');
        echo('</div></div><br class="clear" />');
    }
    ?><input type="submit" name="action" value="Save" />
    <span style="color:red;"><?=$err[1]?></span></form><?
}

function displayLogPage(){
    
}

function displayModulesPage(){
    
}

function displayHooksPage(){
    
}

}
?>