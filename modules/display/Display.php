<? class Display{
public static $name="Display";
public static $author="NexT";
public static $version=0.01;
public static $short='display';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

//TODO: Add ability to delete pic on its edit page
//TODO: Add admin back-end for folder management.

function buildMenu($menu){
    global $a;
    if($a->check('display.folder.*')){
        $inner=array(
            array('Upload',Toolkit::url('gallery','upload/')),
            array('Manage',Toolkit::url('gallery','manage/'))
        );
    }else $inner=array();
    $menu[]=array('Gallery',Toolkit::url('gallery'),'',$inner);
    return $menu;
}

function displayPage(){
    global $t,$a,$params,$param;
    $t->js[]='/display.js';
    $t->css[]='/display.css';
    $param = str_replace(' ','_',$param);
    switch($params[0]){
        case 'view':
            if(strpos($params[1],'-')!==FALSE)$params[1]=substr($params[1],0,strpos($params[1],'-'));
            $this->displayPicture($params[1]);
            break;
        case 'upload':
            if($param=='upload/'){
                $this->displayFolderChooser();
            }else{
                if(substr($param,strlen($param)-1)=='/')$param=substr($param,0,strlen($param)-1);
                $picture = DataModel::getHull('display_pictures');
                $picture->folder=strtolower(str_replace('upload/','',$param));
                $picture->time=time();
                $picture->user=$a->user->username;
                $this->displayEdit($picture);break;
            }
            break;
        case 'edit':
            $picture = DataModel::getData('display_pictures','SELECT * FROM display_pictures WHERE pictureID=?',array($params[1]));
            $this->displayEdit($picture);break;
            break;
        case 'manage':$this->displayManage();break;
        default: $this->displayFolder(strtolower($param));break;
    }
}

function displayPicture($pictureID){
    global $t,$l,$a;
    $picture = DataModel::getData('display_pictures','SELECT folder,title,text,time,tags,filename,user FROM display_pictures WHERE pictureID=?',array($pictureID));
    if($picture==null){
        $t->openPage('404 - Gallery');
        include(PAGEPATH.'404.php');
    }else{
        $folder = DataModel::getData('display_folders','SELECT folder,text,pictures FROM display_folders WHERE folder=?',array($picture->folder));
        if($folder==null)$folder = DataModel::getHull ('display_folders');
        $order = explode(',',$folder->pictures);
        $cpos = array_search($pictureID,$order);
        $path = DATAPATH.'uploads/display/src/'.$picture->folder.'/'.$picture->filename;
        $fpath = explode('/',$picture->folder);
        $folder->title=ucfirst(str_replace('_',' ',$fpath[count($fpath)-1]));
        
        $t->openPage($picture->title.' - Gallery');
        ?><div id="foldercontent">
            <div id="folderinfo" >
                <h3><a href="<?=PROOT.$picture->folder?>"><?=$folder->title?></a></h3>
                <blockquote><?=$l->triggerParse('CORE',$folder->text);?></blockquote>
                <? $l->triggerHookSequentially('FolderHeader','Display',$folder); ?>
                <div id="foldercrumbs">
                    <? $crumbs = array();
                    for($i=0;$i<count($fpath)-1;$i++){
                        $sofar.=$fpath[$i].'/';
                        $crumbs[]='<a href="'.PROOT.$sofar.'">'.ucfirst($fpath[$i]).'</a>'; 
                    }$crumbs = array_reverse($crumbs);echo(implode('',$crumbs)); ?>
                </div>
                <div id="foldernav">
                    <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.upload')){ ?><a href='<?=PROOT.'upload/'.$folder->folder?>'>Upload</a><? } ?>
                    <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.manage')){ ?><a href='<?=PROOT.'manage/'.$folder->folder?>'>Manage</a><? } ?>
                    <? if($a->check('display.folder.'.str_replace('/','.',$picture->folder).'.manage')||$a->user->username==$picture->user){?>
                        <a href="<?=PROOT.'edit/'.$pictureID?>">Edit</a>
                    <? } ?>
                    <? $l->triggerHookSequentially('PictureNav','Display',$picture); ?>
                </div>
            </div>
            <div id="pictureblock" >
                <a name="picture"></a>
                <img id="image" src="<?=$path?>" />
                <div id="picturenav">
                    <? if($cpos>0){ ?>
                        <a href="<?=PROOT.'view/'.$order[0]?>#picture" id="ll">&ll;</a>
                        <a href="<?=PROOT.'view/'.$order[$cpos-1]?>#picture" id="lp">&lt;</a>
                    <? } ?>
                    <a href="<?=PROOT.'view/'.$pictureID.'-'.str_replace(' ','-',$picture->title)?>#picture" id="cc">=</a>
                    <? if($cpos<count($order)-1){ ?>
                        <a href="<?=PROOT.'view/'.$order[$cpos+1]?>#picture" id="rn">&gt;</a>
                        <a href="<?=PROOT.'view/'.$order[count($order)-1]?>#picture" id="rf">&gg;</a>
                    <? } ?>
                    <? if($folder->folder!=''){ ?>
                        <a href="<?=PROOT.$picture->folder?>#folder" id="ff">&rarrhk;</a>
                    <? } ?>
                    <? $l->triggerHookSequentially('PictureImageNav','Display',$picture); ?>
                </div>
                <div id="pictureinfoshort">
                    <h4>Info:</h4>
                    ID: <?=$pictureID?><br />
                    Uploader: <?=Toolkit::getUserPage($picture->user)?><br />
                    Date: <?=Toolkit::toDate($picture->time);?><br />
                    Folder: <?=$picture->folder?><br />
                    
                    <? if(file_exists(ROOT.$path)){
                        $size = filesize(ROOT.$path);
                        $type = Toolkit::getImageType(ROOT.$path);
                        $data = getimagesize(ROOT.$path);?>
                        File: <?=$picture->filename?><br />
                        Type: <?=$type?><br />
                        Width: <?=$data[0]?>px<br />
                        Height: <?=$data[1]?>px<br />
                        Size: <?=Toolkit::displayFilesize($size)?><br />
                    <?}else{?>
                        File is missing!
                    <? } ?>
                    <? $l->triggerHookSequentially('PictureInfoShort','Display',$picture); ?>
                </div>
            </div>
            <div id="pictureinfo">
                <h2><?=$picture->title?></h2>
                Tags: <? Toolkit::compileTagList($picture->tags); ?><br />
                <article>
                    <blockquote>
                        <?=$l->triggerParse('CORE',$picture->text);?>
                    </blockquote>
                </article>
                <? $l->triggerHookSequentially('PictureInfo','Display',$picture); ?>
            </div>
            <script type="text/javascript">$(document).ready(function(){initPicture()});</script>
        </div><?
    }
    $t->closePage();
}

function displayFolder($folderpath){
    global $t,$l,$a,$c;
    if(substr($folderpath,strlen($folderpath)-1)=='/')$folderpath=substr($folderpath,0,strlen($folderpath)-1);
    if($folderpath=='')$folderpath==$c->o['display_default_gallery'];
    
    $folder = DataModel::getData('display_folders','SELECT folder,text FROM display_folders WHERE folder LIKE ?',array($folderpath));
    if($folder==null){
        $t->openPage('404 - Gallery');
        include(PAGEPATH.'404.php');
    }else{
        $t->openPage($folder->folder.' - Gallery');
        $path = explode('/',$folder->folder);
        $folder->title=ucfirst(str_replace('_',' ',$path[count($path)-1]));
        
        $max = DataModel::getData('display_pictures','SELECT COUNT(pictureID) AS pictures FROM display_pictures WHERE folder LIKE ?',array($folder->folder));
        Toolkit::sanitizePager($max->pictures);
        $pictures = DataModel::getData('display_pictures','SELECT pictureID,title,filename FROM display_pictures 
                                                           WHERE folder LIKE ? ORDER BY time DESC
                                                           LIMIT '.$_GET['f'].','.$_GET['s'],array($folder->folder));
        
        $subfolders = DataModel::getData('display_folders','SELECT display_folders.folder,display_pictures.filename 
                                                            FROM display_folders JOIN display_pictures USING(folder)
                                                            WHERE folder LIKE ? 
                                                            GROUP BY display_folders.folder
                                                            ORDER BY display_folders.folder ASC',array($folder->folder.'/%'));
        
        if($subfolders==null)$subfolders=array();else if(!is_array($subfolders))$subfolders=array($subfolders);
        if($pictures  ==null)$pictures  =array();else if(!is_array($pictures  ))$pictures  =array($pictures  );
        ?><div id="foldercontent">
            <div id="folderinfo" >
                <h2><a href="<?=PROOT.$folder->folder?>"><?=$folder->title?></a></h2>
                <blockquote><?=$l->triggerParse('CORE',$folder->text);?></blockquote>
                <? $l->triggerHookSequentially('FolderHeader','Display',$folder); ?>
                <div id="foldercrumbs">
                    <? $crumbs=array();
                    for($i=0;$i<count($path)-1;$i++){
                       $sofar.=$path[$i].'/';
                       $crumbs[]='<a href="'.PROOT.$sofar.'">'.ucfirst($path[$i]).'</a>'; 
                    }$crumbs = array_reverse($crumbs);echo(implode('',$crumbs)); ?>
                </div>
                <div id="foldernav">
                    <? $l->triggerHookSequentially('FolderNav','Display',$folder); ?>
                    <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.upload')){ ?><a href='<?=PROOT.'upload/'.$folder->folder?>'>Upload</a><? } ?>
                    <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.manage')){ ?><a href='<?=PROOT.'manage/'.$folder->folder?>'>Manage</a><? } ?>
                    <?Toolkit::displayPager();?>
                </div>
            </div>
            <div id="folderblock">
                <a name="folder"></a>
                <? foreach($subfolders as $sfolder){
                    $sfolder->title = explode('/',$sfolder->folder);$sfolder->title = end($sfolder->title); ?>
                    <a class="folder" href="<?=PROOT.$sfolder->folder?>#folder" title="<?=$sfolder->folder?>" >
                        <img src="<?=DATAPATH.'uploads/display/res/'.$sfolder->folder.'/'.$sfolder->filename?>" />
                        <h4 class="foldertitle"><?=(strlen($sfolder->title)>12) ? substr($sfolder->title,0,12).'...' : $sfolder->title?></h4>
                    </a>
                <? } ?> 
                <? $tooltips=array();
                   foreach($pictures as $picture){ 
                    $tooltips[] = 'addToolTip($("#p'.$picture->pictureID.'"),"'.$picture->title.'");';?>
                    <a id="p<?=$picture->pictureID?>" class="picture" href="<?=PROOT.'view/'.$picture->pictureID.'-'.str_replace(' ','-',$picture->title)?>#picture">
                        <img src="<?=DATAPATH.'uploads/display/res/'.$folder->folder.'/'.$picture->filename?>" />
                    </a>
                <? } ?>
            </div>

            <script type="text/javascript">
                $(document).ready(function(){<?=implode('',$tooltips);?>});
            </script>
        </div><?
    }
    $t->closePage();
}

function displayEdit($picture){
    global $a,$t,$c,$l;
    if((!$picture->pictureID!=''&& $a->check('display.folder.'.str_replace('/','.',$picture->folder).'.upload'))||
       ( $picture->pictureID!=''&&($a->check('display.folder.'.str_replace('/','.',$picture->folder).'.manage')||$a->user->username==$picture->user))){
        $folder = DataModel::getData('display_folders','SELECT folder,text,pictures FROM display_folders WHERE folder=?',array($picture->folder));
        
        if($folder!=null){
            
            $fpath = explode('/',$folder->folder);
            $folder->title=ucfirst(str_replace('_',' ',$fpath[count($fpath)-1]));
            $path = DATAPATH.'uploads/display/src/'.$picture->folder.'/';

            if(isset($_POST['action'])){
                if($c->o['display_edit_timeout']=='')$c->o['display_edit_timeout']=60;
                if($c->o['display_thumbnail_size']=='')$c->o['display_thumbnail_size']=100;
                if(Toolkit::updateTimestamp("displayEdit",$c->o['display_edit_timeout'])){
                    Toolkit::mkdir(ROOT.$path);
                    Toolkit::mkdir(ROOT.str_replace('/src/','/res/',$path));
                    $err="";
                    $picture->text = $_POST['text'];
                    $picture->title= $_POST['title'];
                    $picture->tags = implode(',',$_POST['tags']);
                    if(strlen($_FILES['file']['name'])>20)$_FILES['file']['name']=substr($_FILES['file']['name'],0,20);

                    if($picture->pictureID!=''){
                        if(is_uploaded_file($_FILES['file']['tmp_name'])){
                            try{
                                if(@!unlink(ROOT.$path.$picture->filename)||
                                   @!unlink(ROOT.str_replace('/src/','/res/',$path).$picture->filename))
                                        $err='Failed to delete previous files! ';
                                $file = Toolkit::uploadFile('file',ROOT.$path,5500,array("image/png","image/jpg","image/jpeg","image/gif"),true,$picture->pictureID.'-'.$_FILES['file']['name'],true);
                                Toolkit::createThumbnail($file,str_replace('/src/','/res/',$file), $c->o['display_thumbnail_size'], $c->o['display_thumbnail_size'], false,true,true);
                                $picture->filename=str_replace(ROOT.$path,'',$file);
                            }catch(Exception $e){$err.=$e->getMessage();}
                        }
                        $l->triggerHookSequentially('Edit','Display',$picture);
                        $picture->saveData();
                    }else{
                        $picture->filename = 'NIL';
                        $picture->insertData();
                        try{
                            $file = Toolkit::uploadFile('file',ROOT.$path,5500,array("image/png","image/jpg","image/jpeg","image/gif"),true,$c->insertID().'-'.$_FILES['file']['name'],true);
                            Toolkit::createThumbnail($file,str_replace('/src/','/res/',$file), $c->o['display_thumbnail_size'], $c->o['display_thumbnail_size'], false,true,true);
                            $picture->pictureID=$c->insertID();
                            $picture->filename =str_replace(ROOT.$path,'',$file);
                            $l->triggerHookSequentially('Upload','Display',$picture);
                            $picture->saveData();
                            $folder->pictures.=','.$picture->pictureID;
                            $folder->saveData();
                            
                            $l->triggerPost('Display','Display',$picture->pictureID,$picture->text,'',Toolkit::url('gallery','view/'.$picture->pictureID),$picture->title);
                            header('Location: '.PROOT.'view/'.$picture->pictureID);
                        }catch(Exception $e){
                            $err.=$e->getMessage();
                            $c->query('DELETE FROM display_pictures WHERE pictureID=?',array($c->insertID()));
                        }
                    }
                }else{
                    $err='Please wait '.$c->o['display_edit_timeout'].'seconds between uploads.';
                }
            }
            
            
            $t->openPage('Picture Editor - Gallery');
            
            include(MODULEPATH.'gui/Editor.php');
            $editor = new SimpleEditor("#","Save","galleryeditor",array("default","plus"));
            if($err!='')$editor->addCustom('<div class="failure">'.$err.'</div>');
            if($picture->pictureID!='')$editor->addCustom('<a href="'.PROOT.'view/'.$picture->pictureID.'#picture"><img src="'.$path.$picture->filename.'" alt="" /></a>');
            $editor->addTextField('title', 'Title', $picture->title, 'text', 'maxlength="256" required');
            if($picture->pictureID!='')$editor->addTextField('file','Upload','','file');
            else                          $editor->addTextField('file','Upload','','file','required');
            $editor->addCustom('<br class="clear" />');
            if($picture->tags!='')$editor->addCustom('<label>Tags</label>'.Toolkit::interactiveList('tags', explode(',',$picture->tags), explode(',',$picture->tags), explode(',',$picture->tags), true, true));
            else                  $editor->addCustom('<label>Tags</label>'.Toolkit::interactiveList('tags', array(), array(), array(), true, true));
            $_POST['text']=$picture->text;
            $l->triggerHookSequentially('EditEditor','Display',$editor);
            
            ?><div id="foldercontent">
                <div id="folderinfo" >
                    <h2>Submit to: <a href="<?=PROOT.$folder->folder?>#folder"><?=$folder->title?></a></h2>
                    <blockquote><?=$l->triggerParse('CORE',$folder->text);?></blockquote>
                    <? $l->triggerHookSequentially('FolderHeader','Display',$folder); ?>
                    <div id="foldercrumbs">
                        <?$crumbs=array();
                        for($i=0;$i<count($fpath)-1;$i++){
                            $sofar.=$fpath[$i].'/';
                            $crumbs[]='<a href="'.PROOT.$sofar.'">'.ucfirst($fpath[$i]).'</a>'; 
                        }$crumbs = array_reverse($crumbs);echo(implode('',$crumbs)); ?>
                    </div>
                    <div id="foldernav">
                        <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.upload')){ ?><a href='<?=PROOT.'upload/'.$folder->folder?>'>Upload</a><? } ?>
                        <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.manage')){ ?><a href='<?=PROOT.'manage/'.$folder->folder?>'>Manage</a><? } ?>
                        <? $l->triggerHookSequentially('EditNav','Display',$folder); ?>
                    </div>
                </div>
                <?$editor->show();?>
            </div><?
        }else{
            $t->openPage('404 - Gallery');
            include(PAGEPATH.'404.php');
        }
    }else{
        $t->openPage('403 - Gallery');
        include(PAGEPATH.'403.php');
    }
    $t->closePage();
}

function displayAPIDeletePicture(){
    global $a,$l;
    $picture = DataModel::getData('display_pictures','SELECT pictureID,user,filename,folder FROM display_pictures WHERE pictureID=?',array($_POST['id']));
    if($picture==null)die('No such picture found');
    if($picture->owner != $a->user->username && !$a->check('display.admin.*'))die('Insufficient privileges.');
    $l->triggerHookSequentially('DeletePicture','Display',$picture);
    unlink(ROOT.DATAPATH.'uploads/display/res/'.$picture->folder.'/'.$picture->filename);
    unlink(ROOT.DATAPATH.'uploads/display/src/'.$picture->folder.'/'.$picture->filename);
    $picture->deleteData();
    die('Picture deleted!');
}
function displayAPISaveData(){
    global $a,$l;
    if(!$a->check('display.folder.*'))die('Insufficient privileges.');
    foreach($_POST as $folder => $pictures){
        if($a->check('display.folder.'.str_replace('/','.',$folder).'.manage')){
            $f = DataModel::getData('display_folders','SELECT folder FROM display_folders WHERE folder LIKE ?',array($folder));
            if($f!=null){
                $f->pictures = implode(',',$pictures);
                $f->saveData();

                foreach($pictures as $picture){
                    $picture = DataModel::getData('display_pictures','SELECT pictureID,folder,filename FROM display_pictures WHERE pictureID=?',array($picture));
                    if($picture!=null){
                        Toolkit::mkdir(ROOT.DATAPATH.'uploads/display/res/'.$folder.'/');
                        Toolkit::mkdir(ROOT.DATAPATH.'uploads/display/src/'.$folder.'/');
                        rename(ROOT.DATAPATH.'uploads/display/res/'.$picture->folder.'/'.$picture->filename,
                               ROOT.DATAPATH.'uploads/display/res/'.$folder.'/'.$picture->filename);
                        rename(ROOT.DATAPATH.'uploads/display/src/'.$picture->folder.'/'.$picture->filename,
                               ROOT.DATAPATH.'uploads/display/src/'.$folder.'/'.$picture->filename);
                        $picture->folder = $folder;
                        $picture->saveData();
                    }
                }
            }
        }
    }
    $l->triggerHook('SaveData','Display');
    die('Data Saved!');
}
function displayManage(){
    global $a,$t,$l;
    
    if($a->user->username!=''){
        $t->openPage('Manage - Gallery');
        
        $max = DataModel::getData('display_pictures','SELECT COUNT(pictureID) AS pictures FROM display_pictures WHERE user LIKE ?',array($a->user->username));
        Toolkit::sanitizePager($max->pictures);
        $pictures = DataModel::getData('display_pictures','SELECT pictureID,title,filename,folder FROM display_pictures 
                                                           WHERE user LIKE ? ORDER BY time DESC
                                                           LIMIT '.$_GET['f'].','.$_GET['s'],array($a->user->username));
        if($pictures==null)$pictures=array();else if(!is_array($pictures))$pictures=array($pictures);
        $folders = $this->getAccessibleFolders();
        
        ?><div id="folderinfo" >
            <h2>Manage</h2>
            <blockquote>Manage your pictures.</blockquote>
            <? $l->triggerHook('ManageHeader','Display'); ?>
            <div id="foldernav">
                <?Toolkit::displayPager();?>
                <a id="saver">Save</a>
                <? $l->triggerHook('ManageNav','Display'); ?>
            </div>
        </div>
        <div id="managecontent">
            <ul id="imagelist">
                <? foreach($pictures as $picture){ ?>
                    <li id="<?=$picture->pictureID?>" title="<?=$picture->title?>" class="picture">
                        <a class="delete">X</a>
                        <a href="<?=PROOT.'view/'.$picture->pictureID?>#picture">
                            <img src="<?=DATAPATH.'uploads/display/res/'.$picture->folder.'/'.$picture->filename?>" />
                        </a>
                        <h4><?=(strlen($picture->title)>15) ? substr($picture->title,0,12).'...' : $picture->title?></h4>
                    </li>
                <? } ?>
            </ul>
            <ul id="folderlist">
                <? foreach($folders as $folder){
                    $pics = explode(',',$folder->pictures);
                    $path = explode('/',$folder->folder);
                    $folder->title = ucfirst(str_replace('_',' ',$path[count($path)-1]));?>
                    
                    <li id="<?=$folder->folder?>"><a class="delete">x</a> <a class="collapse"><?=$folder->title?></a>
                        <ul class="new">
                            <? foreach($pics as $pic){
                                if($pic!=''&&is_numeric($pic)){
                                    $title='';foreach($pictures as $pict){if($pict->pictureID==$pic)$title=$pict->title;}?>

                                    <li id="P<?=$pic?>">
                                        <a class="delete">x</a>
                                        <?=(strlen($title)>53) ? substr($title,0,50).'...' : $title?>
                                    </li>
                            <? }} ?>
                        </ul>
                    </li>
                <? } ?>
            </ul>
        </div>
        <form id="dataform"></form>
        <script type="text/javascript">$(document).ready(function(){initManage();});</script><?
    }else{
        $t->openPage('403 - Gallery');
        include(PAGEPATH.'403.php');
    }
    $t->closePage();
}

function displayFolderChooser(){
    global $t;
    $folders = $this->getAccessibleFolders();
    
    $t->openPage('Upload - Gallery');
    ?><div id="folderchooser">
        <h1>Choose a folder to upload to:</h1>
        <ul>
            <? foreach($folders as $folder){ ?>
                <li><a href="<?=PROOT.'upload/'.$folder->folder?>"><?=$folder->folder?></a></li>
            <? } ?>
        </ul>
    </div>
    <?
    $t->closePage();
}

function displayPanel(){
    global $k,$a;
    ?>
    <li>Display
    <ul class="menu">
        <? if($a->check("display.admin.folders")){ ?>
        <a href="<?=$k->url("admin","Display/folders")?>"><li>Folder Management</li></a><? } ?>
        <? if($a->check("display.admin.pictures")){ ?>
        <a href="<?=$k->url("admin","Display/pictures")?>"><li>Manage Pictures</li></a><? } ?>
    </ul></li><?
}
function displayAdmin(){
    global $params,$a;
    switch($params[1]){
        case 'folders':if($a->check("display.admin.folders"))$this->displayAdminFolders();break;
        case 'pictures':if($a->check("display.admin.pictures"))$this->displayAdminPictures();break;
    }
}

function displayAdminFolders(){
    global $c;
    $folder = DataModel::getData("display_folders","SELECT * FROM display_folders WHERE folder=?",array($_POST['folder']));
    if($folder==null)$folder = DataModel::getHull("display_folders");
    
    if($_POST['action']=="Submit"){
        $folder->text=$_POST['text'];
        if($folder->folder==""){
            try{
                Toolkit::mkdir(ROOT.DATAPATH.'uploads/display/res/'.$_POST['folder']);
                Toolkit::mkdir(ROOT.DATAPATH.'uploads/display/src/'.$_POST['folder']);
                $folder->folder=$_POST['folder'];
                $folder->insertData();
                echo("<div class='success'>Folder added.</div>");
            }catch(Exception $e){
                echo("<div class='failure'>Failed to create folder!</div>");
            }
        }else{
            $folder->saveData();
            echo("<div class='success'>Folder edited.</div>");
        }
    }
    
    if($_POST['action']=="Delete"){
        try{
            Toolkit::rmdir(ROOT.DATAPATH.'uploads/display/res/'.$folder->folder);
            Toolkit::rmdir(ROOT.DATAPATH.'uploads/display/src/'.$folder->folder);
            $c->query('DELETE FROM display_pictures WHERE folder LIKE ?',array($folder->folder));
            $folder->deleteData();
            echo("<div class='success'>Folder and all its pictures deleted.</div>");
        }catch(Exception $e){
            echo("<div class='failure'>Failed to delete pictures!</div>");
        }
    }
    
    $folders = DataModel::getData("display_folders","SELECT * FROM display_folders ORDER BY folder DESC");
    Toolkit::assureArray($folders);
    ?><form class="box" method="post" action="#">
        Path: <input type="text" name="folder" value="<?=$folder->folder?>" placeholder="path/name" maxlength="128" autocomplete="off" /><br />
        <textarea name="text"><?=$folder->text?></textarea><br />
        <input type="submit" name="action" value="Submit" />
    </form> 
    <div class="box fullwidth">
        <table>
            <thead>
                <tr>
                    <th>Path</th>
                    <th>Text</th>
                    <th>Pictures</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($folders as $folder){ ?>
                    <tr>
                        <td><?=$folder->folder?></td>
                        <td><?=$folder->text?></td>
                        <td><?=$folder->pictures?></td>
                        <td><form method="post" action="#">
                                <input type="hidden" name="folder" value="<?=$folder->folder?>" />
                                <input type="submit" name="action" value="Edit" />
                                <input type="submit" name="action" value="Delete" />
                            </form></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    </div><?
}

function displayAdminPictures(){
    
}

function getAccessibleFolders(){
    //Create permissions search.
    global $a;
    if(array_key_exists('*', $a->udPTree)){
        $q='folder LIKE ?';
        $qd=array('%');
    }else if($a->check('display.folder.*')){
        foreach($a->udPTree['display'] as $branch){
            if($branch[0]=='folder'){
                $q.='OR folder LIKE ? ';
                $qd[]=str_replace('*','%',implode('/',array_slice($branch,1)));
            }
        }
        $q = substr($q,2);
    }else{$q('1=-1');$qd=array();}
    $folders = DataModel::getData('display_folders','SELECT folder,pictures FROM display_folders WHERE '.$q,$qd);
    if($folders==null)$folders=array();else if(!is_array($folders))$folders=array($folders);
    return $folders;
}

}
?>