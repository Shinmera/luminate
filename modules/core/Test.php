<? class Test extends Module{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array("LightUp");
public static $hooks=array("foo");

function runTests(){
    ?><style>
        .null{color:gray;}
        .error{color:red;}
        .data{color:green;}
        textarea{box-sizing: border-box;width:100%;min-height:150px;}
        #debug{font-family:monospace;margin-top:20px;border:2px solid red;padding:5px;}
        #output{margin-top:20px;border:2px solid green;padding:5px;}
    </style><form method="post">
        <textarea name="text"><?=$_POST['text']?></textarea><br />
        <input type="submit" />
    </form>
    <div id="debug"><h2>Debug:</h2><?
        global $lightup,$l;
        $args = array("text"=>$_POST['text'],"source"=>'Test',"formatted"=>true,"allowRaw"=>false,"blockedTags"=>array(),"suites"=>array('*','deftag'));
        $args = $lightup->deparse($args);
        $text = $args['text'];
    ?></div><div id="output"><h2>Output:</h2>
        <?=$text?>
    </div><?
}

function testDataModel(){
    global $ntest;$ntest=0;
    $this->runDataModelQuery(array());
    $this->runDataModelQuery(array("table"=>"ud_users","fields"=>array("username")));
    $this->runDataModelQuery(array("table"=>"ud_users","fields"=>array(array("field"=>"userID","function"=>"COUNT","as"=>"count"))));
    $this->runDataModelQuery(array("table"=>"ud_users",
                                   "fields"=>array("username"),
                                   "where"=>"?",
                                   "vars"=>array(
                                               array("group","%istered%","LIKE")
                             )));
    $this->runDataModelQuery(array("table"          =>"ud_users",
                                   "fields"         =>array("username",
                                                            array("field"=>"title","table"=>"derpy_messages")),
                                   "where"          =>"?",
                                   "vars"           =>array(array("status","a","LIKE")),
                                   "jointable"      =>"derpy_messages",
                                   "joinvars"       =>array("username","sender"),
                                   "joincomparison" =>"LIKE",
                                   "limit"          =>5,
                                   "order"          =>array(array("time","ASC","derpy_messages"),
                                                            "username")));
    //"users",array("username","relationship"),array("where"=>"?","vars"=>array(array("awesomeness",100))));
}

function runDataModelQuery($args){    
    global $c,$k,$ntest;$ntest++;
    echo('<br /><b>['.$ntest.']Running test:</b><br />');
    try{
        $time = $k->getMicrotime();
        $resp = DataModel::selectData($args);
        $time = $k->getMicrotime()-$time;
        
        echo('<b>Time:</b> '.round($time,4).'s<br />');
        echo('<b>Query:</b> '.$c->lastQuery.'<br />');
        if($resp == null){
            echo('<b>Return:</b> <span class="null">null</span><br />');
        }else if(!is_array($resp)){
            echo('<b>Return:</b> <span class="data"><br />'.print_r($resp,true).'</span><br />');
        }else{
            echo('<b>Return:</b> <span class="data"><br />');
            foreach($resp as $r){
                echo(print_r($r,true).'<br />');
            }
            echo('</span>');
        }
        return true;
    }catch(Exception $e){
        echo("<b>Error Message:</b> <span class='error'>".nl2br($e->getMessage())."</span><br />");
        //echo("<b>Strack Trace:</b> <br />".nl2br($e->getTraceAsString()).'<br />');
        return false;
    }
}
}
?>