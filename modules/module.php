<? class Module{
public static $name="Module";
public static $author="NexT";
public static $version=0.01;
public static $short='';
public static $required=array("Foo");
public static $hooks=array("foo");

function __construct(){}
public static function getAttribs(){
    return array('name'=>$this::$name,'version'=>$this::$version,'short'=>$this::$short,'required'=>$this::$required);
}
}
?>