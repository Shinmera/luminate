<? global $c,$k,$t,$a,$l; ?>
<div id="header">
    <h1><?=$c->o['sitename']?></h1>

    <nav id="navbar">
        <? $t->printMenu(); ?>
    </nav>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var nav = $("#navbar");
        nav.find("li").each(function() {
            if ($(this).find("ul").length > 0) {
                //Add indicator.
                //$("<span>").text("\u21b4").appendTo($(this).children(":first"));
                $(this).children(":first").addClass("menu");

                $(this).mouseenter(function() {
                    $(this).find("ul").stop(true, true).slideDown();
                });

                $(this).mouseleave(function() {
                    $(this).find("ul").stop(true, true).slideUp();
                });
            }
        });
    });
</script>
<div id="content">