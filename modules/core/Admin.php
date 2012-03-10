<?
class Admin extends Module{
public static $name="Admin";
public static $version=2.01;
public static $short='acp';
public static $required=array("Themes","Auth");
    
function __construct(){
}

function displayPage($params){
    global $a,$t,$site;
    if($site=="index")$site="Panel";
    $t->openPage("Administration");
    $this->displayNavbar();
    
    if($a->check('admin.panel')){
        switch($site){
            case 'Options': $this->displayOptionsPage();break;
            case 'Log':     $this->displayLogPage();break;
            case 'Modules': $this->displayModulesPage();break;
            case 'Hooks':   $this->displayHooksPage();break;
            default:        $this->displayPanelPage();break;
        }
    }else{
        echo("<center>You are not authorized to view this page.</center>");
    }
    $t->closePage();
}

function displayNavbar(){
    global $site,$k;
    $pages=array('Panel','Options','Log','Modules','Hooks');
    ?><div id='pageNav'>
        <div class="description">Administration</div>
        <div class="tabs">
            <? foreach($pages as $page){
                if($page==$site)echo('<a href="'.$k->url("admin",$page).'" class="tab activated">'.$page.'</a>');
                else            echo('<a href="'.$k->url("admin",$page).'" class="tab">'.$page.'</a>');
            } ?>
        </div>
    </div><?
}

function displayPanelPage(){
    global $l;
    $l->triggerHook("panelPage",$this);
}

function displayOptionsPage(){
    global $k,$c;
    if($_POST['action']=="Add"){
        $c->query("INSERT INTO ms_options VALUES(?,?,?)",array($_POST['key'],$_POST['value'],$_POST['type']));
        $err[0]="Key added.";
    }
    if($_POST['action']=="Save"){
        $options = DataModel::getData("ms_options", "SELECT `key`,`value` FROM ms_options");
        foreach($options as $o){
            if(is_array($_POST['val'.$o->key]))$_POST['val'.$o->key]=implode(";",$_POST['val'.$o->key]);
            if($_POST['act'.$o->key]=="delete")
                $c->query("DELETE FROM ms_options WHERE `key`=?",array($o->key));
            else if($_POST['val'.$o->key]!=$o->value)
                $c->query("UPDATE ms_options SET `value`=? WHERE `key`=?",array($_POST['val'.$o->key],$o->key));
        }
        $err[1]="Data saved.";
    }
    
    $options = DataModel::getData("ms_options", "SELECT `key`,`value`,`type` FROM ms_options");
    ?>
    <form action="#" method="post" class="box" style="display:block">
        Add a new key: 
        <input type="text" name="key" placeholder="Key" />
        <input type="text" name="value" placeholder="Value" />
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
            case 'i':echo('<input type="text" class="number" name="val'.$o->key.'" value="'.$o->value.'" />');break;
            case 's':echo('<input type="text" class="string" name="val'.$o->key.'" value="'.$o->value.'" />');break;
            case 't':echo('<textarea type="text" class="text" name="val'.$o->key.'">'.$o->value.'</textarea>');break;
            case 'l':$vals=explode(";",$o->value);$k->interactiveList("val".$o->key,$vals,$vals,$vals,true);break;
        }
        echo('<select name="act"'.$o->key.'><option value="edit">Edit</option><option value="delete">Delete</otpion></select>');
        echo('</div></div><br class="clear" />');
    }
    ?><input type="submit" name="action" value="Save" />
    <span style="color:red;"><?=$err[1]?></span></form><?
}

function displayLogPage(){
    global $k,$c;
    if($_POST['action']=="Clear Log"){
        $c->query("TRUNCATE ms_log",array());
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
    global $k,$c;
    if($_POST['action']=="Install"){
        //FIXME: Add installation procedure.
        //Upload file to temp directory
        //Unpack into temp directory
        //Read config file
        //Move folder to modules directory
        //Open popup and run installation file
        //Clear temp directory
        $err[1]="Module unpacked and installed.";
    }
    if($_POST['action']=="Delete"){
        $c->query("DELETE FROM ms_modules WHERE name=?",array($_POST['name']));
        $err[2]="Module deleted.";
    }
    if($_POST['action']=="Add"){
        $c->query("INSERT INTO ms_modules VALUES(?,?)",array($_POST['name'],$_POST['subject']));
        $err[3]="Module added.";
    }
    
    $modules = DataModel::getData("ms_modules", "SELECT name,subject FROM ms_modules");
    ?><form action="#" method="post" class="box">
        Add module entry:<br />
        <input type="text" name="name" placeholder="Name" />
        <input type="submit" name="action" value="Add" /><span style="color:red;"><?=$err[3]?></span><br />
        <textarea name="subject" placeholder="Description"></textarea>
    </form>
    <form action="#" method="post" class="box" enctype="multipart/form-data">
        Install from package:<br />
        <input type="file" name="file" /><input type="submit" name="action" value="Install" />
        <span style="color:red;"><?=$err[1]?></span>
    </form>
    <div class="box" style="display:block;">
        <div style="color:red;"><?=$err[2]?></div>
        <? if(is_array($modules)){
        foreach($modules as $m){
            echo('<form class="datarow"><input type="submit" name="action" value="Delete" /> <b>'.$m->name.'</b>');
            echo('<blockquote>'.$m->subject.'</blockquote><input type="hidden" name="name" value="'.$m->name.'" /></form>');
        }}else echo('<center>No modules registered!</center>'); ?>
    </div>
    <?
}

function displayHooksPage(){
    global $c,$k;
    if($_POST['action']=="Register"){
        $c->query("INSERT INTO ms_hooks VALUES(?,?,?,?)",array($_POST['source'],$_POST['hook'],
                                                               $_POST['destination'],$_POST['function']));
        $err[0]="Hook registered.";
    }
    if($_POST['action']=="Remove"){
        $c->query("DELETE FROM ms_hooks WHERE source=? AND hook=? AND destination=? AND function=?",array(
                                                               $_POST['source'],$_POST['hook'],
                                                               $_POST['destination'],$_POST['function']));
        $err[1]="Hook removed.";
    }
    
    $hooks = DataModel::getData("ms_hooks", "SELECT `source`,`hook`,`destination`,`function` FROM ms_hooks");
    $modules = DataModel::getData("ms_modules", "SELECT `name` FROM ms_modules");
    ?><form method="post" action="#" class="box">
        Source: <? $k->printSelectObj("source",$modules,"name","name"); ?>
        Destination: <? $k->printSelectObj("destination",$modules,"name","name"); ?><br />
        <label>Hook:</label> <input type="text" name="hook" placeholder="HOOK" /><br />
        <label>Function:</label> <input type="text" name="function" placeholder="functionName" /><br />
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

}
?>