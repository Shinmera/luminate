<?
class Shortener extends Module{
    var $name="Shortener";
    var $version=2.1;
    var $short='s';
    var $required=array("Sqlloader","core/sqlloader.php");

    function getLong($short){
        global $c;
        $result=$c->getData("SELECT `long` FROM ms_urls WHERE `short`=?",array($short));
        return $result[0]['long'];
    }

    function getShort($long){
        global $c;
        $result=$c->getData("SELECT `short` FROM ms_urls WHERE `long`=?",array($long));
        return $result[0]['short'];
    }

    function getShortUrl($long){
        return "http://tymoon.eu/".$this->getShort($long);
    }

    function getShorts(){
        global $c,$k;
        $result=$c->getData("SELECT `short` FROM ms_urls",array());
        return $k->convertArrayDown($result,'short');
    }

    function getLongs(){
        global $c,$k;
        $result=$c->getData("SELECT `long` FROM ms_urls",array());
        return $k->convertArrayDown($result,'long');
    }

    function setShort($short,$url){
        global $c,$k;
        $c->query("INSERT INTO ms_urls VALUES(?,?)",array($short,$url));
                $k->log("Short ".$short." => ".$url." set.");
        return XERR_OK;
    }

    function delShort($short){
        global $c,$k;
        $c->query("DELETE FROM ms_urls WHERE `short`=?",array($short));
                $k->log("Short ".$short." deleted.");
        return XERR_OK;
    }

    function newShortUrl($url){
        global $k;
        if(substr($url,0,2)=="//")$url="http:".$url;
        $existing=$this->getShort($url);
        if($existing!="")return $existing;
        $shorts=$this->getShorts();
        $short=$k->generateRandomString();
        $n=8;
        for($i=0;in_array($short,$shorts);$i++){
            if($i>50)$n=9;
            if($i>150)$n=10;
            $short=$k->generateRandomString($n);
        }
        $this->setShort($short,$url);
        return $short;
    }

function displayPage($params){
    global $a,$c,$k,$p,$s,$t;
    switch($params[0]){
        case '':
            $t->openPage("Shortener");
            ?><form type="GET" action="/api.php">
            <input type="text" name="a" style="width:300px;">
            <input type="hidden" name="m" value="Shortener">
            <input type="hidden" name="c" value="addShort">
            <input type="submit"></form><?
            $t->closePage();
            break;
        default:header('Location: '.$this->getLong($params[0]));break;
    }
}

function displayAdmin($params){
    global $a;
    switch($params[0]){
    case 'manage':
        if(!$a->check('shortener.mod'));
        if($params[1]==""){
        $short=$this->getShorts();
        $long=$this->getLongs();
        ?>Add a new short:<br>
        <form action="/admin/Shortener/manage/add" method="post"><input type="text" name="varkey"> Short:<input type="text" name="varshort"><input type="submit"></form>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="10%">Short</td><td width="80%">Long</td><td width="10%">Actions</td></tr><?
        for($i=0;$i<count($short);$i++){
            ?><tr><td><?=$short[$i]?></td><td><a href='<?=$long[$i]?>'><?=$long[$i]?></a></td><td>
                        <form action="/admin/Shortener/manage/del" method="post"><input type="hidden" name="varkey" value="<?=$short[$i]?>"><input type="submit" value="Delete"></form></td></tr><?
        }
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'add':
                if($_POST['varshort'])$this->setShort($_POST['varshort'],$_POST['varkey']);
                else $this->newShortUrl($_POST['varkey']);
                echo("<center><span style='color:red'>Short URL Added.</span><br><a href='/admin/Shortener/manage'>Return</a></center>");
                break;
            case 'del':
                $this->delShort($_POST['varkey']);
                echo("<center><span style='color:red'>Short URL Deleted.</span><br><a href='/admin/Shortener/manage'>Return</a></center>");
                break;
            }
        }
        break;
    default:
        //$SectionList[""]         = "";
        if($a->check('shortener.mod'))$SectionList["manage"]        = "Manage|Manage the shortlinks";
        $SectionList["0"]             = "<--->";
        return $SectionList;
        break;
    }
}

function apiCall($func,$args,$security=""){
    global $c,$p,$k,$t;
    if($security!=$c->o['API_'.strtoupper($func).'_TOKEN'])return false;
    switch($func){
        case 'addShort':return $this->newShortUrl(urldecode(implode(":",$args)));break;
        case 'generateShort':return $this->generateShort($args['length']);break;
        case 'setShort':return $this->setShort($args['short'],$args['url']);break;
    }
}

function search($sections,$keywords="",$type="OR"){return array();}
}
?>
