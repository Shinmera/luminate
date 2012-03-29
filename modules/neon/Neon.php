<? class Neon{
public static $name="Neon";
public static $author="NexT";
public static $version=2.01;
public static $short='neon';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

//TODO: Registration
function displayLogin(){
    global $t,$a,$k,$params;
    if($a->user == null){
        if($_POST['action']=="Login"){
            if($_POST['username']=="")$err[1]="Username missing!";
            if($_POST['password']=="")$err[2]="Password missing!";
            if(!isset($err)){
                if($a->login($_POST['username'],$_POST['password']))    header("Location: ".$k->url("www",""));
                else                                                    $err[0]="Invalid username or password.";
            }
        }
        $t->openPage("Login");
        ?><div id='pageNav'>
            <div class="description">Login</div>
            <div class="tabs">
            </div>
        </div>
        <center><form action="#" method="post">
            <?=$err[0]?><br />
            <label>Username: </label><input autofocus="autofocus" name="username" type="text" maxlength="64" /><label><?=$err[1]?></label><br />
            <label>Password: </label><input                       name="password" type="password" maxlength="64" /><label><?=$err[2]?></label><br />
            <input type="submit" name="action" value="Login" />
        </form></center><?
    }else{
        if($params[0]=='logout')$a->logout();
        header("Location: ".$k->url("www",""));
    }
    $t->closePage();
}

function buildMenu($menu){
    global $a,$k,$l;
    if($a->user->userID=='')
        $menu[]=array('Login',$k->url("login",""),"float:right;");
    else{
        $innermenu=array(
            array('Settings',$k->url("user","panel")),
            array('Profile',$k->url("user",$a->user->displayname)),
            array('Logout',$k->url("login","logout"))
        );
        $innermenu=$l->triggerHookSequentially("buildMenu",'User',$innermenu);
        $menu[]=array($a->user->displayname,$k->url("user","panel"),"float:right;",$innermenu);
    }
    return $menu;
}

function displayMainPage(){
    global $params,$MODULECACHE;
    
    switch($params[0]){
        case 'ucp':
        case 'panel':$this->displayControlPanelPage();break;
        case '':
        case 'list':
            include(MODULEPATH.$MODULECACHE['Neon_Public']);
            displayUserlistPage();
            break;
        default:
            include(MODULEPATH.$MODULECACHE['Neon_Public']);
            displayUserPage($params[0]);
            break;
    }
}

function displayControlPanelPage(){
    global $t,$a,$l,$k,$params,$MODULECACHE;
    $t->openPage("User Settings");
    
    if($params[1]=='')$params[1]='Profile';
    $pages = array('Profile','Friends');
    $pages = $l->triggerHookSequentially('SETTINGSnavbar',$this,$pages);
    ?><div id='pageNav'>
        <div style="display:inline-block">
            <h1 class="sectionheader">Settings</h1>
        </div>
        <div class="tabs">
            <? foreach($pages as $page){
                if($a->check("admin.".$page)){
                    if($page==$params[1])echo('<a href="'.$k->url("user","panel/".$page).'" class="tab activated">'.$page.'</a>');
                    else                 echo('<a href="'.$k->url("user","panel/".$page).'" class="tab">'.$page.'</a>');
                }
            }if(!in_array($params[1], $pages))echo('<a href="'.$k->url("user",$params[1]).'" class="tab activated">'.$params[1].'</a>'); ?>
        </div>
    </div><?
    
    if($a->check("user.settings")){
        include(MODULEPATH.$MODULECACHE['Neon_Private']);
        switch($params[1]){
            case 'Profile':displayControlPanelProfile();break;
            case 'Friends':displayControlPanelFriends();break;
            default:       $l->triggerHook('SETTINGS'.$params[1],$this);break;
        } 
    }else{
        echo(NO_ACCESS);
    }
    $t->closePage();
}

}
?>