<? class User extends Module{
public static $name="User";
public static $version=2.01;
public static $short='u';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

function __construct(){}

function displayLogin(){
    global $t,$a,$k;
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
            <label>Username: </label><input name="username" type="text" maxlength="64" /><label><?=$err[1]?></label><br />
            <label>Password: </label><input name="password" type="password" maxlength="64" /><label><?=$err[2]?></label><br />
            <input type="submit" name="action" value="Login" />
        </form></center><?
    }else header("Location: ".$k->url("www",""));
    $t->closePage();
}

function displayPanel(){
    global $k;
    ?><div class="box">
        <div class="title">User</div>
        <ul class="menu">
            <a href="<?=$k->url("admin","User")?>"><li>Overview</li></a>
            <a href="<?=$k->url("admin","User/users")?>"><li>User Management</li></a>
            <a href="<?=$k->url("admin","User/groups")?>"><li>Group Management</li></a>
            <a href="<?=$k->url("admin","User/fields")?>"><li>Custom Fields</li></a>
        </ul>
    </div><?
}

function displayAdminPage(){
    global $params,$c,$k;
    $this->displayPanel();
    switch($params[1]){
        case 'users': $this->displayUserManagementPage();break;
        case 'groups': $this->displayGroupsManagementPage();break;
        case 'fields': $this->displayFieldsManagementPage();break;
        case 'edituser': $this->displayEditUserPage();break;
        default:
            $usercount  = $c->getData("SELECT COUNT(userID) AS usercount FROM ud_users");
            $mostrecent = $c->getData("SELECT username,time FROM ud_users ORDER BY time DESC LIMIT 1");
            ?><div class="box">
                <div class="title">Overview</div>
                Total amount of users: <?=$usercount[0]['usercount']?><br />
                Most recent user (<?=$k->toDate($mostrecent[0]['time'])?>): <?=$mostrecent[0]['username']?>
            </div><?
            break;
    }
}

function displayUserManagementPage(){
    global $c;
    $max = $c->getData("SELECT COUNT(userID) FROM ud_users");$max=$max[0]['COUNT(userID)'];
    switch($_GET['action']){
        case '<<':$_GET['f']=0;  $_GET['t']=50; break;
        case '<' :$_GET['f']-=50;$_GET['t']-=50;break;
        case '>' :$_GET['f']+=50;$_GET['t']+=50;break;
        case '>>':$_GET['f']=$max-50;$_GET['t']=$max;break;
    }
    if($_GET['f']<0||!is_numeric($_GET['f']))         $_GET['f']=0;
    if($_GET['t']<$_GET['f']||!is_numeric($_GET['t']))$_GET['t']=$_GET['f']+50;
    if($_GET['t']>$max)$_GET['t']=$max;
    if($_GET['f']>$_GET['t'])$_GET['f']=$_GET['t']-1;
    ?>
    <div class="box" style="display:block;">
        <form action="<?=PROOT?>User/edituser" method="post">
            <input type="text" name="username" placeholder="Username"/>
            <input type="submit" name="action" value="Add" />
        </form>
        <br />
        <form>
            <input type="submit" name="dir" value="<<" />
            <input type="submit" name="dir" value="<" />
            <input type="text" name="f" value="<?=$_GET['f']?>" style="width:50px;"/>
            <input type="text" name="t" value="<?=$_GET['t']?>" style="width:50px;"/>
            <input type="submit" name="dir" value="Go" />
            <input type="submit" name="dir" value=">" />
            <input type="submit" name="dir" value=">>" />
        </form>
        <form action="<?=PROOT?>User/edituser" method="post"><table>
            <thead>
                <tr><th>UID</th><th>Username</th><th>Displayname</th><th>Mail</th><th>Group</th><th>S</th><th>Time</th></tr>
            </thead>
            <tbody>
                <?
                $users = DataModel::getData("ud_users", 'SELECT `userID`,`username`,`displayname`,`mail`,`group`,`status`,`time` '.
                                                        'FROM ud_users ORDER BY time DESC LIMIT '.$_GET['f'].','.$_GET['t']);
                if(!is_array($users))$users=array($users);
                foreach($users as $u){
                    echo('<tr><td><input type="submit" name="userID" value="'.$u->userID.'" /></td><td>'.$u->username.'</td>');
                    echo('<td>'.$u->displayname.'</td><td>'.$u->mail.'</td><td>'.$u->group.'</td><td>'.$u->status.'</td><td>'.$u->time.'</td></tr>');
                }
                ?>
            </tbody>
        </table><input type="hidden" name="action" value="Edit" /></form>
    </div><?
}

function displayGroupsManagementPage(){
    global $c;
    
    if($_POST['title']!=''){
        if($_POST['permissions']!=''){
            if($_POST['action']=="Edit")$this->updateGroup($_POST['title'], $_POST);
            if($_POST['action']=="Add" )$this->addGroup($_POST['title'], $_POST['permissions']);
            if($_POST['action']=="Delete")$this->deleteGroup ($_POST['title']);
        }
        $group = DataModel::getData("ud_groups","SELECT * FROM ud_groups WHERE title=?",array($_POST['title']));
    }
    $groups = DataModel::getData("ud_groups","SELECT * FROM ud_groups");
    ?><form class="box" method="post" action="#">
        <input type="text" name="title" placeholder="Groupname" value="<?=$group->title?>" />
        <input type="submit" name="action" value="<? if($group==null)echo('Add');else echo('Edit'); ?>" /><br />
        <textarea name="permissions" placeholder="Permissiontree" style="min-width:200px;min-height:100px;"><?=$group->permissions?></textarea>
        <? if($group!=null)echo('<input type="submit" name="action" value="Delete" />'); ?>
    </form>
    <div class="box" style="display:block;">
        <table>
            <thead><tr><th style="width:200px;">Title</th><th>Permissions</th><th style="width:100px;">Members</th></tr></thead>
            <tbody><?
                foreach($groups as $g){
                    $amount = $c->getData("SELECT COUNT(userID) FROM ud_users WHERE `group` LIKE ?",array($g->title));
                    echo('<tr><td><form action="#" method="post"><input type="submit" name="title" value="'.$g->title.'" /></td>');
                    echo('<td>'.nl2br($g->permissions).'</td><td>'.$amount[0]['COUNT(userID)'].'</td></tr>');
                }
            ?></tbody>
        </table>
    </div><?
}

function displayFieldsManagementPage(){
    
}

function displayEditUserPage(){
    if($_POST['action']!='Edit')$_POST['action']='Add';
    if($_POST['action']=="Add"&&$_POST['status']!=''){
        $this->addUser($_POST['username'],$_POST['mail'],$_POST['password'],$_POST['status'],$_POST['group'],$_POST['displayname']);
        $_POST['action']='Edit';$_POST['status']='';
    }
    if($_POST['action']=="Edit"&&$_POST['status']!=''){
        $this->updateUser($_POST['userID'], $_POST);
    }
    if($_POST['action']=="Delete"){
        $this->deleteUser($_POST['userID']);
    }
    $user = DataModel::getData("ud_users", "SELECT * FROM ud_users WHERE userID=?",array($_POST['userID']));
    $groups=DataModel::getData("ud_groups","SELECT title FROM ud_groups");
    if($user==null)$user = DataModel::getHull("ud_users");
    ?><form action="#" method="post" class="box">
        <label>UserID</label>       <input type="text" value="<?=$user->userID?>" disabled="disabled" placeholder="Generated"/><br />
        <label>Username</label>     <input type="text" name="username" value="<?=$user->username?>" placeholder="Required"/><br />
        <label>Displayname</label>  <input type="text" name="displayname" value="<?=$user->displayname?>" placeholder="Username"/><br />
        <label>Mail</label>         <input type="text" name="mail" value="<?=$user->mail?>" placeholder="Required"/><br />
        <label>Password</label>     <input type="password" name="password" value="<?=$user->password?>" placeholder="Random"/><br />
        <label>Secret</label>       <input type="text" name="secret" value="<?=$user->secret?>" placeholder="Generated" <? if($_POST['action']=="Add")echo('disabled="disabled"'); ?>/><br />
        <label>Filename</label>     <input type="text" name="filename" value="<?=$user->filename?>" placeholder="noguy.png" <? if($_POST['action']=="Add")echo('disabled="disabled"'); ?>/><br />
        <label>Time</label>         <input type="text" name="time" value="<?=$user->time?>" placeholder="Generated" <? if($_POST['action']=="Add")echo('disabled="disabled"'); ?>/><br />
        <label>Group</label>
            <select name="group">
                <? foreach($groups as $g){
                    if($g->title==$user->group)$sel="selected";else $sel="";
                    echo('<option value="'.$g->title.'" '.$sel.' >'.$g->title.'</option>');
                } ?>
            </select><br />
        <label>Status</label>
            <select name="status">
                <option value="a" <? if($user->status=="a")echo('selected'); ?>>Activated</option>
                <option value="i" <? if($user->status=="i")echo('selected'); ?>>Inactive</option>
                <option value="b" <? if($user->status=="b")echo('selected'); ?>>Banned</option>
                <option value="s" <? if($user->status=="s")echo('selected'); ?>>System</option>
            </select><br />
        <input type="hidden" name="userID" value="<?=$user->userID?>" />
        <input type="submit" name="action" value="<?=$_POST['action']?>" class="flRight" style="font-weight:bold;"/>
        <input type="submit" name="action" value="Delete"/> 
    </form><?
}

function addUser($username,$mail,$password,$status='',$group='Registered',$displayname=''){
    global $c,$k;
    $username=$k->sanitizeString($username);
    if(!in_array($status,array('activated','banned','system')))$status='inactive';
    $status=substr($status,0,1);
    if($displayname=='')$displayname=$username;
    $secret=$k->generateRandomString(31);
    $password=hash('sha512',$password);
    $c->query("INSERT INTO ud_users VALUES(NULL,?,?,?,?,?,?,?,?,?)",array($username,$mail,$password,$secret,$displayname,'',$group,$status,time()));
}

function updateUser($userID,$fields){
    $user = DataModel::getData("ud_users", "SELECT * FROM ud_users WHERE userID=?",array($userID));
    if($user!=null){
        if($fields['password']!=$user->password)$fields['password']=hash('sha512',$fields['password']); //Hash password
        foreach($fields as $key => $value){$user->$key = $value;}
        $user->saveData();
    }
}

function deleteUser($userID){
    global $c;
    $c->query('DELETE FROM ud_users WHERE userID=?',array($userID));
}

function addGroup($groupname,$tree){
    $group = DataModel::getHull("ud_groups");
    $group->title=$groupname;
    $group->permissions=$tree;
    $group->insertData();
}

function updateGroup($groupname,$changes){
    $group = DataModel::getData("ud_groups", "SELECT * FROM ud_groups WHERE title=?",array($groupname));
    foreach($changes as $key=>$value){$group->$key = $value;}
    $group->saveData();
}

function deleteGroup($groupname){
    global $c;
    $c->query("DELETE FROM ud_groups WHERE title=?",array($groupname));
}

function addField($fieldname,$title,$editable=false,$displayed=false,$default=''){
    $field = DataModel::getHull("ud_fields");
    $field->varname=$fieldname;
    $field->title=$title;
    $field->default=$default;
    if(is_bool($editable))$field->editable=$editable;
    if(is_bool($displayed))$field->displayed=$fidplayed;
    $field->insertData();
}

function updateField($fieldname,$changes){
    $field = DataModel::getData("ud_fields", "SELECT * FROM ud_fields WHERE varname=?",array($fieldname));
    foreach($changes as $key=>$value){$field->$key = $value;}
    $field->saveData();
}

function deleteField($fieldname){
    global $c;
    $c->query("DELETE FROM ud_fields WHERE varname=?",array($fieldname));
    $c->query("DELETE FROM ud_field_values WHERE varname=?",array($fieldname));
}

}
?>