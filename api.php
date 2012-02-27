<?
$modu=$_GET['m'];//module
$call=$_GET['c'];//call
$args=$_GET['a'];//arguments
$secu=$_GET['s'];//security

if($call=="time"){die("".time());}

if($modu!=""&&$call!=""&&!defined(INIT)){
    include('/var/www/config.php');
    global $c,$k;
    //parse args
    $temp=explode(";",$args);$args=array();
    for($i=0;$i<count($temp);$i++)
        $args[substr($temp[$i],0,strpos($temp[$i],":"))]=substr($temp[$i],strpos($temp[$i],":")+1);
    //load
    $API = new API();
    $ret = $API->call($modu,$call,$args,$secu);
    if(!is_string($ret)){
        if($_GET['r']=="true")header("Location: ".$_SERVER['HTTP_REFERER']);
        else if(isset($_GET['r']))header("Location: ".$_GET['r']);
        else if($ret===true)echo(1);
        else if($ret===false)echo(0);
        else if(is_numeric($ret))echo("E".$ret.": ".$k->errorCodeToString($ret));
    }else{
        echo($ret);
        if($_GET['r']=="true")header("<script type='text/javascript'>setTimeout(\"window.location='".$_SERVER['HTTP_REFERER']."'\", 5000);</script>");
        else if(isset($_GET['r']))header("<script type='text/javascript'>setTimeout(\"window.location='".$_GET['r']."'\", 5000);</script>");
    }
}

class API{
    function call($modu,$call,$args=array(),$secu=""){
        global $c,$k;
        if($call=="verify"){
            $temp=array();
            foreach($args as $a=>$b){
                $temp[]=" - ".$a.": ".$b;
            }
            return "<b>API Call on ".date("m.d.Y H:i:s").".</b><br />
                        -----------------------------------<br />
                        <b>Call:</b> ".$call."<br />
                        <b>Module:</b> ".$modu."<br />
                        <b>Args:</b> <br />".implode("<br />",$temp)."<br />
                        <b>Secu:</b> ".$secu;
        }else{
            $mod=$this->getInstance($modu);
            if(is_numeric($mod)||$mod==""||!is_object($mod))return $mod;
            return $mod->apiCall($call,$args,$secu);
        }
    }
    function getInstance($modu){
        global $c,$k;
        if(is_numeric($modu))$mod=$c->getData("SELECT moduleID,title,index FROM ms_modules WHERE moduleID=? AND activated=1 LIMIT 1",array($modu));
        else                 $mod=$c->getData("SELECT moduleID,title,index FROM ms_modules WHERE title LIKE ? AND activated=1 LIMIT 1",array($modu));
        if(count($mod)==0)throw new Exception("No such Module '".$modu."'! Invalid API call.");
        
        include(TROOT."loader.php");
        $m = Loader::loadModule($mod[0]['index'],$mod[0]['title'],$mod[0]['moduleID']);
        return $m;
    }
    function exists($modu){
        global $c;
        if(is_numeric($modu))$mod=$c->getData("SELECT moduleID FROM ms_modules WHERE moduleID=? AND activated=1 LIMIT 1",array($modu));
        else                 $mod=$c->getData("SELECT moduleID FROM ms_modules WHERE title LIKE ? AND activated=1 LIMIT 1",array($modu));
        return (count($mod)==1);
    }
    function getID($modu){
        global $c;
        if(is_numeric($modu))$mod=$c->getData("SELECT moduleID FROM ms_modules WHERE moduleID=? AND activated=1 LIMIT 1",array($modu));
        else                 $mod=$c->getData("SELECT moduleID FROM ms_modules WHERE title LIKE ? AND activated=1 LIMIT 1",array($modu));
        if(count($mod)==0)return -1;else return $mod[0]['moduleID'];
    }
}
?>