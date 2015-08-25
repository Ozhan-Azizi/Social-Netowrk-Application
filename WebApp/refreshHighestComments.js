$(function(){
    $.ajaxSetup({cache:false});
    setInterval(function(){$('.homeThread').load('refreshHighestComments.php');
    }, 10000);
    
    
});