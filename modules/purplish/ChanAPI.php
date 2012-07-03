<? class ChanAPI extends Module{
public static $name="ChanAPI";
public static $author="NexT";
public static $version=0.21;
public static $short='capi';
public static $required=array("Auth");
public static $hooks=array("foo");

function display(){
    global $params,$chan;
    if($_POST['id']!='')$_GET['id']=$_POST['id'];
    if($_POST['bid']!='')$_GET['bid']=$_POST['bid'];
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
        case 'report':  $this->displayReport();     break;
        case 'rss':     $this->displayRSS();        break;
        default:        echo('Purplish v'.$chan::$version.' / API'.$this::$version);break;
    }
}

function displayRSS(){
    global $l,$params;
    $params=array_slice($params,1);
    $rss = $l->loadModule('ChanRSS');
    $rss->display();
}

function displayMove(){
    global $a,$c;
    if(!$a->check('chan.mod.move'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT BID,PID FROM ch_posts WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
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
        ?><form action="<?=PROOT?>api/chan/move?id=<?=$_GET['id']?>&bid=<?$_GET['bid']?>" method="post" id="form">
            Change this thread's options:<br />
            <? $boards= DataModel::getData('ch_boards','SELECT boardID,folder FROM ch_boards'); ?>
            Board: <?=Toolkit::printSelectObj("board",$boards,'folder','boardID',$_GET['bid']);?><br />
            Parent: <input type="number" name="parent" value="<?=$post->PID?>" /><br />
            <input type="submit" name='action' value="Change" />
            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
            <input type="hidden" name="bid" value="<?=$_GET['bid']?>" />
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
    $post = DataModel::getData('ch_posts','SELECT ip FROM ch_posts WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)                  die('No such post found.');
    
    if($_POST['action']=='Purge'){
        include('datagen.php');
        $datagen = new DataGenerator();
        $datagen->deleteByIP($post->ip);
        die('All posts by '.$post->ip.' have been deleted.');
    }else{
        ?><form action="<?=PROOT?>api/chan/purge?id=<?=$_GET['id']?>&bid=<?$_GET['bid']?>" method="post" id="apiForm">
            Are you sure?<br />
            <input type="submit" name='action' value="Purge" />
            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
            <input type="hidden" name="bid" value="<?=$_GET['bid']?>" />
            <script type="text/javascript" src="<?=DATAPATH?>js/chan_api_postsend.js"></script>
        </form><?
    }
}

function displaySearch(){
    global $a;
    if(!$a->check('chan.mod.search'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT ip FROM ch_posts WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)                  die('No such post found.');
    $posts = DataModel::getData('ch_posts','SELECT postID,ch_boards.folder 
                                            FROM ch_posts LEFT JOIN ch_boards ON BID=boardID
                                            WHERE ip=? ORDER BY time DESC LIMIT 10',array($post->ip));
    
    echo('<div style="overflow:auto;max-height:600px">');
    foreach($posts as $post){
        @include(ROOT.DATAPATH.'chan/'.$post->folder.'/posts/'.$post->postID.'.php');
        @include(ROOT.DATAPATH.'chan/'.$post->folder.'/posts/_'.$post->postID.'.php');
    }
    echo('</div>');
}

function displayDelete(){
    global $a;
    if(!$a->check('chan.mod.delete'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT postID FROM ch_posts WHERE postID=? AND BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)                  die('No such post found.');
    
    if($_POST['action']=='Delete'){
        include('datagen.php');
        $datagen = new DataGenerator();
        $datagen->deletePost($_GET['id'], $_GET['bid'], true);
        die('Post deleted.');
    }else{
        ?><form action="<?=PROOT?>api/chan/delete?id=<?=$_GET['id']?>&bid=<?$_GET['bid']?>" method="post" id="apiForm">
            Are you sure?<br />
            <input type="submit" name='action' value="Delete" />
            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
            <input type="hidden" name="bid" value="<?=$_GET['bid']?>" />
            <script type="text/javascript" src="<?=DATAPATH?>js/chan_api_postsend.js"></script>
        </form><?
    }
}

function displayEdit(){
    global $a,$c,$l;
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
        $post->subject = $c->enparse($post->subject,true);
        $post->title = $c->enparse($post->title,true);
        $post->name = $c->enparse($post->name,true);
        $post->trip = $c->enparse($post->trip,true);
        $post->mail = $c->enparse($post->mail,true);
        $post->fileOrig = $c->enparse($post->fileOrig,true);
        PostGenerator::generatePostFromObject($post);
        if($post->PID==0){
            include('threadgen.php');
            include('boardgen.php');
            ThreadGenerator::generateThreadFromObject($post);
            BoardGenerator::generateBoard($post->BID);
        }
        $l->triggerHook('editPost','Purplish',$post);
        die('Post edited!');
    }else{
        echo("<link rel='stylesheet' type='text/css' href='".DATAPATH."css/forms.css' id='dynstyle' />");
        include(MODULEPATH.'gui/Editor.php');
        $editor = new SimpleEditor(PROOT.'api/chan/edit?id='.$_GET['id'].'&bid='.$_GET['bid'], 'edit', 'apiForm');
        if($post->file!='')
            $editor->addCustom('<img src="'.DATAPATH.'chan/'.$post->folder.'/thumbs/'.$post->file.'" style="float:left;" alt="Picture" />');
        
        $editor->addTextField('name', 'Name', $post->name, 'text', 'placeholder="Anonymous"');
        $editor->addTextField('trip', 'Trip', $post->trip, 'text', 'placeholder="!!Anonymous"');
        $editor->addTextField('mail', 'Mail', $post->mail, 'text', 'placeholder="sage"');
        $editor->addTextField('title', 'Title', $post->title, 'text', 'placeholder="..."');
        $editor->addCheckbox('options[]', 'Hidden', 'h', strpos($post->options,'h')!==FALSE);
        $editor->addCheckbox('options[]', 'Modpost', 'm',strpos($post->options,'m')!==FALSE);
        if($post->PID==0){
            $editor->addCheckbox('options[]', 'Locked', 'l', strpos($post->options,'l')!==FALSE);
            $editor->addCheckbox('options[]', 'Sticky', 's',strpos($post->options,'s')!==FALSE);
            $editor->addCheckbox('options[]', 'Autosage', 'a',strpos($post->options,'a')!==FALSE);
        }
        $editor->addCustom('
            <input type="hidden" name="id" value="'.$_GET['id'].'" />
            <input type="hidden" name="bid" value="'.$_GET['bid'].'" />');
        $editor->addCustom('<script type="text/javascript" src="'.DATAPATH.'js/chan_api_postsend.js"></script>');
        $_POST['text']=$post->subject;
        $editor->show();
    }
}

function displayBan(){
    global $a,$l;
    if(!$a->check('chan.mod.ban'))die('Insufficient privileges.');
    $post = DataModel::getData('ch_posts','SELECT p.*,ch_boards.folder 
                                            FROM ch_posts AS p LEFT JOIN ch_boards ON BID=boardID
                                            WHERE p.postID=? AND p.BID=?',array($_GET['id'],$_GET['bid']));
    if($post==null)die('No such post found.');
    
    if($_POST['IP']!=''){
        $ban = DataModel::getHull('ch_bans');
        $ban->ip=$_POST['IP'];
        $ban->time=time();
        $ban->period=$_POST['time'];
        $ban->reason=$_POST['reason'];
        $ban->PID=$_GET['id'];
        $ban->folder=$_GET['folder'];
        if($_POST['appeal']=='a')$ban->appeal='You cannot appeal to this ban.';
        if($_POST['mute']=='m')$ban->mute=1;else $ban->mute=0;
        $ban->insertData();
        $l->triggerHook('ban','Purplish',$ban);
        
        $post->subject = $_POST['text'];
        $post->saveData();
        include('postgen.php');
        PostGenerator::generatePostFromObject($post);
        
        if($_POST['time']>0)die('Successfully banned '.$_POST['IP'].' until '.Toolkit::toDate(time()+$_POST['time']).'.');
        else                die('Successfully permabanned '.$_POST['IP'].' until the end of this database entry\'s life cycle.');
    }else{
        
        echo("<link rel='stylesheet' type='text/css' href='".DATAPATH."css/forms.css' id='dynstyle' />");
        include(MODULEPATH.'gui/Editor.php');
        $editor = new SimpleEditor(PROOT.'api/chan/ban?id='.$_GET['id'].'&folder='.$post->folder, 'Ban', 'apiForm');
        if($post->file!='')
            $editor->addCustom('<img src="'.DATAPATH.'chan/'.$post->folder.'/thumbs/'.$post->file.'" style="float:left;" alt="Picture" />');
        $editor->addTextField('IP', 'IP: ', $post->ip,'text','required placeholder="'.$post->ip.'"');
        $editor->addTextField('reason','Reason: ','','text','required placeholder="Spam" maxlength="128"');
        $editor->addDropDown('time',array(1,  60, 1800,  3600,6400,604800,241920,31536000,-1),
                                    array('1 second','1 minute','30 minutes','1 hour',    '1 day',    '1 week',     '1 month',    '1 year',       'Forever'), 'Ban Time:');
        $editor->addCheckbox('mute', 'Mute Ban','m');
        $editor->addCheckbox('appeal', 'Appeal Allowed','a',true);
        $editor->addCustom('
            <input type="hidden" name="id" value="'.$_GET['id'].'" />
            <input type="hidden" name="bid" value="'.$_GET['bid'].'" />');
        $editor->addCustom('<script type="text/javascript" src="'.DATAPATH.'js/chan_api_postsend.js"></script>');
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

function displayReport(){
    global $a,$l;
    if(!is_array($_POST['varposts'])||$_POST['varposts']=='')die('No posts selected.');
    if(!Toolkit::updateTimeout('chan_report', 5))            die('Please wait 5 seconds between reports or deletions.');
    if($_POST['submitter']=='Report'){
        if(trim($_POST['reason'])=='')                       die('Please specify a report reason.');
        $ret='';
        $report = DataModel::getHull('ch_reports');
        $report->ip = $_SERVER['REMOTE_ADDR'];
        $report->time = time();
        $report->reason = $_POST['reason'];
        $report->folder = $_POST['folder'];
        foreach($_POST['varposts'] AS $post){
            $report->PID = $post;
            $report->insertData();
            $l->triggerHook('report','Purplish',$report);
            $ret.='Report for post #'.$post.' on '.$_POST['folder'].' has been submitted.<br />';
        }
        $ret = 'Redirecting... <script type="text/javascript">window.setTimeout("window.location=\''.$_SERVER['HTTP_REFERER'].'\'", 1000);</script>';
        die($ret);
    }else
    if($_POST['submitter']=='Delete'){
        $ret='';
        include('datagen.php');
        $datagen = new DataGenerator();
        foreach($_POST['varposts'] AS $post){
            try{
                if($_POST['fileonly']==="1")
                    $datagen->deletePost($post, $_POST['board'], false, true);
                else
                    $datagen->deletePost($post, $_POST['board'], true, false);
                $ret.='Post deleted.<br />';
            }catch(Exception $e){
                if($a->check('chan.admin'))$ret.=$e->getMessage().'<br />'.$e->getTraceAsString().'<br /><br />';
                else $ret.=$e->getMessage().'<br />';
            }
        }
        $ret = 'Redirecting... <script type="text/javascript">window.setTimeout("window.location=\''.$_SERVER['HTTP_REFERER'].'\'", 1000);</script>';
        die($ret);
    }
    die('Nothing to do.');
}

function displayOptions(){
    ?>
    <h4>Options</h4>
    <form id="optionsForm">
        <input type="checkbox" value="u" id="cbu" /><label>Auto update threads</label><br />
        <input type="checkbox" value="f" id="cbf" /><label>Fixed post box</label><br />
        <input type="checkbox" value="p" id="cbp" /><label>Show image previews</label><br />
        <input type="checkbox" value="e" id="cbe" /><label>Enlarge image on click</label><br />
        <input type="checkbox" value="h" id="cbh" /><label>Enable thread hiding</label><br />
        <input type="checkbox" value="s" id="cbs" /><label>Scroll to post when selecting</label><br />
        <input type="checkbox" value="q" id="cbq" /><label>Show post quote previews</label><br />
        <input type="checkbox" value="a" id="cba" /><label>Automatically watch threads you post in</label><br />
        <input type="checkbox" value="w" id="cbw" /><label>Always show watched threads</label><br />
        <input type="checkbox" value="v" id="cbv" /><label>Hide embedded videos</label><br />
        <input type="submit" id="saveOptions" value="Save" /> 
        <span id="saveResult" style="color:red;font-weight:bold;"></span>
    </form><script type="text/javascript">
        var ops = ['u','p','e','h','s','q','w','f','v'];
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
    global $c,$k;
    $watched = array_filter(explode(";",$_COOKIE['chan_watched']));
    sort($watched);
    if(count($watched)==0)die('No threads watched.');

    for($i=0,$temp=count($watched);$i<$temp;$i++){
        $temp2=explode(" ",$watched[$i]);
        if(is_numeric($temp2[0])&&is_numeric($temp2[1])){
            $boardIDs.= " OR boardID=".$temp2[0];
            $querypart.=" OR (postID=".$temp2[1]." AND BID=".$temp2[0]." AND PID=0)";
        }
    }
    if(trim($querypart)=="")die('No threads watched.');
    $data = $c->getData("SELECT postID,BID,title,name,trip FROM ch_posts WHERE ".substr($querypart,4)." LIMIT 20");
    $boards=$c->getData("SELECT boardID,folder FROM ch_boards WHERE ".substr($boardIDs,4));
    if(count($data)==0)die('Watched threads not found.');

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
        if($postcount>0)$ret.='<td class="watchNewPosts"><a href="'.PROOT.$folder.'/threads/'.$data[$i]['postID'].'.php">'.$postcount.'</a></td></tr>';
        else            $ret.='<td>0</td></tr>';
    }
    die($ret);
}
}
?>