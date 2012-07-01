<?
class ThreadGenerator{
    public static function generateThread($id,$board,$posts=false){
        $post = DataModel::getData('ch_posts',"SELECT * FROM ch_posts WHERE postID=? AND BID=? AND PID=0 ORDER BY postID DESC LIMIT 1", array($id,$board));
        if($post==null)throw new Exception("No such post.");
        ThreadGenerator::generateThreadFromObject($post,$posts);
    }

    public static function generateThreadFromObject($post,$posts=false){
        global $c,$k,$t,$l,$PAGETITLE,$NO_BUFFER;
        $previousTheme = $t->tname;
        $t = $l->loadModule('Themes');
        $t->loadTheme("chan");
        $PAGETITLE=$board->title.' - '.$c->o['chan_title'];
        
        $pID=$post->postID;
        $postlist = $c->getData("SELECT postID FROM ch_posts WHERE PID=? AND BID=? AND `options` NOT LIKE ? ORDER BY postID ASC",array($pID,$post->BID,'%d%'));
        Toolkit::assureArray($postlist);
        if($posts){
            if(!class_exists("PostGenerator"))include('postgen.php');
            PostGenerator::generatePost($pID, $post->BID);
        }
        $board = DataModel::getData('ch_boards',"SELECT boardID,folder,subject,title,filetypes,options FROM ch_boards WHERE boardID=?",array($post->BID));
        $path = ROOT.DATAPATH.'chan/'.$board->folder.'/threads/'.$pID.'.php';
        
        if(BUFFER)ob_end_flush;flush();$NO_BUFFER=true;
        ob_start(create_function('$buffer', 'return "";'));
        ?>
        
        <?='<? $postlist=array('.$pID?>
        <? for($i=0;$i<count($postlist);$i++){
            echo(','.$postlist[$i]['postID']);
         } ?>
        <?=');
        if($_GET["a"]=="postlist")die(implode(";",$postlist));
        if(is_numeric($_GET["a"]))header("Location: '.$k->url("chan",$board->folder).'/posts/".$_GET["a"].".php"); ?>'?>
        
        <? require_once(PAGEPATH.'chan/chan_header.php'); ?>
        <?=write_header($post->title.' - '.$c->o['chan_title'],$board,$pID,$post->options.$board->options,$post->ip);?>

        <input type="hidden" id="view" value="thread" />
        <div class="threadToolbar">
            <a href="<?=$k->url("chan",$board->folder)?>">Return</a> 
            <a href="?b=-50">Last 50</a> 
            <a href="?e=100">First 100</a>
            <a href='#' class='watchThread' id='<?=$pID?>'>Watch</a>
        </div>
        
        <?='<? 
        $begin=$_GET["b"];
        if(!is_numeric($begin))$begin=1;
        if($begin<=0)          $begin=count($postlist)+$begin;
        if($begin<=0)          $begin=1;
        $end  =$_GET["e"];
        if(!is_numeric($end)||$end<=0)$end=count($postlist);
        
        @ include("'.ROOT.DATAPATH.'chan/'.$board->folder.'/posts/".$postlist[0].".php");?>'?>
        <div class="thread" id="T<?=$pID?>">
            <?='<?
            for($i=$begin,$temp=count($postlist);$i<$end&&$i<$temp;$i++){
                @ include("'.ROOT.DATAPATH.'chan/'.$board->folder.'/posts/".$postlist[$i].".php");
            } ?>'?>
        </div><br class="clear" />
        
        <? require_once(PAGEPATH.'chan/chan_footer.php'); ?>
        <?=write_footer($post->title.' - '.$c->o['chan_title'],$post->BID,$board->folder,$pID,$post->options.$board->options);?>
        
        <?
        file_put_contents($path,ob_get_contents(),LOCK_EX);
        ob_end_clean();
        if(BUFFER)ob_start();$NO_BUFFER=false;
        $t->loadTheme($previousTheme);
    }
}
?>
