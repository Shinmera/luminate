<? function write_footer($title,$board,$boardfolder,$thread=0,$options=""){
    global $c,$k,$a,$t;
    
    ?>
    <div id="threadWatch" class="threadWatch" style="display:none;float:left;position:absolute;">
        <table><thead>
            <tr>
                <th id="watchRemoveCol"></th>
                <th id="watchBoardCol">Board</th>
                <th id="watchIDCol">ID</th>
                <th id="watchPosterCol">Poster</th>
                <th id="watchTitleCol">Title</th>
                <th id="watchUnreadCol">Unread</th>
            </tr>
        </thead><tbody>
        </tbody></table>
        <a href="#" id="watchRefreshButton" class="watchButton" title="Refresh">↻</a> 
        <a href="#" id="watchReadButton" class="watchButton" title="Read All">✔</a> 
        <a href="#" id="watchClearButton" class="watchButton" title="Clear">✘</a> 
    </div>
    <div id="popup" class="popup" style="display:none;">Please wait...</div>
    <div id="previewPost" style="display:none;float:left;position:absolute;"></div>
    <img id="previewImage" style="display:none;max-width:400px;max-height:400px;float:left;position:absolute;" alt="preview"/>
    
    <div class="deleteBox">
    <input type="hidden" name="varboard" value="<?=$board?>" />
    <input type="hidden" name="varfolder" id="varfolder" value="<?=$boardfolder?>" />
    <label>Delete:</label><input type="checkbox" name="varfileonly" value="1" /> File only<br />
    <input type="password" name="varpassword" class="password" />
    <input type="submit" name="submitter" value="Delete" />
    </div><div class="reportBox">
    <label>Report:</label><br /><input type="text" name="varreason" placeholder="reason"/>
    <input type="submit" name="submitter" value="Report" />
    </div></form>

    </div><br clear="all">

    <link rel="alternate" type="application/rss+xml" title="<?=$boardfolder?> RSS feed" href="http://<?=HOST.PROOT?>api.php?m=Chan&c=getBoardRSS&a=board:<?=$board?>" />
    <script type="text/javascript">var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
    </script><script type="text/javascript">
    try {var pageTracker = _gat._getTracker("UA-13229468-2");pageTracker._trackPageview();} catch(err) {}</script>
    <script type="text/javascript">var boardID="<?=$board?>";var boardName="<?=$boardfolder?>";</script>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" ></script>
    <script type="text/javascript" src="<?=PROOT?>callables/plugins.js" ></script>
    <script type="text/javascript" src="<?=PROOT?>callables/js.js" ></script>

    <? global $GEN_STARTTIME;
    $time = explode(' ',microtime());$time = $time[1]+$time[0];$total_time = round(($time-$GEN_STARTTIME),4); ?>
    <?='<? $time = explode(" ",microtime());$time = $time[1]+$time[0];$total_time = round(($time-STARTTIME),4); ?>'?>
    <div class="footer">
        &copy;2010-<?=date("Y")?> TymoonNET/NexT <br />
        Static/Dynamic page generated in <?=$total_time?>/<?='<?=$total_time?>'?> seconds.<br />
        Running TyNET v<?=VERSION?>
    </div>
    </body>
    </html>
    <?='<? ob_end_flush(); ?>'?>
    
    <?
    
} ?>
