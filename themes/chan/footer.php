<div id="popup" class="popup" style="display:none;">Please wait...</div>
<div id="previewPost" style="display:none;float:left;position:absolute;"></div>
<img id="previewImage" style="display:none;max-width:400px;max-height:400px;float:left;position:absolute;" alt="preview"/>
<link rel="alternate" type="application/rss+xml" title="<?=$c->o['chan_title']?> Latest Posts Feed" href="<?=Toolkit::url('api','chan/rss')?>" />

<? global $c;
$time = explode(' ',microtime());
$time = $time[1]+$time[0];
$total_time = round(($time-STARTTIME),4);
?>
</div>
<div class="footer">
Running TyNET-<?=CORE::$version?> | Purplish-<?=Purplish::$version?><br />
&copy;2010-<?=date("Y")?> TymoonNET/NexT, All rights reserved.<br />
Page generated in <?=$total_time?> seconds.
</div>
</body>
</html>
<? global $c;$c->query("INSERT INTO ch_hits VALUES(?,?,?,?)",array($_SERVER["REMOTE_ADDR"],time(),0,0)); ?>