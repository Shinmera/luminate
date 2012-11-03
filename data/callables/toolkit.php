<? class Toolkit{
    
public static function p($int){if($int<0)return $int*-1;else return $int;}

public static function getTimeElapsed(){
    $time = explode(' ',microtime());
    $time = $time[1]+$time[0];
    return round(($time-STARTTIME),4);
}
    
public static function url($sub,$url=''){
    if(substr($url,0,1)!="/")$url=PROOT.$url;
    if($sub=="") return 'http://'.HOST.$url;
    else         return 'http://'.$sub.'.'.HOST.$url;
}

public static function log($message){
    global $c,$a;
    $c->query("INSERT INTO ms_log VALUES(NULL,?,?,?)",array($message,time(),$a->user->userID));
}

public static function set($key,$var,$type='s'){
    global $c;
    $c->query('INSERT INTO ms_options VALUES(?,?,?) ON DUPLICATE KEY UPDATE `value`=?',array($key,$var,$type,$var));
    $c->o[$key]=$var;
}

public static function convertArrayDown($array,$field,$ret=array()){
    for($i=0;$i<count($array);$i++)$ret[]=$array[$i][$field];
    return $ret;
}

public static function assureArray(&$obj){
    if($obj==null)$obj=array();
    if(!is_array($obj))$obj=array($obj);
}

public static function suggestedTextField($name,$apisource,$default="",$class="",$return=false){
    $var='<input type="text" id="'.$name.'" name="'.$name.'" class="'.$class.'" value="'.$default.'" autocomplete="off" />
        <script type="text/javascript">
	$(function() {
            public static function split( val ) {
                return val.split( /,\s*/ );
            }
            function extractLast( term ) {
                return split( term ).pop();
            }

            $( "#'.$name.'" )
                // dont navigate away from the field on tab when selecting an item
                .bind( "keydown", function( event ) {
                    if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "autocomplete" ).menu.active ) {
                            event.preventDefault();
                    }
                })
                .autocomplete({
                    source: function( request, response ) {
                        $.getJSON( "'.PROOT.'api/'.$apisource.'", { query: extractLast( request.term ) }, response );
                    },
                    search: function() {
                        // custom minLength
                        var term = extractLast( this.value );
                        if ( term.length < 2 ) {return false;}
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function( event, ui ) {
                        var terms = split( this.value );
                        terms.pop();
                        terms.push( ui.item.value );
                        terms.push( "" );
                        this.value = terms.join( ", " );
                        return false;
                    }
                });
	});
	</script>';
    if($return)return $var;else echo($var);
}

public static function interactiveList($name,$viewData,$valData,$selData=array(),$allowAll=false,$return=false){
    for($i=0;$i<count($viewData);$i++){
        $data[]='"'.$viewData[$i].'":"'.$valData[$i].'"';
    }
    if(!is_array($data))$data=array();
    $data = '{'.implode(',',$data).'}';
    $selData = '["'.implode('","',$selData).'"]';
    if($allowAll)$allowAll='true';else $allowAll='false';
    $var='<div id="'.$name.'" class="interactiveSelect">
            <input autocomplete="off" placeholder="..." type="text" onkeypress="return event.keyCode!=13" />
            <ul></ul>
          </div>
          <script type="text/javascript">
            $(function(){createInteractiveList("'.$name.'",'.$data.','.$selData.','.$allowAll.');});
          </script>';
    if($return)return $var;else echo($var);
}

public static function printSelect($name,$viewData,$valData,$presel=-1,$search=null){
    echo("<select name='".$name."'>");
    for($i=0;$i<count($valData);$i++){
        if($search!=null)$uID=array_search($valData[$i],$search);else $uID=$i;
        if($valData[$i]==$presel)$sel="selected";else $sel="";
        echo("<option value='".$valData[$i]."' ".$sel." >".$viewData[$uID]."</option>");
    }
    echo("</select>");
}

public static function printSelectObj($name,$objects,$viewField,$valField,$presel=-1){
    echo("<select name='".$name."'>");
    for($i=0;$i<count($objects);$i++){
        if($objects[$i]->$valField==$presel)$sel="selected";else $sel="";
        echo("<option value='".$objects[$i]->$valField."' ".$sel." >".$objects[$i]->$viewField."</option>");
    }
    echo("</select>");
}

public static function modCategorySelect($name,$module,$presel=-1,$none=false){
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


public static function err($message,$die=false,$return=false){
    $message="<div style='padding:3px;margin:2px;
                          color:#000;font-weight:bold;font-family: Arial;font-size:10pt;
                          background-color: #FF0000;box-shadow: 0px 0px 2px #FF0000;
                          border-radius: 5px;
                          display:inline-block;vertical-align:text-top;'><div class='error'>".nl2br ($message);
    if($return)$message.="<br /><a href='".$_SERVER['HTTP_REFERER']."'>Return</a>";
    $message.="</div></div>";
    if($die)die($message);else echo($message);
}

public static function handle_exception($e){
    $constants = get_defined_constants(true);
    $message = '
        <html>
            <head>
                <title>TyNET: Uncaught Exception</title>
            </head>
            <body>
                <b>IP:</b> '.$_SERVER['REMOTE_ADDR'].'<br />
                <b>Date:</b> '.date('Y-m-d H:i:s').'<br />
                <b>File:</b> '.$e->getFile().':'.$e->getLine().'<br />
                <b>Message:</b> '.$e->getMessage().'<br />
                <b>Stack Trace:</b><br />
                '.$e->getTraceAsString().'<br />
                <b>Constants:</b><br />
                '.str_replace("\n",'<br />',print_r($constants['user'],true)).'
            </body>
        </html>';
    $headers  = 'MIME-Version: 1.0'."\r\n".
                'Content-type: text/html; charset=UTF-8'."\r\n".
                'To: Sysop <'.SYSOPMAIL.'>'."\r\n".
                'From: TyNET system <sys@'.HOST.'>'."\r\n";
    mail(SYSOPMAIL, 'TyNET: Uncaught Exception', $message, $headers);
}

public static function pf($message){
    echo($message.'<br />');
    ob_flush();flush();
}

public static function swap(&$a,&$b){
    $temp=$a;
    $a=$b;
    $b=$temp;
}

public static function toKeyArray($array,$delim1=";",$delim2="="){
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

public static function toKeyString($array,$delim1=";",$delim2="="){
    foreach($array as $key=>$val){
        $ret.=';'.$key.$delim2.$val;
    }
    $ret=substr($ret,1);
    return $ret;
}

public static function cleanArray($array){
    if(!is_array($array))$array=explode(";",$array);
    $ret=array();
    for($i=0;$i<count($array);$i++){
        if($array[$i]!="")$ret[]=$array[$i];
    }
    return $ret;
}

public static function isAssociative($array){
    return (bool)count(array_filter(array_keys($array), 'is_string'));
}

public static function createThumbnail($in,$out,$w=150,$h=150,$force=false,$magic=false,$crop=false,$quality='100'){
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
            if($crop==false){
                exec("convert -size ".$temp[0]."x".$temp[1]." '".$img."' -quality 100% -coalesce -thumbnail ".$w."x".$h." '".$out."'",$res);
            }else{
                if($crop=="w"){
                    exec("convert '".$img."' -quality ".$quality."% -coalesce -thumbnail ".$h." '".$out."'",$res);
                    exec("convert '".$out."' -quality ".$quality."% -coalesce -gravity center -crop ".$w."x".$h."+0+0 '".$out."'",$res);
                }else
                if($crop=="h"){
                    exec("convert '".$img."' -quality ".$quality."% -coalesce -thumbnail ".$w." '".$out."'",$res);
                    exec("convert '".$out."' -quality ".$quality."% -coalesce -gravity center -crop ".$w."x".$h."+0+0 '".$out."'",$res);
                }else{
                    exec("convert '".$img."' -quality ".$quality."% -coalesce -gravity center -crop ".$w."x".$h."+0+0 '".$out."'",$res);
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
                else                   imagecopyresized( $tmp_img, $imgs, 0,0, $width/2-$w/2,$height/2-$h/2,     $w,$h, $w,$h );

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

public static function compileList($data,$epr=5,$limit=-1,$align="center",$box=""){
    ?><table align="<?=$align?>"><tr><?
    if($limit==-1)$limit=count($data);
    for($i=0;$i<$limit;$i++){
        if($i%$epr==0)echo("</tr><tr>");
        ?><td align="<?=$align?>" style="<?=$box?>"><?=$data[$i]?></td><?
    }
    ?></tr></table><?
}

public static function compileTagList($data){
    if(!is_array($data))$data=explode(',',$data);
    echo('<ul class="tags">');
    for($i=0;$i<count($data);$i++){
        if($data[$i]!="")
            echo('<li><a href="'.Toolkit::url('search','tag/'.$data[$i]).'">'.$data[$i].'</a></li>');
    }
    echo('</ul>');
}

public static function getGravatar($name,$size=100,$extra=""){
    require_once(TROOT."callables/gravatar.php");
    $gravatar = new Gravatar($name,AVATARPATH."noguy.jpg");
    $gravatar->size = $size;
    $gravatar->iclass = $extra;
    return($gravatar);
}

public static function array_insert(&$array, $insert, $position = -1) { 
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

public static function in_arrayi($needle, $haystack) { 
    foreach ($haystack as $value) {
        if (strtolower($value) == strtolower($needle))
            return true;
    }
    return false;
}

public static function removeFromList($needle,$haystack,$sep=";"){
    $haystack = explode($sep,$haystack);
    unset($haystack[array_search($needle, $haystack)]);
    return implode($sep,$haystack);
}

public static function breadcrumbs($array){
    echo("<div class='breadcrumbs'>");
    foreach($array as $a=>$l){
        echo("&gt; <a href='".$l."'>".$a."</a> ");
    }
    echo("</div>");
}

public static function pager($base,$max,$current=0,$step=25,$return=false){
    $ret="<div class='pager'>Pages: ";
    if($max==0)$max=1;
    if($step==0)$step=25;
    if($current<0)$current*=-1;

    if($current>0)$ret.='<a href="'.$base.($current-1).'" title="Backwards to page '.($current+1).'" class="backwards">&lt;</a>';

    for($i=0;$i<$max;$i+=$step){
        if($i/$step==$current) $ret.="<span class='pager_current'>".($i/$step+1)."</span> ";
        else $ret.=" <a href='".$base.($i/$step)."' title='To page ".($i/$step+1)."' class='jump'>".($i/$step+1)."</a> ";
    }

    if(($current+1)*$step<$max)$ret.='<a href="'.$base.($current+1).'" title="Forwards to page '.($current+2).'" class="forwards">&gt;</a>';

    $ret.="</div>";
    if($return)return $ret;else echo($ret);
}

public static function toDate($time,$format='l d.m.Y H:i:s'){
    if(is_numeric($time))
        return date($format,$time);
    else
        return $time;
}

public static function timeAgo($time){
    return Toolkit::toLiteralTime(time()-$time).' ago';
}

public static function toLiteralTime($time){
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60","60","24","7","4.35","12","10");
    
    for($j = 0; $time >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $time /= $lengths[$j];
    }
    $time = round($time);

    if($time != 1) {
        $periods[$j].= "s";
    }
    return $time.' '.$periods[$j];
}

public static function toLiteralFilesize($filesize){return self::displayFilesize($filesize);}

public static function convertHTML($html){
    $html = str_replace("<","&lt;",$html);
    $html = str_replace(">","&gt;",$html);
    return $html;
}

public static function displayFilesize($filesize){
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

public static function unzipFile($file,$destination){
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

public static function downloadFile($url,$destination,$maxsizeKB=500,$allowedfiles=array(""),$overwrite=false,$newname=""){
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

public static function uploadFile($fieldname,$destination,$maxsizeKB=500,$allowedfiles=array(""),$overwrite=false,$newname="",$appendextension=true,$maxnamelength=64){
    if(!is_uploaded_file($_FILES[$fieldname]['tmp_name']))        throw new Exception("No file uploaded!");
    if(!file_exists($_FILES[$fieldname]['tmp_name']))             throw new Exception("No uploaded file exists!");
    $filesize = $_FILES[$fieldname]['size']/1024;
    $filename = $_FILES[$fieldname]['name'];
    $fileorig = $_FILES[$fieldname]['tmp_name'];
    $filetype = self::getMimeType($_FILES[$fieldname]['tmp_name']);
    //new filename if any
    if($newname!=""){
        if($appendextension){
            if(strpos($filename,'.')!==FALSE){
                $extension=substr($filename,strrpos($filename,'.')+1);
                if(strlen($extension)<=1){
                    $extension = substr($filetype,strrpos($filetype,'/')+1);
                }
            }else{
                $extension = substr($filetype,strrpos($filetype,'/')+1);
            }
            $newname = $newname.'.'.$extension;
        }
        $filename = $newname;
    }
    //get away those nasty characters.
    $filename = Toolkit::sanitizeFilename($filename);
    //trim filename length
    if(strlen($filename)>$maxnamelength){
        $extension=substr($filename,strrpos($filename,'.')+1);
        $basename=substr($filename,0,strrpos($filename,'.'));
        $basename=substr($filename,0,64-strlen($extension));
        $filename=$basename.$extension;
    }
    if(substr($destination,strlen($destination)-1)!="/")$destination=$destination."/";
    //perform checks
    if($filesize>$maxsizeKB)                                                throw new Exception("File is too big: ".Toolkit::displayFilesize ($filesize));
    if(file_exists($destination.$filename)&&!$overwrite)                    throw new Exception("File '".$destination.$filename."' already exists!");
    if($allowedfiles[0]!=""&&!in_array(strtolower($filetype),$allowedfiles))throw new Exception("Bad filetype: ".$filetype);
    //move
    if(!move_uploaded_file($fileorig,$destination.$filename))               throw new Exception("File upload failed!");
    return $destination.$filename;
}

//FROM: http://stackoverflow.com/questions/1232769/how-to-get-the-content-type-of-a-file-in-php
public static function getMimeType($file){
    if(!is_file($file))throw new Exception('File '.$file.' does not exist.');
    if (function_exists('finfo_file')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $file);
        finfo_close($finfo);
    } else {
        $type = mime_content_type($file);
    }

    if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
        $returnCode = '';
        $secondOpinion = exec('file -b --mime-type ' . escapeshellarg($file), $foo, $returnCode);
        if ($returnCode === 0 && $secondOpinion) {
            $type = $secondOpinion;
        }
    }
    
    if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
        $exifImageType = exif_imagetype($file);
        if ($exifImageType !== false) {
            $type = image_type_to_mime_type($exifImageType);
        }
    }
    
    if(!$type) throw new Exception('Failed to determine filetype!');
    return $type;
}

public static function displayPager(){
    ?><form class="pagerForm">
        <input class="bb" type="submit" name="dir" value="<<" />
        <input class="b"  type="submit" name="dir" value="<" />
        <input class="from" autocomplete="off" type="text" name="f" value="<?=$_GET['f']?>" style="width:50px;"/>
        <input class="to"   autocomplete="off" type="text" name="t" value="<?=$_GET['t']?>" style="width:50px;"/>
        <input class="go" type="submit" name="dir" value="Go" />
        <input class="f"  type="submit" name="dir" value=">" />
        <input class="ff" type="submit" name="dir" value=">>" />
        <input type="hidden" name="order" value="<?=$_GET['o']?>" />
        <input type="hidden" name="asc" value="<?=$_GET['a']?>" />
    </form><?
}

public static function sanitizePager($max,$orders=array(),$defaultOrder="",$step=50){
    if($_GET['dir']=='')$_GET['dir']=$_GET['action'];
    switch($_GET['dir']){
        case '<<':$_GET['f']=0;  $_GET['t']=$step; break;
        case '<' :$_GET['f']-=$step;$_GET['t']-=$step;break;
        case '>' :$_GET['f']+=$step;$_GET['t']+=$step;break;
        case '>>':$_GET['f']=$max-$step;$_GET['t']=$max;break;
    }
    if($_GET['f']<0||!is_numeric($_GET['f']))         $_GET['f']=0;
    if($_GET['t']<$_GET['f']||!is_numeric($_GET['t']))$_GET['t']=$_GET['f']+$step;
    if($_GET['t']>$max)                               $_GET['t']=$max;
    if($_GET['f']>$_GET['t'])                         $_GET['f']=$_GET['t']-1;
    if($_GET['t']-$_GET['f']>100)                     $_GET['t']=$_GET['f']+100;
    if($_GET['a']!=0&&$_GET['a']!=1)                  $_GET['a']="0";
    if($_GET['a']==0)                                 $_GET['d']='DESC';else $_GET['d']='ASC';
    $_GET['s']=$_GET['t']-$_GET['f'];
    if(!in_array($_GET['o'],$orders)){
        if($defaultOrder=="")$_GET['o']=$orders[0];
        else                 $_GET['o']=$defaultOrder;
    }
}

public static function sanitizeFilename($filename){
    $filename = Toolkit::sanitizeString($filename);
    $filename = str_replace(" ","_", $filename);
    if(strlen($filename)>48){
        $ending=substr($filename,strpos($filename, "."));
        $filename=substr($filename,0,48).$ending;
    }
    return $filename;
}

public static function sanitizeString($s,$extra="\s\.\-_"){
    return preg_replace("/[^a-zA-Z0-9".$extra."]/", "",$s);
}

public static function checkMailValidity($mail){
    /*$atpos= strpos($mail,'@');
    $dotpos=strpos($mail,'.');
    if($atpos==false  ||$dotpos==false ||$dotpos<$atpos)return false;
    $name = substr($mail,0,$atpos);
    $serv = substr($mail,$atpos+1,$dotpos-$atpos-1);
    $dom  = substr($mail,$dotpos+1);
    if($name==false   ||$serv==false   ||$dom==false)   return false;
    if(strlen($name)<3||strlen($serv)<3||strlen($dom)<2)return false;
    if($k->sanitizeString($mail,'@\.\-_')!=$mail)       return false;
    */
    if(!filter_var($mail, FILTER_VALIDATE_EMAIL))return false;
    $banned = explode("\n",file_get_contents(CALLABLESPATH."banned-mails"));
    if(in_array($serv.'.'.$dom,$banned))return false;
    return true;
}

public static function checkDateValidity(&$date){
    if(strpos($date,'.')!==FALSE){        $dates=explode('.',$date);$format="dmy";
    }else if(strpos($date,'/')!==FALSE){  $dates=explode('/',$date);$format="dmy";
    }else if(strpos($date,'-')!==FALSE){  $dates=explode('-',$date);$format="ymd";
    }else if(strpos($date,' ')!==FALSE){  $dates=explode(' ',$date);$format="dmy";
    }else return FALSE;
    if(count($dates)!=3)return false;
    if(!is_numeric($dates[0]))return false;
    if(!is_numeric($dates[1]))return false;
    if(!is_numeric($dates[2]))return false;
    $len0 = strlen($dates[0]);
    $len1 = strlen($dates[1]);
    $len2 = strlen($dates[2]);
    if($format == "dmy"){
        if($len0>2||$len0<1 || $len1>2||$len1<1 || $len2>4||$len2<2)return FALSE;
    }else if($format == "ymd"){
        if($len0>4||$len0<2 || $len1>2||$len1<1 || $len2>2||$len2<1)return FALSE;
    }
    return true;
}

public static function checkURLValidity($url){
    if(!filter_var($url, FILTER_VALIDATE_URL))return false;
    return true;
}

public static function makeUrlReady($s){
    return str_replace(' ','_',self::sanitizeString($s));
}

public static function displayImageSized($imgpath,$limit=800,$title="",$alt="image"){
    $d = getimagesize(ROOT.$imgpath);
    if($d[0]>$limit)$d=$limit;else $d=$d[0];
    echo("<img src='".$imgpath."' width='".$d."px' title='".$title."' alt='".$alt."' />");
}

public static function updateTimeout($action,$timeout){
    return Toolkit::updateTimestamp($action,$timeout);
}

public static function updateTimestamp($action,$timeout){
    $result = DataModel::getData('ms_timer',"SELECT * FROM ms_timer WHERE IP LIKE ? AND action LIKE ? LIMIT 1;",array($_SERVER['REMOTE_ADDR'],$action));
    if($result!=null){
        if((time()-$result->time)<=$timeout)return false;
        $result->time=time();
        $result->saveData();
    }else{
        $result = DataModel::getHull('ms_timer');
        $result->IP=$_SERVER['REMOTE_ADDR'];
        $result->time=time();
        $result->action=$action;
        $result->insertData();
    }
    return true;
}

public static function stringToVarKey($s,$delim1=";",$delim2="="){
    $s = explode($delim1,$s);
    $ar = array();
    for($i=0;$i<count($s);$i++){
        $tmp = strpos($s[$i],$delim2);
        $ar[substr($s[$i],0,$tmp)]=substr($s[$i],$tmp+1);
    }
    return $ar;
}

public static function autoBreakLines($text,$length=100){
    $pointer=0;
    while(true){                                                                //No need to do a check here anyway, saves some operation time.
        $nline=-1;
        while($nline!==FALSE){                                                  //As long as there are newlines in the steps, skip to them.
            $pointer+=$nline+1;
            $nline = strrpos(substr($text,$pointer,$length),"\n");
        }
        
        $lastfound=$pointer;
        $pointer+=$length;
        
        if($pointer>=strlen($text))break;                                       //Might have gone ahead with the last step.
        
        $lastopen = strpos($text,"<",$lastfound);                               //Note this code calls cthulu from the dephts of the
        $lastclose= strpos($text,">",$lastopen);                                //HTML domains. It is not able to handle complex HTML.
                                                                                //Thou hath been warned. Also, @FIXME I guess.
        if($lastopen === FALSE || $lastclose === FALSE){                        //No more tag to match, so assuming incorrect 
                                                                                //formatting.
        }else if($lastopen<$pointer && $lastclose>$pointer){                    //We landed inside a tag.
            $pointer = $lastclose+1;                                            //Skip ahead of it.
            $text = substr($text,0,$pointer)." ".substr($text,$pointer);
        }else if($lastopen<$pointer && $lastclose<$pointer){
            $pointer += $lastclose-$lastopen;                                   //Compensate for invisible tag.
            if($pointer>=strlen($text))return $text;                            //Oh wow, we jumped out of text boundary!
            
            $nextopen = strrpos($text,"<",$pointer-strlen($text));
            $nextclose = strpos($text,">",$nextopen);
            if($nextopen<$pointer && $nextclose>$pointer){                      //We might have jumped into yet another tag!
                $pointer=$nextclose+1;                                          //Fix.
                $text = substr($text,0,$pointer)." ".substr($text,$pointer);
            }
        }else if($lastclose<$lastopen){
            $pointer = $lastclose + 1;
        }

        if(substr($text,$pointer,1)==' '){                                      //Neat, we're on a space, so just break here.
            $text=substr($text,0,$pointer)."\n".substr($text,$pointer+1);
        }else{
            $npointer=strrpos($text,' ',$pointer-strlen($text));                //Find the last position of a space...
            if($npointer!==FALSE&&$npointer>$lastfound){                        //if it's within our step, break there
                $text=substr($text,0,$npointer)."\n".substr($text,$npointer);
            }else{                                                              //else just break apart the word we're currently
                $text=substr($text,0,$pointer)."-\n".substr($text,$pointer);    //standing in.
            }
        }
    }
    return $text;
}

public static function limitLines($text,$maxlines=10){
    $pointer=0;
    while($pointer<strlen($text)&&$maxlines>0){
        $pointer=strpos($text,"\n",$pointer+1);
        if($pointer===FALSE)$pointer=strlen($text);
        $maxlines--;
    }
    
    if($pointer<strlen($text))return substr($text,0,$pointer).'...';
    else                      return $text;
}

public static function wrapAndPrint($array,$front,$end){
    if(is_array($array)){
        foreach($array as $el){
            echo($front.$el.$end);
        }
    }else echo($front.$array.$end);
}

public static function checkShitBrowser(){
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie') !== FALSE && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 9.') === FALSE){
        include(PAGEPATH.'shitbrowser.php');
        die();
    }
}

public static function getMicrotime(){
    $time = explode(' ',microtime());
    $time = $time[1]+$time[0];
    return $time;
}

public static function strnposr($haystack, $needle, $occurrence, $pos = 0) {
    return ($occurrence<2)?strpos($haystack, $needle, $pos):Toolkit::strnposr($haystack,$needle,$occurrence-1,strpos($haystack, $needle, $pos) + 1);
}

public static function generateModuleCache(){
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

public static function generateRandomString($n=5,$set=array(0=>2,1=>1,2=>0),$add=""){
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

public static function unifyNumberString($string,$n){
    while(strlen($string)<$n){
        $string='0'.$string;
    }
    return $string;
}

public static function getUserPage($user,$return=false){
    $t = '<a href="'.Toolkit::url("user",$user).'" />'.$user.'</a>';
    if(!$return)echo($t);else return $t;
}

public static function getUserAvatar($user,$file,$return=false,$size=150){
    if($file=="")$file="noguy.png";
    $t = '<a href="'.Toolkit::url("user",$user).'" class="userAvatar">
          <img src="'.AVATARPATH.$file.'" alt="" title="'.$user.'\'s avatar" style="width:'.$size.'px;height:'.$size.'px;" /></a>';
    if(!$return)echo($t);else return $t;
}

public static function getImageType($file){
    $data = getimagesize($file);
    $type="Unknown";
    switch($data[2]){
        case IMAGETYPE_GIF:$type="gif";break;
        case IMAGETYPE_JPEG:$type='jpg';break;
        case IMAGETYPE_PNG:$type='png';break;
        case IMAGETYPE_SWF:$type='swf';break;
        case IMAGETYPE_PSD:$type='psd';break;
        case IMAGETYPE_BMP:$type='bmp';break;
        case IMAGETYPE_TIFF_MM:
        case IMAGETYPE_TIFF_II:$type='tiff';break;
        case IMAGETYPE_JPC:$type='jpc';break;
        case IMAGETYPE_JP2:$type='jp2';break;
        case IMAGETYPE_JPX:$type='jpx';break;
        case IMAGETYPE_JB2:$type='jb2';break;
        case IMAGETYPE_SWC:$type='swc';break;
        case IMAGETYPE_IFF:$type='iff';break;
        case IMAGETYPE_WBMP:$type='wbmp';break;
        case IMAGETYPE_XBM:$type='xbm';break;
        case IMAGETYPE_ICO:$type='ico';break;
    }
    return $type;
}

public static function mkdir($path){
    if(!file_exists($path)){
        $oldumask = umask(0);
        if(!mkdir($path,0777,true))throw new Exception('Failed to create folder '.$path);
        umask($oldumask);
    }
}

//FROM http://stackoverflow.com/questions/3349753/php-delete-directory-with-files-in-it
public static function rmdir($dirPath) {
    if(!is_dir($dirPath)) throw new InvalidArgumentException('Not a directory!');
    if(substr($dirPath, strlen($dirPath) - 1, 1) != '/')$dirPath .= '/';
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::rmdir($file);
        } else {
            if(!unlink($file))throw new Exception('Failed to delete '.$dirPath.$file);
        }
    }
    if(!rmdir($dirPath))throw new Exception('Failed to delete '.$dirPath);
}

public static function foldersize($path){
    $io = popen('/usr/bin/du -sb '.$path, 'r');
    $size = intval(fgets($io,80));
    pclose($io);
    return $size;
}


}
?>
