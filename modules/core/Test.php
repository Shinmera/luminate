<? class Test extends Module{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array("Themes");
public static $hooks=array("foo");

function runTests(){
    global $c,$l,$k,$t;

    $tables = $c->getData("SHOW TABLES");
    foreach($tables as $table){
        foreach($table as $b=>$t){
            echo("CONVERTING: ".$t."<br />");
            $c->query("ALTER TABLE " . $t . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci",array());
            //$cols = $c->getData("SHOW COLUMNS FROM ".$t);
            //foreach($cols as $col){
            //    echo("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONVERTING: ".$col['Field']."<br />");
            //    $c->query("ALTER TABLE " . $t . " CHANGE " . $col['Field'] . " CHARACTER SET utf8 COLLATE utf8_unicode_ci",array());
            //}
        }
    }
}
}?>