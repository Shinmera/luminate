<? class Neon{
public static $name="Neon";
public static $author="NexT";
public static $version=2.01;
public static $short='neon';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

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
    global $a,$k;
    if($a->user->userID=='')
        $menu[]=array('Login',$k->url("login",""),"float:right;");
    else
        $menu[]=array($a->user->displayname,$k->url("user",""),"float:right;",array(
            array('Settings',$k->url("user","panel")),
            array('Profile',$k->url("user",$a->user->displayname)),
            array('Logout',$k->url("login","logout"))
        ));
    return $menu;
}

function displayMainPage(){
    global $params;
    
    switch($params[0]){
        case 'ucp':
        case 'panel':$this->displayControlPanelPage();break;
        case '':
        case 'list':$this->displayUserlistPage();break;
        default:$this->displayUserPage($params[0]);break;
    }
}

function displayUserlistPage(){
    global $t,$k;
    $t->openPage("Userlist");
    ?><div id='pageNav'>
        <div style="display:inline-block">
            <h1 class="sectionheader">Userlist</h1>
        </div>
        <div class="tabs"></div>
    </div>

    <? global $c;
    $max = $c->getData("SELECT COUNT(userID) FROM ud_users");$max=$max[0]['COUNT(userID)'];
    switch($_GET['action']){
        case '<<':$_GET['f']=0;  $_GET['t']=50; break;
        case '<' :$_GET['f']-=50;$_GET['t']-=50;break;
        case '>' :$_GET['f']+=50;$_GET['t']+=50;break;
        case '>>':$_GET['f']=$max-50;$_GET['t']=$max;break;
    }
    //Sanitize user input
    if($_GET['f']<0||!is_numeric($_GET['f']))         $_GET['f']=0;
    if($_GET['t']<$_GET['f']||!is_numeric($_GET['t']))$_GET['t']=$_GET['f']+50;
    if($_GET['t']>$max)$_GET['t']=$max;
    if($_GET['f']>$_GET['t'])$_GET['f']=$_GET['t']-1;
    if($_GET['a']!=0&&$_GET['a']!=1)      $_GET['a']="0";
    if($_GET['t']-$_GET['f']>100)$_GET['t']=$_GET['f']+100;
    $orders = array('username','displayname','group','time');
    if(!in_array($_GET['o'],$orders))$_GET['o']='time';
    ?>
    <div class="box" style="display:block;">
        <form>
            <input type="submit" name="dir" value="<<" />
            <input type="submit" name="dir" value="<" />
            <input autocomplete="off" type="text" name="f" value="<?=$_GET['f']?>" style="width:50px;"/>
            <input autocomplete="off" type="text" name="t" value="<?=$_GET['t']?>" style="width:50px;"/>
            <input type="submit" name="dir" value="Go" />
            <input type="submit" name="dir" value=">" />
            <input type="submit" name="dir" value=">>" />
            <input type="hidden" name="order" value="<?=$_GET['o']?>" />
            <input type="hidden" name="asc" value="<?=$_GET['a']?>" />
        </form>
        <form action="<?=PROOT?>User/edituser" method="post"><table>
            <thead>
                <tr>
                    <th style="width:70px;">Avatar</th>
                    <th style="width:120px;"><a href="?o=username&a=<?=!$_GET['a']?>">   Username</th>
                    <th style="width:120px;"><a href="?o=displayname&a=<?=!$_GET['a']?>">Displayname</a></th>
                    <th></th>
                    <th style="width:100px;"><a href="?o=group&a=<?=!$_GET['a']?>">      Group</a></th>
                    <th style="width:100px;"><a href="?o=time&a=<?=!$_GET['a']?>">       Registered On</a></th>
                </tr>
            </thead>
            <tbody>
                <?
                if($_GET['a']==0)$_GET['a']="DESC";else $_GET['a']="ASC";
                $users = DataModel::getData("ud_users", 'SELECT `username`,`displayname`,`group`,`status`,`filename`,`time` '.
                                                        'FROM ud_users ORDER BY `'.$_GET['o'].'` '.$_GET['a'].' LIMIT '.$_GET['f'].','.$_GET['t']);
                if(!is_array($users))$users=array($users);
                foreach($users as $user){
                    echo('<tr><td>');
                    if($user->filename!='')echo('<img style="width:50px;" src="'.AVATARPATH.$user->filename.'" alt="Avatar" title="'.$user->displayname.'\'s avatar" />');
                    else                   echo('<img style="width:50px;" src="'.AVATARPATH.'noguy.png" alt="Avatar" title="'.$user->displayname.'\'s avatar" />');
                    echo('</td><td><a href="'.$k->url("user",$user->username).'">'.$user->username.'</a></td><td>'.$user->displayname.'</td>');
                    $k->pf('<td></td><td>'.$user->group.'</td><td>'.$k->toDate($user->time).'</td></tr>');
                }
                ?>
            </tbody>
        </table><input type="hidden" name="action" value="Edit" /></form>
    </div><?
    $t->closePage();
}

function displayControlPanelPage(){
    global $t,$a,$l,$k,$params;
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
        switch($params[1]){
            case 'Profile':$this->displayControlPanelProfile();break;
            case 'Friends':$this->displayControlPanelFriends();break;
            default:       $l->triggerHook('SETTINGS'.$params[1],$this);break;
        } 
    }else{
        echo('<div style="text-align:center;">You are not authorized to view this page.</div>');
    }
    $t->closePage();
}

function displayControlPanelProfile(){
    global $a,$c,$k,$l;
    
    switch($_POST['action']){
        case 'Update Account':
            $_POST['displayname']=trim($k->sanitizeString($_POST['displayname']));
            if($k->checkMailValidity($_POST['mail'])){
                if(strlen($_POST['displayname'])>3){
                    if($_POST['displayname']!=$a->user->displayname){
                        $used = $c->query("SELECT COUNT(userID) FROM ud_users WHERE displayname LIKE ? OR username LIKE ? ",array($_POST['displayname'],$_POST['displayname']));
                        if(count($used)==0)$a->user->displayname=$_POST['displayname'];
                        else               $err[0]="This username is already taken.";
                    }
                    $a->user->mail=$_POST['mail'];
                    $a->user->saveData();
                    $err[2]="Account information saved.";
                }else $err[0]="Invalid username!";
            }else $err[1]="Invalid mail address!";
            break;
        case 'Save Password':
            if(strlen($_POST['newpass'])>5){
                if($_POST['newpass']==$_POST['newpassrepeat']){
                    $hash=hash('sha512',$_POST['newpass']);
                    $a->user->password=$hash;
                    $a->user->saveData();
                    $a->login($a->user->username,$hash,false); //Revalidate cookies
                    $err[5]="Password saved.";
                }else $err[4]="The passwords do not match.";
            }else $err[3]="Password must be longer than 5 characters.";
            break;
        case 'Update Avatar':
            $filename = $a->user->username.'-'.$k->generateRandomString();
            try{
                $dest = $k->uploadFile("avatar",ROOT.AVATARPATH,$c->o['avatar_maxsize'],array("image/png","image/gif","image/jpeg","image/jpg"),false,$filename);
                $k->createThumbnail($dest,$dest,$c->o['avatar_maxdim'],$c->o['avatar_maxdim'],false,true,false);
                unlink(ROOT.AVATARPATH.$a->user->filename);
                $a->user->filename=substr($dest,  strrpos($dest, "/"));
                $a->user->saveData();
                $err[7]="Avatar changed.";
            }catch(Exception $e){$err[6]="Uploading failed: ".$e->getMessage();}
            break;
        case 'Update Profile':
            $u = $l->loadModule('User');
            $u->updateUserFields($a->user->userID,$_POST);
            $err[8]="Profile settings saved.";
            break;
    }
    
    ?>
<div class="tabbed">
    <form action="#" method="post" name="Profile">
        <? $fields = DataModel::getData("ud_fields", "SELECT `varname`, `title`, `default`, `type` FROM ud_fields WHERE `editable`=1");
        $values = DataModel::getData("ud_field_values","SELECT `varname`,`value` FROM ud_field_values WHERE userID=?",array($a->user->userID));
        foreach($fields as $f){
            echo('<label>'.$f->title.':</label>');
            $v = null;
            foreach($values as $value){
                if($value->varname==$f->varname){$v=$value->value;break;}
            }
            switch($f->type){
                case 'i':echo('<input type="number" class="number" name="val'.$f->varname.'" value="'.$v.'" placeholder="'.$f->default.'" />');break;
                case 's':echo('<input type="text" class="string" name="val'.$f->varname.'" value="'.$v.'" placeholder="'.$f->default.'" />');break;
                case 'u':echo('<input type="url" class="url" name="val'.$f->varname.'" value="'.$v.'" placeholder="'.$f->default.'" />');break;
                case 'd':echo('<input type="date" class="date" name="val'.$f->varname.'" value="'.$v.'" placeholder="'.$f->default.'" />');break;
                case 't':echo('<textarea type="text" class="text" name="val'.$f->varname.'" placeholder="'.$f->default.'">'.$v.'</textarea>');break;
                case 'l':$vals=explode(";",$v);$k->interactiveList("val".$f->varname,$vals,$vals,$vals,true);break;
            }
            echo('<br />');
        }
        ?>
        <input type="submit" name="action" value="Update Profile" /><?=$err[8]?>
    </form>
    <form action="#" method="post" enctype="multipart/form-data" name="Avatar">
        <img src="<?=AVATARPATH.$a->user->filename?>" alt="Avatar" title="Your avatar image" /><br />
        <input type="file" name="avatar" accept="image/*" /><?=$err[6]?>
        <input type="submit" name="action" value="Update Avatar" /><?=$err[7]?><br />
        <span class="small">
            Maximum filesize is <?=$c->o['avatar_maxsize']?>kb.
            If the avatar exceeds <?=$c->o['avatar_maxdim']?>x<?=$c->o['avatar_maxdim']?> px, it will be re-scaled.
        </span>
    </form>
    <form action="#" method="post" name="Account">
        <label>Username</label><input type="text" name="displayname" value="<?=$a->user->username?>" disabled="disabled" /><br />
        <label>Displayname</label><input type="text" name="displayname" value="<?=$a->user->displayname?>" /><?=$err[0]?><br />
        <label>E-Mail</label><input type="email" name="mail" value="<?=$a->user->mail?>" /><?=$err[1]?><br />
        <input type="submit" name="action" value="Update Account" /><?=$err[2]?>
    </form>
    <form action="#" method="post" name="Password">
        <label>New password:</label><input type="password" name="newpass" autocomplete="off" /><?=$err[3]?><br />
        <label>Repeat:</label><input type="password" name="newpassrepeat" autocomplete="off" /><?=$err[4]?><br />
        <input type="submit" name="action" value="Save Password" /><?=$err[5]?>
    </form>
</div><?
}

function displayControlPanelFriends(){
    global $a,$k,$c;
    
    switch($action){
        case 'Remove':
            if(count($_POST['friends']>0)){
                $query = "DELETE FROM neon_users WHERE type=? AND (";
                $data = array('f');$sub=array();
                foreach($_POST['blocked'] as $b){
                    $sub[]="((uID1=? AND uID2=?) OR (uID2=? AND uID1=?))";
                    $data[]=$a->user->userID;
                    $data[]=$b;
                    $data[]=$a->user->userID;
                    $data[]=$b;
                }
                $query.=implode(" OR ",$sub).")";
                $c->query($query,$data);
                $err[0]="Friend removed.";
            }
            if(count($_POST['requests']>0)){
                $query = "DELETE FROM neon_users WHERE type=? AND (";
                $data = array('r');$sub=array();
                foreach($_POST['requests'] as $b){
                    $sub[]="(uID2=? AND uID1=?)";
                    $data[]=$a->user->userID;
                    $data[]=$b;
                }
                $query.=implode(" OR ",$sub).")";
                $c->query($query,$data);
                $err[2]="Request denied.";
            }
            if(count($_POST['blocked']>0)){
                $query = "DELETE FROM neon_users WHERE type=? AND (";
                $data = array('b');$sub=array();
                foreach($_POST['blocked'] as $b){
                    $sub[]="(uID1=? AND uID2=?)";
                    $data[]=$a->user->userID;
                    $data[]=$b;
                }
                $query.=implode(" OR ",$sub).")";
                $c->query($query,$data);
                $err[3]="Block  removed.";
            }
            break;
        case 'Add Friend':
            $friend = DataModel::getHull("neon_friends");
            $exists = $c->getData("SELECT userID FROM ud_users WHERE username LIKE ? OR displayname LIKE ?",array($_POST['user'],$_POST['user']));
            if(count($exists)>0){
                $friend->uID1=$a->user->userID;
                $friend->uID2=$exists[0]['userID'];
                $friend->type='r';
                $friend->insertData();
                $err[1]="Friend request sent.";
            }else $err[1]="No such user.";
            break;
        case 'Accept':
            $friend = DataModel::getHull("neon_friends");
            foreach($_POST['requests'] as $request){
                $friend->uID1=$a->user->userID;
                $friend->uID2=$request;
                $friend->type='f';
                $friend->insertData();
                $friend->uID2=$request;
                $friend->uID1=$a->user->userID;
                $friend->saveData();
                $err[2]="Friend accepted.";
            }
            break;
        case 'Block':
            $block = DataModel::getHull("neon_friends");
            $exists = $c->getData("SELECT userID FROM ud_users WHERE username LIKE ? OR displayname LIKE ?",array($_POST['user'],$_POST['user']));
            if(count($exists)>0){
                $block->uID1=$a->user->userID;
                $block->uID2=$exists[0]['userID'];
                $block->type='b';
                $block->insertData();
                $err[4]="User blocked.";
            }
            break;
    }
    
    
    $friends = DataModel::getData("neon_friends","SELECT ud_users.userID as userID, ud_users.displayname AS displayname,ud_users.filename AS filename FROM ".
                                                 "neon_friends INNER JOIN ud_users ON neon_friends.uID2 = ud_users.userID ".
                                                 "WHERE neon_friends.uID1=? AND neon_friends.type LIKE ?",array($a->user->userID,'f'));
    $blocked = DataModel::getData("neon_friends","SELECT ud_users.userID as userID, ud_users.displayname AS displayname,ud_users.filename AS filename FROM ".
                                                 "neon_friends INNER JOIN ud_users ON neon_friends.uID2 = ud_users.userID ".
                                                 "WHERE neon_friends.uID1=? AND neon_friends.type LIKE ?",array($a->user->userID,'b'));
    $requests= DataModel::getData("neon_friends","SELECT ud_users.userID as userID, ud_users.displayname AS displayname,ud_users.filename AS filename FROM ".
                                                 "neon_friends INNER JOIN ud_users ON neon_friends.uID1 = ud_users.userID ".
                                                 "WHERE neon_friends.uID2=? AND neon_friends.type LIKE ?",array($a->user->userID,'r'));
    if($friends==null)$friends=array();
    if($requests==null)$requests=array();
    if($blocked==null)$blocked=array();
    if(!is_array($friends))$friends=array($friends);
    if(!is_array($requests))$requests=array($requests);
    if(!is_array($blocked))$blocked=array($blocked);
    ?>
<div class="tabbed">
    <form action="#" method="post" name="Friends">
        <? foreach($friends as $friend){ ?>
            <div class="useravatarbox" >
                <? $k->getUserAvatar($friend->displayname,$friend->filename,false,50); ?><br />
                <input type="checkbox" name="friends[]" value="<?=$friend->userID?>"/>
                <? $k->getUserPage($friend->displayname); ?>
            </div>
        <? } ?>
        <br />
        <input type="submit" name="action" value="Remove" /><?=$err[0]?>
        <br />
        <input type="text" name="user" class="userpick" />
        <input type="submit" name="action" value="Add Friend" /><?=$err[1]?>
    </form>
    <form action="#" method="post" name="Requests">
        <? foreach($requests as $request){ ?>
            <div class="useravatarbox" >
                <? $k->getUserAvatar($request->displayname,$request->filename,false,50); ?><br />
                <input type="checkbox" name="requests[]" value="<?=$request->userID?>"/>
                <? $k->getUserPage($request->displayname); ?>
            </div>
        <? } ?>
        <br />
        <input type="submit" name="action" value="Remove" />
        <input type="submit" name="action" value="Accept" /><?=$err[2]?>
    </form>
    <form action="#" method="post" name="Blocked">
        <? foreach($blocked as $block){ ?>
            <div class="useravatarbox" >
                <? $k->getUserAvatar($block->displayname,$block->filename,false,50); ?><br />
                <input type="checkbox" name="blocked[]" value="<?=$block->userID?>"/>
                <? $k->getUserPage($block->displayname); ?>
            </div>
        <? } ?>
        <br />
        <input type="submit" name="action" value="Remove" /><?=$err[3]?>
        <br />
        <input type="text" name="user" class="userpick" />
        <input type="submit" name="action" value="Block" /><?=$err[4]?>
    </form>
</div><?
    
    
}

function displayUserPage($username){
    global $t,$l,$k,$a,$params,$site;
    $user = DataModel::getData("ud_users","SELECT username,displayname,filename FROM ud_users WHERE username LIKE ? OR displayname LIKE ?",array($username,$username));
    
    if($user==null){
        $t->openPage("User not found");
        ?><div id='pageNav'>
            <div style="display:inline-block">
                <h1 class="sectionheader">User</h1>
            </div>
            <div class="tabs"></div>
        </div>
        <div class="large" style="text-align:center;">No such user found.</div><?
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
    <?
    if($fields!=null&&!is_array($fields))$fields=array($fields);
    if(is_array($fields)){
        foreach($fields as $field){
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
    }
    
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