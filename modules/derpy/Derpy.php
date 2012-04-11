<? class Derpy{
public static $name="Derpy";
public static $author="NexT";
public static $version=0.01;
public static $short='Derpy';
public static $required=array();
public static $hooks=array("foo");

function userNavbar($menu){$menu[]="Messages";return $menu;}
function buildMenu($menu){$menu[]=array("Messages",Toolkit::url("user","panel/Messages"));return $menu;}

function displayMessagesPage(){
    global $params;
    
    switch($params[2]){
        case 'write':$this->displayWritePage();break;
        case 'read':$this->displayReadPage();break;
        default:
            ?><div class="tabbed">
                <form action="#" method="post" name="Inbox">
                    
                </form>
                <form action="#" method="post" name="Sent">

                </form>
                <form action="#" method="post" name="Saved">

                </form>
                <div name="Write" href="<?=Toolkit::url("user","panel/Messages/write");?>" ></div>
            </div><?
            break;
    }
}

function displayWritePage(){
    
}

function displayReadPage(){
    
}

}
?>