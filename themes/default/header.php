<? global $c,$k; ?>
<div id="header">
    <?=$c->o['sitename']?>

    <div id="navbar">
        <a href="<?=NODOMAIN?>" >Index</a>
        <a href="<?=$k->url("login","");?>" class="flRight">Login</a>
    </div>
</div>

<div id="content">