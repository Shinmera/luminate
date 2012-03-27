function insert(formid,aTag, eTag) {
  var input = $("#"+formid)[0];
  input.focus();
  /* f�r Internet Explorer */
  if(typeof document.selection != 'undefined') {
    /* Einf�gen des Formatierungscodes */
    var range = document.selection.createRange();
    var insText = range.text;
    range.text = aTag + insText + eTag;
    /* Anpassen der Cursorposition */
    range = document.selection.createRange();
    if (insText.length == 0) {
      range.move('character', -eTag.length);
    } else {
      range.moveStart('character', aTag.length + insText.length + eTag.length);      
    }
    range.select();
  }
  /* f�r neuere auf Gecko basierende Browser */
  else if(typeof input.selectionStart != 'undefined')
  {
    /* Einf�gen des Formatierungscodes */
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value.substr(end);
    /* Anpassen der Cursorposition */
    var pos;
    if (insText.length == 0) {
      pos = start + aTag.length;
    } else {
      pos = start + aTag.length + insText.length + eTag.length;
    }
    input.selectionStart = pos;
    input.selectionEnd = pos;
  }
  /* f�r die �brigen Browser */
  else
  {
    /* Abfrage der Einf�geposition */
    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos)) {
      pos = prompt("Inserting at position (0.." + input.val().length + "):", "0");
    }
    if(pos > input.val().length) {
      pos = input.val().length;
    }
    /* Einf�gen des Formatierungscodes */
    var insText = prompt("Please enter the text which is to be formatted:");
    input.value = input.val().substr(0, pos) + aTag + insText + eTag + input.value.substr(pos);
  }
}

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

$(document).ready(function(){
    $(".date").each(function(){
        $(this).datepicker({dateFormat:'dd.mm.yy',yearRange:'1940:2040'});
    });
    
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
    
    $(".spoiler input").each(function(){
        $(this).click(function(){
            $(this).parent().children(".spoilertext").slideToggle();
        });
    });

    $(".sspoiler").each(function(){
        $(this).hover(function(){
            $(this).removeClass("sspoiler");
        },function(){
            $(this).addClass("sspoiler");
        });
    });
    
});