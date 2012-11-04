<? class Loader{
    function loadModule($name){
        global $k,$MODULES,$MODULECACHE;
        if(!class_exists($name))include(MODULEPATH.$MODULECACHE[$name]);
        if(!class_exists($name))throw new Exception("No such class '".$name."'! Is your module named correctly?");
        if(array_key_exists($name::$name,$MODULES)){
            $short = $name::$short;
            global $$short;
            return $$short;
        }
        if($GLOBALS[$name::$short]!="")throw new Exception("Error globalizing '".$name::$name."': $".$name::$short." has already been defined!");
        $this->loadDependencies($name);
        $m = new $name();
        
        if(count($m::$hooks)>0)$loadHooks=true;else $loadHooks=false;
        if($loadHooks)$this->loadHooks($m);
        $this->defineGlobally($m);
        if($loadHooks)$this->triggerHook("INIT",$m,array($k->getMicrotime()));
        $MODULES[$name::$name]=$name::$short;
        return $m;
    }
    
    function moduleExists($name){
        global $MODULECACHE;
        if($MODULECACHE[$name]=='')return false;
        return true;
    }
    
    function defineGlobally(&$m){
        $short = $m::$short;
        if($short!=""){
            if(!isset($$short)){
                global $$short;
                $$short = $m;
            }
            return $short;
        }else return "";
    }
    
    function loadDependencies($m){
        foreach($m::$required as $name){
            $this->loadModule($name);
        }
    }
    
    function loadHooks($m){
        if(count($m::$hooks)>0){
            global $c;
            $hooks = $c->getData("SELECT hook,destination,function FROM ms_hooks WHERE source=?",array($m::$name));
            $temp = array();
            foreach($hooks as $hook){ //Turn into {"hook" => {{"module", "function"}, {"module2", "function2"}}} format
                $temp[$hook['hook']][] = array($hook['destination'],$hook['function']);
            }
            $m::$hooks=$temp;
        }
    }
    
    function triggerPARSE($source,$text,$formatted=true,$allowRaw=false,$blockedTags=array(),$options=array()){
        $args=array("text"=>$text,"source"=>$source,"formatted"=>$formatted,"allowRaw"=>$allowRaw,"blockedTags"=>$blockedTags);
        $args=$args+$options;
        $ret = $this->triggerHookSequentially("PARSE","CORE",$args);
        return $ret['text'];
    }
    
    function triggerPOST($source,$toModule,$sectionID,$postBody,$toUser="",$permalink="",$postTitle="",$type=""){
        $args=array("toModule"=>$toModule,"sectionID"=>$sectionID,"postBody"=>$postBody,
                    "toUser"=>$toUser,"postTitle"=>$postTitle,"permalink"=>$permalink,"type"=>$type);
        $this->triggerHook("POST",$source,$args);
    }
    
    function triggerHook($hook,$source,$args=array(),$modules=array(),$setdominating=false){
        global $k,$DOMINATINGMODULE;
        if(is_string($source))$source = $this->loadModule($source);
        if(array_key_exists($hook,$source::$hooks)){
            $returns = array();
            if(count($modules)==0)$modules=$source::$hooks[$hook];
            foreach($modules as $module){
                try{
                    $mod = $this->loadModule($module[0]);
                    if($setdominating)$DOMINATINGMODULE=$mod;
                    $result = call_user_func(array($mod,$module[1]), $args);
                    if($result!=false&&$result!=="")$returns[]=$result;
                }catch(Exception $e){
                    $k->err($e->getMessage()."\n\n".$e->getTraceAsString());
                }
            }
            return $returns;
        }else{
            return null;
        }
    }
    
    function triggerHookSequentially($hook,$source,$args="",$modules=array()){
        global $k;
        if(is_string($source))$source = $this->loadModule($source);
        if(array_key_exists($hook,$source::$hooks)){
            if(count($modules)==0)$modules=$source::$hooks[$hook];
            foreach($modules as $module){
                try{
                    $mod = $this->loadModule($module[0]);
                    $args = call_user_func(array($mod,$module[1]), $args);
                }catch(Exception $e){
                    $k->err($e->getMessage()."\n\n".$e->getTraceAsString());
                }
            }
            return $args;
        }else{
            return $args;
        }
    }


    function loadPluginCache($module){
        global $PLUGINCACHE,$PLUGINTRIGGERS,$MODULECACHE;
        $path = MODULEPATH.dirname($MODULECACHE[$module]);
        if(!file_exists($path.'/plugincache')){
            Toolkit::generatePluginCache($module);
        }
        $PLUGINCACHE[$module] = unserialize($path.'/plugincache');
        $PLUGINTRIGGERS[$module] = unserialize($path.'/triggercache');
    }

    function loadPlugin($module,$name){
        global $PLUGINS,$PLUGINCACHE,$MODULES,$MODULECACHE;
        if(!is_string($module))$module = $module::$name;
        if(!array_key_exists($module, $PLUGINCACHE))$this->loadPluginCache($module);
        if(!class_exists($name))include(MODULEPATH.dirname($MODULECACHE[$module]).'plugins/'.$PLUGINCACHE[$module][$name]);
        if(!class_exists($name))throw new Exception("No such class '".$name."'! Is your plugin named correctly?");
        if(array_key_exists($name::$name,$PLUGINS[$module])){
            return $PLUGINS[$module][$name::$name];
        }

        $p = new $name($MODULES[$module]);
        $PLUGINS[$module][$name::$name] = $p;
        return $p;
    }

    function triggerPlugin($module,$trigger,$args=array()){
        global $PLUGINTRIGGERS;
        if(!is_string($module))$module = $module::$name;
        if(!array_key_exists($module, $PLUGINCACHE))$this->loadPluginCache($module);
        $plugins = $PLUGINTRIGGERS[$module][$trigger];
        foreach($plugins as $plugin){
            $p = $this->loadPlugin($module,$plugin[0]);
            call_user_func($p,$plugin[1], $args);
        }
    }
    
} ?>
