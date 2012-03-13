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
                if($a->login($_POST['username'],$_POST['password'])){
                    header("Location: ".$k->url("www",""));
                }else{
                    $err[0]="Invalid username or password.";
                }
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
    }else{
        header("Location: ".$k->url("www",""));
    }
    $t->closePage();
}

function displayPanel(){
    global $k;
    ?><div class="box">
        <div class="title">User</div>
        <ul class="menu">
            <a href="<?=$k->url("admin","User")?>"><li>Overview</li></a>
            <a href="<?=$k->url("admin","User/users")?>"><li>User Management</li></a>
            <a href="<?=$k->url("admin","User/fields")?>"><li>Custom Fields</li></a>
        </ul>
    </div><?
}

function displayAdminPage(){
    global $params,$c;
    if($params[1]=="users")         $this->displayUserManagementPage();
    else if($params[1]=="fields")   $this->displayFieldsManagementPage();
    else if($params[1]=="edituser")  $this->displayEditUserPage();
    else{
        $usercount  = $c->getData("SELECT COUNT(userID) AS usercount FROM ud_users");
        $mostrecent = $c->getData("SELECT username,time FROM ud_users ORDER BY time DESC LIMIT 1");
        ?><div class="box" style="display:block;">
            <div class="title">Overview</div>

            <a href="<?=$k->url("admin","User/users")?>" class="button">User Management</a>
            <a href="<?=$k->url("admin","User/fields")?>" class="button">Custom Fields</a>
        </div><?
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

function displayFieldsManagementPage(){
    
}

function displayEditUserPage(){
    $user = DataModel::getData("ud_users", "SELECT * FROM ud_users WHERE userID=?",array($_POST['userID']));
    if($user==null)$user = DataModel::getHull ("ud_users");
    foreach($_POST as $key => $value){
        $user->__set($key,$value);
    }
    if($_POST['action']=="Add"&&$_POST['status']!=''){
        $user->insertData();
    }if($_POST['action']=="Edit"&&$_POST['status']!=''){
        $user->saveData();
    }
    ?><form action="#" method="post" class="box">
        <label>UserID</label>       <input type="text" name="userID" value="<?=$user->userID?>" disabled="disabled" placeholder="Generated"/><br />
        <label>Username</label>     <input type="text" name="username" value="<?=$user->username?>" placeholder="Required"/><br />
        <label>Displayname</label>  <input type="text" name="displayname" value="<?=$user->displayname?>" placeholder="Username"/><br />
        <label>Mail</label>         <input type="text" name="mail" value="<?=$user->mail?>" placeholder="Required"/><br />
        <label>Password</label>     <input type="password" name="password" value="<?=$user->password?>" placeholder="Random"/><br />
        <label>Secret</label>       <input type="text" name="secret" value="<?=$user->secret?>" placeholder="Generated"/><br />
        <label>Filename</label>     <input type="text" name="filename" value="<?=$user->filename?>" placeholder="noguy.png"/><br />
        <label>Time</label>         <input type="text" name="time" value="<?=$user->time?>" placeholder="Generated"/><br />
        <label>Group</label>
            <select name="group">
                
            </select><br />
        <label>Status</label>
            <select name="status">
                <option value="a">Activated</option>
                <option value="i">Inactive</option>
                <option value="b">Banned</option>
                <option value="s">System</option>
            </select><br />
        <? if($_POST['action']=="Edit")echo('<input type="submit" name="action" value="Edit" />');
           else                        echo('<input type="submit" name="action" value="Add" />');?>
    </form><?
}

function addUser($username,$mail,$password,$status='',$group=0,$displayname=''){
    global $c,$k;
    $username=$k->sanitizeString($username);
    if(!in_array($status,array('activated','banned','system')))$status='inactive';
    $status=substr($status,0,1);
    if($displayname=='')$displayname=$username;
    $secret=$k->generateRandomString(31);
    $password=hash('sha512',$password);
    $c->query("INSERT INTO ms_users VALUES(NULL,?,?,?,?,?,?,?,?,?)",array($username,$mail,$password,$secret,$displayname,'',$group,$status,time()));
}

function updateUser($userID,$fields){
    $user = DataModel::getData("ud_users", "SELECT * FROM ud_users WHERE userID=?",array($userID));
    foreach($fields as $key => $value){
        $user->__set($key, $value);
    }$user->saveData();
}

function deleteUser($userID){
    global $c;
    $c->query('DELETE FROM ud_users WHERE userID=?',array($userID));
}

function addField(){
    
}

function updateField(){
    
}

function deleteField(){
    
}

}
?>