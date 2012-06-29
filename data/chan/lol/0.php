
                <? define("POST_SHORT",TRUE); ?>                
                                    <? if(!defined("INIT"))include("/var/www/Luminate/config.php"); ?>
    <? global $a,$l,$t; if($a==null)$a = $l->loadModule("Auth"); ?>
            <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Administration - TyNET</title>
    
    <link rel='stylesheet' type='text/css' href='/Luminate/themes/chan/css/steven.css' />
<link rel='stylesheet' type='text/css' href='/Luminate/data/css/chanfront.css' />
    <link rel='stylesheet' type='text/css' href='null.css' id='dynstyle' />
    <link rel="icon" type="image/png" href="/Luminate/data/images/favicon.png" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" media="all">
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/Luminate/data/js/jquery.js"><\/script>')</script>
    <script type="text/javascript" src="/Luminate/data/js/jquery.cookie.js"></script>
    <meta http-equiv="description" content="" />
<meta http-equiv="content-language" content="en-US,en-GB,en" />
<meta http-equiv="author" content="Shinmera" />
<meta http-equiv="publisher" content="TymoonNET NexT" />
<meta http-equiv="copyright" content="2012 TymoonNET/NexT" />
<meta http-equiv="distribution" content="Global" />
<meta http-equiv="keywords" content="Tymoon;TymoonNET;NexT;TymoonNexT;Shinmera;Nicolas;Hafner;Stevenchan" />
<meta http-equiv="robots" content="ALL" />

<meta name="content-type" content="text/html; charset=UTF-8" />
<meta name="description" content="" />
<meta name="content-language" content="en-US,en-GB,en" />
<meta name="author" content="Shinmera" />
<meta name="publisher" content="TymoonNET NexT" />
<meta name="copyright" content="2012 TymoonNET/NexT" />
<meta name="distribution" content="Global" />
<meta name="keywords" content="Tymoon;TymoonNET;NexT;TymoonNexT;Shinmera;Nicolas;Hafner;Stevenchan" />
<meta name="robots" content="ALL" />
</head>
<body>        <script type="text/javascript">
        if($.cookie("chan_style")!=null)    $("#dynstyle").attr("href",$.cookie("chan_style"));
        else                                $("#dynstyle").attr("href","/Luminate/themes/chan/css/steven.css");
    </script>
        <body><div class="content">
    
             <div class="boardTitle">lol</div>

    

    <ul class="menu" id="menu">
        <li><a href="http://chan.linuz.com/" class="menulink">TyNET</a><ul>
            <ul><li style=""><a href="http://linuz.com/Luminate/">Index</a></li><li style="float:right;"><a href="http://admin.linuz.com/Luminate/">Admin</a><ul><li style=""><a href="http://admin.linuz.com/Luminate/Admin/panel">Panel</a></li><li style=""><a href="http://admin.linuz.com/Luminate/Admin/options">Options</a></li><li style=""><a href="http://admin.linuz.com/Luminate/Admin/log">Log</a></li><li style=""><a href="http://admin.linuz.com/Luminate/Admin/modules">Modules</a></li><li style=""><a href="http://admin.linuz.com/Luminate/Admin/hooks">Hooks</a></li></ul></li><li style=""><a href="http://gallery.linuz.com/Luminate/">Gallery</a><ul><li style=""><a href="http://gallery.linuz.com/Luminate/upload/">Upload</a></li><li style=""><a href="http://gallery.linuz.com/Luminate/manage/">Manage</a></li></ul></li><li style="float:right;"><a href="http://user.linuz.com/Luminate/panel">Mona</a><ul><li style=""><a href="http://user.linuz.com/Luminate/panel">Settings</a></li><li style=""><a href="http://user.linuz.com/Luminate/Mona">Profile</a></li><li style=""><a href="http://user.linuz.com/Luminate/panel/Messages">Messages (0)</a></li><li style=""><a href="http://login.linuz.com/Luminate/logout">Logout</a></li></ul></li></ul>        </ul></li>
        
        <li><a href="/Luminate/lol">lol</a></li>        
        <li class="separator2">&nbsp;</li><li><a href="#">Themes</a><ul>
            <li><a href="#" class="styleLink" id="/Luminate//themes/chan/css/turret.css">turret</a></li><li><a href="#" class="styleLink" id="/Luminate//themes/chan/css/gray.css">gray</a></li><li><a href="#" class="styleLink" id="/Luminate//themes/chan/css/colgate2.css">colgate2</a></li><li><a href="#" class="styleLink" id="/Luminate//themes/chan/css/fresh.css">fresh</a></li><li><a href="#" class="styleLink" id="/Luminate//themes/chan/css/steven.css">steven</a></li><li><a href="#" class="styleLink" id="/Luminate//themes/chan/css/space.css">space</a></li>        </ul></li>
        
        <li><a href="#" id="watchMenuButton" >Watch</a></li>
        <li><a href="/Luminate/api/chan/options" id="options" >Options</a></li>

        <? if($a->check("chan.mod")){ ?>            <li class="separator">&nbsp;</li><li><a href="http://linuz.com/Luminate/admin/">Admin</a><ul>
                <li><a href="http://admin.linuz.com/Luminate/Chan/latestposts">Latest Posts</a></li>
                <li><a href="http://admin.linuz.com/Luminate/Chan/reports">Reports (
                <? $temp=$c->getData("SELECT COUNT(ip) FROM ch_reports",array());echo($temp[0]["COUNT(ip)"]); ?> )</a>
            </ul></li>
        <? } ?>    </ul>

    
            <? if(""==$_SERVER["REMOTE_ADDR"]||strpos("t",",h")===FALSE||$a->check("chan.mod.hidden")){ ?>            <div class="postBox" id="postBox"><a name="postBox"></a>
            <form action="http://api.linuz.com/Luminate/chan/post" method="post" id="postForm" enctype="multipart/form-data">
                <div>
                    <label class="eldesc">Name/Mail</label>
                                            <input type="text" name="varname" id="varname" placeholder="name#tripcode#secure#add" />
                                        <input type="text" name="varmail" id="varmail" placeholder="email#sage#noko" />
                </div>
                <div>
                    <input type="text" name="vartitle" id="vartitle" placeholder="title" /><input type="submit" name="varsubmit" id="varsubmit" value="Post" />
                </div>
                <span id="replyto"></span>
                                        <ul class="toolbar">
                                                    <li><img title="Bold text" alt="Bold" class="icon" src="/Luminate/data/images/icons/Bold.png" tag="b{@}" /></li>
                                                    <li><img title="Italic text" alt="Italic" class="icon" src="/Luminate/data/images/icons/Italic.png" tag="i{@}" /></li>
                                                    <li><img title="Change the font colour" alt="Color" class="icon" src="/Luminate/data/images/icons/Color.png" tag="color(&#36;Choose a colour|color&#36;){@}" /></li>
                                                    <li><img title="Insert an image" alt="Image" class="icon" src="/Luminate/data/images/icons/Image.png" tag="img{@}" /></li>
                                                    <li><img title="Change the font size" alt="Size" class="icon" src="/Luminate/data/images/icons/Size.png" tag="size(&#36;Enter the font size|number&#36;){@}" /></li>
                                                    <li><img title="Insert a hyperlink" alt="Url" class="icon" src="/Luminate/data/images/icons/Url.png" tag="url(&#36;Enter the URL|url&#36;){@}" /></li>
                                            </ul>
                    <script type="text/javascript">
                        $().ready(function(){
                            $(".toolbar .icon").each(function(){
                                $(this).click(function(){
                                    insertAdv($("#fulltext"),$(this).attr("tag"));
                                });
                            });
                        });
                    </script>
                    <textarea name="vartext" id="fulltext"></textarea>
                <div>
                    <label class="eldesc">File</label>   
                    <input type="file" name="varfile" id="varfile"/>
                </div>
                <div>
                    <label class="eldesc">Options</label>
                    <input type="checkbox" name="varoptions[]" value="r" />Spoiler 
                    <input type="checkbox" name="varoptions[]" value="w" />NSFW 
                </div>
                <? if($a->check("chan.mod")){ ?>                    <div>
                        <label class="eldesc">Mod Options</label>
                        <input type="checkbox" name="varoptions[]" value="m" />Modpost 
                        <input type="checkbox" name="varoptions[]" value="h" />Hidden 
                                                    <br />
                            <label class="eldesc">Thread Options</label>
                                <input type="checkbox" name="varoptions[]" value="l" />Locked 
                                <input type="checkbox" name="varoptions[]" value="s" />Sticky 
                                <input type="checkbox" name="varoptions[]" value="e" />Autosage 
                                            </div>
                <? } ?>                <div>
                    <label class="eldesc">Password</label>
                    <input type="password" name="varpassword" class="password" id="varpass"/>
                </div>
                                <div class="tac">
                    By posting you agree to the <a href="http://pages.linuz.com/Luminate/TAC">Terms And Conditions</a>.<br />
                    Allowed Filetypes:                 </div>
                <input type="hidden" name="varboard" id="varboard" value="4" />
                <input type="hidden" name="varthread" id="varthread" value="0" />
                <div class="postResponse" id="postResponse"></div>
            </form></div>
        <? } ?>    
    <form action="http://api.linuz.com/Luminate/chan/report" method="post" id="boardForm">
    
                <input type="hidden" id="view" value="board" />
                <div class="board">
                                    </div>
                <br class="clear" />
                <div class='pager'>Pages: <span class='pager_current'>1</span> </div>
                 
                                    <input type="hidden" name="board" value="4" />
    <input type="hidden" name="folder" id="varfolder" value="lol" />
    <div class="deleteBox">
        <label>Delete:</label><input type="checkbox" name="fileonly" value="1" /> File only<br />
        <input type="password" name="password" class="password" />
        <input type="submit" name="submitter" value="Delete" />
    </div>
    <div class="reportBox">
        <label>Report:</label><br /><input type="text" name="reason" placeholder="reason" maxlength="512" autocomplete="off" />
        <input type="submit" name="submitter" value="Report" />
    </div></form>

    </div><br clear="all">
    
    <div id="threadWatch" class="threadWatch" style="display:none;float:left;position:absolute;">
        <table><thead>
            <tr>
                <th id="watchRemoveCol"></th>
                <th id="watchBoardCol">Board</th>
                <th id="watchIDCol">ID</th>
                <th id="watchPosterCol">Poster</th>
                <th id="watchTitleCol">Title</th>
                <th id="watchUnreadCol">Unread</th>
            </tr>
        </thead><tbody>
        </tbody></table>
        <a href="#" id="watchRefreshButton" class="watchButton" title="Refresh">↻</a> 
        <a href="#" id="watchReadButton" class="watchButton" title="Read All">✔</a> 
        <a href="#" id="watchClearButton" class="watchButton" title="Clear">✘</a> 
    </div>
    <div id="popup" class="popup" style="display:none;">Please wait...</div>
    <div id="previewPost" style="display:none;float:left;position:absolute;"></div>
    <img id="previewImage" style="display:none;max-width:400px;max-height:400px;float:left;position:absolute;" alt="preview"/>

    
        <? $time = explode(" ",microtime());$time = $time[1]+$time[0];$total_time = round(($time-STARTTIME),4); ?>    <div class="footer">
        &copy;2010-2012 TymoonNET/NexT <br />
        Static/Dynamic page generated in 0.0055/<?=$total_time?> seconds.<br />
        Running TyNET vVERSION    </div>
    
    <script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-13229468-2");
pageTracker._trackPageview();
} catch(err) {}</script>

<div class="jqmConfirm jqmWindow" id="confirm" style="display:none">
    <p class="jqmConfirmMsg"></p>
    <input type="submit" value="No" />
    <input type="submit" value="Yes" />
</div>
<div id="proot" style="display:none;">/Luminate/</div>
<div id="apiurl" style="display:none;">/Luminate/api/</div>

<script type="text/javascript" src="/Luminate/data/js/js.js" ></script>
<script type='text/javascript' src='/Luminate/themes/chan/main.js' ></script>
<script type='text/javascript' src='/Luminate/data/js/js.js' ></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" ></script>
<script type="text/javascript" src="/Luminate/data/js/plugins.js" ></script>
</body>
</html>        
    
                