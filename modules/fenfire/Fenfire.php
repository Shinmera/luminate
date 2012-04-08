<?
class Fenfire{
public static $name="Fenfire";
public static $author="NexT";
public static $version=2.01;
public static $short='fenfire';
public static $required=array("Auth");
public static $hooks=array("foo");

function adminNavbar($menu){
    $menu[]="Fenfire";
    return $menu;
}

function displayPanel(){
    global $k,$a;
    ?><div class="box">
        <div class="title">Fenfire</div>
        <ul class="menu">
            <? if($a->check("fenfire.admin.comments")){ ?>
            <a href="<?=$k->url("admin","Fenfire/comments")?>"><li>Comment Management</li></a><? } ?>
            <? if($a->check("fenfire.admin.folders")){ ?>
            <a href="<?=$k->url("admin","Fenfire/folders")?>"><li>Organize Comment Folders</li></a><? } ?>
        </ul>
    </div><?
}

function displayAdminPage(){
    global $a,$c,$k,$params;
    if(!$a->check('fenfire.admin'))return;
    $this->displayPanel();
    switch($params[1]){
        case 'comments':if($a->check("fenfire.admin.comments"))$this->displayAdminComments();break;
        case 'folders':if($a->check("fenfire.admin.folders"))$this->displayAdminFolders();break;
    }
}

function displayAdminComments(){
    global $c,$k;
    
    if($_GET['action']=="M"){
        $mod = $c->getData("SELECT moderation FROM fenfire_comments WHERE commentID=? AND FID=?",array($_GET['commentID'],$_GET['FID']));
        if($mod[0]['moderation']=="1")$mod=0;else $mod=1;
        $c->query("UPDATE fenfire_comments SET moderation=? WHERE commentID=? AND FID=?",array($mod,$_GET['commentID'],$_GET['FID']));
        if($mod==1)$err="Comment blocked.";else $err="Comment approved.";
    }
    
    if($_GET['action']=="X"){
        $c->query("DELETE FROM fenfire_comments WHERE commentID=? AND FID=?",array($_GET['commentID'],$_GET['FID']));
        $err="Comment deleted.";
    }
    
    $max = $c->getData("SELECT COUNT(commentID) FROM fenfire_comments");$max=$max[0]['COUNT(commentID)'];
    switch($_GET['action']){
        case '<<':$_GET['f']=0;  $_GET['t']=50; break;
        case '<' :$_GET['f']-=50;$_GET['t']-=50;break;
        case '>' :$_GET['f']+=50;$_GET['t']+=50;break;
        case '>>':$_GET['f']=$max-50;$_GET['t']=$max;break;
    }
    //Sanitize user input
    if($_GET['f']<0||!is_numeric($_GET['f']))         $_GET['f']=0;
    if($_GET['t']<$_GET['f']||!is_numeric($_GET['t']))$_GET['t']=$_GET['f']+50;
    if($_GET['t']>$max)$_GET['t']=$max;
    if($_GET['f']>$_GET['t'])$_GET['f']=$_GET['t']-1;
    if($_GET['a']!=0&&$_GET['a']!=1)      $_GET['a']="0";
    if($_GET['t']-$_GET['f']>100)$_GET['t']=$_GET['f']+100;
    $orders = array('module','path','commentID','username','time');
    if(!in_array($_GET['o'],$orders))$_GET['o']='time';
    ?>
    <div class="box" style="display:block;">
        <?=$err?><br />
        <form>
            <input type="checkbox" name="b" value="1" <? if($_GET['b']=="1")echo("checked"); ?> />Show approved comments
            <input type="submit" name="dir" value="Show" /><br />
            <input type="submit" name="dir" value="<<" />
            <input type="submit" name="dir" value="<" />
            <input autocomplete="off" type="text" name="f" value="<?=$_GET['f']?>" style="width:50px;"/>
            <input autocomplete="off" type="text" name="t" value="<?=$_GET['t']?>" style="width:50px;"/>
            <input type="submit" name="dir" value="Go" />
            <input type="submit" name="dir" value=">" />
            <input type="submit" name="dir" value=">>" />
            <input type="hidden" name="order" value="<?=$_GET['o']?>" />
            <input type="hidden" name="asc" value="<?=$_GET['a']?>" />
        </form>
        <table>
            <thead><tr>
                <th style="width:60px;"></th>
                <th style="width:150px;"><a href="?o=module&a=<?=!$_GET['a']?>&b=<?=$_GET['b']?>">    Module</a></th>
                <th style="width:200px;"><a href="?o=path&a=<?=!$_GET['a']?>&b=<?=$_GET['b']?>">      Path</a></th>
                <th style="width:100px;"><a href="?o=commentID&a=<?=!$_GET['a']?>&b=<?=$_GET['b']?>"> ID</a></th>
                <th style="width:150px;"><a href="?o=username&a=<?=!$_GET['a']?>&b=<?=$_GET['b']?>">  Username</a></th>
                <th>Text</th>
                <th style="width:100px;"><a href="?o=time&a=<?=!$_GET['a']?>&b=<?=$_GET['b']?>">      Time</a></th>
            </tr></thead>
            <tbody>
                <? if($_GET['a']==0)$_GET['a']="DESC";else $_GET['a']="ASC";
                if($_GET['b']==1)$where="";else $where="WHERE fenfire_comments.moderation=1";
                $comments = DataModel::getData("fenfire_comments","SELECT fenfire_comments.commentID AS commentID,fenfire_comments.FID AS FID,".
                                        "fenfire_comments.username AS username,fenfire_comments.text AS `text`,fenfire_comments.time AS `time`,".
                                        "fenfire_comments.moderation AS `moderation`,fenfire_folders.module AS module,fenfire_folders.path AS path ".
                                        "FROM fenfire_comments INNER JOIN fenfire_folders ON fenfire_comments.FID = fenfire_folders.folderID ".
                                        $where." ORDER BY `".$_GET['o'].'` '.$_GET['a'].' LIMIT '.$_GET['f'].','.$_GET['t']);
                if($comments!=null){
                    if(!is_array($comments))$comments=array($comments);
                    foreach($comments as $comment){
                        if($comment->moderation==1)$mod="background-color:red;";else $mod="";
                        ?><tr>
                            <td style="<?=$mod?>"><form>
                                <input type="hidden" name="o" value="<?=$_GET['o']?>" />
                                <input type="hidden" name="a" value="<?=$_GET['a']?>" />
                                <input type="hidden" name="b" value="<?=$_GET['b']?>" />
                                <input type="hidden" name="commentID" value="<?=$comment->commentID?>" />
                                <input type="hidden" name="FID" value="<?=$comment->FID?>" />
                                <input type="submit" name="action" value="X" class="roundbutton" />
                                <input type="submit" name="action" value="M" class="roundbutton" />
                            </form></td>
                            <td><?=$comment->module?></td>
                            <td><?=$comment->path?></td>
                            <td><?=$comment->commentID?></td>
                            <td><?=$comment->username?></td>
                            <td><?=$comment->text?></td>
                            <td><?=$k->toDate($comment->time);?></td>
                        </tr><?
                    }
                }
                ?>
            <tbody>
        </table>
    </div><?
}

function displayAdminFolders(){
    
}

function getFolder($FID){
    if($FID==""){
        global $DOMINATINGMODULE,$SUPERIORPATH,$param,$c;
        $folder = DataModel::getHull("fenfire_folders");
        $folder->module = $DOMINATINGMODULE::$name;
        if($SUPERIORPATH=="")$folder->path=$param;
        else                 $folder->path=$SUPERIORPATH;
        $folder->open=1;
        $folder->insertData();
        $folder->folderID=$c->insertID();
        return $folder;
    }else{
        return DataModel::getData("fenfire_folders","SELECT * FROM fenfire_folders WHERE folderID=?",array($FID));
    }
}

function commentBox($FID=null){
    global $a,$l,$c;
    $saveduser=$_COOKIE['_session_user'];
    $savedmail=$_COOKIE['_session_mail'];
    if($FID==null){$FID=$this->getFolder("")->folderID;}
    ?>
    
    <form class="commentBox" method="post" action="<?=PROOT?>api.php?m=Comments&c=submitCommentForm">
        <b><a title="commentBox">Add a comment:</a></b><br />
        <? if($a->user==null) {?>
            <label><a href="<?=$k->url("login","")?>" title="If you have a registered account, please log in first.">Username:</a></label><input name="varuser" value="<?=$saveduser?>" type="text" /><br />
            <label><a title="Gravatar supported. Email will not be published.">E-Mail:</a></label>                                        <input name="varmail" value="<?=$savedmail?>" type="text" /><br />
        <? } ?>
            
        <? $l->triggerHook("EDITOR",$this); ?>
        <textarea Name="varsubject" id="varsubject" style="height:100px;"></textarea><br />
        
        <? if($a->user==null&&$c->o['recaptcha_key_public']!=''){
            require_once(TROOT.'callables/recaptchalib.php'); ?>
            <script type="text/javascript">var RecaptchaOptions = {theme : 'clean'};</script>
            <?=recaptcha_get_html($c->o['recaptcha_key_public']);?>
        <? } ?>
        <input type="submit" name="Submit" value="Submit" />
    <input type="hidden" name="varFID" value="<?=$FID?>">
    <input type="hidden" id="varresponse" name="varresponse" value=""></form><?
}

function submitCommentForm(){
    global $a,$k,$c;
    
    $spamcheck=0;
    if(!$k->updateTimestamp("comment",$c->o['post_timeout'])){
        $k->err("Please wait ".$c->o['post_timeout']." seconds between posts.",false,true);
        return;
    }
    
    if($a->user==null){
        if($_POST['varuser']==""){$k->err("No username given.",false,true);return;}
        if($_POST['varmail']==""){$k->err("No email address given.",false,true);return;}
        if(DataModel::getData("SELECT userID FROM ud_users WHERE username=? OR displayname=? LIMIT1",array($_POST['varuser'],$_POST['varuser']))!=null){
            $k->err("You cannot use the name of a registered user.<br />If this name belongs to you, please <a href='".$k->url("login","")."'>log in</a> first.",false,true);
            return;
        }
        setcookie("_session_user",$_POST['varuser'],time()+60*60*24*$c->o['cookie_life_h']);
        setcookie("_session_mail",$_POST['varmail'],time()+60*60*24*$c->o['cookie_life_h']);
        
        //Check recaptcha
        if($c->o['recaptcha_key_private']!=''){
            require_once(CALLABLESPATH.'recaptchalib.php');
            $resp = recaptcha_check_answer ($c->o['recaptcha_key_private'],
                                            $_SERVER["REMOTE_ADDR"],
                                            $_POST["recaptcha_challenge_field"],
                                            $_POST["recaptcha_response_field"]);
            if (!$resp->is_valid){$k->err("Invalid captcha.",false,true);return;}
        }
        
        //Check akismet
        if($c->o['akismet_key']!=''){
            require_once('./callables/akismet.php');
            $akismet = new Akismet(HOST ,$c->o['akismet_key']);
            $akismet->setCommentAuthor($username);
            $akismet->setCommentAuthorEmail($mail);
            $akismet->setCommentContent($subject);
            $akismet->setPermalink(HOST);
            if($akismet->isCommentSpam())$spamcheck=1;
        }
    }else{
        $_POST['varuser']=$a->user->username;
        $_POST['varmail']=$a->user->mail;
    }
    if($_POST['varFID']==""){$k->err("Post malformed.",false,true);return;}
    if($_POST['varsubject']==""){$k->err("No text given.",false,true);return;}
    if($c->o['comment_size_min']!=''&&strlen($_POST['varsubject'])<$c->o['comment_size_min']){$k->err("Post too short.",false,true);return;}
    if($c->o['comment_size_max']!=''&&strlen($_POST['varsubject'])>$c->o['comment_size_max']){$k->err("Post too long.",false,true);return;}

    $post = DataModel::getHull("fenfire_comments");
    $folder = $this->getFolder($_POST['varFID']);
    //Calculate nesting level
    $parent = null;
    if($_POST['varresponse']==""){
        $parent = DataModel::getData("fenfire_comments", "SELECT commentID,level FROM fenfire_comments WHERE commentID=?",$array($_POST['varresponse']));
        if($parent!=null){
            if($parent->level<$c->o['comment_max_level'])$post->level=$parent->level+1;
            else                                         $post->level=$c->o['comment_max_level'];
        }
    }else{
        $post->level=0;
    }
    
    //Insert post
    $post->FID=$folder->folderID;
    $post->username=$_POST['varuser'];
    $post->mail=$_POST['varmail'];
    $post->text=$_POST['varsubject'];
    $post->time=time();
    $post->moderation=$spamcheck;
    $post->insertData();
    $post->commentID=$c->insertID();
    
    //Calculate new order
    $order = explode(";",$folder->order);
    if($parent==null||!in_array($parent->commentID, $order)){
        $order[]=$post->commentID;
    }else{
        for($i=0,$count=count($order);$i<$count;$i++){
            if($order[$i]==$parent->commentID){
                $k->array_insert($order, $post->commentID,$i);
                break;
            }
        }
    }
    $folder->order=implode(";",$order);
    $folder->saveData();
    
    Toolkit::log("Comment from ".$_POST['varuser']." (".$_POST['varmail'].") for  added.");
    header('Location: '.$_SERVER['HTTP_REFERER']);
}

function modComment($CID,$moderation){
    global $a,$c;
    if($a->check('fenfire.admin.comments.mod')){
        $c->query("UPDATE fenifre_comments SET moderation=? WHERE commentID=? LIMIT 1",array($moderation,$CID));
        Toolkit::log("Comment moderation for ID".$CID." set to ".$moderation);
    }
}

function delComment($CID){
    global $a,$c;
    if($a->check('enfire.admin.comments.delete')){
        $folder = DataModel::getData("fenfire_folders","SELECT fenfire_folders.order FROM fenfire_folders ".
                                                       "INNER JOIN fenfire_comments ON fenfire_folders.folderID = fenfire_comments.FID ".
                                                       "WHERE fenfire_comments.commentID=?",array($CID));
        $folder->order = removeFromList($CID,$folder->order,";");
        $folder->saveData();
        $c->query("DELETE FROM fenfire_comments WHERE commentID=?",array($CID));
        Toolkit::log("Comment #".$CID." deleted.");
    }
}

function cleanComments($FID){
    global $c;
    $c->query("DELETE FROM fenfire_comments WHERE FID=?",array($FID));
    $folder = DataModel::getData("fenfire_folders","SELECT module,path FROM fenfire_folders WHERE folderID=?",array($FID));
    $c->query("DELETE FROM fenfire_folders WHERE folderID=?",array($FID));
    Toolkit::log("Comments for ".$folder->module.".".$folder->path." cleared.");
}


//FIXME: Do this, but in a way that doesn't suck donkey balls.
function commentList($FID=null,$width=440){
    
    if($order==""){echo("<center>No comments have been added yet.</center>");return;}
    else $order=explode(",",$order);
    $this->loadComments($args['CID'],$args['MID'],$args['from'].",".$args['to']);
    ?><div class='co_list' style="width:<?=$args['width']?>;"><a name="comments"></a>
    <script type="text/javascript">
    $(function(){
        $(".respondButton").each(function(){
            $(this).click(function(){
                $("#varresponse").val($(this).attr("id"));
                $("#varsubject").focus();
                $("#varsubject").val($("#varsubject").val()+">>"+$(this).attr("id")+"\n");
            });
        });
    });</script>
    <?
    for($i=0;$i<count($order);$i++){
        $id=array_search($order[$i],$this->tbDID);
        if(in_array($order[$i],$this->tbDID)){
        ?><div style="padding:10px;width:<?=($this->tbDLevel[$id]*20)?>px;float:left;"></div>
        <div class="co_entry">
            <div class='co_entryAvatar'><?=$k->getUserAvatar($this->tbDUsername[$id],$this->tbDUsermail[$id],75)?></div>
            <a style="float:right;" href="#<?=$this->tbDID[$id]?>" title="<?=$this->tbDID[$id]?>">#<?=$this->tbDID[$id]?></a>
            <b><?=$this->tbDUsername[$id]?></b>
            <div style='font-size:10px;'>Posted on: <?=$k->toDate($this->tbDTime[$id])?> | <a class="respondButton" id="<?=$this->tbDID[$id]?>" href="#commentBox">Respond</a>
            <? if($a->check('comments.mod')){echo(' | <a href="/api.php?m=Comments&c=delComment&a=CID:'.$this->tbDID[$id].'&s='.$c->o['API_DELCOMMENT_TOKEN'].'&r=true">Delete</a>');} ?></div>
            <?=$p->deparseAll($this->tbDText[$id])?>
        </div>
        <br class="clear" />
    <?}}?>
    </div><?       
}

function getCommentAmount($FID){
    $result = $c->getData("SELECT COUNT(commentID) FROM fenfire_comments WHERE FID=? moderation=?",array($FID,0));
    if($result[0]['COUNT(commentID)']=="")return 0;
    else return $result[0]['COUNT(commentID)'];
}

}
?>
