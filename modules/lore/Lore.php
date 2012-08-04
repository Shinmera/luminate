<? class Lore extends Module{
public static $name="Lore";
public static $author="NexT";
public static $version=0.01;
public static $short='lore';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

function adminNavbar($menu){$menu[]='Lore';return $menu;}

function displayApiParse(){
    global $l;
    $text = $l->triggerPARSE('Lore',$_POST['text']);
    $parser = $l->loadModule('LoreParser');
    echo($parser->parse($text));
}

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
    global $t,$k,$a,$params,$SUPERIORPATH,$page,$type,$action;
    define('CACHEPATH',ROOT.DATAPATH.'cache/lore/');
    
    $params[0]=str_replace(' '  ,'_',$k->sanitizeString($params[0],'\s\-_'));
    $params[1]=str_replace(' '  ,'_',$k->sanitizeString($params[1],'\s\-_'));
    $params[0]=str_replace('%20','_',$params[0]);
    $params[1]=str_replace('%20','_',$params[1]);
    switch(strtolower($params[0])){
        case 'category':
        case 'portal':
        case 'special':
        case 'file':
        case 'template':
        case 'user':
        case 'article':
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
    
    //Create article model.
    $article = DataModel::getData('lore_articles','SELECT title,type,revision,status,current,time 
                                                   FROM lore_articles WHERE title LIKE ? AND type=?',
                                                   array($page,substr($type,0,1)));
    if($article==null){
        global $existing;$existing='inexistent';
        $article = DataModel::getHull('lore_articles');
        $article->title=$page;
        $article->type=substr($type,0,1);
        $article->current='';
        $article->time=time();
        $article->status='o';
        $article->revision=0;
        $article->editor=$a->user->username;
    }
    
    $t->loadTheme("lore");
    switch($type){
        case 'special':
            include('Lore_Special.php');
            $special = new Special();
            $special->display($page);
            break;
        default:
            $ttype=ucfirst($type);
            include('Lore_Article.php');
            if($type=='template'||$type=='file')include('Lore_'.$ttype.'.php');
            else                                $ttype='Article';
            $page = new $ttype($page,$type,$revision,$article);
            call_user_func(array(&$page,'display'.ucfirst($action)));
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
        case 'f':return 'File';break;
        case 'c':return 'Category';break;
        case 'p':return 'Portal';break;
        case 't':return 'Template';break;
        case 'u':return 'User';break;
        default: return $s;break;
    }
}

}
?>