<? global $c,$t,$SUPERIORPATH,$params,$action,$existing; ?>
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

<div id="main-sidebar">
    <div id="logo"><img src="<?=$c->o['lore_logo']?>" /></div>
    <div class="navbar">
        <h5>Navigation</h5>
        <ul>
        <li><a href="<?=Toolkit::url("wiki","")?>">Index</a></li>
        <li><a href="<?=Toolkit::url("wiki","special/latest")?>">Latest Changes</a></li>
        <li><a href="<?=Toolkit::url("wiki","special/newest")?>">Newest Articles</a></li>
        <li><a href="<?=Toolkit::url("wiki","special/random")?>">Random</a></li>
        <li><a href="<?=Toolkit::url("wiki","special/help")?>">Help</a></li>
        </ul>
    </div>
    <form class="navbar" action="<?=Toolkit::url("wiki","special/search")?>" method="post">
        <h5>Search</h5>
        <input type="text" name="query" style="width:140px;" />
        <input type="submit" value="Go" />
    </form>
    <div class="navbar">
        <h5>TyNET</h5>
        <nav id="navbar">
            <? $t->printMenu(null,true); ?>
        </nav>
    </div>
</div>
<div id="main-content">
    <div id="header">

    </div>

    <ul id="content-tabs">
        <li class="<? echo($existing);if($action=='view')   echo('selected');?>" ><a href="<?=Toolkit::url("wiki",$SUPERIORPATH)?>">           View</a></li>
        <li class="<? echo($existing);if($action=='history')echo('selected');?>" ><a href="<?=Toolkit::url("wiki",$SUPERIORPATH."/history")?>">History</a></li>
        <li class="<? echo($existing);if($action=='edit')   echo('selected');?>" ><a href="<?=Toolkit::url("wiki",$SUPERIORPATH."/edit")?>">   Edit</a></li>
        <li class="<? echo($existing);if($params[1]=='discuss')echo('selected'); ?> flRight"><a href="<?=Toolkit::url("wiki",$SUPERIORPATH."/discuss")?>">Discuss</a></li>
    </ul>
    <article id="content">