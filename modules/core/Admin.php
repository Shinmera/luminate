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
    global $site;
    $pages=array('Panel','Options','Log','Modules','Hooks');
    ?><div id='pageNav'>
        <div class="description">Administration</div>
        <div class="tabs">
            <? foreach($pages as $page){
                if($page==$site)echo('<a href="" class="tab activated">'.$page.'</a>');
                else            echo('<a href="" class="tab">'.$page.'</a>');
            } ?>
        </div>
    </div><?
}

function displayPanelPage(){
    global $l;
    $l->triggerHook("panelPage",$this);
}

function displayOptionsPage(){
    $ooptions = DataModel::getData("ms_options", "SELECT `key`,`value`,`type` FROM ms_options");
    foreach($options as $o){
        switch($o->type){
            case 'i':echo('<input type="text" class="number" name="val'.$o->key.'" value="'.$o->value.'" />');break;
            case 's':echo('<input type="text" class="string" name="val'.$o->key.'" value="'.$o->value.'" />');break;
            case 't':echo('<textarea type="text" class="text" name="val'.$o->key.'">'.$o->value.'</textarea>');break;
            case 'l':break;
        }
    }
}

function displayLogPage(){
    
}

function displayModulesPage(){
    
}

function displayHooksPage(){
    
}

}
?>