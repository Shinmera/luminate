        <? if(!defined("INIT"))include("/var/www/Luminate/config.php"); ?>
        <? global $a,$l; if($a==null)$a = $l->loadModule("Auth"); ?>
        <? if("127.0.0.1"==$_SERVER["REMOTE_ADDR"]||strpos("p",",h")===FALSE||$a->check("chan.mod.hidden")){ ?>
            <div class="post   " id="P1">                <a name="1"></a>
                <div class="postInfo">
                    <input type="checkbox" name="varposts[]" value="1" /> 
                    <a href="/Luminate/whatever/thread/1.php#1">No.</a> 
                    <a class="postReply" href="/Luminate/whatever/thread/1.php#q1" id="1">1</a> 
                    <span class="postTitle">Whatever</span> 
                    <span class="postUsername">
                        <a href="mailto:lol@nope.avi">Anonymous</a>                    </span>
                    <span class="postTripcode">!Nope</span> 
                                        <span class="postTime">Thursday 01.01.1970 01:00:01</span> 
                    <span id="postType" class="">&nbsp;</span> 
                    <span class="buttons">
                                                <? if($a->check("chan.mod.purge")){ ?>                            <a class="banUser" href="/Luminate/api/chan/ban?id=1&bid=1">Ban</a>
                        <? }if($a->check("chan.mod.ban")){ ?>                            <a class="purgeUser" href="/Luminate/api/chan/purge?id=1&bid=1">Purge</a>
                        <? }if($a->check("chan.mod.search")){ ?>                            <a class="searchUser" href="/Luminate/api/chan/search?id=1&bid=1">Search</a>
                        <? }if($a->check("chan.mod.delete")){ ?>                            <a class="deletePost" href="/Luminate/api/chan/delete?id=1&bid=1">Delete</a>
                        <? }if($a->check("chan.mod.edit")){ ?>                            <a class="editPost" href="/Luminate/api/chan/edit?id=1&bid=1">Edit</a>
                        <? } ?>                    </span> <br />
                                            <span class="fileName">File </span> 
                        (<a class="fileLink" href="whatever/files/none.png">none.png</a>) 
                        <span class="fileSize">NaN</span> 
                        <span class="fileDimensions"></span> 
                                    </div><div class="postContent">
                                            <a class="postImageLink" title="" href="whatever/files/none.png">
                            <img class="postImage" alt="" src="whatever/thumbs/none.png" border="0">
                        </a>
                                        <article><blockquote>
                        <? if(POST_SHORT===TRUE){ ?>                                                    <? }else{ ?>                            <strong class="" style=""  >fuck</strong> y'all niggas.                        <? } ?>                    </blockquote></article>
                    <br class="clear" />
                </div>
            </div><br />
        <? } ?>        
        