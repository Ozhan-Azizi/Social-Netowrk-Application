$(function(){
    $.ajaxSetup({cache:false});
    setInterval(function(){$('.homeThread').load('refreshLowestComments.php');
    }, 10000);
    
    
});