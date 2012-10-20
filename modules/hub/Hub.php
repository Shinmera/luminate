<? class Hub{
public static $name="Module";
public static $author="NexT";
public static $version=0.01;
public static $short='hub';
public static $required=array("Auth","Themes");
public static $hooks=array("foo");

function displayHead(){
    global $a,$params;
    ?><div id='pageNav'>
        <div style='display:inline-block'>
            <a name="blog" href="<?=PROOT?>" >
                <h1 class='sectionheader'>Hub</h1>
            </a>
        </div>
        <div class='tabs'>
            <a href='<?=PROOT?>project/' class='tab <? if($params[0]==''||$params[0]=='project')echo('activated'); ?>'>Project</a>
            <a href='<?=PROOT?>ticket/' class='tab <? if($params[0]=='ticket')echo('activated'); ?>'>Ticket</a>
            <? if($a->check('hub.dashboard')){ ?>
                <a href='<?=PROOT?>/dashboard' class='tab <? if($params[0]=='dashboard')echo('activated'); ?>'>Dashboard</a>
            <? } ?>
        </div>
    </div><?
}

function displayPage(){
    global $t;
    $t->openPage("Hub");
    $this->displayHead();

    $t->closePage();
}
}
?>