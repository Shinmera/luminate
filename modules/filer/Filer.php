<? class Filer extends Module{
public static $name="Filer";
public static $author="NexT";
public static $version=0.01;
public static $short='filer';
public static $required=array("Auth");
public static $hooks=array("foo");

function __construct(){}

function displayAPI(){
    global $params;
    switch($params[1]){
        case 'upload':$this->displayUpload();break;
        default: $this->displayFiler();break;
    }
}

function displayUpload(){
    global $a,$c;
    if(!$a->check("filer.upload"))die('Insufficient privileges!');
    $file = DataModel::getHull('fi_files');
    try{
        $file->owner=$a->user->username;
        $file->time=time();
        $file->filename=Toolkit::sanitizeFilename($_FILES['file']['name']);
        $file->insertData();
        $file->fileID=$c->insertID();
        
        Toolkit::mkdir(ROOT.DATAPATH.'uploads/filer/');
        $path = Toolkit::uploadFile('file',ROOT.DATAPATH.'uploads/filer/', 5000, array(), false, $file->fileID.'-'.$file->filename, false);
        
        die('{"name":"'.$file->filename.'","size":'.filesize($path).',"url":"'.Toolkit::url('www',str_replace(ROOT.PROOT,'',$path)).'"}');
    }catch(Exception $ex){
        $file->deleteData();
        die($ex->getMessage());
    }
}

function displayFiler(){
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="<?=DATAPATH?>js/jquery.form.js"></script>
    <style>
        #filer{background: #AAA;padding:3px; border-radius: 5px;}
        #result{background: #EEE;padding:2px;border-radius: 3px;}
    </style>
    <form id="filer" action="<?=PROOT?>api/filer/upload" method="post" enctype="multipart/form-data">
        <div id="result"></div>
        <input type="file" name="file" id="file" /><input type="submit" name="action" value="Upload" class="start" />
    </form>
    <script>
    $(function () {
        $("#filer").ajaxForm({
            dataType: 'json',
            success: function(data){
                $("#result").append('<a href="'+data.url+'">'+data.url+"</a><br />");
            },
            error: function(data){
                $("#result").html('Error during upload: '.data);
            }
        });
    });
    </script><?
}

function displayPopupFiler(){
    ?><a href="<?=PROOT?>api/filer/" class="button filerButton" id="filerButton">Upload Files</a>
    <div class="" id="filerJQM" style="display: none;position: fixed;top: 17%;left: 50%;margin-left: -300px;width: 600px;" ></div>
    <script type="text/javascript">
        $(function(){
            $("#filerJQM").jqm({
                ajax: '@href',
                trigger: "#filerButton",
                onLoad: function(){eval($('#filerJQM script').html());}
            });
        });
    </script><?
}

function displayPanel(){
    global $k,$a;
    ?>
    <li>Filer
    <ul class="menu">
        <? if($a->check("filer.upload")){ ?>
        <a href="<?=$k->url("admin","Filer/")?>"><li>Manage Uploaded Files</li></a><? } ?>
    </ul></li><?
}
function displayAdmin(){
    if($_POST['action']=='Delete'){
        $file = DataModel::getData('fi_files', 'SELECT * FROM fi_files WHERE fileID=?',array($_POST['id']));
        if($file==null)echo('<div class="failure">File not found in DB.</div>');
        else{
            if(!file_exists(ROOT.DATAPATH.'uploads/filer/'.$file->fileID.'-'.$file->filename))echo('<div class="failure">File not found on disk.</div>');
            else if(!unlink(ROOT.DATAPATH.'uploads/filer/'.$file->fileID.'-'.$file->filename))echo('<div class="failure">Failed to delete file!</div>');
            else{
                $file->deleteData();
                echo('<div class="success">File deleted!</div>');
            }
        }
    }
    
    $max = DataModel::getData('fi_files','SELECT COUNT(fileID) AS max FROM fi_files');
    Toolkit::sanitizePager($max->max,array('fileID','filename','owner','time'),'time');
    $files = DataModel::getData('fi_files','SELECT * FROM fi_files ORDER BY time DESC LIMIT '.$_GET['f'].','.$_GET['s']);
    Toolkit::assureArray($files);
    
    ?><div class="box fullwidth">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;"><a href="?o=fileID&a=<?=!$_GET['a']?>">fileID</a></th>
                    <th><a href="?o=filename&a=<?=!$_GET['a']?>">Filename</a></th>
                    <th style="width:100px;"><a href="?o=owner&a=<?=!$_GET['a']?>">Owner</a></th>
                    <th style="width:200px;"><a href="?o=time&a=<?=!$_GET['a']?>">Time</a></th>
                    <th style="width:50px;"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($files as $file){ ?>
                    <tr>
                        <td><?=$file->fileID?></td>
                        <td><a href="<?=DATAPATH.'uploads/filer/'.$file->fileID.'-'.$file->filename?>"><?=$file->filename?></a></td>
                        <td><?=Toolkit::getUserPage($file->owner)?></td>
                        <td><?=Toolkit::toDate($file->time)?></td>
                        <td><form method="post">
                                <input type="hidden" name="id" value="<?=$file->fileID?>" />
                                <input type="submit" name="action" value="Delete" />
                            </form></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    </div><?
}

}
?>