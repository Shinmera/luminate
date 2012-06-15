<? class Display{
public static $name="Display";
public static $author="NexT";
public static $version=0.01;
public static $short='display';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

//TODO: Add hooks
//TODO: implement management
//TODO: Add ability to delete pic on its edit page

function buildMenu($menu){return $menu;}

function displayPage(){
    global $t,$a,$params,$param;
    $t->js[]='/display.js';
    $t->css[]='/display.css';
    switch($params[0]){
        case 'view':
            if(strpos($params[1],'-')!==FALSE)$params[1]=substr($params[1],0,strpos($params[1],'-'));
            $this->displayPicture($params[1]);
            break;
        case 'upload':
            if(substr($param,strlen($param)-1)=='/')$param=substr($param,0,strlen($param)-1);
            $picture = DataModel::getHull('display_pictures');
            $picture->folder=strtolower(str_replace('upload/','',$param));
            $picture->time=time();
            $picture->user=$a->user->username;
            $this->displayEdit($picture);break;
            break;
        case 'edit':
            $picture = DataModel::getData('display_pictures','SELECT * FROM display_pictures WHERE pictureID=?',array($params[1]));
            $this->displayEdit($picture);break;
            break;
        case 'manage':$this->displayManage();break;
        default: $this->displayFolder($param);break;
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
        $folder->title=$fpath[count($fpath)-1];
        
        $t->openPage($picture->title.' - Gallery');
        ?><div id="foldercontent">
            <div id="folderinfo" >
                <h3><a href="<?=PROOT.$picture->folder?>"><?=ucfirst($folder->title)?></a></h3>
                <blockquote><?=$l->triggerParse('CORE',$folder->text);?></blockquote>
                <div id="foldercrumbs">
                    <? for($i=0;$i<count($fpath)-1;$i++){
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
                </div>
            </div>
            <div id="pictureinfo">
                <h2><?=$picture->title?></h2>
                <article>
                    <?=$l->triggerParse('CORE',$picture->text);?>
                </article>
            </div>
            <script type="text/javascript">$(document).ready(function(){initPicture()});</script>
        </div><?
    }
    $t->closePage();
}

function displayFolder($folderpath){
    global $t,$l,$a;
    if(substr($folderpath,strlen($folderpath)-1)=='/')$folderpath=substr($folderpath,0,strlen($folderpath)-1);
    
    $folder = DataModel::getData('display_folders','SELECT folder,text FROM display_folders WHERE folder LIKE ?',array($folderpath));
    if($folder==null){
        $t->openPage('404 - Gallery');
        include(PAGEPATH.'404.php');
    }else{
        $t->openPage($folder->folder.' - Gallery');
        $path = explode('/',$folder->folder);
        $folder->title=$path[count($path)-1];
        
        $max = DataModel::getData('display_pictures','SELECT COUNT(pictureID) AS pictures FROM display_pictures WHERE folder LIKE ?',array($folder->folder));
        Toolkit::sanitizePager($max->pictures);
        $pictures = DataModel::getData('display_pictures','SELECT pictureID,title,filename FROM display_pictures 
                                                           WHERE folder LIKE ? ORDER BY time DESC
                                                           LIMIT '.$_GET['f'].','.$_GET['t'],array($folder->folder));
        
        $subfolders = DataModel::getData('display_folders','SELECT display_folders.folder,pictures.filename 
                                                            FROM display_folders LEFT JOIN ( 
                                                                    SELECT s1.filename,s1.folder FROM display_pictures AS s1
                                                                    LEFT JOIN display_pictures AS s2
                                                                        ON s1.folder = s2.folder AND s1.time > s2.time
                                                                ) AS pictures
                                                                USING(folder)
                                                            WHERE folder LIKE ? ORDER BY display_folders.folder ASC',array($folder->folder.'/%'));
        
        if($subfolders==null)$subfolders=array();else if(!is_array($subfolders))$subfolders=array($subfolders);
        if($pictures  ==null)$pictures  =array();else if(!is_array($pictures  ))$pictures  =array($pictures  );
        ?><div id="foldercontent">
            <div id="folderinfo" >
                <h2><a href="<?=PROOT.$folder->folder?>"><?=ucfirst($folder->title)?></a></h2>
                <blockquote><?=$l->triggerParse('CORE',$folder->text);?></blockquote>
                <div id="foldercrumbs">
                    <? for($i=0;$i<count($path)-1;$i++){
                       $sofar.=$path[$i].'/';
                       $crumbs[]='<a href="'.PROOT.$sofar.'">'.ucfirst($path[$i]).'</a>'; 
                    }$crumbs = array_reverse($crumbs);echo(implode('',$crumbs)); ?>
                </div>
                <div id="foldernav">
                    <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.upload')){ ?><a href='<?=PROOT.'upload/'.$folder->folder?>'>Upload</a><? } ?>
                    <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.manage')){ ?><a href='<?=PROOT.'manage/'.$folder->folder?>'>Manage</a><? } ?>
                    <?Toolkit::displayPager();?>
                </div>
            </div>
            <div id="folderblock">
                <a name="folder"></a>
                <? foreach($subfolders as $sfolder){ ?>
                    <div class="folder">
                        <img src="<?=DATAPATH.'uploads/display/res/'.$sfolder->folder.'/'.$sfolder->filename?>" />
                        <h4 class="foldertitle"><?=$sfolder->folder?></h4>
                    </div>
                <? } ?> 
                <? $tooltips=array();
                   foreach($pictures as $picture){ 
                    $tooltips[] = 'addToolTip($("#p'.$picture->pictureID.'"),"'.$picture->title.'");';?>
                    <a id="p<?=$picture->pictureID?>" class="picture" href="<?=PROOT.'view/'.$picture->pictureID.'-'.str_replace(' ','-',$picture->title)?>">
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
            $folder->title=$fpath[count($fpath)-1];
            $path = DATAPATH.'uploads/display/src/'.$picture->folder.'/';

            if(isset($_POST['action'])){
                if($c->o['display_edit_timeout']=='')$c->o['display_edit_timeout']=60;
                if($c->o['display_thumbnail_size']=='')$c->o['display_thumbnail_size']=100;
                if(Toolkit::updateTimestamp("displayEdit",$c->o['display_edit_timeout'])){
                    if(!file_exists(ROOT.$path)){
                        $oldumask = umask(0);
                        mkdir(ROOT.$path,0777,true);
                        mkdir(ROOT.str_replace('/src/','/res/',$path),0777,true);
                        umask($oldumask);
                    }
                    $err="";
                    $picture->text = $_POST['text'];
                    $picture->title= $_POST['title'];
                    $picture->tags = implode(',',$_POST['tags']);
                    if(strlen($_FILES['file']['name'])>50)$_FILES['file']['name']=substr($_FILES['file']['name'],0,50);

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
                        $picture->saveData();
                    }else{
                        $picture->filename = 'NIL';
                        $picture->insertData();
                        try{
                            $file = Toolkit::uploadFile('file',ROOT.$path,5500,array("image/png","image/jpg","image/jpeg","image/gif"),false,$c->insertID().'-'.$_FILES['file']['name'],true);
                            Toolkit::createThumbnail($file,str_replace('/src/','/res/',$file), $c->o['display_thumbnail_size'], $c->o['display_thumbnail_size'], false,true,true);
                            $picture->pictureID=$c->insertID();
                            $picture->filename =str_replace(ROOT.$path,'',$file);
                            $picture->saveData();
                            $folder->pictures.=','.$picture->pictureID;
                            $folder->saveData();
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
            if($picture->pictureID!='')$editor->addCustom('<a href="'.PROOT.'view/'.$picture->pictureID.'"><img src="'.$path.$picture->filename.'" alt="" /></a>');
            $editor->addTextField('title', 'Title', $picture->title, 'text', 'maxlength="256" required');
            if($picture->pictureID!='')$editor->addTextField('file','Upload','','file');
            else                          $editor->addTextField('file','Upload','','file','required');
            $editor->addCustom('<br class="clear" />');
            if($picture->tags!='')$editor->addCustom('<label>Tags</label>'.Toolkit::interactiveList('tags', explode(',',$picture->tags), explode(',',$picture->tags), explode(',',$picture->tags), true, true));
            else                  $editor->addCustom('<label>Tags</label>'.Toolkit::interactiveList('tags', array(), array(), array(), true, true));
            $_POST['text']=$picture->text;
            
            ?><div id="foldercontent">
                <div id="folderinfo" >
                    <h2>Submit to: <a href="<?=PROOT.$folder->folder?>"><?=ucfirst($folder->title)?></a></h2>
                    <blockquote><?=$l->triggerParse('CORE',$folder->text);?></blockquote>
                    <div id="foldercrumbs">
                        <? for($i=0;$i<count($fpath)-1;$i++){
                            $sofar.=$fpath[$i].'/';
                            $crumbs[]='<a href="'.PROOT.$sofar.'">'.ucfirst($fpath[$i]).'</a>'; 
                        }$crumbs = array_reverse($crumbs);echo(implode('',$crumbs)); ?>
                    </div>
                    <div id="foldernav">
                        <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.upload')){ ?><a href='<?=PROOT.'upload/'.$folder->folder?>'>Upload</a><? } ?>
                        <? if($a->check('display.folder.'.str_replace('/','.',$folder->folder).'.manage')){ ?><a href='<?=PROOT.'manage/'.$folder->folder?>'>Manage</a><? } ?>
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
    global $a;
    $picture = DataModel::getData('display_pictures','SELECT user,filename,folder FROM display_pictures WHERE pictureID=?',$_POST['id']);
    if($picture==null)die('No such picture found');
    if($picture->owner != $a->user->username && !$a->check('display.admin.*'))die('Insufficient privileges!');
    unlink(ROOT.DATAPATH.'uploads/display/res/'.$picture->folder.'/'.$picture->filename);
    unlink(ROOT.DATAPATH.'uploads/display/src/'.$picture->folder.'/'.$picture->filename);
    $picture->deleteData();
    die('Picture deleted!');
}
function displayAPISaveData(){
    
}
function displayManage(){
    global $a,$t;
    
    if($a->user->username!=''){
        $t->openPage('Manage - Gallery');
        
        $max = DataModel::getData('display_pictures','SELECT COUNT(pictureID) AS pictures FROM display_pictures WHERE user LIKE ?',array($a->user->username));
        Toolkit::sanitizePager($max->pictures);
        $pictures = DataModel::getData('display_pictures','SELECT pictureID,title,filename,folder FROM display_pictures 
                                                           WHERE user LIKE ? ORDER BY time DESC
                                                           LIMIT '.$_GET['f'].','.$_GET['t'],array($a->user->username));
        if($folders==null)$folders=array();else if(!is_array($folders))$folders=array($folders);
        
        //Create permissions search.
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
        
        ?><div id="folderinfo" >
            <h2>Manage</h2>
            <blockquote>Manage your pictures.</blockquote>
            <div id="foldernav">
                <?Toolkit::displayPager();?>
                <a id="saver">Save</a>
            </div>
        </div>
        <form id="managecontent">
            <ul id="imagelist">
                <? foreach($pictures as $picture){ ?>
                    <li id="<?=$picture->pictureID?>" title="<?=$picture->title?>" class="picture">
                        <a>X</a>
                        <img src="<?=DATAPATH.'uploads/display/res/'.$picture->folder.'/'.$picture->filename?>" />
                    </li>
                <? } ?>
            </ul>
            <ul id="folderlist">
                <? foreach($folders as $folder){
                    $pics = explode(',',$folder->pictures);
                    $path = explode('/',$folder->folder);
                    $folder->title = $path[count($path)-1];?>
                    
                    <li id="<?=$folder->title?>"><a class="delete">x</a> <a class="collapse"><?=ucfirst($folder->title)?></a>
                        <ul class="new">
                            <? foreach($pics as $pic){
                                $title='';foreach($pictures as $pict){if($pict->pictureID==$pic)$title=$pict->title;}?>
                                
                                <li id="P<?=$pic?>"><a class="delete">x</a><?=$title?></li>
                            <? } ?>
                        </ul>
                    </li>
                <? } ?>
            </ul>
        </form>
        <script type="text/javascript">$(document).ready(function(){initManage();});</script><?
    }else{
        $t->openPage('403 - Gallery');
        include(PAGEPATH.'403.php');
    }
    $t->closePage();
}

function displayAdmin(){
    
}

}
?>