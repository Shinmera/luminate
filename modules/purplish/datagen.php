<?
class DataGenerator{

    function mergeThread($threadID,$board,$newID){
        global $c,$l;
        if(!class_exists("BoardGenerator"))include('boardgen.php');
        if(!class_exists("ThreadGenerator"))include('threadgen.php');
        if(!class_exists("PostGenerator"))include('postgen.php');

        $c->query('UPDATE ch_posts SET PID=? WHERE (postID=? OR PID=?) AND BID=?',array($threadID,$threadID,$board));
        
        ThreadGenerator::generateThread($newID,$board,true);
        BoardGenerator::generateBoard($board);
        $l->triggerHook('mergeThread','Purplish',array($threadID,$board,$newID));
    }
    
    function moveThread($threadID,$old,$new){
        global $c,$l;
        if(!class_exists("BoardGenerator"))include('boardgen.php');
        if(!class_exists("ThreadGenerator"))include('threadgen.php');
        if(!class_exists("PostGenerator"))include('postgen.php');
        if($old==$new)return $threadID;
        
        $old = DataModel::getData('','SELECT * FROM ch_boards WHERE boardID=?',array($old));
        $new = DataModel::getData('','SELECT * FROM ch_boards WHERE boardID=?',array($new));
        if($old==null||$new==null)throw new Exception('Boards not found.');
        
        //Clone OP
        $post = DataModel::getData('ch_posts','SELECT * FROM ch_posts WHERE BID=? AND postID=?',array($old->boardID,$threadID));
        if($post==null)throw new Exception('No such thread.');
        if($post->PID!=0)throw new Exception('This isn\'t a thread.');
        $post->postID=null;
        $post->BID=$new->boardID;
        $post->insertData();
        $newID=$c->insertID();
        copy(ROOT.DATAPATH.'chan/'.$old->folder.'/files/'.$post->file, ROOT.DATAPATH.'chan/'.$new->folder.'/files/'.$post->file);
        copy(ROOT.DATAPATH.'chan/'.$old->folder.'/thumbs/'.$post->file,ROOT.DATAPATH.'chan/'.$new->folder.'/thumbs/'.$post->file);
        
        //Modify old post and save data.
        $post->postID = $threadID;
        $post->BID = $old->boardID;
        $post->options = ",l,z";
        $post->subject = 'This thread has been moved to >>'.$new->folder.'/'.$newID;
        $post->saveData();
        
        //Update posts
        $posts = DataModel::getData('ch_posts','SELECT * FROM ch_posts WHERE BID=? AND PID=? ORDER BY postID ASC',array($old->boardID,$threadID));
        Toolkit::assureArray($posts);
        global $postIDs;$postIDs=array($threadID=>$newID);
        foreach($posts as $post){
            $oldID=$post->postID;
            $post->postID=null;
            $post->BID=$new->boardID;
            $post->PID=$newID;
            $post->subject=  preg_replace_callback('`(&gt;){2,4}([0-9]+)`is', array(&$this,'moveThreadCallback'), $post->subject);
            $post->insertData();
            $postIDs[$oldID]=$c->insertID();
            
            $post->postID=$oldID;
            $post->BID=$old->boardID;
            $post->deleteData();
            
            if($post->file!=''){
                rename(ROOT.DATAPATH.'chan/'.$old->folder.'/files/'.$post->file, ROOT.DATAPATH.'chan/'.$new->folder.'/files/'.$post->file);
                rename(ROOT.DATAPATH.'chan/'.$old->folder.'/thumbs/'.$post->file,ROOT.DATAPATH.'chan/'.$new->folder.'/thumbs/'.$post->file);
            }
            unlink(ROOT.DATAPATH.'chan/'.$old->folder.'/posts/'.$oldID.'.php');
        }

        //Regenerate
        ThreadGenerator::generateThread($newID,$new->boardID,true);
        ThreadGenerator::generateThread($threadID,$old->boardID,true);
        BoardGenerator::generateBoardFromObject($old);
        BoardGenerator::generateBoardFromObject($new);
        $l->triggerHook('moveThread','Purplish',array($threadID,$old->boardID,$new->boardID));
        return $newID;
    }
    function moveThreadCallback($matches){
        global $postIDs;
        if(array_key_exists($matches[2], $postIDs))return '&gt;&gt;'.$postIDs[$matches[2]];
        else return $matches[0];
    }
    

    function deleteByIP($ip){
        global $c,$l;
        if(!class_exists("BoardGenerator"))include('boardgen.php');
        if(!class_exists("ThreadGenerator"))include('threadgen.php');
        $posts = DataModel::getData('ch_posts','SELECT postID,PID,BID,file,folder 
                                               FROM ch_posts LEFT JOIN ch_boards ON BID=boardID
                                               WHERE ip=?',array($ip));
        if($posts==null)throw new Exception('No posts recorded for this IP.');
        Toolkit::assureArray($posts);

        //Create query
        $data=array($ip);
        $query="ip=?";
        $boardsgen=array();
        foreach($posts as $post){
            if($post->PID==0){
                $query.=" OR PID=?";
                $data[]=$post->postID;
                $this->cleanThread($post->postID,$post->folder);
            }
            $this->deleteTraces($post->folder,$post->postID,$post->file);
        }
        $c->query("UPDATE ch_posts SET `options`=CONCAT(`options`,'d') WHERE ".$query,$data);
        
        //Regenerate
        foreach($posts as $post){
            if($post->PID!=0){
                ThreadGenerator::generateThread($post->PID,$post->BID);
            }
            if(!in_array($post->BID,$boardsgen)){
                $boardsgen[]=$post->BID;
                BoardGenerator::generateBoard($post->BID);
            }
        }
        
        $l->triggerHook('purge','Purplish',array($ip));
    }

    function deletePost($postID,$board,$generate=true,$imageonly=false,$ignoreperm=false){
        global $l,$a,$c;
        if(!class_exists("ThreadGenerator"))include('threadgen.php');
        if(!class_exists("BoardGenerator"))include('boardgen.php');
        
        $post = DataModel::getData('ch_posts',"SELECT postID,PID,BID,file,password FROM ch_posts WHERE postID=? AND BID=? AND options NOT LIKE ? LIMIT 1",
                                                array($postID,$board,'%d%'));
        if($post == null)throw new Exception("No such post.");
        if(!$a->check("chan.mod.delete") && $_POST['password']!=$post->password && !$ignoreperm) throw new Exception("No Access (password mismatch?).");
        if($post->PID==0)$thread=$postID;
        else             $thread=$post->PID;

        //DATABASE
        if(!$imageonly)$c->query("UPDATE ch_posts SET `options`=CONCAT(`options`,'d') WHERE (postID=? OR PID=?) AND BID=?",array($postID,$postID,$board));
        
        //FILE
        $boardo = DataModel::getData('ch_boards',"SELECT folder FROM ch_boards WHERE boardID=?",array($board));
        if(!$imageonly){
            if($post->PID==0)$this->cleanThread($postID,$boardo->folder);
            else             $this->deleteTraces($boardo->folder,$postID,$post->file);
        }else{
            $this->deleteTraces($boardo->folder,$postID,$post->file,false,false);
        }

        if($generate){
            if($post->PID!=0)ThreadGenerator::generateThread($thread,$board);
            BoardGenerator::generateBoard($board);
        }
        
        $l->triggerHook('delete','Purplish',array($postID,$board,$imageonly));
    }

    function submitPost(){
        global $k,$l,$c,$a;
        if(!class_exists("BoardGenerator"))include('boardgen.php');
        if(!class_exists("ThreadGenerator"))include('threadgen.php');
        if(!class_exists("PostGenerator"))include('postgen.php');
        if(!$k->updateTimestamp('chan_post',$c->o['chan_posttimeout']))
            throw new Exception("Please wait ".$c->o['chan_posttimeout']." seconds between posts.");

        $thread=(int)$_POST['varthread'];
        $file=$_FILES['varfile'];
        $boardid=(int)$_POST['varboard'];
        $name=trim($_POST['varname']);
        $mail=trim($_POST['varmail']);
        $password=trim($_POST['varpassword']);
        $options=$_POST['varoptions'];
        $filename='';$dim='';
        if(!is_array($options))$options=array();
        $text=trim($_POST['vartext']);
        
        //CHECK FOR POST EMPTINESS
        if($_POST['email']!="")throw new Exception("Fields are filled incorrectly.");
        if($file['tmp_name']==""&&trim(str_replace("\n",'',$text))=="")throw new Exception("File or text required.");
        if($c->o['chan_postlength']>0&&strlen($text)>$c->o['chan_postlength'])throw new Exception("Sorry, your text is too long. Please shorten it to ".$c->o['chan_postlength']." characters.");
        if($file['error'] !== UPLOAD_ERR_OK && $file['error'] !== UPLOAD_ERR_NO_FILE && $file['error'] != "")throw new Exception("File upload failed. (ERR".$file['error'].")");

        //CHECK BOARD
        $board = DataModel::getData('ch_boards',"SELECT boardID,folder,maxfilesize,filetypes,options,postlimit FROM ch_boards WHERE boardID=?", array($boardid));
        if($board==null)throw new Exception("No such board. (".board.")");
        if(!$a->check("chan.mod")&&(strpos($board->options,"l")!==FALSE||
                                    strpos($board->options,"m")!==FALSE||
                                    strpos($board->options,"h")!==FALSE))throw new Exception("You are not authorized to post.");
        if($file['tmp_name']=="" && strpos($board->options,"f")!==FALSE) throw new Exception("You need to post a file.");
        
        //SAVE FIELDS
        setcookie("chan2_post_pw",$c->enparse($password),time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
        if(strpos($board->options,"n")===FALSE){;
            setcookie("chan2_post_name",$c->enparse($name),time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
            setcookie("chan2_post_mail",$c->enparse($mail),time()+60*60*$c->o['cookie_life_h'],'/','.'.HOST);
        }

        //CHECK THREAD
        if(!is_numeric($thread)||$thread=="")$thread=0;
        if($thread!=0){
            $threadp = DataModel::getData('ch_posts',"SELECT BID,options FROM ch_posts WHERE postID=? AND PID=0 AND BID=? LIMIT 1", array($thread,$board->boardID));
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
        $banned=DataModel::getData('','SELECT mute FROM ch_bans WHERE ip LIKE ? ORDER BY mute DESC LIMIT 1',array($_SERVER['REMOTE_ADDR']));
        if($banned!=null){
            if($banned->mute=='1')$options[]="h";
            else throw new Exception("You are B&.");
        }

        //CREATE POST INSTANCE
        $post = DataModel::getHull('ch_posts');
        $post->BID=$board->boardID;
        $post->PID=$thread;
        $post->name=$name[0];
        $post->mail=$mail[0];
        $post->trip=$trip;
        $post->title=$_POST['vartitle'];
        $post->subject=$text;
        $post->time=time();
        $post->bumptime=time();
        $post->password=$password;
        $post->file=$filename;
        $post->fileorig=$file['name'];
        $post->filesize=$file['size'];
        $post->filedim =$dim;
        $post->ip=$_SERVER['REMOTE_ADDR'];
        $post->options=implode(',',$options);
        $post->insertData();
        $post->postID=$c->insertID();

        //UPDATE THREAD
        if($thread!=0){
            $tpost = DataModel::getData('ch_posts','SELECT postID,BID,options,bumptime FROM ch_posts WHERE postID=? AND BID=? AND PID=0 LIMIT 1', array($thread,$board->boardID));
            if(strpos($tpost->options,'e')===FALSE&&!in_array("sage",$mail)){
                $posts = $c->getData('SELECT COUNT(postID) FROM ch_posts WHERE PID=? AND BID=? AND options NOT LIKE ?',array($thread,$board->boardID,'%d%'));
                if($posts[0]['COUNT(postID)']>$board->postlimit)$tpost->options.='e';
                $tpost->bumptime=time();
                $tpost->saveData();
            }
        }else{
            $thread=$post->postID;
        }
        $post->subject = $c->enparse($post->subject,true);
        $post->title = $c->enparse($post->title,true);
        $post->name = $c->enparse($post->name,true);
        $post->trip = $c->enparse($post->trip,true);
        $post->mail = $c->enparse($post->mail,true);
        $post->fileOrig = $c->enparse($post->fileOrig,true);
        
        PostGenerator::generatePostFromObject($post);
        ThreadGenerator::generateThread($thread,$board->boardID);
        BoardGenerator::generateBoard($board->boardID);

        $l->triggerPOST('Purplish','Purplish',$post->BID,$post->subject,'',$k->url("chan",$board->folder.'/threads/'.$thread.'.php#'.$post->postID),$post->title);
        $hfile='';$hline='';
        if(!headers_sent($hfile,$hline)){
            if(in_array("noko",$mail)){
                header('Location: '.Toolkit::url("chan",$board->folder.'/threads/'.$thread.'.php#'.$post->postID));
            }else{
                header('Location: '.Toolkit::url("chan",$board->folder.'/'));
            }
        }else{
            die('Error sending headers: '.$hfile.':'.$hline);
        }
        die('Post successful.');
    }

    function calculateTrip($trip) {
        global $c,$k,$p;
        $trip = $c->enparse($trip);
        $trip = mb_convert_encoding($trip,'SJIS','UTF-8');
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

        $s=str_ireplace(array('<br />','<br/>','<br>'),"\n",$s);
        
        $s = preg_replace_callback('`(&gt;){2,4}/?((\w+/|[0-9])([0-9]+|([a-zA-Z]+)(/[0-9]*)?)?)`s', array(&$this,'parseReference'), $s);
        $s = preg_replace('/^(&gt;[^>](.*))\n/m', '<span class="quoteLine">\\1</span>'."\n", $s);
        
        //$s=str_ireplace("\n","<br />",$s);
        return $s;
    }
    
    function parseReference($matches){
        global $threadBoardName,$threadThreadID;
        $sites=array('arch'=>'http://stevenarch.tymoon.eu/',
                     '4chan'=>'http://4chan.org/');
        $matches[3]=str_replace('/','',$matches[3]);
        $text = $matches[0];
        
        if(is_numeric($matches[2])){
            //Same board reference
            $id=$matches[2];
            $board=$threadBoardName;
            $href=PROOT.$threadBoardName.'/threads/'.$threadThreadID.'.php#'.$id;
            if($id==$threadThreadID)$text=$matches[0].' (OP)';
        }else if($matches[4]==''){
            //Board reference
            $id='';
            $board=$matches[3];
            $href=PROOT.$matches[3];
        }else if(is_numeric($matches[4])){
            //Inter board reference
            $post = DataModel::getData('','SELECT PID FROM ch_posts WHERE postID=? AND BID=(SELECT boardID FROM ch_boards WHERE folder LIKE ?)',array($matches[4],$matches[3]));
            if($post==null)return $matches[0];
            if($post->PID==0)$post->PID=$matches[4];
            
            $id=$matches[4];
            $board=$matches[3];
            $href=PROOT.$matches[3].'/threads/'.$post->PID.'.php#'.$matches[4];
        }else if($matches[5]==''){
            //Different site, board reference
            if(!array_key_exists($matches[3],$sites))return $matches[0];
            $id='';$board='';
            $href=$sites[$matches[3]].$matches[5];
        }else{
            //Different site, inter board reference
            if(!array_key_exists($matches[3],$sites))return $matches[0];
            $id='';$board='';
            $href=$sites[$matches[3]].$matches[5].$matches[6];
        }
        return '<a class="directQuote" id="'.$id.'" board="'.$board.'" href="'.$href.'">'.$text.'</a>';
    }

    function cleanBoard($boardID,$folder=null){
        global $c,$l;
        if($folder==null){
            $folder= DataModel::getData('',"SELECT folder FROM ch_boards WHERE boardID=?",array($boardID));
            $folder=$folder->folder;
        }
        echo("<div class='success'>Cleaning:<br />Database...<br />");
        $c->query("DELETE FROM ch_posts WHERE BID=? AND options LIKE ?",array($boardID,'%d%'));
        
        echo("Files: <br />");$temp=glob(ROOT.DATAPATH.'chan/'.$folder.'/files/_*.*');
        if(is_array($temp)&&count($temp)>0)
            foreach($temp as $fn)  {echo('&nbsp; &nbsp; Deleting: '.$fn.'<br />');ob_flush();unlink($fn);}
        else{echo('&nbsp; &nbsp; Clean.<br />');ob_flush();}
        flush();
        echo("Thumbs: <br />");$temp=glob(ROOT.DATAPATH.'chan/'.$folder.'/thumbs/_*.*');
        if(is_array($temp)&&count($temp)>0)
            foreach($temp as $fn) {echo('&nbsp; &nbsp; Deleting: '.$fn.'<br />');ob_flush();unlink($fn);}
        else{echo('&nbsp; &nbsp; Clean.<br />');ob_flush();}
        flush();
        echo("Posts: <br />");$temp=glob(ROOT.DATAPATH.'chan/'.$folder.'/posts/_*.php');
        if(is_array($temp)&&count($temp)>0)
            foreach($temp as $fn)  {echo('&nbsp; &nbsp; Deleting: '.$fn.'<br />');ob_flush();unlink($fn);}
        else{echo('&nbsp; &nbsp; Clean.<br />');ob_flush();}
        flush();
        echo("Threads: <br />");$temp=glob(ROOT.DATAPATH.'chan/'.$folder.'/threads/_*.php');
        if(is_array($temp)&&count($temp)>0)
            foreach($temp as $fn){echo('&nbsp; &nbsp; Deleting: '.$fn.'<br />');ob_flush();unlink($fn);}
        else{echo('&nbsp; &nbsp; Clean.<br />');ob_flush();}
        echo('</div>');
        flush();
        
        $l->triggerHook('clean','Purplish',array($boardID,$folder));
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

    function cleanThread($threadID,$folder){
        global $l;
        $posts = DataModel::getData('ch_posts',"SELECT postID,BID,file FROM ch_posts WHERE PID=? OR postID=? ORDER BY postID DESC",array($threadID,$threadID));
        if($posts==null)return false;
        Toolkit::assureArray($posts);
        
        for($i=1;$i<count($posts);$i++){
            $this->deleteTraces($folder,$posts[$i]->postID,$posts[$i]->file);
        }
        $this->deleteTraces($folder,$posts[0]->postID,$posts[0]->file,true);
        
        $l->triggerHook('cleanThread','Purplish',array($threadID,$folder));
        return true;
    }
}
?>