<? class Lore extends Module{
public static $name="Lore";
public static $author="NexT";
public static $version=0.01;
public static $short='lore';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

function buildMenu($menu){$menu[]=array('Wiki',Toolkit::url("wiki",""));return $menu;}
function adminNavbar($menu){$menu[]='Lore';return $menu;}

function displayPanel(){
    
}

function displayAdminPage(){
    
}

function displayPage(){
    global $t,$params,$SUPERIORPATH,$page,$type,$action;
    
    switch($params[0]){
        case 'category':
        case 'portal':
        case 'special':
        case 'file':
        case 'user':
            $type=$params[0];
            $page=$params[1];
            $params=array_slice($params, 1);
            break;
        case '':$params[0]='Index';
        default:
            $page=$params[0];
            $type='article';
            break;
    }
    if($type=='article')$SUPERIORPATH=$page;
    else                $SUPERIORPATH=$type.'/'.$page;
    if(!in_array($params[1],array('edit','history','discuss')))$action='view';
    else                                                       $action=$params[1];
    
    $t->loadTheme("lore");
    $t->openPage($params[0]);
    
    $t->closePage();
}


}
?>