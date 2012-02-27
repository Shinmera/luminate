<? class Loader{
    function loadModule($index,$name,$id=-1){
        if(!class_exists($name))include(MODULEPATH.$index);else break;
        if(!class_exists($name))throw new Exception("No such class '".$name."'! Is your module named correctly?");
        $m = new $name();
        $m->id=$id;
        
        $this->defineGlobally($m);
        $this->loadDependencies($m);
        return $m;
    }
    
    function defineGlobally(&$m){
        $short = $m->short;
        if($short!=""){
            if(!isset($$short)){
                global $$short;
                $$short = $m;
            }
            return $short;
        }else return "";
    }
    
    function loadDependencies($m){
        foreach($m->required as $name=>$index){
            $this->loadModule($index,$name);
        }
    }
} ?>
