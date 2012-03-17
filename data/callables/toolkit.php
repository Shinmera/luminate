<? class Toolkit{

function getTimeElapsed(){
    $time = explode(' ',microtime());
    $time = $time[1]+$time[0];
    return round(($time-STARTTIME),4);
}
    
public static function url($sub,$url){
    if(substr($url,0,1)!="/")$url=PROOT.$url;
    if($sub=="") return 'http://'.HOST.$url;
    else         return 'http://'.$sub.'.'.HOST.$url;
}

public static function log($message){
    global $c,$a;
    $c->query("INSERT INTO ms_log VALUES(NULL,?,?,?)",array($message,time(),$a->user->userID));
}

function convertArrayDown($array,$field,$ret=array()){
    for($i=0;$i<count($array);$i++)$ret[]=$array[$i][$field];
    return $ret;
}

function interactiveList($name,$viewData,$valData,$selData=array(),$allowAll=false){
    ?><div class='interactiveSelect' id='<?=$name?>'>
    <input autocomplete="off" type="text" id="sel_<?=$name?>_add" placeholder="New Value" ><ul><?
    for($i=0;$i<count($selData);$i++){
        $pos=array_search($selData[$i],$valData);
        ?><li><a href="#">x</a> <input type="hidden" name="<?=$name?>[]" value="<?=$valData[$pos]?>" /><?=$viewData[$pos]?></li> <?
    }
    ?></ul></div><script type="text/javascript">
        <? echo("var ".$name."_viewData = ['".implode("','",$viewData)."'];"); ?>
        <? echo("var ".$name."_valData = ['".implode("','",$valData)."'];"); ?>
        $(function(){
            $("#<?=$name?>>ul").sortable({
                axis:'x',
                containment: '#<?=$name?>>ul'
            });
        });
        $("#sel_<?=$name?>_add").keypress(function(e) {
            if(e.keyCode == 13) {
                var pos=$.inArray($("#sel_<?=$name?>_add").val(),<?=$name?>_valData);
                if(pos==-1)pos=$.inArray($("#sel_<?=$name?>_add").val(),<?=$name?>_viewData);
                if(pos!=-1){
                    $("#<?=$name?>>ul").append(
                        '<li><a href="#">x</a> <input type="hidden" name="<?=$name?>[]" value="'+
                        <?=$name?>_valData[pos]+'" />'+<?=$name?>_viewData[pos]+'</li> ');
                    <?=$name?>resetLinks();
                }else if("<?=$allowAll?>"=="1"){
                    $("#<?=$name?>>ul").append(
                        '<li><a href="#">x</a> <input type="hidden" name="<?=$name?>[]" value="'+
                        $("#sel_<?=$name?>_add").val()+'" />'+$("#sel_<?=$name?>_add").val()+'</li> ');
                    <?=$name?>resetLinks();
                }
                $("#<?=$name?>>ul").sortable('refresh');
                $("#sel_<?=$name?>_add").val('');
                $("#sel_<?=$name?>_add").focus();
                return false;
            }
        });
        <?=$name?>resetLinks();
        function <?=$name?>resetLinks(){
            $("#<?=$name?> a").each(function(){
                $(this).unbind('click');
                $(this).click(function(){
                    $(this).parent().remove();
                    return false;
                });
        });
        }
    </script><?
}

function printSelect($name,$viewData,$valData,$presel=-1,$search=null){
    echo("<select name='".$name."'>");
    for($i=0;$i<count($valData);$i++){
        if($search!=null)$uID=array_search($valData[$i],$search);else $uID=$i;
        if($valData[$i]==$presel)$sel="selected";else $sel="";
        echo("<option value='".$valData[$i]."' ".$sel." >".$viewData[$uID]."</option>");
    }
    echo("</select>");
}

function printSelectObj($name,$objects,$viewField,$valField,$presel=-1){
    echo("<select name='".$name."'>");
    for($i=0;$i<count($objects);$i++){
        if($objects[$i]->$valField==$presel)$sel="selected";else $sel="";
        echo("<option value='".$objects[$i]->$valField."' ".$sel." >".$objects[$i]->$viewField."</option>");
    }
    echo("</select>");
}

function modCategorySelect($name,$module,$presel=-1,$none=false){
    global $c;
    echo("<select name='".$name."'>");
    if($none)echo("<option value='-1' >-</option>");
    for($i=0;$i<count($c->msCID);$i++){
        if($c->msCMID[$i]==$module){
            if($c->msCID[$i]==$presel)$sel="selected";else $sel="";
            echo("<option value='".$c->msCID[$i]."' ".$sel." >".$c->msCTitle[$i]."</option>");
        }
    }
    echo("</select>");
}


function err($message,$die=false){
    $message="<div style='padding:10px;margin:10px;
                          color:white;font-weight:bold;font-family: Arial;
                          background-color: #FF0000;box-shadow: 0px 0px 10px #FF0000;
                          border-radius: 10px;
                          display:inline-block;vertical-align:text-top;'><div class='error'>".nl2br ($message)."</div></div>";
    if($die)die($message);else echo($message);
}

function pf($message){
    echo($message.'<br />');
    ob_flush();flush();
}

function checkBanned($IP){
    global $c;
    $data = $c->getData("SELECT IP,time,reason,appeal FROM ms_banned WHERE ? REGEXP IP",array($_SERVER['REMOTE_ADDR']));
    if(count($data)==0)return false;
    if(time()>$data[0]['time']&&$data[0]['time']!=-1)
        $c->query("DELETE FROM ms_banned WHERE IP=?",array($data[0]['IP']));
    return $data[0];
}

function toKeyArray($array,$delim1=";",$delim2="="){
    $temp=explode($delim1,$array);
    $args=array();
    for($i=0;$i<count($temp);$i++){
        $temp[$i]=trim($temp[$i]);
        if(strlen($temp[$i])>0){
            $temp2 = explode($delim2, $temp[$i]);
            $args[$temp2[0]]=$temp2[1];
        }
    }
    return $args;
}

function toKeyString($array,$delim1=";",$delim2="="){
    foreach($array as $key=>$val){
        $ret.=';'.$key.$delim2.$val;
    }
    $ret=substr($ret,1);
    return $ret;
}

function cleanArray($array){
    if(!is_array($array))$array=explode(";",$array);
    $ret=array();
    for($i=0;$i<count($array);$i++){
        if($array[$i]!="")$ret[]=$array[$i];
    }
    return $ret;
}

function createThumbnail($in,$out,$w=150,$h=150,$force=false,$magic=false,$crop=false){
    if(!file_exists($in))return -1;
    if($out=="")return -1;
    if($w<=1)$w=150;if($h<=1)$h=150;
    $img = $in;
    $temp = getimagesize($img);
    $info = pathinfo($img);
    $res = array();
    //check size
    if($temp[0]>$w||$temp[1]>$h||$force){
        //resize
        if ($magic){
            //convert in two steps.
            if($crop==false){exec("convert -size ".$temp[0]."x".$temp[1]." '".$img."' -coalesce -thumbnail ".$w."x".$h." '".$out."'",$res);
            }else{
                if($crop=="w"){
                                    exec("convert '".$img."' -coalesce -thumbnail ".$h." '".$out."'",$res);
                                    exec("convert '".$out."' -coalesce -gravity center -crop ".$w."x".$h."+0+0 '".$out."'",$res);
                                }else
                if($crop=="h"){
                                    exec("convert '".$img."' -coalesce -thumbnail ".$w." '".$out."'",$res);
                                    exec("convert '".$out."' -coalesce -gravity center -crop ".$w."x".$h."+0+0 '".$out."'",$res);
                                }else{
                                    exec("convert '".$img."' -coalesce -gravity center -crop ".$w."x".$h."+0+0 '".$out."'",$res);
                                }
            }
           }else{
               if ( strtolower($info['extension']) == 'jpg' )$imgs = imagecreatefromjpeg($img);
               if ( strtolower($info['extension']) == 'jpeg')$imgs = imagecreatefromjpeg($img);
               if ( strtolower($info['extension']) == 'png' )$imgs = imagecreatefrompng($img);
               if ( strtolower($info['extension']) == 'gif' )$imgs = imagecreatefromgif($img);

            $width = imagesx( $imgs );
               $height = imagesy( $imgs );
               $tmp_img = imagecreatetruecolor( $w, $h);
               if($crop==false)    imagecopyresized( $tmp_img, $imgs, 0,0, 0,0,                             $w,$h, $width,$height );
            else if($crop=="w")    imagecopyresized( $tmp_img, $imgs, 0,0, $width/2-$w/2,0,                 $w,$h, $w,$height );
            else if($crop=="h")    imagecopyresized( $tmp_img, $imgs, 0,0, 0,$height/2-$h/2,                 $w,$h, $width,$h );
            else                 imagecopyresized( $tmp_img, $imgs, 0,0, $width/2-$w/2,$height/2-$h/2,     $w,$h, $w,$h );

               if ( strtolower($info['extension']) == 'jpg' )imagejpeg( $tmp_img,$out);
               if ( strtolower($info['extension']) == 'jpeg')imagejpeg( $tmp_img,$out);
               if ( strtolower($info['extension']) == 'png' )imagepng( $tmp_img,$out);
               if ( strtolower($info['extension']) == 'gif' )imagegif( $tmp_img,$out);
           }
        return 1;
    }else{
        copy($in,$out);
        return 0;
    }
}

function getUserPage($user){
    return("<a href='".PROOT."user/".str_replace(" ","_",$user)."'>".$user."</a>");
}

function addAPIToken($key){
    global $s,$c;$short = $s->generateShort(50);
    $c->query("INSERT INTO ms_options VALUES(?,?)",array($key,$short));
    return $short;
}

function compileList($data,$epr=5,$limit=-1,$align="center",$box=""){
    ?><table align="<?=$align?>"><tr><?
    if($limit==-1)$limit=count($data);
    for($i=0;$i<$limit;$i++){
        if($i%$epr==0)echo("</tr><tr>");
        ?><td align="<?=$align?>" style="<?=$box?>"><?=$data[$i]?></td><?
    }
    ?></tr></table><?
}

function compileTagList($data){
    for($i=0;$i<count($data);$i++){
        if($data[$i]!="")
            echo("<div class='tag'><a href='".PROOT."search/tag/".$data[$i]."'>".$data[$i]."</a></div> ");
    }
}

function getGravatar($name,$size=100,$extra=""){
    require_once(TROOT."callables/gravatar.php");
    $gravatar = new Gravatar($name,AVATARPATH."noguy.jpg");
    $gravatar->size = $size;
    $gravatar->iclass = $extra;
    return($gravatar);
}

function getGroup($uID){
    global $c;
    $c->loadUsers($uID);
    return $c->msGTitle[array_search($c->udUGroup[array_search($uID,$c->udUID)],$c->msGID)];
}

function array_insert(&$array, $insert, $position = -1) { 
     $position = ($position == -1) ? (count($array)) : $position ; 
     if($position != (count($array))) { 
          $ta = $array; 
          for($i = $position; $i < (count($array)); $i++) { 
               if(!isset($array[$i])) { 
                    die(print_r($array, 1)."\r\nInvalid array: All keys must be numerical and in sequence."); 
               } 
               $tmp[$i+1] = $array[$i]; 
               unset($ta[$i]); 
          } 
          $ta[$position] = $insert; 
          $array = $ta + $tmp; 
          //print_r($array); 
     } else { 
          $array[$position] = $insert; 
     } 
 
     ksort($array); 
     return true; 
}

function in_arrayi($needle, $haystack) { 
    foreach ($haystack as $value) {
        if (strtolower($value) == strtolower($needle))
            return true;
    }
    return false;
}

function breadcrumbs($array){
    echo("<div class='breadcrumbs'>");
    foreach($array as $a=>$l){
        echo("&gt; <a href='".$l."'>".$a."</a> ");
    }
    echo("</div>");
}

function pager($base,$max,$current=0,$step=25,$return=false){
    $ret="";
    $ret.="<div class='pager'>Pages: ";
    if($max==0)$max=1;
    if($step==0)$step=25;
    if($current<0)$current*=-1;
    if($max/$step<15){
        for($i=0;$i<$max;$i+=$step){
            if($i/$step==$current) $ret.="<span class='pager_current'>".($i/$step+1)."</span> ";
            else $ret.="<a href='".$base."".($i/$step)."'>".($i/$step+1)."</a> ";
        }
    }else{
        if($current<5||$current>($max/$step)-5){
            for($i=0;$i<5;$i+=$step){
                $ret.="<a href='".$base."".($i/$step)."'>".($i/$step+1)."</a> ";
            }
        }else{
            for($i=$current-2;$i<$current+2;$i+=$step){
                $ret.="<a href='".$base."".($i/$step)."'>".($i/$step+1)."</a> ";
            }
        }echo("... ");
        for($i=$max-5;$i<$max;$i+=$step){
            if($i/$step+1==$current) $ret.="<span class='pager_current'>".($i/$step+1)."</span> ";
            $ret.="<a href='".$base."".($i/$step)."'>".($i/$ste+1)."</a> ";
        }
    }
    $ret.="</div>";
    if($return)return $ret;else echo($ret);
}

function toDate($time){
    if(is_numeric($time))
        return date('d.m.Y H:i:s',$time);
    else
        return $time;
}

function convertHTML($html){
    $html = str_replace("<","&lt;",$html);
    $html = str_replace(">","&gt;",$html);
    return $html;
}

function removeFromList($needle,$haystack,$sep=";"){
    $haystack = str_replace($needle,"",$haystack);
    $haystack = str_replace($sep.$sep,$sep,$haystack);
    if(substr($haystack,0,1)==$sep)$haystack = substr($haystack,1);
    if(substr($haystack,strlen($haystack)-1)==$sep)$haystack = substr($haystack,0,strlen($haystack)-1);
    return $haystack;
}

function unzipFile($file,$destination){
    $zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res === TRUE) {
        $zip->extractTo($destination);
        $zip->close();
        return true;
    } else {
        return false;
    }
}

function downloadFile($url,$destination,$maxsizeKB=500,$allowedfiles=array(""),$overwrite=false,$newname=""){
    $headers = get_headers($url,1);
    $filename = substr($url,strrpos($url,"/")+1);
    $filesize = $headers['Content-Length']/1024;
    $filetype = $headers['Content-Type'];
    $extension= strtolower(substr($url,strrpos($url,".")+1));
    if(strpos($url,"http://")===FALSE)$url="http://".$url;
    if(substr($destination,strlen($destination)-1)!="/")
        $destination=$destination."/";
    //new filename if any
    if($newname!=""){
        if(strpos($newname,".")===FALSE)
            $newname = $newname.substr($filename,strpos($filename,"."));
        $filename = $newname;
    }
    if(strlen($url)<12)                                          throw new Exception("Invalid URL to download from: '".$url."'");
    if(strpos($url,".")===FALSE)                                 throw new Exception("Invalid URL to download from: '".$url."'");
    if(strpos($headers[0],"200")===FALSE)                        throw new Exception("Invalid URL to download from: '".$url."'");
    if($filesize>$maxsizeKB)                                     throw new Exception("File size is too big: ".$filesize."");
    if($allowedfiles[0]!=""&&!in_array(strtolower($filetype),$allowedfiles)) throw new Exception("Bad filetype: '".$filetype."'");
    if(file_exists($destination.$filename)&&!$overwrite)         throw new Exception("File '".$destination.$filename."' already exists!");

    $path=$destination.$k->sanitizeFilename($filename);

    //download
    $dest=fopen($path,"w");
    $source=fopen(urldecode($url),"r");
    while ($a=fread($source,1024)) fwrite($dest,$a);
    fclose($source);
    fclose($dest);
    return $path;
}

function uploadFile($fieldname,$destination,$maxsizeKB=500,$allowedfiles=array(""),$overwrite=false,$newname=""){
    if(!is_uploaded_file($_FILES[$fieldname]['tmp_name']))        throw new Exception("No uploaded file!");
    if(!file_exists($_FILES[$fieldname]['tmp_name']))             throw new Exception("No uploaded file exists!");
    $filesize = $_FILES[$fieldname]['size']/1024;
    $filename = $_FILES[$fieldname]['name'];
    $fileorig = $_FILES[$fieldname]['tmp_name'];
    $filetype = $_FILES[$fieldname]['type'];
    //new filename if any
    if($newname!=""){
        if(strpos($newname,".")===FALSE)
            $newname = $newname.substr($filename,strpos($filename,"."));
        $filename = $newname;
    }
    //get away those nasty characters.
    $filename = $this->sanitizeFilename($filename);
    if(substr($destination,strlen($destination)-1)!="/")$destination=$destination."/";
    //perform checks
    if($filesize>$maxsizeKB)                                                throw new Exception("Filesize is too big: ".$filesize);
    if($allowedfiles[0]!=""&&!in_array(strtolower($filetype),$allowedfiles))throw new Exception("Bad filetype: ".$filetype);
    if(file_exists($destination.$filename)&&!$overwrite)                    throw new Exception("File '".$destination.$filename."' already exists!");
    //move
    if(!move_uploaded_file($fileorig,$destination.$filename))               throw new Exception("File upload failed!");
    return $destination.$filename;
}

function displayFilesize($filesize){
    if(is_numeric($filesize)){
        $decr = 1024; $step = 0;
        $prefix = array('Byte','KB','MB','GB','TB','PB');

        while(($filesize / $decr) > 0.9){
            $filesize = $filesize / $decr;
            $step++;
        }
        return round($filesize,2).' '.$prefix[$step];
    } else {
        return 'NaN';
    }
}

function sanitizeFilename($filename){
    $filename = $this->sanitizeString($filename);
    $filename = str_replace(" ","_", $filename);
    if(strlen($filename)>48){
        $ending=substr($filename,strpos($filename, "."));
        $filename=substr($filename,0,48).$ending;
    }
    return $filename;
}

function sanitizeString($s,$extra=''){
    return preg_replace("/[^a-zA-Z0-9\s\.\-_]/", "",$s);
}


function displayImageSized($imgpath,$limit=800,$title="",$alt="image"){
    $d = getimagesize(ROOT.$imgpath);
    if($d[0]>$limit)$d=$limit;else $d=$d[0];
    echo("<img src='".$imgpath."' width='".$d."px' title='".$title."' alt='".$alt."' />");
}

function validateMail($mail,$selfcheck=true){
    global $c;
    if($mail=="")return false;
    if(strpos($mail,".")===FALSE)return false;
    if(strpos($mail,"@")===FALSE)return false;
    if(strpos($mail,"@")>=(strlen($mail)-1))return false;
    if(strpos($mail,".")>=(strlen($mail)-1))return false;
    //if(in_array($mail,$c->udUMail)&&$selfcheck)return false;

    //B& hosts
    $banned = explode("\n",file_get_contents(TROOT."callables/banned-mails"));
    if(in_array(substr($mail,strpos($mail,"@")),$banned))return false;

    return true;
}

function updateTimeout($action,$timeout){
    return $this->updateTimeout($action,$timeout);
}

function updateTimestamp($action,$timeout){
    global $c;
    $result=$c->getData("SELECT `time` FROM ms_timer WHERE IP=? AND action=?",array($_SERVER['REMOTE_ADDR'],$action));
    if(count($result)>0){
        if((time()-$result[0]['time'])<=$timeout)return false;
        $c->query("DELETE FROM ms_timer WHERE IP=? AND action=?",array($_SERVER['REMOTE_ADDR'],$action));
    }
    $c->query("INSERT INTO ms_timer VALUES(?,?,?)",array($_SERVER['REMOTE_ADDR'],time(),$action));
    return true;
}

function stringToVarKey($s,$delim1=";",$delim2="="){
    $s = explode($delim1,$s);
    $ar = array();
    for($i=0;$i<count($s);$i++){
        $tmp = strpos($s[$i],$delim2);
        $ar[substr($s[$i],0,$tmp)]=substr($s[$i],$tmp+1);
    }
    return $ar;
}

function wrapAndPrint($array,$front,$end){
    if(is_array($array)){
        foreach($array as $el){
            echo($front.$el.$end);
        }
    }else echo($front.$array.$end);
}

function htmlDiff($old, $new){
    if(!class_exists("simpleDiff"))include(TROOT.'callables/simplediff.php');
    
    $diff = simpleDiff::diff_to_array(false, $old, $new, 1);

    $out = '<table class="diff">';
    $prev = key($diff);

    foreach ($diff as $i=>$line){
        if ($i > $prev + 1){
            $out .= '<tr><td colspan="5" class="separator"><hr /></td></tr>';
        }

        list($type, $old, $new) = $line;

        $class1 = $class2 = '';
        $t1 = $t2 = '';

        if ($type == simpleDiff::INS){
            $class2 = 'ins';
            $t2 = '+';
        }elseif ($type == simpleDiff::DEL){
            $class1 = 'del';
            $t1 = '-';
        }elseif ($type == simpleDiff::CHANGED){
            $class1 = 'del';
            $class2 = 'ins';
            $t1 = '-';
            $t2 = '+';

            $lineDiff = simpleDiff::wdiff($old, $new);

            // Don't show new things in deleted line
            $old = preg_replace('!\{\+(?:.*)\+\}!U', '', $lineDiff);
            $old = str_replace('  ', ' ', $old);
            $old = str_replace('-] [-', ' ', $old);
            $old = preg_replace('!\[-(.*)-\]!U', '<del>\\1</del>', $old);

            // Don't show old things in added line
            $new = preg_replace('!\[-(?:.*)-\]!U', '', $lineDiff);
            $new = str_replace('  ', ' ', $new);
            $new = str_replace('+} {+', ' ', $new);
            $new = preg_replace('!\{\+(.*)\+\}!U', '<ins>\\1</ins>', $new);
        }

        $out .= '<tr>';
        $out .= '<td class="line">'.($i+1).'</td>';
        $out .= '<td class="leftChange">'.$t1.'</td>';
        $out .= '<td class="leftText '.$class1.'">'.$old.'</td>';
        $out .= '<td class="rightChange">'.$t2.'</td>';
        $out .= '<td class="rightText '.$class2.'">'.$new.'</td>';
        $out .= '</tr>';

        $prev = $i;
    }

    $out .= '</table>';
    return $out;
}

function checkShitBrowser(){
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie') !== FALSE && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 9.') === FALSE){
        include(PAGEPATH.'shitbrowser.php');
        die();
    }
}

function getMicrotime(){
    $time = explode(' ',microtime());
    $time = $time[1]+$time[0];
    return $time;
}

function strnposr($haystack, $needle, $occurrence, $pos = 0) {
    return ($occurrence<2)?strpos($haystack, $needle, $pos):$this->strnposr($haystack,$needle,$occurrence-1,strpos($haystack, $needle, $pos) + 1);
}

function generateModuleCache(){
    $modulelist=array();
    $dh = opendir(MODULEPATH);
    while(($file = readdir($dh)) !== false){
        if($file!="."&&$file!=".."){
            if(is_dir(MODULEPATH.$file)){
                $di = opendir(MODULEPATH.$file);
                while(($ifile = readdir($di)) !== false){
                    if($ifile!="."&&$ifile!=".."&&!is_dir(MODULEPATH.$file.'/'.$ifile)){
                        $modulelist[str_replace(".php","",$ifile)]=$file.'/'.$ifile;
                    }
                }
            }else{
                $modulelist[str_replace(".php","",$file)]=$file;
            }
        }
    }
    closedir($dh);
    
    file_put_contents(CALLABLESPATH.'modulecache', serialize($modulelist));
}  

function generateRandomString($n=5,$set=array(0=>2,1=>1,2=>0),$add=""){
    $numbers = "01234567890123456789";
    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $specials = ",;.:-_/\\<>(){}[]+\"'*#@&%?!^~";
    for($i=0;$i<$set[0];$i++)$characters.=$numbers;
    for($i=0;$i<$set[1];$i++)$characters.=$alphabet;
    for($i=0;$i<$set[2];$i++)$characters.=$specials;
    $characters.=$add;
    for ($p = 0; $p < $n; $p++) {
        $short .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    if(is_numeric($short))$short.=$alphabet[mt_rand(0, strlen($alphabet)-1)];
    return $short;
}

}
?>
