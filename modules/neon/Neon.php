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

function displayUserlistPage(){
    global $t;
    //TODO: Add userlist
}

function displayControlPanelPage(){
    global $t,$a,$l,$k,$params;
    $t->openPage("User Settings");
    if($params[1]=='')$params[1]='Profile';
    $pages = array('Profile');
    $pages = $l->triggerHookSequentially('SETTINGSnavbar',$this,$pages);
    ?><div id='pageNav'>
        <div style="display:inline-block">
            <h1 class="sectionheader">Settings</h1>
        </div>
        <div class="tabs">
            <? foreach($pages as $page){
                if($a->check("admin.".$page)){
                    if($page==$params[1])echo('<a href="'.$k->url("user",$page).'" class="tab activated">'.$page.'</a>');
                    else                 echo('<a href="'.$k->url("user",$page).'" class="tab">'.$page.'</a>');
                }
            }if(!in_array($params[1], $pages))echo('<a href="'.$k->url("user",$params[1]).'" class="tab activated">'.$params[1].'</a>'); ?>
        </div>
    </div><?
    if($params[1]=='Profile')$this->displayControlPanelProfile();
    else                     $l->triggerHook('SETTINGS'.$params[1],$this);
}

function displayControlPanelProfile(){
    global $a,$c;
    
    ?><form class="box" action="#" method="post">
        <h3>Account Settings</h3>
        <label>Username</label><input type="text" name="displayname" value="<?=$a->user->username?>" disabled="disabled" /><br />
        <label>Displayname</label><input type="text" name="displayname" value="<?=$a->user->displayname?>" /><br />
        <label>E-Mail</label><input type="email" name="mail" value="<?=$a->user->mail?>" /><br />
        <input type="submit" name="action" value="Update Account" />
    </form>
    <form class="box" action="#" method="post">
        <h3>Password</h3>
        <label>New password:</label><input type="password" name="newpass" autocomplete="off" /><br />
        <label>Repeat:</label><input type="password" name="newpassrepeat" autocomplete="off" /><br />
        <input type="submit" name="action" value="Save Password" />
    </form>
    <form class="box" action="#" method="post" enctype="multipart/form-data">
        <h3>Avatar</h3>
        <img src="<?=AVATARPATH.$a->user->filename?>" alt="Avatar" title="Your avatar image" /><br />
        <input type="file" name="avatar" accept="image/*" />
        <input type="submit" name="action" value="Update Avatar" /><br />
        <span class="small">
            Maximum filesize is <?=$c->o['avatar_maxsize']?>kb.
            If the avatar exceeds <?=$c->o['avatar_maxdim']?>x<?=$c->o['avatar_maxdim']?> px, it will be re-scaled.
        </span>
    </form>
    <form class="box" action="#" method="post">
        <h3>Profile Information</h3>
        <? $fields = DataModel::getData("ud_fields", "SELECT `varname`, `title`, `default`, `type` FROM ud_fields WHERE `editable`=1");
        $values = DataModel::getData("ud_field_values","SELECT `value` FROM ud_field_values WHERE userID=?",array($a->user->userID));
        foreach($fields as $f){
            echo('<label>'.$f->title.':</label>');
            switch($f->type){
                case 'i':echo('<input autocomplete="off" type="number" class="number" name="val'.$f->varname.'" value="'.$u->value.'" placeholder="'.$f->default.'" />');break;
                case 's':echo('<input autocomplete="off" type="text" class="string" name="val'.$f->varname.'" value="'.$u->value.'" placeholder="'.$f->default.'" />');break;
                case 'u':echo('<input autocomplete="off" type="url" class="url" name="val'.$f->varname.'" value="'.$u->value.'" placeholder="'.$f->default.'" />');break;
                case 'd':echo('<input autocomplete="off" type="date" class="date" name="val'.$f->varname.'" value="'.$u->value.'" placeholder="'.$f->default.'" />');break;
                case 't':echo('<br /><textarea type="text" class="text" name="val'.$f->varname.'" placeholder="'.$f->default.'">'.$u->value.'</textarea>');break;
                case 'l':$vals=explode(";",$u->value);$k->interactiveList("val".$f->varname,$vals,$vals,$vals,true);break;
            }
            echo('<br />');
        }
        ?>
        <input type="submit" name="action" value="Update Profile" />
    </form><?
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
        $l->triggerHookSequentially('PROFILEnavbar',$this,$pages);
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