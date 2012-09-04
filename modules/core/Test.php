<? class Test extends Module{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array("Themes");
public static $hooks=array("foo");

function runTests(){
    global $c,$l,$k,$t;

    $up = $l->loadModule("LightUpStream");
    

    ?><style>
        form textarea,form input,form label,#out label,#out>div{width:500px;display: inline-block;}
        form textarea{min-height:200px;}
        #out>div{min-height:200px;border: 1px solid black;}
    </style>
    <form action="#" method="post">
        <label>FEM Code Block</label>
        <label>Text Input to Parse</label><br />
        <textarea name="FEM"><?=$_POST['FEM']?></textarea>
        <textarea name="INP"><?=$_POST['INP']?></textarea><br />
        <input type="submit" value="Parse!" />
        <input type="submit" value="Parse!" />
    </form>
    <div id="out">
        <label>Compiled Code</label>
        <label>Resulting Formatted Text</label><br />
        <div id="fem-output"><pre><?=$CODE?></pre>&nbsp;</div>
        <div id="inp-output"><pre><?=$RESULT?></pre>&nbsp;</div>
    </div>
    <?
    
}
}
?>