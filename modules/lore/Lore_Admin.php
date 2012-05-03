<? class LoreAdmin{
    function displayPanel(){
        global $k,$a;
        ?><li>Lore
            <ul class="menu">
                <a href="<?=$k->url("admin","Lore")?>"><li>Overview</li></a>
                <? if($a->check("lore.admin.article")){ ?>
                <a href="<?=$k->url("admin","Lore/articles")?>"><li>Articles</li></a><? } ?>
                <? if($a->check("lore.admin.portal")){ ?>
                <a href="<?=$k->url("admin","Lore/portals")?>"><li>Portals</li></a><? } ?>
                <? if($a->check("lore.admin.category")){ ?>
                <a href="<?=$k->url("admin","Lore/categories")?>"><li>Categories</li></a><? } ?>
                <? if($a->check("lore.admin.file")){ ?>
                <a href="<?=$k->url("admin","Lore/files")?>"><li>Files</li></a><? } ?>
                <? if($a->check("lore.admin.user")){ ?>
                <a href="<?=$k->url("admin","Lore/users")?>"><li>User pages</li></a><? } ?>
                <? if($a->check("lore.admin.templates")){ ?>
                <a href="<?=$k->url("admin","Lore/templates")?>"><li>Templates</li></a><? } ?>
            </ul>
        </li><?
    }
    
    function displayAdminPage(){
        
    }
}
?>
