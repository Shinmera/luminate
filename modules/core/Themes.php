<?
class Themes extends Module{
public static $name="Themes";
public static $version=1.5;
public static $short='t';
public static $required=array();

var $tname;
var $tauthor;
var $tversion;
var $tdescription;

var $img;
var $css = array();
var $js = array();
var $modules = array();
var $config;
var $headerf;
var $footerf;
var $menudata = array();

function __construct(){
    global $c,$api;
}

function loadTheme($themename,$override=true){
    if($override){
        $this->img="";$this->css=array();
        $this->js=array();$this->spaces=array();
        $this->modules=array();$this->config="";
        $this->headerf="";$this->footerf="";
    }
    if($themename=="")return;
    $contents = explode("\n",file_get_contents(ROOT.THEMEPATH.$themename."/".$themename.".conf"));
    for($i=0;$i<count($contents);$i++){
        $key = trim(substr($contents[$i],0,strpos($contents[$i],":")));
        $value=trim(substr($contents[$i],  strpos($contents[$i],":")+1));
        if($key=="name")$this->tname=$value;
        if($key=="version")$this->tversion=$value;
        if($key=="author")$this->tauthor=$value;
        if($key=="css")$this->css[]=$value;
        if($key=="js")$this->js[]=$value;
        if($key=="images")$this->img=THEMEPATH.$themename."/".$value;
        if($key=="description")$this->tdescription=$value;
        if($key=="header")$this->headerf=$value;
        if($key=="footer")$this->footerf=$value;
    }
}

function getThemes(){
    $themes = array();
    $dir = opendir(ROOT.THEMEPATH);
    while($file = readdir($dir)){
        if(substr($file,0,1)!="."){
            $themes[] = $file;
        }
    }
    return $themes;
}

function setMenu($data){
    if(!is_array($data))$data=explode(";",$data);
    for($i=0;$i<count($data);$i++){
        $key=substr($data[$i],0,strpos($data[$i],"|"));
        $val=substr($data[$i],strpos($data[$i],"|")+1);
        $data[$i]="<a href='".$val."'><li>".$key."</li></a>";
    }
    $this->menudata=$data;
}

function openPage($pagetitle){
    define("PAGETITLE",$pagetitle);
    if($this->tname=="")$this->loadTheme("default");
    include(PAGEPATH.'global_header.php');
    include(ROOT.THEMEPATH.$this->tname.'/'.$this->headerf);
}

function closePage(){
    include(ROOT.THEMEPATH.$this->tname.'/'.$this->footerf);
    include(PAGEPATH.'global_footer.php');
}

function printMenu($return=false){
    global $c;
    $temp = explode(";",$c->o['ms_category_order']);$ret="";
    for($i=0;$i<count($temp);$i++){
        if(substr($temp[$i],0,1)=="C"){ //category that isn't sub.
            $cid = array_search(substr($temp[$i],1),$c->msCID);
            if($c->msCPID[$cid]==-1&&$cid!==FALSE)$ret.=$this->printCategory(substr($temp[$i],1),true);
        }if(substr($temp[$i],0,1)=="P"){ //page
            $pid = array_search(substr($temp[$i],1),$c->msPID);
            if($c->msPCID[$pid]==-1&&$pid!==FALSE)$ret.=$this->printPage(substr($temp[$i],1),true);
        }
    }
    if($return)return $ret;else echo($ret);
}

function printCategory($id,$return=false){
    global $c;
    $cid = array_search($id,$c->msCID);$ret="";
    if(strpos($c->msCSubject[$cid],";")!==FALSE){
        $list = explode(";",$c->msCSubject[$cid]);
        $ret.='<li><a href="#" class="menulink">'.$c->msCTitle[$cid].'</a>';
        if(count($list)>0)$ret.='<ul>';
        for($i=0;$i<count($list);$i++){
            if(substr($list[$i],0,1)=="C")$ret.=$this->printCategory(substr($list[$i],1),true);
            if(substr($list[$i],0,1)=="P")$ret.=$this->printPage(    substr($list[$i],1),true);
        }
        if(count($list)>0)$ret.='</ul>';
    }else{
        $ret.='<li><a href="'.$c->msCSubject[$cid].'" class="menulink">'.$c->msCTitle[$cid].'</a>';
    }
    $ret.='</li>';
    if($return)return $ret;else echo($ret);
}

function printPage($id,$return=false){
    global $c;
    $pid = array_search($id,$c->msPID);$ret="";
    if(strpos($c->msPFilename[$pid],"/")!==FALSE)   $ret='<li><a href="'.$c->msPFilename[$pid].'" class="menulink">'.$c->msPTitle[$pid].'</a></li>';
    else                                            $ret='<li><a href="/'.$c->msPTitle[$pid].'" class="menulink">'.$c->msPTitle[$pid].'</a></li>';
    if($return)return $ret;else echo($ret);
}

function displayAdmin($params){
}
}
?>