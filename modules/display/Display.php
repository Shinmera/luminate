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
    $t->closePage();
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
}

function displayFolder($folderpath){
    global $t;
    $t->openPage(' - Gallery');
}

function displayAdmin(){
    
}

}
?>