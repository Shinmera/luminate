<? class Reader extends Module{
public static $name="Reader";
public static $author="NexT";
public static $version=0.01;
public static $short='';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

function displayPage(){
    global $params;
    if(!is_numeric($params[1])){
        if(strpos($params[1],'-')!==FALSE)$params[1]=substr($params[1],0,strpos($params[1],'-'));
        if(!is_numeric($params[1]))$params[1]=-1;
    }
    switch($params[0]){
        case 'f':$this->displayFolder($params[1]);break;
        case 'p':$this->displayEntry($params[1]);break;
        case 'e':$this->displayEdit($params[1]);break;
        default: break;
    }
}

function displayHead(){
    global $a;
    ?><div id='pageNav'>
        <div style='display:inline-block'>
            <h1 class='sectionheader'>Blog</h1>
        </div>
        <div class='tabs'>
            <a href='<?=PROOT?>' class='tab <? if($params[0]=='')echo('activated'); ?>'>Home</a>
            <a href='<?=PROOT?>f' class='tab <? if($params[0]=='f')echo('activated'); ?>'>Folders</a>
            <? if($a->check('reader.folder.*')){ ?>
                <a href='<?=PROOT?>e' class='tab <? if($params[0]=='e')echo('activated'); ?>'>Edit</a>
            <? } ?>
        </div>
    </div><?
}

function displayEntry($entryID){
    global $t;
    $entry = DataModel::getData('','SELECT *,bl_folders.title FROM bl_entries LEFT JOIN bl_folders ON FID=folderID WHERE entryID=?',array($entryID));
    if($entry==null){
        $t->openPage('404 - Blog');
        $this->displayHead();
        include(PAGEPATH.'404.php');
    }else{
        
    }
    $t->closePage();
}

function displayFolder($folderID){
    global $t;
    if($folderID==''){
        
    }else{
        $folder = DataModel::getData('','SELECT * FROM bl_folders WHERE folderID=?',array($folderID));
        if($folder==null){
            $t->openPage('404 - Blog');
            $this->displayHead();
            include(PAGEPATH.'404.php');
        }else{
            
        }
    }
    $t->closePage();
}

function displayEdit($entryID){
    global $t;
    $entry = DataModel::getData('bl_entries','SELECT *,bl_folders.title FROM bl_entries LEFT JOIN bl_folders ON FID=folderID WHERE entryID=?',array($entryID));
    if($entry==null){
        $entry = DataModel::getHull('bl_entries');
        $t->openPage('Create a new entry - Blog');
    }else{
        $t->openPage('Edit entry `'.$entry->title.'` - Blog');
    }
    
    if($_POST['action']=='submit'){
        
    }
    
    $folders = DataModel::getData('','SELECT folderID,title FROM bl_folders');
    
    $this->displayHead();
    
    include(MODULEPATH.'gui/Editor.php');
    $editor = new SimpleEditor();
    $editor->addCustom('<h2>Edit a blog post</h2>');
    $editor->addTextField('title', 'Title', $entry->title,'text','required maxlength="128"','width:100%;');
    $editor->addCustom('<label>Tags: </label>'.Toolkit::interactiveList('tags', array(), array(), explode(',',$entry->tags), true,true));
    $editor->addCustom('<label>Extract: </label><br /><textarea name="short">'.$entry->short.'</textarea>');
    $editor->show();
    $_POST['text']=$entry->text;
    
    ?><style>#editor{display:block;border:none;background:none;margin:10px;}
             #editor textarea{width:100%;box-sizing:border-box;}</style><?
    $t->closePage();
}

function displayPanel(){
    global $k,$a;
    ?>
    <li>Reader
    <ul class="menu">
        <? if($a->check("reader.admin.edit")){ ?>
        <a href="<?=$k->url("admin","Reader/entries")?>"><li>Entries Management</li></a><? } ?>
        <? if($a->check("reader.admin.folders")){ ?>
        <a href="<?=$k->url("admin","Reader/folders")?>"><li>Organize Folders</li></a><? } ?>
    </ul></li><?
}
function displayAdmin(){
    
}

function displayAdminEntries(){
    
}

function displayAdminFolders(){
    
}


}
?>