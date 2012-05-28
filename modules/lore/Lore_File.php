<? 
class File extends Article{
    function displayEdit(){
        global $a,$t,$k,$lore;
        
        $article = &$this->article;
        $t->openPage($article->title.' - Edit');
        echo('<h1>Edit File</h1>');
        
        if($a->check("lore.admin")||$article->status=='o'||($article->status=='l'&&$a->check('lore.access.'.$article->title))){
            if($_POST['action']=='Save'&&$_POST['reason']!=''){
                try{
                    //BEGIN FILE HACK
                    $path = ROOT.DATAPATH.'uploads/lore/'.$article->title.'/';
                    if(!file_exists($path)){
                        if(!mkdir($path, 0777, true))
                            throw new Exception('Failed to create necessary directories. Please notify the admins.');
                    }
                    if(is_uploaded_file($_FILES['file']['tmp_name'])){
                        $k->uploadFile('file',$path,5500,array("image/png","image/gif","image/jpeg","image/jpg"),true,($article->revision+1),false);
                        $k->createThumbnail($path.($article->revision+1),$path.($article->revision+1).'t75',75,75,false,true,false);
                        $k->createThumbnail($path.($article->revision+1),$path.($article->revision+1).'t150',150,150,false,true,false);
                        $k->createThumbnail($path.($article->revision+1),$path.($article->revision+1).'t300',300,300,false,true,false);
                    }else if($_POST['text']!=$article->current){
                        copy($path.$article->revision,$path.($article->revision+1));
                        copy($path.$article->revision.'t75',$path.($article->revision+1).'t75');
                        copy($path.$article->revision.'t150',$path.($article->revision+1).'t150');
                        copy($path.$article->revision.'t300',$path.($article->revision+1).'t300');
                    }
                    if($a->check('lore.admin.move')&&$_POST['move']!=$article->title){
                        if(DataModel::getData('lore_revisions','SELECT title FROM lore_revisions WHERE title LIKE ? AND type=?',array($_POST['move'],$article->type))==null){
                            rename($path.ROOT.DATAPATH.'uploads/lore/'.$_POST['move']);
                    }}
                    if($a->check('lore.admin.delete')&&$_POST['rollback']=='DELETE'||
                       $a->check('lore.admin.type')&&$_POST['type']!=$article->type&&in_array($_POST['type'],array('a','c','p','u','t'))){
                        unlink($path);
                    }
                    if($a->check('lore.admin.rollback')&&$_POST['rollback']!='DELETE'&&$_POST['rollback']!='CURRENT'){
                        $revision = DataModel::getData('lore_revisions','SELECT title FROM lore_revisions WHERE title LIKE ? AND type=? AND revision=?',
                                                                        array($article->title,$article->type,$_POST['rollback']));
                        if($revision!=null){
                            for($i=$article->revision;$i>$_POST['rollback'];$i--){
                                unlink($path.$i);
                                unlink($path.$i.'t75');
                                unlink($path.$i.'t150');
                                unlink($path.$i.'t300');
                            }
                        }
                    }
                    //END FILE HACK
                    $suc=$this->updateArticle($article);
                }
                catch(Exception $e){$err=$e->getMessage();}
                if($suc!='')echo('<div class="success">'.$suc.'</div>');
                if($err!='')echo('<div class="failure">'.$err.'</div>');
            }else{
                $_POST['text']=$article->current;
            }

            include(MODULEPATH.'gui/Editor.php');
            $editor = new SimpleEditor("#","Save","wikieditor",array("default","plus","wiki"));
            if($a->check('lore.admin')){
                if($a->check('lore.admin.status'))
                    $editor->addDropDown('status', array('o','p','l'), array('Open','Protected','Locked'), 'Status',$article->status);
                if($a->check('lore.admin.rollback')){
                    $data = DataModel::getData('lore_revisions','SELECT revision FROM lore_revisions WHERE title LIKE ? AND type=? ORDER BY revision DESC',array($article->title,$article->type));
                    $revisions = array('CURRENT');
                    if($a->check('lore.admin.delete'))$revisions[]='DELETE';
                    if($data!=null){
                        if(!is_array($data))$data=array($data);
                        foreach($data as $dat){$revisions[]=$dat->revision;}
                    }
                    $editor->addDropDown('rollback',$revisions,null, 'Rollback', 'CURRENT');
                }
                if($a->check('lore.admin.type'))
                    $editor->addDropDown('type',array('a','c','p','u','t','f'),array('Article','Category','Portal','User','Template','File'),'Type',$article->type);
                if($a->check('lore.admin.move'))
                    $editor->addTextField('move','Move to',$article->title,'text','placeholder="NewPage"');
            }
            $editor->addCustom('<br style="display:block;"/>');
            $editor->addTextField('reason','Reason','','text','required placeholder="Article edit"');
            $editor->addTextField('file','Upload','','file');
            $editor->setParseAPI('LoreParse');
            $editor->show();
        }else{
            echo('This page is '.$lore->toStatusString($article->status).'. You do not have the permissions to edit it.');
        }
        
        $t->closePage();
    }
    
    function displayHistory(){
        global $k,$t,$lore;
        if(!isset($_GET['from']))$_GET['from']=1;
        $path = DATAPATH.'uploads/lore/'.$this->page.'/';
        
        $article=&$this->article;
        if($article->revision==0){global $existing;$existing='inexistent';}
        
        $t->openPage($article->title.' - History');
        echo('<h1>Action History</h1>');
        if($article->revision==0){
            echo('<blockquote>This '.$this->type.' does not exist yet.</blockquote>');
        }else{
            $actions = DataModel::getData('lore_actions','SELECT action,args,time,editor,reason FROM lore_actions WHERE title LIKE ? AND type=? ORDER BY time DESC',array($article->title,$article->type));
            if(!is_array($actions))$actions=array($actions);

            ?><form><?
            foreach($actions as $action){
                switch($action->action){
                    case 'edit':
                        ?><div class="revision">
                            <input type="radio" name="from" value="<?=$action->args?>" <?if($action->args==$_GET['from'])echo('checked');?> <? if($action->args<0)echo('disabled');?>/>
                            <input type="radio" name="to"   value="<?=$action->args?>" <?if($action->args==$_GET['to'])  echo('checked');?> <? if($action->args<0)echo('disabled');?>/>
                            <img src="<?=$path.$action->args.'t75'?>" alt="<?=$action->args?>" />
                            Revision no. <?=$k->p($action->args)?> 
                        <?
                        break;
                    case 'status':   ?><div class="status">  Status change to <?=$lore->toStatusString($action->args)?> <?break;
                    case 'type':     ?><div class="type">    Type change to <?=$lore->toTypeString($action->args)?>     <?break;
                    case 'move from':?><div class="moveFrom">Moved from <?=$action->args?>                              <?break;
                    case 'move to':  ?><div class="moveTo">  Moved to <?=$action->args?>                                <?break;
                    case 'delete':   ?><div class="delete">  Page deleted                                               <?break;
                    case 'rollback': ?><div class="rollback">Rollback to revision no. <?=$action->args?>                <?break;
                }
                ?>by <?=$k->getUserPage($action->editor)?> on <?=$k->toDate($action->time)?> ( <?=$action->reason?> )</div><?
            }
            ?><input type="submit" name="action" value="Compare" />
            </form><?
        }
        if(is_numeric($_GET['from'])&&is_numeric($_GET['to'])){
            if($_GET['from']<1)$_GET['from']*=-1;
            if($_GET['to']<1)$_GET['to']*=-1;
            if($_GET['from']>$_GET['to'])$k->swap($_GET['from'],$_GET['to']);
            echo('<h1>Revision Comparison '.$_GET['from'].' <> '.$_GET['to'].'</h1>');
            
            $revisions = DataModel::getData('lore_revisions','SELECT text FROM lore_revisions WHERE title LIKE ? AND type=? AND (revision=? OR revision=?)',
                                            array($article->title,$article->type,$_GET['from'],$_GET['to']));
            if(count($revisions)==2){
                ?>
                <div class="filecompare revA">
                    <a href="<?=$path.$_GET['from']?>">
                        <img src="<?=$path.$_GET['from']?>" alt="<?=$_GET['from']?>" />
                    </a><br />
                    <blockquote><?=$revisions[0]->text?></blockquote>
                </div>
                <div class="filecompare revB">
                    <a href="<?=$path.$_GET['to']?>">
                        <img src="<?=$path.$_GET['to']?>" alt="<?=$_GET['to']?>" />
                    </a><br />
                    <blockquote><?=$revisions[1]->text?></blockquote>
                </div><?
            }else{
                echo('<blockquote>Requested revisions not found.</blockquote>');
            }
        }
        $t->closePage();
    }
    
    function parseText($text){
        $article = &$this->article;
        $path = DATAPATH.'uploads/lore/'.$this->page.'/'.$article->revision;
        $text = parent::parseText($text);
        $text = '<a href="'.$path.'"><img src="'.$path.'" alt="'.$this->page.'/'.$article->revision.'"></a><br />'.$text;
        return $text;
    }
    
    function addDropDown($name,$choices,$labels=null,$label="",$selected="",$arguments="",$style=""){
        if($label!=="")$label='<label>'.$label.'</label>';
        $select=$label.'<select name="'.$name.'" style="'.$style.'" '.$arguments.' >';
        for($i=0,$temp=count($choices);$i<$temp;$i++){
            if($labels==null)$label=$choices[$i];else $label=$labels[$i];
            if($choices[$i]==$selected)$sel='selected';else $sel='';
            $select.='<option value="'.$choices[$i].'" '.$sel.'>'.$label.'</option>';
        }
        echo($select.'</select>');
    }
}
?>