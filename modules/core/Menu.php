<? class Menu extends Module{
public static $name="Menu";
public static $author="NexT";
public static $version=0.01;
public static $short='menu';
public static $required=array("Auth");
public static $hooks=array("foo");

function buildMenu($menu){
    $links = DataModel::getData('ms_links','SELECT * FROM ms_links ORDER BY PID ASC,`order` ASC,linkID ASC');
    Toolkit::assureArray($links);
    return $this->buildMenuHelper($links,0);
}

function buildMenuHelper($links,$pid){
    global $a;
    $dat = array();
    foreach($links as $link){
        if($link->PID==$pid&&$a->check($link->auth)){
            $subdat = array();
            $subdat[]=$link->title;
            $link->link=str_replace('PROOT',PROOT,$link->link);
            $link->link=str_replace('HOST',HOST.PROOT,$link->link);
            $subdat[]=$link->link;
            $subdat[]=$link->description;
            $subdat[]=$link->style;
            $subels=$this->buildMenuHelper($links,$link->linkID);
            if(count($subels)>0)$subdat[]=$subels;
            $dat[]=$subdat;
        }
    }
    return $dat;
}

function displayPanel(){
    global $k,$a;
    ?>
    <li>Menu
    <ul class="menu">
        <? if($a->check("links.*")){ ?>
        <a href="<?=$k->url("admin","Menu/")?>"><li>Change Menu Entries</li></a><? } ?>
    </ul></li><?
}

function displayAdmin(){
    global $a;
    if($a->check('links.*')){
        if($_POST['action']=='Delete'){
            $link = DataModel::getData('ms_links','SELECT * FROM ms_links WHERE linkID=?',array($_POST['id']));
            if($link!=null){
                $link->deleteData();
                echo('<div class="success">Link deleted</div>');
            }else{
                echo('<div class="failure">No such link found.</div>');
            }
        }else
        if($_POST['action']!=''){
            $link = DataModel::getData('ms_links','SELECT * FROM ms_links WHERE linkID=?',array($_POST['id']));
            if($link==null){$link = DataModel::getHull('ms_links');$existing=false;}else{$existing=true;}
            
            $link->PID=$_POST['PID'];
            $link->order=$_POST['order'];
            $link->title=$_POST['title'];
            $link->link=$_POST['link'];
            $link->description=$_POST['desc'];
            $link->style=$_POST['style'];
            $link->auth=$_POST['auth'];
            if($existing){
                $link->saveData();
                echo('<div class="success">Link added</div>');
            }else{
                $link->insertData();
                echo('<div class="success">Link edited</div>');
            }
        }
        
        $links = DataModel::getData('ms_links','SELECT * FROM ms_links ORDER BY PID ASC,`order` ASC,linkID ASC');
        Toolkit::assureArray($links);
        
        $options = '<option value="0">-</option>';
        foreach($links as $link){
            $options.='<option value="'.$link->linkID.'">'.$link->title.'</option>';
        }
        
        ?><style>input[type="text"]{width:100px;}input[type="number"]{width:30px;}</style>
        <form class="box fullwidth" action="#" method="post">
            Parent <select name="PID"><?=$options?></select>
            <input type="text" name="title" placeholder="Title"         value="" required maxlength="32" />
            <input type="text" name="link"  placeholder="Link"          value="" maxlength="256" />
            <input type="text" name="desc"  placeholder="Description"   value="" maxlength="128" />
            <input type="text" name="style" placeholder="Style"         value="" maxlength="128" />
            <input type="text" name="auth"  placeholder="Perm String"   value="" maxlength="128" />
            <input type="submit" name="action" value="Add" />
        </form><?
        
        foreach($links as $link){
            ?><form class="box fullwidth" action="#" method="post" data-id="<?=$link->linkID?>">
                <input type="number" name="order"                           value="<?=$link->order?>" />
                Parent <select name="PID"><?=$options?></select>
                <input type="hidden" name="id"                              value="<?=$link->linkID?>" />
                <input type="text" name="title" placeholder="Title"         value="<?=$link->title?>" required maxlength="32" />
                <input type="text" name="link"  placeholder="Link"          value="<?=$link->link?>" maxlength="256" />
                <input type="text" name="desc"  placeholder="Description"   value="<?=$link->description?>" maxlength="128" />
                <input type="text" name="style" placeholder="Style"         value="<?=$link->style?>" maxlength="128" />
                <input type="text" name="auth"  placeholder="Perm String"   value="<?=$link->auth?>" maxlength="128" />
                <input type="submit" name="action" value="Edit" />
                <input type="submit" name="action" value="Delete" />
                <script>$(function(){
                    $("form[data-id='<?=$link->linkID?>'] select option[value='<?=$link->PID?>']").attr("selected","selected");
                });</script>
            </form><?
        }
    }
}

}
?>