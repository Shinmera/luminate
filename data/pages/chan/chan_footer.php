<? function write_footer($title,$board,$boardfolder,$thread=0,$options=""){
    global $c,$k,$a,$t;
    $write ='<div id="threadWatch" class="threadWatch" style="display:none;float:left;position:absolute;">';
        $write.='<table><thead>';
            $write.='<tr><th id="watchRemoveCol"></th><th id="watchBoardCol">Board</th><th id="watchIDCol">ID</th>
                     <th id="watchPosterCol">Poster</th><th id="watchTitleCol">Title</th><th id="watchUnreadCol">Unread</th></tr>';
        $write.='</thead><tbody>';
        $write.='</tbody></table>';
        $write.='<a href="#" id="watchRefreshButton" class="watchButton" title="Refresh">↻</a> ';
        $write.='<a href="#" id="watchReadButton" class="watchButton" title="Read All">✔</a> ';
        $write.='<a href="#" id="watchClearButton" class="watchButton" title="Clear">✘</a> ';
    $write.='</div>';
    $write.='<div id="popup" class="popup" style="display:none;">Please wait...</div>';
    $write.='<div id="previewPost" style="display:none;float:left;position:absolute;"></div>';
    $write.='<img id="previewImage" style="display:none;max-width:400px;max-height:400px;float:left;position:absolute;" alt="preview"/>'."\n";
    //REPORT AND DELETE SHIT
    $write.='<div class="deleteBox">'."\n";
    $write.='<input type="hidden" name="varboard" value="'.$board.'" />'."\n";
    $write.='<input type="hidden" name="varfolder" id="varfolder" value="'.$boardfolder.'" />'."\n";
    $write.='<label>Delete:</label><input type="checkbox" name="varfileonly" value="1" /> File only<br />';
    $write.='<input type="password" name="varpassword" class="password" />'."\n";
    $write.='<input type="submit" name="submitter" value="Delete" />'."\n";
    $write.='</div><div class="reportBox">'."\n";
    $write.='<label>Report:</label><br /><input type="text" name="varreason" placeholder="reason"/>'."\n";
    $write.='<input type="submit" name="submitter" value="Report" />'."\n";
    $write.='</div></form>'."\n";

    $write.='</div><br clear="all">'."\n";

    //FOOTER CONTENT
    $write.='<link rel="alternate" type="application/rss+xml" title="'.$boardfolder.' RSS feed" href="http://'.HOST.PROOT.'api.php?m=Chan&c=getBoardRSS&a=board:'.$board.'" />';
    $write.='<script type="text/javascript">var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");'."\n";
    $write.='document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));'."\n";
    $write.='</script><script type="text/javascript">'."\n";
    $write.='try {var pageTracker = _gat._getTracker("UA-13229468-2");pageTracker._trackPageview();} catch(err) {}</script>'."\n";
    $write.='<script type="text/javascript">var boardID="'.$board.'";var boardName="'.$boardfolder.'";</script>'."\n";

    $write.='<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" ></script>'."\n";
    $write.='<script type="text/javascript" src="'.PROOT.'callables/plugins.js" ></script>'."\n";
    $write.='<script type="text/javascript" src="'.PROOT.'callables/js.js" ></script>'."\n";

    //ACTUAL FOOTER
    global $GEN_STARTTIME;
    $time = explode(' ',microtime());$time = $time[1]+$time[0];$total_time = round(($time-$GEN_STARTTIME),4);
    $write.='<? $time = explode(" ",microtime());$time = $time[1]+$time[0];$total_time = round(($time-STARTTIME),4); ?>';
    $write.='<div class="footer">&copy;2010-<?=date("Y")?> TymoonNET/NexT <br />'."\n";
    $write.='Static/Dynamic page generated in '.$total_time.'/<?=$total_time?> seconds.<br />'."\n";
    $write.='Running TyNET v'.VERSION.'</div>'."\n";
    $write.='</body>'."\n";
    $write.='</html><? ob_end_flush(); ?>'."\n";
    return $write;
} ?>
