<? class Neon{
public static $name="Neon";
public static $author="NexT";
public static $version=2.01;
public static $short='neon';
public static $required=array("Auth","Themes","User");
public static $hooks=array("foo");


function displayMainPage(){
    global $params;
    
    switch($params[0]){
        case 'ucp':
        case 'panel':
        case '':$this->displayControlPanelPage();break;
        case 'list':$this->displayUserlistPage();break;
        default:$this->displayUserPage($params[0]);break;
    }
}

function displayNavbar(){
    global $site,$k,$a;
    $pages=array('Panel','Options','Log','Modules','Hooks');
    ?><div id='pageNav'>
        <h1 class="sectionheader">User</h1>
        <div class="tabs">
            <? foreach($pages as $page){
                if($a->check("admin.".$page)){
                    if($page==$site)echo('<a href="'.$k->url("admin",$page).'" class="tab activated">'.$page.'</a>');
                    else            echo('<a href="'.$k->url("admin",$page).'" class="tab">'.$page.'</a>');
                }
            }if(!in_array($site, $pages))echo('<a href="'.$k->url("admin",$site).'" class="tab activated">'.$site.'</a>'); ?>
        </div>
    </div><?
}

function displayUserlistPage(){
    global $t;
    
}

function displayControlPanelPage(){
    global $t,$a,$l;
    
}

function displayUserPage($username){
    global $t,$l,$k,$a,$params,$site;
    $user = DataModel::getData("ud_users","SELECT username,displayname,filename FROM ud_users WHERE username LIKE ? OR displayname LIKE ?",array($username,$username));
    
    if($user==null){
        $t->openPage("User not found");
        $this->displayNavbar();
        echo('<div class="title" style="text-align:center;">No such user found.</div>');
    }else{
        $t->openPage($username." - Profile");
        if($params[1]=='')$site='Profile';
        $pages = array('Profile');
        $l->triggerHookSequentially('PAGEmenu',$this,$pages);
        ?><div id='pageNav'>
            <? if($user->filename==''){ $user->filename='noguy.png'; } ?>
            <img src="<?=AVATARPATH.$user->filename?>" alt="" title="<?=$user->displayname?>'s avatar" />
            <div style="display:inline-block">
                <h1 class="sectionheader"><?=$user->displayname?></h1>
                <div class="sectionsubheader"><?=$user->username?></div>
            </div>
            <div class="tabs">
                <? foreach($pages as $page){
                    if($a->check("admin.".$page)){
                        if($page==$site)echo('<a href="'.$k->url("user",$page).'" class="tab activated">'.$page.'</a>');
                        else            echo('<a href="'.$k->url("user",$page).'" class="tab">'.$page.'</a>');
                    }
                }if(!in_array($site, $pages))echo('<a href="'.$k->url("user",$site).'" class="tab activated">'.$site.'</a>'); ?>
            </div>
        </div><?
        
        if($site=='Profile')$this->displayUserProfile($username);
        else                $l->triggerHook('PAGE'.$site,$this);
    }
    
    $t->closePage();
}

function displayUserProfile($username){
    global $k,$a,$l;
    $user = DataModel::getData("ud_users","SELECT userID,displayname,`group`,`status`,`time` FROM ud_users WHERE username LIKE ?",array($username));
    $fields = DataModel::getData("ud_fields", "SELECT ud_fields.title,ud_fields.default,ud_fields.type,ud_field_values.value ".
                                              "FROM ud_fields INNER JOIN ud_field_values USING(varname) ".
                                              "WHERE ud_fields.displayed=1 AND ud_field_values.userID=? ",array($user->userID));
    
    ?><div class='bar'>
        <div class='section'>
            <?=$username?>#<?=$k->unifyNumberString(base_convert($user->userID, 10, 16),10);?><br />
            Registered on <?=$k->toDate($user->time);?>
        </div>
        <div class='section'>
            Group: <?=$user->group?><br />
            &Delta;<?=$a->generateDelta();?>
        </div>
        <? try{ $liroli = $l->loadModule('Liroli');
            $liroli->loadGroups($username);
            echo('<ul class="section dropdown">');
            foreach($liroli->groups as $group){
                echo('<li><a href="'.$k->url('group',$group->name).'"><img src="'.AVATARPATH.$group->filename.'" alt="" >'.$group->name.'</a></li>');
            }
            echo('</ul>');
        }catch(Exception $e){} //Liroli isn't present.
        ?>
    </div><?
}

}
?>