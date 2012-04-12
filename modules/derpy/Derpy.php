<? class Derpy{
public static $name='Derpy';
public static $author='NexT';
public static $version=0.01;
public static $short='Derpy';
public static $required=array();
public static $hooks=array('foo');

function userNavbar($menu){$menu[]='Messages';return $menu;}
function buildMenu($menu){$menu[]=array('Messages',Toolkit::url('user','panel/Messages'));return $menu;}

function displayMessagesPage(){
    global $params,$a;
    $pages = array('Inbox','Outbox','Read','Write');
    ?><ul class='tabBar'>
        <? foreach($pages as $p){
            if($params[2]==$p)$sel='selected';else $sel='';
            echo('<li class="'.$sel.'"><a href="'.Toolkit::url('user','panel/Messages/'.$p).'">'.$p.'</a></li>');
        } ?>
    </ul>
    <div class='tabContainer'>
        <? switch($params[2]){
            case 'Write': $this->displayWritePage();break;
            case 'Read':  $this->displayReadPage();break;
            case 'Outbox':$this->displayOutboxPage();break;
            case 'Inbox':
            default:      $this->displayInboxPage();break;
        } ?>
    </div><?
}

function displayInboxPage(){
    global $c,$a,$k;
    $max = $c->getData('SELECT COUNT(messageID) FROM derpy_messages WHERE recipient LIKE ? '.
                                                    'AND (type LIKE ? OR type LIKE ?)',array($a->user->username,'m','a'));
    $k->sanitizePager($max[0]['COUNT(messageID)'],array('sender','title','time'),'time');
    $k->displayPager();
    
    if($max[0]['COUNT(messageID)']>0){
    ?><form action='<?=PROOT?>Messages/Inbox' method='post'><table class='mailbox'>
        <thead>
            <tr>
                <th style='width:20px;'></th>
                <th style='width:120px;'><a href='?o=sender&a=<?=!$_GET['a']?>'>    Sender</a></th>
                <th><a href='?o=title&a=<?=!$_GET['a']?>'>                          Title</a></th>
                <th style='width:150px;'><a href='?o=time&a=<?=!$_GET['a']?>'>      Time</a></th>
            </tr>
        </thead>
        <tbody><?
            if($_GET['a']==0)$_GET['a']='DESC';else $_GET['a']='ASC';
            $inbox = DataModel::getData('derpy_messages','SELECT messageID,sender,type,title,time,`read` '.
                                                    'FROM derpy_messages WHERE recipient LIKE ? '.
                                                    'AND (type LIKE ? OR type LIKE ?) ORDER BY `'.$_GET['o'].'` '.$_GET['a'].
                                                    ' LIMIT '.$_GET['f'].','.$_GET['t'],array($a->user->username,'m','a'));
            if(!is_array($inbox))$inbox=array($inbox);
            foreach($inbox as $mail){
                if($mail->read=='1')$sel='';else $sel='new';
                ?><tr class='<?=$sel?>'>
                    <td><input type='checkbox' name='toDelete[]' value='<?=$mail->messageID?>' /></td>
                    <td><?=$k->getUserPage($mail->sender)?></td>
                    <td><a href='<?=PROOT?>panel/Messages/Read/<?=$mail->messageID?>'><?=$mail->title?></a></td>
                    <td><?=$k->toDate($mail->time);?></td>
                </tr><?
            }
            ?>
        </tbody>
    </table><input type='submit' name='action' value='Delete' /></form>
    <?
    }else echo('<center>No messages to display.</center>');
}

function displayOutboxPage(){
    global $c,$a,$k;
    $max = $c->getData('SELECT COUNT(messageID) FROM derpy_messages WHERE sender LIKE ? '.
                                                    'AND type LIKE ?',array($a->user->username,'o'));
    $k->sanitizePager($max[0]['COUNT(messageID)'],array('recipient','title','time'),'time');
    $k->displayPager();
    
    if($max[0]['COUNT(messageID)']>0){
    ?><form action='<?=PROOT?>Messages/Outbox' method='post'><table class='mailbox'>
        <thead>
            <tr>
                <th style='width:20px;'></th>
                <th style='width:120px;'><a href='?o=recipient&a=<?=!$_GET['a']?>'>  Recipient</a></th>
                <th><a href='?o=title&a=<?=!$_GET['a']?>'>                          Title</a></th>
                <th style='width:150px;'><a href='?o=time&a=<?=!$_GET['a']?>'>      Time</a></th>
            </tr>
        </thead>
        <tbody><?
            if($_GET['a']==0)$_GET['a']='DESC';else $_GET['a']='ASC';
            $inbox = DataModel::getData('derpy_messages','SELECT messageID,recipient,type,title,time,`read` '.
                                                    'FROM derpy_messages WHERE sender LIKE ? '.
                                                    'AND type LIKE ? ORDER BY `'.$_GET['o'].'` '.$_GET['a'].
                                                    ' LIMIT '.$_GET['f'].','.$_GET['t'],array($a->user->username,'o'));
            if(!is_array($inbox))$inbox=array($inbox);
            foreach($inbox as $mail){
                if($mail->read=='1')$sel='';else $sel='new';
                ?><tr class='<?=$sel?>'>
                    <td><input type='checkbox' name='toDelete[]' value='<?=$mail->messageID?>' /></td>
                    <td><?=$k->getUserPage($mail->recipient)?></td>
                    <td><a href='<?=PROOT?>panel/Messages/Read/<?=$mail->messageID?>'><?=$mail->title?></a></td>
                    <td><?=$k->toDate($mail->time);?></td>
                </tr><?
            }
            ?>
        </tbody>
    </table><input type='submit' name='action' value='Delete' /></form>
    <?
    }else echo('<center>No messages to display.</center>');
}

function displayWritePage(){
    
}

function displayReadPage(){
    global $params,$l,$a,$k,$c;
    
    $mail = DataModel::getData('derpy_messages','SELECT messageID,sender,recipient,type,title,text,time,`read` FROM derpy_messages '.
                               'WHERE (sender LIKE ? OR recipient LIKE ?) AND messageID=?',array($a->user->username,$a->user->username,$params[3]));
    if($mail!=null){
        if($mail->read==0){
            $mail->read=1;
            $mail->saveData();
        }
        ?><div class="mail box" style="display:block;">
            <div class="mailheader">
                <? $avatar = $c->getData("SELECT filename FROM ud_users WHERE username LIKE ?",array($mail->sender));
                $k->getUserAvatar($mail->sender,$avatar[0]['filename'],false,75);?>
                <h2><?=$mail->title?></h2>
                From <?=$k->getUserPage($mail->sender)?> to <?=$k->getUserPage($mail->recipient)?> on <?=$k->toDate($mail->time)?>
            </div>
            <p class="mailtext">
                <?=$l->triggerPARSE("CORE",$mail->text);?>
            </p>
        </div>
        <div class="box" style="display:block;">
            <h3>Reply</h3>
        </div><?
    }else echo('<center>No message found with ID'.$params[3].'</center>');
}

}
?>