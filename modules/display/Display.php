<? class Display{
public static $name="Display";
public static $author="NexT";
public static $version=0.01;
public static $short='display';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

//Access: display.folder.$folder
//Default access is display.folder.username
//Other access is managed through folders.
//Subfolders are separated by dots to conform with the perms.
//Pictures are saved in ./data/uploads/display/$folder/$filename

function buildMenu($menu){return $menu;}

function displayPage(){
    global $t,$params,$param;
    $t->js[]='/display.js';
    $t->css[]='/display.css';
    switch($params[0]){
        case 'view':
            if(strpos($params[1],'-')!==FALSE)$params[1]=substr($params[1],0,strpos($params[1],'-'));
            $this->displayPicture($params[1]);
            break;
        case 'manage':
            
            break;
        case 'upload':
            
            break;
        case 'edit':
            
            break;
        default: $this->displayFolder($param);break;
    }
}

function displayPicture($pictureID){
    global $t,$l,$a;
    $picture = DataModel::getData('display_pictures','SELECT folder,title,text,time,tags,filename,user FROM display_pictures WHERE pictureID=?',array($pictureID));
    if($picture==null){
        $t->openPage('404 - Gallery');
        echo('<br /><div class="failure">The picture you\'re looking for doesn\'t exist.</div>');
    }else{
        $folder = DataModel::getData('display_folders','SELECT folder,text,pictures FROM display_folders WHERE folder=?',array($picture->folder));
        $order = explode(',',$folder->pictures);
        $cpos = array_search($pictureID,$order);
        $path = DATAPATH.'uploads/display/'.$picture->folder.'/'.$picture->filename;
        $fpath = explode('/',$folder->folder);
        $folder->title=$fpath[count($fpath)-1];
        
        $t->openPage($picture->title.' - Gallery');
        ?><div id="foldercontent">
            <div id="folderinfo" >
                <h3><?=ucfirst($folder->title)?></h3>
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
            <div id="pictureblock" >
                <a name="picture"></a>
                <img id="image" src="<?=Toolkit::url('dd',$path)?>" />
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
                    <a href="<?=PROOT.$picture->folder?>#folder" id="ff">&rarrhk;</a>
                    <? if($a->check('display.folder.'.str_replace('/','.',$picture->folder).'.manage')||$a->user->username==$picture->user){?>
                        <a href="<?=PROOT.'edit/'.$pictureID?>">Edit</a>
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
        echo('<br /><div class="failure">The folder you\'re looking for doesn\'t exist.</div>');
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
                <h2><?=ucfirst($folder->title)?></h2>
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
                        <img src="<?=DATAPATH.'uploads/display/'.$sfolder->folder.'/'.$sfolder->filename?>" />
                        <h4 class="foldertitle"><?=$sfolder->folder?></h4>
                    </div>
                <? } ?> 
                <? $tooltips=array();
                   foreach($pictures as $picture){ 
                    $tooltips[] = 'addToolTip($("#p'.$picture->pictureID.'"),"'.$picture->title.'");';?>
                    <a id="p<?=$picture->pictureID?>" class="picture" href="<?=PROOT.'view/'.$picture->pictureID.'-'.str_replace(' ','-',$picture->title)?>">
                        <img src="<?=DATAPATH.'uploads/display/'.$folder->folder.'/'.$picture->filename?>" />
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

function displayAdmin(){
    
}

}
?>