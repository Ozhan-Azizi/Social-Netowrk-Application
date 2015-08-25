            
$(function(){$(".chatBox").focus(function(){
    if(this.value===this.defaultValue)
    {
        this.value="";
       $(this).css({'background-color':'rgb(250, 250, 250)'});
        $(this).css({'opacity':'1.0'});
    }
})
        .blur(function(){
            if(this.value === '')
    {
        this.value = this.defaultValue;
        
    }
});
    
});