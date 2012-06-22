<?
if(!class_exists("ChanDataPost"))include(TROOT.'modules/chan/data.php');
if(!class_exists("DataGenerator"))include(TROOT.'modules/chan/datagen.php');
$t->openPage($c->o['chan_title']);
?><div class="frontLatest"><span class="frontLatestHead">Latest Posts:</span><?
    $datagen = new DataGenerator();
    $posts = ChanDataPost::loadFromDB("SELECT postID,BID,PID,title,subject,name,mail,trip,file,fileOrig,options FROM ch_posts ".
                        "WHERE options NOT REGEXP ? AND options NOT REGEXP ? ORDER BY time DESC LIMIT 10",array('h','d'));
    $boards = ChanDataBoard::loadFromDB("SELECT boardID,folder,options FROM ch_boards",array());
    for($i=0;$i<count($posts);$i++){
        $j=0;
        if($posts[$i]->PID==0)$pID=$posts[$i]->postID;else $pID=$posts[$i]->PID;
        while($j<count($boards)){if($boards[$j]->boardID==$posts[$i]->BID)break;$j++;}

        if(strpos($boards[$j]->options,'h')===FALSE||$a->check("chan.mod")){
            $link=$k->url("chan",PROOT.$boards[$j]->folder."/threads/".$pID.".php");
            echo("<div class='postFront'>");

            echo('<div class="postInfo">');
                echo('<a href="'.$link.'#'.$posts[$i]->postID.'">/'.$boards[$j]->folder.'/</a> ');
                echo('<a class="postReply" href="'.$link.'#q'.$posts[$i]->postID.'" id="'.$tID.'">'.$posts[$i]->postID.'</a> ');
                echo('<span class="postTitle">'.$posts[$i]->title.'</span> ');
                echo('<span class="postUsername">');
                    if($posts[$i]->mail!="")echo('<a href="mailto:'.$posts[$i]->mail.'">'.$posts[$i]->name.'</a>');
                    else                    echo($posts[$i]->name);
                echo('</span>');
                echo('<span class="postTripcode">'.$posts[$i]->trip.'</span> ');
                if(strpos($posts[$i]->options,",m")!==FALSE)echo('<span class="postMod">### MOD ###</span> ');
                echo('<span class="postTime">'.$k->toDate($posts[$i]->time).'</span> ');
            echo("</div>");

            if($posts[$i]->file!=""){
                echo('<a class="postImageLink" title="'.$posts[$i]->fileOrig.'" href="'.$c->o['chan_fileloc_extern'].$boards[$j]->folder.'/files/'.$posts[$i]->file.'">');
                echo('<img class="postImageFront" alt="'.$posts[$i]->fileOrig.'" src="'.$c->o['chan_fileloc_extern'].$boards[$j]->folder.'/thumbs/'.$posts[$i]->file.'">');
                echo('</a>');
            }
            echo("<blockquote>".$datagen->parseQuotes($p->deparseAll($p->limitLength($p->automaticLines($p->stripBBCode(
                                    $posts[$i]->subject)),300),0), $posts[$i]->BID, $boards[$j]->folder, $pID)."...</blockquote>");
            echo("</div><br class='clear' />");
        }
    }
?></div>
<div class="frontNews">
    <? @ include(PAGEPATH.'chan_frontpage.php'); ?>
</div>
<?
$t->closePage();
?>