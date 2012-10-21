function randomstring(length){
    var temp=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
              'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
              '0','1','2','3','4','5','6','7','8','9','.',',',':',';','-','_','=','~','(',')','[',']','{','}','@','#'];
    var randomstring="";
    for(var i=0;i<length;i++){
        randomstring+=temp[Math.floor(Math.random()*(temp.length+1))];
    }
    return randomstring;
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i) ? true : false;
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i) ? true : false;
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) ? true : false;
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());
    }
};

function stringContains(a,search){
    return (a.indexOf(search) != -1);
}

jQuery.extend(jQuery.expr[':'], {
  focus: "a == document.activeElement"
});

function time(){
    return Math.round((new Date()).getTime() / 1000);
}

function implode (glue, pieces) {
    // Joins array elements placing glue string between items and return one string  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/implode
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Waldo Malqui Silva
    // +   improved by: Itsacon (http://www.itsacon.net/)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: implode(' ', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'Kevin van Zonneveld'
    // *     example 2: implode(' ', {first:'Kevin', last: 'van Zonneveld'});
    // *     returns 2: 'Kevin van Zonneveld'
    var i = '',
        retVal = '',
        tGlue = '';
    if (arguments.length === 1) {
        pieces = glue;
        glue = '';
    }
    if (typeof(pieces) === 'object') {
        if (Object.prototype.toString.call(pieces) === '[object Array]') {
            return pieces.join(glue);
        } 
        for (i in pieces) {
            retVal += tGlue + pieces[i];
            tGlue = glue;
        }
        return retVal;
    }
    return pieces;
}

function removeA(arr){
    var what, a= arguments, L= a.length, ax;
    while(L> 1 && arr.length){
        what= a[--L];
        while((ax= arr.indexOf(what))!= -1){
            arr.splice(ax, 1);
        }
    }
    return arr;
}

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

$(document).ready(function(){
    //$(".date").each(function(){
    //    $(this).datepicker({dateFormat:'dd.mm.yy',yearRange:'1940:2040'});
    //});
    
    $(".text").each(function(){
        $(this).resizable({minWidth:200,minHeight:100});
    });
    
    $(".tabbed").each(function(){
        var tabcontainer = $(this);
        
        //Generate tabbar
        var toInsert="<ul class='tabBar'>";
        tabcontainer.children("div,form").each(function(){
            if(typeof $(this).attr("href") !='undefined'){
                toInsert+="<li><a href='"+$(this).attr("href")+"'>"+$(this).attr("name")+"</a></li>";
            }else{
                toInsert+="<li>"+$(this).attr("name")+"</li>";
            }
            $(this).addClass("tabContainer");
            $(this).css("display","none");
        });
        toInsert+="</ul>";
        tabcontainer.prepend(toInsert);
        var tablist = tabcontainer.children("ul");
        
        //Add tab switch functionality
        var i=1;
        $(this).find("ul li").each(function(){
            var a = i; //copy so it's available in the new scope
            $(this).click(function(){
                tabcontainer.children("div,form").css("display","none");
                tabcontainer.children("div:nth-child("+(a+1)+"),form:nth-child("+(a+1)+")").css("display","block");
                tablist.children("li").removeClass("selected");
                $(this).addClass("selected");
            });
            i++;
        });
        
        //Select first.
        if(typeof tabselect== 'undefined')var tabselect = 1;
        tablist.children("li:nth-child("+tabselect+")").addClass("selected");
        tabcontainer.children("div:nth-child("+(tabselect+1)+"),form:nth-child("+(tabselect+1)+")").css("display","block");
    });
    
    $(".sspoiler").each(function(){
        $(this).hover(function(){
            $(this).removeClass("sspoiler");
        },function(){
            $(this).addClass("sspoiler");
        });
    });
    
});

function displayPopupInput(completeFunc,question,type){
    var id=time();if(type=="")type="text";
    $("body").append('<div class="jqmWindow" id="popupInput'+id+'"><p>'+question+'</p><input class="in" type="'+type+'" required /><br /><a href="#" class="jqmClose">Close</a></div>');
    $("#popupInput"+id+" .in").keypress(function(e){
      if(e.which == 13){$("#popupInput"+id+" .jqmClose").click();}
    });
    $('#popupInput'+id).jqm({
        modal: false,
        overlay: 50,
        onHide: function(){
            $(".jqmOverlay").remove();
            completeFunc($("#popupInput"+id+" .in").attr("value"));
            $("#popupInput"+id).remove();
        }
    }).jqmShow();
}

function insertAdv(object,tagform){
    if(stringContains(tagform,"$")){
        var strings0 = tagform.substring(0,tagform.indexOf("$"));tagform = tagform.substring(tagform.indexOf("$")+1,tagform.length);
        var strings1 = tagform.substring(0,tagform.indexOf("$"));
        var strings2 = tagform.substring(tagform.indexOf("$")+1,tagform.length);
        var type = "text";
        if(stringContains(strings1,"|")){
            type = strings1.substring(strings1.indexOf("|")+1);
            strings1 = strings1.substring(0,strings1.indexOf("|"));
        }
        displayPopupInput(function(receive){
            insertAdv(object,strings0+receive+strings2);
        },strings1+":",type);
    }else{
        var start = object[0].selectionStart;
        var end = object[0].selectionEnd;
        var len = object.val().length; 
        var sel = object.val().substring(start,end);
        var text = tagform;
        if(stringContains(tagform,"@")){
            if(sel==''){
                tagform = tagform.replace("@","$Enter a value$");
                insertAdv(object,tagform);
            }else{
                tagform = tagform.replace("@",sel);
                insertAdv(object,tagform);
            }
        }else{
            console.log(object);
            object.val(object.val().substring(0,start)+text+object.val().substring(end,len));
        }
    }
}

function addToolTip(el,text){
    if(el.length>0){
        var tooltip = document.createElement('div');

        $(tooltip).addClass('tooltip').html(text);
        $(tooltip).css({'display':'none','position':'absolute'});

        el.hover(function(){
            var elpos = $(this).offset();
            var tipheight = $(tooltip).height();
            var tipwidth = $(tooltip).width();
            if(tipheight==0)tipheight=30;
            if(tipwidth==0)tipwidth=50;

            var left = elpos.left+$(this).width()/2-tipwidth/2;
            var top = elpos.top-tipheight-10;

            if(top<5)top+=$(this).height();
            if(top>$(document).height()-5)top-=$(this).height();
            if(left<5)left=5;
            if(left>$(document).width()-5-tipwidth)left=$(document).width()-5-tipwidth;
            
            $(tooltip).css({'top':top,'left':left,'display':'none'});
            $(tooltip).stop(true,true).fadeIn(100);
        },function(){
            $(tooltip).stop(true,true).fadeOut(100);
        });

        $("body").append(tooltip);
    }
}

function confirm(msg,func) {
  $('#confirm')
    .jqmShow()
    .find('p.jqmConfirmMsg').html(msg).end()
    .find(':submit:visible').unbind('click').click(function(){
        if(this.value == 'Yes'){func();}
        $('#confirm').jqmHide();
      });
}

$().ready(function() {
  $('#confirm').jqm({overlay: 50, modal: true, trigger: false});
  
  $('a.confirm').click(function() { 
    confirm('About to visit: '+this.href+' !',this.href); 
    return false;
  });
});

function addInteractiveElement($ul,name,val){
    if($("li:last-child",$ul).length>0)
        id=$("li:last-child",$ul).attr("id")+1;
    else id=0;
    name = name.trim(); val = val.trim();
    if(name==""||val=="")return;
    $ul.append('<li id="ILE'+id+'"><a>x</a><input type="hidden" name="'+$ul.parent().attr("id")+'[]" value="'+val+'" />'+name+'</li>');
    try{$ul.sortable("refresh");}catch(e){}
    var $obj= $("#ILE"+id+" a",$ul);
    $obj.click(function(){$(this).parent().remove();return false;});
    return $obj;
}
//??? ODDITY
function createInteractiveList(){
    switch(arguments.length){
        case 0:return;break;
        case 1:arguments[1]=[];
        case 2:arguments[2]=[];
        case 3:arguments[3]=false;
    }
    var dat = arguments[1];
    var pre  = arguments[2];
    var all  = arguments[3];
    var $m = $("#"+arguments[0]);
    var $i = $(">input",$m);
    var $u = $(">ul",$m);
    
    function addVal(val){
        if(dat[val] == undefined){
            var name = null;
            for(var prop in dat){
                if(dat[prop]==val)name=prop;
            }
            if(name != null)
                addInteractiveElement($u,name,val);
            else if(all)
                addInteractiveElement($u,val,val);
        }
        if(dat[val] !== undefined)
            addInteractiveElement($u,val,dat[val]);
    }
    
    try{$u.sortable({axis:"x",containment: "#"+arguments[0]+">ul"});}catch(e){}
    $i.keypress(function(e) {
        if(e.keyCode == 13 || e.keyCode == 188 || e.keyCode == 44) {
            var val = $i.val();
            addVal(val);
            $i.val("");
            $i.focus();
            return false;
        }
        return true;
    });

    var e = jQuery.Event("keypress");
    e.keyCode = 13;e.which = 13;
    for(var item in pre ){
        addVal(pre[item]);
    }
}

$(function(){
    $('.spoiler').each(function(){
        var open = $('>.open',this).html();
        var close = $('>.close',this).html();
        var $div = $('>div',this);
        $('>button',this).unbind('click').click(function(event){
            event.preventDefault();
            if($(this).html()==open) $(this).html(close);
            else                     $(this).html(open);
            $div.stop(true,true).slideToggle(200);
            return false;
        });
    })
});