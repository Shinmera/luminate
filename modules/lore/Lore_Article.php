<?
class Article{
    var $data = null;
    
    
    function __construct($name,$revision=-1){
        if($revision==-1)
            $this->data = DataModel::getData('lore_articles','SELECT title,revision,text,time,editor,type,categories FROM lore_articles 
                                                              WHERE title LIKE ? ORDER BY revision DESC LIMIT 1',array($name));
        else
            $this->data = DataModel::getData('lore_articles','SELECT title,revision,text,time,editor,type,categories FROM lore_articles 
                                                              WHERE title LIKE ? AND revision = ? LIMIT 1',array($name,$revision));
    }
    
    //TODO: Add cache
    function displayView(){
        global $page,$k,$t;
        if($this->data->title==''){
            $this->data->title=$page;
            $this->data->text='This page does not exist yet.';
            $this->data->time='Never';
            $this->data->type='o';
            $this->data->revision='-1';
            $this->data->editor='System';
        }
        ?>  
        <h1><?=$this->data->title?></h1>
        <blockquote>
            <?=$this->printArticle($this->data->text);?>
        </blockquote>
        <?
        if($this->data->categories!=''){
            $cats = explode(',',$this->data->categories);
            echo('<div class="box categories">');
            foreach($cats as $cat){
                echo('<a href="'.Toolkit::url('wiki','category/'.$cat).'">'.$cat.'</a> ');
            }
            echo('</div>');
        }
        ?>
        <div id='infobar'>
            <div style='display:inline-block;float:right;'>
                <? switch($this->data->type){
                    case 'o':echo('<img width="16" height="16" src="'.$t->img.'public.png" alt="Public" title="Public" />');break;
                    case 'p':echo('<img width="16" height="16" src="'.$t->img.'protected.png" alt="Protected" title="Protected" />');break;
                    case 'l':echo('<img width="16" height="16" src="'.$t->img.'locked.png" alt="Locked" title="Locked" />');break;
                } ?>
            </div>
            Revision: <em><a href='<?=$k->url('wiki',$this->data->title.'/history')?>'><?=$this->data->revision?></a></em>
            Last change: <em><?=$k->toDate($this->data->time);?></em><br />
            Editor: <em><?=$k->getUserPage($this->data->editor);?></em>
        </div><?
    }
    
    function displayEdit(){
        
    }
    
    //TODO: Add blanking warning if change is too big
    function displayHistory(){
        global $k;
        echo('<h1>History</h1>');
        if(!is_numeric($_GET['from'])||!is_numeric($_GET['to'])){
            if($this->data==null){
                ?><p>This page does not exist yet.</p><?
            }else{
                $revisions = DataModel::getData('SELECT revision,time,editor FROM lore_articles WHERE title LIKE ? ORDER BY revision DESC',array($this->data->title));
                if(!is_array($revisions))$revisions=array($revisions);
                
                ?><form><?
                foreach($revisions as $revision){
                    ?><div class="revision">
                        <input type="radio" name="from" value="<?=$revision->revision?>" />
                        <input type="radio" name="to"   value="<?=$revision->revision?>" />
                        Revision no. <?=$revision->revision?> by <?=$k->getUserPage($revision->editor)?> on <?=$k->toDate($revision->time)?>
                    </div><?
                }
                ?><input type="submit" name="action" value="Compare" />
                </form><?
            }
        }else{
            if($_GET['from']<1)$_GET['from']*=-1;
            if($_GET['to']<1)$_GET['to']*=-1;
            if($_GET['from']>$_GET['to'])$k->swap($_GET['from'],$_GET['to']);
            
            $revisions = DataModel::getData('SELECT text FROM lore_articles WHERE title LIKE ? AND (revision=? OR revision=?)',
                                            array($this->data->title,$_GET['from'],$_GET['to']));
            
            ?><script type="text/javascript" src="<?=DATAPATH?>js/diff_match_patch.js"></script>
            <script type="text/javascript">
                var diff = new diff_match_patch();
                $(document).ready(function(){
                    dmp.Diff_Timeout = 5;
                    dmp.Diff_EditCost = 10;

                    var d = dmp.diff_main($("#revA").html(), $("#revB").html());

                    dmp.diff_cleanupSemantic(d);
                    $("#comparison").html(dmp.diff_prettyHtml(d));
                });
            </script>
            <div id="revA" style="display:none;"><?=$this->printArticle($revisions[0]->text)?></div>
            <div id="revB" style="display:none;"><?=$this->printArticle($revisions[1]->text)?></div>
            <div id="comparison">
                Please wait, computing diff...
            </div><?
        }
    }
    
    function displayDiscuss(){
        global $l;
        $l->triggerHook('DISPLAYdiscussion','Lore');
    }
    
    function printArticle($text){
        global $l;
        return $l->triggerPARSE('Lore',$this->data->text);
    }
    
    function updateArticle(){
        
    }
    
    
}
?>