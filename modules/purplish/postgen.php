<?
class PostGenerator{
    public static function generatePost($id,$board){
        $post = DataModel::getData('ch_posts',"SELECT * FROM ch_posts WHERE postID=? AND BID=? ORDER BY postID DESC LIMIT 1", array($id,$board));
        if($post==null)throw new Exception("No such post.");
        PostGenerator::generatePostFromObject($post);
    }

    public static function generatePostFromObject($post){
        global $c,$k,$l;
        if(!class_exists("DataGenerator"))include('datagen.php');
        $pID=$post->postID;
        if($post->PID!=0)$tID=$post->PID;else $tID=$post->postID;
        $datagen = new DataGenerator();
        $folder = $c->getData("SELECT folder FROM ch_boards WHERE boardID=?",array($post->BID));$folder=$folder[0]['folder'];
        Toolkit::mkdir(ROOT.DATAPATH.'chan/'.$folder.'/posts/');
        $path = ROOT.DATAPATH.'chan/'.$folder.'/posts/'.$pID.'.php';
        $tpath= PROOT.$folder.'/threads/'.$tID.'.php';
        $type = '';
        if(strpos($post->options,'s')!==FALSE)$type.="sticky";
        if(strpos($post->options,'l')!==FALSE)$type.="locked";

        if(BUFFER)ob_end_flush();flush();
        ob_start();
        
        ?>
        <?='<? if(!defined("INIT"))include("'.TROOT.'config.php"); ?>'."\n"?>
        <?='<? global $a,$l; if($a==null)$a = $l->loadModule("Auth"); ?>'."\n"?>
        <?='<? if("'.$post->ip.'"==$_SERVER["REMOTE_ADDR"]||strpos("'.$post->options.'",",h")===FALSE||$a->check("chan.mod.hidden")){ ?>'."\n"?>
            <? if($post->PID==0){ ?><div class="postOP <?=$type?>" id="P<?=$pID?>">
            <? }else            { ?><div class="post   <?=$type?>" id="P<?=$pID?>"><? } ?>
                <a name="<?=$pID?>"></a>
                <div class="postInfo">
                    <input type="checkbox" name="varposts[]" value="<?=$pID?>" /> 
                    <a href="<?=$tpath?>#<?=$pID?>">No.</a> 
                    <a class="postReply" href="<?=$tpath?>#q<?=$pID?>" id="<?=$tID?>"><?=$pID?></a> 
                    <span class="postTitle"><?=$post->title?></span> 
                    <span class="postUsername">
                        <? if(trim($post->name)==""&&trim($post->trip)=="")$post->name="Anonymous"; 
                        if($post->mail!="")echo('<a href="mailto:'.$post->mail.'">'.$post->name.'</a>');
                        else               echo($post->name); ?>
                    </span>
                    <span class="postTripcode"><?=$post->trip?></span> 
                    <? if(strpos($post->options,"m")!==FALSE) echo('<span class="postMod">### MOD ###</span>'); ?>
                    <span class="postTime"><?=$k->toDate($post->time)?></span> 
                    <span id="postType" class="<?=$type?>">&nbsp;</span> 
                    <span class="buttons">
                        <? if($post->PID==0){ ?>
                            <?='<? if($a->check("chan.mod.move")){ ?>'?>
                                <a class="moveThread" href="<?=PROOT?>api/chan/move?id=<?=$post->postID?>&bid=<?=$post->BID?>">Move / Merge</a>
                            <?='<? } ?>'?>
                        <? } ?>
                        <?='<? if($a->check("chan.mod.purge")){ ?>'?>
                            <a class="banUser" href="<?=PROOT?>api/chan/ban?id=<?=$post->postID?>&bid=<?=$post->BID?>">Ban</a>
                        <?='<? }if($a->check("chan.mod.ban")){ ?>'?>
                            <a class="purgeUser" href="<?=PROOT?>api/chan/purge?id=<?=$post->postID?>&bid=<?=$post->BID?>">Purge</a>
                        <?='<? }if($a->check("chan.mod.search")){ ?>'?>
                            <a class="searchUser" href="<?=PROOT?>api/chan/search?id=<?=$post->postID?>&bid=<?=$post->BID?>">Search</a>
                        <?='<? }if($a->check("chan.mod.delete")){ ?>'?>
                            <a class="deletePost" href="<?=PROOT?>api/chan/delete?id=<?=$post->postID?>&bid=<?=$post->BID?>">Delete</a>
                        <?='<? }if($a->check("chan.mod.edit")){ ?>'?>
                            <a class="editPost" href="<?=PROOT?>api/chan/edit?id=<?=$post->postID?>&bid=<?=$post->BID?>">Edit</a>
                        <?='<? } ?>'?>
                    </span> <br />
                    <? if($post->file!=""){ ?>
                        <span class="fileName">File <?=$post->fileorig?></span> 
                        (<a class="fileLink" href="<?=$c->o['chan_fileloc_extern'].$folder.'/files/'.$post->file?>"><?=$post->file?></a>) 
                        <span class="fileSize"><?=$k->displayFilesize($post->filesize)?></span> 
                        <span class="fileDimensions"><?=$post->filedim?></span> 
                    <? } ?>
                </div><div class="postContent">
                    <? if($post->file!=""){ ?>
                        <a class="postImageLink" title="<?=$post->fileorig?>" href="<?=$c->o['chan_fileloc_extern'].$folder.'/files/'.$post->file?>">
                            <img class="postImage" alt="<?=$post->fileorig?>" src="<?=$c->o['chan_fileloc_extern'].$folder.'/thumbs/'.$post->file?>" border="0">
                        </a>
                    <? }
                    $temp=$datagen->parseQuotes(Toolkit::autoBreakLines($post->subject),  $post->BID, $folder, $tID);
                    if(strpos($post->options,"p")!==FALSE)$temp=$l->triggerPARSE('Purplish',$temp,true,true,array(),array('suites'=>array('*','deftag')));
                    else                                  $temp=$l->triggerPARSE('Purplish',$temp);
                    $shorttemp=str_replace("\n",'<br />',Toolkit::limitLines(str_replace('<br />',"\n",$temp),$c->o['chan_maxlines']));
                    
                    ?>
                    <article><blockquote>
                        <?='<? if(POST_SHORT===TRUE){ ?>'?>
                            <?=($shorttemp==$temp)?$temp:$shorttemp.'<hr /><a href="'.$tpath.'#'.$pID.'" class="direct">Post abbreviated.</a>'?>
                        <?='<? }else{ ?>'?>
                            <?=$temp;?>
                        <?='<? } ?>'?>
                    </blockquote></article>
                    <br class="clear" />
                </div>
            </div><br />
        <?='<? } ?>'?>
        
        <?
        $data = ob_get_contents();
        ob_end_clean();
        file_put_contents($path,$data,LOCK_EX);
        if(BUFFER)ob_start();
    }

}
?>
