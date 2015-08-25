$(document).ready(function(){
   $("#Drag").draggable();
   
    $('#menu').accordion({collapsible: true, active: false});
    
    $('.overMenuBefore').hover(function(){
    	$(this).addClass('overMenu');
    },
        function(){
        $(this).removeClass("overMenu");
        });
   
     $(".prf").hover(function(){
    $(this).addClass("active");
    },
    function(){
        $(this).removeClass("active");
    },
            
        $('button').click(function() {
    	var toAdd = $("input[name=message]").val();
        $('#messages').append("<p>"+toAdd+"</p>");
    })
            
  );
   
});