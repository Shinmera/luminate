<? class Neon{
public static $name="Neon";
public static $author="NexT";
public static $version=2.01;
public static $short='neon';
public static $required=array("Auth","Themes","User");
public static $hooks=array("foo");

function buildMenu($menu){
    global $a,$k;
    if($a->user->userID=='')
        $menu[]=array('Login',$k->url("login",""),"float:right;");
    else
        $menu[]=array($a->user->displayname,$k->url("user",""),"float:right;",array(
            array('Settings',$k->url("user","")),
            array('Profile',$k->url("user",$a->user->displayname))
        ));
    return $menu;
}

function displayMainPage(){
    global $params;
    
    switch($params[0]){
        case 'ucp':
        case 'panel':
        case '':$this->displayControlPanelPage();break;
        case 'list':$this->displayUserlistPage();break;
        default:$this->displayUserPage($params[0]);break;
    }
}

function displayNavbar(){
    global $site,$k,$a;
    $pages=array('Panel','Options','Log','Modules','Hooks');
    ?><div id='pageNav'>
        <h1 class="sectionheader">User</h1>
        <div class="tabs">
            <? foreach($pages as $page){
                if($a->check("admin.".$page)){
                    if($page==$site)echo('<a href="'.$k->url("admin",$page).'" class="tab activated">'.$page.'</a>');
                    else            echo('<a href="'.$k->url("admin",$page).'" class="tab">'.$page.'</a>');
                }
            }if(!in_array($site, $pages))echo('<a href="'.$k->url("admin",$site).'" class="tab activated">'.$site.'</a>'); ?>
        </div>
    </div><?
}

function displayUserlistPage(){
    global $t;
    //TODO: Add userlist
}

function displayControlPanelPage(){
    global $t,$a,$l,$k,$params;
    $t->openPage("User Settings");
    if($params[1]=='')$site='Profile';
    $pages = array('Profile'); //TODO: Split up properly
    $l->triggerHookSequentially('SETTINGSmenu',$this,$pages);
    ?><div id='pageNav'>
        <div style="display:inline-block">
            <h1 class="sectionheader">Settings</h1>
        </div>
        <div class="tabs">
            <? foreach($pages as $page){
                if($a->check("admin.".$page)){
                    if($page==$site)echo('<a href="'.$k->url("user",$page).'" class="tab activated">'.$page.'</a>');
                    else            echo('<a href="'.$k->url("user",$page).'" class="tab">'.$page.'</a>');
                }
            }if(!in_array($site, $pages))echo('<a href="'.$k->url("user",$site).'" class="tab activated">'.$site.'</a>'); ?>
        </div>
    </div><?
    //TODO: Add page separation
}

function displayUserPage($username){
    global $t,$l,$k,$a,$params,$site;
    $user = DataModel::getData("ud_users","SELECT username,displayname,filename FROM ud_users WHERE username LIKE ? OR displayname LIKE ?",array($username,$username));
    
    if($user==null){
        $t->openPage("User not found");
        $this->displayNavbar();
        echo('<div class="title" style="text-align:center;">No such user found.</div>');
    }else{
        $t->openPage($username." - Profile");
        if($params[1]=='')$site='Profile';
        $pages = array('Profile');
        $l->triggerHookSequentially('PROFILEmenu',$this,$pages);
        ?><div id='pageNav'>
            <? if($user->filename==''){ $user->filename='noguy.png'; } ?>
            <img src="<?=AVATARPATH.$user->filename?>" alt="" title="<?=$user->displayname?>'s avatar" />
            <div style="display:inline-block">
                <h1 class="sectionheader"><?=$user->displayname?></h1>
                <div class="sectionsubheader"><?=$user->username?></div>
            </div>
            <div class="tabs">
                <? foreach($pages as $page){
                    if($a->check("admin.".$page)){
                        if($page==$site)echo('<a href="'.$k->url("user",$page).'" class="tab activated">'.$page.'</a>');
                        else            echo('<a href="'.$k->url("user",$page).'" class="tab">'.$page.'</a>');
                    }
                }if(!in_array($site, $pages))echo('<a href="'.$k->url("user",$site).'" class="tab activated">'.$site.'</a>'); ?>
            </div>
        </div><?
        
        if($site=='Profile')$this->displayUserProfile($username);
        else                $l->triggerHook('PAGE'.$site,$this);
    }
    
    $t->closePage();
}

function displayUserProfile($username){
    global $k,$a,$l;
    $user = DataModel::getData("ud_users","SELECT userID,displayname,`group`,`status`,`time` FROM ud_users WHERE username LIKE ?",array($username));
    $fields = DataModel::getData("ud_fields", "SELECT ud_fields.title AS `title`,ud_fields.default AS `default`,ud_fields.type AS `type`,ud_field_values.value AS `value` ".
                                              "FROM ud_fields INNER JOIN ud_field_values USING(varname) ".
                                              "WHERE ud_fields.displayed=1 AND ud_field_values.userID=? ",array($user->userID));
    $friends = DataModel::getData("neon_friends","SELECT ud_users.displayname AS displayname, ud_users.filename AS filename ".
                                                 "FROM neon_friends INNER JOIN ud_users ON neon_friends.uID2 = ud_users.userID ".
                                                 "WHERE neon_friends.type LIKE ? AND neon_friends.uID1 LIKE ?",array('f',$user->userID));
    //TODO: Add last time visited
    ?><div class='bar'>
        <div class='section'>
            <?=$username?>#<?=$k->unifyNumberString(base_convert($user->userID, 10, 16),10);?><br />
            Registered on <?=$k->toDate($user->time);?>
        </div>
        <div class='section'>
            Group: <?=$user->group?><br />
            &Delta;<?=$a->generateDelta();?>
        </div>
        <? $l->triggerHook('PROFILEbar',$this,array($user->userID)); ?>
    </div>
    <? foreach($fields as $field){
        if($field->type=='t'){
            if($field->value=='')$field->value=$field->default;
            echo('<div class="box userTextBox"><h3>'.$field->title.'</h3>'.$field->value.'</div>');
        }
    }
    echo('<div class="box userInfoBox"><h3>Info</h3><ul>');
    foreach($fields as $field){
        if($field->value=='')$field->value=$field->default;
        if($field->value!=''){
            switch($field->type){
                case 'i':
                case 's':
                case 'd':
                    echo('<li>'.$field->title.': '.$field->value.'</li>');
                    break;
                case 'l':
                    echo('<li>'.$field->title.': '.$field->value.'</li>');
                    break;
                case 'u':
                    echo('<li>'.$field->title.': <a href="'.$field->value.'">'.$field->value.'</a></li>');
                    break;
            }
        }
    }
    echo('</ul></div>');
    
    if($friends!=null&&!is_array($friends))$friends=array($friends);
    if(is_array($friends)){
        echo('<div class="box userFriendsBox"><h3>Friends</h3><ul>');
        foreach($friends as $friend){
            echo('<li><a href="'.$k->url("user",$friend->displayname).'"><img src="'.AVATARPATH.$friend->filename.'" alt="'.$friend->displayname.'" title="'.$friend->displayname.'\'s avatar" /></a></li>');
        }
        echo('</ul></div>');
    }
    
    $l->triggerHook('PROFILEpage',$this);
    
}

}
?>