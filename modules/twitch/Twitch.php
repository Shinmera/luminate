<? class Twitch extends Module{
public static $name="Twitch";
public static $author="NexT";
public static $version=0.01;
public static $short='twitch';
public static $required=array("Auth");
public static $hooks=array("foo");

var $twitter = null;

function __construct(){
    global $c;
    include(realpath(dirname(__FILE__))."/tmhOAuth.php");
    $this->twitter = new tmhOAuth(array(
        'consumer_key' => $c->o['twitch_consumer_key'],
        'consumer_secret' => $c->o['twitch_consumer_secret'],
    ));
    $this->userdata = null;
    $this->setUserAuth();
}

function displayUserPanel(){
    try{
        $name = $this->verifyUser();
        $auth = "Yes (".$name.")";
    }catch(Exception $e){
        $auth = "No (".$e->getMessage().")";
    }
    ?><form action="?tab=Twitter" method="post" name="Twitter">
        <?=($_POST['twitch_error']!='')? '<div class="failure">Failed to authenticate: '.$_POST['twitch_error'].'</div>' : ''?>
        <?=($_GET['twitch_error']!='') ? '<div class="failure">Failed to authenticate: '.$_GET['twitch_error'].'</div>' : ''?>
        <?=($_GET['twitch_ok']!='')    ? '<div class="success">Successfully authenticated!</div>' : ''?>
        <label>Credentials Available:</label>   <?=($this->userdata->secret == '')? 'No' : 'Yes'?><br />
        <label>Authentication Possible:</label> <?=$auth?><br />
        <label>Use Twitter:</label>             <?=($this->userdata->active != 1 )? 'No' : 'Yes'?><br />
        <input type="hidden" name="action" value="Twitter" />
        <input type="hidden" name="tab" value="Twitter" />
        <input type="submit" name="twitAction" value="Authenticate" />
        <input type="submit" name="twitAction" value="<?=($this->userdata->active == 1)? 'Disable' : 'Enable'?>" />
    </form><? 
}

function displayUserSave(){
    switch($_POST['twitAction']){
        case 'Enable':
            $this->userdata->active = 1;
            $this->userdata->saveData();
            break;
        case 'Disable':
            $this->userdata->active = 0;
            $this->userdata->saveData();
            break;
        case 'Authenticate':
            Toolkit::set('twitch_return',FULLURL);
            $params = array('oauth_callback'     => 'http://api.'.HOST.PROOT.'twitter');
            $this->twitter->config['user_secret'] = '';
            $this->twitter->config['user_token'] = '';
            $code = $this->twitter->request('POST', $this->twitter->url('oauth/request_token', ''), $params);

            if ($code == 200) {
                $tokens = $this->twitter->extract_params($this->twitter->response['response']); 
                $this->userdata->token = $tokens['oauth_token'];
                $this->userdata->secret = $tokens['oauth_token_secret'];
                $this->userdata->saveData();
                $authurl = $this->twitter->url("oauth/authenticate", '')."?oauth_token={$tokens['oauth_token']}";
                header('Location: '.$authurl);
            } else {
                $_POST['twitch_error'] = "Error ".$code;
            }
            break;
    }
}

function apiTwitterReturn(){
    global $c,$a;

    $code = $this->twitter->request('POST', $this->twitter->url('oauth/access_token', ''), array(
        'oauth_verifier' => $_REQUEST['oauth_verifier']
    ));

    if ($code == 200) {
        $tokens = $this->twitter->extract_params($this->twitter->response['response']);
        $this->userdata->token = $tokens['oauth_token'];
        $this->userdata->secret = $tokens['oauth_token_secret'];
        $this->userdata->saveData();
        header("Location: http://".$c->o['twitch_return'].'&twitch_ok=true');
    } else {
        $this->userdata->token = '';
        $this->userdata->secret = '';
        $this->userdata->saveData();
        header("Location: http://".$c->o['twitch_return'].'&twitch_error='.$code);
    }
}

function verifyUser(){
    $code = $this->twitter->request('GET',$this->twitter->url('1/account/verify_credentials'));
    if($code == 200){
        $resp = json_decode($this->twitter->response['response']);
        return $resp->screen_name;
    }else{
        throw new Exception("Error ".$code);
    }
}

function setUserAuth(){
    if($this->userdata==null){
        global $a;
        $this->userdata = DataModel::getData("tw_data","SELECT userID,token,secret,active FROM tw_data WHERE userID=?",array($a->user->userID));
        if($this->userdata != null){
            $this->twitter->config['user_secret'] = $this->userdata->secret;
            $this->twitter->config['user_token'] = $this->userdata->token;
        }else{
            $this->userdata = DataModel::getHull("tw_data");
            $this->userdata->userID = $a->user->userID;
            $this->userdata->secret = '';
            $this->userdata->token = '';
            $this->userdata->active = 0;
            $this->userdata->insertData();
        }
    }
}

function universalPostHook($args){
    try{
        $this->apiTweet($args['toModule'].' \''.$args['postTitle'].'\': '.$args['permalink']);
    }catch(Exception $e){}
}

function apiTweet($text=null){
    if($this->userdata->active != 1)
        throw new Exception("Twitter is not active for this user.");
    if($this->twitter->config['user_secret'] == "")
        throw new Exception("User is not authenticated.");

    if($text == '' || $text == null) $text = $_GET['text'];
    if($text == '') $text = $_POST['text'];
    if($text == ''){
        throw new Exception("No text defined.");
    }else{
        $code = $this->twitter->request("POST", $this->twitter->url('1/statuses/update'), array('status' => $text));
        if($code != 200){
            throw new Exception("Error ".$this->twitter->response['code']);
        }else{
            return true;
        }
    }
}

}?>