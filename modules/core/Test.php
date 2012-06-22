<? class Test extends Module{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array("LightUp");
public static $hooks=array("foo");

function runTests(){
    global $c,$l,$k;
    $rss = $l->loadModule('ChanRSS');
    $rss->displayGlobal();
}

}
?>