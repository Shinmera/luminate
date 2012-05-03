<? 
class Special extends Article{
    
    function __construct($name,$revision=-1){}
    
    function displayView(){
        global $page;
        switch($page){
            case 'latest':$this->displayLatest();break;
            case 'newest':$this->displayNewest();break;
            case 'random':$this->displayRandom();break;
            case 'help':  $this->displayHelp();break;
            default:
                echo('<div class="center">Sorry, a special page called "'.$page.'" doesn\'t exist.</div>');
                break;
        }
    }
    
    function displayLatest(){
        
    }
    
    function displayNewest(){
        
    }
    
    function displayRandom(){
        global $c;
        $num = $c->getData('SELECT COUNT(title) FROM lore_articles');
        $rand = mt_rand(0,$num[0]['COUNT(title)']);
        $name = $c->getData('SELECT title FROM lore_articles LIMIT ?,1',array($rand));
        header('Location: '.PROOT.$name);
    }
    
    function displayHelp(){
        
    }
    
    function displayEdit(){$this->displayView();}
    function displayHistory(){$this->displayView();}
    function displayDiscuss(){$this->displayView();}
    function updateArticle(){}
}
?>