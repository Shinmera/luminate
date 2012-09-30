<? class Reader extends Module{
public static $name="Reader";
public static $author="NexT";
public static $version=2.01;
public static $short='reader';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

function displayPage(){
    global $params,$t,$SUPERIORPATH;
    $t->css[]='/reader.css';
    if(!is_numeric($params[1])){
        if(strpos($params[1],'-')!==FALSE)$params[1]=substr($params[1],0,strpos($params[1],'-'));
        if(!is_numeric($params[1]))$params[1]=-1;
    }
    $SUPERIORPATH=$params[0].'/'.$params[1];
    switch($params[0]){
        case 'f':$this->displayFolder($params[1]);break;
        case 'p':$this->displayEntry($params[1]);break;
        case 'e':$this->displayEdit($params[1]);break;
        default: $this->displayHome();break;
    }
}

function displayHead(){
    global $a,$params;
    ?><div id='pageNav'>
        <div style='display:inline-block'>
            <a name="blog" href="<?=PROOT?>" >
                <h1 class='sectionheader'>Blog</h1>
            </a>
        </div>
        <div class='tabs'>
            <a href='<?=PROOT?>' class='tab <? if($params[0]==''||$params[0]=='p')echo('activated'); ?>'>Articles</a>
            <a href='<?=PROOT?>f' class='tab <? if($params[0]=='f')echo('activated'); ?>'>Folders</a>
            <? if($a->check('reader.folder.*')){ ?>
                <a href='<?=PROOT?>e/<?=($params[0]=='p')?$params[1]:''?>' class='tab <? if($params[0]=='e')echo('activated'); ?>'>Edit</a>
            <? } ?>
        </div>
    </div><?
}

function displayHome(){
    global $t,$l,$a;
    
    $max = DataModel::getData('','SELECT COUNT(entryID) AS max FROM bl_entries');
    Toolkit::sanitizePager($max->max,array(),'',10);
    $t->openPage('Blog Home');
    $this->displayHead();
    ?>
    <div id="folderhead">
        <h2>Blog Home</h2>
        <?=$l->triggerHook('folderHead','Reader');?>
        <?=Toolkit::displayPager()?>
        <br style="clear:left;" />
    </div>
    <?=$l->triggerHook('folderTop','Reader');?>
    

    <? $userID = ($a->user!=null)? $a->user->userID : -1;
    $entries = DataModel::getData('', 'SELECT b.*,f.title AS ftitle,u.displayname,u.filename 
                                          FROM bl_entries as b
                                          LEFT JOIN bl_folders AS f ON FID=folderID 
                                          LEFT JOIN ud_users AS u ON owner=userID 
                                          WHERE b.published=1 OR b.owner=?
                                          ORDER BY time DESC LIMIT '.$_GET['f'].','.$_GET['s'],
                                          array($userID));
    Toolkit::assureArray($entries);
    foreach($entries as $entry){ ?>
        <article class="entry fullEntry <?=($entry->published==1)? '' : 'unpublished'?>">
            <div class="bloghead">
                <?=Toolkit::getUserAvatar($entry->displayname, $entry->filename,false,75)?>
                <h2><a href="<?=PROOT.'p/'.$entry->entryID.'-'.Toolkit::makeUrlReady($entry->title)?>#blog"><?=$entry->title?></a></h2>
                in <a href="<?=PROOT.'f/'.$entry->FID.'-'.Toolkit::makeUrlReady($entry->ftitle)?>"><?=$entry->ftitle?></a><br />
                Posted on <?=Toolkit::toDate($entry->time)?> by <?=Toolkit::getUserPage($entry->displayname)?>.
                <?=$l->triggerHook('entryHead','Reader',$entry);?>
                <br style="clear:left;" />
            </div>
            <blockquote>
                <?=$l->triggerPARSE('Reader',$entry->short);?>
                <br style="clear:both;" />
            </blockquote>
        </article>
    <? } ?>

    <div id="folderfoot">
        <? $l->triggerHook('folderFoot','Reader'); ?>
    </div>
    <?
    $t->closePage();
}

function displayEntry($entryID){
    global $t,$l,$a;
    $userID = ($a->user!=null)? $a->user->userID : -1;
    $entry = DataModel::getData('','SELECT b.*,f.title AS ftitle,u.displayname,u.filename FROM bl_entries as b
                                    LEFT JOIN bl_folders AS f ON FID=folderID 
                                    LEFT JOIN ud_users AS u ON owner=userID
                                    WHERE entryID=? AND (b.published=1 OR b.owner=?)',
                                    array($entryID,$userID));
    if($entry==null){
        $t->openPage('404 - Blog');
        $this->displayHead();
        include(PAGEPATH.'404.php');
    }else{
        $t->openPage($entry->title.' - Blog');
        $this->displayHead();
        ?>
        <div id="bloghead">
            <?=Toolkit::getUserAvatar($entry->displayname, $entry->filename,false,75)?>
            <h2><a href="<?=PROOT.'p/'.$entryID.'-'.Toolkit::makeUrlReady($entry->title)?>#blog"><?=$entry->title?></a></h2>
            in <a href="<?=PROOT.'f/'.$entry->FID.'-'.Toolkit::makeUrlReady($entry->ftitle)?>"><?=$entry->ftitle?></a><br />
            Posted on <?=Toolkit::toDate($entry->time)?> by <?=Toolkit::getUserPage($entry->displayname)?>.
            <?=$l->triggerHook('entryHead','Reader',$entry);?>
            <br style="clear:left;" />
        </div>
        <?=$l->triggerHook('entryTop','Reader',$entry);?>
        <article id="article" class="entry">
            <ul id="articleButtons">
                <li id="fontSmaller" title="Smaller font"><i class="icon-font"></i></li>
                <li id="fontLarger" title="Larger font"><i class="icon-font"></i></li>
                <li id="fontInvert" title="Invert colours"><i class="icon-font"></i></li>
                <li id="sizeToggle" title="Change width"><i class="icon-resize-full"></i></li>
                <? if($entry->published==0){ ?>
                    <li id="unpublished" title="Publish article">
                        <a href="<?=PROOT.'e/'.$entryID?>" class="icon-exclamation-sign"></a>
                    </li>
                <? } ?>
            </ul>
            <blockquote>
                <?=$l->triggerPARSE('Reader',$entry->short);?><br />
                <?=$l->triggerPARSE('Reader',$entry->subject);?>
                <br style="clear:both;" />
            </blockquote>
        </article>
        <div id="blogfoot">
            <? $l->triggerHook('entryFoot','Reader',$entry); ?>
        </div>
        <script type="text/javascript">
            $(function(){
                $("#fontSmaller").click(function(){
                    curSize = $("#article blockquote").css("font-size").replace('px','').replace('pt','');
                    $("#article blockquote").css("font-size",curSize*0.8 + "px");
                });
                $("#fontLarger").click(function(){
                    curSize = $("#article blockquote").css("font-size").replace('px','').replace('pt','');
                    $("#article blockquote").css("font-size",curSize*1.2 + "px");
                });
                $("#fontInvert").click(function(){
                    $("#content").toggleClass("invert");
                });
                $("#sizeToggle").click(function(){
                    $("#article").toggleClass("fullEntry");
                    $("#sizeToggle i").toggleClass("icon-resize-full");
                    $("#sizeToggle i").toggleClass("icon-resize-small");
                });
            });
        </script>
        <?
    }
    $t->closePage();
}

function displayFolder($folderID){
    global $t,$l,$a;
    if($folderID==-1){
        $t->openPage('Folders - Blog');
        $this->displayHead();
        ?><div id="folderhead">
            <h2>Folder Overview</h2><br />
            <?=$l->triggerHook('folderHead','Reader',$folder);?>
            <br style="clear:left;" />
        </div>
        <?=$l->triggerHook('folderTop','Reader',$folder);?>
        <? $folders = DataModel::getData('', 'SELECT folderID,f.title,text,COUNT(entryID) AS count
                                              FROM bl_folders AS f LEFT JOIN bl_entries ON folderID=FID 
                                              GROUP BY folderID ORDER BY title DESC');
        Toolkit::assureArray($folders);
        foreach($folders as $folder){ ?>
            <article class="entry fullEntry">
                <div class="bloghead">
                    <h2><a href="<?=PROOT.'f/'.$folder->folderID.'-'.Toolkit::makeUrlReady($folder->title)?>"><?=$folder->title?></a></h2>
                    ( <?=$folder->count?> entries )<br />
                    <blockquote>
                        <?=$l->triggerPARSE('Reader',$folder->text);?>
                        <br style="clear:both;" />
                    </blockquote>
                </div>
            </article>
        <? } ?>
        <div id="folderfoot">
            <? $l->triggerHook('folderFoot','Reader',$folder); ?>
        </div>
        <?
    }else{
        $folder = DataModel::getData('','SELECT * FROM bl_folders WHERE folderID=?',array($folderID));
        if($folder==null){
            $t->openPage('404 - Blog');
            $this->displayHead();
            include(PAGEPATH.'404.php');
        }else{
            $max = DataModel::getData('','SELECT COUNT(entryID) AS max FROM bl_entries WHERE FID=?',array($folderID));
            Toolkit::sanitizePager($max->max,array(),'',10);
            $t->openPage('Blog Home');
            $this->displayHead();
            ?>
            <div id="folderhead">
                <h2><?=$folder->title?></h2><br />
                <?=$l->triggerPARSE('Reader',$folder->text);?>
                <?=$l->triggerHook('folderHead','Reader',$folder);?>
                <?=Toolkit::displayPager()?>
                <br style="clear:left;" />
            </div>
            <?=$l->triggerHook('folderTop','Reader',$folder);?>
            <? $userID = ($a->user!=null)? $a->user->userID : -1;
            $entries = DataModel::getData('', 'SELECT b.*,f.title AS ftitle,u.displayname,u.filename FROM bl_entries as b
                                                LEFT JOIN bl_folders AS f ON FID=folderID 
                                                LEFT JOIN ud_users AS u ON owner=userID 
                                                WHERE FID=? AND (b.published=1 OR b.owner=?) 
                                                ORDER BY time DESC LIMIT '.$_GET['f'].','.$_GET['s'],
                                                array($folderID,$userID));
            Toolkit::assureArray($entries);
            foreach($entries as $entry){ ?>
                <article class="entry fullEntry <?=($entry->published==1)? '' : 'unpublished'?>">
                    <div class="bloghead">
                        <?=Toolkit::getUserAvatar($entry->displayname, $entry->filename,false,75)?>
                        <h2><a href="<?=PROOT.'p/'.$entry->entryID.'-'.Toolkit::makeUrlReady($entry->title)?>#blog"><?=$entry->title?></a></h2>
                        in <a href="<?=PROOT.'f/'.$entry->FID.'-'.Toolkit::makeUrlReady($entry->ftitle)?>"><?=$entry->ftitle?></a><br />
                        Posted on <?=Toolkit::toDate($entry->time)?> by <?=Toolkit::getUserPage($entry->displayname)?>.
                        <?=$l->triggerHook('entryHead','Reader',$entry);?>
                        <br />
                    </div>
                    <blockquote>
                        <?=$l->triggerPARSE('Reader',$entry->short);?>
                        <br style="clear:both;" />
                    </blockquote>
                </article>
            <? } ?>
            <div id="folderfoot">
                <? $l->triggerHook('folderFoot','Reader',$folder); ?>
            </div>
            <?
        }
    }
    $t->closePage();
}

function displayEdit($entryID){
    global $t,$a,$l,$c;
    $entry = DataModel::getData('bl_entries','SELECT * FROM bl_entries WHERE entryID=?',array($entryID));
    
    if(!$a->check('reader.folder.*')&&$entry->owner!==$a->user->userID){
        $t->openPage('Permission Denied - Blog');
        $this->displayHead();
        include(PAGEPATH.'403.php');
    }else{
        if($entry==null||(!$a->check('reader.mod.*')&&$entry->owner!==$a->user->userID)){
            $entry = DataModel::getHull('bl_entries');
            $t->openPage('Create a new entry - Blog');
            $new=true;
        }else{
            $t->openPage('Edit entry `'.$entry->title.'` - Blog');
            $new=false;
        }
        $this->displayHead();

        if($_POST['action']=='submit'){
            $entry->title=$_POST['title'];
            $entry->FID=$_POST['FID'];
            $entry->tags =implode(',',$_POST['tags']);
            $entry->short=$_POST['short'];
            $entry->subject=$_POST['text'];
            $entry->published=$_POST['published'];
            if($new){
                $entry->owner=$a->user->userID;
                $entry->time=time();
                $entry->insertData();
                $l->triggerPOST('Reader','Reader',$entry->FID,$entry->short,"",Toolkit::url('blog','p/'.$c->insertID()),$entry->title);
                echo('<div class="success"><a href="'.Toolkit::url('blog','p/'.$c->insertID()).'">Blog entry</a> added!</div><br />');
                $new=false;
            }else{
                $entry->saveData();
                echo('<div class="success"><a href="'.Toolkit::url('blog','p/'.$entry->entryID).'">Blog entry</a> edited!</div><br />');
            }
        }
        if($_POST['action']=='Delete'&&!$new){
            $entry->deleteData();
            echo('<div class="success">Blog entry deleted!</div>');
            $entry = DataModel::getHull('bl_entries');$new=true;
        }

        $folders = DataModel::getData('','SELECT folderID,title FROM bl_folders');
        Toolkit::assureArray($folders);
        foreach($folders as $folder){
            $fIDs[]=$folder->folderID;$fLs[]=$folder->title;
        }

        include(MODULEPATH.'gui/Editor.php');
        $editor = new SimpleEditor();
        $editor->addTextField('title', 'Title', $entry->title,'text','required maxlength="128"','width:200px;');
        $editor->addDropDown('FID', $fIDs, $fLs, 'Folder', $entry->FID);
        $editor->addCustom('<label>Tags: </label>'.Toolkit::interactiveList('tags', array(), array(), explode(',',$entry->tags), true,true));
        $editor->addCheckbox('published','Published','1',$entry->published);
        $editor->addCustom($l->triggerHookSequentially('editor','Reader'));
        $editor->addCustom('<label>Extract: </label><br /><textarea name="short">'.$entry->short.'</textarea>');
        $_POST['text']=$entry->subject;
        $editor->addExtraAction('Delete');
        $editor->show();

        ?><style>
            #editor{display:block;border:none;background:none;margin:10px;}
            #editor textarea{width:100%;box-sizing:border-box;}
            .filerButton{position:absolute;bottom:5px;right:0;font-weight:bold;}
        </style><?
    }
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
    global $params;
    switch($params[1]){
        case 'entries':$this->displayAdminEntries();break;
        case 'folders':$this->displayAdminFolders();break;
    }
}

function displayAdminEntries(){
    global $l;
    $max = DataModel::getData('','SELECT COUNT(entryID) AS max FROM bl_entries');
    Toolkit::sanitizePager($max->max,array('time','title','displayname','folder','published','tags'),'time');
    $entries = DataModel::getData('', 'SELECT b.*,f.title AS folder,u.displayname FROM bl_entries as b
                                       LEFT JOIN bl_folders AS f ON FID=folderID 
                                       LEFT JOIN ud_users AS u ON owner=userID
                                       ORDER BY '.$_GET['o'].' '.$_GET['d'].' LIMIT '.$_GET['f'].','.$_GET['s']);
    Toolkit::assureArray($entries);
    ?><div class="box fullwidth">
        <h3>Manage Blog Entries</h3>
        <table>
            <thead>
                <tr>
                    <th style="width:200px"><a href="?o=title&a=<?=!$_GET['a']?>">Title</a></th>
                    <th style="width:80px"><a href="?o=folder&a=<?=!$_GET['a']?>">Folder</a></th>
                    <th>Short</th>
                    <th style="width:200px"><a href="?o=tags&a=<?=!$_GET['a']?>">Tags</a></th>
                    <th style="width:80px"><a href="?o=displayname&a=<?=!$_GET['a']?>">Owner</a></th>
                    <th style="width:80px"><a href="?o=time&a=<?=!$_GET['a']?>">Time</a></th>
                    <th style="width:20px"><a href="?o=published&a=<?=!$_GET['a']?>">Publ</a></th>
                    <th style="width:20px"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($entries as $entry){ ?>
                    <tr>
                        <td><?=$entry->title?></td>
                        <td><?=$entry->folder?></td>
                        <td><?=$l->triggerPARSE('Reader',$entry->short)?></td>
                        <td><?=$entry->tags?></td>
                        <td><?=$entry->displayname?></td>
                        <td><?=Toolkit::toDate($entry->time)?></td>
                        <td><?=$entry->published?></td>
                        <td><a href="<?=Toolkit::url('blog','e/'.$entry->entryID)?>" class="button">Edit</a></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
     </div><?
}

function displayAdminFolders(){
    global $l,$c;
    
    $folder = DataModel::getData('bl_folders','SELECT * FROM bl_folders WHERE folderID=?',array($_POST['FID']));
    if($folder==null){$folder = DataModel::getHull ('bl_folders');$new=true;}else $new=false;
    
    if($_POST['action']=='Submit'){
        $folder->title=$_POST['title'];
        $folder->text=$_POST['text'];
        
        if($new){
            $folder->insertData();
            $folder->folderID=$c->insertID();
            echo('<div class="success">Folder added</div>');
        }else{
            $folder->saveData();
            echo('<div class="success">Folder edited</div>');
        }
    }
    if($_POST['action']=='Delete'){
        $folder->deleteData();
        echo('<div class="success">Folder deleted</div>');
    }
    
    $folders = DataModel::getData('','SELECT * FROM bl_folders ORDER BY title DESC');
    Toolkit::assureArray($folders);
    ?><form class="box" style="width:20%">
        <h3><?=($folder->folderID=='')?'Add a Folder':'Edit the folder'?></h3>
        Title: <input type="text" name="title" value="<?=$folder->title?>" maxlength="64" required /><br />
        <textarea name="text"><?=$folder->text?></textarea><br />
        <input type="hidden" name="FID" value="<?=$folder->folderID?>" />
        <input type="submit" name="action" value="Submit" />
    </form>
    <div class="box" style="width:70%">
        <h3>Manage Folders</h3>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Text</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($folders as $folder){ ?>
                    <tr>
                        <td><?=$folder->title?></td>
                        <td><?=$l->triggerPARSE('Reader',$folder->text)?></td>
                        <td><form>
                            <input type="hidden" name="FID" value="<?=$folder->folderID?>" />
                            <input type="submit" name="action" value="Edit" />
                            <input type="submit" name="action" value="Delete" />
                        </form></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    </div><?
}


}
?>