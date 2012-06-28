<?
//TODO: Rebuild admin.
//TODO: Add hooks.
//TODO: Sanitize for v4.
//TODO: Re-test everything.
//TODO: Tune akismet for unknown IPs.
//TODO: Implement mute bans properly.
//TODO: Fix problems with board editing.
//TODO: Create frontpage.

class Purplish{
public static $name="Chan";
public static $author="NexT";
public static $version=2.01;
public static $short='chan';
public static $required=array("Auth");
public static $hooks=array("foo");

function displayPage(){
    global $params,$param,$c,$a;
    include(PAGEPATH.'chan/chan_banned.php');
    if($c->o['chan_online']=='1'||$a->check('chan.admin.*')){
        switch(trim($params[0])){
            case 'byID':
                $board = DataModel::getData('',"SELECT folder FROM ch_boards WHERE boardID=? OR folder LIKE ?",array($params[1],$params[1]));
                $thread = DataModel::getData('',"SELECT PID FROM ch_posts WHERE postID=?",array($params[2]));

                if($board==null||$thread==null)die();
                if($thread->PID==0)$thread->PID=$params[2];

                header('Location: '.Toolkit::url("chan",$board[0]['folder'].'/threads/'.$thread->PID.'.php'));
                break;
            case '':
                include(PAGEPATH.'chan/chan_frontpage.php');
                break;
            default:
                if(is_dir(ROOT.DATAPATH.'chan/'.$param))
                    include(ROOT.DATAPATH.'chan/'.$param.'/index.php');
                else if(file_exists(ROOT.DATAPATH.'chan/'.$param)&&!is_dir(ROOT.DATAPATH.'chan/'.$param))
                    include(ROOT.DATAPATH.'chan/'.$param);
                else{
                    global $l;
                    header('HTTP/1.0 404 Not Found');
                    $t = $l->loadModule('Themes');
                    $t->loadTheme("chan");
                    $t->openPage("404 - Purplish");
                    include(PAGEPATH.'404.php');
                    $t->closePage();
                }
                break;
        }
    }else{
        include(PAGEPATH.'chan/chan_offline.php');
    }
}

function displayAdminPanel(){
    global $l;
    $admin = $l->loadModule('ChanAdmin');
    $admin->displayPanel();
}
function displayAdmin(){
    global $l;
    $admin = $l->loadModule('ChanAdmin');
    $admin->display();
}

function displayRSS(){
    global $l;
    $rss = $l->loadModule('ChanRSS');
    $rss->display();
}

function displayAPI(){
    global $l,$c,$a;
    $api = $l->loadModule('ChanAPI');
    if($c->o['chan_online']=='1'||$a->check('chan.admin.*'))
        $api->display();
    else
        die('Offline mode active. Please wait with your request.');
}
}
?>
