<? class Layouter extends Module{
public static $name="Layouter";
public static $author="NexT";
public static $version=0.01;
public static $short='layouter';
public static $required=array("Auth");
public static $hooks=array("foo");

function displayPage(){
    
}

function displayPanel(){
    global $k,$a;
    ?>
    <li>Layouter
    <ul class="menu">
        <? if($a->check("layouter.admin.layouts")){ ?>
        <a href="<?=$k->url("admin","Layouter/layouts")?>"><li>Layout Management</li></a><? } ?>
        <? if($a->check("layouter.admin.instances")){ ?>
        <a href="<?=$k->url("admin","Layouter/instances")?>"><li>Change Layout Instances</li></a><? } ?>
    </ul></li><?
}
function displayAdmin(){
    global $a,$params;
    switch($params[1]){
        case 'layouts':if($a->check('layouter.admin.layouts'))$this->displayAdminLayouts();break;
        case 'instances':if($a->check('layouter.admin.instances'))$this->displayAdminInstances();break;
    }
}

function displayAdminLayouts(){
    
}

function displayAdminInstances(){
    
}

}
?>