<?
class Admin extends Module{
public static $name="Admin";
public static $version=2.01;
public static $short='acp';
public static $required=array("Themes","Auth");
public static $hooks=array("foo");
    
function __construct(){
}

function buildMenu($menu){
    global $a,$k;
    if($a->check('admin.panel'))
        $menu[]=array('Admin',$k->url("admin",""),"float:right;",array(
                                array('Panel',   $k->url("admin","Panel")),
                                array('Options', $k->url("admin","Options")),
                                array('Log',     $k->url("admin","Log")),
                                array('Modules', $k->url("admin","Modules")),
                                array('Hooks',   $k->url("admin","Hooks"))
                            ));
    return $menu;
}

function displayPage($params){
    global $a,$t,$l,$site;
    if($site=="index")$site="Panel";
    $t->openPage("Administration");
    $this->displayNavbar();
    
    if($a->check('admin.panel')){
        switch($site){
            case 'Options': if($a->check("admin.options"))$this->displayOptionsPage();break;
            case 'Log':     if($a->check("admin.log"))$this->displayLogPage();break;
            case 'Modules': if($a->check("admin.modules"))$this->displayModulesPage();break;
            case 'Hooks':   if($a->check("admin.hooks"))$this->displayHooksPage();break;
            case 'Panel':   if($a->check("admin.panel"))$this->displayPanelPage();break;
            default:        if($a->check("admin.panel"))$l->triggerHook("ADMIN".$site,$this);break;
        }
    }else{
        echo("<center>You are not authorized to view this page.</center>");
    }
    $t->closePage();
}

function displayNavbar(){
    global $site,$k,$a;
    $pages=array('Panel','Options','Log','Modules','Hooks');
    ?><div id='pageNav'>
        <h1 class="sectionheader">Administration</h1>
        <div class="tabs">
            <? foreach($pages as $page){
                if($a->check("admin.".$page)){
                    if($page==$site)echo('<a href="'.$k->url("admin",$page).'" class="tab activated">'.$page.'</a>');
                    else            echo('<a href="'.$k->url("admin",$page).'" class="tab">'.$page.'</a>');
                }
            }if(!in_array($site, $pages))echo('<a href="'.$k->url("admin",$site).'" class="tab activated">'.$site.'</a>'); ?>
        </div>
    </div><?
}

function displayPanelPage(){
    global $l;
    $l->triggerHook("PANELdisplay",$this);
}

function displayOptionsPage(){
    global $k;
    if($_POST['action']=="Add"){
        $this->addOption($_POST['key'],$_POST['value'],$_POST['type']);
        $err[0]="Key added.";
    }
    if($_POST['action']=="Save"){
        $options = DataModel::getData("ms_options", "SELECT `key`,`value` FROM ms_options");
        foreach($options as $o){
            if(is_array($_POST['val'.$o->key]))$_POST['val'.$o->key]=implode(";",$_POST['val'.$o->key]);
            if($_POST['act'.$o->key]=="delete"){
                $this->deleteOption($o->key);
            }else if($_POST['val'.$o->key]!=$o->value){
                $this->setOption($o->key,$_POST['val'.$o->key]);
            }
        }
        $err[1]="Data saved.";
    }
    
    $options = DataModel::getData("ms_options", "SELECT `key`,`value`,`type` FROM ms_options");
    ?>
    <form action="#" method="post" class="box" style="display:block">
        Add a new key: 
        <input autocomplete="off" type="text" name="key" placeholder="Key" />
        <input autocomplete="off" type="text" name="value" placeholder="Value" />
        <select name="type">
            <option value="s" >String</option>
            <option value="i" >Number</option>
            <option value="l" >List</option>
            <option value="t" >Text</option>
        </select>
        <input type="submit" name="action" value="Add" /><span style="color:red;"><?=$err[0]?></span>
    </form>
    <form action="#" method="post" class="box" style="display:block"><?
    foreach($options as $o){
        echo('<div class="datarow">'.$o->key.'<div class="flRight">');
        switch($o->type){
            case 'i':echo('<input autocomplete="off" type="number" class="number" name="val'.$o->key.'" value="'.$o->value.'" />');break;
            case 's':echo('<input autocomplete="off" type="text" class="string" name="val'.$o->key.'" value="'.$o->value.'" />');break;
            case 't':echo('<textarea type="text" class="text" name="val'.$o->key.'">'.$o->value.'</textarea>');break;
            case 'l':$vals=explode(";",$o->value);$k->interactiveList("val".$o->key,$vals,$vals,$vals,true);break;
        }
        echo('<select name="act'.$o->key.'"><option value="edit">Edit</option><option value="delete">Delete</otpion></select>');
        echo('</div></div><br class="clear" />');
    }
    ?><input type="submit" name="action" value="Save" />
    <span style="color:red;"><?=$err[1]?></span></form><?
}

function displayLogPage(){
    global $k;
    if($_POST['action']=="Clear Log"){
        $this->clearLog();
        $err="Log cleared.";
    }
    $logs = DataModel::getData("ms_log","SELECT subject,time,user FROM ms_log ORDER BY time DESC");
    ?><div class="box" style="display:block;">
        <form method="post" action="#"><input type="submit" name="action" value="Clear Log" /></form>
        <span style="color:red;"><?=$err?></span>
    <? if(is_array($logs)){ ?>
    <table>
        <thead>
            <tr><th style="width:150px">Time</th>
                <th style="width:150px">User</th>
                <th>Action</th></tr>
        </thead>
        <tbody>
            <? foreach($logs as $l){
                echo('<tr><td>'.$k->toDate($l->time).'</td><td>'.$l->user.'</td><td>'.$l->subject.'</td></tr>');
            } ?>
        </tbody>
    </table>
    <? }else echo('<center>No log records available.</center>');
    ?></div><?
}

function displayModulesPage(){
    global $k,$MODULECACHE;
    if($_POST['action']=="Install"){
        try{
            $k->uploadFile("archive",TEMPPATH,5000,array("application/zip","application/x-zip","application/x-zip-compressed",
                                                            "application/octet-stream","application/x-compress",
                                                            "application/x-compressed","multipart/x-zip"),true,"package.zip");
            $this->installModule();
        }catch(Exception $e){
            $k->err("Error Code: ".$e->getCode()."<br>Error Message: ".$e->getMessage()."<br>Strack Trace: <br>".$e->getTraceAsString());
        }
    }
    if($_POST['action']=="Delete"){
        $this->deleteModule($_POST['name']);
        $err[2]="Module deleted.";
    }
    if($_POST['action']=="Add"){
        $this->addModule($_POST['name'],$_POST['subject']);
        $err[3]="Module added.";
    }
    
    $modules = DataModel::getData("ms_modules", "SELECT name,subject FROM ms_modules");
    ?><form action="#" method="post" class="box">
        Add module entry:<br />
        <input autocomplete="off" type="text" name="name" placeholder="Name" />
        <input type="submit" name="action" value="Add" /><span style="color:red;"><?=$err[3]?></span><br />
        <textarea name="subject" placeholder="Description"></textarea>
    </form>
    <form action="#" method="post" class="box" enctype="multipart/form-data">
        Install from package:<br />
        <input type="file" name="archive" accept="application/zip,application/x-zip,application/x-zip-compressed,
                                                  application/octet-stream,application/x-compress,
                                                  application/x-compressed,multipart/x-zip" />
        <input type="submit" name="action" value="Install" />
        <span style="color:red;"><?=$err[1]?></span>
    </form>
    <div class="box" style="display:block;">
        <div style="color:red;"><?=$err[2]?></div>
        <? if(is_array($modules)){
        foreach($modules as $m){
            echo('<form class="datarow"><input type="submit" name="action" value="Delete" /> <b>'.$m->name.'</b>');
            if(!class_exists($m->name))include(MODULEPATH.$MODULECACHE[$m->name]);$vars=get_class_vars($m->name);
            echo(' v'.$vars['version'].' by '.$vars['author']);
            if($vars['required'][0]!=''){
                sort($vars['required']);
                echo('<br />&nbsp;&nbsp; Requires: '.implode(", ",$vars['required']));
            }
            echo('<blockquote>'.$m->subject.'</blockquote>');
            echo('<input type="hidden" name="name" value="'.$m->name.'" /></form>');
        }}else echo('<center>No modules registered!</center>'); ?>
    </div>
    <?
}

function displayHooksPage(){
    global $k;
    if($_POST['action']=="Register"){
        $this->registerHook($_POST['source'],$_POST['hook'],$_POST['destination'],$_POST['function']);
        $err[0]="Hook registered.";
    }
    if($_POST['action']=="Remove"){
        $this->removeHook($_POST['source'],$_POST['hook'],$_POST['destination'],$_POST['function']);
        $err[1]="Hook removed.";
    }
    
    $hooks = DataModel::getData("ms_hooks", "SELECT `source`,`hook`,`destination`,`function` FROM ms_hooks");
    $modules = DataModel::getData("ms_modules", "SELECT `name` FROM ms_modules");
    ?><form method="post" action="#" class="box">
        Source: <? $k->printSelectObj("source",$modules,"name","name"); ?>
        Destination: <? $k->printSelectObj("destination",$modules,"name","name"); ?><br />
        <label>Hook:</label> <input autocomplete="off" type="text" name="hook" placeholder="HOOK" /><br />
        <label>Function:</label> <input autocomplete="off" type="text" name="function" placeholder="functionName" /><br />
        <input type="submit" name="action" value="Register" /><span style="color:red;"><?=$err[0]?></span>
    </form>
    <div class="box" style="display:block;">
    <? if(is_array($hooks)){ ?>
    <span style="color:red;"><?=$err[1]?></span><table>
        <thead>
            <tr><th style="width:150px">Source</th>
                <th style="width:150px">Destination</th>
                <th style="width:150px">Hook</th>
                <th>Function</th></tr>
        </thead>
        <tbody>
            <? foreach($hooks as $h){
                echo('<tr><td>'.$h->source.'</td><td>'.$h->destination.'</td><td>'.$h->hook.'</td><td>'.$h->function.'</td></tr>');
            } ?>
        </tbody>
    </table>
    <? }else echo('<center>No hooks registered?!</center>');
    ?></div><?
}

function addOption($key,$value,$type='s'){
    global $c,$k,$l;
    $c->query("INSERT INTO ms_options VALUES(?,?,?)",array($key,$value,$type));
    $l->triggerHook("OPTIONadded",$this,array($key,$value));
    $k->log('Option key \''.$key.'\' ('.$type.') added with value \''.$value.'\'.');
}

function setOption($key,$value){
    global $c,$k,$l;
    $c->query("UPDATE ms_options SET `value`=? WHERE `key`=?",array($value,$key));
    $l->triggerHook("OPTIONset",$this,array($key,$value));
    $k->log('Option key \''.$key.'\' changed to \''.$value.'\'.');
}

function deleteOption($key){
    global $c,$k,$l;
    $c->query("DELETE FROM ms_options WHERE `key`=?",array($key));
    $l->triggerHook("OPTIONdeleted",$this,array($key));
    $k->log('Option key \''.$key.'\' deleted.');
}

function clearLog(){
    global $c,$k,$l;
    $c->query("TRUNCATE ms_log",array());
    $l->triggerHook("LOGcleared",$this);
    $k->log('Log cleared.');
}

//TODO: Test
function installModule(){
    global $k,$c,$l;
    $k->pf('<div class="box"><b>Starting installation...</b><br />');
    $k->pf('Extracting archive...');
    mkdir(TEMPPATH.'module/');
    if($k->unzipFile(TEMPPATH.'package.zip',TEMPPATH.'module/')){
        $k->pf('Reading configuration...');
        $config = $k->toKeyArray(file_get_contents(TEMPPATH.'module/install.conf'),"\n",":");
        if($config!=FALSE&&$config['name']!=''&&$config['install']!=''){
            $k->pf('Creating database entry...');
            $c->query('INSERT INTO ms_modules VALUES(?,?)',$config['name'],$config['description']);
            $k->pf('Moving files...');
            if(rename(TEMPPATH.'module/',MODULEPATH.$config['name'].'/')){
                $k->pf('Generating module cache...');
                $k->generateModuleCache();
                $k->pf('Running installation script...');
                //include(MODULEPATH.$config['name'].'/'.$config['install']);
                ?><div id="modalWindow" class="jqmWindow">
                    <div id="jqmTitle">
                        <button class="jqmClose">Close</button>
                    </div>
                    <iframe id="installContent" src=""></iframe>
                </div><script type="text/javascript">
                    $(document).ready(function() {
                        var loadInIframeModal = function(hash){
                            var $modal = $(hash.w);
                            var $modalContent = $("iframe", $modal);
                            $modalContent.html('').attr('src', '<?=TROOT.'modules/'.$config['name'].'/'.$config['install']?>');
                        }
                        $('#modalWindow').jqm({
                            modal: true,
                            target: '#installContent',
                            onShow:  loadInIframeModal
                        }).jqmShow();
                    });
                </script><?
                $k->pf('<b>Installation complete!</b>');
                $l->triggerHook("MODULEinstalled",$this,array($config['name']));
                $k->log("Module '".$config['name']."' installed.");
            }else $k->pf('<b>Failed to move the files!</b>');
        }else $k->pf('<b>Failed to read the configuration file!</b>');
    }else $k->pf('<b>Failed to extract the archive!</b>');
    $k->pf('</div>');
}

function addModule($name,$description){
    global $c,$k,$l;
    $c->query("INSERT INTO ms_modules VALUES(?,?)",array($name,$description));
    $l->triggerHook("MODULEadded",$this,array($name));
    $k->log("Module '".$name."' added.");
}

function deleteModule($name){
    global $c,$k,$l;
    $c->query("DELETE FROM ms_modules WHERE name=?",array($name));
    $l->triggerHook("MODULEdeleted",$this,array($name));
    $k->log("Module '".$name."' deleted.");
}

function registerHook($source,$hook,$destination,$function){
    global $c,$k,$l;
    $c->query("INSERT INTO ms_hooks VALUES(?,?,?,?)",array($source,$hook,$destination,$function));
    $l->triggerHook("HOOKregistered",$this,array($source,$hook,$destination,$function));
    $k->log("Hook ".$source.'::'.$hook.' => '.$destination.'::'.$function.' added.');
}

function removeHook($source,$hook,$destination,$function){
    global $c,$k,$l;
    $c->query("DELETE FROM ms_hooks WHERE source=? AND hook=? AND destination=? AND function=?",array($source,$hook,$destination,$function));
    $l->triggerHook("HOOKremoved",$this,array($source,$hook,$destination,$function));
    $k->log("Hook ".$source.'::'.$hook.' => '.$destination.'::'.$function.' deleted.');
}

}
?>