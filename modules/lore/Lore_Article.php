<?
class Article{
    var $data = null;
    
    
    function __construct($name,$revision=-1){
        if($revision==-1)
            $this->data = DataModel::getData('lore_articles','SELECT title,revision,text,time,editor,type,portal,categories FROM lore_articles 
                                                              WHERE title LIKE ? ORDER BY revision DESC LIMIT 1',array($name));
        else
            $this->data = DataModel::getData('lore_articles','SELECT title,revision,text,time,editor,type,portal,categories FROM lore_articles 
                                                              WHERE title LIKE ? AND revision = ? LIMIT 1',array($name,$revision));
    }
    
    function display(){
        
    }
    
    function displayEdit(){
        
    }
    
    function displayDiff($revision){
        
    }
    
    function displayDiscussion(){
        
    }
    
    function updateArticle(){
        
    }
    
    
}
?>