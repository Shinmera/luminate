<? class Display{
public static $name="Display";
public static $author="NexT";
public static $version=0.01;
public static $short='display';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

//Access: display.folder.$folder
//Default access is display.folder.username
//Other access is managed through folders.
//Subfolders are separated by dots to conform with the perms.
//Pictures are saved in ./data/uploads/display/$folder/$filename

function buildMenu($menu){return $menu;}

function displayPage(){
    global $t;
    $t->openPage();
        $this->displayPicture();
        $this->displayFolder();
    $t->closePage();
}

function displayPicture($pictureID){
    
}

function displayFolder($folderpath){
    
}

function displayAdmin(){
    
}

}
?>