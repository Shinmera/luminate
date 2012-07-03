<?
class Auth extends Module{
public static $name='Auth';
public static $version=1.8;
public static $short='a';
public static $required=array();
public static $hooks=array('foo');

var $udPTree=array();
var $user;

    public function __construct() {
        $this->auth($_COOKIE['v4username'],$_COOKIE['v4hash']);
    }

    function auth($name,$token){
        global $k;
        if($name==''||$token=='')return false;
        $this->loadUser($name);
        $ctoken = $this->composeToken($name,$this->user->secret);
        if($token!==$ctoken){
            $this->resetUser();
            return false;
        }
        if($this->user != null){
            $k->updateTimestamp('visit:'.$this->user->userID,0);
            return true;
        }else{
            $this->resetUser();
            return false;
        }
    }

    function login($name,$pass,$hash=true){
        global $c,$l;
        if($name==''||$pass=='')return false;
        $this->loadUser($name);
        if($hash)$pass=$this->getPasswordHash($pass);
        
        if($name==$this->user->username&&$this->user->password===$pass&&$this->user->status=='a'){
            $l->triggerHook('USERlogin',$this);
            $token = $this->composeToken($name,$this->user->secret);
            setcookie('v4username',$name,time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
            setcookie('v4hash',$token,time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
            setcookie('v4username',$name,time()+60*60*$c->o['cookie_life_h'],'/');
            setcookie('v4hash',$token,time()+60*60*$c->o['cookie_life_h'],'/');
            return true;
        }else{
            $this->resetUser();
            return false;
        }
    }

    function logout(){
        global $c,$l;
        $l->triggerHook('USERlogout',$this);
        setcookie('v4username',' ',time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
        setcookie('v4hash',' ',time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
        setcookie('v4username',' ',time()+60*60*$c->o['cookie_life_h'],'/');
        setcookie('v4hash',' ',time()+60*60*$c->o['cookie_life_h'],'/');
        $this->resetUser();
        return true;
    }
    
    function resetUser(){
        $this->user = null;
        //$this->udPTree = array();
    }

    function loadUser($name){
        global $c;
        $this->user = DataModel::getData('ud_users', 'SELECT userID,username,mail,password,secret,displayname,filename,`group`,`status`
                                                      FROM ud_users WHERE username LIKE ?', array($name));
        if($this->user == null){
            $this->udPTree=array();
            $results=$c->getData('SELECT permissions FROM ud_groups WHERE title=?',array('Unregistered'));
            $results=explode("\n",$results[0]['permissions']);
            foreach($results as $result){
                $base = explode('.',$result);
                $this->udPTree[$base[0]][]=array_slice($base,1);
            }
        }else{
            $this->udPTree=array();
            $results=$c->getData('SELECT permissions FROM ud_groups WHERE title=?',array($this->user->group));
            $results=explode("\n",$results[0]['permissions']);
            foreach($results as $result){
                $base = explode('.',$result);
                $this->udPTree[$base[0]][]=array_slice($base,1);
            }
            //Individual permissions override group permissions.
            $results=$c->getData('SELECT tree FROM ud_permissions WHERE UID=?',array($this->user->userID));
            $results=explode("\n",$results[0]['permissions']);
            foreach($results as $result){
                $base = explode('.',$result);
                $this->udPTree[$base[0]][]=array_slice($base,1);
            }
        }
    }

    //TODO: Add exclusion masks
    function check($tree){
        $tree=explode('.',trim(strtolower($tree)));
        if(array_key_exists('*',$this->udPTree))return true;
        if(!isset($this->udPTree[$tree[0]]))    return false;
        
        foreach($this->udPTree[$tree[0]] as $branch){
            if($branch=='*')return true;
            $level=1;
            foreach($branch as $node){
                if($node=='*')return true;              //Allow all below this, so we're good.
                if($tree[$level]=='*')return true;      //Search for any subnode.
                if($node!==$tree[$level])break;         //Mismatch on this tree node.
                if(count($branch)==$level)return true;  //We've traversed all the way down this branch
                $level++;                               //And found no mismatches, this has to be correct.
            }
        }
        return false;
    }

    function grant($uid,$tree){
        global $c;
        if(!$this->check('auth.permissions.grant'))return false;
        $results=$c->getData('SELECT tree FROM ud_permissions WHERE UID=?',array($uid));
        if(count($results)==0){
            $c->query('INSERT INTO ud_permissions VALUES(?,?)',array($uid,$tree),false);
        }else{
            $perms=explode("\n",$results[0]['tree']);
            if(!in_array($tree,$perms)){
                $perms[]=$tree;
                $c->query('UPDATE ud_permissions SET tree=? WHERE UID=?',array(implode("\n",$perms),$uid),false);
            }
        }
    }
    
    function changePassword($password,$user=-1){
        if($user==-1)$user=$this->user;
        else if(is_numeric($user))$user=DataModel::getData('ud_users','SELECT userID,password,secret FROM ud_users WHERE userID=?',array($user));
        $user->secret = Toolkit::generateRandomString(31);
        $user->password = $this->getPasswordHash($password,$user->secret);
        $user->saveData();
    }

    function composeToken($name,$pass){
        global $c;
        return hash('sha512',$c->o['salt0'].'-'.$name.'-'.$c->o['salt1'].'-'.$_SERVER['REMOTE_ADDR'].'-'.$c->o['salt2'].'-'.$pass.'-'.$c->o['salt3']);
    }
    
    function getPasswordHash($password,$secret=-1){
        if($secret==-1)$secret=$this->user->secret;
        return hash('sha512',$password.hash('md5',$secret));
    }
    
    function generateDelta($userID=-1,$group=''){
        global $k,$c;
        if($userID==-1){
            $tree = $k->toKeyString($this->udPTree);
            return substr(hash('sha512',$tree),0,10);
        }else{
            $udPTree=array();
            $results=$c->getData('SELECT permissions FROM ud_groups WHERE title=?',array($group));
            for($i=0;$i<count($results);$i++){
                $base = explode('.',$results[$i]['permissions']);
                $udPTree[$base[0]]=  array_slice($base,1);
            }
            //Individual permissions override group permissions.
            $results=$c->getData('SELECT tree FROM ud_permissions WHERE UID=?',array($userID));
            for($i=0;$i<count($results);$i++){
                $base = explode('.',$results[$i]['tree']);
                $udPTree[$base[0]]=  array_slice($base,1);
            }
            
            $tree = $k->toKeyString($udPTree);
            return substr(hash('sha512',$tree),0,10);
        }
    }

}
?>
