<? function write_header($title,$board,$thread=0,$options="",$posterIP=""){
    global $c,$a,$t,$k,$l;

    $time = explode(' ',microtime());
    $time = $time[1]+$time[0];
    global $GEN_STARTTIME;$GEN_STARTTIME=$time;
    
    ?>
    <?='<? if(!defined("INIT"))include("'.TROOT.'config.php"); ?>'."\n"?>
    <?='<? global $a,$l,$t; if($a==null)$a = $l->loadModule("Auth"); ?>'."\n"?>
    <?='<? if(!@ob_start("ob_gzhandler"))@ob_start(); ?>'?>
    <? if(strpos($options,"h")!==FALSE){ ?>
        <?='<? if(!$a->check("chan.mod"))include("'.PAGEPATH.'chan/chan_500.php"); ?>'?>
    <? } ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$title?></title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>
        <script type="text/javascript" src="<?=DATAPATH?>js/jquery.cookie.js"></script>
        <? for($i=0;$i<count($t->js );$i++){echo("<script type='text/javascript' src='".PROOT."themes/".$t->name."/".$t->js[$i]."' ></script>"."\n");} ?>
        <? for($i=0;$i<count($t->css);$i++){echo("<link rel='stylesheet' type='text/css' id='dynstyle' href='".PROOT."themes/".$t->name."/".$t->css[$i]."' />"."\n");} ?>
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
        <? if(strpos($board->defaultTheme,".css")==FALSE)$board->defaultTheme="steven.css"; ?>
        <script type="text/javascript">
            if($.cookie("chan_style")!=null)    $("#dynstyle").attr("href",$.cookie("chan_style"));
            else                                $("#dynstyle").attr("href","<?=PROOT.'themes/'.$t->name.'/css/'.$board->defaultTheme?>");
        </script>
        <?=file_get_contents(TROOT.'callables/meta.php')?>
    </head>
    <?='<? ob_flush(); ?>'?>
    <body><div class="content">
    
    <?=$l->triggerPARSE('Purplish',$board->subject);?>
    <?if(file_exists(PAGEPATH.'chan_glob_header.php'))echo(file_get_contents(PAGEPATH.'chan_glob_header.php')); ?>
    <div class="boardTitle"><?=$board->title?></div>

    

    <ul class="menu" id="menu">
        <li><a href="<?=$k->url("chan","/")?>" class="menulink">TyNET</a><ul>
            <?=$t->printMenu();?>
        </ul></li>
        
        <? $boards = DataModel::getData('','SELECT boardID,title,folder FROM ch_boards');
        $cats = DataModel::getData('','SELECT order FROM ch_categories');
        foreach($cats as $cat){
            $order = explode(',',$cat);
            foreach($order AS $bID){
                foreach($boards as $board){
                    if($board->boardID==$bID){
                        ?><li><a href="<?=PROOT?><?=$board->folder?>"><?=$board->title?></a></li><? 
                        break;
                    }
                }
            }
        }
        ?>
        
        <li class="separator2">&nbsp;</li><li><a href="#">Themes</a><ul>
            <? $d = opendir(TROOT."themes/".$t->name."/css/");
            while (($file = readdir($d))!==FALSE) {
                $ext = substr($file,strrpos($file,"."));
                if($ext==".css")
                    ?><li><a href="#" class="styleLink" id="/themes/<?=$t->name?>/css/<?=$file?>"><?=substr($file,0,strrpos($file,"."))?></a></li><?
            }
            closedir($d); ?>
        </ul></li>
        
        <li><a href="#" id="watchMenuButton" >Watch</a></li>
        <li><a href="/api.php?m=Chan&c=getOptionsPage" id="options" >Options</a></li>

        <? if($a->check("chan.mod")){ ?>
            <li class="separator">&nbsp;</li><li><a href="<?=$k->url("","admin/")?>">Admin</a><ul>
                <li><a href="<?=$k->url("admin","Chan/latestposts")?>">Latest Posts</a></li>
                <li><a href="<?=$k->url("admin","Chan/reports")?>">Reports (;
                <? $temp=$c->getData("SELECT COUNT(ip) FROM chan_reports");echo($temp[0]["COUNT(ip)"]); ?>)</a>
            </ul></li>
        <? } ?>
    </ul>

    
    <? if((strpos($options,"l")===FALSE&&(($thread==0&&strpos($options,"t")!==FALSE)||$thread!=0))){ ?>
        <?='<? if("'.$posterIP.'"==$_SERVER["REMOTE_ADDR"]||strpos("'.$options.'",",h")===FALSE||$a->check("chan.mod.hidden")){ ?>;'?>
            <div class="postBox" id="postBox"><a name="postBox"></a>
            <form action="<?=Toolkit::url("api","chan/post")?>" method="post" id="postForm" enctype="multipart/form-data">
                <div>
                    <label class="eldesc">Name/Mail</label>;
                    <? if(strpos($options,"n")===FALSE){ ?>
                        <input type="text" name="varname" id="varname" placeholder="name#tripcode#secure#add" />
                    <? } ?>
                    <input type="text" name="varmail" id="varmail" placeholder="email#sage#noko" />
                </div>
                <div>
                    <input type="text" name="vartitle" id="vartitle" placeholder="title" /><input type="submit" name="varsubmit" id="varsubmit" value="Post" />
                </div><span id="replyto"></span>
                    <? if($a->check("chan.mod.bbcodes"))$t->apiCall("getBBCodeBar",array("formid"=>"fulltext","level"=>"99"));
                    else                                $t->apiCall("getBBCodeBar",array("formid"=>"fulltext","level"=> "0")); ?>
                    <textarea name="vartext" id="fulltext"></textarea>
                <div>
                    <label class="eldesc">File</label>   
                    <input type="file" name="varfile" id="varfile"/>
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
                            <br /><input type="checkbox" name="varoptions[]" value="l" />Locked 
                            <input type="checkbox" name="varoptions[]" value="s" />Sticky 
                            <input type="checkbox" name="varoptions[]" value="e" />Autosage 
                        <? } ?>
                    </div>
                <?='<? } ?>'?>
                <div>
                    <label class="eldesc">Password</label>
                    <input type="password" name="varpassword" class="password" id="varpass"/>
                </div>
                <? $filetypes=explode(";",$board->filetypes);
                for($x=0;$x<count($filetypes);$x++){$filetypes[$x]=substr($filetypes[$x],strpos($filetypes[$x],"/")+1);}
                $filetypes=implode(", ",$filetypes); ?>
                <div class="tac">By posting you agree to the <a href="<?=$k->url('pages','TAC')?>">Terms And Conditions</a>.<br />Allowed Filetypes: <?=$filetypes?> </div>;
                <input type="hidden" name="varboard" id="varboard" value="<?=$board->boardID?>" />
                <input type="hidden" name="varthread" id="varthread" value="<?=$thread?>" />
                <div class="postResponse" id="postResponse"></div>
            </form></div>
        <?='<? } ?>'?>
    <? } ?>

    <form action="<?=Toolkit::url('api','chan/report')?>" method="post" id="boardForm">
    <?
    
}
?>
