<? class Display{
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

function displayGalleryPage(){
    global $site;
    echo("DICKS");
}

function displayGalleryFolder(){
    
}

function displayGalleryPicture(){
    
}


}
?>
