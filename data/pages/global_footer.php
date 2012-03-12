<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-13229468-2");
pageTracker._trackPageview();
} catch(err) {}</script>

<? for($i=0;$i<count($t->js);$i++){echo("<script type='text/javascript' src='".THEMEPATH.$t->tname."/".$t->js[$i]."' ></script>\n");}?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" ></script>
<script type="text/javascript" src="<?=CALLABLESPATH.'js/plugins.js'?>" ></script>
<script type="text/javascript" src="<?=CALLABLESPATH.'js/js.js'?>" ></script>
</body>
</html>