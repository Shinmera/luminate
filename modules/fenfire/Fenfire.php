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

function loadCommentOrder($MID,$CID){
    global $c;
    $result=$c->getData("SELECT `order` FROM tb_comment_order WHERE MID=? AND CID=?",array($MID,$CID));
    if(count($result)<=0)return "";
    return $result[0]["order"];
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



function submitCommentForm(){
    if($c->userpriv<1){
        if($_POST['varuser']==""){echo("<center>Error: No user name given.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}
        if($_POST['varmail']==""){echo("<center>Error: No mail address given.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}
        if($k->in_arrayi($_POST['varuser'],$c->udUName)){
                                    echo("<center>Error: You cannot use the name of a registered user.<br />
                                            If this name belongs to you, please <a href='/user/login'>log in</a> first.<br />
                                        <a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}
        $args['user']=$_POST['varuser'];
        $args['mail']=$_POST['varmail'];
        $args["recaptcha_challenge_field"]=$_POST["recaptcha_challenge_field"];
        $args["recaptcha_response_field"]=$_POST["recaptcha_response_field"];
    }else{
        if($c->userpriv<1){echo("<center>Error: You do not have the necessary permissions to post.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}
        $args['user']=$c->username;
        $args['mail']=$c->udUMail[$c->uID];
    }
    if($_POST['varMID']==""){echo("<center>Error: Post malformed.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}
    if($_POST['varCID']==""){echo("<center>Error: Post malformed.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}
    if($_POST['varsubject']==""){echo("<center>Error: No text given.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}
    if(strlen($_POST['varsubject'])<$c->o['comment_size_min']){echo("<center>Error: Post too short.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}
    if(strlen($_POST['varsubject'])>$c->o['comment_size_max']){echo("<center>Error: Post too long.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");return;}

    $args['MID']=$_POST['varMID'];
    $args['CID']=$_POST['varCID'];
    $args['subject']=$_POST['varsubject'];
    $args['response']=$_POST['varresponse'];

    $response=$this->apiCall("submitComment",$args,$c->o['API_SUBMITCOMMENT_TOKEN']);
    switch((string)$response){
        case XERR_TIMEOUT:
            echo("<center>Error: Please wait ".$c->o['post_timeout']." seconds between posts.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");
            break;
        case XERR_INVALID_CAPTCHA:
            echo("<center>Error: You entered an invalid captcha.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");
            break;
        case XERR_BAD_AKISMET:
            echo("<center>Error: The comment has been marked as spam and is held back for moderation.<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");
            break;
        case XERR_INVALID_API_TOKEN:
            echo("<center>Error: An internal API error occurred. Please try again. <br />
                If the error persists, contact the sysop at <a href='mailto:".$c->o['sysop_mail']."'>".$c->o['sysop_mail']."</a>.<br />
                <a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");
            break;
        case XERR_GENERIC:
            echo("<center>Error: Generic Error.<a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");
            break;
        case XERR_OK:
            header('Location: '.$_SERVER['HTTP_REFERER']);
            break;
        default:
            echo("<center>An unknown error occurred: '".$response."' .<a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");
            break;
    }
}

function submitComment(){
    if(!$k->updateTimestamp("comment",$c->o['post_timeout']))return XERR_TIMEOUT;

    //check the captcha
    if(!in_array($args['user'],$c->udUName)){
        setcookie("_session_user",$args['user'],time()+60*60*24*$c->o['cookie_life_h']);
        setcookie("_session_mail",$args['mail'],time()+60*60*24*$c->o['cookie_life_h']);
        require_once($root.'callables/recaptchalib.php');
        $resp = recaptcha_check_answer ($c->o['recaptcha_key_private'],
                                        $_SERVER["REMOTE_ADDR"],
                                        $args["recaptcha_challenge_field"],
                                        $args["recaptcha_response_field"]);
        if (!$resp->is_valid)return XERR_INVALID_CAPTCHA;
    }

    //check akismet
    require_once('./callables/akismet.php');
    $akismet = new Akismet(HOST ,$c->o['akismet_key']);
    $akismet->setCommentAuthor($args['user']);
    $akismet->setCommentAuthorEmail($args['mail']);
    $akismet->setCommentContent($args['subject']);
    $akismet->setPermalink(HOST);
    if($akismet->isCommentSpam())$spamcheck=1;else $spamcheck=0;

    //get the comment list, level, order list and next ID.
    $this->loadComments($args['CID'],$args['MID'],"1000");
    $level=$this->tbDLevel[array_search($args['response'],$this->tbDID)]+1;if($level>5)$level=5;
    $CO=$this->loadCommentOrder($args['MID'],$args['CID']);
    $nid='%';

    if($CO!="")$CO=explode(",",$CO);else{
        $c->query("INSERT INTO tb_comment_order VALUES(?,?,?)",array($args['CID'],$args['MID'],''));
        $CO=array();
    }

    //find the correct position
    if(count($CO)>0){
        if($args['response']!=""){
            $pos=array_search($args['response'],$CO)+1;
            for($i=$pos;$i<count($CO);$i++){
                if($this->tbDLevel[array_search($CO[$i],$this->tbDID)]<$level){
                    $pos=$i;
                    break;
                }
            }
        }else{
            $pos=count($CO);
            $level=0;
        }
        $k->array_insert($CO,$nid,$pos);
        $COf=implode(",",$CO);
    }else{
        $level=0;
        $COf=$nid."";
    }

    //insert
    $query="INSERT INTO tb_comments VALUES(NULL,?,?,?,?,?,?,?,?,?)";
    $c->query($query,array($args['CID'],$args['MID'],$args['user'],$args['mail'],$args['subject'],time(),$level,0,$spamcheck));
    $result=$c->getData("SELECT commentID FROM tb_comments ORDER BY commentID DESC LIMIT 1;");
    $nid=$result[0]['commentID'];$COf=str_replace("%",$nid,$COf);
    $query="UPDATE tb_comment_order SET `order`=? WHERE CID=? AND MID=? LIMIT 1;";
    $c->query($query,array($COf,$args['CID'],$args['MID']));
    $k->log("Comment from ".$args['user']." (".$args['mail'].") for CID ".$args['CID']." and MID ".$args['MID']." added.");
    //FIXME: SEND MESSAGES

    if($spamcheck==1)   return XERR_BAD_AKISMET;
    else                return XERR_OK;
}

function modComment(){
    if($a->check('comments.mod')){
        $c->query("UPDATE tb_comments SET moderation=? WHERE commentID=? LIMIT 1",array($args['moderation'],$args['CID']));
        $k->log("Comment moderation for ID".$args['CID']." set to ".$args['moderation']);
    }
}

function delComment(){
    if($a->check('comments.mod')){
        $this->loadComment($args['CID']);
        $order=$this->loadCommentOrder($this->tbDMID[0],$this->tbDCID[0]);
        $c->query("UPDATE tb_comment_order SET `order`=? WHERE CID=? AND MID=? LIMIT  1",array(
                                            $k->removeFromList($args['CID'],$order,","),$this->tbDCID[0],$this->tbDMID[0]));
        $c->query("DELETE FROM tb_comments WHERE commentID=? LIMIT 1",array(
                                            $args['CID']));
        $k->log("Comment ID".$args['CID']." deleted.");
    }
}

function cleanComments(){
    $c->query("DELETE FROM tb_comments WHERE CID=? AND MID=?",array($args['CID'],$args['MID']));
    $c->query("DELETE FROM tb_comment_order WHERE CID=? AND MID=?",array($args['CID'],$args['MID']));
    $k->log("Comments for CID ".$args['CID']." and MID ".$args['MID']." cleared.");
}

function commentList(){
    if($args['MID']==""||$args['CID']=="")return;
    if($args['from']=="")$args['from']=0;
    if($args['to']=="")$args['to']=25;
    if($args['width']=="")$args['width']='440px';
    $order=$this->loadCommentOrder($args['MID'],$args['CID']);
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

function commentBox(){
    $saveduser=$_COOKIE['_session_user'];
    $savedmail=$_COOKIE['_session_mail'];
    if($args['width']=="")$args['width']='440px';
    ?><div class="commentBox">
    <b><a title="commentBox">Add a comment:</a></b><br />
    <script type="text/javascript">var RecaptchaOptions = {theme : 'clean'};</script>
    <form method="post" action="<?=PROOT?>api.php?m=Comments&c=submitCommentForm">
    <table width="<?=$args['width']?>">
        <? if($c->userpriv<1) {?>
            <tr><td><a title="If you have a registered account, please log in first.">Username:</a></td><td align="right"><input style="width:200px;" name="varuser" value="<?=$saveduser?>" type="text" /></td></tr>
            <tr><td><a title="Gravatar supported. Email will not be published.">E-Mail:</a></td><td align="right"><input style="width:200px;" name="varmail" value="<?=$savedmail?>" type="text"/></td></tr>
        <? }?>
    </table>
        <TEXTAREA Name="varsubject" id="varsubject" style="width:<?=$args['width']?>;height:100px;"></TEXTAREA><br />
        <? if($c->userpriv<1){require_once(TROOT.'callables/recaptchalib.php');echo recaptcha_get_html($c->o['recaptcha_key_public']);} ?>
        <input type="submit" name="Submit" value="Submit" />
    <input type="hidden" name="varMID" value="<?=$args['MID']?>">
    <input type="hidden" name="varCID" value="<?=$args['CID']?>">
    <input type="hidden" id="varresponse" name="varresponse" value=""></form></div><?
}

function getCommentAmount(){
    $result = $c->getData("SELECT COUNT(commentID) FROM tb_comments WHERE CID=? AND MID=? AND moderation=?",array($args['CID'],$args['MID'],0));
    if($result[0]['COUNT(commentID)']=="")return 0;
    else return $result[0]['COUNT(commentID)'];
}

}
?>
