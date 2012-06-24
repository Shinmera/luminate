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
        case 'move':    $this->displayMove();       break;
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

function displayMove(){
    global $a;
    if(!$a->check('chan.mod.move'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT BID,PID WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)                die('No such post found.');
    if($post->PID!=0)              die('This isn\'t a thread.'); 
    
    if($_POST['action']=='Change'){
        include('datagen.php');
        $datagen = new DataGenerator();
        if($post->BID!=$_POST['board']){
            $datagen->moveThread($_GET['id'], $_GET['bid'], $_POST['board']);
            $boardname = $c->getData('SELECT folder FROM ch_boards WHERE boardID=?',array($_POST['board']));
            $ret = 'Thread moved to '.$boardname[0]['folder'];
        }
        if($_POST['parent']!=$post->PID){
            $datagen->mergeThread($_GET['id'], $_GET['bid'], $_POST['parent']);
            $ret = 'Thread merged with '.$_POST['parent'];
        }
        die($ret);
    }else{
        ?><form action="<?=PROOT?>api/chan/purge?id=<?=$_GET['id']?>&bid=<?$_GET['bid']?>" method="post" id="form">
            Change this thread's options:<br />
            <? $boards= DataModel::getData('ch_boards','SELECT boardID,folder FROM ch_boards'); ?>
            Board: <?=Toolkit::printSelectObj("board",$boards,'folder','boardID',$_GET['bid']);?><br />
            Parent: <input type="number" name="parent" value="<?=$post->PID?>" /><br />
            <input type="submit" name='action' value="Change" />
            <script type="text/javascript">
                $(document).ready(function() { $("#form").ajaxForm( {success: showResponse}); });
                function showResponse(responseText, statusText, xhr, $form){ $("#form").parent().html("Response: "+responseText); }
            </script>
        </form><?
    }
}

function displayPurge(){
    global $a;
    if(!$a->check('chan.mod.delete'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT ip WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)                  die('No such post found.');
    
    if($_POST['action']=='Purge'){
        include('datagen.php');
        $datagen = new DataGenerator();
        $datagen->deleteByIP($post->ip);
        die('All posts by '.$post->ip.' have been deleted.');
    }else{
        ?><form action="<?=PROOT?>api/chan/purge?id=<?=$_GET['id']?>&bid=<?$_GET['bid']?>" method="post" id="form">
            Are you sure?<br />
            <input type="submit" name='action' value="Purge" />
            <script type="text/javascript">
                $(document).ready(function() { $("#form").ajaxForm( {success: showResponse}); });
                function showResponse(responseText, statusText, xhr, $form){ $("#form").parent().html("Response: "+responseText); }
            </script>
        </form><?
    }
}

function displaySearch(){
    global $a;
    if(!$a->check('chan.mod.search'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT ip WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)                  die('No such post found.');
    $posts = DataModel::getData('ch_posts','SELECT postID,ch_boards.folder 
                                            FROM ch_posts LEFT JOIN ch_boards ON BID=boardID
                                            WHERE ip=? ORDER BY time DESC LIMIT 10',array($post->ip));
    
    echo('<div style="overflow:scroll;">');
    foreach($posts as $post){
        include(ROOT.DATAPATH.'chan/'.$post->folder.'/posts/'.$post->postID.'.php');
    }
    echo('</div>');
}

function displayDelete(){
    global $a;
    if(!$a->check('chan.mod.delete'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT postID WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)                  die('No such post found.');
    
    if($_POST['action']=='Delete'){
        include('datagen.php');
        $datagen = new DataGenerator();
        $datagen->deletePost($_GET['id'], $_GET['bid'], true);
        die('Post deleted.');
    }else{
        ?><form action="<?=PROOT?>api/chan/delete?id=<?=$_GET['id']?>&bid=<?$_GET['bid']?>" method="post" id="form">
            Are you sure?<br />
            <input type="submit" name='action' value="Delete" />
            <script type="text/javascript">
                $(document).ready(function() { $("#form").ajaxForm( {success: showResponse}); });
                function showResponse(responseText, statusText, xhr, $form){ $("#form").parent().html("Response: "+responseText); }
            </script>
        </form><?
    }
}

function displayEdit(){
    global $a;
    if(!$a->check('chan.mod.edit'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT p.*,ch_boards.folder 
                                            FROM ch_posts AS p LEFT JOIN ch_boards ON BID=boardID
                                            WHERE p.postID=? AND p.BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)                die('No such post found.');
    //Post edit
    //Thread move,merge
    if($_POST['action']=='edit'){
        $post->name=$_POST['name'];
        $post->trip=$_POST['trip'];
        $post->mail=$_POST['mail'];
        $post->title=$_POST['title'];
        $post->subject=$_POST['text'];
        $_POST['options'][]='p';
        $post->options=implode(',',$_POST['options']);
        $post->saveData();
        include('postgen.php');
        PostGenerator::generatePostFromObject($post);
        die('Post edited!');
    }else{
        echo("<link rel='stylesheet' type='text/css' href='".DATAPATH."css/forms.css' id='dynstyle' />");
        include(MODULEPATH.'gui/Editor.php');
        $editor = new SimpleEditor(PROOT.'api/chan/edit?id='.$_GET['id'].'&bid='.$_GET['bid'], 'edit', 'form');
        if($post->file!='')
            $editor->addCustom('<img src="'.DATAPATH.'chan/'.$post->folder.'/thumbs/'.$post->file.'" style="float:left;" alt="Picture" />');
        
        $editor->addTextField('name', 'Name', $post->name, 'text', 'Anonymous');
        $editor->addTextField('trip', 'Trip', $post->trip, 'text', '!!Anonymous');
        $editor->addTextField('mail', 'Mail', $post->mail, 'text', 'sage');
        $editor->addTextField('title', 'title', $post->title, 'text', '...');
        $editor->addCheckbox('options[]', 'Hidden', 'h', strpos($post->options,'h')!==FALSE);
        $editor->addCheckbox('options[]', 'Modpost', 'm',strpos($post->options,'m')!==FALSE);
        $editor->addCustom('<script type="text/javascript">
                                $(document).ready(function() { $("#form").ajaxForm( {success: showResponse}); });
                                function showResponse(responseText, statusText, xhr, $form){ $("#form").parent().html("Response: "+responseText); }
                            </script>');
        $_POST['text']=$post->subject;
        $editor->show();
    }
}

function displayBan(){
    global $a;
    if(!$a->check('chan.mod.ban'))die('Insufficient privileges.');
    if($_POST['IP']!=''){
        $ban = DataModel::getHull('ch_bans');
        $ban->ip=$_POST['IP'];
        $ban->time=$_POST['time'];
        $ban->reason=$_POST['reason'];
        $ban->PID=$_GET['id'];
        $ban->folder=$_GET['folder'];
        if($_POST['appeal']=='a')$ban->appeal='You cannot appeal to this ban.';
        if($_POST['mute']=='m')$ban->mute=1;else $ban->mute=0;
        $ban->insertData();
        if($_POST['time']>0)die('Successfully banned '.$_POST['IP'].' until '.Toolkit::toDate($_POST['time']).'.');
        else                die('Successfully permabanned '.$_POST['IP'].' until the end of this database entry\'s life cycle.');
    }else{
        $post = DataModel::getData('ch_posts','SELECT p.postID,p.ip,p.subject,p.file,ch_boards.folder 
                                               FROM ch_posts AS p LEFT JOIN ch_boards ON BID=boardID
                                               WHERE p.postID=? AND p.BID=?',array($_GET['id'],$_GET['bid']));
        if($post==null)die('No such post found.');
        
        echo("<link rel='stylesheet' type='text/css' href='".DATAPATH."css/forms.css' id='dynstyle' />");
        include(MODULEPATH.'gui/Editor.php');
        $editor = new SimpleEditor(PROOT.'api/chan/ban?id='.$_GET['id'].'&folder='.$post->folder, 'Ban', 'form');
        if($post->file!='')
            $editor->addCustom('<img src="'.DATAPATH.'chan/'.$post->folder.'/thumbs/'.$post->file.'" style="float:left;" alt="Picture" />');
        $editor->addTextField('IP', 'IP: ', $post->ip,'text','required placeholder="'.$post->ip.'"');
        $editor->addTextField('reason','Reason: ','','text','required placeholder="Spam" maxlength="128"');
        $editor->addDropDown('time',array(time()+1,  time()+60, time()+1800,  time()+3600,time()+6400,time()+604800,time()+241920,time()+31536000,-1),
                                    array('1 second','1 minute','30 minutes','1 hour',    '1 day',    '1 week',     '1 month',    '1 year',       'Forever'), 'Ban Time:');
        $editor->addCheckbox('mute', 'Mute Ban','m');
        $editor->addCheckbox('appeal', 'Appeal Allowed','a',true);
        $editor->addCustom('<script type="text/javascript">
                                $(document).ready(function() { $("#form").ajaxForm( {success: showResponse}); });
                                function showResponse(responseText, statusText, xhr, $form){ $("#form").parent().html("Response: "+responseText); }
                            </script>');
        $_POST['text']=$post->subject;
        $editor->show();
    }
}

function displayPost(){
    include('datagen.php');
    try{
        $datagen = new DataGenerator();
        $datagen->submitPost();
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