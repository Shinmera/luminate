<?php
function displayUserlistPage(){
    global $t,$k,$a;
    $t->openPage("Userlist");
    ?><div id='pageNav'>
        <div style="display:inline-block">
            <h1 class="sectionheader">Userlist</h1>
        </div>
        <div class="tabs"></div>
    </div>

    <? global $c;
    if($a->check("user.list")){
        $max = $c->getData("SELECT COUNT(userID) FROM ud_users");
        
        $k->sanitizePager($max[0]['COUNT(userID)'],array('username','displayname','group','time'),'time');
        ?>
        <div class="box" style="display:block;">
            <? $k->displayPager(); ?>
            <form action="<?=PROOT?>User/edituser" method="post"><table>
                <thead>
                    <tr>
                        <th style="width:70px;">Avatar</th>
                        <th style="width:120px;"><a href="?o=username&a=<?=!$_GET['a']?>">   Username</th>
                        <th style="width:120px;"><a href="?o=displayname&a=<?=!$_GET['a']?>">Displayname</a></th>
                        <th></th>
                        <th style="width:100px;"><a href="?o=group&a=<?=!$_GET['a']?>">      Group</a></th>
                        <th style="width:100px;"><a href="?o=time&a=<?=!$_GET['a']?>">       Registered On</a></th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    if($_GET['a']==0)$_GET['a']="DESC";else $_GET['a']="ASC";
                    $users = DataModel::getData("ud_users", 'SELECT `username`,`displayname`,`group`,`status`,`filename`,`time` '.
                                                            'FROM ud_users ORDER BY `'.$_GET['o'].'` '.$_GET['a'].' LIMIT '.$_GET['f'].','.$_GET['s']);
                    if(!is_array($users))$users=array($users);
                    foreach($users as $user){
                        echo('<tr><td>');
                        if($user->filename!='')echo('<img style="width:50px;" src="'.AVATARPATH.$user->filename.'" alt="Avatar" title="'.$user->displayname.'\'s avatar" />');
                        else                   echo('<img style="width:50px;" src="'.AVATARPATH.'noguy.png" alt="Avatar" title="'.$user->displayname.'\'s avatar" />');
                        echo('</td><td><a href="'.$k->url("user",$user->username).'">'.$user->username.'</a></td><td>'.$user->displayname.'</td>');
                        $k->pf('<td></td><td>'.$user->group.'</td><td>'.$k->toDate($user->time).'</td></tr>');
                    }
                    ?>
                </tbody>
            </table><input type="hidden" name="action" value="Edit" /></form>
        </div><?
    }else{
        echo(NO_ACCESS);
    }
    $t->closePage();
}

function displayUserPage($username){
    global $t,$l,$k,$a,$params,$site,$SUPERIORPATH;
    $user = DataModel::getData("ud_users","SELECT userID,username,displayname,`group`,`status`,`time`,filename FROM ud_users WHERE username LIKE ? OR displayname LIKE ?",array($username,$username));
    $SUPERIORPATH=$user->username;
    if($user==null){
        $t->openPage("User not found");
        ?><div id='pageNav'>
            <div style="display:inline-block">
                <h1 class="sectionheader">User</h1>
            </div>
            <div class="tabs"></div>
        </div>
        <? include(PAGEPATH.'404.php'); ?><?
    }else{
        $t->openPage($username." - Profile");
        if($params[1]=='')$params[1]='Profile';
        $pages = array('Profile');
        $pages = $l->triggerHookSequentially('PROFILEnavbar',"Neon",$pages);
        if($a->user->username!=$user->username){
            $friend = DataModel::getData("neon_friends","SELECT type FROM neon_friends WHERE (uID1=? AND uID2=?) OR (uID2=? AND uID1=?)",
                                                        array($user->userID,$a->user->userID,$user->userID,$a->user->userID));
            if(count($friend)==0)$pages[]='Add Friend';
        }
        
        if($a->check("user.profile.".$username)){
            ?><div id='pageNav'>
                <? if($user->filename==''){ $user->filename='noguy.png'; } ?>
                <img src="<?=AVATARPATH.$user->filename?>" alt="" title="<?=$user->displayname?>'s avatar" />
                <div style="display:inline-block">
                    <h1 class="sectionheader"><?=$user->displayname?></h1>
                    <div class="sectionsubheader"><?=$user->username?></div>
                </div>
                <div class="tabs">
                    <? foreach($pages as $page){
                        if($a->check("user.".$page)){
                            if($page==$params[1])echo('<a href="'.$k->url("user",$site.'/'.$page).'" class="tab activated">'.$page.'</a>');
                            else                 echo('<a href="'.$k->url("user",$site.'/'.$page).'" class="tab">'.$page.'</a>');
                        }
                    }if(!in_array($params[1], $pages))echo('<a href="'.$k->url("user",$site).'" class="tab activated">'.$site.'</a>'); ?>
                </div>
            </div><?

            switch($params[1]){
                case '':
                case 'Profile':displayUserProfile($username,$user);break;
                case 'Add Friend':break;//FIXME: Add Add Friend page. As if anyone would want to be friends anyway tho...
                default:$l->triggerHook('PAGE'.$site,"Neon",array($user->username,$user));break;
            }
        }else{
            echo(NO_ACCESS);
        }
    }
    
    $t->closePage();
}

function displayUserProfile($username,$user=null){
    global $k,$a,$l,$c;
    if($user==null)
        $user = DataModel::getData("ud_users","SELECT userID,displayname,`group`,`status`,`time` FROM ud_users WHERE username LIKE ?",array($username));
    $fields = DataModel::getData("ud_fields", "SELECT ud_fields.title AS `title`,ud_fields.default AS `default`,ud_fields.type AS `type`,ud_field_values.value AS `value` ".
                                              "FROM ud_fields INNER JOIN ud_field_values USING(varname) ".
                                              "WHERE ud_fields.displayed=1 AND ud_field_values.userID=? ",array($user->userID));
    $friends = DataModel::getData("neon_friends","SELECT ud_users.displayname AS displayname, ud_users.filename AS filename ".
                                                 "FROM neon_friends INNER JOIN ud_users ON neon_friends.uID2 = ud_users.userID ".
                                                 "WHERE neon_friends.type LIKE ? AND neon_friends.uID1 LIKE ?",array('f',$user->userID));
    ?><div class='bar'>
        <div class='section'>
            <?=$username?>#<?=$k->unifyNumberString(base_convert($user->userID, 10, 16),10);?><br />
            Registered on <?=$k->toDate($user->time);?>
        </div>
        <div class='section'>
            <? $time = $c->getData("SELECT time FROM ms_timer WHERE `action` LIKE ?",array('visit:'.$user->userID)); ?>
            Last visit: <?=$k->toDate($time[0]['time']);?><br />
            &nbsp;
        </div>
        <div class='section'>
            Group: <?=$user->group?><br />
            &Delta;<?=$a->generateDelta($user->userID,$user->group);?>
        </div>
        <? $l->triggerHook('PROFILEbar',"Neon",array($user->userID)); ?>
    </div>
    <?
    if($fields!=null&&!is_array($fields))$fields=array($fields);
    if(is_array($fields)){
        foreach($fields as $field){
            if($field->type=='t'){
                if(trim($field->value)=='')$field->value=trim($field->default);
                if($field->value!=''){
                    $text = $l->triggerPARSE("Neon",$field->value);
                    echo('<div class="box userTextBox"><h3>'.$field->title.'</h3>'.$text.'</div>');
                }
            }
        }
        echo('<div class="box userInfoBox"><h3>Info</h3><ul>');
            echo('<li>Username: '.$user->username.'</li>');
            echo('<li>Displayname: '.$user->displayname.'</li>');
            foreach($fields as $field){
                if(trim($field->value==''))$field->value=trim($field->default);
                if($field->value!=''){
                    switch($field->type){
                        case 'i':
                        case 's':
                        case 'd':
                            echo('<li>'.$field->title.': '.$field->value.'</li>');
                            break;
                        case 'l':
                            echo('<li>'.$field->title.': '.$field->value.'</li>');
                            break;
                        case 'u':
                            echo('<li>'.$field->title.': <a href="'.$field->value.'">'.$field->value.'</a></li>');
                            break;
                    }
                }
            }
        echo('</ul></div>');
    }
    
    if($friends!=null&&!is_array($friends))$friends=array($friends);
    if(is_array($friends)){
        echo('<div class="box userFriendsBox"><h3>Friends</h3><ul>');
        foreach($friends as $friend){
            echo('<li><a href="'.$k->url("user",$friend->displayname).'">');
            echo('<img src="'.AVATARPATH.$friend->filename.'" alt="'.$friend->displayname.'" title="'.$friend->displayname.'\'s avatar" /></a></li>');
        }
        echo('</ul></div>');
    }
    $l->triggerHook('PROFILEpage',"Neon",array($user->userID));
}
?>
