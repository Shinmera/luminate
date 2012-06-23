<? class ChanRSS extends Module{
public static $name="ChanRSS";
public static $author="NexT";
public static $version=0.10;
public static $short='crss';
public static $required=array();
public static $hooks=array("foo");

function display(){
    switch($_GET['type']){
        case 'thread':$this->displayThread();break;
        case 'board': $this->displayBoard();break;
        default:      $this->displayGlobal();break;
    }
}

function displayThread(){
    if($_GET['id']==''||$_GET['bid']=='')die('Thread or board ID missing.');
    $op = DataModel::getData('ch_posts','SELECT p.title,p.name,p.trip,p.file,ch_boards.folder
                                         FROM ch_posts AS p LEFT JOIN ch_boards ON BID=boardID
                                         WHERE p.postID=? AND p.BID=?',array($_GET['id'],$_GET['bid']));
    if($op==null)                        die('Thread not found.');
    
    $posts = DataModel::getData('ch_posts','SELECT p.postID,p.BID,p.name,p.trip,p.mail,p.title,p.file,p.subject,p.time,ch_boards.folder
                                            FROM ch_posts AS p LEFT JOIN ch_boards ON BID=boardID
                                            WHERE p.PID=? AND p.BID=? ORDER BY time DESC LIMIT 5',array($_GET['id'],$_GET['bid']));
    if($posts==null)                     die();
    if(!is_array($posts))$posts=array($posts);
    
    $this->generateFeed($op->title.' Thread #'.$_GET['id'], $op->title.' Thread #'.$_GET['id'],
                        Toolkit::url('chan',$op->folder.'/threads/'.$_GET['id'].'.php'), $op->folder.'/thumbs/'.$op->file, $posts);
}

function displayBoard(){
    global $c;
    if($_GET['bid']=='')die('Board ID missing.');
    $board = DataModel::getData('ch_boards', 'SELECT folder,title FROM ch_boards WHERE boardID=? OR folder LIKE ?',array($_GET['bid'],$_GET['bid']));
    if($board==null)    die('Board not found.');
    
    $posts = DataModel::getData('ch_posts','SELECT p.postID,p.BID,p.name,p.trip,p.mail,p.title,p.file,p.subject,p.time,ch_boards.folder
                                            FROM ch_posts AS p LEFT JOIN ch_boards ON BID=boardID
                                            WHERE p.PID=0 AND p.BID=? ORDER BY bumptime DESC LIMIT 5',array($_GET['bid']));
    if($posts==null)    die();
    if(!is_array($posts))$posts=array($posts);
    
    $this->generateFeed($board->title.' Thread Feed',$c->o['chan_title'].': /'.$board->folder.'/ - '.$board->title.' Thread RSS Feed',
                        Toolkit::url('chan',$board->folder), $board->folder.'/thumbs/'.$posts[0]->file, $posts);
}

function displayGlobal(){
    global $c;
    $posts = DataModel::getData('ch_posts','SELECT p.postID,p.BID,p.name,p.trip,p.mail,p.title,p.file,p.subject,p.time,ch_boards.folder
                                            FROM ch_posts AS p LEFT JOIN ch_boards ON BID=boardID
                                            ORDER BY time DESC LIMIT 10');
    if($posts==null)    die();
    if(!is_array($posts))$posts=array($posts);
    
    $this->generateFeed($c->o['chan_title'].' Latest Posts', $c->o['chan_title'].' latest posts from the whole chan.',
                        Toolkit::url('chan'), $posts[0]->folder.'/thumbs/'.$posts[0]->file, $posts);
}

function generateFeed($title,$description,$link,$imageurl,$data){
    global $l;
    header("Content-type: text/xml");
    ?><?='<?xml version="1.0" encoding="UTF-8" ?>'."\n"?>
    <rss version="2.0"><channel>
        <title><?=$title?></title>
        <description><?=$description?></description>
        <link><?=$link?></link>
        <image>
            <url>http://<?=HOST.DATAPATH.'chan/'.$imageurl?></url>
            <title>Channel Image</title>
        </image>
        <? foreach($data as $dat){ ?>
            <item>
                <title><?=$dat->title.' #'.$dat->postID?></title>
                <author><?=$dat->name.' '.$dat->trip.' '.$dat->mail?></author>
                <link><?=Toolkit::url('chan','byID/'.$dat->BID.'/'.$dat->postID)?></link>
                <guid isPermaLink="true"><?=Toolkit::url('chan','byID/'.$dat->BID.'/'.$dat->postID)?></guid>
                <pubDate><?=Toolkit::toDate($dat->time)?></pubDate>
                <description>
                    <![CDATA[
                        <div><?=$dat->title.' by '.$dat->name.' '.$dat->trip?></div>
                        <? if($dat->file!=''&&$dat->folder!=''){ ?>
                            <img src="http://<?=HOST.DATAPATH.'chan/'.$dat->folder.'/thumbs/'.$dat->file?>" style="float:left;margin:5px;" />
                        <? } ?><br />
                        <blockquote>
                            <?=$l->triggerPARSE('Purplish',$dat->subject)?>
                        </blockquote>
                    ]]>
                </description>
            </item>
        <? } ?>
    </channel></rss>
    <?
}

}
?>