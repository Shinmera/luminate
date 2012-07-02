var anchor = document.location.hash.substring(1);
var focused = window;
var post_ids="";var checked_ids="";
var origtitle = document.title;
//update u preview p enlarge e scroll s hidden h quote q watched w fixed postbox f video hiding v
//abcdgijklmnoqrtxyz
var options = 'upeshq';
//TODO: Fix updated post spasms

function isScrollBottom() { 
    var documentHeight = $(document).height(); 
    var scrollPosition = $(window).height() + $(window).scrollTop(); 
    return (documentHeight == scrollPosition);
}

function updateThread(){
    $.ajax({
        url: "?a=postlist",
        success: function(data){
            if(data!=post_ids){
                if(post_ids!=""){
                    curposts = post_ids.split(";");
                    allposts = data.split(";");
                    newposts = allposts.filter(function(x) {return curposts.indexOf(x) < 0});
                    for(i=0;i<newposts.length;i++){
                        if($("#P"+newposts[i]).length==0){
                            $.ajax({
                                url: $("#proot").html()+"data/chan/"+$("#varfolder").val()+"/posts/"+newposts[i]+".php",
                                success: function(post){
                                    $(".thread").html($(".thread").html()+post);
                                    
                                }
                            });
                        }
                    }
                    document.title = "("+(allposts.length-checked_ids.split(";").length)+") "+origtitle;
                }else{checked_ids=data;}
                post_ids=data;
            }
        }
    });
}

function addWatchedThread(board,id){
    var pcook = "";
    if($.cookie('chan_watched')!=null)pcook=$.cookie('chan_watched');
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
            $.cookie('chan_watched',pcook+";"+board+" "+id+" "+html,{ expires: 356, path: '/' });
            refreshWatched();
        }
    });
    return true;
}
function delWatchedThread(board,id){
    var watched = [];
    if($.cookie('chan_watched')!=null)watched=$.cookie('chan_watched').split(";");
    if(watched.length==0)return false;

    for(var i=0;i<watched.length;i++){
        if(watched[i].indexOf(board+" "+id)!= -1){
            watched.splice(i, 1);
            break;
        }
    }
    $.cookie('chan_watched',implode(";",watched),{ expires: 356, path: '/' });
    refreshWatched();
    return true;
}
function setThreadRead(board,id){
    var watched = [];
    if($.cookie('chan_watched')!=null)watched=$.cookie('chan_watched').split(";");
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
            $.cookie('chan_watched',implode(";",watched),{ expires: 356, path: '/' });
            refreshWatched();
        }
    });
    return true;
}
function readWatched(){
    var watched = [];
    if($.cookie('chan_watched')!=null)watched=$.cookie('chan_watched').split(";");
    if(watched.length==0)return false;

    $.ajax({
        url: $("#proot").html()+'api/time',
        success: function(html){
            for(var i=0;i<watched.length;i++){
                var temp = watched[i].split(" ");
                watched[i]=temp[0]+" "+temp[1]+" "+html;
                break;
            }
            $.cookie('chan_watched',implode(";",watched),{ expires: 356, path: '/' });
            refreshWatched();
        }
    });
    return true;
}
function clearWatched(){
    $.cookie('chan_watched','',{ expires: 356, path: '/' });
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

function hideThread(id){
    if($.cookie('chan_thread_hidden')!=null){
        var threads=$.cookie('chan_thread_hidden').split(";");
        if($.inArray(id,threads)){
            $.cookie('chan_thread_hidden',$.cookie('chan_thread_hidden').replace(","+id+",",''),{ expires: 356, path: '/' });
            $("#P"+id+" .postContent").slideToggle();
            $("#T"+id).slideToggle();
        }else{
            $.cookie('chan_thread_hidden',$.cookie('chan_thread_hidden')+","+id+",",{ expires: 356, path: '/' });
            $("#P"+id+" .postContent").slideToggle();
            $("#T"+id).slideToggle();
        }
    }else{
        $.cookie('chan_thread_hidden',","+id+",");
        $("#P"+id+" .postContent").slideToggle();
            $("#T"+id).slideToggle();
    }
}

function setFields(){
    $(".password").each(function(){
        if($.cookie('chan_post_pw')==null){
            $(this).val(randomstring(15));
        }
        else $(this).val($.cookie('chan_post_pw'));
    });
    if($.cookie('chan_post_name')!=null)$("#varname").val($.cookie('chan_post_name'));
    if($.cookie('chan_post_mail')!=null)$("#varmail").val($.cookie('chan_post_mail'));
}
function hideThreads(){
    if($.cookie('chan_thread_hidden')!=null){
        var hiddenThreads = $.cookie('chan_thread_hidden').split(",");
        for(var i=0;i<hiddenThreads.length;i++){hideThread(hiddenThreads[i]);}
    }
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
}

function registerQuotes(){
    $(".directQuote").each(function(){
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

function registerSelectScroll(){
    $('a[href]').click(function(){
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

    if($('#P'+anchor).length>0){
        $('#P'+anchor).addClass('selected');
        $('html,body').animate({scrollTop: $('#P'+anchor).offset().top-40},200);
    }
}

function registerPostReply(){
    $(".postReply").unbind('click');
    $(".postReply").click(function(){
            var pos = $(this).parent().offset();
            var width = $(this).parent().width();
            var left = (pos.left+width);
            if (left>$(window).width()-$("#postBox").width()-40)left=$(window).width()-$("#postBox").width()-40;
            $("#fulltext").focus();
            $("#fulltext").val($("#fulltext").val()+">>"+$(this).html()+"\n");
            $("#replyto").html("Reply to "+$(this).attr("id"));
            $("#varthread").attr("value",$(this).attr("id"));
            $("#postBox").css({"left":left+"px","top":(pos.top+40)+"px"});
            window.scrollTo(0,pos.top-40);
            return false;
    });
}

function registerImagePreviews(){
    $(".postImage").each(function(){
        $(this).mousemove(function(e){
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
        });
        $(this).mouseout(function(e){
            $("#previewImage").stop(true, true).fadeOut();
        });
    });
}

function registerImageEnlarge(){
    $(".postImageLink").each(function(){
        $(this).click(function(){
            var pic = $(this).children();
            if(pic.attr("src").indexOf("thumbs")!=-1){
                pic.attr("src",pic.attr("src").replace("thumbs","files"));
                $("#previewImage").stop(true, true).fadeOut();
            }else pic.attr("src",pic.attr("src").replace("files","thumbs"));
            return false;
        });
    });
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
    if($.cookie("chan_options")!=null){options=$.cookie('chan_options');}
    else $.cookie('chan_options',options,{ expires: 356, path: '/' });
    
    $(".styleLink").each(function(){
        $(this).click(function(){
            $("#dynstyle").attr("href",$(this).attr("id"));
            $.cookie("chan_style",$(this).attr("id"),{ expires: 356, path: '/' });
        });
    });
    
    setFields();
    animateMenu();
    registerButtons();
    registerShortcuts();
    registerThreadRead();
    if(options.indexOf('h')!=-1){hideThreads();}
    if(options.indexOf('p')!=-1){registerImagePreviews();}
    if(options.indexOf('e')!=-1){registerImageEnlarge();}
    if(options.indexOf('q')!=-1){registerQuotes();}
    if(options.indexOf('s')!=-1){registerSelectScroll();}
    if(options.indexOf('u')!=-1){registerAutoUpdate();}
    if(options.indexOf('v')!=-1){hideVideos();}
    if(options.indexOf('w')!=-1){$("#threadWatch").fadeIn();}
    registerPostReply();
    
    if($("#postBox").css("content")!=='\'!draggable\''){
        if(options.indexOf('f')==-1){
            $("#postBox").css("left",($(document).width()-$("#postBox").outerWidth()-20)+"px");
            $("#postBox").draggable({containment: 'document'});
        }else{
            $("#postBox").css({position:'static',display:'block',width:'400px','margin-left':'auto','margin-right':'auto'});
        }
    }
    if($("#fulltext").css("content")!=='\'!resizable\''){
        $("#fulltext").resizable();
    }
    
    if($("#threadWatch").css("content")!=='\'!draggable\''){
        $("#threadWatch").draggable({containment: 'document'});
        $("#threadWatch").resizable({minWidth:300,minHeight:30}).css({"width":"400px","height":"100px"});
        $("#threadWatch").css({right:"0px",top:(65+$("#postBox").height())+"px"});
    }
});
