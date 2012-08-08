var anchor = document.location.hash.substring(1);
var focused = window;
var post_ids = "";
var origtitle = document.title;
var fetch = 10;
//update u preview p enlarge e scroll s hidden h quote q watched w fixed postbox f video hiding v auto-watch a debug b
//bcdgijklmnoqrtxyz
var options = 'upeshq';
var cssoptions = {"postbox":    {"draggable": true,"resizable": true},
                  "threadwatch":{"draggable": true,"resizable": true},
                  "post":       {"filenamelimit":false}};

function isScrollBottom() { 
    var documentHeight = $(document).height(); 
    var scrollPosition = $(window).height() + $(window).scrollTop(); 
    return (scrollPosition+5 >= documentHeight);
}

function updateThread(){
    if(options.indexOf('b')!=-1){console.log('[PREFETCH] Performing update...');}
    $.ajax({
        url: "?a=postlist",
        success: function(data){
            data=data.trim();
            if(options.indexOf('b')!=-1){console.log('[PREFETCH] Old data: '+post_ids);}
            if(options.indexOf('b')!=-1){console.log('[PREFETCH] New data: '+data);}
            if(data!=post_ids){
                if(post_ids!=""){
                    curposts = post_ids.split(";");
                    allposts = data.split(";");
                    post_ids=data;
                    addMissingPosts(curposts.length-allposts.length);
                    document.title = "("+(allposts.length-curposts.length)+") "+origtitle;
                }else{
                    post_ids=data;
                }
            }
        }
    });
}

function addMissingPosts(n){
    if(options.indexOf('b')!=-1){console.log('[PREFETCH] Loading '+n+' posts...');}
    var allposts = post_ids.split(";"),posts = {};
    var i=0,v=0,m=n,id=0;
    
    if(n>0){
        id=$(".thread .post:last-child").data("postid");
        i=allposts.indexOf(id+"")+1;
        v=i+n;
        if(v>allposts.length){n=allposts.length-i;v=allposts.length;}
    }else{n*=-1;
        id=$(".thread .post:first-child").data("postid");
        v=allposts.indexOf(id+"");
        i=v-n;
        if(i<0){i=0;n=v;}
    }
    if(options.indexOf('b')!=-1){console.log('[PREFETCH] DBG: I:'+i+' V:'+v+' M:'+m);}
    for(;i<v;i++){
        $.ajax({
            url: $("#proot").html()+"data/chan/"+$("#varfolder").val()+"/posts/"+allposts[i]+".php",
            success: function(post){
                $post = $(post);
                customizePost($post);
                posts[$post.data("postid")] = $post;
                if(options.indexOf('b')!=-1){console.log('[PREFETCH] Received post '+$post.data("postid"));}
                if(Object.size(posts)==n){
                    addMissingPostsHelper(posts,m);
                }
            }
        });
    }
}
function addMissingPostsHelper(posts,m){
    var allposts = post_ids.split(";");
    var $first = $(".thread .post:first-child");
    for(var id in posts){
        if(allposts.indexOf(id)!=-1&&$("#P"+id).length==0){
            if(options.indexOf('b')!=-1){console.log('[PREFETCH] Adding post '+id);}
            post = posts[id];
            $(post).fadeIn();
            if(m>0)$(".thread").append($(post));
            else   $(post).insertBefore($first);
        }
    }
}

function addWatchedThread(board,id){
    if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Adding '+board+'/'+id);}
    var pcook = "";
    if($.cookie('chan2_watched')!=null)pcook=$.cookie('chan2_watched');
    var watched = pcook.split(";");
    if(watched.length==20)return false;

    for(var i=0;i<watched.length;i++){
        if(watched[i].indexOf(board+" "+id)!= -1){
            return false;
        }
    }
    $.ajax({
        url: $("#proot").html()+'api/time',
        success: function(html){
            $.cookie('chan2_watched',pcook+";"+board+" "+id+" "+html,{ expires: 356, path: '/' });
            refreshWatched();
        }
    });
    return true;
}
function delWatchedThread(board,id){
    if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Deleting '+board+'/'+id);}
    var watched = [];
    if($.cookie('chan2_watched')!=null)watched=$.cookie('chan2_watched').split(";");
    if(watched.length==0)return false;

    for(var i=0;i<watched.length;i++){
        if(watched[i].indexOf(board+" "+id)!= -1){
            watched.splice(i, 1);
            break;
        }
    }
    $.cookie('chan2_watched',implode(";",watched),{ expires: 356, path: '/' });
    refreshWatched();
    return true;
}
function setThreadRead(board,id){
    if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Setting '+board+'/'+id+' as read');}
    var watched = [];
    if($.cookie('chan2_watched')!=null)watched=$.cookie('chan2_watched').split(";");
    if(watched.length==0)return false;

    $.ajax({
        url: $("#proot").html()+'api/time',
        success: function(html){
            for(var i=0;i<watched.length;i++){
                if(watched[i].indexOf(board+" "+id)!= -1){
                    watched[i]=board+" "+id+" "+html;
                    break;
                }
            }
            $.cookie('chan2_watched',implode(";",watched),{ expires: 356, path: '/' });
            refreshWatched();
        }
    });
    return true;
}
function readWatched(){
    if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Setting watched threads as read');}
    var watched = [];
    if($.cookie('chan2_watched')!=null)watched=$.cookie('chan2_watched').split(";");
    if(watched.length==0)return false;

    $.ajax({
        url: $("#proot").html()+'api/time',
        success: function(html){
            for(var i=0;i<watched.length;i++){
                var temp = watched[i].split(" ");
                watched[i]=temp[0]+" "+temp[1]+" "+html;
                break;
            }
            $.cookie('chan2_watched',implode(";",watched),{ expires: 356, path: '/' });
            refreshWatched();
        }
    });
    return true;
}
function clearWatched(){
    if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Clearing watched threads');}
    $.cookie('chan2_watched','',{ expires: 356, path: '/' });
    refreshWatched();
}
function refreshWatched(){
    if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Refreshing watched threads...');}
    $.ajax({
        url: $("#proot").html()+'api/chan/watch',
        success: function(xml){
            if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Received: '+xml);}
            $("#threadWatch table tbody").html(xml);
            $(".watchDeleteButton").each(function(){
                $(this).unbind('click');
                $(this).click(function(){
                    delWatchedThread($(this).attr("board"),$(this).attr("id"));
                    return false;
                });
            });
        }
    });
}
function registerAutoWatch(){
    if(options.indexOf('b')!=-1){console.log('[CUSTOM] Registering auto-watch');}
    $("#varsubmit").click(function(){
        addWatchedThread($("#varboard"),$("#varthread"));
    });
}

function hideThread(id){
    var threads = new Array();
    if($.cookie('chan2_thread_hidden')!=null&&$.cookie('chan2_thread_hidden')!=''){
        threads=$.cookie('chan2_thread_hidden').split(",");
        if(threads.indexOf(id)!==-1){
            if(options.indexOf('b')!=-1){console.log('[CUSTOM] Unhiding thread '+id);}
            removeA(threads,id);
            $("#P"+id+" .postContent").slideDown();
            $("#T"+id).slideDown();
        }else{
            if(options.indexOf('b')!=-1){console.log('[CUSTOM] Hiding thread '+id);}
            threads.push(id);
            $("#P"+id+" .postContent").slideUp();
            $("#T"+id).slideUp();
        }
    }else{
        if(options.indexOf('b')!=-1){console.log('[CUSTOM] Hiding thread '+id);}
        threads.push(id);
        $("#P"+id+" .postContent").slideUp();
        $("#T"+id).slideUp();
    }
    $.cookie('chan2_thread_hidden',implode(',',threads),{ expires: 356, path: '/' });
}
function hideThreads(){
    if(options.indexOf('b')!=-1){console.log('[CUSTOM] Hiding threads');}
    if($.cookie('chan2_thread_hidden')!=null){
        var threads = $.cookie('chan2_thread_hidden').split(",");
        for(var i=0;i<threads.length;i++){
            $("#P"+threads[i]+" .postContent").slideUp();
            $("#T"+threads[i]).slideUp();
        }
    }
}

function setFields(){
    if(options.indexOf('b')!=-1){console.log('[BASE] Setting fields');}
    $(".password").each(function(){
        if($.cookie('chan2_post_pw')==null){
            $(this).val(randomstring(15));
        }
        else $(this).val($.cookie('chan2_post_pw'));
    });
    if($.cookie('chan2_post_name')!=null)$("#varname").val($.cookie('chan2_post_name'));
    if($.cookie('chan2_post_mail')!=null)$("#varmail").val($.cookie('chan2_post_mail'));
}

function registerButtons(){
    if(options.indexOf('b')!=-1){console.log('[BASE] Registering button actions');}
    $(".hideThread").each(function(){
        $(this).click(function(){
            hideThread($(this).attr("id"));
            return false;
        });
    });
    $("#watchMenuButton").each(function(){
        $(this).click(function(){
            if($("#threadWatch").css("display")=="none"){
                $("#threadWatch").stop(true, true).fadeIn();
                refreshWatched();
            }else $("#threadWatch").stop(true, true).fadeOut();
            return false;
        });
    });
    $("#watchRefreshButton").click(function(){refreshWatched();return false;});
    $("#watchReadButton").click(function(){readWatched();return false;});
    $("#watchClearButton").click(function(){clearWatched();return false;});
    $(".watchThread").each(function(){
        $(this).click(function(){
            addWatchedThread($("#varboard").attr("value"),$(this).attr("id"));
            $("#threadWatch").fadeIn();
            return false;
        });
    });
    
    $(".postOP .postInfo .buttons a").each(function(){
        $(this).click(function(){return false;});
    });
    $('#popup').jqm({ajax: '@href', 
                     trigger: '.moveThread, .mergeThread, .banUser, .purgeUser, .searchUser, .deletePost, .editPost, #options',
                     onLoad: function(){
                         eval($('#popup script').html());
                     }});
                 
    $('.fetchPrevious').click(function(){
        var $self=$(this);
        $self.html('Fetching posts...');
        $.ajax({
            url: "?a=postlist",
            success: function(data){
                post_ids=data.trim();
                addMissingPosts(-1*fetch);
                $self.attr("amount",$self.attr("amount")-fetch);
                if($self.attr("amount")>=fetch) $self.html("Fetch previous "+fetch+"/"+$self.attr("amount"));
                else if($self.attr("amount")>0) $self.html("Fetch previous "+$self.attr("amount"));
                else                            $self.remove();
        }});
        return false;
    });
    $('.fetchNext').click(function(){
        var $self=$(this);
        $self.html('Fetching posts...');
        $.ajax({
            url: "?a=postlist",
            success: function(data){
                post_ids=data.trim();
                addMissingPosts(fetch);
                $self.attr("amount",$self.attr("amount")-fetch);
                if($self.attr("amount")>=fetch) $self.html("Fetch next "+fetch+"/"+$self.attr("amount"));
                else if($self.attr("amount")>0) $self.html("Fetch next "+$self.attr("amount"));
                else                            $self.remove();
        }});
        return false;
    });
}

function customizePost(post){
    if(options.indexOf('b')!=-1){console.log('[POST] Customizing '+post.data('postid'));}
    if(options.indexOf('q')!=-1){
        if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Adding directquote hovering');}
        $(".directQuote",post).each(function(){
            $(this).unbind('hover');
            $(this).hover(function(e){
                $("#previewPost").stop(true, true).fadeOut(1);
                $("#previewPost").html("");
                $("#previewPost").css({"left":(e.pageX+10)+"px","top":(e.pageY+10)+"px"});
                if($(this).attr("board")==null)return;
                if($(this).attr("board").trim()==boardName.trim()&&$("#P"+$(this).attr("id")).length!=0){
                    if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Found post data in DOM');}
                    $("#previewPost").html($("#P"+$(this).attr("id"))[0].outerHTML);
                    $("#previewPost").stop(true, true).fadeIn();
                }else{
                    if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Attempting to fetch post...');}
                    var board=$(this).attr("board");
                    var id=$(this).attr("id");
                    $.ajax({
                        url: $("#proot").html()+'data/chan/'+board+"/posts/"+id+".php",
                        success: function(data) {
                            if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Got post data');}
                            $("#previewPost").html(data);
                            $("#previewPost").stop(true, true).fadeIn();
                        },
                        error: function() {
                            if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Attempting to fetch deleted post...');}
                            $.ajax({
                                url: $("#proot").html()+'data/chan/'+board+"/posts/_"+id+".php",
                                success: function(data) {
                                    if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Got deleted post data');}
                                    $("#previewPost").html(data);
                                    $("#previewPost").stop(true, true).fadeIn();
                                },
                                error: function(){
                                    if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] No success! ');}
                                }
                            });
                        }
                    });
                }
            },function(e){
                $("#previewPost").stop(true, true).fadeOut(200);
            });
        });
    }
    
    if(options.indexOf('s')!=-1){
        if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Adding post scrolling');}
        $('a[href]',post).click(function(){
            var id = this.hash.replace('#','');
            var real = false;
            $('#P'+id).each(function(){
                if($(this).parent().attr("id")!="previewPost"){
                    $(".post,.postOP").removeClass('selected');
                    $('#P'+id).addClass('selected');
                    $('html,body').animate({scrollTop: $('#P'+id).offset().top-40},200);
                    real=true;
                }
            });
            if($(this).attr('board')!==$("#varfolder").val())real=false;
            if(real&&!$(this).hasClass('direct'))return false;
            else                                 return true;
        });
    }
    
    $(".postReply",post).click(function(){
        if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Adding post reply conveniences');}
        var pos = $(this).parent().offset();
        var width = $(this).parent().width();
        var left = (pos.left+width);
        if (left>$(window).width()-$("#postBox").width()-40)left=$(window).width()-$("#postBox").width()-40;
        $("#fulltext").focus();
        $("#fulltext").val($("#fulltext").val()+">>"+$(this).html()+"\n");
        $("#replyto").html("Reply to "+$(this).attr("id"));
        $("#varthread").attr("value",$(this).attr("id"));
        if(cssoptions.postbox.draggable)
            $("#postBox").css({"left":left+"px","top":(pos.top+40)+"px"});
        window.scrollTo(0,pos.top-40);
        return false;
    });
    
    if(options.indexOf('p')!=-1){
        if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Adding image hovering');}
        $(".postImage",post).mousemove(function(e){
            var newpath=$(this).attr("src").replace("thumbs","files");
            if(newpath!=$(this).attr("src")){
                $("#previewImage").attr("src",$(this).attr("src").replace("thumbs","files"));
                $("#previewImage").css({"left":(e.pageX+10)+"px"});
                if(($(window).scrollTop()+$(window).height())>(e.pageY+10+$("#previewImage").height()))
                    $("#previewImage").css({"top":(e.pageY+10)+"px"});
                else
                    $("#previewImage").css({"top":($(window).scrollTop()+$(window).height()-$("#previewImage").height())+"px"});
                $("#previewImage").stop(true, true).fadeIn();
            }
        }).mouseout(function(e){
            $("#previewImage").stop(true, true).fadeOut();
        });
    }
    
    if(options.indexOf('e')!=-1){
        if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Adding image resizing');}
        $(".postImageLink",post).click(function(){
            var pic = $(this).children();
            if(pic.attr("src").indexOf("thumbs")!=-1){
                pic.attr("src",pic.attr("src").replace("thumbs","files"));
                $("#previewImage").stop(true, true).fadeOut();
            }else pic.attr("src",pic.attr("src").replace("files","thumbs"));
            return false;
        });
    }
    
    if(cssoptions.post.filenamelimit){
        if(options.indexOf('b')!=-1){console.log('[POST]['+post.data('postid')+'] Adding filename expanding');}
        $(".fileName",post).mouseenter(function(){$(this).stop(true,true).animate({maxWidth:"500px"},500);})
                           .mouseleave(function(){$(this).stop(true,true).animate({maxWidth:"100px"},500);});
    }
}

function animateMenu(){
    if(options.indexOf('b')!=-1){console.log('[BASE] Animating menu');}
    $("#menu").supersubs({ 
        minWidth:    12,   // minimum width of sub-menus in em units 
        maxWidth:    27,   // maximum width of sub-menus in em units 
        extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                           // due to slight rounding differences and font-family 
        }).superfish({
            pathClass:  'current' ,
            animation:   {height:'show'}
        });          // call supersubs first, then superfish, so that subs are 
                     // not display:none when measuring. Call before initialising 
                     // containing tabs for same reason. 
}

function registerShortcuts(){
    if(options.indexOf('b')!=-1){console.log('[BASE] Registering shortcuts');}
    $(window).keydown(function(e){
        var key = String.fromCharCode(e.which);
        if (e.which == 113){
            $("#postBox").css({"top":(window.scrollY+40)+"px"});
            $("#fulltext").focus();
            e.preventDefault();
        }
    });
}

function registerAutoUpdate(){
    if(options.indexOf('b')!=-1){console.log('[CUSTOM] Registering auto update');}
    if($("#view").val()=="thread"){
        $(".thread").everyTime(10000,function(){
            updateThread();
        });
        $(window).scroll(function(){ 
            if(isScrollBottom()){ 
                checked_ids=post_ids;
                document.title=origtitle;
            } 
        }); 
    }
}

function registerThreadRead(){
    if(options.indexOf('b')!=-1){console.log('[BASE] Registering thread as read');}
    if($("#varthread").val()!=""){
        setThreadRead($("#varboard").val(),$("#varthread").val());
    }
}

function hideVideos(){
    if(options.indexOf('b')!=-1){console.log('[CUSTOM] Hiding videos');}
    $("iframe.youtube-player").each(function(){
        var src = $(this).attr("src");
        url = src.replace('embed/','watch?v=');
        $('<a href="'+url+'" title="'+url+'">Watch Youtube Video</a>').insertBefore(this);
        $(this).remove();
    });
}

$(function(){
    if(options.indexOf('b')!=-1){console.log("[INIT] THEME");}
    
    if(isMobile.any()&&$.cookie("chan2_style")==null)
        $("#dynstyle").attr("href",$("#proot").html()+"themes/chan/css/mobile.css");
    if($.cookie("chan2_options")!=null){options=$.cookie('chan2_options');}
    else $.cookie('chan2_options',options,{ expires: 356, path: '/' });
    
    $(".styleLink").click(function(){
        $("#dynstyle").attr("href",$("#proot").html()+'themes/chan/css/'+$(this).attr("id"));
        $.cookie("chan2_style",$(this).attr("id"),{ expires: 356, path: '/' });
    });
    
    if(options.indexOf('b')!=-1){console.log("[INIT] BASE");}
    setFields();
    animateMenu();
    registerButtons();
    registerShortcuts();
    registerThreadRead();
    
    if(options.indexOf('b')!=-1){console.log("[INIT] CUSTOM");}
    if(options.indexOf('h')!=-1){hideThreads();}
    if(options.indexOf('u')!=-1){registerAutoUpdate();}
    if(options.indexOf('v')!=-1){hideVideos();}
    if(options.indexOf('w')!=-1){$("#threadWatch").fadeIn();}
    if(options.indexOf('a')!=-1){registerAutoWatch();}
    
    if(options.indexOf('b')!=-1){console.log("[INIT] CSS");}
    if($("options").length>0){
        var opts = $("options").css('content').replace(/"/g,'').replace(/\\'/g,'"').replace(/'/g,'');
        if($("options").css('content').length>5){
            if(options.indexOf('b')!=-1){console.log('[CSS] Attempting to parse OPTS: '+opts);}
            jQuery.extend(cssoptions,$.parseJSON(opts));
        }
    }
    
    if(options.indexOf('b')!=-1){console.log("[INIT] POSTBOX");}
    if(cssoptions.postbox.draggable){
        if(options.indexOf('f')==-1){
            if(options.indexOf('b')!=-1){console.log('[POSTBOX] Draggable');}
            $("#postBox").css("left",($(document).width()-$("#postBox").outerWidth()-20)+"px");
            $("#postBox").draggable({containment: 'document'});
        }else{
            if(options.indexOf('b')!=-1){console.log('[POSTBOX] Static');}
            $("#postBox").css({position:'static',display:'block',width:'400px','margin-left':'auto','margin-right':'auto'});
        }
    }
    if(cssoptions.postbox.resizable){
        if(options.indexOf('b')!=-1){console.log('[POSTBOX] Resizable');}
        $("#fulltext").resizable();
    }
    
    
    if(options.indexOf('b')!=-1){console.log('[INIT] THREADWATCH');}
    if(cssoptions.threadwatch.draggable){
        if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Draggable');}
        $("#threadWatch").draggable({containment: 'document'});
        $("#threadWatch").css({right:"0px",top:(65+$("#postBox").height())+"px"});
    }
    if(cssoptions.threadwatch.resizable){
        if(options.indexOf('b')!=-1){console.log('[THREADWATCH] Resizable');}
        $("#threadWatch").resizable({minWidth:300,minHeight:30}).css({"width":"400px","height":"100px"});
    }
    if(cssoptions.post.filenamelimit){$(".fileName").css("max-width","100px");}
    
    if(options.indexOf('b')!=-1){console.log("[INIT] POST");}
    $(".post,.postOP").each(function(){customizePost($(this))});
});
