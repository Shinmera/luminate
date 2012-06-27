$(function(){
    $("body").append('<div style="position:absolute;display:none;box-shadow: 0 0 5px #000;max-width:50%;max-height:50%;" id="previewPost"></div>');
    $("a.postReference").hover(function(e){
        $("#previewPost").html("");
        $("#previewPost").css({"right":(-e.pageX+10+$(window).width())+"px","top":(e.pageY)+"px"});
        $.ajax({
            url: $("#proot").html()+'data/chan/'+$(this).attr("folder")+'/posts/'+$(this).attr("id")+'.php',
            success: function(data){
                $('#previewPost').html(data);
                $('#previewPost').css('display','inline-block');
            },
            error: function() {
                $.ajax({
                    url: $("#proot").html()+'data/chan/'+$(this).attr("folder")+'/posts/_'+$(this).attr("id")+'.php',
                    success: function(data) {
                        $("#previewPost").html(data);
                        $('#previewPost').css('display','inline-block');
                    }
                });
            }
        });
    },function(){
        $('#previewPost').css('display','none');
    });
});