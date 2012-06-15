function initPicture(){
    if($("#ll").length!=0)addToolTip($("#ll"),'Last');
    if($("#lp").length!=0)addToolTip($("#lp"),'Previous');
    if($("#rn").length!=0)addToolTip($("#rn"),'Next');
    if($("#rf").length!=0)addToolTip($("#rf"),'First');
    addToolTip($("#ff"),'Folder');
    addToolTip($("#cc"),'Permalink');
    
    if($("#image").length != 0){
        $("#image").load(function(){
            var img_width = $("#image").width();
            var img_height = $("#image").height();
            var cont_width = $(window).width();
            var cont_height = $(window).height();
            var infoheight = $("#pictureinfoshort").height();
            var navheight = $("#picturenav").height();

            opt_width=img_width;
            opt_height=img_height;
            if(img_width>cont_width){
                opt_width=cont_width-50;
                opt_height=Math.floor(img_height*(cont_width/img_width))-50;
            }
            if(cont_height<opt_height){
                opt_height=cont_height-50;
                opt_width=Math.floor(img_width*(cont_height/img_height))-50;
            }

            $("#image").css({width:(opt_width)+"px",height:(opt_height)+"px"});
            $("#image").click(function(){
                if($("#image").width()==opt_width){
                    $("#image").stop(true,true).animate({width:img_width,height:img_height},500);
                    $("#pictureinfoshort").stop(true,true).animate({"max-height":0},500);
                    $("#picturenav").stop(true,true).animate({"max-height":0},500);
                }else{
                    $("#image").stop(true,true).animate({width:opt_width,height:opt_height},500);
                    $("#pictureinfoshort").stop(true,true).animate({"max-height":infoheight},500);
                    $("#picturenav").stop(true,true).animate({"max-height":navheight},500);
                }
            });
            $("#pictureinfoshort").click(function(){
                if($("#pictureinfoshort").height()==infoheight)
                    $("#pictureinfoshort").stop(true,true).animate({"max-height":0},500);
                else
                    $("#pictureinfoshort").stop(true,true).animate({"max-height":infoheight},500);
            });
        });
    }
}

function initManage(){
    var $imagelist = $("#imagelist");
    var $folderlist = $("#folderlist");

    function resetDeleteHandlers(){
        $( "li>ul>li>.delete",$folderlist).unbind("click").click(function(){
            $(this).parent().slideUp(200,function(){
                $(this).remove()});
        });
    }

    function saveData(){
        $("#saver").html('Saving Data...');
        $("li",$folderlist).each(function(){
            var name = $(this).attr("id");
            $("ul li",$(this)).each(function(){
                $("#managecontent").append('<input type="hidden" name="'+name+'[]" value="'+$(this).attr("id").substr(1)+'">');
            });
        });

        $.ajax($('#apiurl').html()+'displayManageSave',{
            data: $("#managecontent").serialize(),
            type: "POST",
            success:function(data){$("#saver").html(data);},
            error: function(){$("#saver").html('Saving failed!');}
        }).done(function(){$("#managecontent input").remove();});
    }

    $("#saver").click(function(){saveData();})

    $( "li", $imagelist ).draggable({
        cancel: "a",
        revert: false,
        scroll: true,
        zIndex: 100,
        opacity: 0.8,
        containment: "body",
        helper: "clone",
        cursor: "move",
        addClasses: false
    })
    $( ">li", $folderlist ).droppable({
        accept: "#imagelist > li",
        hoverClass: "hover",
        activeClass: "active",
        drop: function( event, ui ) {
            $folderlist.find("#P"+ui.draggable.attr("id")).remove();
            $( "ul", $(this)).append('<li id="P'+ui.draggable.attr("id")+'"><a class="delete">x</a>'+ui.draggable.attr("title")+'</li>');
            $( "ul", $(this)).sortable('refresh');
            resetDeleteHandlers();
        }
    })
    $(">li ul",$folderlist).sortable({
        containment: "#folderlist",
        cursor: "move",
        zIndex: 100,
        connectWith: ".new"
    }).disableSelection();

    $( ">li>.delete",$folderlist).click(function(){
        var id = $(this).parent().attr("id");
        var $folderu = $("#"+id+" ul",$folderlist);
        confirm("Do you really want to delete everything in '"+id+"'?",function(){$folderu.slideUp(500,
                function(){
                    $('li',$folderu).remove();
                    $folderu.css('display','block');
                });
            });
    });
    $(">li>.collapse",$folderlist).click(function(){
        $("ul",$(this).parent()).slideToggle();
    });

    $("a",$imagelist).click(function(){
        var id = $(this).parent().attr("id");
        confirm("Do you really want to delete '"+$(this).parent().attr("title")+"'?",function(){
            $("#saver").html('Deleting picture...');
            $.ajax($('#apiurl').html()+'displayManageDelete',{
                data: {'id':id},
                type: "POST",
                success: function(data){
                    $("#saver").html(data);
                    $("#P"+id).remove();
                    $("#"+id).remove();
                    saveData();
                },
                error: function(){$("#saver").html('Deleting failed!');}
            });
        });
    });

    resetDeleteHandlers();
}