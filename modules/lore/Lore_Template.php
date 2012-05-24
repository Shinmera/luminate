<?
class Template extends Article{
    var $page;
    var $rev;
    var $type;
    
    function displayEdit(){
        global $a,$t,$lore;
        
        $article = DataModel::getData('lore_articles','SELECT title,type,revision,status,current,time FROM lore_articles WHERE title LIKE ?',array($this->page));
        if($article==null){
            global $existing;$existing='inexistent';
            $article = DataModel::getHull('lore_articles');
            $article->title=$this->page;
            $article->type=substr($this->type,0,1);
            $article->current='';
            $article->time=time();
            $article->status='o';
            $article->revision=0;
            $article->editor=$a->user->username;
        }
        
        $t->openPage($article->title.' - Edit');
        echo('<h1>Edit '.ucfirst($this->type).'</h1>');
        
        if($a->check("lore.admin")||$article->status=='o'||($article->status=='l'&&$a->check('lore.access.'.$article->title))){
            if($_POST['action']=='Save'&&$_POST['reason']!=''){
                try{$suc=$this->updateArticle($article);}
                catch(Exception $e){$err=$e->getMessage();}
                if($suc!='')echo('<div class="success">'.$suc.'</div>');
                if($err!='')echo('<div class="failure">'.$err.'</div>');
            }else{
                $_POST['text']=$article->current;
            }

            ?><form action="#" method="post">
                <textarea id="deftag" name="text" style="width:100%;min-height:300px;box-sizing: border-box;" placeholder="deftag(<?=$article->title?>){ }" ><?=$_POST['text']?></textarea><br />
                Preview:<br />
                <style>.previewbox{border: 1px solid #CCC;background-color:#EEE;}</style>
                <div class="previewbox" id="userpreview"></div>
                <div class="previewbox" id="parsepreview" style="min-height:100px;"></div>
                <input type="submit" value="Submit" /><span style="color:red;font-weight:bold;"><?=$err?></span>
            </form>
            <script type="text/javascript">
                var deftag;
                function updatePreviewBoxes(){
                    if(deftag==$("#deftag").val())return;
                    deftag = $("#deftag").val();
                    var prevtag = $("#deftag").val().split('deftag');
                    var alltags = '';
                    
                    for(var n=1;n<prevtag.length;n++){
                        var tagcode = prevtag[n].substr(1,prevtag[n].indexOf(')')-1);
                        var args = tagcode.split(',');
                        tagcode = args[0]+'(';
                        for(var i=1;i<args.length;i++){
                            args[i] = args[i].split(' ');
                            switch(args[i].length){
                                case 0:break;
                                case 1:args[i][1]='TEXT';
                                case 2:args[i][2]='false';
                                case 3:
                                    switch(args[i][1]){
                                        case 'STRI':args[i][3]='sTr1n6';break;
                                        case 'TEXT':args[i][3]='Text XY';break;
                                        case 'URLS':args[i][3]='http://google.com';break;
                                        case 'MAIL':args[i][3]='admin@test.net';break;
                                        case 'DATE':args[i][3]='1/1/2012';break;
                                        case 'BOOL':args[i][3]='true';break;
                                        case 'INTE':args[i][3]='5';break;
                                        default:break;
                                    }
                                case 4:
                                    tagcode+=args[i][3]+',';
                                    break;
                            }
                        }
                        if(args.length>1)tagcode=tagcode.substr(0,tagcode.length-1);
                        alltags+=tagcode+'){Body Part}\n';
                    }
                    
                    $("#userpreview").html(alltags);
                    $("#parsepreview").html('Plase wait...');
                    $.post('<?=Toolkit::url('wiki','api/lightupCUSTOM')?>',{'deftag':deftag,'text':alltags},function(text){
                        $("#parsepreview").html(text);
                    });
                }

                $().ready(function(){
                    updatePreviewBoxes();
                    $("#deftag").change(function(){updatePreviewBoxes();});
                    $("#deftag").keyup(function(){updatePreviewBoxes();});
                });
            </script><?
        }else{
            echo('This page is '.$lore->toStatusString($article->status).'. You do not have the permissions to edit it.');
        }
        
        $t->closePage();
    }
    
    function updateArticle($article){
        global $a,$c,$l,$k,$existing;
        $_POST['text']=trim($_POST['text']);
        $_POST['move']=str_replace('_',' ',$k->sanitizeString($_POST['move'],'\s\-_'));
        
        $action = DataModel::getHull('lore_actions');
        $action->title=$article->title;
        $action->reason=$_POST['reason'];
        $action->editor=$a->user->username;
        $action->time=time();
        
        if($a->check('lore.admin.move')&&$_POST['move']!=$article->title){
            if(DataModel::getData('lore_revisions','SELECT title FROM lore_revisions WHERE title LIKE ?',array($_POST['move']))==null){
                $c->query('UPDATE lore_revisions SET title=? WHERE title LIKE ?',array($_POST['move'],$article->title));
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
        
        if($a->check('lore.admin.type')&&$_POST['type']!=$article->type&&in_array($_POST['type'],array('a','c','p','u'))){
            if(($article->type=='c'||$article->type=='p')&&($_POST['type']!='c'&&$_POST['type']!='p'))
                $c->query('DELETE FROM lore_categories WHERE title LIKE ?',array($article->title));
                
            $article->type=$_POST['type'];
            $article->saveData();
            
            
            $action->action='type';
            $action->args=$_POST['type'];
            $action->insertData();
            $suc.=' Type changed.';
        }
        
        if($a->check('lore.admin.delete')&&$_POST['rollback']=='DELETE'){
            $c->query('DELETE FROM lore_revisions WHERE title=?',array($article->title));
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
            $revision = DataModel::getData('lore_revisions','SELECT text FROM lore_revisions WHERE title LIKE ? AND revision=?',
                                                             array($article->title,$_POST['rollback']));
            if($revision==null)throw new Exception('Cannot rollback to '.$_POST['rollback'].': Revision does not exist.');
            
            $c->query('DELETE FROM lore_revisions WHERE title LIKE ? AND revision > ?',
                                                             array($article->title,$_POST['rollback']));
            $c->query('UPDATE lore_actions SET args*=-1 WHERE args > 0 AND title LIKE ? AND revision > ?',
                                                             array($article->title,$_POST['rollback']));
            
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
    
    function parseText($text){
        global $l;
        $lightup = $l->loadModule('LightUp');
        $lightup->loadCode();
        $lightup->parseFuncEM($text,array('deftag'));
        
        $prevtag = explode('deftag',$text);
        $alltags = '';

        for($n=1;$n<count($prevtag);$n++){
            $tagcode = substr($prevtag[$n],1,strpos($prevtag[$n],')')-1);
            $args = explode(',',$tagcode);
            $tagcode = $args[0].'(';
            for($i=1;$i<count($args);$i++){
                $args[$i] = explode(' ',$args[$i]);
                switch(count($args[$i])){
                    case 0:break;
                    case 1:$args[$i][1]='TEXT';
                    case 2:$args[$i][2]='false';
                    case 3:
                        switch($args[$i][1]){
                            case 'STRI':$args[$i][3]='sTr1n6';break;
                            case 'TEXT':$args[$i][3]='Text XY';break;
                            case 'URLS':$args[$i][3]='http://google.com';break;
                            case 'MAIL':$args[$i][3]='admin@test.net';break;
                            case 'DATE':$args[$i][3]='1/1/2012';break;
                            case 'BOOL':$args[$i][3]='true';break;
                            case 'INTE':$args[$i][3]='5';break;
                            default:break;
                        }
                    case 4:
                        $tagcode.=$args[$i][3].',';
                        break;
                }
            }
            if(count($args)>1)$tagcode=substr($tagcode,0,strlen($tagcode)-1);
            $alltags.=$tagcode.'){Body Part}'."\n";
        }
        return $lightup->parseFuncEM($alltags,array('*'));
    }
}
?>