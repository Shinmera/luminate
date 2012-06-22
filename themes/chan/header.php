<? global $c,$boards,$k,$t;
if(!class_exists("ChanDataBoard"))include(TROOT.'modules/chan/data.php');
$id=$c->msMID[array_search("Chan",$c->msMTitle)];
?>
<script type="text/javascript">
    if($.cookie("chan_style")!=null)
        $("#dynstyle").attr("href",$.cookie("chan_style"));
    else $("#dynstyle").attr("href","/themes/<?=$t->name?>/css/steven.css");
</script>;
<div class="frontHeader"><?=$c->o['chan_title']?></div>
<ul class="frontMenu" id="menu"><?
$boards = ChanDataBoard::loadFromDB("SELECT boardID,folder,PID,title FROM ch_boards WHERE options NOT REGEXP ? AND boardID>0",array(',h'));
$c->loadCategories($id);
for($i=0;$i<count($c->msCID);$i++){
    $list=explode(";",$c->msCSubject[$i]);
    echo('<li class="separator">&nbsp;</li>');
    for($j=0;$j<count($list);$j++){
        for($n=0;$n<count($boards);$n++){
        if($boards[$n]->boardID==$list[$j]){
            echo('<li><a href="'.$k->url("chan","/".$boards[$n]->folder).'" title="'.$boards[$n]->title.'">'.$boards[$n]->folder.'</a></li>');
        }}
    }
}
?></ul>
<div class="frontContent">
    
