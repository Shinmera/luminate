<? function write_header($title,$board,$thread=0,$options="",$posterIP=""){
    global $c,$a,$t,$k,$p;
    if(!class_exists("ChanDataBoard"))include(TROOT.'modules/chan/data.php');

    $time = explode(' ',microtime());
    $time = $time[1]+$time[0];
    global $GEN_STARTTIME;$GEN_STARTTIME=$time;

    $write="";
    $write.='<? global $a,$t;if(!@ob_start("ob_gzhandler"))@ob_start(); ?>'."\n";
    if(strpos($options,"h")!==FALSE)$write.='<? if(!$a->check("chan.mod"))die("Access Denied."); ?>';
    $write.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
    $write.='<html xmlns="http://www.w3.org/1999/xhtml"><head>'."\n";
    $write.='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\n";
    $write.='<title>'.$title.'</title>'."\n";
    //$write.='<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>'."\n";
    $write.='<script type="text/javascript" src="/callables/jquery.js"></script>'."\n";
    $write.='<script type="text/javascript" src="/callables/jquery.cookie.js"></script>'."\n";
    for($i=0;$i<count($t->js );$i++){$write.="<script type='text/javascript' src='".PROOT."themes/".$t->name."/".$t->js[$i]."' ></script>"."\n";}
    for($i=0;$i<count($t->css);$i++){$write.="<link rel='stylesheet' type='text/css' id='dynstyle' href='".PROOT."themes/".$t->name."/".$t->css[$i]."' />"."\n";}
    $write.='<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>'."\n";
    if(strpos($board->defaultTheme,".css")==FALSE)$board->defaultTheme="steven.css";
    $write.='<script type="text/javascript">if($.cookie("chan_style")!=null)
                        $("#dynstyle").attr("href",$.cookie("chan_style"));
                        else $("#dynstyle").attr("href","/themes/'.$t->name.'/css/'.$board->defaultTheme.'");
             </script>';
    $write.=file_get_contents(TROOT.'callables/meta.php')."\n";
    $write.='</head>'."\n";
    $write.='<? ob_flush(); ?>'."\n";
    $write.='<body><div class="content">'."\n";
    
    $write.=$p->deparseAll($board->subject,100)."\n";
    if(file_exists(PAGEPATH.'chan_glob_header.php'))$write.=file_get_contents(PAGEPATH.'chan_glob_header.php');
    $write.='<div class="boardTitle">'.$board->title.'</div>'."\n";

    //TOOLBAR
    $moduleID=$c->msMID[array_search("Chan",$c->msMTitle)];
    $c->loadCategories($moduleID);
    $boards = ChanDataBoard::loadFromDB("SELECT boardID,folder,PID,title FROM ch_boards",array());

    $write.='<ul class="menu" id="menu">'."\n";
        $write.='<li><a href="'.$k->url("chan","/").'" class="menulink">TyNET</a><ul>'."\n";
            $write.=$t->printMenu(true)."\n";
        $write.='</ul></li>'."\n";
        for($i=0;$i<count($c->msCID);$i++){
            $list=explode(";",$c->msCSubject[$i]);
            $write.='<li class="separator">&nbsp;</li>';
            for($j=0;$j<count($list);$j++){
                for($n=0;$n<count($boards);$n++){
                if($boards[$n]->boardID==$list[$j]){
                    $write.='<li><a href="'.$k->url("chan",$boards[$n]->folder).'" title="'.$boards[$n]->title.'">'.$boards[$n]->folder.'</a><ul>';
                    $write.=recursiveBoardPrint($boards[$n]->boardID,$boards);
                    $write.='</ul></li>';
                }}
            }
        }
        
        $write.='<li class="separator2">&nbsp;</li><li><a href="#">Themes</a><ul>'."\n";
            $d = opendir(TROOT."themes/".$t->name."/css/");
            while (false !== ($file = readdir($d))) {
                $ext = substr($file,strrpos($file,"."));
                if($ext==".css")$write.='<li><a href="#" class="styleLink" id="/themes/'.$t->name.'/css/'.$file.'">'.substr($file,0,strrpos($file,".")).'</a></li>'."\n";
            }
            closedir($d);
        $write.='</ul></li>'."\n";
        
        $write.='<li><a href="#" id="watchMenuButton" >Watch</a></li>'."\n";
        $write.='<li><a href="/api.php?m=Chan&c=getOptionsPage" id="options" >Options</a></li>'."\n";

        $write.='<? if($a->check("chan.mod")){ ?>'."\n";
            $write.='<li class="separator">&nbsp;</li><li><a href="'.$k->url("","admin/").'">Admin</a><ul>'."\n";
                $write.='<li><a href="'.$k->url("","admin/Chan/latestposts").'">Latest Posts</a></li>'."\n";
                $write.='<li><a href="'.$k->url("","admin/Chan/latestpics").'">Latest Pictures</a></li>'."\n";
                $write.='<li><a href="'.$k->url("","admin/ACP/tickets").'">Tickets (';
                $write.='<? $temp=$c->getData("SELECT COUNT(ticketID) FROM ms_tickets ORDER BY time DESC");echo($temp[0]["COUNT(ticketID)"]); ?>)</a>'."\n";
            $write.='</ul></li>'."\n";
        $write.='<? } ?>'."\n";
    $write.='</ul>'."\n";

    //
    if((strpos($options,"l")===FALSE&&(($thread==0&&strpos($options,"t")!==FALSE)||$thread!=0))){
    $write.='<? if("'.$posterIP.'"==$_SERVER["REMOTE_ADDR"]||strpos("'.$options.'",",h")===FALSE||$a->check("chan.mod.hidden")){ ?>';
        $write.='<div class="postBox" id="postBox"><a name="postBox"></a>'."\n";
        $write.='<form action="/api.php?m=Chan&c=handlePost" method="post" id="postForm" enctype="multipart/form-data">'."\n";
        $write.='<div><label class="eldesc">Name/Mail</label>';
        if(strpos($options,"n")===FALSE)
            $write.='<input type="text" name="varname" id="varname" placeholder="name#tripcode#secure#add" />'."\n";
        $write.='<input type="text" name="varmail" id="varmail" placeholder="email#sage#noko" /></div>'."\n";
        $write.='<div><input type="text" name="vartitle" id="vartitle" placeholder="title" /><input type="submit" name="varsubmit" id="varsubmit" value="Post" /></div><span id="replyto"></span>'."\n";
            $write.='<? if($a->check("chan.mod.bbcodes"))$t->apiCall("getBBCodeBar",array("formid"=>"fulltext","level"=>"99"));'."\n";
            $write.='else                                $t->apiCall("getBBCodeBar",array("formid"=>"fulltext","level"=> "0")); ?>'."\n";
            $write.='<textarea name="vartext" id="fulltext"></textarea>'."\n";
        $write.='<div><label class="eldesc">File</label>   <input type="file" name="varfile" id="varfile" /></div>'."\n";
        $write.='<div><label class="eldesc">Options</label><input type="checkbox" name="varoptions[]" value="r" />Spoiler <input type="checkbox" name="varoptions[]" value="w" />NSFW </div>'."\n";
        $write.='<? if($a->check("chan.mod")){ ?>'."\n";
            $write.='<div><label class="eldesc">Mod Options</label>'."\n";
            $write.='<input type="checkbox" name="varoptions[]" value="m" />Modpost '."\n";
            $write.='<input type="checkbox" name="varoptions[]" value="h" />Hidden '."\n";
            if($thread==0){
                $write.='<br /><input type="checkbox" name="varoptions[]" value="l" />Locked '."\n";
                $write.='<input type="checkbox" name="varoptions[]" value="s" />Sticky '."\n";
                $write.='<input type="checkbox" name="varoptions[]" value="e" />Autosage '."\n";
            }
            $write.='</div>'."\n";
        $write.='<? } ?>'."\n";
        $write.='<div><label class="eldesc">Password</label><input type="password" name="varpassword" class="password" id="varpass"/></div>'."\n";
        $filetypes=explode(";",$board->filetypes);
        for($x=0;$x<count($filetypes);$x++){$filetypes[$x]=substr($filetypes[$x],strpos($filetypes[$x],"/")+1);}
        $filetypes=implode(", ",$filetypes);
        $write.='<div class="tac">By posting you agree to the <a href="'.$k->url('main','TAC').'">Terms And Conditions</a>.<br />Allowed Filetypes: '.$filetypes.' </div>';
        $write.='<input type="hidden" name="varboard" id="varboard" value="'.$board->boardID.'" />'."\n";
        $write.='<input type="hidden" name="varthread" id="varthread" value="'.$thread.'" /><div class="postResponse" id="postResponse"></div>'."\n";
        $write.='</form></div>'."\n";
    $write.='<? } ?>'."\n";
    }

    $write.='<form action="/api.php?m=Chan&c=handleBoardForm" method="post" id="boardForm">'."\n";
    return $write;
}

function recursiveBoardPrint($PID,$boards,$step=0){
    $step++;global $k;
    if($step>10)return;
    for($i=0;$i<count($boards);$i++){
        if($boards[$i]->PID==$PID){
            $write.='<li><a href="'.$k->url("chan",$boards[$i]->folder).'" title="'.$boards[$i]->title.'" >'.$boards[$i]->folder.'</a><ul>';
            $write.=recursiveBoardPrint($boards[$i]->boardID,$boards,$step);
            $write.='</ul></li>';
        }
    }
    return $write;
}

?>
