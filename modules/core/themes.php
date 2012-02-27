<?
class Themes{
var $id;

var $name;
var $author;
var $version;
var $description;

var $img;
var $css = array();
var $js = array();
var $spaces = array();
var $modules = array();
var $config;
var $headerf;
var $footerf;
var $menudata = array();

function __construct(){
    global $c,$api;
    $c->loadCategories($api->getID("Themes"));
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
        if($key=="name")$this->name=$value;
        if($key=="version")$this->version=$value;
        if($key=="author")$this->author=$value;
        if($key=="css")$this->css[]=$value;
        if($key=="js")$this->js[]=$value;
        if($key=="space")$this->spaces[]=$value;
        if($key=="images")$this->img=THEMEPATH.$themename."/".$value;
        if($key=="description")$this->description=$value;
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
    if($this->name=="")$this->loadTheme("default");
    include(ROOT.PROOT.'callables/global_header.php');
    include(ROOT.THEMEPATH.$this->name.'/'.$this->headerf);
}

function closePage(){
    global $c;
        $c->close();
        include(TROOT.'callables/global_footer.php');
    include(ROOT.THEMEPATH.$this->name.'/'.$this->footerf);
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


function displayPage($params){}

function displayAdmin($params){
    global $c,$k,$p,$a;
    switch($params[0]){
    case 'themes':
        if(!$a->check("themes.themes"))return;
        if($params[1]==""){
        ?><form action="/admin/Themes/themes/install" method="post" enctype="multipart/form-data"><input type="file" name="varkey"><input type="submit" value="Submit"></form>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="15%">Name</td><td width="45%">Description</td><td width="15%">Author</td><td width="15%">Version</td><td width="10%">Actions</td></tr><?
        $themes=$this->getThemes();
        $temp = new Themes();
        for($i=0;$i<count($themes);$i++){
            $temp->loadTheme($themes[$i]);
            if($temp->name==$c->o['default_theme'])$color="#FF0000";else $color="#0099FF";
            ?><tr bgcolor="<?=$color?>"><td><?=$temp->name?></td><td><?=$temp->description?></td><td><?=$temp->author?></td><td><?=$temp->version?></td><td>
                        <form action="/admin/Themes/themes/set" method="post"><input type="hidden" name="varkey" value="<?=$themes[$i]?>"><input type="submit" value="Default"></form>
                        <form action="/admin/Themes/themes/del" method="post"><input type="hidden" name="varkey" value="<?=$themes[$i]?>"><input type="submit" value="Delete"></form></td></tr><?
        }
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'del':
                if($_POST['sure']==""){
                    ?><center>Are you sure?<br />
                    <form action='/admin/Themes/themes/del' method='post'><input type="submit" name="sure" value="Yes"> <input type="submit" name="sure" value="No">
                    <input type='hidden' name="varkey" value="<?=$_POST['varkey']?>"></form></center><?
                }else if($_POST['sure']=="Yes"){
                    unlink(ROOT.THEMEPATH.$_POST['varkey']);
                                        $k->log("Theme ".$_POST['varkey']." deleted.");
                    echo("<center><span style='color:red'>Theme deleted.</span><br /><a href='/admin/Themes/themes'>Return</a></center>");
                }else{
                    echo("<center><a href='/admin/Themes/themes'>Return</a></center>");
                }
                break;
            case 'install':
                $e=$k->uploadFile("varkey",TROOT."temp",500,array("file/zip"));
                if($e==XERR_OK){
                    //FIXME: *UNZIP. SOMEHOW.*//
                    $file=file_get_contents(TROOT."temp/info.cfg");$file=explode(";",$file);
                    $info=array();
                    for($i=0;$i<count($file);$i++){
                        $key=trim(substr($file[$i],0,strpos($file[$i],":")));
                        $value=trim(substr($file[$i],strpos($file[$i],":"+1)));
                        $info[$key]=$value;
                    }
                                        $k->log("Theme ".$info['title']." installed.");
                    echo("<center><span style='color:red'>Theme Installed.</span><br /><a href='/admin/Themes/themes'>Return</a></center>");
                }else{
                    echo("<center><span style='color:red'>Failed. (E".$e.")</span><br /><a href='/admin/Themes/themes'>Return</a></center>");
                }
                break;
            case 'set':
                $c->query("UPDATE ms_settings SET `value`=? WHERE `key`='default_theme'",array($_POST['varkey']));
                                $k->log("Theme ".$_POST['varkey']." set as default.");
                echo("<center><span style='color:red'>Theme set as default.</span><br /><a href='/admin/Themes/themes'>Return</a></center>");
                break;
            }
        }

        break;
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'pages':
        if(!$a->check("themes.pages"))return;
        if($params[1]==""){
        ?>Add a new page:<br />
        <form action="/admin/Themes/pages/add" method="post"><input type="text" name="varkey"><input type="submit" value="Submit"></form>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="5%">ID</td><td width="15%">Category</td><td width="30%">Title</td><td width="40">Filename</td><td width="10%">Actions</td></tr><?
        for($i=0;$i<count($c->msPID);$i++){
            ?><tr bgcolor="#0099FF"><td><?=$c->msPID[$i]?></td><td><?=$c->msCTitle[array_search($c->msPCID[$i],$c->msCID)]?></td><td><?=$c->msPTitle[$i]?></td><td><?=$c->msPFilename[$i]?></td><td>
                        <form action="/admin/Themes/pages/edit" method="post"><input type="hidden" name="varkey" value="<?=$c->msPID[$i]?>"><input type="submit" value="Edit"></form>
                        <form action="/admin/Themes/pages/del" method="post"><input type="hidden" name="varkey" value="<?=$c->msPID[$i]?>"><input type="submit" value="Delete"></form></td></tr><?
        }
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'add':
                if($_POST['varcategory']==""){
                    ?><center><form action="/admin/Themes/pages/add" method="post">Add page '<?=$_POST['varkey']?>' to <select name="varcategory">
                    <option value="-1"> </option>
                    <? for($i=0;$i<count($c->msCID);$i++){
                        echo("<option value='".$c->msCID[$i]."'>".$c->msCTitle[$i]."</option>");
                    } ?></select>
                    with link <input type="text" name="varfilename"><input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                    <input type="submit" value="Submit">
                    </form></center><?
                }else{
                    $c->query("INSERT INTO ms_pages VALUES(NULL,?,?,?)",array($_POST['varcategory'],$p->enparse($_POST['varkey']),$_POST['varfilename']));
                    $result=$c->getData("SELECT pageID FROM ms_pages ORDER BY pageID DESC LIMIT 1;");
                    $c->query("UPDATE ms_categories SET subject = CONCAT(subject,?) WHERE categoryID=?",array(";P".$result[0]['pageID'],$_POST['varcategory']));
                    echo("<center><span style='color:red'>Page added.</span><br /><a href='/admin/Themes/pages'>Return</a></center>");
                                        $k->log("Page ".$_POST['varkey']." added.");
                }
                break;

            case 'del':
                $temp = $c->msPCID[array_search($_POST['varkey'],$c->msPID)];
                $c->query("DELETE FROM ms_pages WHERE pageID=?",array($_POST['varkey']));
                $c->query("UPDATE ms_categories SET subject=? WHERE categoryID=?",array($k->removeFromList("P".$_POST['varkey'],$c->msCSubject[array_search($temp,$c->msCID)]),$temp));
                echo("<center><span style='color:red'>Page deleted.</span><br /><a href='/admin/Themes/pages'>Return</a></center>");
                                $k->log("Page ID".$_POST['varkey']." deleted.");
                break;

            case 'edit':
                if($_POST['varcategory']==""){
                    $temp = array_search($_POST['varkey'],$c->msPID);
                    ?><center><form action="/admin/Themes/pages/edit" method="post">Set page <input type="text" name="varname" value="<?=$c->msPTitle[$temp]?>"> in <select name="varcategory">
                    <option value="-1"> </option>
                    <? for($i=0;$i<count($c->msCID);$i++){
                        if($c->msCID[$i]==$c->msPCID[$temp])$sele = "selected"; else $sele = "";
                        echo("<option value='".$c->msCID[$i]."' ".$sele." >".$c->msCTitle[$i]."</option>");
                    } ?></select>
                    to link <input type="text" name="varfilename" value="<?=$c->msPFilename[$temp]?>"><input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                    <input type="submit" value="Submit">
                    </form></center><?
                }else{
                    $c->query("UPDATE ms_pages SET CID=?, title=?, filename=? WHERE pageID=?",array($_POST['varcategory'],$p->enparse($_POST['varname']),$_POST['varfilename'],$_POST['varkey']));
                    echo("<center><span style='color:red'>Page edited.</span><br /><a href='/admin/Themes/pages'>Return</a></center>");
                                        $k->log("Page ".$_POST['varname']." edited.");
                }
                break;
            }
        }

        break;
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'pagecats':
        if(!$a->check("themes.pagecats"))return;
        if($params[1]==""){
        ?>Add a new category:<br />
        <form action="/admin/Themes/pagecats/add" method="post"><input type="text" name="varkey"><input type="submit" value="Submit"></form>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="15%">Title</td><td width="15%">Parent</td><td width="60">Items/Link</td><td width="10%">Actions</td></tr><?
        for($i=0;$i<count($c->msCID);$i++){
                    if($c->msCMID[$i]==$this->id){
            //prepare items
            if(strpos($c->msCSubject[$i],";")!==FALSE){
                $temp = explode(";",$c->msCSubject[$i]);$items = array();
                for($j=0;$j<count($temp);$j++){
                    if(substr($temp[$j],0,1)=="P")$items[]=$c->msPTitle[array_search(substr($temp[$j],1),$c->msPID)];
                    if(substr($temp[$j],0,1)=="C")$items[]=$c->msCTitle[array_search(substr($temp[$j],1),$c->msCID)];
                }
                $items = implode("<br />",$items);
            }else{
                $items=$c->msCSubject[$i];
            }
            ?><tr bgcolor="#0099FF"><td><?=$c->msCTitle[$i]?></td><td><?=$c->msCTitle[array_search($c->msCPID[$i],$c->msCID)]?></td><td><?=$items?></td><td>
                        <form action="/admin/Themes/pagecats/edit" method="post"><input type="hidden" name="varkey" value="<?=$c->msCID[$i]?>"><input type="submit" value="Edit"></form>
                        <form action="/admin/Themes/pagecats/del" method="post"><input type="hidden" name="varkey" value="<?=$c->msCID[$i]?>"><input type="submit" value="Delete"></form></td></tr><?
                    }
                }
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'add':
                if($_POST['varparent']==""){
                    ?><center><form action="/admin/Themes/pagecats/add" method="post">Add category "<?=$_POST['varkey']?>" to
                    <select name="varparent"><option value="-1" ></option>
                    <? for($i=0;$i<count($c->msCID);$i++){echo("<option value='".$c->msCID[$i]."'>".$c->msCTitle[$i]."</option>");} ?>
                    </select>
                    <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                    <input type="submit" value="Submit">
                    </form></center><?
                }else{
                    $c->query("INSERT INTO ms_categories VALUES(NULL,?,?,?,?,?)",array($this->id,$_POST['varparent'],$_POST['varkey'],'',''));
                    $result=$c->getData("SELECT categoryID FROM ms_categories ORDER BY categoryID DESC LIMIT 1;");
                    if($_POST['varparent']==-1)
                        $c->query("UPDATE ms_options SET `value`= CONCAT(`value`,?) WHERE `key`='ms_category_order'",array(";C".$result[0]['categoryID']));
                    else
                        $c->query("UPDATE ms_categories SET `subject`= CONCAT(`subject`,?) WHERE `categoryID`=?",array(";C".$result[0]['categoryID'],$_POST['varparent']));
                    echo("<center><span style='color:red'>Category added.</span><br /><a href='/admin/Themes/pagecats'>Return</a></center>");
                                        $k->log("Pagecategory ".$_POST['varkey'].' added.');
                }
                break;

            case 'del':
                $c->query("DELETE FROM ms_categories WHERE categoryID=?",array($_POST['varkey']));
                if($c->msCPID[array_search($_POST['varkey'],$c->msCID)]==-1)
                    $c->query("UPDATE ms_options SET `value`=? WHERE `key`='ms_category_order';",array($k->removeFromList("C".$_POST['varkey'],$c->o['ms_category_order'])));
                else{
                    $temp = array_search($c->msCPID[array_search($_POST['varkey'],$c->msCID)],$c->msCID);
                    $c->query("UPDATE ms_categories SET `subject`=? WHERE categoryID=?",array($k->removeFromList("C".$_POST['varkey'],$c->msCSubject[$temp]),$c->msCID[$temp]));
                }echo("<center><span style='color:red'>Category deleted.</span><br /><a href='/admin/Themes/pagecats'>Return</a></center>");
                                $k->log("Pagecategory ID".$_POST['varkey'].' deleted.');
                break;

            case 'edit':
                if($_POST['varname']==""){
                    $temp = array_search($_POST['varkey'],$c->msCID);
                    ?><center><form action="/admin/Themes/pagecats/edit" method="post">
                    <input type="text" name="varname" value="<?=$c->msCTitle[$temp]?>">
                    <select name="varparent">
                    <? if($c->msCPID[$temp]==-1)echo('<option value="-1" selected></option>');else echo('<option value="-1" ></option>');
                    for($i=0;$i<count($c->msCID);$i++){
                        if($c->msCPID[$temp]==$c->msCID[$i])$sele="selected";else $sele="";
                        echo("<option ".$sele." value='".$c->msCID[$i]."'>".$c->msCTitle[$i]."</option>");
                    }
                    ?>
                    </select><br />
                    <input type="text" name="varsubject" size="30px" value="<?=$c->msCSubject[$temp]?>">
                    <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                    <input type="submit" >
                    </form></center><?
                }else{
                    $c->query("UPDATE ms_categories SET subject=?, title=?, PID=? WHERE categoryID=?",array($_POST['varsubject'],$_POST['varname'],$_POST['varparent'],$_POST['varkey']));
                    //Remove from old list
                    if($c->msCPID[array_search($_POST['varkey'],$c->msCID)]==-1)
                        $c->query("UPDATE ms_options SET `value`=? WHERE `key`='ms_category_order'",array($k->removeFromList("C".$_POST['varkey'],$c->o['ms_category_order'])));
                    else{
                        $temp = array_search($c->msCPID[array_search($_POST['varkey'],$c->msCID)],$c->msCID);
                        $c->query("UPDATE ms_categories SET `subject`=? WHERE categoryID=?",array($k->removeFromList("C".$_POST['varkey'],$c->msCSubject[$temp]),$c->msCID[$temp]));
                    }
                    //Add To new list
                    if($_POST['varparent']==-1)
                        $c->query("UPDATE ms_options SET `value`= CONCAT(`value`,?) WHERE `key`='ms_category_order'",array(";C".$_POST['varkey']));
                    else
                        $c->query("UPDATE ms_categories SET `subject`= CONCAT(`subject`,?) WHERE `categoryID`=?",array(";C".$_POST['varkey'],$_POST['varparent']));
                    echo("<center><span style='color:red'>Category edited.</span><br /><a href='/admin/Themes/pagecats'>Return</a></center>");
                                        $k->log("Pagecategory ".$_POST['varname'].' edited.');
                }
                break;
            }
        }

        break;
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'headers':
        if(!$a->check("themes.headers"))return;
        if($params[1]==""){
        ?>Add a new header:<br />
        <form action="/admin/Themes/headers/add" enctype="multipart/form-data" method="post"><input type="text" name="varkey"><input type="file" name="varfile"><input type="submit" value="Submit"></form>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="15%">Title</td><td width="80%">Image</td><td width="5%">Actions</td></tr><?
        for($i=0;$i<count($c->msHTitle);$i++){
            ?><tr bgcolor="#0099FF"><td><?=$c->msHTitle[$i]?></td><td><? $k->displayImageSized(HEADERPATH.$c->msHFile[$i],750); ?></td><td>
                        <form action="/admin/Themes/headers/del" method="post"><input type="hidden" name="varkey" value="<?=$c->msHFile[$i]?>"><input type="submit" value="Delete"></form></td></tr><?
        }
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'add':
                $ret = $k->uploadFile("varfile",ROOT.HEADERPATH);
                if($ret == XERR_OK){
                    $c->query("INSERT INTO ms_headers VALUES(?,?)",array($_POST['varkey'],$k->sanitizeFilename($_FILES['varfile']['name'])));
                    echo("<center><span style='color:red'>Header added.</span><br /><a href='/admin/Themes/headers'>Return</a></center>");
                                        $k->log("Header ".$_POST['varkey'].' added.');
                }else{
                    echo("<center><span style='color:red'>UPLOAD FAILED!<br />(E".$ret.")</span><br /><a href='/admin/Themes/headers'>Return</a></center>");
                }
                break;

            case 'del':
                unlink(ROOT.HEADERPATH.$_POST['varkey']);
                $c->query("DELETE FROM ms_headers WHERE filename=?",array($_POST['varkey']));
                echo("<center><span style='color:red'>Header deleted.</span><br /><a href='/admin/Themes/headers'>Return</a></center>");
                                $k->log("Header ".$_POST['varkey']." deleted.");
                break;
            }
        }
        break;

    default:
        //$SectionList[""]         = "";
        if($a->check('themes.themes'))$SectionList["themes"]        = "Themes|Add or Remove site themes";
        if($a->check('themes.headers'))$SectionList["headers"]     = "Headers|Add or remove rotating header images";
        $SectionList["0"]             = "<--->";
        if($a->check('themes.pages'))$SectionList["pages"]         = "Pages|Manage the available pages";
        if($a->check('themes.pagecats'))$SectionList["pagecats"]    = "Categories|Edit the menu categories";
        $SectionList["1"]             = "<--->";
        return $SectionList;
        break;
    }
}

function apiCall($func,$args,$security=""){
    global $c;
    if($security!=$c->o['API_'.strtoupper($func).'_TOKEN'])return;

    switch($func){
    case 'deluser':break;
    case 'getBBCodeBar':
        $c->loadBBCode();
        if($args['level']==""||!is_numeric($args['level']))$args['level']=99;
        $s.='<div class="bbcodeBar">';
        for($i=0;$i<count($c->msBTitle);$i++){
            if(file_exists(ROOT.IMAGEPATH.'icons/'.$c->msBTitle[$i].'.png')&&$c->msBAdmin[$i]<=$args['level']){
                $raw=explode("|",$c->msBRaw[$i]);
                $s.="<a href=\"javascript:insert('".$args['formid']."','".$raw[0]."','".$raw[1]."');\">
                    <img class='bbcodeIcon' src='http://".HOST.IMAGEPATH.'icons/'.$c->msBTitle[$i].".png' title='".$c->msBTitle[$i]."' alt='".$raw[0]."'></a>";
            }
        }
        $s.='</div>';
        if($args['return'])return $s;
        else echo($s);
        break;
    }
}

function search($sections,$keywords="",$type="OR"){return array();}
}
?>