<? class ChanAPI extends Module{
public static $name="ChanAPI";
public static $author="NexT";
public static $version=0.21;
public static $short='capi';
public static $required=array("Auth");
public static $hooks=array("foo");

function display(){
    global $params,$chan;
    switch($params[1]){
        case 'purge':   $this->displayPurge();      break;
        case 'search':  $this->displaySearch();     break;
        case 'delete':  $this->displayDelete();     break;
        case 'edit':    $this->displayEdit();       break;
        case 'ban':     $this->displayBan();        break;
        case 'post':    $this->displayPost();       break;
        case 'options': $this->displayOptions();    break;
        case 'watch':   $this->displayThreadWatch();break;
        default:        echo('Purplish v'.$chan::$version.' / API'.$this::$version);break;
    }
}

function displayPurge(){
    global $a;
    if(!$a->check('chan.mod.purge'))die('Insufficient privileges.');
    if($_POST['action']){
        
    }
}

function displaySearch(){
    global $a;
    if(!$a->check('chan.mod.search'))die('Insufficient privileges.');
    
}

function displayDelete(){
    global $a;
    if(!$a->check('chan.mod.delete'))die('Insufficient privileges.');
    
}

function displayEdit(){
    global $a;
    if(!$a->check('chan.mod.edit'))die('Insufficient privileges.');
    //Post edit
    //Thread move,merge
}

function displayBan(){
    global $a;
    if(!$a->check('chan.mod.ban'))die('Insufficient privileges.');
    
}

function displayPost(){
    include('datagen.php');
    try{
        DataGenerator::submitPost();
    }catch(Exception $e){
        global $a;
        if($a->check('chan.admin'))Toolkit::err($e->getMessage()."\n\n".$e->getTraceAsString());
        else Toolkit::err($e->getMessage());
    }
}

function displayOptions(){
    ?><form>
        <input type="checkbox" value="u" id="cbu" /><label style="width:200px;display:inline-block;vertical-align:middle">Auto update threads</label><br />
        <input type="checkbox" value="f" id="cbf" /><label style="width:200px;display:inline-block;vertical-align:middle">Fixed post box</label><br />
        <input type="checkbox" value="p" id="cbp" /><label style="width:200px;display:inline-block;vertical-align:middle">Show image previews</label><br />
        <input type="checkbox" value="e" id="cbe" /><label style="width:200px;display:inline-block;vertical-align:middle">Enlarge image on click</label><br />
        <input type="checkbox" value="h" id="cbh" /><label style="width:200px;display:inline-block;vertical-align:middle">Enable thread hiding</label><br />
        <input type="checkbox" value="s" id="cbs" /><label style="width:200px;display:inline-block;vertical-align:middle">Scroll to post when selecting</label><br />
        <input type="checkbox" value="q" id="cbq" /><label style="width:200px;display:inline-block;vertical-align:middle">Show post quote previews</label><br />
        <input type="checkbox" value="w" id="cbw" /><label style="width:200px;display:inline-block;vertical-align:middle">Always show watched threads</label><br />
        <input type="submit" id="saveOptions" value="Save" /> 
        <span id="saveResult" style="color:red;font-weight:bold;"></span>
    </form><script type="text/javascript">
        var ops = ['u','p','e','h','s','q','w','f'];
        for(var i=0;i<ops.length;i++){
            if(options.indexOf(ops[i])!=-1)$("#cb"+ops[i]).prop("checked", true);
        }
        $("#saveOptions").click(function(){
            options="";
            for(var i=0;i<ops.length;i++){
                if($("#cb"+ops[i]).is(":checked"))options+=ops[i];
            }
            $.cookie("chan_options",options,{ expires: 356, path: '/' });
            $("#saveResult").html("Saved! Reloading page...");
            window.setTimeout('location.reload()', 1000);
        });
    </script><?
}

function displayThreadWatch(){
    $watched = array_filter(explode(";",$_COOKIE['chan_watched']));
    sort($watched);
    if(count($watched)==0)return "";

    for($i=0,$temp=count($watched);$i<$temp;$i++){
        $temp2=explode(" ",$watched[$i]);
        if(is_numeric($temp2[0])&&is_numeric($temp2[1])){
            $boardIDs.= " OR boardID=".$temp2[0];
            $querypart.=" OR (postID=".$temp2[1]." AND BID=".$temp2[0]." AND PID=0)";
        }
    }
    if(trim($querypart)=="")return "";
    $data = $c->getData("SELECT postID,BID,title,name,trip FROM ch_posts WHERE ".substr($querypart,4)." LIMIT 20");
    $boards=$c->getData("SELECT boardID,folder FROM ch_boards WHERE ".substr($boardIDs,4));
    if(count($data)==0)return '';

    $ret="";
    for($i=0,$temp=count($data);$i<$temp;$i++){

        $time=0;
        for($j=0,$temp2=count($watched);$j<$temp2;$j++){
            $temp3=explode(" ",$watched[$j]);
            if($temp3[0]==$data[$i]['BID']&&$temp3[1]==$data[$i]['postID']){
                $time=$temp3[2];break;
        }}
        $postcount = $c->getData("SELECT COUNT(postID) FROM ch_posts WHERE PID=? AND BID=? AND time>?",array($data[$i]['postID'],$data[$i]['BID'],$time));
        $postcount=$postcount[0]['COUNT(postID)'];

        $folder="";
        for($j=0,$temp2=count($boards);$j<$temp2;$j++){
            if($boards[$j]['boardID']==$data[$i]['BID']){
                $folder=$boards[$j]['folder'];break;
        }}

        $ret.='<tr><td><a href="#" class="watchDeleteButton" title="Remove" id="'.$data[$i]['postID'].'" board="'.$data[$i]['BID'].'" >✘</a></td>'.
                '<td><a href="'.$k->url('chan',$folder).'">'.$folder.'</a></td>'.
                '<td><a href="'.$k->url('chan',$folder.'/threads/'.$data[$i]['postID']).'">'.$data[$i]['postID'].'</a></td>'.
                '<td>'.$data[$i]['name'].$data[$i]['trip'].'</td>'.
                '<td>'.$data[$i]['title'].'</td>';
        if($postcount>0)$ret.='<td class="watchNewPosts"><a href="'.$k->url('chan',$folder.'/threads/'.$data[$i]['postID']).'">'.$postcount.'</a></td></tr>';
        else            $ret.='<td>0</td></tr>';
    }
    return $ret;
}
}
?>