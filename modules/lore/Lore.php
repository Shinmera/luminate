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
    define('CACHEPATH',ROOT.DATAPATH.'cache/lore/');
    
    $params[0]=str_replace('_',' ',$k->sanitizeString($params[0],'\s\-_'));
    $params[1]=str_replace('_',' ',$k->sanitizeString($params[1],'\s\-_'));
    switch(strtolower($params[0])){
        case 'category':
        case 'portal':
        case 'special':
        case 'file':
        case 'template':
        case 'user':
            $type=strtolower($params[0]);
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
    if(strlen($page)>127)$page=substr($page,0,127);
    
    $t->loadTheme("lore");
    switch($type){
        case 'special':
            include(MODULEPATH.$MODULECACHE['Lore_Special']);
            $special = new Special();
            $special->display($page);
            break;
        case 'file':
            include(MODULEPATH.$MODULECACHE['Lore_File']);
            $file = new File($page,$revision);
            call_user_func(array(&$file,'display'.ucfirst($action)));
            break;
        default:
            include(MODULEPATH.$MODULECACHE['Lore_Article']);
            $article = new Article($page,$type,$revision);
            call_user_func(array(&$article,'display'.ucfirst($action)));
            break;
    }
}

function toStatusString($s){
    switch($s){
        case 'o':return 'Open';break;
        case 'p':return 'Protected';break;
        case 'l':return 'Locked';break;
        default: return $s;break;
    }
}

function toTypeString($s){
    switch($s){
        case 'a':return 'Article';break;
        case 'c':return 'Category';break;
        case 'p':return 'Portal';break;
        case 't':return 'Template';break;
        case 'u':return 'User';break;
        default: return $s;break;
    }
}

}
?>