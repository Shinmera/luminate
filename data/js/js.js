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

function errorCodeToString(err){
    switch(err){
        case 0:  return 'Generic Error';break;
        case 1:  return 'Ok';break;
        case 2:  return 'No Access';break;
        case 3:  return 'Resource Not Loaded';break;
        case 4:  return 'Resource Not Found';break;
        case 5:  return 'Invalid Request';break;
        case 6:  return 'Invalid Password';break;
        case 7:  return 'Invalid User';break;
        case 8:  return 'Invalid Authentification';break;
        case 9:  return 'Invalid Captcha';break;
        case 10: return 'Invalid API Securiy Token';break;
        case 11: return 'Invalid URL';break;
        case 12: return 'Invalid Group';break;
        case 13: return 'Unzipping Failed';break;
        case 14: return 'Module Not Found';break;
        case 15: return 'Module Not Active';break;
        case 50: return 'OpenID Cancelled';break;
        case 51: return 'OpenID Generic Error';break;
        case 100:return 'Bad IP';break;
        case 101:return 'Bad Akismet';break;
        case 121:return 'File Too Big';break;
        case 122:return 'Invalid Filetype';break;
        case 123:return 'Upload Failed';break;
        case 124:return 'File Exists';break;
        case 200:return 'Timeout';break;
        default: return 'Unknown Error';break;
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

$(function(){
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
        $(this).datepicker();
    });
    
    $(".tabbed").each(function(){
        var tabcontainer = $(this);
        tabcontainer.children("div,form").addClass("tabContainer");
        tabcontainer.children("div,form").css("display","none");
        tabcontainer.children("div:nth-child(1),form:nth-child(1)").css("display","block");
        
        var toInsert="<ul class='tabBar'>";
        tabcontainer.children("div,form").each(function(){
            toInsert+="<li>"+$(this).attr("name")+"</li>";
        });
        toInsert+="</ul>";
        tabcontainer.prepend(toInsert);
        var tablist = tabcontainer.children("ul");
        tablist.children("li:nth-child(1)").addClass("selected");
        
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
    });
})