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
        case 'latestimages':if($a->check("chan.mod.latestimages"))  $this->displayLatestImages();break;
        case 'tickets':     if($a->check("chan.mod.tickets"))       $this->displayReports();    break;
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
                <? if($a->check("chan.mod.latestimages")){ ?>
                <a href="<?=$k->url("admin","Chan/latestimages")?>"><li>Latest Images</li></a><? } ?>
                <? if($a->check("chan.mod.tickets")){ ?>
                <a href="<?=$k->url("admin","Chan/tickets")?>"><li>Report Tickets</li></a><? } ?>
                <? if($a->check("chan.mod.bans")){ ?>
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
    
}

function displayLatestPosts(){
    
}

function displayLatestImages(){
    
}

function displayReports(){
    
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
        <script type="text/javascript">
            $(function(){
                $("body").append('<div style="position:absolute;display:none;box-shadow: 0 0 5px #000;" id="previewPost"></div>');
                $("a.postReference").hover(function(e){
                    $("#previewPost").html("");
                    $("#previewPost").css({"right":(-e.pageX+10+$(window).width())+"px","top":(e.pageY)+"px"});
                    $.ajax({
                        url: '<?=DATAPATH?>chan/'+$(this).attr("folder")+'/posts/'+$(this).attr("id")+'.php',
                        success: function(data){
                            $('#previewPost').html(data);
                            $('#previewPost').css('display','inline-block');
                        },
                        error: function() {
                            $.ajax({
                                url: '<?=DATAPATH?>chan/'+$(this).attr("folder")+'/posts/_'+$(this).attr("id")+'.php',
                                success: function(data) {
                                    $("#previewPost").html(data);
                                    $('#previewPost').css('display','inline-block');
                                }
                            });
                        }
                    });
                },function(){
                    $('#previewPost').css('display','none');
                });
            });
        </script>
    </div><?
    
}
}
?>