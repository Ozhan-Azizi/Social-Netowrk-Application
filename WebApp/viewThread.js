$(document).ready(function() {
    $('button').click(function() {
    	var toAdd = $("input[name=addComment]").val();
        $('#comments').append("<p>"+toAdd+"</p>");
    });
});