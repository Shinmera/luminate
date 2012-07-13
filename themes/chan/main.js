var anchor = document.location.hash.substring(1);
var focused = window;
var post_ids="";
var origtitle = document.title;
//update u preview p enlarge e scroll s hidden h quote q watched w fixed postbox f video hiding v auto-watch a
//bcdgijklmnoqrtxyz
var options = 'upeshq';
var cssoptions = {"postbox":    {"draggable": true,"resizable": true},
                  "threadwatch":{"draggable": true,"resizable": true}};

function isScrollBottom() { 
    var documentHeight = $(document).height(); 
    var scrollPosition = $(window).height() + $(window).scrollTop(); 
    return (scrollPosition+5 >= documentHeight);
}

function updateThread(){
    $.ajax({
        url: "?a=postlist",
        success: function(data){
            data=data.trim();
            if(data!=post_ids){
                if(post_ids!=""){
                    curposts = post_ids.split(";");
                    allposts = data.split(";");
                    post_ids=data;
                    addMissingPosts(allposts.length-curposts.length);
                    document.title = "("+(allposts.length-curposts.length)+") "+origtitle;
                }else{
                    post_ids=data;
                }
            }
        }
    });
}

function addMissingPosts(n){
    var allposts = post_ids.split(";"),posts = {};
    var i=0,v=0,m=0;
    if(n>0){i=1;v=n;m=-1;n--;}
    else   {i=allposts.length+parseInt(n);v=allposts.length+2;m=1;n*=-1;}
    for(;i<v;i++){
        if($("#P"+allposts[i]).length==0){
            $.ajax({
                url: $("#proot").html()+"data/chan/"+$("#varfolder").val()+"/posts/"+allposts[i]+".php",
                success: function(post){
                    $post = $(post);
                    customizePost($post);
                    posts[$post.attr("id")] = $post;
                    if(Object.size(posts)==n){
                        addMissingPostsHelper(posts,m);
                    }
                }
            });
        }
    }
}
function addMissingPostsHelper(posts,m){
    var allposts = post_ids.split(";");
    var $first = $(".thread .post:first-child");
    for(var id in allposts){
        var post = posts['P'+allposts[id]];
        if(post!=undefined){
            $(post).fadeIn();
            if(m>0)$(".thread").append($(post));
            else   $(post).insertBefore($first);
        }
    }
}

function addWatchedThread(board,id){
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
    $.cookie('chan2_watched','',{ expires: 356, path: '/' });
    refreshWatched();
}
function refreshWatched(){
    $.ajax({
        url: $("#proot").html()+'api/chan/watch',
        success: function(xml){
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
    $("#varsubmit").click(function(){
        addWatchedThread($("#varboard"),$("#varthread"));
    });
}

function hideThread(id){
    var threads = new Array();
    if($.cookie('chan2_thread_hidden')!=null&&$.cookie('chan2_thread_hidden')!=''){
        threads=$.cookie('chan2_thread_hidden').split(",");
        if(threads.indexOf(id)!==-1){
            removeA(threads,id);
            $("#P"+id+" .postContent").slideDown();
            $("#T"+id).slideDown();
        }else{
            threads.push(id);
            $("#P"+id+" .postContent").slideUp();
            $("#T"+id).slideUp();
        }
    }else{
        threads.push(id);
        $("#P"+id+" .postContent").slideUp();
        $("#T"+id).slideUp();
    }
    $.cookie('chan2_thread_hidden',implode(',',threads),{ expires: 356, path: '/' });
}
function hideThreads(){
    if($.cookie('chan2_thread_hidden')!=null){
        var threads = $.cookie('chan2_thread_hidden').split(",");
        for(var i=0;i<threads.length;i++){
            $("#P"+threads[i]+" .postContent").slideUp();
            $("#T"+threads[i]).slideUp();
        }
    }
}

function setFields(){
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
                 
    $('.fetchNext,.fetchPrevious').click(function(){
        var $self=$(this);
        $self.html('Fetching posts...');
        $.ajax({
            url: "?a=postlist",
            success: function(data){
                post_ids=data.trim();
                addMissingPosts($self.attr("amount"));
                $self.remove();
        }});
        return false;
    });
}

function customizePost(post){
    if(options.indexOf('q')!=-1){
        $(".directQuote",post).each(function(){
            $(this).unbind('hover');
            $(this).hover(function(e){
                $("#previewPost").stop(true, true).fadeOut(1);
                $("#previewPost").html("");
                $("#previewPost").css({"left":(e.pageX+10)+"px","top":(e.pageY+10)+"px"});
                if($(this).attr("board")==null)return;
                if($(this).attr("board").trim()==boardName.trim()&&$("#P"+$(this).attr("id")).length!=0){
                    $("#previewPost").html($("#P"+$(this).attr("id"))[0].outerHTML);
                    $("#previewPost").stop(true, true).fadeIn();
                }else{
                    var board=$(this).attr("board");
                    var id=$(this).attr("id");
                    $.ajax({
                        url: $("#proot").html()+'data/chan/'+board+"/posts/"+id+".php",
                        success: function(data) {
                            $("#previewPost").html(data);
                            $("#previewPost").stop(true, true).fadeIn();
                        },
                        error: function() {
                            $.ajax({
                                url: $("#proot").html()+'data/chan/'+board+"/posts/_"+id+".php",
                                success: function(data) {
                                    $("#previewPost").html(data);
                                    $("#previewPost").stop(true, true).fadeIn();
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
        $(".postImageLink",post).click(function(){
            var pic = $(this).children();
            if(pic.attr("src").indexOf("thumbs")!=-1){
                pic.attr("src",pic.attr("src").replace("thumbs","files"));
                $("#previewImage").stop(true, true).fadeOut();
            }else pic.attr("src",pic.attr("src").replace("files","thumbs"));
            return false;
        });
    }
}

function animateMenu(){
    var hovering=0;
    $("#menu li ul").each(function(){
        $(this).css({"left":"0px",
                     "top":($(this).parent().outerHeight()+$(this).parent().offset.y)+"px"});
        $(this).hover(function(){
            hovering++;
        },function(){
            hovering--;
            if(hovering==0)$(this).children("ul").stop(true, true).fadeOut();
        });
    });

    $("#menu>li").each(function(){
        $(this).hover(function(){
            hovering++;
            $(this).children("ul").stop(true, true).fadeIn();
        },function(){
            hovering--;
            if(hovering==0)$(this).children("ul").stop(true, true).fadeOut();
        });
    });
}

function registerShortcuts(){
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
    if($("#varthread").val()!=""){
        setThreadRead($("#varboard").val(),$("#varthread").val());
    }
}

function hideVideos(){
    $("iframe.youtube-player").each(function(){
        var src = $(this).attr("src");
        url = src.replace('embed/','watch?v=');
        $('<a href="'+url+'" title="'+url+'">Watch Youtube Video</a>').insertBefore(this);
        $(this).remove();
    });
}

$(function(){
    if($.cookie("chan2_options")!=null){options=$.cookie('chan2_options');}
    else $.cookie('chan2_options',options,{ expires: 356, path: '/' });
    
    $(".styleLink").each(function(){
        $(this).click(function(){
            $("#dynstyle").attr("href",$("#proot").html()+'themes/chan/css/'+$(this).attr("id"));
            $.cookie("chan2_style",$(this).attr("id"),{ expires: 356, path: '/' });
        });
    });
    
    setFields();
    animateMenu();
    registerButtons();
    registerShortcuts();
    registerThreadRead();
    if(options.indexOf('h')!=-1){hideThreads();}
    if(options.indexOf('u')!=-1){registerAutoUpdate();}
    if(options.indexOf('v')!=-1){hideVideos();}
    if(options.indexOf('w')!=-1){$("#threadWatch").fadeIn();}
    if(options.indexOf('a')!=-1){registerAutoWatch();}
    $(".post,.postOP").each(function(){customizePost($(this))});
    
    if($("options").length>0){
        var opts = $("options").css('content').replace(/"/g,'').replace(/\\'/g,'"').replace(/'/g,'');
        if($("options").css('content').length>5)
            cssoptions = $.parseJSON(opts);
    }
    
    if(cssoptions.postbox.draggable){
        if(options.indexOf('f')==-1){
            $("#postBox").css("left",($(document).width()-$("#postBox").outerWidth()-20)+"px");
            $("#postBox").draggable({containment: 'document'});
        }else{
            $("#postBox").css({position:'static',display:'block',width:'400px','margin-left':'auto','margin-right':'auto'});
        }
    }
    if(cssoptions.postbox.resizable){$("#fulltext").resizable();}
    
    if(cssoptions.threadwatch.draggable){
        $("#threadWatch").draggable({containment: 'document'});
        $("#threadWatch").css({right:"0px",top:(65+$("#postBox").height())+"px"});
    }
    if(cssoptions.threadwatch.resizable){$("#threadWatch").resizable({minWidth:300,minHeight:30}).css({"width":"400px","height":"100px"});}
});
