<?
class Search{
var $id;

function __construct(){
}

function displayPage($params){
    global $a,$c,$k,$p,$api,$t;
    $type="";
    switch($params[0]){
        case 'tag':
            if($params[1]=="")$t->openPage("Search by Tag");
            else $t->openPage($params[1]." - Tag Search");
            if($_POST['varsearch']==""){
                $_POST['varsearch']=$params[1];
                $_POST['varcategory']='all';
            }
            $type="tag";
            break;
        case 'module':
            if($params[1]=="")$t->openPage("Search by Module");
            else{
                if($_POST['varsearch']==""){
                    if(is_numeric($params[1]))$tempid=array_search($params[1],$c->msMID);
                    else                      $tempid=array_search($params[1],$c->msMTitle);
                    if($tempid!==FALSE){
                        $_POST['varsearch'].=' '.$c->msMID[$tempid];
                        $_POST['varcategory'][]= $c->msMID[$tempid];
                    }
                }
                $t->openPage($params[1]." - Module Search");
            }
            $type="module";
            break;
        case 'user':
            if($params[1]=="")$t->openPage("Search by User");
            else{
                if($_POST['varsearch']==""){
                    $_POST['varsearch'].=' '.$k->getUID($temp[$i]);
                    $_POST['varcategory']='all';
                }
                $t->openPage($params[1]." - User Search");
            }
            $type="user";
            break;
        default:
            if($params[0]==""){
                if($_POST['varsearch']=="")$t->openPage("Search");
                else $t->openPage($_POST['varsearch']." - Search");
            }
            break;
    }
    $this->apiCall("fullSearchBox",array('select'=>$_POST['varcategory'],'search'=>$_POST['varsearch'],'type'=>$type));

    ?><div class='se_results'><?
    if($_POST['varsearch']!=""){
        if(!$k->updateTimestamp("search",$c->o['search_timeout'])){
            echo("Please wait ".$c->o['search_timeout']." seconds between search queries.");
        }else{
            $keywords='%'.str_replace(" ", "%",trim($_POST['varsearch'])).'%';
            $prep=array();
            for($i=0;$i<count($_POST['varcategory']);$i++){
                $temp=explode("-",$_POST['varcategory'][$i]);
                if($api->exists($temp[0]))
                    $prep[$temp[0]][]=$temp[1];
            }
            if(count($prep)>0)echo("<a name='results'>Results:</a>");
            foreach($prep as $modname => $cats){
                $mod=$api->getInstance($modname);
                $ret=$mod->search($cats,$keywords,$type);
                for($i=0;$i<count($ret);$i++){
                    ?><div class='se_results_entry'>
                        <div class='se_results_entry_title'><?=$modname?>: <a href="<?=$ret[$i]['link']?>"><?=$ret[$i]['title']?></a></div>
                        by <?=$k->getUserPageById($ret[$i]['owner'])?>
                        <blockquote><?=$p->deparseAll($ret[$i]['subject'])?></blockquote>
                    </div><?
                }
            }
        }
    }
    ?></div><?
    $t->closePage();
}

function displayAdmin($params){
    switch($params[0]){
    default:
        //$SectionList[""]         = "";
        $SectionList["0"]         = "<--->";
        return $SectionList;
        break;
    }
}

function apiCall($func,$args,$security=""){
    global $c,$k,$api;
    if($security!=$c->o['API_'.strtoupper($func).'_TOKEN'])return;

    switch($func){
    case 'deluser':break;
    case 'searchBox':
        ?><form method="post" action="<?=PROOT?>search/" class="se_box"><input type="text" name="varsearch" /><input type="submit" value="Search" /></form><?
        break;
    case 'fullSearchBox':
        if(!is_array($args['select']))$args['select']=array($args['select']);
        ?><form method="post" action="<?=PROOT?>search/<?=$args['type']?>#results" class="se_full_box">
            Search:<br />
            <? $modules=explode(";",$c->o['activated_modules']);
            for($i=0;$i<count($modules);$i++){
                $mID=array_search($modules[$i],$c->msMID);
                $module=$api->getInstance($c->msMTitle[$mID]);
                $sections=$module->search("list");
                if(count($sections)>0){
                    echo("<div class='se_module'><b>".$c->msMTitle[$mID].'</b><br />');
                    for($j=0;$j<count($sections);$j++){
                        if(in_array($c->msMTitle[$mID].'-'.$sections[$j],$args['select'])||in_array('all',$args['select'])||in_array($modules[$i],$args['select']))
                                $sel="checked";else $sel="";?>
                        <input type="checkbox" value="<?=$c->msMTitle[$mID]?>-<?=$sections[$j]?>" name="varcategory[]" <?=$sel?>><?=$sections[$j]?><br />
                    <? }
                    echo("</div>");
                }
            } ?><br class="clear" />
            for <br />
            <input type="text" name="varsearch" class="se_search_field" value="<?=$args['search']?>"><br />
            <input type="submit" value="Go">
        </form><?
        break;
    }
}

function search($sections,$keywords="",$type="OR"){return array();}
}
?>