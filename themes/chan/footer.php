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
&copy;2010-<?=date("Y")?> TymoonNET/NexT, All rights reserved.<br />
Page generated in <?=$total_time?> seconds.<br />
Running TyNET v<?=VERSION?>.
</div>
</body>
</html>
