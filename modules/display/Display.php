<? class Display extends Module{
public static $name="Display";
public static $author="NexT";
public static $version=2.01;
public static $short='display';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

function buildMenu($menu){
    global $k;
    $menu[]=array("Gallery",$k->url("gallery",""));
    return $menu;
}

function displayPanel(){
    global $k,$a;
    ?><li>Gallery
        <ul class="menu">
            <a href="<?=$k->url("admin","Gallery")?>"><li>Overview</li></a>
            <? if($a->check("gallery.admin.pictures")){ ?>
            <a href="<?=$k->url("admin","Gallery/pictures")?>"><li>Manage Pictures</li></a><? } ?>
            <? if($a->check("gallery.admin.folders")){ ?>
            <a href="<?=$k->url("admin","Gallery/folders")?>"><li>Organize Folders</li></a><? } ?>
        </ul>
    </li><?
}
function adminNavbar($menu){$menu[]='Gallery';return $menu;}

function displayAdminPage(){
    
}

function displayGalleryPage(){
    global $site,$params,$a;
    switch($site){
        case 'p':if($a->check("gallery.picture"))$this->displayGalleryPicture();break;
        case 'f':if($a->check("gallery.folder"))$this->displayGalleryFolder();break;
        default :if($a->check("gallery"))$this->displayGalleryOverview();break;
    }
}

function displayGalleryOverview(){
    
}

function displayGalleryFolder(){
    global $params;
    $folder = DataModel::getData("display_folders","SELECT folderID,inherit FROM display_folders WHERE title LIKE ?",array($params[1]));
    
    if($folder!=null){
        $folders = "FID=".implode(" OR FID=",explode(";",$folder->folderID.";".$folder->inherit));
        $pictures = DataModel::getData("display_pictures","SELECT pictureID,title,filename FROM display_pictures WHERE ".$folders." ORDER BY time DESC ".$limit);


        $t->openPage($folder->$params[1]." - Gallery");
        
    }else{
        $t->openPage("404 - Gallery");
        echo("<div class='large center'>Folder not found.</div>");
    }
    $t->closePage();
}
    
function displayGalleryPicture(){
    global $params,$t,$a,$k;
    if(strpos($params[1],"-")!==FALSE)$page=substr($pparams[1],0,strpos($pparams[1],"-"));
    $picture = DataModel::getData("display_pictures","SELECT FID,title,subject,time,tags,filename FROM display_pictures WHERE pictureID=?",array($params[1]));
    
    if($picture!=null){
        $folder = DataModel::getData("display_folders","SELECT title FROM display_folders WHERE folderID=?",array($picture->FID));
        $user = DataModel::getData("ud_users","SELECT ud_users.displayname,ud_users.filename FROM ud_users ".
                                               "INNER JOIN  ud_permissions ON ud_permissions.UID = ud_users.userID WHERE ud_permissions.tree LIKE ?",
                                                array('%gallery.folder.'.$folder->title.'%'));
        $t->openPage($picture->title." - Gallery");
        
        ?><div id='pageNav'>
            <? if($user->filename==''){ $user->filename='noguy.png'; } ?>
            <img src="<?=AVATARPATH.$user->filename?>" alt="" title="<?=$user->displayname?>'s avatar" />
            <div style="display:inline-block">
                <h1 class="sectionheader"><?=$user->displayname?></h1>
            </div>
            <div class="tabs">
            </div>
        </div><?
    }else{
        $t->openPage("404 - Gallery");
        echo("<div class='large center'>Picture not found.</div>");
    }
    $t->closePage();
}

function displayPictureEditPage(){
    
}


function displayEmbeddedGalleryFolder($page){
    
}

}
?>
