
$(function(){
    $.ajaxSetup({cache:false});
    setInterval(function(){$('#chatlogs').load('refreshChat.php');
    }, 1000);
    
    
});