<?
class Themes extends Module{
public static $name="Themes";
public static $version=1.5;
public static $short='t';
public static $required=array("Auth");
public static $hooks=array("foo");

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

function __construct(){}

//TODO: Add template system.
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

function printMenu($menu=null){
    /**
     * Sample menu format:
     * Pseudo: [ [ A, link, (style) ], [ B, link, (style) ], [ C, link, (style), [ CA, link, (style) ]]]
     * PHP:    array(array('A','/A'),array('B','/B'),array('C','/C','',array('CA','/CA')))
     * Produces:
     * V        [   A   ] [   B   ] [   C   ]
     * H                            [  CA   ]
     */
    
    if(!is_array($menu)){
        global $l,$k;
        $menu=array();
        $menu[]=array('Index',NODOMAIN);
        $menu = $l->triggerHookSequentially("buildMenu",$this,$menu);
    }
    
    echo('<ul>');
    foreach($menu as $item){
        echo('<li style="'.$item[2].'"><a href="'.$item[1].'">'.$item[0].'</a>');
            if(count($item)==4)$this->printMenu($item[3]);
        echo('</li>');
    }
    echo('</ul>');
}

function displayPanel(){
    global $k,$a;
    ?><div class="box">
        <div class="title">Themes</div>
        <ul class="menu">
            <? if($a->check("themes.admin")){ ?>
            <a href="<?=$k->url("admin","Themes")?>"><li>Manage Themes</li></a><? } ?>
            <? if($a->check("themes.admin.edit")){ ?>
            <a href="<?=$k->url("admin","Themes/edit")?>"><li>Edit Theme Files</li></a><? } ?>
        </ul>
    </div><?
}

function adminNavbar($menu){$menu[]='Themes';return $menu;}
function displayAdminPage(){
    global $params,$a;
    switch($params[1]){
        case 'edit':if($a->check("themes.admin.edit"))$this->displayEditPage();break;
        default:    if($a->check("themes.admin"))$this->displayThemesPage();break;
    }
}

function displayThemesPage(){
    global $k;
    if($_POST['action']=="Install"){
        try{
            $k->uploadFile("archive",TEMPPATH,5000,array("application/zip","application/x-zip","application/x-zip-compressed",
                                                            "application/octet-stream","application/x-compress",
                                                            "application/x-compressed","multipart/x-zip"),true,"package.zip");
            $this->installTheme();
        }catch(Exception $e){
            $k->err("Error Code: ".$e->getCode()."<br>Error Message: ".$e->getMessage()."<br>Strack Trace: <br>".$e->getTraceAsString());
        }
    }
    ?><form class="box" action="#" method="post">
        Install from package:<br />
        <input type="file" name="archive" accept="application/zip,application/x-zip,application/x-zip-compressed,
                                                  application/octet-stream,application/x-compress,
                                                  application/x-compressed,multipart/x-zip" />
        <input type="submit" name="action" value="Install" />
    </form>

    <div class="box" style="display:block;"><?
        $dh = opendir(ROOT.THEMEPATH);
        $origtheme = $this->tname;
        while(($file = readdir($dh)) !== false){
            if($file!="."&&$file!=".."&&is_dir(ROOT.THEMEPATH.$file)){
                $this->loadTheme($file);
                ?><div class="datarow">
                    <b><?=$this->tname?></b> v<?=$this->tversion?> by <?=$this->tauthor?><br />
                    <blockquote>
                        <?=$this->tdescription?>
                    </blockquote>
                    Edit: 
                    <form action="<?=PROOT?>Themes/edit" method="post" style="display:inline-block;">
                        <input type="submit" name="file" value="<?=$this->headerf?>" />
                    <input type="hidden" name="theme" value="<?=$file?>" /></form>
                    <form action="<?=PROOT?>Themes/edit" method="post" style="display:inline-block;">
                        <input type="submit" name="file" value="<?=$this->footerf?>" />
                    <input type="hidden" name="theme" value="<?=$file?>" /></form>
                    <? foreach($this->js as $js){ ?>
                        <form action="<?=PROOT?>Themes/edit" method="post" style="display:inline-block;">
                            <input type="submit" name="file" value="<?=$js?>" />
                        <input type="hidden" name="theme" value="<?=$file?>" /></form>
                    <? } ?>
                    <? foreach($this->css as $css){ ?>
                        <form action="<?=PROOT?>Themes/edit" method="post" style="display:inline-block;">
                            <input type="submit" name="file" value="<?=$css?>" />
                        <input type="hidden" name="theme" value="<?=$file?>" /></form>
                    <? } ?>
                </div><?
            }
        }
        
    ?></div><?
    $this->loadTheme($origtheme);
}

function displayEditPage(){
    global $l,$k;
    if($_POST['file']=='')$_POST['file']=$_GET['file'];
    if($_POST['theme']=='')$_POST['theme']=$_GET['theme'];
    
    ?><form id="editorForm" action="<?=$k->url("api","Themes/savepage");?>" method="post">
        <input tpye="hidden" name="theme" value="<?=$_POST['theme']?>" />
        <input type="text" name="file" value="<?=$_POST['file']?>" /><br /><?

        $ace = $l->loadModule("Ace");
        $filetype=substr($_POST['file'],strrpos($_POST['file'], ".")+1);
        if($filetype=="js")$filetype="javascript"; //All others match file ending and type already.
        $ace->getAceEditor("source",$filetype,
            file_get_contents(ROOT.THEMEPATH.$_POST['theme'].'/'.$_POST['file']),"width:100%;height:500px;");

        ?>
        <input type="submit" id="submitter" value="Save To Disc" />
        <span id="result" style="color:red;"><?=$_GET['result']?></span>
    </form>
    <script type="text/javascript">
        $("#submitter").click(function(){
            transferToTextarea();
            return true;
            /*$.post("<?=$k->url("api","Themes/savepage");?>", $("#editorForm").serialize(),function(data) {
                $("#result").html(data);
            });*/
        });
    </script><?
}

function displayAPI(){
    global $params;
    switch($params[1]){
        case 'savepage':$this->displayAPISavePage();break;
        case 'preview':$this->displayAPIPreviewPage();break;
    }
}

function displayAPISavePage(){
    global $a;
    if(!$a->check("themes.admin.edit"))die("No permissions.");
    if($_POST['source']!=''){
        if(!file_put_contents(ROOT.THEMEPATH.$_POST['theme'].'/'.$_POST['file'],$_POST['source']))
            header('Location: '.$_SERVER['HTTP_REFERER'].'?file='.$_POST['file'].'&theme='.$_POST['theme'].'&result=Failed%20to%20save%20the%20file!');
        else
            header('Location: '.$_SERVER['HTTP_REFERER'].'?file='.$_POST['file'].'&theme='.$_POST['theme'].'&result=Saved!');
    }else{
        header('Location: '.$_SERVER['HTTP_REFERER'].'?file='.$_POST['file'].'&theme='.$_POST['theme'].'&result=No%20source%20received.');
    }
}

function displayAPIPreviewPage(){
    if($_POST['theme']=='')$_POST['theme']=$_GET['theme'];
    if($_POST['theme']=='')$_POST['theme']='default';
    
    $this->loadTheme($_POST['theme']);
    $this->openPage("Themepreview");
    
    $this->closePage();
}

//TODO: Test
function installTheme(){
    global $k,$c,$l;
    $k->pf('<div class="box"><b>Starting installation...</b><br />');
    $k->pf('Extracting archive...');
    mkdir(TEMPPATH.'theme/');
    if($k->unzipFile(TEMPPATH.'package.zip',TEMPPATH.'theme/')){
        $k->pf('Reading configuration...');
        $config = $k->toKeyArray(file_get_contents(TEMPPATH.'module/install.conf'),"\n",":");
        if($config!=FALSE&&$config['name']!=''){
            $k->pf('Moving files...');
            if(rename(TEMPPATH.'theme/',ROOT.THEMEPATH.$config['name'].'/')){
                $k->pf('<b>Installation complete!</b>');
                $l->triggerHook("THEMEinstalled",$this,array($config['name']));
                $k->log("Module '".$config['name']."' installed.");
            }else $k->pf('<b>Failed to move the files!</b>');
        }else $k->pf('<b>Failed to read the configuration file!</b>');
    }else $k->pf('<b>Failed to extract the archive!</b>');
    $k->pf('</div>');
}

}
?>