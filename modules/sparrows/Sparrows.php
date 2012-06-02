<? class Sparrows{
public static $name="Sparrows";
public static $author="NexT";
public static $version=0.01;
public static $short='Sparrows';
public static $required=array("Auth");
public static $hooks=array("foo");

function displayAdminPage(){
    global $params;
    switch($params[0]){
        case 'table':
            switch($params[1]){
                case 'edit':$this->displayTableEditor();break;
                case 'data':$this->displayTableDatabrowser();break;
                default:    $this->displayTableOverview();break;
            }
            break;
        case 'synch':$this->displaySynchOverview();break;
        default:     $this->displayDatabaseOverview();
    }
}

function displayDatabaseOverview(){
    
}

function displaySynchOverview(){
    
}

function displayTableOverview(){
    
}

function displayTableDatabrowser(){
    
}

function displayTableEditor(){
    
}

}
?>