<? global $t,$c,$a; ?>
<script type="text/javascript">
    if($.cookie("chan2_style")!=null){
        $("#dynstyle").attr("href","<?=PROOT?>themes/chan/css/"+$.cookie("chan2_style"));
    }
</script>
<ul class="menu sf-menu" id="menu">
        <li><a href="<?=PROOT?>" class="menulink">TyNET</a>
            <?=$t->printMenu();?>
        </li>
        
        <? $boards = DataModel::getData('','SELECT boardID,title,folder FROM ch_boards');
        $cats = DataModel::getData('','SELECT `order` FROM ch_categories');
        if($cats==null)$cats=array();if(!is_array($cats))$cats=array($cats);
        if($boards==null)$boards=array();if(!is_array($boards))$boards=array($boards);
        foreach($cats as $cat){
            $order = explode(',',$cat->order);
            foreach($order AS $bID){
                foreach($boards as $board){
                    if($board->boardID==$bID){
                        ?><li><a href="<?=PROOT?><?=$board->folder?>" title="<?=$board->title?>"><?=$board->folder?></a></li><? 
                        break;
                    }
                }
            }
        }
        ?>
        
        <li class="separator2">&nbsp;</li><li><a href="#">Themes</a><ul>
            <? $d = opendir(TROOT."themes/chan/css/");
            while (($file = readdir($d))!==FALSE) {
                $ext = substr($file,strrpos($file,"."));
                if($ext==".css"){
                    ?><li><a href="#" class="styleLink" id="<?=$file?>"><?=substr($file,0,strrpos($file,"."))?></a></li><?
                }
            }
            closedir($d); ?>
            <li><b>Note:</b> Some themes need a page reload after being selected, due to JS changes.</li>
        </ul></li>
        
        <li><a href="#" id="watchMenuButton" >Watch</a></li>
        <li><a href="<?=PROOT?>api/chan/options" id="options" >Options</a></li>

        <? if($a->check("chan.mod")){ ?>
            <li class="separator">&nbsp;</li><li><a href="<?=Toolkit::url("","admin/")?>">Admin</a><ul>
                <li><a href="<?=Toolkit::url("admin","Chan/latestposts")?>">Latest Posts</a></li>
                <li><a href="<?=Toolkit::url("admin","Chan/reports")?>">Reports (
                <? $temp=$c->getData("SELECT COUNT(ip) FROM ch_reports",array());echo($temp[0]["COUNT(ip)"]);?> )</a>
            </ul></li>
        <? } ?>
    </ul>
    
    <div id="content">