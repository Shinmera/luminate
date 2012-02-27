<?php
class ResourceManager{
    
    
    function getFile($reference){
        
    }
    
    function getURL($mixed){
        if(substr($mixed,0,7)=="http://")return $mixed;
        $pos = strrpos($mixed,".");
        if($pos===FALSE){
            
        }else{
            $ext = substr($mixed,  $pos);
        }
    }
    
    function getResourceURL($localFile){
        
    }
    
}
?>
