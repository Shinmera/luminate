<? global $a,$c,$k,$p,$s,$t,$api; ?>
<body>
<div class="header">
<? 
$c->loadHeaders();
$header = $c->msHFile[mt_rand(0,count($c->msHFile)-1)]; 
$headline=explode(";",$c->o['headlines']);$headline=$headline[mt_rand(0,count($headline)-1)];?>

<div class='headerImg'><a href='/'><img class='headerImg' src='<?=HEADERPATH.$header?>' alt="header image" /></a></div>
<div class='headline'></div>
<div class='headlineText'><?=$headline?></div>
</div><br class="clear" />

<div class="menuBarBG">
<ul class="menu" id="menu">
<li style='border-right: 2px solid #0000c4;'><a href="/" class="menulink" style="max-width:50px;">Index</a></li>
<? $t->printMenu(); ?>

<li style="float:right;border-left: 2px solid #0000c4;"><a href="/rss" class="menulink" style="text-align:right; max-width:20px; min-height:20px" >
<img border="0" alt="RSS" style="float:right;margin-top: -3px;margin-right:-5px;" title="View RSS Feeds" src="<?=$t->img?>/rss.png" /></a></li>
<?
if($c->uID!=-1){
        $msgs=$api->call("UCP","getMessageCount",array('box' => 'inbox', 'arguments' => "AND arguments ='unread'"));
        if($msgs>0)$msgs='NewMsg';else $msgs="";
    echo('<li style="float:right"><a style="text-align:right" href="/user" class="menulink'.$msgs.'">'.$c->udUName[$c->uID].'</a><ul>
          <li><a style="text-align:right" href="/user/'.$c->username.'" class="menulink">My Profile</a></li>');
    if($a->check('user.profile.messages'))echo('<li><a style="text-align:right;" href="/user/messages" class="menulink'.$msgs.'">Messages</a></li>');
    if($a->check('user.profile'))echo('<li><a style="text-align:right" href="/user/" class="menulink">Control Panel</a></li>');
    if($a->check('admin.panel'))echo('<li><a style="text-align:right" href="/admin" class="menulink">Admin Panel</a></li>');
    echo('<li><a style="text-align:right" href="/user/logout" class="menulink">Logout</a></li></ul></li>');
}else{
    echo('<li  style="float:right"><a style="text-align:right" href="/user" class="menulink">Login</a><ul>
    <li><a style="text-align:right" href="/user/list" class="menulink">Userlist</a></li></ul></li>');
}
?>

</ul></div>
<div style="background-image:url('<?=$t->img?>/menu_lower.png');background-repeat:repeat-x;background-color:#FFF;width:100%;height:10px;"></div>
<? if(count($t->menudata)>0)echo('<ul class="menulinks">'.implode($t->menudata).'</ul>'); ?>

<script type="text/javascript">
    var menu=new menu.dd("menu");
    menu.init("menu","menuhover");
</script>

<div class="searchBox">
<? $api->call("Search","searchBox",array()); ?></div>

<div class="container">