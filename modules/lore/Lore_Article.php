<?
class Article{
    var $page;
    var $rev;
    var $type;
    var $article;
    
    function __construct($page,$type='a',$revision=-1,$article=null){
        $this->page=$page;
        $this->type=$type;
        $this->rev=$revision;
        $this->article=$article;
    }
    
    function displayView(){
        global $k,$t,$lore,$c,$page;
        
        $article=&$this->article;
        $page=$article->title;
        
        if($this->rev!=-1){
            $revision = DataModel::getData('lore_revisions','SELECT text FROM lore_revisions WHERE title LIKE ? AND revision=? AND type=?',array($article->title,$this->rev,$article->type));
            if($revision==null)$article->current='The revision you are looking for does not exist.';
            else               $article->current=$revision->text;
            $article->revision=$this->rev;
        }
        
        if(substr($article->current,0,11)=='#!redirect:')
            header('Location: '.PROOT.str_replace('#!redirect:','',$article->current));
        $t->openPage($article->title);
        
        if($article->type!='p'){
            if($article->type!='a')echo('<h1>'.ucfirst($this->type).': '.str_replace('_',' ',$article->title).'</h1>');
            else                   echo('<h1>'.str_replace('_',' ',$article->title).'</h1>');
        }
        $path=CACHEPATH.'articles/'.$article->title.'/'.$article->revision.'.html';
        if(file_exists($path))echo(file_get_contents($path));
        else                  echo($this->parseText($article->current));
        echo('<br class="clear"/>');
        
        if($article->type=='c'){
            $cats = $c->getData('SELECT article FROM lore_categories WHERE title LIKE ? ORDER BY article',array($article->title));
            if(count($cats)>0){
                echo('<div class="box categories">');
                $lastchar='';
                foreach($cats as $cat){
                    if($lastchar!=strtoupper(substr($cat['article'],0,1))){
                        $lastchar=strtoupper(substr($cat['article'],0,1));
                        echo('<h4>'.$lastchar.'</h4>');
                    }
                    echo('<a href="'.PROOT.'category/'.$cat['article'].'">'.$cat['article'].'</a><br />');
                }
                echo('</div>');
            }else{
                echo('<hr />No articles have been added to this category yet.');
            }
        }
        ?>
        <div id='infobar'>
            <div style='display:inline-block;float:right;'>
                <? switch($article->status){
                    case 'o':echo('<img width="16" height="16" src="'.$t->img.'public.png" alt="Public" title="This page is publicly editable by registered users." />');break;
                    case 'p':echo('<img width="16" height="16" src="'.$t->img.'protected.png" alt="Protected" title="This page is protected and can only be edited by certain users." />');break;
                    case 'l':echo('<img width="16" height="16" src="'.$t->img.'locked.png" alt="Locked" title="This page is locked and can only be edited by moderators." />');break;
                } ?>
            </div>
            Current Revision: <em><a href='<?=PROOT.$article->title.'/history?to='.$article->revision?>'><?=$article->revision?></a></em>
            Type: <?=$lore->toTypeString($article->type);?><br />
            Created on: <em><?=$k->toDate($article->time);?></em>
        </div><?
        $t->closePage();
    }
    
    function displayHistory(){
        global $k,$t,$lore;
        if(!isset($_GET['from']))$_GET['from']=1;
        
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
                ?><script type="text/javascript" src="<?=DATAPATH?>js/diff_match_patch.js"></script>
                <script type="text/javascript">
                    var dmp = new diff_match_patch();
                    $(document).ready(function(){
                        dmp.Diff_Timeout = 5;
                        dmp.Diff_EditCost = 10;

                        var d = dmp.diff_main($("#revA").html(), $("#revB").html());

                        dmp.diff_cleanupSemantic(d);
                        $("#comparison").html(dmp.diff_prettyHtml(d));
                    });
                </script>
                <div id="revA" style="display:none;"><?=$revisions[0]->text?></div>
                <div id="revB" style="display:none;"><?=$revisions[1]->text?></div>
                <div id="comparison">
                    Please wait, computing diff...
                </div><?
            }else{
                echo('<blockquote>Requested revisions not found.</blockquote>');
            }
        }
        $t->closePage();
    }
    
    function displayEdit(){
        global $a,$t,$lore;
        
        $article = &$this->article;
        $t->openPage($article->title.' - Edit');
        echo('<h1>Edit '.ucfirst($this->type).'</h1>');
        
        if(($a->check("lore.admin")||$article->status=='o'||($article->status=='l'&&$a->check('lore.access.'.$article->title)))&&
            $a->user!=null){
            if($_POST['action']=='Save'&&$_POST['reason']!=''){
                try{$suc=$this->updateArticle($article);}
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
            $editor->addCustom('<br style="display:block;" />');
            $editor->addTextField('reason','Reason','','text','required placeholder="Article edit"');
            $editor->setParseAPI('LoreParse');
            $editor->show();
        }else{
            if($a->user==null)
                echo('Please <a href="'.Toolkit::url('login').'">log in</a> or <a href="'.Toolkit::url('login','register').'">register</a> to edit this page.');
            else
                echo('This page is '.$lore->toStatusString($article->status).'. You do not have the permissions to edit it.');
        }
        
        $t->closePage();
    }
    
    function displayDiscuss(){
        global $t,$l;
        
        $article = &$this->article;
        if($article->revision==0){global $existing;$existing='inexistent';}
        
        $t->openPage($article->title.' - Discussion');
        echo('<h1>Discussion</h1>');
        if($article->revision==0)
            echo('<blockquote>This '.$this->type.' does not exist yet.</blockquote>');
        else
            $l->triggerHook('DISPLAYdiscussion','Lore');
        
    }
    
    function updateArticle($article){
        global $a,$c,$l,$k,$existing;
        $_POST['text']=trim($_POST['text']);
        $_POST['move']=str_replace('_',' ',$k->sanitizeString($_POST['move'],'\s\-_'));
        
        $action = DataModel::getHull('lore_actions');
        $action->title=$article->title;
        $action->type=$article->type;
        $action->reason=$_POST['reason'];
        $action->editor=$a->user->username;
        $action->time=time();
        
        if($a->check('lore.admin.move')&&$_POST['move']!=$article->title){
            if(DataModel::getData('lore_revisions','SELECT title FROM lore_revisions WHERE title LIKE ? AND type=?',array($_POST['move'],$article->type))==null){
                $c->query('UPDATE lore_revisions SET title=? WHERE title LIKE ? AND type=?',array($_POST['move'],$article->title,$article->type));
                if($article->type=='c'||$article->type=='p')
                    $c->query('UPDATE lore_categories SET title=? WHERE title LIKE ?',array($_POST['move'],$article->title));
                
                $action->action='move to';
                $action->args=$_POST['move'];
                $action->insertData();
                
                $action->title=$_POST['move'];
                $action->action='move from';
                $action->args=$article->title;
                $action->insertData();
                
                rename(CACHEPATH.'articles/'.$article->title.'/',CACHEPATH.'articles/'.$_POST['move'].'/');
                $article->current='#!history';
                $article->saveData();
                
                $article->current=$_POST['text'];
                $article->title=$_POST['move'];
                $article->insertData();
                $suc.=' Page moved.';
            }else throw new Exception('Cannot move to '.$_POST['move'].', destination already exists!');
        }
        
        if($a->check('lore.admin.status')&&$_POST['status']!=$article->status&&in_array($_POST['status'],array('o','p','l'))){
            $article->status=$_POST['status'];
            $article->saveData();
            
            $action->action='status';
            $action->args=$_POST['status'];
            $action->insertData();
            $suc.=' Status changed.';
        }
        
        if($a->check('lore.admin.type')&&$_POST['type']!=$article->type&&in_array($_POST['type'],array('a','c','p','u','t','f'))){
            if(($article->type=='c'||$article->type=='p')&&($_POST['type']!='c'&&$_POST['type']!='p'))
                $c->query('DELETE FROM lore_categories WHERE title LIKE ?',array($article->title));
            
            $c->query('UPDATE lore_revisions SET type=? WHERE title LIKE ? AND type=?',
                    array($_POST['type'],$article->title,$article->type));
            $c->query('UPDATE lore_actions SET type=? WHERE title LIKE ? AND type=?',
                    array($_POST['type'],$article->title,$article->type));
                
            $article->type=$_POST['type'];
            $article->saveData();
            
            $action->action='type';
            $action->args=$_POST['type'];
            $action->insertData();
            $suc.=' Type changed.';
        }
        
        if($a->check('lore.admin.delete')&&$_POST['rollback']=='DELETE'){
            $c->query('DELETE FROM lore_revisions WHERE title=? AND type=?',array($article->title,$article->type));
            if($article->type=='c'||$article->type=='p')
                $c->query('DELETE FROM lore_categories WHERE title LIKE ?',array($article->title));
            
            unlink(CACHEPATH.'articles/'.$article->title);
            $article->current='#!history';
            $article->saveData();
            
            $action->action='delete';
            $action->args='';
            $action->insertData();
            
            $l->triggerPOST('Lore','Lore',$article->title,'Article '.$article->title.' deleted.','',$k->url('wiki',$article->title.'/history'),'Article '.$article->title.' deleted.');
            return('Page deleted.');
        }
        
        if($a->check('lore.admin.rollback')&&$_POST['rollback']!='DELETE'&&$_POST['rollback']!='CURRENT'){
            $revision = DataModel::getData('lore_revisions','SELECT text FROM lore_revisions WHERE title LIKE ? AND type=? AND revision=?',
                                                             array($article->title,$article->type,$_POST['rollback']));
            if($revision==null)throw new Exception('Cannot rollback to '.$_POST['rollback'].': Revision does not exist.');
            
            $c->query('DELETE FROM lore_revisions WHERE title LIKE ? AND type=? AND revision > ?',
                                                             array($article->title,$article->type,$_POST['rollback']));
            $c->query('UPDATE lore_actions SET args*=-1 WHERE args > 0 AND title LIKE ? AND type=? AND revision > ?',
                                                             array($article->title,$article->type,$_POST['rollback']));
            
            $article->current=$revision->text;
            $article->revision=$_POST['rollback'];
            $article->saveData();
            
            $action->action='rollback';
            $action->args=$_POST['rollback'];
            $action->insertData();
            
            $l->triggerPOST('Lore','Lore',$article->title,'Article '.$article->title.' rolled back.','',$k->url('wiki',$article->title.'/history'),'Article '.$article->title.' rolled back.');
            return('Rollback to '.$_POST['rollback'].' performed.');
        }

        if($_POST['text']==$article->current){
            if(!isset($suc))throw new Exception('No changes noticed.');
            else            return $suc;
        }
        else if(strlen($_POST['text'])<$c->o['lore_minlength'])throw new Exception('Minimum length requirement not met.');
        else{
            $article->revision++;
            $article->current=$_POST['text'];
            if($existing=='')$article->saveData();
            else             $article->insertData();
            
            $revision = DataModel::getHull('lore_revisions');
            $revision->title=$article->title;
            $revision->type=$article->type;
            $revision->revision=$article->revision;
            $revision->text=$_POST['text'];
            $revision->time=time();
            $revision->editor=$a->user->username;
            $revision->insertData();

            $action->action='edit';
            $action->args=$article->revision;
            $action->insertData();
            
            $l->triggerPOST('Lore','Lore',$article->title,'Article '.$article->title.' edited.','',$k->url('wiki',$article->title.'/history'),'Article '.$article->title.' edited.');
            if(strpos($_POST['text'],'#!nocache')===FALSE)
                $this->cacheRevision($revision);
        }
        return($suc.' Page updated.');
    }

    function cacheRevision($revision){
        $path=CACHEPATH.'articles/'.$revision->title.'/';
        if(!file_exists($path)){
            mkdir($path,0777,true);
            file_put_contents($path.'index.html', '');
        }
            
        $text = $this->parseText($revision->text);
        
        if(!file_put_contents($path.$revision->revision.'.html',$text,LOCK_EX))
            throw new Exception('Failed to generate '.$path.$revision->revision.'.html');
        else
            return true;
    }
    
    function parseText($text){
        global $l;
        $text = $l->triggerPARSE('Lore',$text);
        $parser = $l->loadModule('LoreParser');
        $text = $parser->parse($text);
        return $text;
    }
}
?>