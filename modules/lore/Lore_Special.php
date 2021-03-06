<? 
class Special{
    
    function display($page){
        global $t;
        switch($page){
            case 'latest':$this->displayLatest();break;
            case 'newest':$this->displayNewest();break;
            case 'random':$this->displayRandom();break;
            case 'templates':$this->displayTemplates();break;
            case 'help':  $this->displayHelp();break;
            default:
                $t->openPage('404');
                echo('<h1>404s</h1><div class="center">Sorry, a special page called "'.$page.'" doesn\'t exist.</div>');
                $t->closePage();break;
        }
    }
    
    function displayLatest(){
        global $t,$k,$lore;
        $actions = DataModel::getData('lore_actions','SELECT title,type,action,editor,time,args,reason,type
                                                       FROM lore_actions ORDER BY time DESC LIMIT 25');
        $t->openPage('Latest Changes');
        echo('<h1>Latest Changes</h1>');
        foreach($actions as $action){
            ?><?=$k->toDate($action->time)?> <label><a href='<?=PROOT.$lore->toTypeString($action->type).'/'.$action->title?>'><?=$action->title?></a></label><?
            switch($action->action){
                case 'edit':     ?><span class="revision">Revision no. <?=$k->p($action->args)?>                     <?break;
                case 'status':   ?><span class="status">  Status change to <?=$lore->toStatusString($action->args)?> <?break;
                case 'type':     ?><span class="type">    Type change to <?=$lore->toTypeString($action->args)?>     <?break;
                case 'move from':?><span class="moveFrom">Moved from <?=$action->args?>                              <?break;
                case 'move to':  ?><span class="moveTo">  Moved to <?=$action->args?>                                <?break;
                case 'delete':   ?><span class="delete">  Page deleted                                               <?break;
                case 'rollback': ?><span class="rollback">Rollback to revision no. <?=$action->args?>                <?break;
            }
            ?>by <?=$k->getUserPage($action->editor)?> ( <?=$action->reason?> )</span><br /><?
        }

        $t->closePage();
    }
    
    function displayNewest(){
        global $t,$k,$lore;
        $articles = DataModel::getData('lore_articles','SELECT title,type,time FROM lore_articles ORDER BY time DESC LIMIT 25');
        
        $t->openPage('Newest Articles');
        echo('<h1>Newest Articles</h1>');
        foreach($articles as $article){
            ?><?=$k->toDate($article->time)?>
            <label><a href='<?=PROOT.$lore->toTypeString($article->type).'/'.$article->title?>'><?=$lore->toTypeString($article->type).': '.$article->title?></a></label><br /><?
        }

        $t->closePage();
    }
    
    function displayRandom(){
        global $c;
        $num = $c->getData('SELECT COUNT(title) FROM lore_articles');
        $rand = mt_rand(0,$num[0]['COUNT(title)']);
        $name = $c->getData('SELECT title FROM lore_articles LIMIT ?,1',array($rand));
        header('Location: '.PROOT.$name[0]['title']);
    }
    
    function displayTemplates(){
        
    }
    
    function displayHelp(){
        header('Location: '.PROOT.'Help');
    }
    
}
?>