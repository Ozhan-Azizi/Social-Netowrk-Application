
    $(function(){
    $(".perPic").slice(0, 1).show(); // select the first ten
    $("#load").click(function(e){ // click event for load more
        e.preventDefault();
        $(".perPic:hidden").slice(0, 1).show(); // select next 10 hidden divs and show them
        if($(".perPic:hidden").length == 0){ // check if any hidden divs still exist
            alert("All are shown"); // alert if there are none left
        }
    });
});



