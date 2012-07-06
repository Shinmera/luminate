<? class Twatter extends Module{
public static $name="Twatter";
public static $author="NexT";
public static $version=0.05;
public static $short='twatter';
public static $required=array("Themes","Auth");
public static $hooks=array("foo");

function displayAPIPost(){
    global $a,$c,$l;
    if($a->check('twatter.post')){
        if(strlen($_POST['text'])>$c->o['_post_limit'])die('Post size limit exceeded.');
        if(strlen($_POST['text'])<3)die('Update too short.');
        if(!Toolkit::updateTimestamp('twatter_post', 3))die('Please wait 3 seconds between posts.');
        
        global $status;
        $status = DataModel::getHull('twatter_status');
        $status->time=time();
        $status->text=$_POST['text'];
        $status->user=$a->user->username;
        $status->insertData();
        
        preg_replace_callback('`\@([\w]*)`is', array(&$this,'parseCallback'), $status->text);
        $l->triggerPOST('Twatter','Twatter',$status->user,$status->text,'',Toolkit::url('twatter','view/'.$c->insertID()),substr($status->text,0,30).'...');
        die('Post succeeded!');
    }else die('Insufficient privileges.');
}
function parseCallback($matches){
    global $status,$c,$l;
    $l->triggerPOST('Twatter','Twatter',$status->user,$status->text,$marches[1],Toolkit::url('twatter','view/'.$c->insertID()),substr($status->text,0,30).'...');
}

function displayAPIFollow(){
    global $a,$l,$c;
    if($a->check('twatter.post')){
        if(DataModel::getData('ud_users','SELECT username FROM ud_users WHERE username LIKE ?',array($_POST['user']))==null)die('No such user.');
        if(!Toolkit::updateTimestamp('twatter_follow', 3))die('Please wait 3 seconds between follow changes.');
        
        $follow = DataModel::getData('twatter_follow','SELECT user,following FROM twatter_follow WHERE user LIKE ? and following LIKE ?',array($a->user->username,$_POST['user']));
        if($follow==null){
            $follow = DataModel::getHull('twatter_follow');
            $follow->user = $a->user->username;
            $follow->following = $_POST['user'];
            $follow->insertData();
            $l->triggerPOST('Twatter','Twatter',$follow->user,$follow->user.' started following '.$follow->following,$follow->following);
            $l->triggerHook('twatterFollow','Twatter',$follow->following);
            die('Followed!');
        }else{
            $follow->deleteData();
            $l->triggerHook('twatterUnfollow','Twatter',$follow->following);
            die('Unfollowed!');
        }
    }else die('Insufficient privileges.');
}

function displayTwatter(){
    global $params,$t,$a;
    switch($params[0]){
        case 'view':
            $post = DataModel::getData('twatter_status','SELECT statusID,user,text,time FROM twatter_status WHERE statusID=?',array($params[1]));
            if($post==null){
                $t->openPage('404 - Twatter');
                include(PAGEPATH.'404.php');
            }else{
                $t->openPage('Post '.$post->statusID.' by '.$post->user.' - Twatter');
                $this->displayPost($post);
            }break;
        case 'user':
            if(DataModel::getData('SELECT userID FROM ud_users WHERE username LIKE ?',array($params[1]))==null){
                $t->openPage('404 - Twatter');
                include(PAGEPATH.'404.php');
            }else{
                $t->openPage($params[1].' - Twatter');
                $this->displayTwatPage($params[1]);
            }break;
        default:
            if($a->check('twatter.post')){
                $t->openPage('Twatter Home');
                $this->displayHomePage();
            }else{
                $t->openPage('403 - Twatter');
                include(PAGEPATH.'403.php');
            }break;
    }
    $t->closePage();
}

function displayHomePage(){
    global $a,$l;
    
    $l->triggerHook('twatterHomePage','Twatter');
    ?>
    <form id="postForm" method="POST" action="<?=PROOT?>api/twatterPOST">
        <input type="text" name="text" placeholder="Currently I am..." />
        <input type="submit" id="post" value="Post" />
        <span id="response"></span>
    </form>
    <script type="text/javascript">
        $(function(){
            $("#post").click(function(){
                $.post($("#postForm").attr("action"),$("#postForm").serialize(),function(data){
                    $("#response").html(data);
                });
                return false;
            });
        });
    </script>
    <?
    $max = DataModel::getData('twatter_status','SELECT COUNT(statusID) AS status FROM twatter_status 
                                                                                 LEFT JOIN twatter_follow ON twatter_status.user = twatter_follow.following
                                                                                 WHERE twatter_follow.user LIKE ?',array($a->user->username));
    Toolkit::sanitizePager($max->status);
    $status = DataModel::getData('twatter_status','SELECT statusID,user,text,time FROM twatter_status 
                                                                                 LEFT JOIN twatter_follow ON twatter_status.user = twatter_follow.following
                                                                                 WHERE twatter_follow.user LIKE ?
                                                                                 ORDER BY time DESC LIMIT '.$_GET['f'].','.$_GET['s'],array($a->user->username));
    
    ?><div class="posts"><?
        Toolkit::displayPager();
        foreach($status as $stat){$this->displayPost($stat);}
    ?></div><?
}

function displayTwatPage($twat){
    global $l,$a;
    $max = DataModel::getData('twatter_status','SELECT COUNT(statusID) AS status FROM twatter_status WHERE user LIKE ?',array($twat));
    Toolkit::sanitizePager($max->status);
    $status = DataModel::getData('twatter_status','SELECT statusID,user,text,time FROM twatter_status WHERE user LIKE ? ORDER BY time DESC LIMIT '.$_GET['f'].','.$_GET['s'],array($twat));
    $following = DataModel::getData('twatter_follow','SELECT following FROM twatter_follow WHERE user LIKE ? AND following LIKE ?',array($a->user->username,$twat));
    
    $l->triggerHook('twatterUserPage','Twatter',$twat);
    
    if($following==null){?><a title="<?=$twat?>" id="followButton" class="follow"   href="<?=PROOT?>api/twatterFOLLOW">Follow</a><?}
    else                {?><a title="<?=$twat?>" id="followButton" class="unfollow" href="<?=PROOT?>api/twatterFOLLOW">Unfollow</a><?}
    ?><div class="posts"><?
        Toolkit::displayPager();
        foreach($status as $stat){$this->displayPost($stat);}
    ?></div>
    <script type="text/javascript">
        $(function(){
            $("#followButton").click(function(){
                $.post($("#followButton").attr("href"),{'user':$("#followButton").attr("title")},function(data){
                    $("#followButton").html(data);
                });
                return false;
            });
        });
    </script><?
}

function displayPost($post){
    global $l;
    ?><div id="<?=$post->statusID?>" class="status">
        <blockquote><?=$l->triggerParse('Twatter',$post->text);?></blockquote>
        <div class="statusInfo">
            <a href="<?=PROOT.'show/'.$post->statusID?>"><?=Toolkit::toDate($post->time)?></a>
            by <?=Toolkit::getUserPage($post->user)?>
            <?=$l->triggerHook('twatterPostInfo','Twatter',$post);?>
        </div>
    </div><?
}

}?>