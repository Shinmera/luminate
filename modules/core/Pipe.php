<? class Pipe extends Module{
public static $name="Pipe";
public static $author="NexT";
public static $version=0.01;
public static $short='pipe';
public static $required=array();
public static $hooks=array();

private $types = array('xml','json','block');

function parseDataModel($datamodel,$type){
    if(!$this->isTypeSupported($type))throw new Exception('Requested format type is not supported!');
    return call_user_func(array(&$this,'parseTo'.strtoupper($type)), $datamodel);
}

function isTypeSupported($type){
    return in_array($type,$this->types);
}

function parseToXML($data){
    
}

function parseToJSON($data){
    
}

function parseToBLOCK($data){
    
}


}
?>