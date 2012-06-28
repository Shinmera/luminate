<? class ChanAdmin extends Module{
public static $name="ChanAdmin";
public static $author="NexT";
public static $version=0.01;
public static $short='cadmin';
public static $required=array("Auth");
public static $hooks=array("foo");

function display(){
    global $params,$a;
    switch($params[1]){
        case 'categories':  if($a->check("chan.admin.categories"))  $this->displayCategories(); break;
        case 'boards':      if($a->check("chan.admin.boards"))      $this->displayBoards();     break;
        case 'edit':        if($a->check("chan.admin.boards"))      $this->displayEditBoard();  break;
        case 'filetypes':   if($a->check("chan.admin.filetypes"))   $this->displayFiletypes();  break;
        case 'latestposts': if($a->check("chan.mod.latestposts"))   $this->displayLatestPosts();break;
        case 'reports':     if($a->check("chan.mod.reports"))       $this->displayReports();    break;
        case 'bans':        if($a->check("chan.mod.bans"))          $this->displayBans();       break;
        case 'settings':    if($a->check("chan.admin.settings"))    $this->displayGeneralOptions();break;
        default:            if($a->check("chan.*"))                 $this->displayStatistics(); break;
    }
}

function displayPanel(){
    global $k,$a;
    if($a->check('chan.admin.*')){
        ?><li>Purplish
            <ul class="menu">
                <a href="<?=$k->url("admin","Chan/")?>"><li>Overview</li></a>
                <? if($a->check("chan.admin.settings")){ ?>
                <a href="<?=$k->url("admin","Chan/settings")?>"><li>Settings</li></a><? } ?>
                <? if($a->check("chan.admin.categories")){ ?>
                <a href="<?=$k->url("admin","Chan/categories")?>"><li>Categories</li></a><? } ?>
                <? if($a->check("chan.admin.boards")){ ?>
                <a href="<?=$k->url("admin","Chan/boards")?>"><li>Boards</li></a><? } ?>
                <? if($a->check("chan.admin.filetypes")){ ?>
                <a href="<?=$k->url("admin","Chan/filetypes")?>"><li>Filetypes</li></a><? } ?>
                <? if($a->check("chan.mod.latestposts")){ ?>
                <a href="<?=$k->url("admin","Chan/latestposts")?>"><li>Latest Posts</li></a><? } ?>
                <? if($a->check("chan.mod.reports")){ ?>
                <a href="<?=$k->url("admin","Chan/reports")?>"><li>Report Tickets</li></a><? } ?>
                <? if($a->check("chan.mod.ban")){ ?>
                <a href="<?=$k->url("admin","Chan/bans")?>"><li>Ban Entries</li></a><? } ?>
            </ul>
        </li><?
    }
}

function displayGeneralOptions(){
    global $c;
    if($_POST['action']=='Save'){
        Toolkit::set('chan_title',$_POST['title']);
        Toolkit::set('chan_opthumbsize',$_POST['opts'],'i');
        Toolkit::set('chan_thumbsize',$_POST['ts'],'i');
        Toolkit::set('chan_maxlines',$_POST['mlines'],'i');
        Toolkit::set('chan_tpp',$_POST['tpp'],'i');
        Toolkit::set('chan_posttimeout',$_POST['timeout'],'i');
        Toolkit::set('chan_trips',$_POST['trips']);
        Toolkit::set('chan_fileloc_extern',$_POST['cdn'],'u');
        Toolkit::set('chan_online',$_POST['online'],'b');
        echo('<div class="success">Options saved!</div>');
    }
    
    
    ?><form action="#" method="post" class="box">
        <h3>Settings</h3>
              <label>Chan Title:</label>       <input type="text" name="title" value="<?=$c->o['chan_title']?>" />
        <br /><label>OP Thumb Size:</label>    <input type="number" name="opts" value="<?=$c->o['chan_opthumbsize']?>" />
        <br /><label>Thumb Size:</label>       <input type="number" name="ts" value="<?=$c->o['chan_thumbsize']?>" />
        <br /><label>Short Post Lines:</label> <input type="number" name="mlines" value="<?=$c->o['chan_maxlines']?>" />
        <br /><label>Threads Per Page:</label> <input type="number" name="tpp" value="<?=$c->o['chan_tpp']?>" />
        <br /><label>Post Timeout:</label>     <input type="number" name="timeout" value="<?=$c->o['chan_posttimeout']?>" />
        <br /><label>Tripcodes:</label>        <textarea name="trips" style="vertical-align:text-top;"><?=$c->o['chan_trips']?></textarea>
        <br /><label>CDN Location:</label>     <input type="url" name="cdn" value="<?=$c->o['chan_fileloc_extern']?>" />
        <br /><label>Online:</label>           <input type="checkbox" name="online" value="1" <?=($c->o['chan_online']=='1')?'checked':''?> />
        <br /><input type="submit" name="action" value="Save" />
    </form><?
}

function displayStatistics(){
    include(MODULEPATH.'gui/Statistics.php');
    $weekago = time()-604800;
    
    $posts = DataModel::getData('','SELECT COUNT(postID) AS count,folder
                                    FROM ch_posts LEFT JOIN ch_boards ON BID=boardID
                                    WHERE time > ? GROUP BY folder ORDER BY folder DESC',array($weekago));
    if(is_array($posts)){
        $postsByBoard=array();
        foreach($posts as $post){
            $postsByBoard['Posts'][] = $post->count;
            $bb[]=$post->folder;
        }

        $chartByBoards = new Chart('postchart',$bb,$postsByBoard,'bar');
        $chartByBoards->setCaption('Posts in the last week - By board');
        $chartByBoards->display();
    }
}

function displayCategories(){
    if($_POST['action']=='Delete'){
        $cat = DataModel::getData('ch_categories','SELECT title FROM ch_categories WHERE title=?',array($_POST['title']));
        if($cat!=null){
            $cat->deleteData();
            echo('<div class="success">Category deleted!</div>');
        }
    }
    if($_POST['action']=='Add'){
        $cat = DataModel::getHull('ch_categories');
        $cat->title = $_POST['title'];
        $cat->order = implode(',',$_POST['order']);
        $cat->insertData();
        echo('<div class="success">Category added!</div>');
    }
    
    $cats = DataModel::getData('','SELECT * FROM ch_categories');
    $boards = DataModel::getData('', 'SELECT boardID,title FROM ch_boards');
    if($cats==null)$cats=array();if(!is_array($cats))$cats=array($cats);
    if($boards==null)$boards=array();if(!is_array($boards))$boards=array($boards);
    
    $bids = array(); $btitles = array();
    foreach($boards as $board){
        $bids[]=$board->boardID;
        $btitles[]=$board->title;
    }
    
    ?><form class="box fullwidth" action="#" method="post">
        <h4>Add a category:</h4>
        Title:  <input type="text" maxlength="16" name="title" required />
        Boards: <?=Toolkit::interactiveList('order', $btitles,$bids)?>
        <input type="submit" name="action" value="Add" />
    </form>
    <div class="box fullwidth">
        <table>
            <thead>
                <tr>
                    <th width="100px">Title</th>
                    <th>Boards</th>
                    <th width="20px"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($cats as $cat){ ?>
                    <tr>
                        <td><?=$cat->title?></td>
                        <td>
                            <? $boards = explode(',',$cat->order);
                            foreach($boards as $board){
                                $title=$btitles[array_search($board,$bids)];?>
                                <a href='<?=Toolkit::url('chan',$title)?>'><?=$title?></a>
                            <? } ?>
                        </td>
                        <td><form action="#" method="post">
                            <input type="hidden" name="title" value="<?=$cat->title?>" />
                            <input type="submit" name="action" value="Delete" />
                        </form></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    </div><?
}

function displayBoards(){
    $max = DataModel::getData('','SELECT COUNT(boardID) AS max FROM ch_boards');
    Toolkit::sanitizePager($max->max,array('title','folder','maxfilesize','maxpages','postlimit'),'title');
    $boards = DataModel::getData('', 'SELECT * FROM ch_boards 
                                      ORDER BY `'.$_GET['o'].'` '.$_GET['d'].' 
                                      LIMIT '.$_GET['f'].','.$_GET['s'],array());
    if($boards==null)$boards=array();if(!is_array($boards))$boards=array($boards);
    
    ?><form class="box fullwidth" action="<?=PROOT?>Chan/edit" method="post">
        <h4>Add a board:</h4>
        Title:  <input type="text" maxlength="128" name="title" required />
        Folder:  <input type="text" maxlength="32" name="folder" required />
        <input type="submit" name="action" value="Add" />
    </form>
    <div class="box fullwidth">
        <?=Toolkit::displayPager();?>
        <table>
            <thead>
                <tr>
                    <th width="150px"><a href="?o=title&a=<?=!$_GET['a']?>">Title</a></th>
                    <th width="90px"><a href="?o=folder&a=<?=!$_GET['a']?>">Folder</a></th>
                    <th width="">Filetypes</th>
                    <th width="80px"><a href="?o=maxfilesize&a=<?=!$_GET['a']?>">Max Filesize</a></th>
                    <th width="80px"><a href="?o=maxpages&a=<?=!$_GET['a']?>">Max Pages</a></th>
                    <th width="80px"><a href="?o=postlimit&a=<?=!$_GET['a']?>">Sage Limit</a></th>
                    <th width="">Options</th>
                    <th width="30px"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($boards as $board){ ?>
                    <tr>
                        <td><?=$board->title?></td>
                        <td><?=$board->folder?></td>
                        <td><?=$board->filetypes?></td>
                        <td><?=$board->maxfilesize?></td>
                        <td><?=$board->maxpages?></td>
                        <td><?=$board->postlimit?></td>
                        <td><?=$board->options?></td>
                        <td><form action="<?=PROOT?>Chan/edit" method="post">
                            <input type="hidden" name="title" value="<?=$board->title?>" />
                            <input type="submit" name="action" value="Edit" />
                        </form></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    </div><?
}

function displayEditBoard(){
    global $c;include(MODULEPATH.'gui/Editor.php');
    $board = DataModel::getData('ch_boards','SELECT * FROM ch_boards WHERE title=?',array($_POST['title']));
    
    if($board==null){
        $board = DataModel::getHull('ch_boards');
        $board->title=$_POST['title'];
        $board->folder=$_POST['folder'];
        $board->maxfilesize=15000;
        $board->maxpages=10;
        $board->postlimit=150;
        $board->defaulttheme=$c->o['chan_defaulttheme'];
        $board->filetypes='image/png;image/jpeg;image/gif';
        $board->options='t';
        $existing=false;
    }else{
        $existing=true;
    }
    
    if($_POST['action']=='Save'){
        try{
            $ret='';
            foreach($_POST as $key=>$val){$board->$key=$val;}
            $board->options=implode(',',$_POST['options']);
            $board->filetypes=implode(';',$_POST['filetypes']);
            
            if($existing){
                $board->saveData();
                $ret.='Board edited.';
            }else{
                Toolkit::mkdir(ROOT.DATAPATH.'chan/'.$board->folder.'/posts');
                Toolkit::mkdir(ROOT.DATAPATH.'chan/'.$board->folder.'/threads');
                Toolkit::mkdir(ROOT.DATAPATH.'chan/'.$board->folder.'/files');
                Toolkit::mkdir(ROOT.DATAPATH.'chan/'.$board->folder.'/thumbs');
                $board->insertData();
                $_POST['rebuild'][]='b';
                $ret.='Board added.';
            }

            include('boardgen.php');
            include('threadgen.php');
            include('postgen.php');
            if(in_array('b',$_POST['rebuild'])){
                BoardGenerator::generateBoardFromObject($board);
                $ret.='<br />Board regenerated.';
            }
            if(in_array('t',$_POST['rebuild'])){
                $threads = DataModel::getData('SELECT * FROM ch_posts WHERE PID=0 AND BID=?',array($board->boardID));
                foreach($threads as $thread){ThreadGenerator::generateThreadFromObject($thread);}
                $ret.='<br />Threads regenerated.';
            }
            if(in_array('p',$_POST['rebuild'])){
                $posts = DataModel::getData('SELECT * FROM ch_posts WHERE BID=?',array($board->boardID));
                foreach($posts as $post){PostGenerator::generatePostFromObject($post);}
                $ret.='<br />Posts regenerated.';
            }
            echo('<div class="success">'.$ret.'</div>');
        }catch(Exception $ex){
            echo('<div class="failure">'.$ex->getMessage().'</div>');
        }
    }
    
    if($_POST['action']=='Delete'&&$_POST['sure']=='sure'&&$existing){
        try{
            Toolkit::rmdir(ROOT.DATAPATH.'chan/'.$board->folder);
            $c->query('DELETE FROM ch_posts WHERE BID=?',array($board->boardID));
            $c->query('DELETE FROM ch_boards WHERE boardID=?',array($board->boardID));
            echo('<div class="success">Board deleted.</div>');
        }catch(Exception $ex){
            echo('<div class="failure">Failed to delete: '.$ex->getMessage().'</div>');
        }
    }
    
    $filetypes = DataModel::getData('','SELECT title,mime FROM ch_filetypes');
    if($filetypes==null)$filetypes=array();if(!is_array($filetypes))$filetypes=array($filetypes);
    $fnames=array('png','jpeg','gif');
    $fmimes=array('image/png','image/jpeg','image/gif');
    foreach($filetypes as $type){
        $fnames[]=$type->title;
        $fmimes[]=$type->mime;
    }
    
    ?><form class="box fullwidth" method="post" action="#">
        <h3>Edit Board Settings</h3>
              <label>Title: </label>          <input type="text" name="title" value="<?=$board->title?>" required maxlength="128"/>
              <label>Folder: </label>         <input type="text" name="folder" value="<?=$board->folder?>" required maxlength="32" <?=($existing)? 'disabled':''?>/>
        <br /><label>Max Filesize: </label>   <input type="number" name="maxfilesize" value="<?=$board->maxfilesize?>" required/>
              <label>Max Pages: </label>      <input type="number" name="maxpages" value="<?=$board->maxpages?>" required/>
        <br /><label>Sage Limit: </label>     <input type="number" name="postlimit" value="<?=$board->postlimit?>" required/>
              <label>Default Theme: </label>  <input type="text" name="defaulttheme" value="<?=$board->defaulttheme?>" maxlength="32"/>
        <br /><label>Filetypes: </label>      <?=Toolkit::interactiveList('filetypes', $fnames, $fmimes, explode(';',$board->filetypes))?>
        <br /><label>Options: </label>
            <input type="checkbox" name="options[]" value="t" <?=(strpos($board->options,'t')!==FALSE)? 'checked': ''?> /> New Threads
            <input type="checkbox" name="options[]" value="l" <?=(strpos($board->options,'l')!==FALSE)? 'checked': ''?> /> Locked
            <input type="checkbox" name="options[]" value="h" <?=(strpos($board->options,'h')!==FALSE)? 'checked': ''?> /> Hidden
            <input type="checkbox" name="options[]" value="n" <?=(strpos($board->options,'n')!==FALSE)? 'checked': ''?> /> Anon Only
            <input type="checkbox" name="options[]" value="a" <?=(strpos($board->options,'a')!==FALSE)? 'checked': ''?> /> Archive
            <input type="checkbox" name="options[]" value="f" <?=(strpos($board->options,'f')!==FALSE)? 'checked': ''?> /> File Required
        <br /><label>Custom board header:</label>
            <textarea style="display:inline-block;vertical-align:text-top;" name="subject"><?=$board->subject?></textarea>
        <hr /><input type="submit" name="action" value="Save" /> And rebuild the
            <input type="checkbox" name="rebuild[]" value="b" checked /> board
            <input type="checkbox" name="rebuild[]" value="t" checked /> threads
            <input type="checkbox" name="rebuild[]" value="p" /> posts
        <div style="<?=($existing)?'float:right':'display:none'?>">
             <input type="checkbox" name="sure" value="sure" /> I am sure I want to
             <input type="submit" name="action" value="Delete" /></div>
    </form><?
}

function displayFiletypes(){
    if($_POST['action']=='Delete'){
        $type = DataModel::getData('ch_filetypes','SELECT title,preview FROM ch_filetypes WHERE title=?',array($_POST['title']));
        if($type!=null){
            unlink(ROOT.IMAGEPATH.'chan/previews/'.$type->preview);
            $type->deleteData();
            echo('<div class="success">Filetype deleted!</div>');
        }
    }
    if($_POST['action']=='Add'){
        try{
            $file = Toolkit::uploadFile('preview', ROOT.IMAGEPATH.'chan/previews/' , 2000, array('image/png','image/jpeg','image/gif'), false,$_POST['title']);
            $type = DataModel::getHull('ch_filetypes');
            $type->title = $_POST['title'];
            $type->mime = $_POST['mime'];
            $type->preview = substr($file,strrpos($file,'/')+1);
            $type->insertData();
            echo('<div class="success">Filetype added!</div>');
        }catch(Exception $ex){
            echo('<div class="failure">'.$ex->getMessage().'</div>');
        }
    }
    
    $filetypes = DataModel::getData('','SELECT * FROM ch_filetypes');
    if($filetypes==null)$filetypes=array();if(!is_array($filetypes))$filetypes=array($filetypes);
    
    ?>
    <form class="box fullwidth" enctype="multipart/form-data" action="#" method="post">
        <h4>Add a filetype:</h4>
        Title:      <input type="text" maxlength="32" name="title" required />
        Mime Type:  <input type="text" maxlength="64" name="mime" required />
                    <input type="file" name="preview" required />
        <input type="submit" name="action" value="Add" />
    </form>
    <div class="box fullwidth">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Mime</th>
                    <th>Preview</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($filetypes as $type){ ?>
                    <tr>
                        <td><?=$type->title?></td>
                        <td><?=$type->mime?></td>
                        <td><img src="<?=IMAGEPATH.'chan/previews/'.$type->preview?>" alt="Preview" /></td>
                        <td><form method="post" action="#">
                            <input type="hidden" name="title" value="<?=$type->title?>" />
                            <input type="submit" name="action" value="Delete" />
                        </form></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    </div><?
}

function displayLatestPosts(){
    $max = DataModel::getData('','SELECT COUNT(ip) AS max FROM ch_posts');
    Toolkit::sanitizePager($max->max);
    $posts = DataModel::getData('','SELECT postID,folder
                                    FROM ch_posts LEFT JOIN ch_boards ON BID=boardID
                                    ORDER BY time DESC
                                    LIMIT '.$_GET['f'].','.$_GET['s'],array());
    if($posts==null)$posts=array();if(!is_array($posts))$posts=array($posts);
    
    ?><div class="box fullwidth">
        <?=Toolkit::displayPager();?>
        <? foreach($posts as $post){
            @include(ROOT.DATAPATH.'chan/'.$post->folder.'/posts/'.$post->postID.'.php');
            @include(ROOT.DATAPATH.'chan/'.$post->folder.'/posts/_'.$post->postID.'.php');
        } ?>
    </div>
    <link rel='stylesheet' type='text/css' href='<?=DATAPATH?>css/chanpost.css' />
    <style>.postInfo .buttons{display:inline-block;}</style>
    <script type="text/javascript">
        $(function(){
            $("body").append('<div id="popup" class="jqmWindow"></div>');
            $('#popup').jqm({ajax: '@href', 
                    trigger: '.moveThread, .mergeThread, .banUser, .purgeUser, .searchUser, .deletePost, .editPost, #options',
                    onLoad: function(){
                        eval($('#popup script').html());
                    }});
        });
        </script><?
}

function displayReports(){
    global $a;
    if($_POST['action']=='Remove'){
        $report = DataModel::getData('ch_reports','SELECT ip,time FROM ch_reports WHERE ip=? AND time=?',array($_POST['ip'],$_POST['time']));
        if($report!=null){
            $report->deleteData();
            echo('<div class="success">Report removed.</div>');
        }
    }
    
    $max = DataModel::getData('','SELECT COUNT(ip) AS max FROM ch_reports');
    Toolkit::sanitizePager($max->max,array('ip','time'),'time');
    $reports = DataModel::getData('ch_reports','SELECT *,boardID
                                          FROM ch_reports LEFT JOIN ch_boards USING(folder)
                                          ORDER BY `'.$_GET['o'].'` '.$_GET['d'].' 
                                          LIMIT '.$_GET['f'].','.$_GET['s'],array());
    if($reports==null)$reports=array();if(!is_array($reports))$reports=array($reports);
    
    ?><div class="box fullwidth">
        <?=Toolkit::displayPager();?>
        <table>
            <thead>
                <tr>
                    <th width="90px"><a href="?o=ip&a=<?=!$_GET['a']?>">Reporter IP</a></th>
                    <th width="120px"><a href="?o=time&a=<?=!$_GET['a']?>">Time</a></th>
                    <th width="">Reason</th>
                    <th width="10px">Post</th>
                    <th width="200px"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($reports as $report){ ?>
                    <tr>
                        <td><?=$report->ip?></td>
                        <td><?=Toolkit::toDate($report->time,'d.m.Y H:i:s')?></td>
                        <td><?=$report->reason?></td>
                        <td><a class="postReference" folder="<?=$report->folder?>" id="<?=$report->PID?>">View</a></td>
                        <td>
                            <form method="post" action="#" style="display:inline-block;">
                                <input type="hidden" name="time" value="<?=$report->time?>" />
                                <input type="hidden" name="ip" value="<?=$report->ip?>" />
                                <input type="submit" name="action" value="Remove" />
                            </form>
                            <? if($a->check('chan.mod.delete')){ ?>
                                <a href="<?=PROOT?>api/chan/delete?id=<?=$report->PID?>&bid=<?=$report->boardID?>" class="button deletePost">Delete Post</a>
                            <? } ?>
                            <? if($a->check('chan.mod.ban')){ ?>
                                <a href="<?=PROOT?>api/chan/ban?id=<?=$report->PID?>&bid=<?=$report->boardID?>" class="button banUser">Ban</a>
                            <? } ?>
                        </td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
        <link rel='stylesheet' type='text/css' href='<?=DATAPATH?>css/chanpost.css' />
        <script type="text/javascript" src="<?=DATAPATH?>js/chan_admin_postpreview.js"></script>
        <script type="text/javascript">
            $(function(){
                $("body").append('<div id="popup" class="jqmWindow"></div>');
                $('#popup').jqm({ajax: '@href', 
                     trigger: '.moveThread, .mergeThread, .banUser, .purgeUser, .searchUser, .deletePost, .editPost, #options',
                     onLoad: function(){
                         eval($('#popup script').html());
                     }});
            });
         </script>
    </div><?
}

function displayBans(){
    
    if($_POST['action']=='Delete'){
        $ban = DataModel::getData('ch_bans','SELECT * FROM ch_bans WHERE ip=? AND time=?',array($_POST['ip'],$_POST['time']));
        if($ban!=null){
            $ban->deleteData();
            echo('<div class="success">Ban on '.$_POST['ip'].' lifted.</div>');
        }
    }
    
    $max = DataModel::getData('','SELECT COUNT(ip) AS max FROM ch_bans');
    Toolkit::sanitizePager($max->max,array('ip','time','period','mute'),'time');
    $bans = DataModel::getData('ch_bans','SELECT * FROM ch_bans 
                                          ORDER BY `'.$_GET['o'].'` '.$_GET['d'].' 
                                          LIMIT '.$_GET['f'].','.$_GET['s'],array());
    if($bans==null)$bans=array();if(!is_array($bans))$bans=array($bans);
    
    ?><div class="box fullwidth">
        <?=Toolkit::displayPager();?>
        <table>
            <thead>
                <tr>
                    <th width="90px"><a href="?o=ip&a=<?=!$_GET['a']?>">IP</a></th>
                    <th width="120px"><a href="?o=time&a=<?=!$_GET['a']?>">Time</a></th>
                    <th width="70px"><a href="?o=period&a=<?=!$_GET['a']?>">Period</a></th>
                    <th width="">Reason</th>
                    <th width="">Appeal</th>
                    <th width="20px"><a href="?o=mute&a=<?=!$_GET['a']?>">Mute</a></th>
                    <th width="10px">Post</th>
                    <th width="10px"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($bans as $ban){ ?>
                    <tr>
                        <td><?=$ban->ip?></td>
                        <td><?=Toolkit::toDate($ban->time,'d.m.Y H:i:s')?></td>
                        <td><?=Toolkit::toLiteralTime($ban->period)?></td>
                        <td><?=$ban->reason?></td>
                        <td><?=$ban->appeal?></td>
                        <td><?=$ban->mute?></td>
                        <td><a class="postReference" folder="<?=$ban->folder?>" id="<?=$ban->PID?>">View</a></td>
                        <td>
                            <form method="post" action="#">
                                <input type="hidden" name="time" value="<?=$ban->time?>" />
                                <input type="hidden" name="ip" value="<?=$ban->ip?>" />
                                <input type="submit" name="action" value="Delete" />
                            </form>
                        </td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
        <link rel='stylesheet' type='text/css' href='<?=DATAPATH?>css/chanpost.css' />
        <script type="text/javascript" src="<?=DATAPATH?>js/chan_admin_postpreview.js"></script>
    </div><?
    
}
}
?>