<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-13229468-2");
pageTracker._trackPageview();
} catch(err) {}</script>

<div class="jqmConfirm jqmWindow" id="confirm">
    <p class="jqmConfirmMsg"></p>
    <input type="submit" value="No" />
    <input type="submit" value="Yes" />
</div>

<script type="text/javascript" src="<?=DATAPATH.'js/js.js'?>" ></script>
<? global $t;
for($i=0;$i<count($t->js);$i++){
    if(substr($t->js[$i],0,1)=='/')echo("<script type='text/javascript' src='".DATAPATH."js".$t->js[$i]."' ></script>\n");
    else                           echo("<script type='text/javascript' src='".THEMEPATH.$t->tname."/".$t->js[$i]."' ></script>\n");
}?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" ></script>
<script type="text/javascript" src="<?=DATAPATH.'js/plugins.js'?>" ></script>
</body>
</html>