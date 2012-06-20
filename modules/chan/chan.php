<?
function chan_linkifyTablePostID($cell,$i,$j,$table){
    return '<a href="'.PROOT.'chan/byID/'.$table[$i][$j+1].'/'.$cell.'">'.$cell.'</a>';
}
function chan_imageifyTablePostFile($cell,$i,$j,$table){
    global $c;$folder=$c->getData("SELECT folder FROM ch_boards WHERE boardID=?",array($table[$i][1]));$folder=$folder[0]['folder'];
    return '<a href="'.DATAPATH.'chan/'.$folder.'/files/'.$cell.'">'.
           '<img src="'.DATAPATH.'chan/'.$folder.'/thumbs/'.$cell.'" />'.'</a>';
}

//TODO: Sanitize for v4.
//TODO: Reduce bloat.
//TODO: Re-test everything.
//TODO: Tune akismet for unknown IPs.

class Chan{
var $id;
public static $name="Chan";
public static $author="NexT";
public static $version=2.01;
public static $short='chan';
public static $required=array("Auth");
public static $hooks=array("foo");

function displayPage($params){
    global $a,$t,$c,$p,$k;
    $t->loadTheme("chan");
    switch(trim($params[0])){
    case 'byID':
        $board = $c->getData("SELECT folder FROM ch_boards WHERE boardID=?",array($params[1]));
        $thread = $c->getData("SELECT PID FROM ch_posts WHERE postID=?",array($params[2]));
        if(count($board)==0||count($thread)==0)die();
        if($thread[0]['PID']==0)$thread[0]['PID']=$params[2];
        header('Location: '.$k->url("chan",$board[0]['folder'].'/threads/'.$thread[0]['PID'].'.php'));
        break;
    case '':
        include(MODULEPATH.'chan/frontpage.php');
        break;
    default:
        $para=implode("/",$params);
        $para=str_ireplace("/thread/","/threads/",$para);
        $para=str_ireplace("/post/","/posts/",$para);
        if(is_dir(ROOT.DATAPATH.'chan/'.$para)&&file_exists(ROOT.DATAPATH.'chan/'.$para.'/index.php'))
            include(ROOT.DATAPATH.'chan/'.$para.'/index.php');
        else if(is_numeric(end($params)))
            include(ROOT.DATAPATH.'chan/'.$para.'.php');
        else if(file_exists(ROOT.DATAPATH.'chan/'.$para)&&!is_dir(ROOT.DATAPATH.'chan/'.$para))
            include(ROOT.DATAPATH.'chan/'.$para);
        else{
            header('HTTP/1.0 404 Not Found');
            $t->openPage("404! D:");
            echo(E404);
            $t->closePage();
        }
        break;
    }
}

function displayAdmin($params){
    global $a,$c,$k,$p,$s,$t;
    if(!class_exists("ChanDataPost"))include(TROOT.'modules/chan/data.php');
    if(!class_exists("BoardGenerator"))include(TROOT.'modules/chan/boardgen.php');
    switch($params[0]){
    case 'categories':
        if(!$a->check('chan.admin.categories'))return;
        $c->loadCategories($this->id);
        if($params[1]==""){ 
            ?><form action="/admin/Chan/categories/add" method="post"><input type="text" name="varkey"><input type="submit" value="Submit"></form><?
            $table = new SettingsTable("categoriesTable", new TableData(array("ID","title","Board Order"),$c->msCID,$c->msCTitle,$c->msCSubject),array("edit","delete"),"/admin/Chan/categories/");
            $table->printTable();
        }else{
            switch($params[1]){
            case 'add':
                if($_POST['varsubject']==""){
                    $boards = $c->getData("SELECT boardID,folder FROM ch_boards", array());
                    ?><form action="#" method="POST">
                        Title: <input type="text" name="varkey" value="<?=$_POST['varkey']?>"/><br />
                        Board Order: <? $k->interactiveList("varsubject",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID")); ?>
                        <input type="submit" value="Submit">
                    </form><?
                }else{
                    $c->query("INSERT INTO ms_categories VALUES(NULL,?,?,?,?,?)",array($this->id,'',$_POST['varkey'],$_POST['varboards'],''));
                    echo("<center><span style='color:red'>Category added.</span><br /><a href='/admin/Chan/categories'>Return</a></center>");
                }
                break;
            case 'edit':
                if($_POST['vartitle']==""){
                    $cID=array_search($_POST['varkey'],$c->msCID);
                    $boards = $c->getData("SELECT boardID,folder FROM ch_boards", array());
                    ?><center><form action="#" method="POST">
                        Title: <br /><input type="text" name="vartitle" value="<?=$c->msCTitle[$cID]?>"/><br />
                        Board Order: <? $k->interactiveList("varsubject",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID"),explode(";",$c->msCSubject[$cID])); ?>
                        <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>"/>
                        <input type="submit" value="Submit">
                    </form></center><?
                }else{
                    $c->query("UPDATE ms_categories SET subject=?, title=? WHERE categoryID=?",array(implode(";",$_POST['varsubject']),$_POST['vartitle'],$_POST['varkey']));
                    echo("<center><span style='color:red'>Category edited.</span><br /><a href='/admin/Chan/categories'>Return</a></center>");
                    $k->log("Category ".$_POST['vartitle']." edited.");
                }
                break;
            case 'delete':
            case 'del':
                $c->query("DELETE FROM ms_categories WHERE categoryID=?",array($_POST['varkey']));
                echo("<center><span style='color:red'>Category deleted.</span><br /><a href='/admin/Chan/categories'>Return</a></center>");
                $k->log("Category ID".$_POST['varkey']." deleted.");
                break;
            }
        }
        break;

    case 'boards':
        if(!$a->check('chan.admin.boards'))return;
        if($params[1]==""){
            $boards = $c->getData("SELECT boardID,folder,title,merged,options FROM ch_boards",array());
            ?><form action="/admin/Chan/boards/add" method="post"><input type="text" name="varkey"><input type="submit" value="Submit"></form><?
            $table = new SettingsTable("boardsTable", new TableData(array("ID","folder","title","merged","options"),$boards),array("edit","delete"),
                    "/admin/Chan/boards/","/api.php?m=Chan&c=delBoard");
            $table->printTable();
        }else{
            switch($params[1]){
            case 'add':
                if($_POST['varfolder']==""||$_POST['vartitle']==""){
                    $boards = $c->getData("SELECT boardID,folder FROM ch_boards");
                    ?><form action="#" method="POST">
                        <br />Parent: <? $k->printSelect("varparent",$k->convertArrayDown($boards,"folder",array(" ")),$k->convertArrayDown($boards,"boardID",array("0")),$presel=-1); ?>
                        <br />Folder: <input type="text" name="varfolder" value="" />
                        Title: <input type="text" name="vartitle" value="<?=$_POST['varkey']?>" />
                        <br />Subject:<br />
                        <textarea name="varsubject" style="width:100%;height:200px;"></textarea>
                        <br />Max File Size (kb): <input type="text" name="varmaxfilesize" value="5130" />
                        Max pages: <input type="text" name="varmaxpages" value="15" />
                        Post limit: <input type="text" name="varpostlimit" value="300" />
                        Default theme: <input type="text" name="vardefaulttheme" value="" /> 
                        <? $filetypes=array("image/png","image/gif","image/jpeg","image/bitmap"); ?>
                        <br />Filetypes: <? $k->interactiveList("varfiletypes",$filetypes,$filetypes,array("image/png","image/gif","image/jpeg","image/bitmap"),true); ?>
                        <br />Merged from: <? $k->interactiveList("varmerged",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID")); ?>
                        <br />Options:
                            <input type="checkbox" name="varoptions[]" value="t" checked />New Threads 
                            <input type="checkbox" name="varoptions[]" value="l" />Locked
                            <input type="checkbox" name="varoptions[]" value="h" />Hidden
                            <input type="checkbox" name="varoptions[]" value="n" />Anon only
                            <input type="checkbox" name="varoptions[]" value="a" />Archive
                            <input type="checkbox" name="varoptions[]" value="f" />File required 
                        <br /><input type="submit" value="Submit" />
                    </form><?
                }else{
                    if(!is_array($_POST['varfiletypes']))$_POST['varfiletypes']=array();
                    if(!is_array($_POST['varmerged']))$_POST['varmerged']=array();
                    if(!is_array($_POST['varoptions']))$_POST['varoptions']=array();
                    $board = new ChanDataBoard(0,$_POST['varparent'],$_POST['varfolder'],$_POST['vartitle'],$_POST['varsubject'],$_POST['varmaxfilesize'],$_POST['varmaxpages'],
                            $_POST['varpostlimit'],implode(";",$_POST['varfiletypes']),$_POST['vardefaulttheme'],implode(";",$_POST['varmerged']),','.implode(",",$_POST['varoptions']), false, false);
                    $board->saveToDB();
                    mkdir(ROOT.DATAPATH.'chan/'.$_POST['varfolder']);
                    mkdir(ROOT.DATAPATH.'chan/'.$_POST['varfolder'].'/posts');
                    mkdir(ROOT.DATAPATH.'chan/'.$_POST['varfolder'].'/threads');
                    mkdir(ROOT.DATAPATH.'chan/'.$_POST['varfolder'].'/thumbs');
                    mkdir(ROOT.DATAPATH.'chan/'.$_POST['varfolder'].'/files');
                    BoardGenerator::generateBoard($board->boardID);
                    echo("<center><span style='color:red'>Board added.</span><br /><a href='/admin/Chan/boards'>Return</a></center>");
                    $k->log("Board ".$_POST['vartitle']." added.");
                }
                break;
            case 'edit':
                $board = ChanDataBoard::loadFromDB("SELECT * FROM ch_boards WHERE boardID=?",array($_POST['varkey']));
                $board=$board[0];
                if($_POST['vartitle']==""){
                    $boards = $c->getData("SELECT boardID,folder FROM ch_boards WHERE boardID!=?",array($_POST['varkey']));
                    ?><form action="#" method="POST">
                        <br />Parent: <? $k->printSelect("varparent",$k->convertArrayDown($boards,"folder",array(" ")),$k->convertArrayDown($boards,"boardID",array("0")),$board->PID); ?>
                        <br />Folder: <input type="text" name="varfolder" value="<?=$board->folder?>" disabled />
                        Title: <input type="text" name="vartitle" value="<?=$board->title?>" />
                        <br />Subject:<br />
                        <textarea name="varsubject" style="width:100%;height:200px;"><?=$board->subject?></textarea>
                        <br />Max File Size (kb): <input type="text" name="varmaxfilesize" value="<?=$board->maxFileSize?>" />
                        Max pages: <input type="text" name="varmaxpages" value="<?=$board->maxPages?>" />
                        Post limit: <input type="text" name="varpostlimit" value="<?=$board->postLimit?>" />
                        Default theme: <input type="text" name="vardefaulttheme" value="<?=$board->defaultTheme?>" />
                        <? $filetypes=array("image/png","image/gif","image/jpeg","image/bitmap"); ?>
                        <br />Filetypes: <? $k->interactiveList("varfiletypes",$filetypes,$filetypes,explode(";",$board->filetypes),true); ?>
                        <br />Merged from: <? $k->interactiveList("varmerged",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID"),explode(";",$board->merged)); ?>
                        <br />Options:
                            <input type="checkbox" name="varoptions[]" value="t" <? if(strpos($board->options,"t")!==FALSE)echo('checked'); ?> />New Threads
                            <input type="checkbox" name="varoptions[]" value="l" <? if(strpos($board->options,"l")!==FALSE)echo('checked'); ?> />Locked
                            <input type="checkbox" name="varoptions[]" value="h" <? if(strpos($board->options,"h")!==FALSE)echo('checked'); ?> />Hidden
                            <input type="checkbox" name="varoptions[]" value="n" <? if(strpos($board->options,"n")!==FALSE)echo('checked'); ?> />Anon only
                            <input type="checkbox" name="varoptions[]" value="a" <? if(strpos($board->options,"a")!==FALSE)echo('checked'); ?> />Archive
                            <input type="checkbox" name="varoptions[]" value="f" <? if(strpos($board->options,"f")!==FALSE)echo('checked'); ?> />File required
                        <br /><input type="submit" value="Submit" />
                        <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>" />
                    </form><?
                }else{
                    //UPDATE
                    if(!is_array($_POST['varfiletypes']))$_POST['varfiletypes']=array();
                    if(!is_array($_POST['varmerged']))$_POST['varmerged']=array();
                    if(!is_array($_POST['varoptions']))$_POST['varoptions']=array();
                    $board->PID=$_POST['varparent'];
                    $board->title=$_POST['vartitle'];
                    $board->subject=$_POST['varsubject'];
                    $board->maxFileSize=$_POST['varmaxfilesize'];
                    $board->maxPages=$_POST['varmaxpages'];
                    $board->postLimit=$_POST['varpostlimit'];
                    $board->filetypes=implode(";",$_POST['varfiletypes']);
                    $board->defaulttheme=$_POST['vardefaulttheme'];
                    $board->merged=implode(";",$_POST['varmerged']);
                    $board->options=','.implode(",",$_POST['varoptions']);
                    if($board->merged=="")$board->merged=" ";
                    if($board->options=="")$board->options=" ";
                    $board->saveToDB();
                    BoardGenerator::generateBoard($_POST['varkey']);
                    echo("<center><span style='color:red'>Board edited.</span><br /><a href='/admin/Chan/boards'>Return</a></center>");
                    $k->log("Board ".$_POST['vartitle']." edited.");
                }
                break;
            case 'delete':
            case 'del':
                $this->apiCall("delBoard",array("ID"=>$_POST['varkey']));
                echo("<span style='color:red'>Board deleted.</span><br /><a href='/admin/Chan/boards'>Return</a></center>");
                $k->log("Board ID".$_POST['varkey']." deleted.");
                break;
            }
        }
        break;

    case 'filetypes':
        if(!$a->check('chan.admin.filetypes'))return;
        if($params[1]==""){
            $types = $c->getData("SELECT typeID,title,mime,preview FROM ch_filetypes",array());?>
            <form action="/admin/Chan/filetypes/add" method="post"><input type="text" name="varkey"><input type="submit" value="Submit"></form>
            <? $table = new SettingsTable("boardsTable", new TableData(array("ID","title","mime","preview"),$types),array("edit","delete"),"/admin/Chan/filetypes/");
            $table->printTable();
        }else{
            switch($params[1]){
            case 'add':
                if($_POST['varmime']==""||$_FILES['varfile']==""){
                    ?><center><form action="#" method="POST" enctype="multipart/form-data" >
                        Title: <input type="text" name="vartitle" value="<?=$_POST['varkey']?>" /><br />
                        Mime: <input type="text" name="varmime" value="" /><br />
                        Preview: <input type="file" name="varfile" /><br />
                        <input type="submit" value="Submit" />
                    </form></center><?
                }else{
                    $ret=$k->uploadFile("varfile",ROOT.IMAGEPATH.'previews/',1024,array("image/png","image/jpeg","image/gif","image/bitmap"),false,$_POST['vartitle']);
                    if(is_numeric($ret)){
                        echo("<center><span style='color:red'>Upload failed.</span><br /><a href='/admin/Chan/filetypes'>Return</a></center>");
                    }else{
                        $type = new ChanDataFileType(0,$_POST['vartitle'],$_POST['varmime'],str_replace(ROOT.IMAGEPATH.'previews/',"",$ret), false, false);
                        $type->saveToDB();
                        echo("<center><span style='color:red'>Filetype added.</span><br /><a href='/admin/Chan/filetypes'>Return</a></center>");
                        $k->log("Filetype ".$_POST['vartitle']." added.");
                    }
                }
                break;
            case 'edit':
                $type = ChanDataFileType::loadFromDB("SELECT * FROM ch_filetypes WHERE typeID=?",array($_POST['varkey']));
                $type=$type[0];
                if($_POST['vartitle']==""){
                    $boards = $c->getData("SELECT boardID,folder FROM ch_boards WHERE boardID!=?",array($_POST['varkey']));
                    ?><center><form action="#" method="POST" enctype="multipart/form-data">
                        Title: <input type="text" name="vartitle" value="<?=$type->title?>" /><br />
                        Mime: <input type="text" name="varmime" value="<?=$type->mime?>" /><br />
                        Preview: <br />
                        <img src="<?=IMAGEPATH?>previews/<?=$type->preview?>" alt="preview" /><br />
                        <input type="file" name="varfile" /><br />
                        <input type="submit" value="Submit" />
                        <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>" />
                    </form></center><?
                }else{
                    if($_FILES['varfiles']!="")$ret=$k->uploadFile("varfile",ROOT.IMAGEPATH.'previews/',1024,array("image/png","image/jpeg","image/gif","image/bitmap"),false,$_POST['vartitle']);
                    else $ret=$type->preview;
                    if(is_numeric($ret)){
                        echo("<center><span style='color:red'>Upload failed.</span><br /><a href='/admin/Chan/filetypes'>Return</a></center>");
                    }else{
                        $type->preview=str_replace(ROOT.IMAGEPATH.'previews/',"",$ret);
                        $type->title=$_POST['vartitle'];
                        $type->mime=$_POST['varmime'];
                        $type->saveToDB();
                        echo("<center><span style='color:red'>Filetype edited.</span><br /><a href='/admin/Chan/filetypes'>Return</a></center>");
                        $k->log("Filetype ".$_POST['vartitle']." edited.");
                    }
                }
                break;
            case 'delete':
            case 'del':
                $type = ChanDataFileType::loadFromDB("SELECT preview FROM ch_filetypes WHERE typeID=?",array($_POST['varkey']));
                unlink(ROOT.IMAGEPATH.'previews/'.$type[0]->preview);
                $c->query("DELETE FROM ch_filetypes WHERE typeID=?",array($_POST['varkey']));
                echo("<center><span style='color:red'>Filetype deleted.</span><br /><a href='/admin/Chan/filetypes'>Return</a></center>");
                $k->log("Filetype ID".$_POST['varkey']." deleted.");
                break;
            }
        }
        break;

    case 'latestposts':
        $iterator=new TableIterator("chan_linkifyTablePostID",0,-1,0);
        $temp=$c->getData("SELECT COUNT(postID) FROM ch_posts");
        $table = new QueryTable("table", array('ID','BID','name','trip','subject','ip'),
                                        "SELECT postID,BID,name,trip,subject,ip FROM ch_posts ORDER BY time DESC",
                                        50, 0, 100, $temp[0]['COUNT(postID)'],$iterator);
        $table->printTable();
        break;

    case 'latestpics':
        $iterator=new TableIterator(array("chan_linkifyTablePostID","","","","chan_imageifyTablePostFile",""));
        $temp=$c->getData("SELECT COUNT(postID) FROM ch_posts WHERE file!=''");
        $table = new QueryTable("table", array('ID','BID','name','trip','file','ip'),
                                        "SELECT postID,BID,name,trip,file,ip FROM ch_posts WHERE file!='' ORDER BY time DESC",
                                        50, 0,100,$temp[0]['COUNT(postID)'],$iterator);
        $table->printTable();
        break;
    
    case 'rebuild':
        $boards=$c->getData("SELECT boardID,folder FROM ch_boards");
        if($_POST['varsubmit']==="Rebuild"){
            ob_flush();flush();
            $genposts = false;if($_POST['vargenposts']=="true")$genposts=true;
            $genthreads=false;if($_POST['vargenthreads']=="true")$genthreads=true;
            if($_POST['varboards']==""){
                foreach($boards as $board){
                    echo("<b>Generating ".$board['folder']."...</b><br />");ob_flush();flush();
                    BoardGenerator::generateBoard($board['boardID'],$genposts,$genthreads);
                }
            }else{
                foreach($_POST['varboards'] as $board){
                    echo("<b>Generating #".$board."...</b><br />");ob_flush();flush();
                    BoardGenerator::generateBoard($board,$genposts,$genthreads);
                }
            }
            echo("<br /><b>Done!</b>");ob_flush();flush();
        }
        ?><form action="#" method="post">
        <br />Boards to regenerate (Adding no boards equals rebuilding all):<br />
        <?=$k->interactiveList("varboards",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID"))?><br />
        <input type="checkbox" name="vergenthreads" value="true" /> Regenerate Threads<br />
        <input type="checkbox" name="vargenposts" value="true" /> Regenerate Posts<br />
        <input type="submit" name="varsubmit" value="Rebuild" />
        </form>
        <?
        break;
    
    case 'clean':
        $boards=$c->getData("SELECT boardID,folder FROM ch_boards");
        if($_POST['varsubmit']==="Clean"){
            ob_flush();
            if(!class_exists("DataGenerator"))include(TROOT.'modules/chan/datagen.php');
            $datagen = new DataGenerator();
            if($_POST['varboards']==""){
                foreach($boards as $board){
                    echo("<br /><b>Cleaning ".$board['folder']."...</b><br />");ob_flush();
                    $datagen->cleanBoard($board['boardID']);
                }
            }else{
                foreach($_POST['varboards'] as $board){
                    echo("<br /><b>Cleaning #".$board."...</b><br />");ob_flush();
                    $datagen->cleanBoard($board);
                }
            }
            echo("<br /><b>Done!</b>");ob_flush();
        }
        ?><form action="#" method="post">
        <br />Boards to clean (Adding no boards equals cleaning all):<br />
        <?=$k->interactiveList("varboards",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID"))?><br />
        <input type="submit" name="varsubmit" value="Clean" />
        </form>
        <?
        break;
    
    default:
        //$SectionList[""]         = "";
        if($a->check('chan.mod'))$SectionList["latestposts"] = "Latest Posts|Show the latest posts.";
        if($a->check('chan.mod'))$SectionList["latestpics"] = "Latest Pics|Show the latest pictures.";
        $SectionList["1"]         = "<--->";
        if($a->check('chan.admin.categories'))$SectionList["categories"]= "Categories|Add/Remove Board Categories";
        if($a->check('chan.admin.boards'))$SectionList["boards"]    = "Boards|Manage Boards";
        if($a->check('chan.admin.filetypes'))$SectionList["filetypes"] = "Filetypes|Change Filetypes";
        $SectionList["0"]         = "<--->";
        if($a->check('chan.admin.rebuild'))$SectionList["rebuild"] = "Rebuild|Rebuild the static files";
        if($a->check('chan.admin.clean'))$SectionList["clean"] = "Clean|Remove traces of deleted posts";
        return $SectionList;
        break;
    }
}

function apiCall($func,$args,$security=""){
    global $c,$k,$a,$api,$t,$p;
    if(!class_exists("ChanDataPost"))include(TROOT.'modules/chan/data.php');
    if(!class_exists("BoardGenerator"))include(TROOT.'modules/chan/boardgen.php');
    if(!class_exists("DataGenerator"))include(TROOT.'modules/chan/datagen.php');
    if(!class_exists("PostGenerator"))include(TROOT.'modules/chan/postgen.php');
    if($security!=$c->o['API_'.strtoupper($func).'_TOKEN'])return;
    $datagen = new DataGenerator();

    switch($func){
    case 'deluser':break;
    case 'delBoard':
        if(!$a->check("chan.admin.boards"))return false;
        if($args['ID']=="")$args['ID']=$_GET['id'];
        $board = ChanDataBoard::loadFromDB("SELECT folder FROM ch_boards WHERE boardID=?",array($args['ID']));
        if(count($board)==0)return false;
        if((int)$args['ID']>0){
            $c->query("UPDATE ch_boards SET boardID=? WHERE boardID=?",array((-1*$args['ID']),$args['ID']));
            $c->query("UPDATE ch_posts SET `options`=CONCAT(`options`,?) WHERE BID=?",array(',d',$args['ID']));
            rename(ROOT.DATAPATH.'chan/'.$board[0]->folder,ROOT.DATAPATH.'chan/.'.$board[0]->folder);
            return true;
        }else{
            $c->query("DELETE FROM ch_boards WHERE boardID=?",array($args['ID']));
            $c->query("DELETE FROM ch_posts  WHERE BID=?",array($args['ID']));
            exec('rm "'.ROOT.DATAPATH.'chan/.'.$board[0]->folder.'" -R');
            return true;
        }
        break;
    
    case 'purgeForm':
        ?><center><form action="/api.php?m=Chan&c=handlePurge" method="post" id="submitPurgeForm">
        Purge Everything of #<?=$args['postID']?>?<br />
        <input type="hidden" name="varpost" value="<?=$args['postID']?>"/>
        <input type="hidden" name="varboard" value="<?=$args['boardID']?>"/>
        <input type="submit" value="Do it!">
        </form></center>
        <script type='text/javascript'>
            $(document).ready(function() {$("#submitPurgeForm").ajaxForm( {success: showResponse});});
            function showResponse(responseText, statusText, xhr, $form){$("#submitPurgeForm").parent().html("Response: "+responseText);}
        </script><?
        break;

    case 'searchForm':
        $posterip=$c->getData("SELECT ip FROM ch_posts WHERE postID=? AND BID=?",array($args['postID'],$args['boardID']));
        $posterip=$posterip[0]['ip'];
        ?><center><form action="/api.php?m=Chan&c=handleSearch" method="post" id="submitSearchForm">
        Search for #<?=$args['postID']?>'s posts with IP<br />
        <input type="text" name="varip" value="<?=$posterip?>" /> on<br />
        <? $boards=$c->getData("SELECT boardID,folder FROM ch_boards"); ?>
        <?=$k->interactiveList("varsearch",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID"),array($args['boardID']),false)?>
        (Adding no boards equals searching on all)<br />
        <input type="hidden" name="varpost" value="<?=$args['postID']?>"/>
        <input type="hidden" name="varboard" value="<?=$args['boardID']?>"/>
        <input type="submit" value="Search">
        </form></center>
        <script type='text/javascript'>
            $(document).ready(function() {$("#submitSearchForm").ajaxForm( {success: showResponse});});
            function showResponse(responseText, statusText, xhr, $form){$("#submitSearchForm").parent().html(responseText);}
        </script><?
        break;

    case 'deleteForm':
        ?><center><form action="/api.php?m=Chan&c=handleDelete" method="post" id="submitDeleteForm">
        Delete Post #<?=$args['postID']?>
        <input type="hidden" name="varpost" value="<?=$args['postID']?>"/>
        <input type="hidden" name="varboard" value="<?=$args['boardID']?>"/>
        <input type="submit" value="Delete">
        </form></center>
        <script type='text/javascript'>
            $(document).ready(function() {$("#submitDeleteForm").ajaxForm( {success: showResponse});});
            function showResponse(responseText, statusText, xhr, $form){$("#submitDeleteForm").parent().html("Response: "+responseText);}
        </script><?
        break;

    case 'editForm':
        ?><center><form action="/api.php?m=Chan&c=handleEdit" method="post" id="submitEditForm">
        Edit Post #<?=$args['postID']?>:<br />
        <? $post=ChanDataPost::loadFromDB("SELECT postID,PID,BID,title,subject,name,trip,mail,options FROM ch_posts
                    WHERE postID=? AND BID=? ORDER BY postID DESC LIMIT 1",array($args['postID'],$args['boardID']));
        $post=$post[0];if($post=="")return;
        if($post->ip!=$_SERVER['REMOTE_ADDR']&&!$a->check("chan.mod.edit"))return;?>
        <? if($a->check("chan.mod.edit.name")){
            echo('<input type="text" name="varname" value="'.$post->name.'" style="width:100px;" />');
            echo('<input type="text" name="vartrip" value="'.$post->trip.'" style="width:100px;" />');
            echo('<input type="text" name="varmail" value="'.$post->mail.'" style="width:100px;" /><br />');
        }
        echo('<input type="text" name="vartitle" value="'.$post->title.'" style="width:300px;" /><br />');
        if(!$a->check("chan.mod.bbcodes"))$t->apiCall('getBBCodeBar',array('formid'=>'edittext','level'=>'0'));
        else                              $t->apiCall('getBBCodeBar',array('formid'=>'edittext','level'=>'99'));
        echo('<textarea name="vartext" style="width:400px;height:150px;" id="edittext" >'.$post->subject.'</textarea><br />');
        if($a->check("chan.mod")){
            if(strpos($post->options,"m")!==FALSE)$temp="checked";else $temp="";
            echo('<input type="checkbox" name="varoptions[]" value="m" '.$temp.' />Modpost ');
            if(strpos($post->options,"h")!==FALSE)$temp="checked";else $temp="";
            echo('<input type="checkbox" name="varoptions[]" value="h" '.$temp.' />Hidden ');
            if($post->PID==0){
                if(strpos($post->options,"l")!==FALSE)$temp="checked";else $temp="";
                echo('<input type="checkbox" name="varoptions[]" value="l" '.$temp.' />Locked ');
                if(strpos($post->options,"s")!==FALSE)$temp="checked";else $temp="";
                echo('<input type="checkbox" name="varoptions[]" value="s" '.$temp.' />Sticky ');
                if(strpos($post->options,"e")!==FALSE)$temp="checked";else $temp="";
                echo('<input type="checkbox" name="varoptions[]" value="e" '.$temp.' />Autosage ');
            }
        } ?>
        <input type="hidden" name="varpost" value="<?=$args['postID']?>"/>
        <input type="hidden" name="varboard" value="<?=$args['boardID']?>"/>
        <input type="submit" value="Edit">
        </form></center>
        <script type='text/javascript'>
            $(document).ready(function() {$("#submitEditForm").ajaxForm( {success: showResponse});});
            function showResponse(responseText, statusText, xhr, $form){$("#submitEditForm").parent().html("Response: "+responseText);}
        </script><?
        break;

    case 'mergeForm':
        ?><center><form action="/api.php?m=Chan&c=handleMerge" method="post" id="submitMergeForm">
        Merge Thread #<?=$args['postID']?> with<br />
        <? $boards=$c->getData("SELECT boardID,folder FROM ch_boards"); ?>
        <input type="text" name="varmergethread" value="0" /> on <?=$k->printSelect("varmergeboard",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID"),$args['boardID']);?>
        <input type="hidden" name="varpost" value="<?=$args['postID']?>"/>
        <input type="hidden" name="varboard" value="<?=$args['boardID']?>"/>
        <br /><input type="checkbox" name="varmergeop" value="true" checked /> Merge OPs
        <br /><input type="submit" value="Go.">
        </form></center>
        <script type='text/javascript'>
            $(document).ready(function() {$("#submitMergeForm").ajaxForm( {success: showResponse});});
            function showResponse(responseText, statusText, xhr, $form){$("#submitMergeForm").parent().html("Response: "+responseText);}
        </script><?
        break;

    case 'moveForm':
        ?><center><form action="/api.php?m=Chan&c=handleMove" method="post" id="submitMoveForm">
        Move Thread #<?=$args['postID']?>
        <? $boards=$c->getData("SELECT boardID,folder FROM ch_boards"); ?>
        to <?=$k->printSelect("varmoveboard",$k->convertArrayDown($boards,"folder"),$k->convertArrayDown($boards,"boardID"),$args['boardID']);?>
        <input type="hidden" name="varpost" value="<?=$args['postID']?>"/>
        <input type="hidden" name="varboard" value="<?=$args['boardID']?>"/>
        <br /><input type="submit" value="Go.">
        </form></center>
        <script type='text/javascript'>
            $(document).ready(function() {$("#submitMeoveForm").ajaxForm( {success: showResponse});});
            function showResponse(responseText, statusText, xhr, $form){$("#submitMoveForm").parent().html("Response: "+responseText);}
        </script><?
        break;

    case 'banForm':
        ?><center><form action="/api.php?m=Chan&c=handleBan" method="post" id="submitBanForm">
        Ban Post #<?=$args['postID']?><br />
        Mask: <input type="text" name="varmask" value="<?=$args['mask']?>" /><br />
        Time: <select name="vartime">
        <option value="custom">Custom</option>
        <option value="1">1 Second</option>
        <option value="60">1 Minute</option>
        <option value="3600">1 Hour</option>
        <option value="86400">1 Day</option>
        <option value="604800">1 Week</option>
        <option value="2592000">1 Month</option>
        <option value="31536000">1 Year</option>
        <option value="-1">Permaban</option></select><input type="text" name="vartime2" value="60">m<br />
        Reason: (/mute gives a silencer ban.)<br />
        <textarea name="varreason" style="width:300px;height:75px;"></textarea><br />
        Post Message:<br />
        <? $t->apiCall('getBBCodeBar',array('formid'=>'bantext','level'=>'0')); ?>
        <textarea name="varmessage" id="bantext" style="width:300px;height:75px;">[b][color=red](USER WAS BANNED FOR THIS POST)[/color][/b]</textarea><br />
        Appeal: <select name="varappeal"><option value="true">Allow</option><option value="false">Deny</option></select>
        <input type="hidden" name="varpost" value="<?=$args['postID']?>"/>
        <input type="hidden" name="varboard" value="<?=$args['boardID']?>"/>
        <input type="submit" value="Ban">
        </form></center>
        <script type='text/javascript'>
            $(document).ready(function() {
                $("#submitBanForm").ajaxForm( {success: showResponse});

            });
            function showResponse(responseText, statusText, xhr, $form){
                $("#submitBanForm").parent().html("Response: "+responseText);
            }
        </script><?
        break;


    case 'handlePurge':
        if(!$a->check("chan.mod.purge"))return "No Access.";
        if(($_POST['varpost']==""||$_POST['varboard']==""))return "Missing postID.";
        try{
            $post = ChanDataPost::loadFromDB("SELECT ip FROM ch_posts WHERE postID=? AND BID=?",$array($_POST['varpost'],$_POST['varboard']));
            if(count($post)==0)return "No such post.";
            $datagen->deleteByIP($post[0]->ip);
        }catch(Exception $e){
            if($a->check('chan.admin'))return $e->getMessage()."\n\n".$e->getTraceAsString();
            else return $e->getMessage();
        }
        break;
    case 'handleSearch':
        if(!$a->check("chan.mod.search"))return "No Access.";
        if(($_POST['varpost']==""||$_POST['varboard']==""))return "Missing postID.";
        
        $search=$_POST['varsearch'];$boardq="";if(!is_array($search))$search=array();
        foreach($search as $board){$boardq.=" OR BID=".$board;}
        if($boardq!="")$boardq=" AND (".substr($boardq,4).") ";
        
        $posts = ChanDataPost::loadFromDB("SELECT postID,BID,name,trip,title,subject,file FROM ch_posts WHERE ip LIKE ? ".$boardq." ORDER BY time DESC LIMIT 10",array($_POST['varip']));
        if($boardq!="")$boardq="WHERE ".str_replace("BID","boardID",substr($boardq,4));
        $boards = $c->getData("SELECT boardID,folder FROM ch_boards ".$boardq);
        
        echo('Last (m10) posts by '.$_POST['varip'].':<br />');
        echo('<div style="max-height:500px;overflow:scroll;"><table style="width:100%;">'.
             '<thead><tr><th>ID</th><th>Name</th><th>Message</th></tr></thead><tbody>');
        for($i=0,$temp=count($posts);$i<$temp;$i++){
            $folder="";for($j=0;$j<count($boards);$j++){if($boards[$j]["boardID"]==$posts[$i]->BID)$folder=$boards[$j]["folder"];}
            if($posts[$i]->file!='')$img='<img class="flLeft" src="'.$c->o['chan_fileloc_extern'].$folder.'/thumbs/'.$posts[$i]->file.'" />';else $img="";
            echo('<tr><td><a href="'.$k->url("chan","byID/".$posts[$i]->BID."/".$posts[$i]->postID).'">'.$posts[$i]->postID.'</a></td>'.
                     '<td>'.$posts[$i]->name.$posts[$i]->trip.'</td>'.
                     '<td>'.$img.'<b>'.$posts[$i]->title.'</b><br /><br />'.$p->deparseAll($posts[$i]->subject,0).'<br class="clear" /></td></tr>');
        }
        echo("</tbody></table></div>");
        
        break;
    case 'handleDelete':
        if(!$a->check("chan.mod.delete"))return "No Access.";
        if(($_POST['varpost']==""||$_POST['varboard']==""))return "Missing postID.";
        try{
            $datagen->deletePost($_POST['varpost'], $_POST['varboard'], true);
        }catch(Exception $e){
            if($a->check('chan.admin'))return $e->getMessage()."\n\n".$e->getTraceAsString();
            else return $e->getMessage();
        }
        break;
    case 'handleEdit':
        if(!$a->check("chan.mod.edit"))return "No Access.";
        if(($_POST['varpost']==""||$_POST['varboard']==""))return "Missing postID.";
        $post = ChanDataPost::loadFromDB("SELECT * FROM ch_posts WHERE postID=? AND BID=?",
                                        array($_POST['varpost'],$_POST['varboard']));
        if(count($post)==0)return "No such post.";
        $post=$post[0];
        $post->name=$_POST['varname'];
        $post->trip=$_POST['vartrip'];
        $post->mail=$_POST['varmail'];
        $post->title=$_POST['vartitle'];
        $post->subject=$_POST['vartext'];
        if(!is_array($_POST['varoptions']))$_POST['varoptions']=array();
        if($a->check("chan.mod.bbcodes")&&!in_array("p",$_POST['varoptions']))$_POST['varoptions'][]="p";
        $post->options=implode(",",$_POST['varoptions']);
        $post->saveToDB();
        $post->name=$p->enparse($post->name);
        $post->trip=$p->enparse($post->trip);
        $post->mail=$p->enparse($post->mail);
        $post->title=$p->enparse($post->title);
        $post->subject=$p->enparse($post->subject);
        PostGenerator::generatePostFromObject($post);
        if($post->PID==0){
            BoardGenerator::generateBoard($post->BID);
        }
        return "Post edited.";
        break;
    case 'handleMerge':
        if(!$a->check("chan.mod.merge"))return "No Access.";
        if(($_POST['varpost']==""||$_POST['varboard']==""))return "Missing postID.";
        try{
            if($_POST['varmergeop']=="true")$op=true;else $op=false;
            $datagen->mergeThread($_POST['varpost'], $_POST['varboard'],$_POST['varmergethread'],$_POST['varmergeboard'],$op);
        }catch(Exception $e){
            if($a->check('chan.admin'))return $e->getMessage()."<br />".$e->getTraceAsString();
            else return $e->getMessage();
        }
        break;
    case 'handleMove':
        if(!$a->check("chan.mod.move"))return "No Access.";
        if(($_POST['varpost']==""||$_POST['varboard']==""))return "Missing postID.";
        try{
            $datagen->moveThread($_POST['varpost'], $_POST['varboard'],$_POST['varmoveboard']);
        }catch(Exception $e){
            if($a->check('chan.admin'))return $e->getMessage()."<br />".$e->getTraceAsString();
            else return $e->getMessage();
        }
        break;

    case 'handleBan':
        if(!$a->check("admin.ban"))return "No Access.";
        if($_POST['varmask']=="")return "Missing IP Mask.";
        if($_POST['varpost']!=""){
            if(($_POST['varpost']==""||$_POST['varboard']==""))return "Missing postID.";
            $post = ChanDataPost::loadFromDB("SELECT postID,BID,subject FROM ch_posts WHERE postID=? AND BID=?",array($_POST['varpost'],$_POST['varboard']));
            if(count($post)==0)return "No such post.";
            $post[0]->subject.="\n".$_POST['varmessage'];
            $post[0]->saveToDB();
            PostGenerator::generatePost($_POST['varpost'],$_POST['varboard']);
        }
        if($_POST['vartime']=="custom")$_POST['vartime']=60*$_POST['vartime2'];
        $api->call("ACP","ban",array("mask"=>$_POST['varmask'],"time"=>$_POST['vartime'],"reason"=>$_POST['varreason'],"appeal"=>$_POST['varappeal']),$c->o['API_BAN_TOKEN']);
        return "IP ".$_POST['varmask']." has been banned for ".$_POST['vartime']." seconds.";
        break;
        
    case 'handlePost':
        try{
            $datagen->submitPost();
        }catch(Exception $e){
            if($a->check('chan.admin'))$k->err($e->getMessage()."\n\n".$e->getTraceAsString());
            else $k->err($e->getMessage());
        }
        break;
        
    case 'handleBoardForm':
        if($_POST['varboard']==""){$k->err("No board given.");die();}
        if($_POST['submitter']=="Delete"){
            if(!class_exists("DataGenerator"))include(TROOT.'modules/chan/datagen.php');
            $datagen = new DataGenerator();
            for($i=0;$i<count($_POST['varposts']);$i++){
                try{
                    if($_POST['varfileonly']==="1")
                        $datagen->deletePost($_POST['varposts'][$i], $_POST['varboard'], false, true);
                    else
                        $datagen->deletePost($_POST['varposts'][$i], $_POST['varboard'], true, false);
                    echo('Post deleted.<br />');
                }catch(Exception $e){
                    if($a->check('chan.admin'))$k->err($e->getMessage()."\n\n".$e->getTraceAsString());
                    else $k->err($e->getMessage());
                }
            }
            echo('Redirecting... <script type="text/javascript">window.setTimeout("window.location=\''.$_SERVER['HTTP_REFERER'].'\'", 1000);</script>');
        }
        if($_POST['submitter']=="Report"){
            $_POST['varreason']=trim($_POST['varreason']);
            if($_POST['varreason']==""||$_POST['varreason']=="reason")$k->err("No reporting reason given.");
            else if($_POST['varposts']=="")$k->err("No posts checked.");
            else{
                for($i=0;$i<count($_POST['varposts']);$i++){
                    $post = ChanDataPost::loadFromDB("SELECT subject FROM ch_posts WHERE postID=? AND BID=?",array($_POST['varposts'][$i], $_POST['varboard']));
                    $subject = '[url='.$k->url('chan','byID/'.$_POST['varboard'].'/'.$_POST['varposts'][$i]).']>>'.$_POST['varposts'][$i].'[/url]'."[hr]\n".$p->reparseHTML(array('',$post[0]->subject));
                    if(count($post)==0)$k->err("No such post.");
                    else $c->query("INSERT INTO ms_tickets VALUES(NULL,?,?,?,?,?,?)",array(
                                    $this->id,$_POST['varposts'][$i],$_POST['varreason'],$subject,time(),$_SERVER['REMOTE_ADDR']));
                }
                echo('Report ticket filed.<br />');
                echo('Redirecting... <script type="text/javascript">window.setTimeout("window.location=\''.$_SERVER['HTTP_REFERER'].'\'", 1000);</script>');
            }
        }
        break;

    case 'getPostData':
        if($args['board']==""||$args['post']=="")return;
        $bid=$c->getData("SELECT folder FROM ch_boards WHERE boardID=? LIMIT 1;",array($args['board']));
        $id=$c->getData("SELECT postID,PID,name,trip,title,subject,time,options FROM ch_posts WHERE BID=? AND postID=? ORDER BY postID DESC LIMIT 1;",array($args['board'],$args['post']));
        if(count($id)==0||count($bid)==0)return;
        $bid=$bid[0];$id=$id[0];
        $return.= "<post id='".$id['postID']."' pid='".$id['PID']."' >";
        if($id['PID']==0){
            $postcount = $c->getData("SELECT COUNT(postID) FROM ch_posts WHERE BID=? AND PID=? AND options NOT REGEXP ?",array($args['board'],$args['post'],'d'));
            $lastpost = $c->getData("SELECT postID FROM ch_posts WHERE BID=? AND PID=? AND options NOT REGEXP ? ORDER BY postID DESC LIMIT 1;",array($args['board'],$args['post'],'d'));
            $return.= "<thread postcount='".$postcount[0]['COUNT(postID)']."' lastpost='".$lastpost[0]['postID']."' />";
        }
        $return.= "<board  id='".$args['board']."' folder='".$bid['folder']."' />";
        $return.= "<user name='".$id['name']."' trip='".$id['trip']."' />";
        $return.= "<data title='".$id['title']."' time='".$id['time']."' options='".$id['options']."' >".$p->deparseAll($p->automaticLines($id['subject']),0)."</data>";
        $return.= "</post>";
        return $return;
        break;

    case 'getLatestBoardPost':
        if($args['board']=="")return;
        if(is_numeric($args['board'])){
            $bid=$c->getData("SELECT boardID,folder FROM ch_boards WHERE boardID=? LIMIT 1;",array($args['board']));
        } else {
            $bid=$c->getData("SELECT boardID,folder FROM ch_boards WHERE folder=? LIMIT 1;" ,array($args['board']));
        }
        $bid=$bid[0];
        $id=$c->getData("SELECT postID FROM ch_posts WHERE BID=? ORDER BY postID DESC LIMIT 1;",array($bid['boardID']));
        if(count($id)==0)return;
        include(ROOT.DATAPATH.'chan/'.$bid['folder'].'/posts/'.$id[0]['postID'].'.php');
        break;

    case 'getLatestBoardPostInfo':
        if($args['board']=="")return;
        if(is_numeric($args['board'])){
            $bid=$c->getData("SELECT boardID,folder FROM ch_boards WHERE boardID=? LIMIT 1;",array($args['board']));
        } else {
            $bid=$c->getData("SELECT boardID,folder FROM ch_boards WHERE folder=? LIMIT 1;" ,array($args['board']));
        }
        $bid=$bid[0];
        $id=$c->getData("SELECT postID,PID,title,name,trip,time FROM ch_posts WHERE BID=? ORDER BY postID DESC LIMIT 1;",array($bid['boardID']));
        if(count($id)==0)return;$id=$id[0];
        return '#'.$id['postID'].' in response to #'.$id['PID'].' by '.$id['name'].''.$id['trip'].' on '.$k->toDate($id['time']).' ('.$id['title'].')';
        break;

    case 'getLatestThreadPost':
        if($args['board']==""||$args['thread']=="")return;
        if(is_numeric($args['board'])){
            $bid=$c->getData("SELECT boardID,folder FROM ch_boards WHERE boardID=? LIMIT 1;",array($args['board']));
        } else {
            $bid=$c->getData("SELECT boardID,folder FROM ch_boards WHERE folder=? LIMIT 1;" ,array($args['board']));
        }
        $bid=$bid[0];
        $id=$c->getData("SELECT postID FROM ch_posts WHERE BID=? AND PID=? ORDER BY postID DESC LIMIT 1;",array($bid['boardID'],$args['thread']));
        if(count($id)==0)return;
        include(ROOT.DATAPATH.'chan/'.$bid['folder'].'/posts/'.$id[0]['postID'].'.php');
        break;

    case 'getLatestThreadPostInfo':
        if($args['board']==""||$args['thread'])return;
        if(is_numeric($args['board'])){
            $bid=$c->getData("SELECT boardID,folder FROM ch_boards WHERE boardID=? LIMIT 1;",array($args['board']));
        } else {
            $bid=$c->getData("SELECT boardID,folder FROM ch_boards WHERE folder=? LIMIT 1;" ,array($args['board']));
        }
        $bid=$bid[0];
        $id=$c->getData("SELECT postID,title,name,trip,time FROM ch_posts WHERE BID=? AND PID=? ORDER BY postID DESC LIMIT 1;",array($bid['boardID'],$args['thread']));
        if(count($id)==0)return;
        return '#'.$id['postID'].' by '.$id['name'].''.$id['trip'].' on '.$k->toDate($id['time']).' ('.$id['title'].')';
        break;

    case 'getLatestThreadPostID':
        if($args['board']==""||$args['thread']=="")return;
        if(!is_numeric($args['board'])){
            $args['board']=$c->getData("SELECT boardID FROM ch_boards WHERE folder=? LIMIT 1;" ,array($args['board']));
            $args['board']=$args['board'][0]['boardID'];
        }
        $id=$c->getData("SELECT postID FROM ch_posts WHERE BID=? AND PID=? ORDER BY postID DESC LIMIT 1;",array($bid['boardID'],$args['thread']));
        if(count($id)==0)return 0;
        return $id[0]['postID'];
        break;
    
    case 'getBoardRSS':
        if($args['board']=="")return;
        $board=$c->getData("SELECT boardID,folder FROM ch_boards WHERE boardID=?",array($args['board']));
        if(count($board)==0)return '';
        $posts=$c->getData("SELECT postID,name,trip,title,subject,file FROM ch_posts WHERE BID=? AND PID=0 AND options NOT REGEXP ? ORDER BY postID DESC LIMIT 5;",array($args['board'],'d'));
        header("Content-type: text/xml");
	echo('<?xml version="1.0" encoding="ISO-8859-1" ?><rss version="2.0"><channel>');
        echo('<title>'.$c->o['chan_title'].' /'.$board[0]['folder'].'/ threads</title>');
	echo('<description>'.$c->o['chan_title'].'RSS feed for /'.$board[0]['folder'].'/ threads</description>');
	echo('<link>'.$k->url('chan',$board[0]['folder']).'</link>');
        for($i=0,$temp=count($posts);$i<$temp;$i++){
            echo('<item>');
		echo('<title>#'.$posts[$i]['postID'].' '.$posts[$i]['name'].$posts[$i]['trip'].' '.$posts[$i]['title'].'</title>');
		echo('<link>'.$k->url('chan','byID/'.$args['board'].'/'.$posts[$i]['postID']).'</link>');
		echo('<guid isPermaLink="true">'.$k->url('chan','byID/'.$args['board'].'/'.$posts[$i]['postID']).'</guid>');
		echo('<description><![CDATA[<img src="http://'.HOST.DATAPATH.'chan/'.$board[0]['folder'].'/thumbs/'.$posts[$i]['file'].'" /><br />'.$p->deparseAll($posts[$i]['subject']).']]></description>');
            echo('</item>');//http://localhost/data/chan/test/thumbs/132093191671696.png
        }
        echo('</channel></rss>');
        break;
    
    case 'getThreadRSS':
        if($args['board']==""||$args['thread']=="")return;
        $op=$c->getData("SELECT postID,name,trip,title FROM ch_posts WHERE BID=? AND postID=? AND options NOT REGEXP ?",array($args['board'],$args['thread'],'d'));
        if(count($op)==0)return '';
        $posts=$c->getData("SELECT postID,name,trip,title,subject FROM ch_posts WHERE BID=? AND PID=? AND options NOT REGEXP ? ORDER BY postID DESC LIMIT 5;",array($args['board'],$args['thread'],'d'));
        header("Content-type: text/xml");
	echo('<?xml version="1.0" encoding="ISO-8859-1" ?><rss version="2.0"><channel>');
        echo('<title>'.$c->o['chan_title'].' thread #'.$op[0]['postID'].': '.$op[0]['title'].'</title>');
	echo('<description>'.$c->o['chan_title'].'RSS feed for thread #'.$op[0]['postID'].': '.$op[0]['title'].'</description>');
	echo('<link>'.$k->url('chan','byID/'.$args['board'].'/'.$args['thread']).'</link>');
        for($i=0,$temp=count($posts);$i<$temp;$i++){
            echo('<item>');
		echo('<title>#'.$posts[$i]['postID'].' '.$posts[$i]['name'].$posts[$i]['trip'].' '.$posts[$i]['title'].'</title>');
		echo('<link>'.$k->url('chan','byID/'.$args['board'].'/'.$posts[$i]['postID']).'</link>');
		echo('<guid isPermaLink="true">'.$k->url('chan','byID/'.$args['board'].'/'.$posts[$i]['postID']).'</guid>');
		echo('<description><![CDATA['.$p->deparseAll($posts[$i]['subject']).']]></description>');
            echo('</item>');
        }
        echo('</channel></rss>');
        break;
    
    case 'getThreadWatchTable':
        $watched = array_filter(explode(";",$_COOKIE['chan_watched']));
        sort($watched);
        if(count($watched)==0)return "";
        
        for($i=0,$temp=count($watched);$i<$temp;$i++){
            $temp2=explode(" ",$watched[$i]);
            if(is_numeric($temp2[0])&&is_numeric($temp2[1])){
                $boardIDs.= " OR boardID=".$temp2[0];
                $querypart.=" OR (postID=".$temp2[1]." AND BID=".$temp2[0]." AND PID=0)";
            }
        }
        if(trim($querypart)=="")return "";
        $data = $c->getData("SELECT postID,BID,title,name,trip FROM ch_posts WHERE ".substr($querypart,4)." LIMIT 20");
        $boards=$c->getData("SELECT boardID,folder FROM ch_boards WHERE ".substr($boardIDs,4));
        if(count($data)==0)return '';
        
        $ret="";
        for($i=0,$temp=count($data);$i<$temp;$i++){
            
            $time=0;
            for($j=0,$temp2=count($watched);$j<$temp2;$j++){
                $temp3=explode(" ",$watched[$j]);
                if($temp3[0]==$data[$i]['BID']&&$temp3[1]==$data[$i]['postID']){
                    $time=$temp3[2];break;
            }}
            $postcount = $c->getData("SELECT COUNT(postID) FROM ch_posts WHERE PID=? AND BID=? AND time>?",array($data[$i]['postID'],$data[$i]['BID'],$time));
            $postcount=$postcount[0]['COUNT(postID)'];
            
            $folder="";
            for($j=0,$temp2=count($boards);$j<$temp2;$j++){
                if($boards[$j]['boardID']==$data[$i]['BID']){
                    $folder=$boards[$j]['folder'];break;
            }}
            
            $ret.='<tr><td><a href="#" class="watchDeleteButton" title="Remove" id="'.$data[$i]['postID'].'" board="'.$data[$i]['BID'].'" >✘</a></td>'.
                  '<td><a href="'.$k->url('chan',$folder).'">'.$folder.'</a></td>'.
                  '<td><a href="'.$k->url('chan',$folder.'/threads/'.$data[$i]['postID']).'">'.$data[$i]['postID'].'</a></td>'.
                  '<td>'.$data[$i]['name'].$data[$i]['trip'].'</td>'.
                  '<td>'.$data[$i]['title'].'</td>';
            if($postcount>0)$ret.='<td class="watchNewPosts"><a href="'.$k->url('chan',$folder.'/threads/'.$data[$i]['postID']).'">'.$postcount.'</a></td></tr>';
            else            $ret.='<td>0</td></tr>';
        }
        return $ret;
        break;
        
    case 'getOptionsPage':
        ?><form>
            <input type="checkbox" value="u" id="cbu" /><label style="width:200px;display:inline-block;vertical-align:middle">Auto update threads</label><br />
            <input type="checkbox" value="f" id="cbf" /><label style="width:200px;display:inline-block;vertical-align:middle">Fixed post box</label><br />
            <input type="checkbox" value="p" id="cbp" /><label style="width:200px;display:inline-block;vertical-align:middle">Show image previews</label><br />
            <input type="checkbox" value="e" id="cbe" /><label style="width:200px;display:inline-block;vertical-align:middle">Enlarge image on click</label><br />
            <input type="checkbox" value="h" id="cbh" /><label style="width:200px;display:inline-block;vertical-align:middle">Enable thread hiding</label><br />
            <input type="checkbox" value="s" id="cbs" /><label style="width:200px;display:inline-block;vertical-align:middle">Scroll to post when selecting</label><br />
            <input type="checkbox" value="q" id="cbq" /><label style="width:200px;display:inline-block;vertical-align:middle">Show post quote previews</label><br />
            <input type="checkbox" value="w" id="cbw" /><label style="width:200px;display:inline-block;vertical-align:middle">Always show watched threads</label><br />
            <input type="submit" id="saveOptions" value="Save" /> 
            <span id="saveResult" style="color:red;font-weight:bold;"></span>
        </form><script type="text/javascript">
            var ops = ['u','p','e','h','s','q','w','f'];
            for(var i=0;i<ops.length;i++){
                if(options.indexOf(ops[i])!=-1)$("#cb"+ops[i]).prop("checked", true);
            }
            $("#saveOptions").click(function(){
                options="";
                for(var i=0;i<ops.length;i++){
                    if($("#cb"+ops[i]).is(":checked"))options+=ops[i];
                }
                $.cookie("chan_options",options,{ expires: 356, path: '/' });
                $("#saveResult").html("Saved! Reloading page...");
                window.setTimeout('location.reload()', 1000);
            });
        </script><?
        break;
    }
}
}
?>
