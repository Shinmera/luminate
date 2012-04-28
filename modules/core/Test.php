<? class Test{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array();
public static $hooks=array("foo");

function runTests(){
    ?><style>
        .null{color:gray;}
        .error{color:red;}
        .data{color:green;}
    </style><?
    $this->testDataModel();
}

function testDataModel(){
    global $ntest;$ntest=0;
    $this->runDataModelQuery("",array(),null,array(),null,null,null,false);
    $this->runDataModelQuery("ud_users",array(),null,array(),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username"),null,array(),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),null,array(),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"?",array(array("username","Shinmera")),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"(?|?)",array(array("username","Shinmera"),array("username","Faggot")),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"?",array(array("username","Shinmera","LIKE")),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"?",array(array("username","Shinmera","LIKE",null)),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"?",array(array("username","Shinmera","LIKE",null,null)),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"?",array(array("username","Shinmera","LIKE",null,null,'LOWER')),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"?",array(array("username","Shinmera","LIKE",null,null,'LOWER','LOWER')),null,null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"?",array(array("username","Shinmera","LIKE",null,null,'LOWER','LOWER')),'1',null,null,false);
    $this->runDataModelQuery("ud_users",array("username","mail"),"?",array(array("username","Shinmera","LIKE",null,null,'LOWER','LOWER')),'1','fenfire_comments',array('username'),false);
    $this->runDataModelQuery("ud_users",array("username"=>'ud_users',"mail"=>'fenfire_comments'),"?",array(array("username","Shinmera","LIKE",null,null,'LOWER','LOWER')),'1','fenfire_comments',array('username'),false);
}

function runDataModelQuery($table,$fields,$whereString=null,$whereVars=array(),$limit=null,$innerJoinTable=null,$innerJoinVars=null,$loadFields=false){    
    global $c,$k,$ntest;$ntest++;
    echo('<br /><b>['.$ntest.']Running test:</b><br />');
    try{
        $time = $k->getMicrotime();
        $resp = DataModel::selectData($table, $fields, $whereString, $whereVars, $limit, $innerJoinTable, $innerJoinVars, $loadFields);
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