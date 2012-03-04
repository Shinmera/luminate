<?
class Admin extends Module{
public static $name="Admin";
public static $version=2.01;
public static $short='acp';
public static $required=array("Themes","Auth");
    
function __construct(){
}

function displayPage($params){
    global $a,$c,$k,$p,$s,$t,$api;
    $t->openPage("Administration");

    ?><div id='pageNav'>
        <div class="description">Administration</div>
        <div class="tabs">
            <a class="tab activated">Lol</a>
            <a class="tab">Whatever</a>
        </div>
    </div><?
    if($a->check('admin.panel')){
        echo("<center>You are athorized to view this page.</center>");
        
    }else{
        echo("<center>You are not authorized to view this page.</center>");
    }
    $t->closePage();
}
}
?>