<? function write_footer($title,$board,$boardfolder,$thread=0,$options=""){
    global $c,$l,$CORE;
    ?>
    <input type="hidden" name="board" value="<?=$board?>" />
    <input type="hidden" name="folder" id="varfolder" value="<?=$boardfolder?>" />
    <div class="deleteBox">
        <label>Delete:</label><input type="checkbox" name="fileonly" value="1" /> File only<br />
        <input type="password" name="password" class="password" />
        <input type="submit" name="submitter" value="Delete" />
    </div>
    <div class="reportBox">
        <label>Report:</label><br /><input type="text" name="reason" placeholder="reason" maxlength="512" autocomplete="off" />
        <input type="submit" name="submitter" value="Report" />
    </div></form>
    </div><br clear="all">
    
    <div id="threadWatch" class="threadWatch">
        <div class="tablediv"><table><thead>
            <tr>
                <th id="watchRemoveCol"></th>
                <th id="watchBoardCol">Board</th>
                <th id="watchIDCol">ID</th>
                <th id="watchPosterCol">Poster</th>
                <th id="watchTitleCol">Title</th>
                <th id="watchUnreadCol">Unread</th>
            </tr>
        </thead><tbody>
        </tbody></table></div>
        <a href="#" id="watchRefreshButton" class="watchButton" title="Refresh">↻</a> 
        <a href="#" id="watchReadButton" class="watchButton" title="Read All">✔</a> 
        <a href="#" id="watchClearButton" class="watchButton" title="Clear">✘</a> 
    </div>
    <options id="options"></options>
    <div id="proot" style="display:none;"><?=PROOT?></div>
    <div id="popup" class="popup" style="display:none;">Please wait...</div>
    <div id="previewPost" style="display:none;float:left;position:absolute;"></div>
    <img id="previewImage" style="display:none;max-width:400px;max-height:400px;float:left;position:absolute;" alt="preview"/>
    <script type="text/javascript">var boardName = "<?=$boardfolder?>";</script>
    <link rel="alternate" type="application/rss+xml" title="<?=$c->o['chan_title']?> Latest Posts Feed" href="<?=Toolkit::url('api','chan/rss')?>" />
    <link rel="alternate" type="application/rss+xml" title="/<?=$boardfolder?>/ Thread Feed" href="<?=Toolkit::url('api','chan/rss/board/?bid='.$board)?>" />
    <? if($thread!=0){ ?>
        <link rel="alternate" type="application/rss+xml" title="<?=$title?> Post Feed" href="<?=Toolkit::url('api','chan/rss/thread/?id='.$thread.'&bid='.$board)?>" />
    <? } ?>
    
    <? $l->triggerHook('footer','Purplish',array($title,$board,$thread)); ?>
    
    <? global $GEN_STARTTIME;
    $time = explode(' ',microtime());$time = $time[1]+$time[0];$total_time = round(($time-$GEN_STARTTIME),4); ?>
    <?='<? $time = explode(" ",microtime());$time = $time[1]+$time[0];$total_time = round(($time-STARTTIME),4); ?>'?>
    <div class="footer">
        Running TyNET-<?=$CORE::$version?> | Purplish-<?=Purplish::$version?><br />
        &copy;2010-<?=date("Y")?> TymoonNET/NexT, all rights reserved.<br />
        Static/Dynamic page generated in <?=$total_time?>/<?='<?=$total_time?>'?> seconds.<br />
    </div>
    
    <? include(PAGEPATH.'global_footer.php'); ?>
    <?=(BUFFER)?'<? ob_end_flush(); ?>':''?>
    <?='<? $c->query("INSERT INTO ch_hits VALUES(?,?,?,?)",array($_SERVER["REMOTE_ADDR"],time(),'.$board.','.$thread.')); ?>'."\n" ?>
    
    <?
    
} ?>
