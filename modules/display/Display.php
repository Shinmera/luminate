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
    if($params[0]=='view'){
        if(strpos($params[1],'-')!==FALSE)$params[1]=substr($params[1],0,strpos($params[1],'-'));
        $this->displayPicture($params[1]);
    }else
        $this->displayFolder($param);
}

function displayPicture($pictureID){
    global $t,$l;
    $picture = DataModel::getData('display_pictures','SELECT folder,title,text,time,tags,filename,user FROM display_pictures WHERE pictureID=?',array($pictureID));
    if($picture!=null){
        $t->openPage('404 - Gallery');
        echo('<br /><div class="failure">The picture you\'re looking for doesn\'t exist.</div>');
    }else{
        $folder = DataModel::getData('display_folders','SELECT pictures FROM display_folders WHERE folder=?',array($picture->folder));
        $order = explode(',',$folder->pictures);
        $cpos = array_search($pictureID,$order);
        $path = DATAPATH.'uploads/display/'.$picture->folder.'/'.$picture->filename;
        
        $t->openPage($picture->title.' - Gallery');
        ?><div id="pictureblock" >
            <a name="picture"></a>
            <img id="image" src="<?=Toolkit::url('dd',$path)?>" />
            <div id="picturenav">
                <a href="<?=PROOT.'view/'.$order[0]?>#picture" id="ll">&ll;</a>
                <a href="<?=PROOT.'view/'.$order[$cpos-1]?>#picture" id="lp">&lt;</a>
                <a href="<?=PROOT.'view/'.$order[$cpos]?>#picture" id="cc">=</a>
                <a href="<?=PROOT.'view/'.$order[$cpos+1]?>#picture" id="rn">&gt;</a>
                <a href="<?=PROOT.'view/'.$order[count($order)-1]?>#picture" id="rf">&gg;</a>
                <a href="<?=PROOT.$picture->folder?>#folder" id="ff">&rarrhk;</a>
            </div>
            <div id="pictureinfoshort">
                <h4>Info:</h4>
                ID: <?=$pictureID?><br />
                Uploader: <?=Toolkit::getUserPage($picture->user)?><br />
                Date: <?=Toolkit::toDate($picture->time);?><br />
                Folder: <?=$picture->folder?><br />
                
                <? if(file_exists(ROOT.$path)){
                    $size = filesize(ROOT.$path);$type = Toolkit::getImageType(ROOT.$path);
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
            <h3>Description</h3>
            <article>
                <?=$l->triggerParse('CORE',$picture->text);?>
            </article>
        </div><?
    }
    $t->closePage();
}

function displayFolder($folderpath){
    global $t,$l;
    if(substr($folderpath,strlen($folderpath)-1)=='/')$folderpath=substr($folderpath,0,strlen($folderpath)-1);
    $page = substr($folderpath,strrpos($folderpath,'/')+1);
    if(is_numeric($page)){
        $folderpath = substr($folderpath,0,strrpos($folderpath,'/'));
    }else $page=0;
    
    $folder = DataModel::getData('display_folders','SELECT folder,text,pictures FROM display_folders WHERE folder LIKE ?',array($folderpath));
    if($folder==null){
        $t->openPage('404 - Gallery');
        echo('<br /><div class="failure">The folder you\'re looking for doesn\'t exist.</div>');
    }else{
        $t->openPage($folder->folder.' - Gallery');
        $path = explode('/',$folder->folder);
        $folder->title=$path[count($path)-1];
        $subfolders = DataModel::getData('display_folders','SELECT display_folders.folder,pictures.filename 
                                                            FROM display_folders LEFT JOIN ( 
                                                                    SELECT s1.filename,s1.folder FROM display_pictures AS s1
                                                                    LEFT JOIN display_pictures AS s2
                                                                        ON s1.folder = s2.folder AND s1.time > s2.time
                                                                ) AS pictures
                                                                USING(folder)
                                                            WHERE folder LIKE ?',array($folder->folder.'%'));
        $pictures = DataModel::getData('display_folders','SELECT title,filename FROM display_pictures WHERE folder LIKE ?',array($folder->folder));
        $order = explode(',',$folder->pictures);
        ?><div id="foldercontent">
            <div id="folderinfo" >
                <h3><?=ucfirst($folder->title)?></h3>
                <blockquote>
                    <?=$l->triggerParse('CORE',$folder->text);?>
                </blockquote>
                <div id="foldercrumbs">
                    <? for($i=0;$i<count($path)-1;$i++){
                       $sofar.=$path[$i].'/';
                       $crumbs[]='<a href="'.PROOT.$sofar.'">'.ucfirst($path[$i]).'</a>'; 
                    }$crumbs = array_reverse($crumbs);echo(implode('',$crumbs)); ?>
                </div>
                <div id="foldernav">
                    <a href="">1</a>
                </div>
            </div>
            <div id="folderblock">
                <? foreach($subfolders as $sfolder){ ?>
                    <div class="folder">
                        <img src="<?=DATAPATH.'uploads/display/'.$sfolder->folder.'/'.$folder->filename?>" />
                        <h4 class="foldertitle">Mithent</h4>
                    </div>
                <? } ?> 
                <div id="1" class="picture"><img src="<?=DATAPATH.'uploads/display/'.$folder->folder.'/'.$folder->filename?>" /></div>
            </div>

            <script type="text/javascript">
                $(document).ready(function(){
                    addToolTip($("#1"),'Suiseiseki 1');
                    addToolTip($("#2"),'Suiseiseki 2');
                    addToolTip($("#3"),'Suiseiseki 3');
                });
            </script>
        </div><?
    }
    $t->closePage();
}

function displayAdmin(){
    
}

}
?>