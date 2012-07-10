<? class Ages extends Module{
public static $name="Ages";
public static $author="NexT";
public static $version=0.01;
public static $short='ages';
public static $required=array("");
public static $hooks=array("foo");

function display(){
    global $params;
    switch($params[1]){
        case 'hist':$this->displayHistory($params[0]);break;
        default:    if(!is_numeric($params[1]))$params[1]=0;
                    $this->displayPage($params[0],$params[1]);break;
    }
}

function displayPage($adventure,$page=0){
    
}

function displayHistory($adventure){
    
}

function displayPanel(){
    global $a;?>
    <li>Ages
    <ul class="menu">
        <? if($a->check("ages.admin.adventures")){ ?>
        <a href="<?=PROOT?>Ages/adventures"><li>Manage adventures</li></a><? } ?>
        <? if($a->check("ages.adventure.*")){ ?>
        <a href="<?=PROOT?>Ages/chapters"><li>Manage chapters</li></a><? } ?>
        <? if($a->check("ages.adventure.*")){ ?>
        <a href="<?=PROOT?>Ages/pages"><li>Manage pages</li></a><? } ?>
    </ul></li><?
}

function displayAdmin(){
    global $params,$a;
    switch($params[0]){
        case 'adventures':if($a->check('ages.admin.adventures'))$this->displayAdminAdventures();break;
        case 'chapters':if($a->check('ages.adventure.*'))$this->displayAdminChapters();break;
        case 'pages':if($a->check('ages.adventure.*'))$this->displayAdminPages();break;
    }
}

function displayAdminAdventures(){
    
}

function displayAdminChapters(){
    
}

function displayAdminPages(){
    
}

}
?>