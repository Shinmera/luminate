<?
class Fenfire{
public static $name="Fenfire";
public static $author="NexT";
public static $version=2.01;
public static $short='fenfire';
public static $required=array("Auth");
public static $hooks=array("foo");

function loadCommentOrder($MID,$CID){
    global $c;
    $result=$c->getData("SELECT `order` FROM tb_comment_order WHERE MID=? AND CID=?",array($MID,$CID));
    if(count($result)<=0)return "";
    return $result[0]["order"];
}

function adminNavbar($menu){
    $menu[]="Fenfire";
    return $menu;
}

function displayPanel(){
    
}

function displayAdminPage(){
    global $a,$c,$k;
    if(!$a->check('comments.mod'))return;
    switch($params[0]){
    case 'manage':
        switch($params[1]){
        case 'del':
            $this->apiCall("delComment",array("CID" => $_POST['varkey']),$c->o['API_DELCOMMENT_TOKEN']);
            echo("<center><span style='color:red'>Comment deleted.</span><br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");
            break;
        case 'mod':
            $this->loadComment($_POST['varkey']);
            if($this->tbDModeration[0]==1)$mod=0; else $mod=1;
            $this->apiCall("modComment",array("CID" => $_POST['varkey'],"moderation" => $mod),$c->o['API_MODCOMMENT_TOKEN']);
            echo("<center><span style='color:red'>Comment modded.</span><br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a></center>");
            break;
        case 'approved':
            if($params[2]==""||!is_numeric($params[2]))$params[2]=0;
            $this->loadCommentsMod(0,($params[2]*50).",".(($params[2]+1)*50));
            ?><a href='/admin/Comments/manage/approved'>Approved</a> | <a href='/admin/Comments/manage/flagged'>Flagged</a> | <a href='/admin/Comments/manage'>All</a>
            <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width='10%'>ID</td><td width='25%'>User/Mail</td><td width="45%">Excerpt</td><td width="10%">Time</td><td width="10%">Actions</td></tr><?
            for($i=0;$i<count($this->tbDID);$i++){?>
                <tr><td><?=$this->tbDID[$i]?></td><td><?=$this->tbDUsername[$i].' - '.$this->tbDUsermail[$i]?></td><td><?=substr($this->tbDText[$i],0,150)."..."?></td><td><?=$k->toDate($this->tbDTime[$i])?></td><td>
                        <form action="/admin/Comments/manage/mod" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Mod"></form>
                        <form action="/admin/Comments/manage/edit" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Edit"></form>
                        <form action="/admin/Comments/manage/del" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Delete"></form></td></tr>
            <? }?></table><?
            break;
        case 'flagged':
            if($params[2]==""||!is_numeric($params[2]))$params[2]=0;
            $this->loadCommentsMod(1,($params[2]*50).",".(($params[2]+1)*50));
            ?><a href='/admin/Comments/manage/approved'>Approved</a> | <a href='/admin/Comments/manage/flagged'>Flagged</a> | <a href='/admin/Comments/manage'>All</a>
            <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width='10%'>ID</td><td width='25%'>User/Mail</td><td width="45%">Excerpt</td><td width="10%">Time</td><td width="10%">Actions</td></tr><?
            for($i=0;$i<count($this->tbDID);$i++){?>
                <tr><td><?=$this->tbDID[$i]?></td><td><?=$this->tbDUsername[$i].' - '.$this->tbDUsermail[$i]?></td><td><?=substr($this->tbDText[$i],0,150)."..."?></td><td><?=$k->toDate($this->tbDTime[$i])?></td><td>
                        <form action="/admin/Comments/manage/mod" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Mod"></form>
                        <form action="/admin/Comments/manage/edit" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Edit"></form>
                        <form action="/admin/Comments/manage/del" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Delete"></form></td></tr>
            <? }?></table><?
            break;
        default:
            if($params[2]==""||!is_numeric($params[2]))$params[2]=0;
            $this->loadCommentsMod(0,($params[2]*50).",".(($params[2]+1)*50));
            $this->loadCommentsMod(1,($params[2]*50).",".(($params[2]+1)*50));
            ?><a href='/admin/Comments/manage/approved'>Approved</a> | <a href='/admin/Comments/manage/flagged'>Flagged</a> | <a href='/admin/Comments/manage'>All</a>
            <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width='10%'>ID</td><td width='25%'>User/Mail</td><td width="45%">Excerpt</td><td width="10%">Time</td><td width="10%">Actions</td></tr><?
            for($i=0;$i<count($this->tbDID);$i++){
                if($this->tbDModeration[$i]==1)$col="bgcolor='red'";?>
                <tr <?=$col?>><td><?=$this->tbDID[$i]?></td><td><?=$this->tbDUsername[$i].' - '.$this->tbDUsermail[$i]?></td><td><?=substr($this->tbDText[$i],0,150)."..."?></td><td><?=$k->toDate($this->tbDTime[$i])?></td><td>
                        <form action="/admin/Comments/manage/mod" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Mod"></form>
                        <form action="/admin/Comments/manage/edit" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Edit"></form>
                        <form action="/admin/Comments/manage/del" method="post"><input type="hidden" name="varkey" value="<?=$this->tbDID[$i]?>"><input type="submit" value="Delete"></form></td></tr>
            <? }?></table><?
            break;
        }
    default:
        if($a->check('comments.mod'))$SectionList["manage"]     = "Manage Comments|Delete and Moderate comments";
        $SectionList["0"]         = "<--->";
        return $SectionList;
        break;
    }
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
