<? function write_header($title,$board,$thread=0,$options="",$posterIP=""){
    global $c,$a,$t,$k,$l;

    $time = explode(' ',microtime());
    $time = $time[1]+$time[0];
    global $GEN_STARTTIME;$GEN_STARTTIME=$time;
    
    ?>
    <?='<? if(!defined("INIT"))include("'.TROOT.'config.php"); ?>'."\n"?>
    <?='<? global $a,$l,$t,$c; if($a==null)$a = $l->loadModule("Auth"); ?>'."\n"?>
    <?=(BUFFER)?'<? if(COMPRESS)ob_start("ob_gzhandler");else ob_start(); ?>':''?>
    <? if(strpos($options,"h")!==FALSE){ ?>
        <?='<? if(!$a->check("chan.mod"))include("'.PAGEPATH.'chan/chan_403.php"); ?>'?>
    <? } ?>
    <? if(strpos($board->defaultTheme,".css")==FALSE)$board->defaultTheme=$c->o['chan_theme']; ?>
    <? global $DYNSTYLE;$DYNSTYLE=PROOT."themes/chan/css/".$board->defaultTheme; ?>
    <? include(PAGEPATH.'global_header.php'); ?>
    <script type="text/javascript">
        if($.cookie("chan2_style")!=null){
            $("#dynstyle").attr("href","<?=PROOT?>themes/chan/css/"+$.cookie("chan2_style"));
        }
    </script>
    <?=(BUFFER)?'<? ob_flush(); ?>':''?>
    <body><div class="content">
    
    <?=$l->triggerPARSE('Purplish',$board->subject,true,true);?>
    <? include(PAGEPATH.'chan/chan_glob_header.php');?>
    <h1 class="boardTitle"><?=$board->title?></h1>

    <? $l->triggerHook('header','Purplish',array($title,$board,$thread)); ?>

    <ul class="menu sf-menu" id="menu">
        <li><a href="<?=$k->url("www")?>" class="menulink">TyNET</a>
            <?=$t->printMenu();?>
        </li>
        <li><a href="<?=PROOT?>"><?=$c->o['chan_title']?></a></li>
        <li class="separator2">&nbsp;</li>
        <? $boards = DataModel::getData('','SELECT boardID,title,folder FROM ch_boards');
        $cats = DataModel::getData('','SELECT `order` FROM ch_categories');
        Toolkit::assureArray($boards);Toolkit::assureArray($cats);
        foreach($cats as $cat){
            $order = explode(',',$cat->order);
            foreach($order AS $bID){
                foreach($boards as $bboard){
                    if($bboard->boardID==$bID){
                        ?><li><a href="<?=PROOT?><?=$bboard->folder?>" title="<?=$bboard->title?>"><?=$bboard->folder?></a></li><? 
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

        <?='<? if($a->check("chan.mod")){ ?>'?>
            <li class="separator">&nbsp;</li><li><a href="<?=$k->url("admin","Chan/")?>">Admin</a><ul>
                <li><a href="<?=$k->url("admin","Chan/latestposts")?>">Latest Posts</a></li>
                <li><a href="<?=$k->url("admin","Chan/reports")?>">Reports (
                <?='<? $temp=$c->getData("SELECT COUNT(ip) FROM ch_reports",array());echo($temp[0]["COUNT(ip)"]); ?>'?> )</a>
            </ul></li>
        <?='<? } ?>'?>
            
        <? $l->triggerHook('menu','Purplish',array($title,$board,$thread)); ?>
    </ul>

    
    <? if((strpos($options,"l")===FALSE&&(($thread==0&&strpos($options,"t")!==FALSE)||$thread!=0))){ ?>
        <?='<? if("'.$posterIP.'"==$_SERVER["REMOTE_ADDR"]||strpos("'.$options.'",",h")===FALSE||$a->check("chan.mod.hidden")){ ?>'?>
            <div class="postBox" id="postBox"><a name="postBox"></a>
            <form action="<?=Toolkit::url("api","chan/post")?>" method="post" id="postForm" enctype="multipart/form-data">
                <div>
                    <label class="eldesc">Name/Mail</label>
                    <? if(strpos($options,"n")===FALSE){ ?>
                        <input type="text" name="varname" id="varname" placeholder="name#tripcode#secure#add" />
                    <? } ?>
                    <input type="text" name="varmail" id="varmail" placeholder="email#sage#noko" />
                    <input type="text" name="email" id="email" placeholder="should not be filled out" />
                    <style>input#email{display:none}</style>
                </div>
                <div>
                    <input type="text" name="vartitle" id="vartitle" placeholder="title" /><input type="submit" name="varsubmit" id="varsubmit" value="Post" />
                </div>
                <span id="replyto"></span>
                    <? $availableTags = $l->triggerHookSequentially("GETtags","CORE",array());?>
                    <ul class="toolbar">
                        <? foreach($availableTags as $tag){
                          if(in_array($tag[3],array('default','plus','chan'))&&file_exists(ROOT.DATAPATH.'images/icons/'.$tag[0].'.png')){ ?>
                            <li><img title="<?=$tag[1]?>" alt="<?=$tag[0]?>" class="icon" src="<?=DATAPATH.'images/icons/'.$tag[0]?>.png" tag="<?=$tag[2]?>" /></li>
                        <? }} ?>
                    </ul>
                    <script type="text/javascript">
                        $().ready(function(){
                            $(".toolbar .icon").each(function(){
                                $(this).click(function(){
                                    insertAdv($("#fulltext"),$(this).attr("tag"));
                                });
                            });
                        });
                    </script>
                    <textarea name="vartext" id="fulltext"></textarea>
                <div>
                    <label class="eldesc">File</label>   
                    <input type="file" name="varfile" id="varfile"/>
                </div>
                <div>
                    <label class="eldesc">Password</label>
                    <input type="password" name="varpassword" class="password" id="varpass"/>
                </div>
                <div>
                    <label class="eldesc">Options</label>
                    <input type="checkbox" name="varoptions[]" value="r" />Spoiler 
                    <input type="checkbox" name="varoptions[]" value="w" />NSFW 
                </div>
                <?='<? if($a->check("chan.mod")){ ?>'?>
                    <div>
                        <label class="eldesc">Mod Options</label>
                        <input type="checkbox" name="varoptions[]" value="m" />Modpost 
                        <input type="checkbox" name="varoptions[]" value="h" />Hidden 
                        <? if($thread==0){ ?>
                            <br />
                            <label class="eldesc">Thread Options</label>
                                <input type="checkbox" name="varoptions[]" value="l" />Locked 
                                <input type="checkbox" name="varoptions[]" value="s" />Sticky 
                                <input type="checkbox" name="varoptions[]" value="e" />Autosage 
                        <? } ?>
                    </div>
                <?='<? } ?>'?>
                <? $filetypes=explode(";",$board->filetypes);
                for($x=0;$x<count($filetypes);$x++){$filetypes[$x]=substr($filetypes[$x],strpos($filetypes[$x],"/")+1);}
                $filetypes=implode(", ",$filetypes); ?>
                <div class="tac">
                    By posting you agree to the <a href="<?=$k->url('page','tac')?>">Terms And Conditions</a>.<br />
                    Allowed Filetypes: <?=$filetypes?>
                </div>
                <input type="hidden" name="varboard" id="varboard" value="<?=$board->boardID?>" />
                <input type="hidden" name="varthread" id="varthread" value="<?=$thread?>" />
                <div class="postResponse" id="postResponse"></div>
                <? $l->triggerHook('form','Purplish',array($title,$board,$thread)); ?>
            </form></div>
        <?='<? } ?>'?>
    <? } ?>

    <form action="<?=Toolkit::url('api','chan/report')?>" method="post" id="boardForm">
    <?
    
}
?>
