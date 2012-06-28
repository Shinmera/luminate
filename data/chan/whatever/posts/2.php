        <? if(!defined("INIT"))include("/var/www/Luminate/config.php"); ?>
        <? global $a,$l; if($a==null)$a = $l->loadModule("Auth"); ?>
        <? if("127.0.0.1"==$_SERVER["REMOTE_ADDR"]||strpos("p",",h")===FALSE||$a->check("chan.mod.hidden")){ ?>
            <div class="post   " id="P2">                <a name="2"></a>
                <div class="postInfo">
                    <input type="checkbox" name="varposts[]" value="2" /> 
                    <a href="'.$tpath.'#2">No.</a> 
                    <a class="postReply" href="'.$tpath.'#q2" id="1">2</a> 
                    <span class="postTitle"></span> 
                    <span class="postUsername">
                                            </span>
                    <span class="postTripcode">!!Phone</span> 
                                        <span class="postTime">Thursday 01.01.1970 01:00:01</span> 
                    <span id="postType" class="">&nbsp;</span> 
                    <span class="buttons">
                                                <? if($a->check("chan.mod.purge")){ ?>                            <a class="banUser" href="/Luminate/api/chan/ban?id=2&bid=1">Ban</a>
                        <? }if($a->check("chan.mod.ban")){ ?>                            <a class="purgeUser" href="/Luminate/api/chan/purge?id=2&bid=1">Purge</a>
                        <? }if($a->check("chan.mod.search")){ ?>                            <a class="searchUser" href="/Luminate/api/chan/search?id=2&bid=1">Search</a>
                        <? }if($a->check("chan.mod.delete")){ ?>                            <a class="deletePost" href="/Luminate/api/chan/delete?id=2&bid=1">Delete</a>
                        <? }if($a->check("chan.mod.edit")){ ?>                            <a class="editPost" href="/Luminate/api/chan/edit?id=2&bid=1">Edit</a>
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
                        <? if(POST_SHORT===TRUE){ ?>                                                    <? }else{ ?>                            MEH.                        <? } ?>                    </blockquote></article>
                    <br class="clear" />
                </div>
            </div><br />
        <? } ?>        
        