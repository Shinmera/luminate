var AJAXCOUNT = 0;

function defVar(vars, def){
    return (typeof(vars) !== 'undefined') ? vars : def;
}

function fixActionsY(){
    $("#ticketActions").css("top",($("article").position().top + $("article").height()+10)+"px")
}

function fixMargins(){
    var border = 120;
    var curWidth = $('body').innerWidth();
    var minWidth = parseInt($(".content").css("min-width").replace('px',''));
    if(curWidth > 1300)
        $(".content").css({"margin-left":'20%',"margin-right":'20%'})
    else if(curWidth > minWidth + border*2){
        widthToSpend = (curWidth-minWidth) / 2;
        $(".content").css({"margin-left":widthToSpend + 'px',"margin-right":widthToSpend + 'px'})
    }else{
        $(".content").css({"margin-left":border,"margin-right":border})
    }
}

function message(text, type){
    type = defVar(type, 'info');
    $("#message").html(text).removeClass().addClass(type);
    $("#message").slideDown(100);
    setTimeout(function(){$("#message").slideUp(100);}, 3000);
}

function toggleSpinner(){
    if($("#spinner").css("display") == "none"){
        $("#spinner").fadeIn(500);
    }else{
        $("#spinner").fadeOut(500);
    }
}

function ajaxGET(url, data, func){
    toggleSpinner();
    var onend = function(){
        func();
        toggleSpinner();
    };
    $.get(url, data, onend);
}

function ajaxPOST(dat){
    var id               = AJAXCOUNT++;
    var url              = dat.url;
    var data             = defVar(dat.data, '');
    var actionType       = defVar(dat.actionType, 'POST');
    var funcSuccess      = defVar(dat.onSuccess, function(data){});
    var funcServerError  = defVar(dat.onServerError, function(data){});
    var funcNetworkError = defVar(dat.onNetworkError, function(data){});
    var funcComplete     = defVar(dat.onComplete, function(data){});
    var executeScriptBit = defVar(dat.executeScriptBit, true);
    console.log("Starting new AJAX with ID A"+id)
    console.log("[A"+id+"]Attempting POST to '"+url+"' with the following data:");
    console.log(data);

    toggleSpinner();
    $.ajax({
        "url": url,
        "type": 'post',
        "data": data,
        "dataType": 'json',
        "success": function(data, textStatus, jqHXR){
            if(data.status=='200'){
                console.log("[A"+id+"] Returned successfully with '"+data.status+"'. jqHXR Object and DATA are:");
                console.log(jqHXR);console.log(data);
                message(actionType + " succeeded!",'ok');
                funcSuccess(data, textStatus, jqHXR);
            }else{
                console.log("[A"+id+"] Returned with server error '"+data.status+"'. jqHXR Object and DATA are:");
                console.log(jqHXR);console.log(data);
                message(actionType + " failed!<br />"+data.statusText);
                funcServerError(data, textStatus, jqHXR);
            }
            if("script" in data && typeof(data.script) == 'string' && executeScriptBit){
                console.log("[A"+id+"] Executing JS script bit:");
                console.log(data.script);
                eval(data.script);
            }
        },
        "error": function(data, textStatus, jqHXR){
            switch(textStatus){
                case 'timeout'      : message(actionType + " failed!<br />Network timeout.",'fail');break;
                case 'error'        : message(actionType + " failed!<br />Network error.",'fail');break;
                case 'abort'        : message(actionType + " failed!<br />Request aborted.",'fail');break;
                case 'parsererror'  : message(actionType + " failed!<br />Received a bad response.",'fail');break;
            }
            funcNetworkError(data, textStatus, jqHXR);
            console.log("[A"+id+"] Returned with network error '"+textStatus+"'. jqHXR Object and DATA are:");
            console.log(jqHXR);console.log(data);
        },
        "complete": function(data, textStatus, jqHXR){
            toggleSpinner();
            funcComplete(data, textStatus, jqHXR);
        }
    });
}

function popup(content, div){
    div = typeof div !== 'undefined' ? div : 'popup';
    $("#"+div+" p").html(content);
    if($("#"+div).css("display") == "none"){
        $("#"+div+",#popupBack").fadeIn(100);
    }
}

$(document).ready(function(){
    fixActionsY();
    $(".button.toggleable").click(function(){$(this).toggleClass("active");});
    $("#ticketContent .postImage").click(function(){
        var url = $(this).attr("src");
        popup('<img src="'+url+'" alt="" />');
    });
    $("#popupBack").click(function(){
        $(".popup").fadeOut(100);
    })

    $(".ticketAction,#ticketActionsLegend li").click(function(){
        if($(this).hasClass("highlighted")){
            $(".ticketAction,#ticketActionsLegend li").removeClass("hidden");
            $(".ticketAction,#ticketActionsLegend li").removeClass("highlighted");
        }else{
            $(".ticketAction,#ticketActionsLegend li").removeClass("highlighted");
            $(".ticketAction,#ticketActionsLegend li").addClass("hidden")
            var classList =$(this).attr('class').split(/\s+/);
            $.each( classList, function(index, item){
                if (item.indexOf('action') == 0) {
                   $("." + item).removeClass("hidden");
                   $("." + item).addClass("highlighted");
                }
            });
        }
    });
    $("#toolEdit").click(function(){
        if($(this).hasClass("active")){
            $(".body .previewButton").each(function(){if($(this).hasClass("active"))$(this).click();})
            $(".body input, .body textarea, .body select").css("display","none");
            $(".formInputArea a,.formInputArea span,.formInputArea p").css("display", "inline-block");
            $(this).removeClass("active");
        }else{
            $(".body input, .body textarea, .body select").css("display","inline-block");
            $(".formInputArea a,.formInputArea span,.formInputArea p").css("display", "none");
            $($(".body input")[0]).focus();
            $(this).addClass("active");
        }
        fixActionsY();
    });
    $("#toolComment").click(function(){
        $("textarea#comment").focus();
    });
    $("#toolManageTeam,#toolManageGroup").click(function(){
        $(".team .manageList").toggleClass("invisible");
        $(".team .usersList").toggleClass("invisible");
    });
    $(".team .button.edit").click(function(){
        $(this).parent().children("form").toggleClass("invisible");
    });
    $(".team .button.add").click(function(){
        $(this).parent().children("div").append("<input type='text' class='permnode' name='permissions[]' />");
    });
    $(".team .button.remove").click(function(){
        var user = $(this).attr("data-user");
        popup("<form action='#' method='post' class='formInputArea'> \
                <div id='popupHead' class='head'> \
                    <h1>Remove "+user+" From Team</h1> \
                    <ul class='menu'> \
                        <li><input type='submit' value='Remove' /></li> \
                    </ul> \
                </div> \
                <input type='hidden' name='user' value='"+user+"'> \
            </form>");
        $('#popupComment').focus();
    });
    $("#toolJoin").click(function(){
        message("Sending join request...");
        ajaxPOST({'url':$(this).attr('href'),'actionType':'Join request'});
        return false;
    });
    $("#toolTeamAddUser").click(function(){
        popup("<form action='#' method='post' class='formInputArea'> \
                <div id='popupHead' class='head'> \
                    <h1>Add User to Team</h1> \
                    <ul class='menu'> \
                        <li><input type='submit' value='Assign' /></li> \
                    </ul> \
                </div> \
                <input type='text' name='assignee' required class='suggest user' id='popupAssign' placeholder='Username'/> \
            </form>");
        $('#popupAssign').focus();
    });
    $("#toolAssign").click(function(){
        popup("<form action='#' method='post' class='formInputArea'> \
                <div id='popupHead' class='head'> \
                    <h1>Assign Ticket</h1> \
                    <ul class='menu'> \
                        <li><input type='submit' value='Assign' /></li> \
                    </ul> \
                </div> \
                <input type='text' name='assignee' required class='suggest user' id='popupAssign' placeholder='Username' /> \
                <textarea name='comment' class='previewable' id='popupComment' ></textarea> \
            </form>");
        $('#popupAssign').focus();
    });
    $("#toolType").click(function(){
        popup("<form action='#' method='post' class='formInputArea'> \
                <div id='popupHead' class='head'> \
                    <h1>Change Type</h1> \
                    <ul class='menu'> \
                        <li><input type='submit' value='Change' /></li> \
                    </ul> \
                </div> \
                <select name='type' id='popupType'> \
                    <option value='b'>Bug</option> \
                    <option value='i' selected>Improvement</option> \
                    <option value='f'>New Feature</option> \
                    <option value='f'>Duplicate</option> \
                </select> \
                <textarea name='comment' class='previewable' id='popupComment' ></textarea> \
            </form>");
        $('#popupType').focus();
    });
    $("#toolStatus").click(function(){
        popup("<form action='#' method='post' class='formInputArea'> \
                <div id='popupHead' class='head'> \
                    <h1>Change Status</h1> \
                    <ul class='menu'> \
                        <li><input type='submit' value='Change' /></li> \
                    </ul> \
                </div> \
                <select name='type' id='popupStatus'> \
                    <option value='r'>Request</option> \
                    <option value='i'>Implementation</option> \
                    <option value='t'>Testing</option> \
                    <option value='c' selected>Closed</option> \
                    <option value='q'>Rejected</option> \
                    <option value='a'>Archived</option> \
                </select> \
                <textarea name='comment' class='previewable' id='popupComment' ></textarea> \
            </form>");
        $('#popupStatus').focus();
    });
    $("#toolClose").click(function(){
        popup("<form action='#' method='post' class='formInputArea'> \
                <div id='popupHead' class='head'> \
                    <h1>Close Ticket</h1> \
                    <ul class='menu'> \
                        <li><input type='submit' value='Close' /></li> \
                    </ul> \
                </div> \
                <textarea name='comment' class='previewable' id='popupComment' ></textarea> \
            </form>");
        $('#popupComment').focus();
    });

    $(".previewable").each(function(){
        var curID = $(this).attr("id");
        $('<input type="button" class="previewButton" id="prev-'+curID+'" value="Preview" />').insertBefore($(this))
        $('#prev-'+curID).click(function(){
            var textareaID = $(this).attr("id").substring(5);
            var textarea = $("#"+textareaID)
            if($(this).hasClass("active")){
                $(this).removeClass("active").attr("value","Preview");
                textarea.css("display","inline-block")
                $("#cont-"+textareaID).remove()
            }else{
                $(this).addClass("active").attr("value","Edit");
                $("<div id='cont-"+textareaID+"' class='previewContainer'>&nbsp;</div>").insertAfter(textarea);
                $("#cont-"+textareaID).css({"height":textarea.height()+"px"});
                textarea.css("display","none")
            }
            return false;
        })
    });

    $(".user").click(function(){
        if ($(this).attr('data-user')) {
            popup("<div id='popupHead' class='head'> \
                    <a href='/u/"+$(this).attr('data-user')+"'> \
                    <img src='./user-"+$(this).attr('data-user').toLowerCase()+"-64.png' alt='' /> \
                    <h1>"+$(this).attr('data-user')+"</h1></a> \
                    <ul class='menu'> \
                        <li><a href='/u/"+$(this).attr('data-user')+"'>Profile</a></li> \
                        <li><a href='/_user/"+$(this).attr('data-user')+"'>TyNET</a></li> \
                        <li><a href='/_user/panel/Messages/Write?to="+$(this).attr('data-user')+"'>Message</a></li> \
                    </ul> \
                </div>");
        }
        return false;
    });

    $("#showLabels").click(function(){
        if($(".shortcut").css("display") == "none")
            $(".shortcut").css("display","inline-block");
        else
            $(".shortcut").css("display","none");
    });

    $('form.ajax input[type="submit"]').click(function(){
        form = $(this).parent();
        data = form.serialize();
        url = form.attr("action");
        ajaxPOST({'url':url, 'data':data, 'actionType':'Save'});
        return false;
    });

    //Add cancel focus keybind to form fields.
    $("input,textarea,select").keyup(function(event){
        if(event.keyCode == 27){
            $(this).blur();
        }
    });

    //Close popup.
    $("#popup").keyup(function(event){
        if(event.keyCode == 27){
            $("#popupBack").click();
            $("body").click();
        }
    });
})
$(window).load(function(){
    fixActionsY();
    fixMargins();

    var shortcuts = {
        'a a':'#legendAssign',
        'a c':'#legendComment',
        'a e':'#legendEdit',
        'a o':'#legendOpen',
        'a s':'#legendStatus',
        'a t':'#legendType',
        'a x':'#legendClose',
        'g p':'#returnButton a',
        't a':'#toolAssign,#toolAddProject,#toolShowActivity',
        't c':'#toolComment',
        't e':'#toolEdit',
        't g':'#toolSwitchGroup',
        't j':'#toolJoin',
        't l':'#toolLeave',
        't m':'#toolManageTeam,#toolManageGroup,#toolManageProjects,#toolSendMessage',
        't p':'#toolSubProject,#toolPermissions',
        't s':'#toolStatus,#toolSubmit',
        't t':'#toolType,#toolTyNETProfile,#toolShowTickets',
        't u':'#toolTeamAddUser',
        't x':'#toolClose',
        'l':'#showLabels'};

    for(s in shortcuts){
        var pos = $(shortcuts[s]).position();
        var lab = s.replace(/\s/g,'')
        if($(shortcuts[s]).length > 0){
            $(shortcuts[s]).append('<label id="short-'+lab+'" class="shortcut">'+lab+'</label>');
            $("#short-"+lab).css({"top":(pos.top-10)+"px",
                                "left":(pos.left+$(shortcuts[s]).width())+"px"});
            Mousetrap.bind(s, function(e, s){
                $(shortcuts[s]).click();
                $("#short-"+s.replace(/\s/g,'')).addClass('active');
                setTimeout(function(){$("#short-"+s.replace(/\s/g,'')).removeClass('active')}, 200)
                return false;
            });
        }
    }

    toggleSpinner();
    $(window).resize(function(){fixMargins();});
});