<? 
class LoreParser extends Module{
public static $name="LoreParser";
public static $author="Shinmera";
public static $version=0.01;
public static $short='loreparser';
public static $required=array('LightUp');
public static $hooks=array("foo");

    function parse($text){
        $text = preg_replace_callback('`\#\!include:([-A-Z0-9_-]*)`is',     array(&$this,'includeCallback'),    $text);
        
        global $lightup;
        $args = array("text"=>$text,"source"=>'Lore',"formatted"=>true,
            "allowRaw"=>false,"blockedTags"=>array(),"suites"=>array('*'));
        $args = $lightup->deparse($args);
        $text = $args['text'];
        
        $text = preg_replace_callback('`\[([-A-Z0-9_-]*)\]`is',             array(&$this,'pageCallback'),       $text);
        $text = preg_replace_callback('`\[([-A-Z0-9_-]*)\|([-A-Z0-9_-]*)\]`is',array(&$this,'pageCallback'),    $text);
        
        $text = preg_replace_callback('`\#\!category:([-A-Z0-9_-]*)`is',    array(&$this,'categoryCallback'),   $text);
        $text = preg_replace_callback('`\#\!portal:([-A-Z0-9_-]*)`is',      array(&$this,'portalCallback'),     $text);
        $text = preg_replace_callback('`\#\!file:([-A-Z0-9_-]*)(\|(75|150|300)){0,1}`is',array(&$this,'fileCallback'),$text);
        
        $text = preg_replace_callback('`\#\!history`is',                    array(&$this,'historyCallback'),    $text);
        $text = str_replace(          '#!noparse',                          '',                                 $text);
        $text = str_replace(          '#!nocache',                          '',                                 $text);
        
        return $text;
    }
    
    function pageCallback($matches){
        global $k,$lore;
        $article = DataModel::getData('lore_articles','SELECT title,type FROM lore_articles WHERE title LIKE ?',array($matches[1]));
        if(isset($matches[2]))$title=$matches[2];else $title=$matches[1];
        if($article==null){
            return '<a href="'.$k->url('wiki',$matches[1]).'" class="pagelink inexistent">'.$title.'</a>';
        }else{
            return '<a href="'.$k->url('wiki',$lore->toTypeString($article->type).'/'.$article->title).'" class="pagelink">'.$title.'</a>';
        }
    }
    
    function categoryCallback($matches){
        global $c,$k,$page;
        $category = DataModel::getData('lore_articles','SELECT title FROM lore_articles WHERE title LIKE ? AND type=?',array($matches[1],'c'));
        if($category==null){
            return '';
        }else{
            $c->query('INSERT IGNORE INTO lore_categories SET title=?, article=?',array($category->title,$page));
            return '<div class="box categorybox">This article is part of <a class="pagelink" href="'.$k->url('wiki',$category->title).'">'.$category->title.'</a>.</div>';
        }
    }
    
    function portalCallback($matches){
        global $k;
        $portal = DataModel::getData('lore_articles','SELECT title FROM lore_articles WHERE title LIKE ? AND type=?',array($matches[1],'p'));
        if($portal==null){
            return '';
        }else{
            return '<div class="box portalbox">
                        This article is part of a series on <a class="pagelink" href="'.$k->url('wiki',$portal->title).'">'.$portal->title.'</a>.<br />
                        Visit the <a class="pagelink" href="'.$k->url('wiki',$portal->title).'">'.$portal->title.' Portal</a> for more.
                    </div>';
        }
    }
    
    function fileCallback($matches){
        $file = DataModel::getData('lore_articles','SELECT title,revision FROM lore_articles WHERE title LIKE ? AND type=?',array($matches[1],'f'));
        if($file==null){
            return '<a class="pagelink inexistent" href="'.PROOT.'file/'.$matches[1].'">File: '.$matches[1].'</a>';
        }else{
            if($matches[3]=='')$matches[3]='75';
            $path = DATAPATH.'uploads/lore/'.$file->title.'/'.$file->revision.'t'.$matches[3];
            return '<a href="'.PROOT.'file/'.$matches[1].'">
                        <img alt="'.$file->title.'" title="'.$file->title.'" src="'.$path.'" /></a>';
        }
    }
    
    function includeCallback($matches){
        global $lightup,$c;
        $template = DataModel::getData('lore_articles','SELECT current FROM lore_articles WHERE title LIKE ? AND type=?',array($matches[1],'t'));
        if($template!=null){
            $lightup->parseFuncEM($c->desecureHTML($template->current),array('deftag'));
        }
        return '';
    }
    
    function historyCallback($matches){
        global $page,$type,$k,$lore;
        $actions = DataModel::getData('lore_actions','SELECT action,args,time,editor,reason FROM lore_actions WHERE title LIKE ? AND type=? ORDER BY time DESC',array($page,substr($type,0,1)));
        if(!is_array($actions))$actions=array($actions);

        $return = '<div class="box history">';
        foreach($actions as $action){
            switch($action->action){
                case 'edit':     $return.='<div class="revision">Revision no. '.$k->p($action->args);                     break;
                case 'status':   $return.='<div class="status">  Status change to '.$lore->toStatusString($action->args); break;
                case 'type':     $return.='<div class="type">    Type change to '.$lore->toTypeString($action->args);     break;
                case 'move from':$return.='<div class="moveFrom">Moved from '.$action->args;                              break;
                case 'move to':  $return.='<div class="moveTo">  Moved to '.$action->args;                                break;
                case 'delete':   $return.='<div class="delete">  Page deleted';                                           break;
                case 'rollback': $return.='<div class="rollback">Rollback to revision no. '.$action->args;                break;
            }
            $return.=' by '.$k->getUserPage($action->editor,true).' on '.$k->toDate($action->time).' ( '.$action->reason.' )</div>';
        }
        return $return.'</div>';
    }
}
?>
