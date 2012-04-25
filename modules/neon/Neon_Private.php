<?php
function displayControlPanelProfile(){
    global $a,$c,$k,$l;
    
    switch($_POST['action']){
        case 'Update Account':
            $_POST['displayname']=trim($k->sanitizeString($_POST['displayname']));
            if($k->checkMailValidity($_POST['mail'])){
                if(strlen($_POST['displayname'])>3){
                    if($_POST['displayname']!=$a->user->displayname){
                        $used = $c->query("SELECT userID FROM ud_users WHERE displayname LIKE ? OR username LIKE ? ",array($_POST['displayname'],$_POST['displayname']));
                        if($user[0]['userID']=='')$a->user->displayname=$_POST['displayname'];
                        else                      $err[0]="This username is already taken.";
                    }
                    $a->user->mail=$_POST['mail'];
                    $a->user->saveData();
                    $l->triggerHook("UPDATEaccount",'User');
                    $err[2]="Account information saved.";
                }else $err[0]="Invalid username!";
            }else $err[1]="Invalid mail address!";
            break;
        case 'Save Password':
            if(strlen($_POST['newpass'])>5){
                if($_POST['newpass']==$_POST['newpassrepeat']){
                    $hash=hash('sha512',$_POST['newpass']);
                    $a->user->password=$hash;
                    $a->user->saveData();
                    $a->login($a->user->username,$hash,false); //Revalidate cookies
                    $l->triggerHook("UPDATEpassword",'User');
                    $err[5]="Password saved.";
                }else $err[4]="The passwords do not match.";
            }else $err[3]="Password must be longer than 5 characters.";
            break;
        case 'Update Avatar':
            $filename = $a->user->username.'-'.$k->generateRandomString();
            try{
                $dest = $k->uploadFile("avatar",ROOT.AVATARPATH,$c->o['avatar_maxsize'],array("image/png","image/gif","image/jpeg","image/jpg"),false,$filename);
                $k->createThumbnail($dest,$dest,$c->o['avatar_maxdim'],$c->o['avatar_maxdim'],false,true,false);
                unlink(ROOT.AVATARPATH.$a->user->filename);
                $a->user->filename=substr($dest,  strrpos($dest, "/"));
                $a->user->saveData();
                $l->triggerHook("UPDATEavatar",'User');
                $err[7]="Avatar changed.";
            }catch(Exception $e){$err[6]="Uploading failed: ".$e->getMessage();}
            break;
        case 'Update Profile':
            $u = $l->loadModule('User');
            $u->updateUserFields($a->user->userID,$_POST);
            $err[8]="Profile settings saved.";
            break;
    }
    
    ?>
    <div class="tabbed">
        <form action="#" method="post" name="Profile">
            <? $fields = DataModel::getData("ud_fields", "SELECT `varname`, `title`, `default`, `type` FROM ud_fields WHERE `editable`=1");
            $values = DataModel::getData("ud_field_values","SELECT `varname`,`value` FROM ud_field_values WHERE userID=?",array($a->user->userID));
            foreach($fields as $f){
                echo('<label>'.$f->title.':</label>');
                $v = null;
                if($values!=null){
                    if(!is_array($values))$values=array($values);
                    foreach($values as $value){
                        if($value->varname==$f->varname){$v=$value->value;break;}
                    }
                }
                switch($f->type){
                    case 'i':echo('<input type="number" class="number" name="val'.$f->varname.'" value="'.$v.'" placeholder="'.$f->default.'" />');break;
                    case 's':echo('<input type="text" class="string" name="val'.$f->varname.'" value="'.$v.'" placeholder="'.$f->default.'" />');break;
                    case 'u':echo('<input type="url" class="url" name="val'.$f->varname.'" value="'.$v.'" placeholder="'.$f->default.'" />');break;
                    case 'd':echo('<input type="date" class="date" name="val'.$f->varname.'" value="'.$v.'" placeholder="'.$f->default.'" />');break;
                    case 't':echo('<textarea class="text" name="val'.$f->varname.'" placeholder="'.$f->default.'">'.$v.'</textarea>');break;
                    case 'l':$vals=explode(";",$v);$k->interactiveList("val".$f->varname,$vals,$vals,$vals,true);break;
                }
                echo('<br />');
            }
            ?>
            <input type="submit" name="action" value="Update Profile" /><?=$err[8]?>
        </form>
        <form action="#" method="post" enctype="multipart/form-data" name="Avatar">
            <img src="<?=AVATARPATH.$a->user->filename?>" alt="Avatar" title="Your avatar image" /><br />
            <input type="file" name="avatar" accept="image/*" /><?=$err[6]?>
            <input type="submit" name="action" value="Update Avatar" /><?=$err[7]?><br />
            <span class="small">
                Maximum filesize is <?=$c->o['avatar_maxsize']?>kb.
                If the avatar exceeds <?=$c->o['avatar_maxdim']?>x<?=$c->o['avatar_maxdim']?> px, it will be re-scaled.
            </span>
        </form>
        <form action="#" method="post" name="Account">
            <label>Username</label><input type="text" name="displayname" value="<?=$a->user->username?>" disabled="disabled" /><br />
            <label>Displayname</label><input type="text" name="displayname" value="<?=$a->user->displayname?>" /><?=$err[0]?><br />
            <label>E-Mail</label><input type="email" name="mail" value="<?=$a->user->mail?>" /><?=$err[1]?><br />
            <input type="submit" name="action" value="Update Account" /><?=$err[2]?>
        </form>
        <form action="#" method="post" name="Password">
            <label>New password:</label><input type="password" name="newpass" autocomplete="off" /><?=$err[3]?><br />
            <label>Repeat:</label><input type="password" name="newpassrepeat" autocomplete="off" /><?=$err[4]?><br />
            <input type="submit" name="action" value="Save Password" /><?=$err[5]?>
        </form>
        <div name="View Profile" href="<?=$k->url("user",$a->user->username);?>" ></div>
    </div><?
}

function displayControlPanelFriends(){
    global $a,$k,$c,$l;
    
    switch($_POST['action']){
        case 'Remove':
            if(count($_POST['friends'])>0){
                $query = "DELETE FROM neon_friends WHERE type=? AND (";
                $data = array('f');$sub=array();
                foreach($_POST['blocked'] as $b){
                    $sub[]="((uID1=? AND uID2=?) OR (uID2=? AND uID1=?))";
                    $data[]=$a->user->userID;
                    $data[]=$b;
                    $data[]=$a->user->userID;
                    $data[]=$b;
                    $l->triggerHook("FRIENDremove",'User',array($a->user->userID,$b));
                }
                $query.=implode(" OR ",$sub).")";
                $c->query($query,$data);
                $err[0]="Friend removed.";
            }
            if(count($_POST['requests'])>0){
                $query = "DELETE FROM neon_friends WHERE type=? AND (";
                $data = array('r');$sub=array();
                foreach($_POST['requests'] as $b){
                    $sub[]="(uID2=? AND uID1=?)";
                    $data[]=$a->user->userID;
                    $data[]=$b;
                    $l->triggerHook("FRIENDdeny",'User',array($a->user->userID,$b));
                }
                $query.=implode(" OR ",$sub).")";
                $c->query($query,$data);
                $err[2]="Request denied.";
            }
            if(count($_POST['blocked'])>0){
                $query = "DELETE FROM neon_friends WHERE type=? AND (";
                $data = array('b');$sub=array();
                foreach($_POST['blocked'] as $b){
                    $sub[]="(uID1=? AND uID2=?)";
                    $data[]=$a->user->userID;
                    $data[]=$b;
                    $l->triggerHook("FRIENDunblock",'User',array($a->user->userID,$b));
                }
                $query.=implode(" OR ",$sub).")";
                $c->query($query,$data);
                $err[3]="Block  removed.";
            }
            break;
        case 'Add Friend':
            $friend = DataModel::getHull("neon_friends");
            $exists = $c->getData("SELECT userID FROM ud_users WHERE username LIKE ? OR displayname LIKE ?",array($_POST['user'],$_POST['user']));
            $exists = $exists[0]['userID'];
            if($exists!=''){
                $friend->uID1=$a->user->userID;
                $friend->uID2=$exists;
                $friend->type='r';
                $friend->insertData();
                $l->triggerHook("FRIENDrequest",'User',array($a->user->userID,$exists));
                $err[1]="Friend request sent.";
            }else $err[1]="No such user.";
            break;
        case 'Accept':
            $friend = DataModel::getHull("neon_friends");
            foreach($_POST['requests'] as $request){
                $friend->uID1=$a->user->userID;
                $friend->uID2=$request;
                $friend->type='f';
                $friend->insertData();
                $friend->uID2=$request;
                $friend->uID1=$a->user->userID;
                $friend->saveData();
                $l->triggerHook("FRIENDaccept",'User',array($a->user->userID,$request));
                $err[2]="Friend accepted.";
            }
            break;
        case 'Block':
            $exists = $c->getData("SELECT userID FROM ud_users WHERE username LIKE ? OR displayname LIKE ?",array($_POST['user'],$_POST['user']));
            $exists = $exists[0]['userID'];
            if($exists!=''){
                $already = $c->getData("SELECT uID1,uID2 FROM neon_friends WHERE (uID1=? AND uID2=?) OR (uID2=? AND uID1=?)",
                                        array($a->user->userID,$exists,$a->user->userID,$exists));
                if(count($already)==0){
                    $block = DataModel::getHull("neon_friends");
                    $block->uID1=$a->user->userID;
                    $block->uID2=$exists;
                    $block->type='b';
                    $block->insertData();
                }else{
                    $block = DataModel::getData("neon_friends","SELECT * FROM neon_friends WHERE uID1=? AND uID2=?",array($already[0]['uID1'],$already[0]['uID2']));
                    $block->type='b';
                    $block->saveData();
                }
                $l->triggerHook("FRIENDblock","Neon",array($a->user->userID,$exists));
                $err[4]="User blocked.";
            }else $err[4]="No such user.";
            break;
    }
    
    
    $friends = DataModel::getData("neon_friends","SELECT ud_users.userID as userID, ud_users.displayname AS displayname,ud_users.filename AS filename FROM ".
                                                 "neon_friends INNER JOIN ud_users ON neon_friends.uID2 = ud_users.userID ".
                                                 "WHERE neon_friends.uID1=? AND neon_friends.type LIKE ?",array($a->user->userID,'f'));
    $blocked = DataModel::getData("neon_friends","SELECT ud_users.userID as userID, ud_users.displayname AS displayname,ud_users.filename AS filename FROM ".
                                                 "neon_friends INNER JOIN ud_users ON neon_friends.uID2 = ud_users.userID ".
                                                 "WHERE neon_friends.uID1=? AND neon_friends.type LIKE ?",array($a->user->userID,'b'));
    $requests= DataModel::getData("neon_friends","SELECT ud_users.userID as userID, ud_users.displayname AS displayname,ud_users.filename AS filename FROM ".
                                                 "neon_friends INNER JOIN ud_users ON neon_friends.uID1 = ud_users.userID ".
                                                 "WHERE neon_friends.uID2=? AND neon_friends.type LIKE ?",array($a->user->userID,'r'));
    if($friends==null)$friends=array();
    if($requests==null)$requests=array();
    if($blocked==null)$blocked=array();
    if(!is_array($friends))$friends=array($friends);
    if(!is_array($requests))$requests=array($requests);
    if(!is_array($blocked))$blocked=array($blocked);
    ?>
    <div class="tabbed">
        <div name="Friends">
            <form action="#" method="post">
                <? foreach($friends as $friend){ ?>
                    <div class="useravatarbox" >
                        <? $k->getUserAvatar($friend->displayname,$friend->filename,false,50); ?><br />
                        <input type="checkbox" name="friends[]" value="<?=$friend->userID?>"/>
                        <? $k->getUserPage($friend->displayname); ?>
                    </div>
                <? } ?>
                <br />
                <input type="submit" name="action" value="Remove" /><?=$err[0]?>
            </form>
            <br />
            <form action="#" method="post">
                <input type="text" name="user" class="userpick" />
                <input type="submit" name="action" value="Add Friend" /><?=$err[1]?>
            </form>
        </div>
        <form action="#" method="post" name="Requests">
            <? foreach($requests as $request){ ?>
                <div class="useravatarbox" >
                    <? $k->getUserAvatar($request->displayname,$request->filename,false,50); ?><br />
                    <input type="checkbox" name="requests[]" value="<?=$request->userID?>"/>
                    <? $k->getUserPage($request->displayname); ?>
                </div>
            <? } ?>
            <br />
            <input type="submit" name="action" value="Remove" />
            <input type="submit" name="action" value="Accept" /><?=$err[2]?>
        </form>
        <div name="Blocked">
            <form action="#" method="post">
                <? foreach($blocked as $block){ ?>
                    <div class="useravatarbox" >
                        <? $k->getUserAvatar($block->displayname,$block->filename,false,50); ?><br />
                        <input type="checkbox" name="blocked[]" value="<?=$block->userID?>"/>
                        <? $k->getUserPage($block->displayname); ?>
                    </div>
                <? } ?>
                <br />
                <input type="submit" name="action" value="Remove" /><?=$err[3]?>
            </form>
            <br />
            <form action="#" method="post">
                <input type="text" name="user" class="userpick" />
                <input type="submit" name="action" value="Block" /><?=$err[4]?>
            </form>
        </div>
    </div><?
}
?>
