<? class ChanRSS extends Module{
public static $name="ChanRSS";
public static $author="NexT";
public static $version=0.01;
public static $short='crss';
public static $required=array();
public static $hooks=array("foo");

function display(){
    
}

function displayThread(){
    global $c;
    if($_GET['id']==''||$_GET['bid']=='')die('Thread or board ID missing.');
    $board = $c->getData('SELECT folder FROM ch_boards WHERE boardID=?',array($_GET['bid']));
    $op = DataModel::getData('ch_posts','SELECT title,name,trip,file FROM ch_posts WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
    $posts = DataModel::getData('ch_posts','SELECT postID,BID,name,trip,mail,title,file,subject,time FROM ch_posts WHERE PID=? AND BID=? ORDER BY time DESC LIMIT 5',array($_GET['id'],$_GET['bid']));
    
    $this->generateFeed($op->title.' Thread #'.$_GET['id'], $op->title.' Thread #'.$_GET['id'], Toolkit::url('chan','threads/'.$_GET['id'].'.php'), $board[0]['folder'].'/thumbs/'.$op->file, $posts);
}

function displayBoard(){
    
}

function displayGlobal(){
    
}

function generateFeed($title,$description,$link,$imageurl,$data){
    global $l;
    header("Content-type: text/xml");
    ?><?='<?xml version="1.0" encoding="UTF-8" ?>'?>
    <rss version="2.0"><channel>
        <title><?=$title?></title>
        <description><?=$description?></description>
        <link><?=$link?></link>
        <image>
            <url>http://<?=HOST.DATAPATH.'chan/'.$imageurl?></url>
            <title>Thread Image</title>
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
                        <div><?=$dat->title.' '.$dat->name.' '.$dat->trip?></div>
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