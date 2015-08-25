$(function(){
    $.ajaxSetup({cache:false});
    setInterval(function(){$('.homeThread').load('refreshComments.php');
    }, 10000);
    
    
});