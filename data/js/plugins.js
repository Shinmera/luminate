(function(a){a.fn.jqm=function(d){var e={overlay:50,overlayClass:"jqmOverlay",closeClass:"jqmClose",trigger:".jqModal",ajax:f,ajaxText:"",target:f,modal:f,toTop:f,onShow:f,onHide:f,onLoad:f};return this.each(function(){if(this._jqm)return c[this._jqm].c=a.extend({},c[this._jqm].c,d);b++;this._jqm=b;c[b]={c:a.extend(e,a.jqm.params,d),a:f,w:a(this).addClass("jqmID"+b),s:b};if(e.trigger)a(this).jqmAddTrigger(e.trigger)})};a.fn.jqmAddClose=function(a){return l(this,a,"jqmHide")};a.fn.jqmAddTrigger=function(a){return l(this,a,"jqmShow")};a.fn.jqmShow=function(b){return this.each(function(){b=b||window.event;a.jqm.open(this._jqm,b)})};a.fn.jqmHide=function(b){return this.each(function(){b=b||window.event;a.jqm.close(this._jqm,b)})};a.jqm={hash:{},open:function(b,g){var i=c[b],k=i.c,l="."+k.closeClass,m=parseInt(i.w.css("z-index")),m=m>0?m:3e3,n=a("<div></div>").css({height:"100%",width:"100%",position:"fixed",left:0,top:0,"z-index":m-1,opacity:k.overlay/100});if(i.a)return f;i.t=g;i.a=true;i.w.css("z-index",m);if(k.modal){if(!d[0])j("bind");d.push(b)}else if(k.overlay>0)i.w.jqmAddClose(n);else n=f;i.o=n?n.addClass(k.overlayClass).prependTo("body"):f;if(e){a("html,body").css({height:"100%",width:"100%"});if(n){n=n.css({position:"absolute"})[0];for(var o in{Top:1,Left:1})n.style.setExpression(o.toLowerCase(),"(_=(document.documentElement.scroll"+o+" || document.body.scroll"+o+"))+'px'")}}if(k.ajax){var p=k.target||i.w,q=k.ajax,p=typeof p=="string"?a(p,i.w):a(p),q=q.substr(0,1)=="@"?a(g).attr(q.substring(1)):q;p.html(k.ajaxText).load(q,function(){if(k.onLoad)k.onLoad.call(this,i);if(l)i.w.jqmAddClose(a(l,i.w));h(i)})}else if(l)i.w.jqmAddClose(a(l,i.w));if(k.toTop&&i.o)i.w.before('<span id="jqmP'+i.w[0]._jqm+'"></span>').insertAfter(i.o);k.onShow?k.onShow(i):i.w.show();h(i);return f},close:function(b){var e=c[b];if(!e.a)return f;e.a=f;if(d[0]){d.pop();if(!d[0])j("unbind")}if(e.c.toTop&&e.o)a("#jqmP"+e.w[0]._jqm).after(e.w).remove();if(e.c.onHide)e.c.onHide(e);else{e.w.hide();if(e.o)e.o.remove()}return f},params:{}};var b=0,c=a.jqm.hash,d=[],e=a.browser.msie&&a.browser.version=="6.0",f=false,g=a('<iframe src="javascript:false;document.write(\'\');" class="jqm"></iframe>').css({opacity:0}),h=function(b){if(e)if(b.o)b.o.html('<p style="width:100%;height:100%"/>').prepend(g);else if(!a("iframe.jqm",b.w)[0])b.w.prepend(g);i(b)},i=function(b){try{a(":input:visible",b.w)[0].focus()}catch(c){}},j=function(b){a()[b]("keypress",k)[b]("keydown",k)[b]("mousedown",k)},k=function(b){var e=c[d[d.length-1]],f=!a(b.target).parents(".jqmID"+e.s)[0];if(f)i(e);return!f},l=function(b,d,e){return b.each(function(){var b=this._jqm;a(d).each(function(){if(!this[e]){this[e]=[];a(this).click(function(){for(var a in{jqmShow:1,jqmHide:1})for(var b in this[a])if(c[this[a][b]])c[this[a][b]].w[a](this);return f})}this[e].push(b)})})}})(jQuery);jQuery.tableDnD={currentTable:null,dragObject:null,mouseOffset:null,oldY:0,build:function(a){this.each(function(){this.tableDnDConfig=$.extend({onDragStyle:null,onDropStyle:null,onDragClass:"tDnD_whileDrag",onDrop:null,onDragStart:null,scrollAmount:5,serializeRegexp:/[^\-]*$/,serializeParamName:null,dragHandle:null},a||{});jQuery.tableDnD.makeDraggable(this)});jQuery(document).bind("mousemove",jQuery.tableDnD.mousemove).bind("mouseup",jQuery.tableDnD.mouseup);return this},makeDraggable:function(a){var b=a.tableDnDConfig;if(a.tableDnDConfig.dragHandle){var c=$("td."+a.tableDnDConfig.dragHandle,a);c.each(function(){jQuery(this).mousedown(function(c){jQuery.tableDnD.dragObject=this.parentNode;jQuery.tableDnD.currentTable=a;jQuery.tableDnD.mouseOffset=jQuery.tableDnD.getMouseOffset(this,c);if(b.onDragStart){b.onDragStart(a,this)}return false})})}else{var d=jQuery("tr",a);d.each(function(){var c=$(this);if(!c.hasClass("nodrag")){c.mousedown(function(c){if(c.target.tagName=="TD"){jQuery.tableDnD.dragObject=this;jQuery.tableDnD.currentTable=a;jQuery.tableDnD.mouseOffset=jQuery.tableDnD.getMouseOffset(this,c);if(b.onDragStart){b.onDragStart(a,this)}return false}}).css("cursor","move")}})}},updateTables:function(){this.each(function(){if(this.tableDnDConfig){jQuery.tableDnD.makeDraggable(this)}})},mouseCoords:function(a){if(a.pageX||a.pageY){return{x:a.pageX,y:a.pageY}}return{x:a.clientX+document.body.scrollLeft-document.body.clientLeft,y:a.clientY+document.body.scrollTop-document.body.clientTop}},getMouseOffset:function(a,b){b=b||window.event;var c=this.getPosition(a);var d=this.mouseCoords(b);return{x:d.x-c.x,y:d.y-c.y}},getPosition:function(a){var b=0;var c=0;if(a.offsetHeight==0){a=a.firstChild}while(a.offsetParent){b+=a.offsetLeft;c+=a.offsetTop;a=a.offsetParent}b+=a.offsetLeft;c+=a.offsetTop;return{x:b,y:c}},mousemove:function(a){if(jQuery.tableDnD.dragObject==null){return}var b=jQuery(jQuery.tableDnD.dragObject);var c=jQuery.tableDnD.currentTable.tableDnDConfig;var d=jQuery.tableDnD.mouseCoords(a);var e=d.y-jQuery.tableDnD.mouseOffset.y;var f=window.pageYOffset;if(document.all){if(typeof document.compatMode!="undefined"&&document.compatMode!="BackCompat"){f=document.documentElement.scrollTop}else if(typeof document.body!="undefined"){f=document.body.scrollTop}}if(d.y-f<c.scrollAmount){window.scrollBy(0,-c.scrollAmount)}else{var g=window.innerHeight?window.innerHeight:document.documentElement.clientHeight?document.documentElement.clientHeight:document.body.clientHeight;if(g-(d.y-f)<c.scrollAmount){window.scrollBy(0,c.scrollAmount)}}if(e!=jQuery.tableDnD.oldY){var h=e>jQuery.tableDnD.oldY;jQuery.tableDnD.oldY=e;if(c.onDragClass){b.addClass(c.onDragClass)}else{b.css(c.onDragStyle)}var i=jQuery.tableDnD.findDropTargetRow(b,e);if(i){if(h&&jQuery.tableDnD.dragObject!=i){jQuery.tableDnD.dragObject.parentNode.insertBefore(jQuery.tableDnD.dragObject,i.nextSibling)}else if(!h&&jQuery.tableDnD.dragObject!=i){jQuery.tableDnD.dragObject.parentNode.insertBefore(jQuery.tableDnD.dragObject,i)}}}return false},findDropTargetRow:function(a,b){var c=jQuery.tableDnD.currentTable.rows;for(var d=0;d<c.length;d++){var e=c[d];var f=this.getPosition(e).y;var g=parseInt(e.offsetHeight)/2;if(e.offsetHeight==0){f=this.getPosition(e.firstChild).y;g=parseInt(e.firstChild.offsetHeight)/2}if(b>f-g&&b<f+g){if(e==a){return null}var h=jQuery.tableDnD.currentTable.tableDnDConfig;if(h.onAllowDrop){if(h.onAllowDrop(a,e)){return e}else{return null}}else{var i=$(e).hasClass("nodrop");if(!i){return e}else{return null}}return e}}return null},mouseup:function(a){if(jQuery.tableDnD.currentTable&&jQuery.tableDnD.dragObject){var b=jQuery.tableDnD.dragObject;var c=jQuery.tableDnD.currentTable.tableDnDConfig;if(c.onDragClass){jQuery(b).removeClass(c.onDragClass)}else{jQuery(b).css(c.onDropStyle)}jQuery.tableDnD.dragObject=null;if(c.onDrop){c.onDrop(jQuery.tableDnD.currentTable,b)}jQuery.tableDnD.currentTable=null}},serialize:function(){if(jQuery.tableDnD.currentTable){return jQuery.tableDnD.serializeTable(jQuery.tableDnD.currentTable)}else{return"Error: No Table id set, you need to set an id on your table and every row"}},serializeTable:function(a){var b="";var c=a.id;var d=a.rows;for(var e=0;e<d.length;e++){if(b.length>0)b+="&";var f=d[e].id;if(f&&f&&a.tableDnDConfig&&a.tableDnDConfig.serializeRegexp){f=f.match(a.tableDnDConfig.serializeRegexp)[0]}b+=c+"[]="+d[e].id}return b},serializeTables:function(){var a="";this.each(function(){a+=jQuery.tableDnD.serializeTable(this)});return a}};jQuery.fn.extend({tableDnD:jQuery.tableDnD.build,tableDnDUpdate:jQuery.tableDnD.updateTables,tableDnDSerialize:jQuery.tableDnD.serializeTables});(function(a){function b(){var a="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(a)}else if(window.opera&&window.opera.postError){window.opera.postError(a)}}a.fn.ajaxSubmit=function(c){function t(e){function C(c){if(o.aborted||B){return}try{z=w(n)}catch(d){b("cannot access response document: ",d);c=v}if(c===u&&o){o.abort("timeout");return}else if(c==v&&o){o.abort("server abort");return}if(!z||z.location.href==j.iframeSrc){if(!r)return}n.detachEvent?n.detachEvent("onload",C):n.removeEventListener("load",C,false);var e="success",f;try{if(r){throw"timeout"}var g=j.dataType=="xml"||z.XMLDocument||a.isXMLDoc(z);b("isXml="+g);if(!g&&window.opera&&(z.body==null||z.body.innerHTML=="")){if(--A){b("requeing onLoad callback, DOM not available");setTimeout(C,250);return}}var h=z.body?z.body:z.documentElement;o.responseText=h?h.innerHTML:null;o.responseXML=z.XMLDocument?z.XMLDocument:z;if(g)j.dataType="xml";o.getResponseHeader=function(a){var b={"content-type":j.dataType};return b[a]};if(h){o.status=Number(h.getAttribute("status"))||o.status;o.statusText=h.getAttribute("statusText")||o.statusText}var i=j.dataType||"";var l=/(json|script|text)/.test(i.toLowerCase());if(l||j.textarea){var p=z.getElementsByTagName("textarea")[0];if(p){o.responseText=p.value;o.status=Number(p.getAttribute("status"))||o.status;o.statusText=p.getAttribute("statusText")||o.statusText}else if(l){var q=z.getElementsByTagName("pre")[0];var t=z.getElementsByTagName("body")[0];if(q){o.responseText=q.textContent?q.textContent:q.innerHTML}else if(t){o.responseText=t.innerHTML}}}else if(j.dataType=="xml"&&!o.responseXML&&o.responseText!=null){o.responseXML=D(o.responseText)}try{y=F(o,j.dataType,j)}catch(c){e="parsererror";o.error=f=c||e}}catch(c){b("error caught: ",c);e="error";o.error=f=c||e}if(o.aborted){b("upload aborted");e=null}if(o.status){e=o.status>=200&&o.status<300||o.status===304?"success":"error"}if(e==="success"){j.success&&j.success.call(j.context,y,"success",o);k&&a.event.trigger("ajaxSuccess",[o,j])}else if(e){if(f==undefined)f=o.statusText;j.error&&j.error.call(j.context,o,e,f);k&&a.event.trigger("ajaxError",[o,j,f])}k&&a.event.trigger("ajaxComplete",[o,j]);if(k&&!--a.active){a.event.trigger("ajaxStop")}j.complete&&j.complete.call(j.context,o,e);B=true;if(j.timeout)clearTimeout(s);setTimeout(function(){if(!j.iframeTarget)m.remove();o.responseXML=null},100)}function x(){function h(){try{var a=w(n).readyState;b("state = "+a);if(a.toLowerCase()=="uninitialized")setTimeout(h,50)}catch(c){b("Server abort: ",c," (",c.name,")");C(v);s&&clearTimeout(s);s=undefined}}var c=g.attr("target"),e=g.attr("action");f.setAttribute("target",l);if(!d){f.setAttribute("method","POST")}if(e!=j.url){f.setAttribute("action",j.url)}if(!j.skipEncodingOverride&&(!d||/post/i.test(d))){g.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(j.timeout){s=setTimeout(function(){r=true;C(u)},j.timeout)}var i=[];try{if(j.extraData){for(var k in j.extraData){i.push(a('<input type="hidden" name="'+k+'" />').attr("value",j.extraData[k]).appendTo(f)[0])}}if(!j.iframeTarget){m.appendTo("body");n.attachEvent?n.attachEvent("onload",C):n.addEventListener("load",C,false)}setTimeout(h,15);f.submit()}finally{f.setAttribute("action",e);if(c){f.setAttribute("target",c)}else{g.removeAttr("target")}a(i).remove()}}function w(a){var b=a.contentWindow?a.contentWindow.document:a.contentDocument?a.contentDocument:a.document;return b}var f=g[0],h,i,j,k,l,m,n,o,p,q,r,s;var t=!!a.fn.prop;if(e){for(i=0;i<e.length;i++){h=a(f[e[i].name]);h[t?"prop":"attr"]("disabled",false)}}if(a(":input[name=submit],:input[id=submit]",f).length){alert('Error: Form elements must not have name or id of "submit".');return}j=a.extend(true,{},a.ajaxSettings,c);j.context=j.context||j;l="jqFormIO"+(new Date).getTime();if(j.iframeTarget){m=a(j.iframeTarget);q=m.attr("name");if(q==null)m.attr("name",l);else l=q}else{m=a('<iframe name="'+l+'" src="'+j.iframeSrc+'" />');m.css({position:"absolute",top:"-1000px",left:"-1000px"})}n=m[0];o={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(c){var d=c==="timeout"?"timeout":"aborted";b("aborting upload... "+d);this.aborted=1;m.attr("src",j.iframeSrc);o.error=d;j.error&&j.error.call(j.context,o,d,c);k&&a.event.trigger("ajaxError",[o,j,d]);j.complete&&j.complete.call(j.context,o,d)}};k=j.global;if(k&&!(a.active++)){a.event.trigger("ajaxStart")}if(k){a.event.trigger("ajaxSend",[o,j])}if(j.beforeSend&&j.beforeSend.call(j.context,o,j)===false){if(j.global){a.active--}return}if(o.aborted){return}p=f.clk;if(p){q=p.name;if(q&&!p.disabled){j.extraData=j.extraData||{};j.extraData[q]=p.value;if(p.type=="image"){j.extraData[q+".x"]=f.clk_x;j.extraData[q+".y"]=f.clk_y}}}var u=1;var v=2;if(j.forceSync){x()}else{setTimeout(x,10)}var y,z,A=50,B;var D=a.parseXML||function(a,b){if(window.ActiveXObject){b=new ActiveXObject("Microsoft.XMLDOM");b.async="false";b.loadXML(a)}else{b=(new DOMParser).parseFromString(a,"text/xml")}return b&&b.documentElement&&b.documentElement.nodeName!="parsererror"?b:null};var E=a.parseJSON||function(a){return window["eval"]("("+a+")")};var F=function(b,c,d){var e=b.getResponseHeader("content-type")||"",f=c==="xml"||!c&&e.indexOf("xml")>=0,g=f?b.responseXML:b.responseText;if(f&&g.documentElement.nodeName==="parsererror"){a.error&&a.error("parsererror")}if(d&&d.dataFilter){g=d.dataFilter(g,c)}if(typeof g==="string"){if(c==="json"||!c&&e.indexOf("json")>=0){g=E(g)}else if(c==="script"||!c&&e.indexOf("javascript")>=0){a.globalEval(g)}}return g}}if(!this.length){b("ajaxSubmit: skipping submit process - no element selected");return this}var d,e,f,g=this;if(typeof c=="function"){c={success:c}}d=this.attr("method");e=this.attr("action");f=typeof e==="string"?a.trim(e):"";f=f||window.location.href||"";if(f){f=(f.match(/^([^#]+)/)||[])[1]}c=a.extend(true,{url:f,success:a.ajaxSettings.success,type:d||"GET",iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},c);var h={};this.trigger("form-pre-serialize",[this,c,h]);if(h.veto){b("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(c.beforeSerialize&&c.beforeSerialize(this,c)===false){b("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var i,j,k=this.formToArray(c.semantic);if(c.data){c.extraData=c.data;for(i in c.data){if(a.isArray(c.data[i])){for(var l in c.data[i]){k.push({name:i,value:c.data[i][l]})}}else{j=c.data[i];j=a.isFunction(j)?j():j;k.push({name:i,value:j})}}}if(c.beforeSubmit&&c.beforeSubmit(k,this,c)===false){b("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[k,this,c,h]);if(h.veto){b("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var m=a.param(k);if(c.type.toUpperCase()=="GET"){c.url+=(c.url.indexOf("?")>=0?"&":"?")+m;c.data=null}else{c.data=m}var n=[];if(c.resetForm){n.push(function(){g.resetForm()})}if(c.clearForm){n.push(function(){g.clearForm()})}if(!c.dataType&&c.target){var o=c.success||function(){};n.push(function(b){var d=c.replaceTarget?"replaceWith":"html";a(c.target)[d](b).each(o,arguments)})}else if(c.success){n.push(c.success)}c.success=function(a,b,d){var e=c.context||c;for(var f=0,h=n.length;f<h;f++){n[f].apply(e,[a,b,d||g,g])}};var p=a("input:file",this).length>0;var q="multipart/form-data";var r=g.attr("enctype")==q||g.attr("encoding")==q;if(c.iframe!==false&&(p||c.iframe||r)){if(c.closeKeepAlive){a.get(c.closeKeepAlive,function(){t(k)})}else{t(k)}}else{if(a.browser.msie&&d=="get"){var s=g[0].getAttribute("method");if(typeof s==="string")c.type=s}a.ajax(c)}this.trigger("form-submit-notify",[this,c]);return this};a.fn.ajaxForm=function(c){if(this.length===0){var d={s:this.selector,c:this.context};if(!a.isReady&&d.s){b("DOM not ready, queuing ajaxForm");a(function(){a(d.s,d.c).ajaxForm(c)});return this}b("terminating; zero elements found by selector"+(a.isReady?"":" (DOM not ready)"));return this}return this.ajaxFormUnbind().bind("submit.form-plugin",function(b){if(!b.isDefaultPrevented()){b.preventDefault();a(this).ajaxSubmit(c)}}).bind("click.form-plugin",function(b){var c=b.target;var d=a(c);if(!d.is(":submit,input:image")){var e=d.closest(":submit");if(e.length==0){return}c=e[0]}var f=this;f.clk=c;if(c.type=="image"){if(b.offsetX!=undefined){f.clk_x=b.offsetX;f.clk_y=b.offsetY}else if(typeof a.fn.offset=="function"){var g=d.offset();f.clk_x=b.pageX-g.left;f.clk_y=b.pageY-g.top}else{f.clk_x=b.pageX-c.offsetLeft;f.clk_y=b.pageY-c.offsetTop}}setTimeout(function(){f.clk=f.clk_x=f.clk_y=null},100)})};a.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};a.fn.formToArray=function(b){var c=[];if(this.length===0){return c}var d=this[0];var e=b?d.getElementsByTagName("*"):d.elements;if(!e){return c}var f,g,h,i,j,k,l;for(f=0,k=e.length;f<k;f++){j=e[f];h=j.name;if(!h){continue}if(b&&d.clk&&j.type=="image"){if(!j.disabled&&d.clk==j){c.push({name:h,value:a(j).val()});c.push({name:h+".x",value:d.clk_x},{name:h+".y",value:d.clk_y})}continue}i=a.fieldValue(j,true);if(i&&i.constructor==Array){for(g=0,l=i.length;g<l;g++){c.push({name:h,value:i[g]})}}else if(i!==null&&typeof i!="undefined"){c.push({name:h,value:i})}}if(!b&&d.clk){var m=a(d.clk),n=m[0];h=n.name;if(h&&!n.disabled&&n.type=="image"){c.push({name:h,value:m.val()});c.push({name:h+".x",value:d.clk_x},{name:h+".y",value:d.clk_y})}}return c};a.fn.formSerialize=function(b){return a.param(this.formToArray(b))};a.fn.fieldSerialize=function(b){var c=[];this.each(function(){var d=this.name;if(!d){return}var e=a.fieldValue(this,b);if(e&&e.constructor==Array){for(var f=0,g=e.length;f<g;f++){c.push({name:d,value:e[f]})}}else if(e!==null&&typeof e!="undefined"){c.push({name:this.name,value:e})}});return a.param(c)};a.fn.fieldValue=function(b){for(var c=[],d=0,e=this.length;d<e;d++){var f=this[d];var g=a.fieldValue(f,b);if(g===null||typeof g=="undefined"||g.constructor==Array&&!g.length){continue}g.constructor==Array?a.merge(c,g):c.push(g)}return c};a.fieldValue=function(b,c){var d=b.name,e=b.type,f=b.tagName.toLowerCase();if(c===undefined){c=true}if(c&&(!d||b.disabled||e=="reset"||e=="button"||(e=="checkbox"||e=="radio")&&!b.checked||(e=="submit"||e=="image")&&b.form&&b.form.clk!=b||f=="select"&&b.selectedIndex==-1)){return null}if(f=="select"){var g=b.selectedIndex;if(g<0){return null}var h=[],i=b.options;var j=e=="select-one";var k=j?g+1:i.length;for(var l=j?g:0;l<k;l++){var m=i[l];if(m.selected){var n=m.value;if(!n){n=m.attributes&&m.attributes["value"]&&!m.attributes["value"].specified?m.text:m.value}if(j){return n}h.push(n)}}return h}return a(b).val()};a.fn.clearForm=function(){return this.each(function(){a("input,select,textarea",this).clearFields()})};a.fn.clearFields=a.fn.clearInputs=function(){var a=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var b=this.type,c=this.tagName.toLowerCase();if(a.test(b)||c=="textarea"){this.value=""}else if(b=="checkbox"||b=="radio"){this.checked=false}else if(c=="select"){this.selectedIndex=-1}})};a.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||typeof this.reset=="object"&&!this.reset.nodeType){this.reset()}})};a.fn.enable=function(a){if(a===undefined){a=true}return this.each(function(){this.disabled=!a})};a.fn.selected=function(b){if(b===undefined){b=true}return this.each(function(){var c=this.type;if(c=="checkbox"||c=="radio"){this.checked=b}else if(this.tagName.toLowerCase()=="option"){var d=a(this).parent("select");if(b&&d[0]&&d[0].type=="select-one"){d.find("option").selected(false)}this.selected=b}})};})(jQuery);jQuery.fn.corners=function(a){function B(a,b,c,d){var e=document.createElement("div");e.style.fontSize="1px";var f=document.createElement("div");f.style.overflow="hidden";f.style.height="1px";f.style.borderColor=c;f.style.borderStyle="none solid";var g=b.sizex-1;var h=b.sizey-1;if(!h){h=1}for(var i=0;i<b.sizey;i++){var j=g-Math.floor(Math.sqrt(1-Math.pow(1-i/h,2))*g);if(i==2&&b.sizex==6&&b.sizey==6){j=2}var k=f.cloneNode(false);k.style.borderWidth="0 "+j+"px";if(d){k.style.borderWidth="0 "+(b.tr?j:0)+"px 0 "+(b.tl?j:0)+"px"}else{k.style.borderWidth="0 "+(b.br?j:0)+"px 0 "+(b.bl?j:0)+"px"}d?e.appendChild(k):e.insertBefore(k,e.firstChild)}if(d){a.insertBefore(e,a.firstChild)}else{a.appendChild(e)}}function A(a,b,c,d,e){if(d&&!a.tl){b.style.marginLeft=0}if(d&&!a.tr){b.style.marginRight=0}if(!d&&!a.bl){b.style.marginLeft=0}if(!d&&!a.br){b.style.marginRight=0}b.style.backgroundColor=e;if(d){c.appendChild(b)}else{c.insertBefore(b,c.firstChild)}}function z(a,b,c,d,e){var f,g;var h=document.createElement("div");h.style.fontSize="1px";h.style.backgroundColor=c;var i=0;for(f=1;f<=b.sizey;f++){var j,k,l;arc=Math.sqrt(1-Math.pow(1-f/b.sizey,2))*b.sizex;var m=b.sizex-Math.ceil(arc);var n=Math.floor(i);var o=b.sizex-m-n;var p=document.createElement("div");var q=h;p.style.margin="0px "+m+"px";p.style.height="1px";p.style.overflow="hidden";for(g=1;g<=o;g++){if(g==1){if(g==o){j=(arc+i)*.5-n}else{k=Math.sqrt(1-Math.pow(1-(m+1)/b.sizex,2))*b.sizey;j=(k-(b.sizey-f))*(arc-n-o+1)*.5}}else{if(g==o){k=Math.sqrt(1-Math.pow((b.sizex-m-g+1)/b.sizex,2))*b.sizey;j=1-(1-(k-(b.sizey-f)))*(1-(i-n))*.5}else{l=Math.sqrt(1-Math.pow((b.sizex-m-g)/b.sizex,2))*b.sizey;k=Math.sqrt(1-Math.pow((b.sizex-m-g+1)/b.sizex,2))*b.sizey;j=(k+l)*.5-(b.sizey-f)}}A(b,p,q,e,x(c,d,j));q=p;var p=q.cloneNode(false);p.style.margin="0px 1px"}A(b,p,q,e,d);i=arc}if(e){a.insertBefore(h,a.firstChild)}else{a.appendChild(h)}}function y(a,b,c,d,e){if(b.transparent){B(a,b,c,e)}else{z(a,b,c,d,e)}}function x(a,b,c){var d=Array(parseInt("0x"+a.substring(1,3)),parseInt("0x"+a.substring(3,5)),parseInt("0x"+a.substring(5,7)));var e=Array(parseInt("0x"+b.substring(1,3)),parseInt("0x"+b.substring(3,5)),parseInt("0x"+b.substring(5,7)));r="0"+Math.round(d[0]+(e[0]-d[0])*c).toString(16);g="0"+Math.round(d[1]+(e[1]-d[1])*c).toString(16);b="0"+Math.round(d[2]+(e[2]-d[2])*c).toString(16);return"#"+r.substring(r.length-2)+g.substring(g.length-2)+b.substring(b.length-2)}function w(a,b){var a=a||"";var c={sizex:5,sizey:5,tl:false,tr:false,bl:false,br:false,webkit:true,mozilla:true,transparent:false};if(b){c.sizex=b.sizex;c.sizey=b.sizey;c.webkit=b.webkit;c.transparent=b.transparent;c.mozilla=b.mozilla}var d=false;var e=false;jQuery.each(a.split(" "),function(a,b){b=b.toLowerCase();var f=parseInt(b);if(f>0&&b==f+"px"){c.sizey=f;if(!d){c.sizex=f}d=true}else{switch(b){case"no-native":c.webkit=c.mozilla=false;break;case"webkit":c.webkit=true;break;case"no-webkit":c.webkit=false;break;case"mozilla":c.mozilla=true;break;case"no-mozilla":c.mozilla=false;break;case"anti-alias":c.transparent=false;break;case"transparent":c.transparent=true;break;case"top":e=c.tl=c.tr=true;break;case"right":e=c.tr=c.br=true;break;case"bottom":e=c.bl=c.br=true;break;case"left":e=c.tl=c.bl=true;break;case"top-left":e=c.tl=true;break;case"top-right":e=c.tr=true;break;case"bottom-left":e=c.bl=true;break;case"bottom-right":e=c.br=true;break}}});if(!e){if(!b){c.tl=c.tr=c.bl=c.br=true}else{c.tl=b.tl;c.tr=b.tr;c.bl=b.bl;c.br=b.br}}return c}function v(a){var b=255;var c="";var d;var e=/([0-9]+)[, ]+([0-9]+)[, ]+([0-9]+)/;var f=e.exec(a);for(d=1;d<4;d++){c+=("0"+parseInt(f[d]).toString(16)).slice(-2)}return"#"+c}function u(a){return"#"+a.substring(1,2)+a.substring(1,2)+a.substring(2,3)+a.substring(2,3)+a.substring(3,4)+a.substring(3,4)}function t(a){try{var b=jQuery.css(a,"background-color");if(b.match(/^(transparent|rgba\(0,\s*0,\s*0,\s*0\))$/i)&&a.parentNode){return t(a.parentNode)}if(b==null){return"#ffffff"}if(b.indexOf("rgb")>-1){b=v(b)}if(b.length==4){b=u(b)}return b}catch(c){return"#ffffff"}}function s(a,b){var c=document.createElement(a);c.style.border="none";c.style.borderCollapse="collapse";c.style.borderSpacing=0;c.style.padding=0;c.style.margin=0;if(b){c.style.verticalAlign=b}return c}function q(a,b,c,d){if(c.indexOf("px")<0){try{console.error("%s padding not in pixels",d?"top":"bottom",a)}catch(e){}c=b.sizey+"px"}c=parseInt(c);if(c-b.sizey<0){try{console.error("%s padding is %ipx for %ipx corner:",d?"top":"bottom",c,b.sizey,a)}catch(e){}c=b.sizey}return c-b.sizey+"px"}function p(a,b,c){var d=jQuery(a);var e;while(e=a.firstChild){c.appendChild(e)}if(a.style.height){var f=parseInt(d.css("height"));c.style.height=f+"px";f+=parseInt(d.css("padding-top"))+parseInt(d.css("padding-bottom"));a.style.height=f+"px"}if(a.style.width){var g=parseInt(d.css("width"));c.style.width=g+"px";g+=parseInt(d.css("padding-left"))+parseInt(d.css("padding-right"));a.style.width=g+"px"}c.style.paddingLeft=d.css("padding-left");c.style.paddingRight=d.css("padding-right");if(b.tl||b.tr){c.style.paddingTop=q(a,b,d.css("padding-top"),true)}else{c.style.paddingTop=d.css("padding-top")}if(b.bl||b.br){c.style.paddingBottom=q(a,b,d.css("padding-bottom"),false)}else{c.style.paddingBottom=d.css("padding-bottom")}a.style.padding=0;return c}function o(a,b,c,d){var e=p(a,b,document.createElement("div"));a.appendChild(e);if(b.tl||b.tr){y(a,b,c,d,true)}if(b.bl||b.br){y(a,b,c,d,false)}}function n(){jQuery(this).parent("form").each(function(){this.submit()});return false}function m(a){var b=document.createElement("a");b.id=a.id;b.className=a.className;if(a.onclick){b.href="javascript:";b.onclick=a.onclick}else{jQuery(a).parent("form").each(function(){b.href=this.action});b.onclick=n}var c=document.createTextNode(a.value);b.appendChild(c);a.parentNode.replaceChild(b,a);return b}function l(){if(!this.parentNode.onclick){this.parentNode.click()}}function k(a,b,c,d){var e=s("table");var f=s("tbody");e.appendChild(f);var g=s("tr");var h=s("td","top");g.appendChild(h);var i=s("tr");var j=p(a,b,s("td"));i.appendChild(j);var k=s("tr");var m=s("td","bottom");k.appendChild(m);if(b.tl||b.tr){f.appendChild(g);y(h,b,c,d,true)}f.appendChild(i);if(b.bl||b.br){f.appendChild(k);y(m,b,c,d,false)}a.appendChild(e);if(jQuery.browser.msie){e.onclick=l}a.style.overflow="hidden"}function j(a,b){var c=""+b.sizex+"px";var d=jQuery(a);if(b.tl){d.css("-moz-border-radius-topleft",c)}if(b.tr){d.css("-moz-border-radius-topright",c)}if(b.bl){d.css("-moz-border-radius-bottomleft",c)}if(b.br){d.css("-moz-border-radius-bottomright",c)}}function i(a,b){var c=""+b.sizex+"px "+b.sizey+"px";var d=jQuery(a);if(b.tl){d.css("WebkitBorderTopLeftRadius",c)}if(b.tr){d.css("WebkitBorderTopRightRadius",c)}if(b.bl){d.css("WebkitBorderBottomLeftRadius",c)}if(b.br){d.css("WebkitBorderBottomRightRadius",c)}}var b="rounded_by_jQuery_corners";var c=w(a);var d=false;try{d=document.body.style.WebkitBorderRadius!==undefined;var e=navigator.userAgent.indexOf("Chrome");if(e>=0){d=false}}catch(f){}var h=false;try{h=document.body.style.MozBorderRadius!==undefined;var e=navigator.userAgent.indexOf("Firefox");if(e>=0&&parseInt(navigator.userAgent.substring(e+8))<3){h=false}}catch(f){}return this.each(function(a,e){$e=jQuery(e);if($e.hasClass(b)){return}$e.addClass(b);var f=/{(.*)}/.exec(e.className);var g=f?w(f[1],c):c;var l=e.nodeName.toLowerCase();if(l=="input"){e=m(e)}if(d&&g.webkit){i(e,g)}else{if(h&&g.mozilla&&g.sizex==g.sizey){j(e,g)}else{var n=t(e.parentNode);var p=t(e);switch(l){case"a":case"input":k(e,g,n,p);break;default:o(e,g,n,p);break}}}})}