<? global $k,$c,$CORE; ?>

</div>
<div id="footer">
    &copy;2010-<?=date("Y")?> TymoonNexT, all rights reserved.<br />
    Running TyNET-<?=$CORE::$version?><br />
    Page generated in <?=$k->getTimeElapsed();?>s using <?=$c->queries?> queries.
</div>