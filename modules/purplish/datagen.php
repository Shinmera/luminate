<?
class DataGenerator{

    function mergeThread($threadID,$board,$newID){
        global $c;
        if(!class_exists("BoardGenerator"))include(TROOT.'modules/chan/boardgen.php');
        if(!class_exists("ThreadGenerator"))include(TROOT.'modules/chan/threadgen.php');
        if(!class_exists("PostGenerator"))include(TROOT.'modules/chan/postgen.php');

        $c->query('UPDATE ch_posts SET PID=? WHERE (postID=? OR PID=?) AND BID=?',array($threadID,$threadID,$board));
        
        ThreadGenerator::generateThread($newID,$board,true);
        BoardGenerator::generateBoard($board);
    }
    
    function moveThread($threadID,$oldboard,$newboard){
        global $c,$a,$k;
        if(!class_exists("BoardGenerator"))include(TROOT.'modules/chan/boardgen.php');
        if(!class_exists("ThreadGenerator"))include(TROOT.'modules/chan/threadgen.php');
        if(!class_exists("PostGenerator"))include(TROOT.'modules/chan/postgen.php');
        if($oldboard==$newboard)return $threadID;
        
        $post = DataModel::getData('ch_posts',"SELECT postID,PID,BID,file,subject FROM ch_posts WHERE (PID=? OR postID=?) AND BID=? ORDER BY postID ASC",array($threadID,$threadID,$oldboard));
        if(count($post)==0)throw new Exception("No such thread.");
        $oboard=ChanDataBoard::loadFromDB("SELECT folder FROM ch_boards WHERE boardID=?",array($oldboard));
        $nboard=ChanDataBoard::loadFromDB("SELECT folder FROM ch_boards WHERE boardID=?",array($newboard));
        if(count($nboard)==0||count($oboard)==0)throw new Exception("No such board.");
        $oldboard=$post[0]->BID;

        //UPDATE DATA
        $lastBoardID=$c->getData("SELECT postID FROM ch_posts WHERE BID=? ORDER BY postID DESC LIMIT 1;",array($newboard));
        $lastBoardID=$lastBoardID[0]['postID'];
        global $threadOldBoard,   $threadNewBoard,          $threadOldID,          $threadNewID,               $idmap;
        $threadOldBoard=$oldboard;$threadNewBoard=$newboard;$threadOldID=$threadID;$threadNewID=$lastBoardID+1;$idmap=array();
        
        for($i=0;$i<count($post);$i++){
            $idmap[$post[$i]->postID]=($lastBoardID+$i+1);
            //FIX DIRECT QUOTES
            $post[$i]->subject=str_ireplace("&lt;","<",$post[$i]->subject);
            $post[$i]->subject=str_ireplace("&gt;",">",$post[$i]->subject);
            $post[$i]->subject=str_ireplace("&#36;","$",$post[$i]->subject);
            $post[$i]->subject=str_ireplace("&lsquo;","'",$post[$i]->subject);
            $post[$i]->subject=str_ireplace("&quot;",'"',$post[$i]->subject);
            $post[$i]->subject=preg_replace_callback('`>>([0-9]+)`is',array(&$this, 'fixBrokenQuote'),$post[$i]->subject);

            $post[$i]->changePrimaryKey(($lastBoardID+$i+1),$newboard);
            if($post[$i]->PID!=0)$post[$i]->PID=($lastBoardID+1);
            $post[$i]->saveToDB();
            //MOVE FILES
            if($post[$i]->file!=""){
                if($i==0){
                    copy(ROOT.DATAPATH.'chan/'.$oboard[0]->folder.'/files/'.$post[$i]->file,
                           ROOT.DATAPATH.'chan/'.$nboard[0]->folder.'/files/'.$post[$i]->file);
                    copy(ROOT.DATAPATH.'chan/'.$oboard[0]->folder.'/thumbs/'.$post[$i]->file,
                           ROOT.DATAPATH.'chan/'.$nboard[0]->folder.'/thumbs/'.$post[$i]->file);
                }else{
                    rename(ROOT.DATAPATH.'chan/'.$oboard[0]->folder.'/files/'.$post[$i]->file,
                           ROOT.DATAPATH.'chan/'.$nboard[0]->folder.'/files/'.$post[$i]->file);
                    rename(ROOT.DATAPATH.'chan/'.$oboard[0]->folder.'/thumbs/'.$post[$i]->file,
                           ROOT.DATAPATH.'chan/'.$nboard[0]->folder.'/thumbs/'.$post[$i]->file);
                }
            }
            unlink(ROOT.DATAPATH.'chan/'.$oboard[0]->folder.'/posts/'.$post[$i]->postID.".php");
        }
        
        //ADD POST REFER
        $post[0]->postID = $threadID;
        $post[0]->BID = $oldboard;
        $post[0]->changePrimaryKey($threadID,$oldboard);
        $post[0]->options = "";
        $post[0]->inserted = false;
        $post[0]->loaded = false;
        $post[0]->subject = 'This thread has been moved to >>'.$nboard[0]->folder.'/'.($lastBoardID+1);
        $post[0]->saveToDB();

        //GENERATE NEW ONES
        ThreadGenerator::generateThread(($lastBoardID+1),$newboard,true);
        ThreadGenerator::generateThread($threadID,$oldboard,true);
        BoardGenerator::generateBoard($newboard);
        BoardGenerator::generateBoard($oldboard);
        return $post[0]->postID;
    }

    function fixBrokenQuote($matches){
        global $threadOldBoard,$threadNewBoard,$idmap,$threadOldID,$threadNewID;
        if(array_key_exists($matches[1],$idmap))return '>>'.$idmap[$matches[1]];
        else                                    return $matches[0];
    }

    function deleteByIP($ip){
        if(!class_exists("BoardGenerator"))include(TROOT.'modules/chan/boardgen.php');
        if(!class_exists("ThreadGenerator"))include(TROOT.'modules/chan/threadgen.php');
        $post = DataModel::getData('ch_posts',"SELECT postID,PID,BID,file FROM ch_posts WHERE ip=?",array($ip));
        $board= DataModel::getData('ch_boards',"SELECT folder FROM ch_boards WHERE boardID=?",array($post[0]->BID));

        //FILE
        $data=array($ip);
        $query="ip=?";
        $boardsgen=array();
        for($i=0;$i<count($post);$i++){
            if($post[$i]->PID==0){
                $query.=" OR PID=?";
                $data[]=$post[$i]->postID;
                if(!in_array($post[$i]->BID,$boardsgen))$boardsgen[]=$post[$i]->BID;
                $this->cleanThread($post[$i]->postID);
            }else{
                ThreadGenerator::generateThread($post[$i]->PID,$post[$i]->BID);
            }
            $this->deleteTraces($board->folder,$post[$i]->postID,$post[$i]->file);
        }
        $c->query("UPDATE ch_posts SET `options`=CONCAT(`options`,'d') WHERE ".$query,$data);
        
        //REGEN BOARDS
        for($i=0;$i<count($boardsgen);$i++){
            BoardGenerator::generateBoard($boardsgen[$i]);
        }
    }

    function deletePost($postID,$board,$generate=true,$imageonly=false){
        global $k,$a,$c;
        if(!class_exists("ThreadGenerator"))include(TROOT.'modules/chan/threadgen.php');
        if(!class_exists("BoardGenerator"))include(TROOT.'modules/chan/boardgen.php');
        
        $post = DataModel::getData('ch_posts',"SELECT postID,PID,BID,file FROM ch_posts WHERE postID=? AND BID=? AND options NOT REGEXP ? LIMIT 1",array($postID,$board,'d'));
        if(count($post)==0)throw new Exception("No such post.");
        if(!$a->check("chan.mod.delete")&&$_POST['password']!=$post->password)throw new Exception("No Access.");
        if($post[0]->PID==0)$thread=$postID;
        else                $thread=$post[0]->PID;

        //DATABASE
        if(!$imageonly)$c->query("UPDATE ch_posts SET `options`=CONCAT(`options`,'d') WHERE (postID=? OR PID=?) AND BID=?",array($postID,$postID,$board));
        
        //FILE
        $boardo = ChanDataBoard::loadFromDB("SELECT folder FROM ch_boards WHERE boardID=?",array($board));
        if(!$imageonly){
            $this->deleteTraces($boardo[0]->folder,$postID,$post[0]->file);
            if($post[0]->PID==0)$this->cleanThread($postID);
        }else{
            $this->deleteTraces($boardo[0]->folder,$postID,$post[0]->file,false,false);
        }

        if($generate){
            if($post->PID!=0)ThreadGenerator::generateThread($thread,$board);
            BoardGenerator::generateBoard($board);
        }
    }

    function submitPost(){
        global $k,$p,$c,$a;
        if(!class_exists("BoardGenerator"))include('boardgen.php');
        if(!class_exists("ThreadGenerator"))include('threadgen.php');
        if(!class_exists("PostGenerator"))include('postgen.php');
        if(!$k->updateTimestamp('chan_post',$c->o['chan_posttimeout']))
            throw new Exception("Please wait ".$c->o['chan_posttimeout']." seconds between posts.");

        //CHECK FOR POST EMPTINESS
        $thread=(int)$_POST['varthread'];
        $file=$_FILES['varfile'];
        $board=(int)$_POST['varboard'];
        $name=trim($_POST['varname']);
        $mail=trim($_POST['varmail']);
        $password=trim($_POST['varpassword']);
        $options=$_POST['varoptions'];
        $filename='';$dim='';
        if(!is_array($options))$options=array();
        $text=trim($_POST['vartext']);
        
        if($file['tmp_name']==""&&trim(str_replace("\n",'',$text))=="")throw new Exception("File or text required.");
        if($c->o['chan_postlength']>0&&strlen($text)>$c->o['chan_postlength'])throw new Exception("Sorry, your text is too long. Please shorten it to ".$c->o['chan_postlength']." characters.");
        if($file['error'] !== UPLOAD_ERR_OK && $file['error'] !== UPLOAD_ERR_NO_FILE && $file['error'] != "")throw new Exception("File upload failed. (ERR".$file['error'].")");

        //CHECK BOARD
        $board = DataModel::getData('ch_boards',"SELECT boardID,folder,maxfilesize,filetypes,options,postlimit FROM ch_boards WHERE boardID=?", array($board));
        if($board==null)throw new Exception("No such board. (".board.")");
        if(!$a->check("chan.mod")&&(strpos($board->options,"l")!==FALSE||
                                    strpos($board->options,"m")!==FALSE||
                                    strpos($board->options,"h")!==FALSE))throw new Exception("You are not authorized to post.");
        if($file['tmp_name']=="" && strpos($board->options,"f")!==FALSE) throw new Exception("You need to post a file.");
        
        //SAVE FIELDS
        setcookie("chan_post_pw",$c->enparse($password),time()+60*60*$c->o['cookie_life_h'],'/');
        if(strpos($board->options,"n")===FALSE){;
            setcookie("chan_post_name",$c->enparse($name),time()+60*60*$c->o['cookie_life_h'],'/');
            setcookie("chan_post_mail",$c->enparse($mail),time()+60*60*$c->o['cookie_life_h'],'/');
        }

        //CHECK THREAD
        if(!is_numeric($thread)||$thread=="")$thread=0;
        if($thread!=0){
            $threadp = DataModel::getData('ch_posts',"SELECT BID,options FROM ch_posts WHERE postID=? AND PID=0 AND BID=? ORDER BY postID DESC LIMIT 1", array($thread,$board->boardID));
            if($threadp==null)throw new Exception("No such thread.");
            if($threadp->BID!=$board->boardID)throw new Exception("Invalid board. (".$board->boardID."/".$threadp->BID.")");
            if(!$a->check("chan.mod")&&(strpos($threadp->options,"l")!==FALSE||
                                        strpos($threadp->options,"h")!==FALSE||
                                        strpos($threadp->options,"d")!==FALSE))throw new Exception("No Access.");
        }else if(strpos($board->options,"t")===FALSE&&!$a->check("chan.mod")){throw new Exception("No Access.");
        }else if($file['tmp_name']=="")throw new Exception("File required.");
        
        //CHECK AKISMET
        if(DataModel::getData('','SELECT COUNT(ip) FROM ch_posts WHERE IP LIKE ?',array($_SERVER['REMOTE_ADDR']))==null){
            require_once(TROOT.'callables/akismet.php');
            $akismet = new Akismet(HOST ,$c->o['akismet_key']);
            $akismet->setCommentAuthor($name);
            $akismet->setCommentAuthorEmail($mail);
            $akismet->setCommentContent($text);
            $akismet->setPermalink(HOST);
            if($akismet->isCommentSpam())throw new Exception("Akismet spam detection triggered.");
        }

        //CHECK FILE
        if($file['tmp_name']!=""){
            if($file['size']>($board->maxfilesize*1024))throw new Exception("File too big. (Max: ".$k->displayFilesize(($board->maxfilesize*1024))." )");
            if(!in_array($file['type'],explode(";",$board->filetypes)))throw new Exception("Invalid filetype: ".$file['type']);

            //UPLOAD FILE
            $fileext=substr($file['name'],  strrpos($file['name'],'.')+1);
            $filename=time().mt_rand(10000,99999).'.'.$fileext;
            if(!move_uploaded_file($file['tmp_name'], ROOT.DATAPATH.'chan/'.$board->folder.'/files/'.$filename))throw new Exception("File upload failed.");

            //GENERATE THUMB
            if(in_array($file['type'],array("image/png","image/jpeg","image/gif","image/tiff","image/bmp","image/bitmap"))){
                if(in_array("w",$options)){//nsfW
                    copy(ROOT.IMAGEPATH.'chan/previews/nsfw.png',ROOT.DATAPATH.'chan/'.$board->folder.'/thumbs/'.$filename);
                }else if(in_array("r",$options)){//spoileR
                    copy(ROOT.IMAGEPATH.'chan/previews/spoiler.png',ROOT.DATAPATH.'chan/'.$board->folder.'/thumbs/'.$filename);
                }else{
                    if($thread!=0)$this->createThumbnail($board->folder, $filename,$c->o['chan_thumbsize']);
                    else          $this->createThumbnail($board->folder, $filename,$c->o['chan_opthumbsize']);
                }
                $dim=getimagesize(ROOT.DATAPATH.'chan/'.$board->folder.'/files/'.$filename);
                $dim=$dim[0]."x".$dim[1];
            }else{
                $filetype = ChanDataFileType::loadFromDB("SELECT preview FROM ch_filetypes WHERE mime LIKE ?",array($file['type']));
                copy(ROOT.IMAGEPATH.'chan/previews/'.$filetype[0]->preview,ROOT.DATAPATH.'chan/'.$board->folder.'/thumbs/'.$filename);
                $dim="NaP";
            }
        }

        //GATHER OTHER DATA
        $name=explode('#',$name);
        $mail=explode('#',$mail);
        if(count($name)>1)$trip=$this->calculateTrip(implode("#",array_slice($name, 1)));else $trip='';
        if(count($name)==0||strpos($board->options,"n")!==FALSE)$name[0]="Anonymous";
        if(!$a->check("chan.mod")||!is_array($options))$options=array();
        if($a->check("chan.mod.*"))$options[]="p";
        $banned=$k->checkBanned($_SERVER['REMOTE_ADDR']);
        if($banned!==FALSE){
            $uID=array_search($banned[1],$c->msBIP);
            if($c->msBReason[$uID]=="/mute")$options[]="h";
            else throw new Exception("You are B&.");
        }

        //CREATE POST INSTANCE
        $post = new ChanDataPost(NULL,$board->boardID,$thread,$name[0],$mail[0],$trip,$_POST['vartitle'],
                                 $text,time(),time(),$password,$filename,$file['name'],
                                 $file['size'],$dim,$_SERVER['REMOTE_ADDR'],','.implode(",",$options));
        $post->saveToDB();

        //UPDATE THREAD
        if($thread!=0){
            $tpost = DataModel::getData('ch_posts',"SELECT postID,BID,options,bumptime FROM ch_posts WHERE postID=? AND BID=? ORDER BY postID DESC LIMIT 1;", array($thread,$board->boardID));
            if(strpos($tpost->options,"e")===FALSE&&!in_array("sage",$mail))$tpost[0]->bumptime=time();
            $posts = $c->getData("SELECT COUNT(postID) FROM ch_posts WHERE PID=? AND BID=? AND options NOT REGEXP ?",array($thread,$board->boardID,'d'));
            if($posts[0]['COUNT(postID)']>$board->postlimit)$tpost[0]->options.="e";
            $tpost[0]->saveToDB();
        }else{
            $thread=$post->postID;
        }
        $post->subject = $p->enparse($post->subject);
        $post->title = $p->enparse($post->title);
        $post->name = $p->enparse($post->name);
        $post->trip = $p->enparse($post->trip);
        $post->mail = $p->enparse($post->mail);
        $post->fileOrig = $p->enparse($post->fileOrig);
        PostGenerator::generatePostFromObject($post);
        ThreadGenerator::generateThread($thread,$board->boardID);
        BoardGenerator::generateBoard($board->boardID);

        if(in_array("noko",$mail)){
            if($thread!=0)header('Location: '.$k->url("chan",$board->folder.'/threads/'.$thread.'.php#'.$post->postID));
            else          header('Location: '.$k->url("chan",$board->folder.'/threads/'.$post->postID.'.php'));
        }else{
                          header('Location: '.$k->url("chan",$board->folder.'/'));
        }
    }

    function calculateTrip($trip) {
        global $c,$k,$p;
        $trip = $p->convertCharset($trip);
        $trip = mb_convert_encoding($trip,'SJIS','UTF-8');
        $trip = $c->enparse($trip);
        $predefined=$k->stringToVarKey($c->o['chan_trips'],"\n");
        if(array_key_exists('#'.$trip, $predefined))return '!'.$predefined['#'.$trip];
        $trip=explode("#",$trip);
        
        if ($trip[0]!='') {
            if(array_key_exists('#'.$trip[0], $predefined)){
                $tripcode=$predefined['#'.$trip[0]];
            }else{
                $trip[0] = strtr($trip[0], "&amp;", "&");
                $trip[0] = strtr($trip[0], "&#44;", ", ");
                $salt = substr($trip[0]."H.", 1, 2);
                $salt = preg_replace("/[^\.-z]/", ".", $salt);
                $salt = strtr($salt, ":;<=>?@[\\]^_`", "ABCDEFGabcdef");
                $tripcode = substr(crypt($trip[0], $salt), -10);
            }
        }

        if ($trip[1]!='') {
            if ($trip[0] != '')$tripcode.='!';
            if(array_key_exists('##'.$trip[1], $predefined)){
                $tripcode=$predefined['##'.$trip[1]];
            }else{
                $secure_tripcode = md5($trip[1] . SECRET_KEY);
                $secure_tripcode = base64_encode($secure_tripcode);
                $secure_tripcode = str_rot13($secure_tripcode);
                $secure_tripcode = substr($secure_tripcode, 2, 10);
                $tripcode.='!'.$secure_tripcode;
            }
        }

        if($trip[2]!=''){
            if(array_key_exists('###'.$trip[2], $predefined)){
                $tripcode=$predefined['###'.$trip[2]];
            }else{
                if($trip[0]!= ''||$trip[1]!= '')$tripcode.='!';
                $tripcode.='!!'.$trip[2];
            }
        }

        if($tripcode!='')$tripcode='!'.$tripcode;

        return $tripcode;
    }


    function createThumbnail($board,$file,$thumbsize=-1){
        global $k,$c;
        if($thumbsize==-1)$thumbsize=$c->o['chan_thumbsize'];
        if($thumbsize<=1)$thumbsize=200;
        return $k->createThumbnail(ROOT.DATAPATH.'chan/'.$board.'/files/'.$file,
                                   ROOT.DATAPATH.'chan/'.$board.'/thumbs/'.$file,
                                   $thumbsize,$thumbsize,false,true);
    }

    function parseQuotes($s,$boardid,$boardname,$threadid){
        global $threadBoardID,$threadBoardName,$threadThreadID;
        $threadBoardID=$boardid;
        $threadBoardName=$boardname;
        $threadThreadID=$threadid;

        $s=str_ireplace("<br />","\n",$s);
        $s=str_ireplace("<br/>","\n",$s);
        $s=str_ireplace("<br>","\n",$s);
        //DIRECT QUOTES
        $s = preg_replace_callback('`>{2,4}/?((\w+/|[0-9])([0-9]+|([a-zA-Z]+)(/[0-9]*)?)?)`s', array(&$this,'parseReference'), $s);
        //LINE QUOTES
        $s = preg_replace('/^(&gt;[^>](.*))\n/m', '<span class="quoteLine">\\1</span>'."\n", $s);
        $s=str_ireplace("\n","<br />",$s);
        return $s;
    }
    
    function parseReference($matches){
        global $threadBoardName,$threadThreadID;
        $sites=array('arch'=>'http://stevenarch.tymoon.eu/',
                     '4chan'=>'http://4chan.org/');
        $matches[0]=str_replace('>','&gt;',$matches[0]);
        $matches[2]=str_replace('/','',$matches[2]);
        
        if(is_numeric($matches[1])){
            //Same board reference
            $id=$matches[1];
            $board=$threadBoardName;
            $href=PROOT.$threadBoardName.'/threads/'.$threadThreadID.'.php#'.$id;
        }else if($matches[3]==''){
            //Board reference
            $id='';
            $board=$matches[2];
            $href=PROOT.$matches[2];
        }else if(is_numeric($matches[3])){
            //Inter board reference
            $post = DataModel::getData('','SELECT PID FROM ch_posts WHERE postID=? AND BID=(SELECT boardID FROM ch_boards WHERE folder LIKE ?)',array($matches[3],$matches[2]));
            if($post==null)return $matches[0];
            if($post->PID==0)$post->PID=$matches[3];
            
            $id=$matches[3];
            $board=$matches[2];
            $href=PROOT.$matches[2].'/threads/'.$post->PID.'.php#'.$matches[3];
        }else if($matches[5]==''){
            //Different site, board reference
            if(!array_key_exists($matches[2],$sites))return $matches[0];
            $id='';$board='';
            $href=$sites[$matches[2]].$matches[4];
        }else{
            //Different site, inter board reference
            if(!array_key_exists($matches[2],$sites))return $matches[0];
            $id='';$board='';
            $href=$sites[$matches[2]].$matches[4].'/'.$matches[5];
        }
        return '<a class="directQuote" id="'.$id.'" board="'.$board.'" href="'.$href.'">'.$matches[0].'</a>';
    }

    function cleanBoard($boardID){
        global $c;
        $board= ChanDataBoard::loadFromDB("SELECT folder FROM ch_boards WHERE boardID=?",array($boardID));
        echo("Database...<br />");
        $c->query("DELETE FROM ch_posts WHERE BID=? AND options REGEXP ?",array($boardID,'d'));
        
        echo("Files: <br />");$temp=glob(ROOT.DATAPATH.'chan/'.$board->folder.'/files/_*.php');
        if(is_array($temp)&&count($temp)>0)
            foreach($temp as $fn)  {echo('&nbsp; &nbsp; Deleting: '.$fn.'<br />');ob_flush();unlink($fn);}
        else{echo('&nbsp; &nbsp; Clean.<br />');ob_flush();}
        flush();
        echo("Thumbs: <br />");$temp=glob(ROOT.DATAPATH.'chan/'.$board->folder.'/thumbs/_*.php');
        if(is_array($temp)&&count($temp)>0)
            foreach($temp as $fn) {echo('&nbsp; &nbsp; Deleting: '.$fn.'<br />');ob_flush();unlink($fn);}
        else{echo('&nbsp; &nbsp; Clean.<br />');ob_flush();}
        flush();
        echo("Posts: <br />");$temp=glob(ROOT.DATAPATH.'chan/'.$board->folder.'/posts/_*.php');
        if(is_array($temp)&&count($temp)>0)
            foreach($temp as $fn)  {echo('&nbsp; &nbsp; Deleting: '.$fn.'<br />');ob_flush();unlink($fn);}
        else{echo('&nbsp; &nbsp; Clean.<br />');ob_flush();}
        flush();
        echo("Threads: <br />");$temp=glob(ROOT.DATAPATH.'chan/'.$board->folder.'/threads/_*.php');
        if(is_array($temp)&&count($temp)>0)
            foreach($temp as $fn){echo('&nbsp; &nbsp; Deleting: '.$fn.'<br />');ob_flush();unlink($fn);}
        else{echo('&nbsp; &nbsp; Clean.<br />');ob_flush();}
        flush();
    }

    function deleteTraces($board,$postid,$file,$thread=false,$post=true){
        if($post)
            @ rename(ROOT.DATAPATH.'chan/'.$board.'/posts/'.$postid.".php",
                   ROOT.DATAPATH.'chan/'.$board.'/posts/_'.$postid.".php");
        if($file!==""){
            @ rename(ROOT.DATAPATH.'chan/'.$board.'/files/'.$file,
                    ROOT.DATAPATH.'chan/'.$board.'/files/_'.$file);
            @ rename(ROOT.DATAPATH.'chan/'.$board.'/thumbs/'.$file,
                    ROOT.DATAPATH.'chan/'.$board.'/thumbs/_'.$file);
        }
        if($thread){
            @ rename(ROOT.DATAPATH.'chan/'.$board.'/threads/'.$postid.".php",
                   ROOT.DATAPATH.'chan/'.$board.'/threads/_'.$postid.".php");
        }
    }

    function cleanThread($threadID){
        $posts = DataModel::getData('ch_posts',"SELECT postID,BID,file FROM ch_posts WHERE PID=? OR postID=? ORDER BY postID DESC",array($threadID,$threadID));
        if(count($posts)==0)return false;
        $board = ChanDataBoard::loadFromDB("SELECT folder FROM ch_boards WHERE boardID=?",array($posts[0]->BID));
        if(count($board)==0)return false;
        for($i=1;$i<count($posts);$i++){
            $this->deleteTraces($board->folder,$posts[$i]->postID,$posts[$i]->file);
        }
        $this->deleteTraces($board->folder,$posts[0]->postID,$posts[0]->file,true);
        return true;
    }
}
?>