$(function() {
    $("#apiForm").ajaxForm( {
        success: function(responseText, statusText, xhr, $form){
            $("#apiForm").parent().html("Response: "+responseText);
    }});
});