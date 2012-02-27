<? class Module{
var $name="Module";
var $version=0.01;
var $short='';
var $required=array("foo"=>"foo.php");

function __construct(){}

function displayPage($params){
    global $c,$k;
}

function displayAdmin($params){
    global $c,$k;
    switch($params[0]){
    default:
        //$SectionList[""]         = "";
        $SectionList["0"]         = "<--->";
        return $SectionList;
        break;
    }
}

function apiCall($func,$args,$security=""){
    global $c,$k;
    if($security!=$c->o['API_'.strtoupper($func).'_TOKEN'])return;

    switch($func){
    }
}

function search($sections,$keywords="",$type="OR"){
    if($sections=="list"){
        return array();
    }

    $return=array();
    for($i=0;$i<count($titles);$i++){
        //$return[]=array("title"=>$titles[$i],"subject"=>$subjects[$i],"link"=>$links[$i],"owner"=>$owners[$i]);
    }
    return $return;
}
}
?>