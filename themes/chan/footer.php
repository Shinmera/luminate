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
