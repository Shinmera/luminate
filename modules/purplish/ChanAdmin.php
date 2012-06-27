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
        default:            if($a->check("chan.*"))                 $this->displayStatistics(); break;
    }
}

function displayPanel(){
    global $k,$a;
    if($a->check('chan.admin.*')){
        ?><li>Purplish
            <ul class="menu">
                <a href="<?=$k->url("admin","Chan/")?>"><li>Overview</li></a>
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
    
}

function displayBoards(){
    
}

function displayEditBoard(){
    
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