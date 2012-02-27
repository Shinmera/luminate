<?
class ACP{

function __construct(){
}

function loadSql(){
    return XERR_OK;
}

function displayPage($params){
    global $a,$c,$k,$p,$s,$t,$api;
    $t->openPage("ACP");

    ?><div class='title'>Administration</div><?
    if($a->check('admin.panel')){

        if($params[0]!=""){?><a href='/admin'><center><< Back to the main ACP >></center></a><br /><? }

        if(in_array($params[0],$c->msMTitle)){
            $mod = $api->getInstance($params[0]);
            $mod->displayAdmin(array_splice($params,1));
        }else{

        ?><table width="100%" class="admin">
        <tr><td width=20%></td><td width=80%></td></tr><?
        $temp = explode(";",$c->o['activated_modules']);
        for($i=0;$i<count($temp);$i++){
            $id = array_search($temp[$i],$c->msMID);
            $mod = $api->getInstance($temp[$i]);
            $temp2 = $mod->displayAdmin("");
            if(count($temp2)>1){
                echo("<tr class='admin_module'><td><b><span style='font-size:20pt;'><a href='/admin/ACP/modules#".$c->msMTitle[$id]."' name='".$c->msMTitle[$id]."'>".$c->msMTitle[$id]."</a></span></b></td><td></td></tr>");
                foreach($temp2 as $link => $name){
                    $desc="";
                    if(strstr($name,"|")!=FALSE){$desc=substr($name,strpos($name,"|")+1);$name=substr($name,0,strpos($name,"|"));}
                    if($name=="<--->") echo("<tr height='2px'></tr>");
                    else echo("<tr class='admin_link'><td>><a href='".$k->url('','admin/'.$c->msMTitle[$id].'/'.$link."'>".$name)."</a></td><td>".$desc."</td></tr>");
                }
            }
        }
        ?></table><?
        }
    }else{
        echo("<center>You are not authorized to view this page.</center>");
    }
    $t->closePage();
}

function displayAdmin($params){
    global $a,$c,$k,$p,$s,$t;
    switch($params[0]){
        case 'log':
            if(!$a->check('admin.log'))return;
            if($params[1]=="clear"){
                $c->query("TRUNCATE TABLE ms_log",array());
                $k->log("Log cleared.");
            }$c->loadLog();?>
            <form action="/admin/ACP/log/clear" method="post"><input type="submit" value="Clear Log"></form><br />
            <code><?
        for($i=0;$i<count($c->msLID);$i++){
                    echo($k->toDate($c->msLTime[$i])." ".$k->getUDN($c->msLOwner[$i])." > ".$p->deparseAll($c->msLSubject[$i])."<br />");
                }?>
            </code>
            <?
            break;
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'modules':
        if(!$a->check('admin.modules'))return;
        if($params[1]==""){
        ?><div style="float:left;">Install a new module:<br />
        <form action="/admin/ACP/modules/install" method="post" enctype="multipart/form-data"><input type="file" name="varkey"><input type="submit" value="Submit"></form></div>
        <div style="float:right;">Manually add a new module:<br />
        <form action="/admin/ACP/modules/add" method="post"><input type="text" name="varkey"><input type="submit" value="Submit"></form></div>
        <br clear="all">
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="15%">Title</td><td width="40%">Description</td><td width="10%">Subdomain</td><td width="10%">Index</td><td wdith="15%">Provides</td><td width="10%">Actions</td></tr><?
        for($i=0;$i<count($c->msMID);$i++){
            if(in_array($c->msMID[$i],explode(";",$c->o['activated_modules'])))$temp = "#0099FF"; else $temp = "#CC0000";
            ?><tr bgcolor="<?=$temp?>"><td><a name='<?=$c->msMTitle[$i]?>'><?=$c->msMTitle[$i]?></a></td>
                        <td><?=$p->deparseAll($c->msMSubject[$i]);?></td><td><?=$c->msMSub[$i]?></td><td><?=$c->msMIndex[$i]?></td><td><?=$p->deparseAll($c->msMProvides[$i])?></td><td>
                        <form action="/admin/ACP/modules/toggle" method="post"><input type="hidden" name="varkey" value="<?=$c->msMID[$i]?>"><input type="submit" value="Toggle"></form>
                        <form action="/admin/ACP/modules/edit" method="post"><input type="hidden" name="varkey" value="<?=$c->msMID[$i]?>"><input type="submit" value="Edit"></form>
                        <form action="/admin/ACP/modules/del" method="post"><input type="hidden" name="varkey" value="<?=$c->msMID[$i]?>"><input type="submit" value="Delete"></form></td></tr><?
        }
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'toggle':
                if(in_array($_POST['varkey'],explode(";",$c->o['activated_modules']))){
                    $temp = "deactivated";
                    $c->query("UPDATE ms_options SET `value`=? WHERE `key`='activated_modules'",array($k->removeFromList($_POST['varkey'],$c->o['activated_modules'])));
                }else{
                    $temp = "activated";
                    $c->query("UPDATE ms_options SET `value`=? WHERE `key`='activated_modules'",array($c->o['activated_modules'].";".$_POST['varkey']));
                }
                                $k->log("Module ID".$_POST['varkey']." ".$temp);
                echo("<center><span style='color:red'>Module ".$temp.".</span><br /><a href='/admin/ACP/modules'>Return</a></center>");
                break;
            case 'add':
                if($_POST['varindex']==""){
                    ?><center>
                    <form action="/admin/ACP/modules/add" method="post">
                        Add new module "<?=$_POST['varkey']?>":<br />
                        <table><tr></tr>
                        <tr><td>Description:</td><td><textarea rows="3" name='vardesc' style='width: 300px;'></textarea></td></tr>
                        <tr><td>Subdomain:</td><td><input type="text" name="varsub" style='width: 300px;'></td></tr>
                        <tr><td>Index Page:</td><td><input type="text" name="varindex" style='width: 300px;'></td></tr>
                        <tr><td>Provides:</td><td><textarea rows="5" name="varprovides" style='width: 300px;'></textarea></td></tr>
                        </table>
                        <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                        <input type="submit" value="Submit">
                    </form></center><?
                }else{
                    $c->query("INSERT INTO ms_modules VALUES(NULL,?,?,?,?,?)",array(
                                                            $_POST['varkey'],$p->enparse($_POST['vardesc']),$_POST['varsub'],$_POST['varindex'],$p->enparseNoBreak($_POST['varprovides'])));
                    echo("<center><span style='color:red'>Module Added.</span><br /><a href='/admin/ACP/modules'>Return</a></center>");
                                        $k->log("Module ".$_POST['varkey'].' added.');
                }
                break;
            case 'install':
                $e=$k->uploadFile("varkey",ROOT.TEMPPATH,500,array("file/zip"));
                if(!is_numeric($e)){
                    $k->unzipFile($e,ROOT.TEMPPATH);
                    $file=file_get_contents(ROOT.TEMPPATH."info.cfg");$file=explode(";",$file);
                    $info=array();
                    for($i=0;$i<count($file);$i++){
                        $key=trim(substr($file[$i],0,strpos($file[$i],":")));
                        $value=trim(substr($file[$i],strpos($file[$i],":"+1)));
                        $info[$key]=$value;
                    }
                    $c->query("INSERT INTO ms_modules VALUES(NULL,?,?,?,?,?)",array(
                                                $info['title'],$p->enparse($info['description']),$info['subdomain'],$info['index'],$p->enparseNoBreak($info['provides'])));
                    echo("<center><span style='color:red'>Module Installed.</span><br /><a href='/admin/ACP/modules'>Return</a></center>");
                                        $k->log("Module ".$info['title']." installed.");
                }else{
                    echo("<center><span style='color:red'>Failed. (E".$e.")</span><br /><a href='/admin/ACP/modules'>Return</a></center>");
                }
                break;
            case 'del':
                $c->query("DELETE FROM ms_modules WHERE `moduleID`=?",array($_POST['varkey']));
                echo("<center><span style='color:red'>Module Deleted.</span><br /><a href='/admin/ACP/modules'>Return</a></center>");
                                $k->log("Module ID".$_POST['varkey'].' deleted.');
                break;
            case 'edit':
                if($_POST['varname']==""){
                    $temp = array_search($_POST['varkey'],$c->msMID);
                    ?><center>
                    <form action="/admin/ACP/modules/edit" method="post">
                        <table><tr></tr>
                        <tr><td>Name:</td><td><input type="text" name="varname" style='width: 300px;' value="<?=$c->msMTitle[$temp]?>"></td></tr>
                        <tr><td>Description:</td><td><textarea rows="3" name='vardesc' style='width: 300px;'><?=$c->msMSubject[$temp]?></textarea></td></tr>
                        <tr><td>Subdomain:</td><td><input type="text" name="varsub" style='width: 300px;' value="<?=$c->msMSub[$temp]?>"></td></tr>
                        <tr><td>Index Page:</td><td><input type="text" name="varindex" style='width: 300px;' value="<?=$c->msMIndex[$temp]?>"></td></tr>
                        <tr><td>Provides:</td><td><textarea rows="5" name="varprovides" style='width: 300px;'><?=$c->msMProvides[$temp]?></textarea></td></tr>
                        </table>
                        <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                        <input type="submit" value="Submit">
                    </form></center><?
                }else{
                    $c->query("UPDATE ms_modules SET title=?, subject=?, subdomain=?,`index`=?, provides=? WHERE moduleID=?",array(
                                                 $_POST['varname'],$p->enparse($_POST['vardesc']),$_POST['varsub'],$_POST['varindex'],$p->enparseNoBreak($_POST['varprovides']),$_POST['varkey']));
                    echo("<center><span style='color:red'>Module Edited.</span><br /><a href='/admin/ACP/modules'>Return</a></center>");
                                        $k->log("Module ".$_POST['varname']." edited.");
                }
                break;
            }
        }
        break;
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'settings':
        if(!$a->check('admin.settings'))return;
        if($params[1]==""||$params[1]=="all"){
        ksort($c->o);
        ?>Add a new variable:<br />
        <form action="/admin/ACP/settings/add" method="post"><input type="text" name="varkey"><input type="text" name="varval"><input type="submit" value="Submit"></form>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="30%">Key</td><td width="60%">Value</td><td width="10%">Actions</td></tr><?
        foreach($c->o as $key => $value){
            if(substr($key,0,4)!="API_"||$params[1]=="all"){
            ?><tr bgcolor="#0099FF"><td><?=$key?></td><td><span style="font-family:'Courier New', Courier, mono;font-weight:bold;"><?=$k->convertHTML($value);?></span></td><td>
                        <form action="/admin/ACP/settings/edit" method="post"><input type="hidden" name="varkey" value="<?=$key?>"><input type="submit" value="Edit"></form>
                        <form action="/admin/ACP/settings/del" method="post"><input type="hidden" name="varkey" value="<?=$key?>"><input type="submit" value="Delete"></form></td></tr><?
        }}
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'add':
                $this->apiCall("addKey",array("key"=>$_POST['varkey'],"value"=>$_POST['varval']),$c->o['API_ADDKEY_TOKEN']);
                echo("<center><span style='color:red'>Variable Added.</span><br /><a href='/admin/ACP/settings'>Return</a></center>");
                break;
            case 'del':
                $this->apiCall("delKey",array("key"=>$_POST['varkey']),$c->o['API_DELKEY_TOKEN']);
                echo("<center><span style='color:red'>Variable Deleted.</span><br /><a href='/admin/ACP/settings'>Return</a></center>");
                break;
            case 'edit':
                if($_POST['varval']==""){
                    ?><center>Enter the new value:
                    <form action="/admin/ACP/settings/edit" method="post">
                        <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                        <TEXTAREA name="varval" rows="30" cols="70"><?=$c->o[$_POST['varkey']]?></TEXTAREA><br />
                        <input type="submit" value="Submit">
                    </form></center><?
                }else{
                    $c->query("UPDATE ms_options SET `value`=? WHERE `key`=?",array($_POST['varval'],$_POST['varkey']));
                    echo("<center><span style='color:red'>Variable Edited.</span><br /><a href='/admin/ACP/settings'>Return</a></center>");
                                        $k->log("Variable ".$_POST['varkey']." edited.");
                }
                break;
            }
        }
        break;
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'bbcode':
        if(!$a->check('admin.bbcode'))return;
        $c->loadBBCode();
        if($params[1]==""){
        ?>Add a new code:<br />
        <form action="/admin/ACP/bbcode/add" method="post"><input type="text" name="varkey"><input type="submit" value="Submit"></form>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="20%">Title</td><td width="30%">Raw</td><td width="30%">HTML</td><td width="10%">Admin</td><td width="10%">Actions</td></tr><?
        for($i=0;$i<count($c->msBCode);$i++){
            ?><tr bgcolor="#0099FF"><td><?=$c->msBTitle[$i]?></td><td><?=$k->convertHTML($c->msBRaw[$i]);?></td><td><?=$k->convertHTML($c->msBHTML[$i])?></td><td><?=$c->msBAdmin[$i]?></td><td>
                        <form action="/admin/ACP/bbcode/edit" method="post"><input type="hidden" name="varkey" value="<?=$c->msBTitle[$i]?>"><input type="submit" value="Edit"></form>
                        <form action="/admin/ACP/bbcode/del" method="post"><input type="hidden" name="varkey" value="<?=$c->msBTitle[$i]?>"><input type="submit" value="Delete"></form></td></tr><?
        }
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'add':
                if($_POST['varhtml']==""){
                    ?><center><form action="/admin/ACP/bbcode/add" method="post">Add bbcode <?=str_replace("\\\\","\\",$_POST['varkey'])?>.<br />
                    Raw: <input type="text" name="varraw" style="width:300px;"><br />
                                        Code: <input type="text" name="varbb" style="width:300px;"><br />
                                        HTML: <input type="text" name="varhtml" style="width:300px;"><br />
                                        Access: <input type="text" name="varadmin"><br />
                    <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                    <input type="submit" value="Submit">
                    </form></center><?
                }else{
                    $c->query("INSERT INTO ms_bbcode VALUES(?,?,?,?,?)",array(
                        $_POST['varkey'],$_POST['varraw'],str_replace("\\\\","\\",$_POST['varbb']),str_replace("\\\\","\\",$_POST['varhtml']),$_POST['varadmin']),false);
                    echo("<center><span style='color:red'>BBCode added.</span><br /><a href='/admin/ACP/bbcode'>Return</a></center>");
                                        $k->log("BBCode ".$_POST['varkey']." added.");
                }
                break;

            case 'del':
                $c->query("DELETE FROM ms_bbcode WHERE title=?",array(str_replace("\\\\","\\",$_POST['varkey'])));
                echo("<center><span style='color:red'>BBCode deleted.</span><br /><a href='/admin/ACP/bbcode'>Return</a></center>");
                                $k->log("BBCode ".$_POST['varkey']." deleted.");
                break;

            case 'edit':
                if($_POST['varhtml']==""){
                    $key = str_replace("\\\\","\\",$_POST['varkey']);
                    $temp = array_search($key,$c->msBTitle);
                    ?><center><form action="/admin/ACP/bbcode/edit" method="post">
                                        Raw: <input type="text" name="varraw" value="<?=$c->msBRaw[$temp]?>" style="width:300px;"><br />
                    BBCode: <input type="text" name="varbbcode" value="<?=$c->msBCode[$temp]?>" style="width:300px;"><br />
                    HTML: <input type="text" name="varhtml" value="<?=$p->enparse($c->msBHTML[$temp])?>" style="width:300px;"><br />
                    Access: <input type="text" name="varadmin" value="<?=$c->msBAdmin[$temp]?>">
                    <input type="hidden" name="varkey" value="<?=$key?>">
                    <input type="submit" >
                    </form></center><?
                }else{
                    $c->query("UPDATE ms_bbcode SET raw=?, bbcode=?, html=?, admin=? WHERE title=?",array(
                        $_POST['varraw'],str_replace("\\\\","\\",$_POST['varbbcode']),str_replace("\\\\","\\",$_POST['varhtml']),$_POST['varadmin'],str_replace("\\\\","\\",$_POST['varkey'])),false);
                    echo("<center><span style='color:red'>BBCode edited.</span><br /><a href='/admin/ACP/bbcode'>Return</a></center>");
                                $k->log("BBCode ".$_POST['varkey']." edited.");
                }
                break;
            }
        }

        break;
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'ban':
        if(!$a->check('admin.ban'))return;
        if($params[1]==""){
        ?>Ban the following Mask:<br />
        <form action="/admin/ACP/ban/add" method="post"><input type="text" name="varkey"><input type="submit" value="Submit"></form>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="10%">Mask</td><td width="10%">Time</td><td width="35%">Reason</td><td width="35%">Appeal</td><td width="10%">Actions</td></tr><?
        for($i=0;$i<count($c->msBIP);$i++){
            ?><tr bgcolor="#0099FF"><td><?=$c->msBIP[$i]?></td><td><?=date("D M j G:i:s T Y",$c->msBTime[$i])?></td><td><?=$k->convertHTML($c->msBReason[$i])?></td><td><?=$k->convertHTML($c->msBAppeal[$i])?></td><td>
                        <form action="/admin/ACP/ban/del" method="post"><input type="hidden" name="varkey" value="<?=$c->msBIP[$i]?>"><input type="submit" value="Delete"></form></td></tr><?
        }
        ?></table><?
        }else if($_POST['varkey']!=""){
            switch($params[1]){
            case 'add':
                if($_POST['vartime']==""){
                    ?><center><form action="#" method="post">
                    Ban IP Mask '<?=$_POST['varkey']?>'<br />
                    Time: <select name="vartime">
                    <option value="custom">Custom</option>
                    <option value="1">1 Second</option>
                    <option value="60">1 Minute</option>
                    <option value="3600">1 Hour</option>
                    <option value="86400">1 Day</option>
                    <option value="604800">1 Week</option>
                    <option value="2592000">1 Month</option>
                    <option value="31536000">1 Year</option>
                    <option value="-1">Permaban</option></select><input type="text" name="vartime2" value="60">m<br />
                    Reason:<br />
                    <textarea name="varreason" style="width:300px;height:150px;"></textarea><br />
                    Appeal: <select name="varappeal"><option value="true">Allow</option><option value="false">Deny</option></select>
                    <input type="hidden" name="varkey" value="<?=$_POST['varkey']?>">
                    <input type="submit" value="Ban">
                    </form></center><?
                }else{
                    if($_POST['vartime']=="custom")$_POST['vartime']=60*$_POST['vartime2'];
                    $this->apiCall("ban",array("mask"=>$_POST['varkey'],"time"=>$_POST['vartime'],"reason"=>$_POST['varreason'],"appeal"=>$_POST['varappeal']),$c->o['API_BAN_TOKEN']);
                    echo("<center><span style='color:red'>Mask banned.</span><br /><a href='/admin/ACP/ban'>Return</a></center>");
                }
                break;
            case 'del':
                $this->apiCall("unban",array("mask"=>$_POST['varkey']),$c->o['API_UNBAN_TOKEN']);
                echo("<center><span style='color:red'>Mask unbanned.</span><br /><a href='/admin/ACP/ban'>Return</a></center>");
                break;
            }
        }
        break;
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    case 'tickets':
        if($params[1]=="clear"){$c->query("TRUNCATE TABLE ms_tickets",array());}
        $c->loadTickets();
        ?><a href="<?=PROOT?>admin/ACP/tickets/clear">Clear All Tickets</a>
        <table width="100%" cellpadding="5px"><tr bgcolor="#666666"><td width="10%">ID</td><td width="35%">Ticket</td><td width="35%">Text</td><td width="10%">Time</td><td width="10%">Reporter IP</td></tr><?
        for($i=0;$i<count($c->msRID);$i++){
            echo("<tr bgcolor='#0099FF'><td>".$c->msRID[$i]."</td><td>".$c->msRTicket[$i]."</td><td>".$p->deparseAll($c->msRText[$i])."</td><td>".$k->toDate($c->msRTime[$i])."</td><td>".$c->msRIP[$i]."</td></tr>");
        }
        ?></table><?
        break;

    default:
        if($a->check('admin.settings')) $SectionList["settings"]    = "Settings|Change core variables directly";
        if($a->check('admin.modules'))  $SectionList["modules"]     = "Modules|Manage the site's modules";
                                        $SectionList["2"]           = "<--->";
        if($a->check('admin.log'))      $SectionList["log"]         = "Log|View the mod log";
        if($a->check('admin.tickets'))  $SectionList["tickets"]     = "Tickets|View report tickets";
        if($a->check('admin.ban'))      $SectionList["ban"]         = "Ban|Ban IP Masks";
                                        $SectionList["3"]           = "<--->";
        if($a->check('admin.bbcode'))   $SectionList["bbcode"]      = "BBCode|Change the BBCode system";
                                        $SectionList["4"]           = "<--->";
                                        //$SectionList[""]         = "";
        return $SectionList;
        break;
    }
}

function apiCall($func,$args,$security=""){
    global $c,$p,$k;
    if($security!=$c->o['API_'.strtoupper($func).'_TOKEN'])return;

    switch($func){
    case 'deluser':

        break;
    case 'addKey':
        if(substr($args['key'],0,4)=="API_")$k->addAPIToken($args['key']);
        else $c->query("INSERT INTO ms_options VALUES(?,?)",array($args['key'],$args['value']),false);
                $c->o[$args['key']]=$args['value'];
                $k->log("Key ".$args['key']." => ".$args['value']." added.");
        break;
    case 'delKey':
        $c->query("DELETE FROM ms_options WHERE `key`=?",array($args['key']));
                $k->log("Key ".$args['key']." deleted.");
        break;
    case 'ban':
        if($args['IP']!="")$args['mask']=$args['IP'];
        if($args['appeal']=="")$args['appeal']="true";
        if($args['reason']=="")$args['reason']="No reason given.";
        if(substr($args['mask'],0,1)!=="`")$args['mask']="`".$args['mask']."`is";
        $c->query("INSERT INTO ms_banned VALUES(?,?,?,?)",array($args['mask'],time()+$args['time'],$p->enparse($args['reason']),$args['appeal']),false);
        $k->log("IP ".$args['mask']." banned for ".$args['time']."s (".$args['reason'].")");
        break;
    case 'unban':
        if($args['IP']!="")$args['mask']=$args['IP'];
        $c->query("DELETE FROM ms_banned WHERE IP=?",array($args['mask']));
                $k->log("IP ".$args['mask']." unbanned.");
        break;
    }
}

function search($sections,$keywords="",$type="OR"){return array();}
}
?>