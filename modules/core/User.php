<? class User extends Module{
public static $name="User";
public static $version=2.01;
public static $short='u';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

function __construct(){}

function displayPanel(){
    global $k,$a;
    ?><div class="box">
        <div class="title">User</div>
        <ul class="menu">
            <a href="<?=$k->url("admin","User")?>"><li>Overview</li></a>
            <? if($a->check("user.admin.users")){ ?>
            <a href="<?=$k->url("admin","User/users")?>"><li>User Management</li></a><? } ?>
            <? if($a->check("user.admin.groups")){ ?>
            <a href="<?=$k->url("admin","User/groups")?>"><li>Group Management</li></a><? } ?>
            <? if($a->check("user.admin.fields")){ ?>
            <a href="<?=$k->url("admin","User/fields")?>"><li>Custom Fields</li></a><? } ?>
        </ul>
    </div><?
}

function adminNavbar($menu){$menu[]='User';return $menu;}
function displayAdminPage(){
    global $params,$c,$k,$a;
    if(!$a->check("user.admin.panel"))return;
    $this->displayPanel();
    switch($params[1]){
        case 'users':   if($a->check("user.admin.users"))$this->displayUserManagementPage();break;
        case 'groups':  if($a->check("user.admin.groups"))$this->displayGroupsManagementPage();break;
        case 'fields':  if($a->check("user.admin.fields"))$this->displayFieldsManagementPage();break;
        case 'edituser':if($a->check("user.admin.users.edit"))$this->displayEditUserPage();break;
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
            <input autocomplete="off" type="text" name="f" value="<?=$_GET['f']?>" style="width:50px;"/>
            <input autocomplete="off" type="text" name="t" value="<?=$_GET['t']?>" style="width:50px;"/>
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
        <input autocomplete="off" type="text" name="title" placeholder="Groupname" value="<?=$group->title?>" />
        <input autocomplete="off" type="submit" name="action" value="<? if($group==null)echo('Add');else echo('Edit'); ?>" /><br />
        <textarea name="permissions" placeholder="Permissiontree" style="min-width:200px;min-height:100px;"><?=$group->permissions?></textarea>
        <? if($group!=null)echo('<br /><input type="submit" name="action" value="Delete" />'); ?>
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
    global $c;
    
    if($_POST['varname']!=''){
        if($_POST['title']!=''){
            if($_POST['action']=="Edit")$this->updateField($_POST['varname'], $_POST);
            if($_POST['action']=="Add" )$this->addField($_POST['varname'], $_POST['title'], $_POST['default'],$_POST['editable'],$_POST['displayed'],$_POST['type']);
            if($_POST['action']=="Delete")$this->deleteField($_POST['varname']);
        }
        $field = DataModel::getData("ud_fields","SELECT * FROM ud_fields WHERE varname=?",array($_POST['varname']));
    }
    $fields = DataModel::getData("ud_fields","SELECT * FROM ud_fields");
    ?><form class="box" method="post" action="#">
        <input autocomplete="off" type="text" name="varname" placeholder="Variable Name" value="<?=$field->varname?>" />
        <input autocomplete="off" type="text" name="title" placeholder="Field Title" value="<?=$field->title?>" />
        <input type="submit" name="action" value="<? if($field==null)echo('Add');else echo('Edit'); ?>" /><br />
        <textarea name="default" placeholder="Default Value" style="min-width:400px;min-height:100px;"><?=$field->default?></textarea><br />
        <select name="type">
            <option value="s" <? if($field->type=='s')echo('selected'); ?>>String</option>
            <option value="i" <? if($field->type=='i')echo('selected'); ?>>Number</option>
            <option value="u" <? if($field->type=='u')echo('selected'); ?>>URL</option>
            <option value="t" <? if($field->type=='t')echo('selected'); ?>>Text</option>
            <option value="d" <? if($field->type=='d')echo('selected'); ?>>Date</option>
            <option value="l" <? if($field->type=='l')echo('selected'); ?>>List</option>
        </select>
        <input type="checkbox" name="editable" value="1"  <? if($field->editable==1)echo('checked'); ?> /> Editable
        <input type="checkbox" name="displayed" value="1" <? if($field->displayed==1)echo('checked'); ?> /> Displayed
        <? if($field!=null)echo('<br /><input type="submit" name="action" value="Delete" />'); ?>
    </form>
    <div class="box" style="display:block;">
        <table>
            <thead><tr><th style="width:200px;">Varname</th><th style="width:200px;">Title</th><th>Default</th>
                    <th style="width:10px;">E</th><th style="width:10px;">D</th><th style="width:10px;">T</th></tr></thead>
            <tbody><?
                if(!is_array($fields))$fields=array($fields);
                foreach($fields as $f){
                    echo('<tr><td><form action="#" method="post"><input type="submit" name="varname" value="'.$f->varname.'" /></td>');
                    echo('<td>'.$f->title.'</td><td>'.nl2br($f->default).'</td><td>'.$f->editable.'</td><td>'.$f->displayed.'</td><td>'.$f->type.'</td></tr>');
                }
            ?></tbody>
        </table>
    </div><?
}

function displayEditUserPage(){
    if($_POST['userID']=='')$_POST['userID']=$_GET['userID'];
    switch($_POST['action']){
        case '':$_POST['action']='Add';
        case 'Add':
            $this->addUser($_POST['username'],$_POST['mail'],$_POST['password'],$_POST['status'],$_POST['group'],$_POST['displayname']);
            $_POST['action']='Edit';
            $_POST['status']='';
            break;
        case 'Edit': $this->updateUser($_POST['userID'], $_POST);break;
        case "Save Fields": $this->updateUserFields($_POST['userID'], $_POST);$_POST['action']='Edit';break;
        case "Save Permissions": $this->updateUserPermissions($_POST['userID'],$_POST['tree']);break;
        case "Delete": $this->deleteUser($_POST['userID']);header('Location: /'.PROOT.'/users');break;
    }
    
    $user = DataModel::getData("ud_users", "SELECT * FROM ud_users WHERE userID=?",array($_POST['userID']));
    $groups=DataModel::getData("ud_groups","SELECT title FROM ud_groups");
    if($user==null)$user = DataModel::getHull("ud_users");
    ?><form action="#" method="post" class="box">
        <h2 class="title">User Variables</h2>
        <label>UserID</label>       <input autocomplete="off" type="number" value="<?=$user->userID?>" readonly="readonly" placeholder="Generated"/><br />
        <label>Username</label>     <input autocomplete="off" type="text" required="required" name="username" value="<?=$user->username?>" placeholder="Required"/><br />
        <label>Displayname</label>  <input autocomplete="off" type="text" name="displayname" value="<?=$user->displayname?>" placeholder="Username"/><br />
        <label>Mail</label>         <input autocomplete="off" type="email" required="required" name="mail" value="<?=$user->mail?>" placeholder="Required"/><br />
        <label>Password</label>     <input autocomplete="off" type="password" required="required" name="password" value="<?=$user->password?>" placeholder="Random"/><br />
        <label>Secret</label>       <input autocomplete="off" type="text" name="secret" value="<?=$user->secret?>" placeholder="Generated" <? if($_POST['action']=="Add")echo('disabled="disabled"'); ?>/><br />
        <label>Filename</label>     <input autocomplete="off" type="text" name="filename" value="<?=$user->filename?>" placeholder="noguy.png" <? if($_POST['action']=="Add")echo('disabled="disabled"'); ?>/><br />
        <label>Time</label>         <input autocomplete="off" type="number" name="time" value="<?=  strtotime($user->time)?>" placeholder="Generated" <? if($_POST['action']=="Add")echo('disabled="disabled"'); ?>/><br />
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
    </form>
        
    <? $fields = DataModel::getData('ud_fields','SELECT `title`,`varname`,`default`,`type` FROM ud_fields');
    $ufields = DataModel::getData('ud_field_values','SELECT varname,value FROM ud_field_values WHERE userID=?',array($user->userID));
    if(!is_array($fields))$fields=array($fields);if(!is_array($ufields))$ufields=array($ufields);?>
    <form action="#" method="post" class="box">
        <h2 class="title">Fields</h2>
        <? foreach($fields as $f){
            $u = null;
            foreach($ufields as $nu){if($nu->varname==$f->varname){$u=$nu;break;}}
            if($u->value=='')$u->value=$f->default;
            
            echo('<label>'.$f->title.'</label>');
            switch($f->type){
                case 'i':echo('<input autocomplete="off" type="number" class="number" name="val'.$f->varname.'" value="'.$u->value.'" placeholder="'.$f->default.'" />');break;
                case 's':echo('<input autocomplete="off" type="text" class="string" name="val'.$f->varname.'" value="'.$u->value.'" placeholder="'.$f->default.'" />');break;
                case 'u':echo('<input autocomplete="off" type="url" class="url" name="val'.$f->varname.'" value="'.$u->value.'" placeholder="'.$f->default.'" />');break;
                case 'd':echo('<input autocomplete="off" type="date" class="date" name="val'.$f->varname.'" value="'.$u->value.'" placeholder="'.$f->default.'" />');break;
                case 't':echo('<textarea type="text" class="text" name="val'.$f->varname.'" placeholder="'.$f->default.'">'.$u->value.'</textarea>');break;
                case 'l':$vals=explode(";",$u->value);$k->interactiveList("val".$f->varname,$vals,$vals,$vals,true);break;
            }
            echo('<br />');
        } ?>
        <input type="hidden" name="userID" value="<?=$user->userID?>" />
        <input type="submit" name="action" value="Save Fields" />
    </form>
        
    <? $permissions = DataModel::getData('ud_permissions','SELECT tree FROM ud_permissions WHERE UID=?',array($user->userID)); ?>
    <form action="#" method="post" class="box">
        <a class="button flRight" id="addTreeBranch" title="Add Row">+</a>
        <h2 class="title">Permissions</h2>
        <div id="permissionTree">
            <? global $c;$groupperms = $c->getData("SELECT permissions FROM ud_groups WHERE title=?",array($user->group));
            $groupperms = explode("\n",$groupperms[0]['permissions']);
            foreach($groupperms as $p){
                echo('<input type="text" disabled="disabled" style="width:90%" value="'.$p.'" /><br />');
            }
            
            $permissions = explode("\n",$permissions->tree);$i=0;
            foreach($permissions as $p){
                echo('<input type="text" name="tree[]" style="width:90%" value="'.$p.'" id="branch'.$i.'" />');
                echo('<a class="button removeBranch" value="'.$i.'" >-<br /></a>');
                $i++;
            } ?>
        </div>
        <br />
        <input type="hidden" name="userID" value="<?=$user->userID?>" />
        <input type="submit" name="action" class="flRight" value="Save Permissions" />
        <script type="text/javascript">
            $(document).ready(function(){
                $("#addTreeBranch").click(function(){
                    $("#permissionTree").append('<input type="text" style="width:90%" name="tree[]" /><br />');
                });
                $(".removeBranch").each(function(){$(this).click(function(){
                    $("#branch"+$(this).attr('value')).remove();
                    $(this).remove();
                });});
            });
        </script>
    </form><?
}

function addUser($username,$mail,$password,$status='',$group='Registered',$displayname=''){
    global $c,$k,$l;
    $username=$k->sanitizeString($username);
    if(!in_array($status,array('activated','banned','system')))$status='inactive';
    $status=substr($status,0,1);
    if($displayname=='')$displayname=$username;
    $secret=$k->generateRandomString(31);
    $password=hash('sha512',$password);
    $c->query("INSERT INTO ud_users VALUES(NULL,?,?,?,?,?,?,?,?,?)",array($username,$mail,$password,$secret,$displayname,'',$group,$status,time()));
    
    $l->triggerHook("USERadd",$this,array($c->insertID()));
    Toolkit::log("Added user @".$c->insertID());
}

function updateUser($userID,$fields){
    global $l;
    $user = DataModel::getData("ud_users", "SELECT * FROM ud_users WHERE userID=?",array($userID));
    if($user!=null){
        if($fields['password']!=$user->password)$fields['password']=hash('sha512',$fields['password']); //Hash password
        foreach($fields as $key => $value){$user->$key = $value;}
        $user->saveData();
    }
    
    $l->triggerHook("USERupdate",$this,array($userID));
    Toolkit::log("Updated user @".$userID);
}

function updateUserFields($userID,$values,$prefix="val"){
    global $c,$k,$l;
    $fields = DataModel::getData('ud_fields','SELECT varname,`type` FROM ud_fields');
    if(!is_array($fields))$fields=array($fields);
    $ufield = DataModel::getHull('ud_field_values');
    $ufield->userID=$userID;
    $c->query("DELETE FROM ud_field_values WHERE userID=?",array($userID));
    foreach($fields as $f){
        $valid=false;
        switch($f->type){
            case 'i':if(is_numeric($values[$prefix.$f->varname]))           $valid=true;break;
            case 's':if(strpos($values[$prefix.$f->varname],"\n")===FALSE)  $valid=true;break;
            case 'u':if($k->checkURLValidity($values[$prefix.$f->varname])) $valid=true;break;
            case 'd':if($k->checkDateValidity($values[$prefix.$f->varname]))$valid=true;break;
            case 't':                                                       $valid=true;break;
            case 'l':                                                       $valid=true;break;
        }
        if($valid){
            $ufield->varname = $f->varname;
            $ufield->value = $values[$prefix.$f->varname];
            $ufield->insertData();
        }
    }
    
    $l->triggerHook("UPDATEfields",$this,array($userID));
    //Toolkit::log("Updated user fields for @".$userID);
}

function updateUserPermissions($userID,$tree){
    global $c,$l;
    $ftree = "";
    foreach($tree as $branch){
        if(trim($branch)!='')$ftree.=strtolower(trim($branch))."\n";
    }
    $ftree = trim($ftree);
    $c->query("UPDATE ud_permissions SET tree=? WHERE UID=?",array($ftree,$userID));
    
    $l->triggerHook("USERupdatePermissions",$this,array($userID));
    Toolkit::log("Updated permissions for @".$userID);
}

function deleteUser($userID){
    global $c,$l;
    $c->query('DELETE FROM ud_users WHERE userID=?',array($userID));
    
    $l->triggerHook("USERdelete",$this,array($userID));
    Toolkit::log("Deleted user '".$userID."'");
}

function addGroup($groupname,$tree){
    global $l;
    $group = DataModel::getHull("ud_groups");
    $group->title=$groupname;
    $group->permissions=$tree;
    $group->insertData();
    
    $l->triggerHook("GROUPadd",$this,array($groupname));
    Toolkit::log("Added group '".$groupname."'");
}

function updateGroup($groupname,$changes){
    global $l;
    $group = DataModel::getData("ud_groups", "SELECT * FROM ud_groups WHERE title=?",array($groupname));
    foreach($changes as $key=>$value){$group->$key = $value;}
    $group->saveData();
    
    $l->triggerHook("GROUPupdate",$this,array($groupname));
    Toolkit::log("Updated group '".$groupname."'");
}

function deleteGroup($groupname){
    global $c,$l;
    $c->query("DELETE FROM ud_groups WHERE title=?",array($groupname));
    
    $l->triggerHook("GROUPdelete",$this,array($groupname));
    Toolkit::log("Deleted group '".$groupname."'");
}

function addField($fieldname,$title,$default='',$editable=0,$displayed=0,$type='s'){
    global $l;
    if($editable=='')$editable=0;
    if($displayed=='')$displayed=0;
    if($default=='')$default=' ';
    $field = DataModel::getHull("ud_fields");
    $field->varname=$fieldname;
    $field->title=$title;
    $field->default=$default;
    $field->editable=$editable;
    $field->displayed=$displayed;
    $field->type=$type;
    $field->insertData();
    
    $l->triggerHook("FIELDadd",$this,array($fieldname));
    Toolkit::log("Added field '".$fieldname."'");
}

function updateField($fieldname,$changes){
    global $l;
    $field = DataModel::getData("ud_fields", "SELECT * FROM ud_fields WHERE varname=?",array($fieldname));
    foreach($changes as $key=>$value){$field->$key = $value;}
    $field->saveData();
    
    $l->triggerHook("FIELDupdate",$this,array($fieldname));
    Toolkit::log("Updated field '".$fieldname."'");
}

function deleteField($fieldname){
    global $c,$k,$l;
    $c->query("DELETE FROM ud_fields WHERE varname=?",array($fieldname));
    $c->query("DELETE FROM ud_field_values WHERE varname=?",array($fieldname));
    
    $l->triggerHook("FIELDdelete",$this,array($fieldname));
    $k->log("Deleted field '".$fieldname."'");
}

}
?>