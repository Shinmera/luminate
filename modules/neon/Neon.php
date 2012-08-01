<? class Neon extends Module{
public static $name='Neon';
public static $author='NexT';
public static $version=2.01;
public static $short='neon';
public static $required=array('Auth','Themes');
public static $hooks=array('foo');

function displayLogin(){
    global $t,$a,$k,$params;
    if($a->user == null){
        
        if($_POST['action']=='Login'){
            $err=null;
            if($_POST['username']=='')$err[1]='Username missing!';
            if($_POST['password']=='')$err[2]='Password missing!';
            if($err==null){
                if($a->login($_POST['username'],$_POST['password']))    header('Location: '.$k->url('www',''));
                else                                                    $err[0]='Invalid username or password.';
            }
        }
        
        $t->openPage('Login');
        ?><div id='pageNav'>
            <div style='display:inline-block'>
                <h1 class='sectionheader'>Login</h1>
            </div>
            <div class='tabs'>
                <a href='<?=$k->url('login','')?>' class='tab <? if($params[0]!='register')echo('activated'); ?>'>Login</a>
                <a href='<?=$k->url('login','register')?>' class='tab <? if($params[0]=='register')echo('activated'); ?>'>Register</a>
            </div>
        </div><?
        
        if($params[0]=='register')$this->displayRegisterPage();
        else{?>
            <center><form action='#' method='post'>
                <?=$err[0]?><br />
                <label>Username: </label><input autofocus='autofocus' name='username' type='text' maxlength='32' required /><label><?=$err[1]?></label><br />
                <label>Password: </label><input                       name='password' type='password' maxlength='64' required /><label><?=$err[2]?></label><br />
                <input type='submit' name='action' value='Login' />
            </form></center><?
        }
        $t->closePage();
    }else{
        if($params[0]=='logout')$a->logout();
        header('Location: '.$k->url('www',''));
    }
}

function displayRegisterPage(){
    global $a,$k,$c,$params;
    require_once(CALLABLESPATH.'recaptchalib.php');
    
    if($params[1]!=''&&$params[2]!=''){
        $user = DataModel::getData('ud_users','SELECT `username`,`group`,`status` FROM ud_users WHERE `username`=? AND `secret`=? LIMIT 1;',array($params[1],$params[2]));
        if($user==null)$err[0]='Wrong activation code or user!';
        else{
            if($user->status=='u'){
                $user->group='Registered';
                $user->status='a';
                $user->saveData();
                $suc[0]='Account activated successfully! You can now <a href="/">log in</a>.';
                Toolkit::log('User "'.$params[1].'" activated.');
            }else{
                $err[0]='This account has already been activated.';
            }
        }
    }
    
    if($_POST['action']=='Register'){
        
        $err=null;
        if(strlen($_POST['username'])<=3){
            $err[1]='Username must be longer than 3 characters.';
        }
        
        if(DataModel::getData('ud_users','SELECT userID FROM ud_users WHERE LOWER(username) = ? LIMIT 1',array(strtolower($_POST['username'])))!=null){
            $err[1]='This username is taken.';
        }
        
        if($_POST['username']!=$k->sanitizeString($_POST['username'])){
            $_POST['username']=$k->sanitizeString($_POST['username']);
            $err[1]='Username adapted. Is this ok?';
        }
        
        
        if(strlen($_POST['password'])<=5){
            $err[2]='Password must be longer than 5 characters.';
        }
        
        if($_POST['password']!=$_POST['repeat']){
            $err[3]='The passwords don\'t match.';
        }
        
        if(!$k->checkMailValidity($_POST['email'])){
            $err[4]='This address is invalid.';
        }
        
        if($_POST['toc']!='accepted'&&$c->o['toc_url']!=''){
            $err[5]='You must read and accept the TOC.';
        }
        
        if($c->o['recaptcha_key_private']==''){
            $resp = recaptcha_check_answer ($c->o['recaptcha_key_private'],
                                            $_SERVER['REMOTE_ADDR'],
                                            $_POST['recaptcha_challenge_field'],
                                            $_POST['recaptcha_response_field']);
            if(!$resp->is_valid)$err[6]='Sorry, the captcha wasn\'t entered correctly.';
        }
        
        if($err==null){
            $activationCode=$k->generateRandomString(31);
            $user = DataModel::getHull('ud_users');
            $user->username=$_POST['username'];
            $user->mail=$_POST['email'];
            $user->password=$a->getPasswordHash($_POST['password'],$activationCode);
            $user->secret=$activationCode;
            $user->displayname=$_POST['username'];
            $user->group='Unregistered';
            $user->status='u';
            $user->time=time();
            
            Toolkit::log('User "'.$_POST['username'].'" ('.$_POST['email'].') registered.');
            $regurl=$k->url('login','register/'.$_POST['username'].'/'.$activationCode);
            
            $message = '
                <html>
                <head>
                    <title>Account confirmation for TyNET</title>
                </head>
                <body>
                    Welcome to TymoonNET, '.$_POST['username'].'.<br /><br />
                    To complete the registration procedure, please open this URL in your browser:<br />
                    <a href="'.$regurl.'">'.$regurl.'</a><br />
                    After that, you will be able to log in and change your account settings.<br /><br />
                    Greetings, the TyNET staff.
                </body>
                </html>
            ';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

            // Additional headers
            $headers .= 'To: <'.$_POST['email'].'>' . "\r\n";
            $headers .= 'From: TymoonNET <noreply@tymoon.eu>' . "\r\n";
            
            if(mail($_POST['email'],'Account confirmation for TyNET',$message,$headers)){
                $suc[0]='Your account has been registered successfully! Please check your e-mail for the activation code.';
            }else{
                $user->status='a';
                $suc[0]='Your account has been registered successfully! You can now <a href="'.PROOT.'">log in</a>.';
            }
            $user->insertData();
        }
    }
    
    if($suc[0]!=""){ ?><div class="success"><?=$suc[0]?></div>
    <? }else{ 
        if($err[0]!=""){?><div class="failure"><?=$err[0]?></div>
        <? }else{ ?>
        <script type="text/javascript">var RecaptchaOptions = {theme : 'clean'};</script>
        <center><form action="#" method="post" style="text-align:left;display:inline-block;width:450px;white-space:nowrap;">
            <label>Username: </label><input autofocus="autofocus" name="username" type="text"     maxlength="32" required value="<?=$_POST['username']?>" /> <label class="fixed"><?=$err[1]?></label><br />
            <label>Password: </label><input                       name="password" type="password" maxlength="64" required value="<?=$_POST['password']?>" /> <label class="fixed"><?=$err[2]?></label><br />
            <label>Repeat:   </label><input                       name="repeat"   type="password" maxlength="64" required value="<?=$_POST['repeat']?>" />   <label class="fixed"><?=$err[3]?></label><br />
            <label>E-Mail:   </label><input                       name="email"    type="mail"     maxlength="35" required value="<?=$_POST['email']?>" />    <label class="fixed"><?=$err[4]?></label><br />
            <? if($c->o['toc_url']!=""){ ?>
                <label><a href="<?=$c->o['toc_url']?>">Terms Of Content:</a></label>
                    <label><input         name="toc"      type="checkbox" value="accepted" required /> I accept.</label><label><?=$err[5]?></label><br />
            <? } ?>
            <? if($c->o['recaptcha_key_public']!=""){ ?>
                <div style="display:inline-block;"><?=recaptcha_get_html($c->o['recaptcha_key_public']);?></div><br /><label><?=$err[6]?></label><br />
            <? } ?>
            <input type="submit" name="action" value="Register" />
        </form></center>
    <? }}
}

function buildMenu($menu){
    global $a,$k,$l;
    if($a->user->userID=='')
        $menu[]=array('Login',$k->url("login",""),"float:right;");
    else{
        $innermenu=array(
            array('Settings',$k->url("user","panel")),
            array('Profile',$k->url("user",$a->user->displayname))
        );
        $innermenu=$l->triggerHookSequentially("buildMenu",'User',$innermenu);
        $innermenu[]=array('Logout',$k->url("login","logout"));
        $menu[]=array($a->user->displayname,$k->url("user","panel"),"float:right;",$innermenu);
    }
    return $menu;
}

function displayMainPage(){
    global $params,$l,$MODULECACHE,$DOMINATINGMODULE;
    $DOMINATINGMODULE=$l->loadModule('User'); //Spoof module.
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
    $pages = $l->triggerHookSequentially('SETTINGSNavbar',"User",$pages);
    ?><div id='pageNav'>
        <div style="display:inline-block">
            <h1 class="sectionheader">Settings</h1>
        </div>
        <div class="tabs">
            <? foreach($pages as $page){
                if($a->check("user.".$page)){
                    if($page==$params[1])echo('<a href="'.$k->url("user","panel/".$page).'" class="tab activated">'.$page.'</a>');
                    else                 echo('<a href="'.$k->url("user","panel/".$page).'" class="tab">'.$page.'</a>');
                }
            }if(!in_array($params[1], $pages))echo('<a href="'.$k->url("user",$params[1]).'" class="tab activated">'.$params[1].'</a>'); ?>
        </div>
    </div><?
    
    if($a->check("user.profile")){
        include(MODULEPATH.$MODULECACHE['Neon_Private']);
        switch($params[1]){
            case 'Profile':displayControlPanelProfile();break;
            case 'Friends':displayControlPanelFriends();break;
            default:       $l->triggerHook('SETTINGS'.$params[1],"User");break;
        } 
    }else{
        echo(NO_ACCESS);
    }
    $t->closePage();
}

}
?>