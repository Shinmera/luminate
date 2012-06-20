<? global $a ?>
<? if("'.$post->ip.'"==$_SERVER["REMOTE_ADDR"]||strpos("'.$post->options.'",",h")===FALSE||$a->check("chan.mod.hidden")){ ?>
    <? if($post->PID==0){ ?><div class="postOP <?=$type?>" id="P<?=$pID?>">
    <? }else            { ?><div class="post   <?=$type?>" id="P<?=$pID?>"><? } ?>
        <a name="<?=$pID?>"></a>
        <div class="postInfo">
            <input type="checkbox" name="varposts[]" value="<?=$pID?>" /> 
            <a href="'.$tpath.'#<?=$pID?>">No.</a> 
            <a class="postReply" href="'.$tpath.'#q<?=$pID?>" id="<?=$tID?>"><?=$pID?></a> 
            <span class="postTitle"><?=$post->title?></span> 
            <span class="postUsername">
                <? if(trim($post->name)==""&&trim($post->trip)=="")$post->name="Anonymous"; 
                if($post->mail!="")echo('<a href="mailto:'.$post->mail.'">'.$post->name.'</a>');
                else               echo('$post->name'); ?>
            </span>
            <span class="postTripcode"><?=$post->trip?></span> 
            <? if(strpos($post->options,"m")!==FALSE) echo('<span class="postMod">### MOD (<?=$a->generateDelta();?>) ###</span>'); ?>
            <span class="postTime"><?=$k->toDate($post->time)?></span> 
            <span id="postType" class="<?=$type?>">&nbsp;</span> 
            <span class="buttons">
                <? if($post->PID==0){ ?>
                    <? if($a->check("chan.mod.move")){ ?>
                        <a class="moveThread" href="/api.php?m=Chan&c=moveForm&a=postID:<?=$post->postID?>;boardID:<?=$post->BID?>">Move</a>
                    <? }if($a->check("chan.mod.merge")){ ?>
                        <a class="mergeThread" href="/api.php?m=Chan&c=mergeForm&a=postID:<?=$post->postID?>;boardID:<?=$post->BID?>">Merge</a>
                <? } ?>
                <? }if($a->check("admin.ban")){ ?>
                    <a class="banUser" href="/api.php?m=Chan&c=banForm&a=mask:<?=$post->ip?>;postID:<?=$post->postID?>;boardID:<?=$post->BID?>">Ban</a>
                <? }if($a->check("admin.ban")){ ?>
                    <a class="purgeUser" href="/api.php?m=Chan&c=purgeForm&a=mask:<?=$post->ip?>">Purge</a>
                <? }if($a->check("chan.mod.search")){ ?>
                    <a class="searchUser" href="/api.php?m=Chan&c=searchForm&a=postID:<?=$post->postID?>;boardID:<?=$post->BID?>">Search</a>
                <? }if($a->check("chan.mod.delete")){ ?>
                    <a class="deletePost" href="/api.php?m=Chan&c=deleteForm&a=postID:<?=$post->postID?>;boardID:<?=$post->BID?>">Delete</a>
                <? }if($a->check("chan.mod.edit")){ ?>
                    <a class="editPost" href="/api.php?m=Chan&c=editForm&a=postID:<?=$post->postID?>;boardID:<?=$post->BID?>">Edit</a>
                <? } ?>
            </span> <br />
            <? if($post->file!=""){ ?>
                <span class="fileName">File <?=$post->fileOrig?></span> 
                (<a class="fileLink" href="<?=$c->o['chan_fileloc_extern'].$folder.'/files/'.$post->file?>"><?=$post->file?></a>) 
                <span class="fileSize"><?=$k->displayFilesize($post->fileSize)?></span> 
                <span class="fileDimensions"><?=$post->fileDim?></span> 
            <? } ?>
        </div><div class="postContent">
            <?=if($post->file!=""){ ?>
                <a class="postImageLink" title="<?=$post->fileOrig?>" href="<?=$c->o['chan_fileloc_extern'].$folder.'/files/'.$post->file?>">
                    <img class="postImage" alt="<?=$post->fileOrig?>" src="<?=$c->o['chan_fileloc_extern'].$folder.'/thumbs/'.$post->file?>" border="0">
                </a>
            <? }

            if(strpos($post->options,"p")!==FALSE) $temp=$datagen->parseQuotes($p->deparseAll($p->automaticLines($post->subject),99),  $post->BID, $folder, $tID);
            else                                   $temp=$datagen->parseQuotes($p->deparseAll($p->automaticLines($post->subject),0 ),  $post->BID, $folder, $tID);
            ?>
            <article><blockquote>
                <? if(POST_SHORT===TRUE){ ?>
                    <?=$p->balanceTags($p->limitLines($temp,$c->o['chan_maxlines'],"<br />"));?>
                <? }else{ ?>
                    <?=$temp;?>
                <? } ?>
            </blockquote></article>
        </div>
    </div><br />
<? } ?>