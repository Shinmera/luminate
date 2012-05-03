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
    global $MODULECACHE;
    include(MODULEPATH.$MODULECACHE['Lore_Admin']);
    $admin = new LoreAdmin();
    $admin->displayPanel();
}

function displayAdminPage(){
    $admin = new LoreAdmin();
    $admin->displayAdminPage();
}

function displayPage(){
    global $t,$k,$params,$SUPERIORPATH,$MODULECACHE,$page,$type,$action;
    include(MODULEPATH.$MODULECACHE['Lore_Article']);
    
    $params[0]=str_replace('_',' ',$k->sanitizeString($params[0],'\s\-_'));
    $params[1]=str_replace('_',' ',$k->sanitizeString($params[1],'\s\-_'));
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
    if(is_numeric($params[1]))$revision=$params[1];
    else                      $revision=-1;
    if(!in_array($params[1],array('edit','history','discuss')))$action='view';
    else                                                       $action=$params[1];
    
    $t->loadTheme("lore");
    $t->openPage($params[0].' - Lore');
    
    
    if($type!='article')include(MODULEPATH.$MODULECACHE['Lore_'.ucfirst($type)]);
    $temp = ucfirst($type);
    $object = new $temp($page,$revision.ucfirst($action));
    call_user_func(array(&$object,'display'.ucfirst($action)));
    
    $t->closePage();
}


}
?>