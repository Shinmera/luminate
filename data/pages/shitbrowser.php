<? include("/var/www/TyNET/config.php");
global $c; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>You're using a shitty browser - <?=$c->o['sitename']?></title>
<link rel='stylesheet' type='text/css' href='<?=THEMEPATH?>default/shitbrowser.css' />
</head>
<body>
<div class="header"><?=$c->o['sitename']?></div>
<div class="content">
    <div class="title">Whoops!</div>
    <div class="subtitle">Looks like you're using an old browser.</div>
    <hr /><br />
    Old browsers such as yours become an increasing problem for web designers and the browser users themselves too.<br />
    <br />
    <b>For you</b>, they cause a lot of problems because they become more and more incapable of displaying newer webpages with advanced technologies
    and they leave you open for attacks because they are often insecure and leave a lot of security holes open.<br />
    <br />
    <b>For the webdesigners</b>, old browsers are a problem because they force them to take long ways around the limitations,
    just to make the site work for outdated technology too.<br />
    <br />
    <b>However</b>, since we think that supporting outdated software that is only harmful to the producer <i>and</i> the user is bogus,
    we'll simply inform you here that you can easily update your browser to a new, up-to-date version that will bring you a better experience
    and will make our lives easier.<br />
    And the best part is: It only takes <i>a few minutes</i> to update.<br />
    <br />
    <b>Everybody wins!</b><br />
    <br />
    <div class="browsers">
    We support these great browsers:<br />
    <div class="browser">
        <a href="http://google.com/chrome">
        <div class="browsericon chrome"></div>
        <br />Google
        <br />Chrome</a>
    </div>
    <div class="browser">
        <a href="http://mozilla.org/en-US/firefox/new">
        <div class="browsericon firefox"></div>
        <br /> Mozilla
        <br />Firefox</a>
    </div>
    <div class="browser">
        <a href="http://opera.com">
        <div class="browsericon opera"></div>
        <br />
        <br />Opera</a>
    </div>
    <div class="browser">
        <a href="http://beautyoftheweb.com">
        <div class="browsericon ie"></div>
        <br />Internet
        <br />Explorer 9</a>
    </div>
    <div class="browser">
        <a href="http://apple.com/safari">
        <div class="browsericon safari"></div>
        <br />Apple
        <br />Safari</a>
    </div>
    </div>
</div>

<div class="footer">
&copy;2010-<?=date("Y")?> TymoonNET/NexT, All rights reserved.<br />
Running TyNET v<?=CORE::$version?>.
</div>

</body>
</html>
