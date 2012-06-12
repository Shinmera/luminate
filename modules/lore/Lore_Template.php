<?
class Template extends Article{
    var $page;
    var $rev;
    var $type;
    
    function displayEdit(){
        global $a,$t,$lore;
        
        $article = &$this->article;
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

            include(MODULEPATH.'gui/Editor.php');
            $editor = new SimpleEditor("#","Save","wikieditor",array(""));
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
                    $editor->addDropDown('type',array('a','c','p','u','t'),array('Article','Category','Portal','User','Template'),'Type',$article->type);
                if($a->check('lore.admin.move'))
                    $editor->addTextField('move','Move to',$article->title,'text','placeholder="NewPage"');
            }
            $editor->addTextField('reason','Reason','','text','required placeholder="Article edit"');
            $editor->setParseAPI('');
            $editor->show();
            
            ?>
            Preview:<br />
            <style>.previewbox{border: 1px solid #CCC;background-color:#EEE;}</style>
            <div class="previewbox" id="userpreview"></div>
            <div class="previewbox" id="parsepreview" style="min-height:100px;"></div>
            <script type="text/javascript">
                var deftag;
                function updatePreviewBoxes(){
                    if(deftag==$("#wikieditortxt").val())return;
                    deftag = $("#wikieditortxt").val();
                    var prevtag = $("#wikieditortxt").val().split('deftag');
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
                        alltags+=tagcode+'){Body Part '+n+'}\n';
                    }
                    
                    $("#userpreview").html(alltags);
                    $("#parsepreview").html('Plase wait...');
                    $.post('<?=PROOT.'api/lightupCUSTOM'?>',{'deftag':deftag,'text':alltags},function(text){
                        $("#parsepreview").html(text);
                    });
                }

                $().ready(function(){
                    updatePreviewBoxes();
                    $("#wikieditortxt").change(function(){updatePreviewBoxes();});
                    $("#wikieditortxt").keyup(function(){updatePreviewBoxes();});
                });
            </script><?
        }else{
            echo('This page is '.$lore->toStatusString($article->status).'. You do not have the permissions to edit it.');
        }
        
        $t->closePage();
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