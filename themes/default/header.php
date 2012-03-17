<? global $c,$k,$t,$a; ?>
<div id="header">
    <?=$c->o['sitename']?>

    <nav id="navbar">
        <? $menu=array();
        $menu[]=array('Index',NODOMAIN);
        if($a->check('admin.panel'))
            $menu[]=array('Admin',$k->url("admin",""),"float:right;",array(
                                   array('Panel',   $k->url("admin","panel")),
                                   array('Options', $k->url("admin","options")),
                                   array('Log',     $k->url("admin","log")),
                                   array('Modules', $k->url("admin","modules")),
                                   array('Hooks',   $k->url("admin","hooks"))
                               ));
        if($a->user->userID=='')
            $menu[]=array('Login',$k->url("login",""),"float:right;");
        $t->printMenu($menu); ?>
    </nav>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var nav = $("#navbar");
        nav.find("li").each(function() {
            if ($(this).find("ul").length > 0) {
                //Add indicator.
                $("<span>").text("\u21b4").appendTo($(this).children(":first"));

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