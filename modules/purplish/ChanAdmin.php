<? class ChanAdmin extends Module{
public static $name="ChanAdmin";
public static $author="NexT";
public static $version=0.01;
public static $short='cadmin';
public static $required=array("Auth");
public static $hooks=array("foo");

function display(){
    global $params,$a;
    switch($params[0]){
        case 'categories':  if($a->check("chan.admin.categories"))  $this->displayCategories(); break;
        case 'boards':      if($a->check("chan.admin.boards"))      $this->displayBoards();     break;
        case 'edit':        if($a->check("chan.admin.boards"))      $this->displayEditBoard();  break;
        case 'filetypes':   if($a->check("chan.admin.filetypes"))   $this->displayFiletypes();  break;
        case 'latestposts': if($a->check("chan.mod.latestposts"))   $this->displayLatestPosts();break;
        case 'latestimages':if($a->check("chan.mod.latestimages"))  $this->displayLatestImages();break;
        case 'tickets':     if($a->check("chan.mod.tickets"))       $this->displayReports();    break;
        case 'bans':        if($a->check("chan.mod.bans"))          $this->displayBans();       break;
        default:            if($a->check("chan.*"))                 $this->displayStatistics(); break;
    }
}

function displayPanel(){
    global $k,$a;
    if($a->check('chan.admin.*')){
        ?><li>Purplish
            <ul class="menu">
                <a href="<?=$k->url("admin","Chan/")?>"><li>Overview</li></a>
                <? if($a->check("chan.admin.categories")){ ?>
                <a href="<?=$k->url("admin","Chan/categories")?>"><li>Categories</li></a><? } ?>
                <? if($a->check("chan.admin.boards")){ ?>
                <a href="<?=$k->url("admin","Chan/boards")?>"><li>Boards</li></a><? } ?>
                <? if($a->check("chan.admin.filetypes")){ ?>
                <a href="<?=$k->url("admin","Chan/filetypes")?>"><li>Filetypes</li></a><? } ?>
                <? if($a->check("chan.mod.latestposts")){ ?>
                <a href="<?=$k->url("admin","Chan/latestposts")?>"><li>Latest Posts</li></a><? } ?>
                <? if($a->check("chan.mod.latestimages")){ ?>
                <a href="<?=$k->url("admin","Chan/latestimages")?>"><li>Latest Images</li></a><? } ?>
                <? if($a->check("chan.mod.tickets")){ ?>
                <a href="<?=$k->url("admin","Chan/tickets")?>"><li>Report Tickets</li></a><? } ?>
                <? if($a->check("chan.mod.bans")){ ?>
                <a href="<?=$k->url("admin","Chan/bans")?>"><li>Ban Entries</li></a><? } ?>
            </ul>
        </li><?
    }
}

function displayStatistics(){
    
}

function displayCategories(){
    
}

function displayBoards(){
    
}

function displayEditBoard(){
    
}

function displayFiletypes(){
    
}

function displayLatestPosts(){
    
}

function displayLatestImages(){
    
}

function displayReports(){
    
}

function displayBans(){
    
}
}
?>