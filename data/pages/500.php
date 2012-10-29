<? @header('Location: '.DATAPATH.'pages/500/'); ?>
<style type="text/css">
#errorOverlay{
    position:fixed;
    top:0;left:0;right:0;bottom:0;
    padding:10%;
    z-index:10000000;
    background:#151515;
    color:#EEE;
    text-align:center;
    font-size:25pt;
}
#errorOverlay a{font-size:12pt;color: #00EEFF;}
#errorOverlay a:hover{}
#errorFooter{
    font-size:8pt;
    margin-top:10px;
}
#errorNote{
    position:fixed;
    left:0;right:0;bottom:0;
    z-index:10000000;
    background:#151515;
    color:#EEE;
    text-align:center;
    display:none;
    font-size:20pt;
    opacity:0.75;
}
</style>
<div id="errorOverlay">
    An unexpected internal error occurred.<br />
    Administrators have been notified.<br />
    <a href="#" id="show_page">Show me the page anyway.</a>
    <div id="errorFooter">
        &copy;2010-<?=date("Y")?> TymoonNexT, all rights reserved.<br />
        Running TyNET-<?=$CORE::$version?><br />
    </div>
</div>
<div id="errorNote">
    This page may be incomplete or non functional.
</div>
<script type="text/javascript">
    function hide_overlay(){
        document.getElementById('errorOverlay').style.display="none";
        document.getElementById('errorNote').style.display="block";
    }
    document.getElementById('show_page').onclick = hide_overlay;
</script>