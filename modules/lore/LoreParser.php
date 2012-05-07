<? 
class LoreParser extends Module{
public static $name="LoreParser";
public static $author="Shinmera";
public static $version=0.01;
public static $short='loreparser';
public static $required=array();
public static $hooks=array("foo");

    function parse($text){
        $text = preg_replace_callback('`\[([-A-Z0-9_-]*)\]`is',             array(&$this,'pageCallback'),$text);
        $text = preg_replace_callback('`\[([-A-Z0-9_-]*|[-A-Z0-9_-]*)\]`is',array(&$this,'pageCallback'),$text);
        
        $text = preg_replace_callback('`\{category:([-A-Z0-9_-]*)\}`is',    array(&$this,'categoryCallback'), $text);
        $text = preg_replace_callback('`\{portal:([-A-Z0-9_-]*)\}`is',      array(&$this,'portalCallback'), $text);
        
        $text = preg_replace_callback('`\#\!history`is',                    array(&$this,'historyCallback'),$text);
    }
    
    function pageCallback($matches){
        global $k;
        $article = DataModel::getData('lore_articles','SELECT title FROM lore_articles WHERE title LIKE ?',array($matches[1]));
        if($article==null){
            return '<a href="'.$k->url('wiki',$matches[1]).'" class="pagelink inexistent">'.$matches[1].'</a>';
        }else{
            return '<a href="'.$k->url('wiki',$article['title']).'" class="pagelink">'.$matches[2].'</a>';
        }
    }
    
    function categoryCallback($matches){
        $category = DataModel::getData('lore_articles','SELECT title FROM lore_articles WHERE title LIKE ? AND type LIKE ?',array($matches[1],'c'));
        if($category==null){
            return '';
        }else{
            global $page;
            $c->query('INSERT INTO lore_categories SET title=?, article=?',array($category['title'],$page));
            return '<div class="box categorybox">This article is part of <a href="'.$k->url('wiki',$category['title']).'">'.$category['title'].'</a>.</div>';
        }
    }
    
    function portalCallback($matches){
        $portal = DataModel::getData('lore_articles','SELECT title FROM lore_articles WHERE title LIKE ? AND type LIKE ?',array($matches[1],'p'));
        if($portal==null){
            return '';
        }else{
            return '<div class="box portalbox">
                        This article is part of a series on <a href="'.$k->url('wiki',$portal['title']).'">'.$category['title'].'</a>.<br />
                        Visit the <a href="'.$k->url('wiki',$portal['title']).'">'.$portal['title'].' Portal</a> for more.
                    </div>';
        }
    }
    
    function historyCallback($matches){
        global $page,$k;
        $actions = DataModel::getData('lore_actions','SELECT action,args,time,editor,reason FROM lore_actions WHERE title LIKE ? ORDER BY time DESC',array($page));
        if(!is_array($actions))$actions=array($actions);

        $return = "";
        foreach($actions as $action){
            switch($action->action){
                case 'edit':
                    $k->p($action->args);
                    break;
                case 'status':   ?><div class="status">  Status change to <?=$this->toStatusString($action->args)?> <?break;
                case 'type':     ?><div class="type">    Type change to <?=$this->toTypeString($action->args)?>     <?break;
                case 'move from':?><div class="moveFrom">Moved from <?=$action->args?>                              <?break;
                case 'move to':  ?><div class="moveTo">  Moved to <?=$action->args?>                                <?break;
                case 'delete':   ?><div class="delete">  Page deleted                                               <?break;
                case 'rollback': ?><div class="rollback">Rollback to revision no. <?=$action->args?>                <?break;
            }
            ?>by <?=$k->getUserPage($action->editor)?> on <?=$k->toDate($action->time)?> ( <?=$action->reason?> )</div><?
        }
    }
}
?>
