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
    echo('<div class="box">A</div>');
}
}
?>