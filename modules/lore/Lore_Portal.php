<? 
class Portal extends Article{
    
    function __construct($name,$revision=-1){
        if($revision==-1)
            $this->data = DataModel::getData('lore_portals','SELECT title,revision,text,time,editor,type FROM lore_portals
                                                              WHERE title LIKE ? ORDER BY revision DESC LIMIT 1',array($name));
        else
            $this->data = DataModel::getData('lore_portals','SELECT title,revision,text,time,editor,type FROM lore_portals 
                                                              WHERE title LIKE ? AND revision = ? LIMIT 1',array($name,$revision));
    }
    
    function displayView(){
        parent::displayView();
    }
    
    function displayEdit(){
        parent::displayEdit();
    }
    
    function displayHistory(){
        parent::displayHistory();
    }
    
    function displayDiscuss(){
        parent::displayHistory();
    }
    
    function updateArticle(){
        parent::updateArticle();
    }
}
?>