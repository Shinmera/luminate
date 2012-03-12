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
    
}

function displayFieldsManagementPage(){
    
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
    
}

function deleteUser($userID){
    
}

function addField(){
    
}

function updateField(){
    
}

function deleteField(){
    
}

}
?>