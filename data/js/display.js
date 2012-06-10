$(document).ready(function(){

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
});