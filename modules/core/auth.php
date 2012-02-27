<?
class Auth{
var $name="Auth";
var $version=1.8;
var $short='a';
var $required=array('Sqlloader'=>'core/sqlloader.php');

var $udPBase=array();
var $udPTree=array();

    public function __construct() {
        global $c;
        if(!$c->base_loaded)$c->loadBase();
        $this->auth($_COOKIE['username'],$_COOKIE['hash']); 
    }

    function composeToken($name,$pass){
        global $c;
        return hash("sha512",$c->o['salt0']."-".$name."-".$c->o['salt1']."-".$_SERVER['REMOTE_ADDR']."-".$c->o['salt2']."-".$pass."-".$c->o['salt3']);
    }

    function authPass($name,$pass){
        global $c;
        $c->loadUsers("%",$name);
        if(in_array($name,$c->udUName)){
            if($c->udUPass[array_search($name,$c->udUName)]===$pass){
                return XERR_OK;
            }else{
                return XERR_INVALID_PASS;
            }
        }else{
            return XERR_INVALID_USER;
        }
    }

    function auth($name,$token){
        global $c,$k;
        if($name==""||$token=="")return XERR_INVALID_USER;
        $c->loadUsers("%",$name);
        $ctoken = $this->composeToken($name,$c->udUSecret[array_search($name,$c->udUName)]);
        if($token!==$ctoken)return XERR_INVALID_AUTH;
        if($c->authUser($name)===XERR_OK){
            $this->loadPermissions();
            $k->updateTimestamp("visit:".$c->userID,0);
            return XERR_OK;
        }else{
            $c->resetUser();
            return XERR_INVALID_USER;
        }
    }

    function login($name,$pass,$hash=true){
        global $c;
        if($hash)$pass=hash("sha512",$pass);
        if($this->authPass($name,$pass)===XERR_OK){
            $token = $this->composeToken($name,$c->udUSecret[array_search($name,$c->udUName)]);
            setcookie('username',$name,time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
            setcookie('hash',$token,time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
            setcookie('username',$name,time()+60*60*$c->o['cookie_life_h'],'/');
            setcookie('hash',$token,time()+60*60*$c->o['cookie_life_h'],'/');
            return XERR_OK;
        }else{
            return XERR_INVALID_AUTH;
        }
    }

    function logout(){
        global $c;
        setcookie('username',' ',time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
        setcookie('hash',' ',time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
        setcookie('username',' ',time()+60*60*$c->o['cookie_life_h'],'/');
        setcookie('hash',' ',time()+60*60*$c->o['cookie_life_h'],'/');
        $c->resetUser();
        return XERR_OK;
    }

    function authOID($id="",$method="",$action="login"){
        require CALLABLESPATH.'openid.php';
        try {
            $openid = new LightOpenID(HOST);
            if(!$openid->mode) {
                if($id!="") {
                    switch($method){
                        case 'wordpress': $id=$id.'.wordpress.com';
                        case 'aol': $id=$id.'openid.aol.com/';
                        case 'myspace': $id='www.myspace.com/'.$id;
                        case 'orange': $id='openid.orange.fr/'.$id;
                        case 'blogger': $id=$id.'.blogger.com';
                        case 'livejournal': $id=$id.'.livejournal.com';
                        case 'yahoo': $id='me.yahoo.com';
                        case 'google': $id='https://www.google.com/accounts/o8/id';
                        case 'myopenid': $id=$id.'.myopenid.com';
                        case 'steam': $id='steamcommunity.com/openid/';
                        case 'clickpass': $id='clickpass.com/public/'.$id;
                        case 'verisign': $id=$id.'.pip.verisignlabs.com';
                        case 'typepad': $id=$id.'.typepad.com';
                        case 'claimid': $id='claimid.com/'.$id;
                        case 'clavid': $id=$id.'.clavid.com';
                        case 'launchpad': $id='launchpad.net/~'.$id;
                        case 'ubuntu': $id='login.ubuntu.com';
                        case 'seznam': $id=$id.'.id.seznam.cz';
                        case 'xlogon': $id='http://xlogon.net/'.$id;
                        case 'hyves': $id='hyves.nl';
                        case 'mixi': $id='mixi.jp';
                        case 'virgilio': $id='virgilio.it';
                        case 'irtualna': $id='openid.wp.pl';
                        case 'flickr': $id='http://www.flickr.com'.$id;
                    }
                    $openid->identity = $id;
                    $openid->required = array('namePerson/friendly', 'contact/email');
                    $openid->optional = array('namePerson/first','namePerson/last','birthDate');
                    header('Location: ' . $openid->authUrl());
                }
            } elseif($openid->mode == 'cancel') {
                return XERR_OID_CANCEL;
            } else {
                $openid->validate();
                $info = $openid->getAttributes();

                if($action=="login"){
                    global $c;
                    $token=$this->composeToken($info['namePerson/friendly'],$c->udUSecret[array_search($info['namePerson/friendly'],$c->udUName)]);
                    setcookie('username',$info['namePerson/friendly'],time()+60*60*$c->o['cookie_life_h'],'/');
                    setcookie('hash',$token,time()+60*60*$c->o['cookie_life_h'],'/');
                }if($action=="register"){
                    //FIXME: HOOK TO REGISTRATION
                }

                return $openid->identity;
            }
        } catch(ErrorException $e) {
            return XERR_OID_GENERIC;
        }
    }

    function loadPermissions(){
        global $c;
        $this->udPBase=array();
        $this->udPTree=array();
        $results=$c->getData("SELECT base,tree FROM ud_permissions WHERE UID=?",array($c->userID));
        for($i=0;$i<count($results);$i++){
            $this->udPBase[]=$results[$i]['base'];
            $this->udPTree[]=$results[$i]['tree'];
        }
    }

    function check($tree){
        $tree=explode(".",trim(strtolower($tree)));
        if(in_array("*",$this->udPBase))return true;
        $bID=array_search($tree[0],$this->udPBase);
        if($bID===FALSE)return false;
        
        $perms=explode("\n",$this->udPTree[$bID]);
        for($i=0;$i<count($perms);$i++){
            $perms[$i]=trim(strtolower($perms[$i]));
            if(strrpos($perms[$i], ".*")===FALSE)$perms[$i].='.*';
            
            $permtree=explode(".",$perms[$i]);
            for($j=0;$j<count($permtree);$j++){
                if($permtree[$j]=="*")return true;
                if($permtree[$j]!=$tree[$j+1])break;
                if($j==count($tree)-2)return true;
            }
        }
        return false;
    }

    function grant($uid,$base,$tree){
        global $c;
        if(!$this->check("auth.permissions.grant"))return XERR_NO_ACCESS;
        $results=$c->getData("SELECT tree FROM ud_permissions WHERE UID=? AND BASE LIKE ?",array($uid,$base));
        if(count($results)==0){
            $c->query("INSERT INTO ud_permissions VALUES(?,?,?)",array($uid,$base,$tree),false);
        }else{
            $perms=explode("\n",$results[0]['tree']);
            if(!in_array($tree,$perms)){
                $perms[]=$tree;
                $c->query("UPDATE ud_permissions SET tree=? WHERE UID=? AND BASE LIKE ?",array(implode("\n",$perms),$uid,$base),false);
            }
        }
    }

}
?>
