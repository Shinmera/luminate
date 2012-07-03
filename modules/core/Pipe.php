<? class Pipe extends Module{
public static $name="Pipe";
public static $author="NexT";
public static $version=0.01;
public static $short='pipe';
public static $required=array();
public static $hooks=array();

private $types = array('XML','JSON','BLOCK','LISP');

function parseDataModel($datamodel,$type="XML"){
    if(!$this->isTypeSupported($type))throw new Exception('Requested format type is not supported!');
    return call_user_func(array(&$this,'parseTo'.strtoupper($type)), $datamodel);
}

function isTypeSupported($type){
    return in_array(strtoupper($type),$this->types);
}

function parseToXML($data){
    $res='';
    foreach($data as $dat){
        $res.='<row table="'.$dat->getTable().'">';
        
        $fields = $dat->getHolder();
        foreach($fields as $key => $value){
            $res='<field name="'.$key.'" value="'.$value.'" />';
        }
        
        $res.='</row>';
    }
    return $res;
}

function parseToJSON($data){
    $rows = array();
    foreach($data as $dat){
        $inner = array('"table":"'.$dat->getTable().'"');
        
        $fields = $dat->getHolder();
        foreach($fields as $key => $value){
            if(is_numeric($value))  $inner[]='"'.$key.'": '.$value;
            else                    $inner[]='"'.$key.'":"'.$value.'"';
        }
        
        $rows[]='{'.implode(',',$inner).'}';
    }
    return '['.implode(',',$rows).']';
}

function parseToBLOCK($data){
    $res='';
    foreach($data as $dat){
        $res.=$dat->getTable()."{\n";
        
        $fields = $dat->getHolder();
        foreach($fields as $key => $value){
            $res=$key.':'.$value."\n";
        }
        
        $res.="}\n";
    }
    return $res;
}

function parseToLISP($data){
    $rows = array();
    foreach($data as $dat){
        $inner = array('(:table '.$dat->getTable().')');
        
        $fields = $dat->getHolder();
        foreach($fields as $key => $value){
            if(is_numeric($value))  $inner[]='(:'.$key.' '.$value.')';
            else                    $inner[]='(:'.$key.' "'.$value.'")';
        }
        
        $rows[]='('.implode(' ',$inner).')';
    }
    return '('.implode(' ',$rows).')';
}


}
?>