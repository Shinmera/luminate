<? class Test extends Module{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array("Themes");
public static $hooks=array("foo");

function runTests(){
    global $c,$l,$k,$t;
    $t->openPage('A');
    
    Toolkit::interactiveList('B', array('Nope'), array('WELL!'), array('Nope'));
    $t->closePage();
}



}
?>