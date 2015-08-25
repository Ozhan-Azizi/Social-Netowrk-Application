

    $(function(){
    $(".insideThreads").slice(0, 1).show(); // select the first ten
    $("#load").click(function(e){ // click event for load more
        e.preventDefault();
        $(".insideThreads:hidden").slice(0, 2).show();// select next 10 hidden divs and show them
        $(".overbody").animate({
            height: +"72px"
        });
        if($(".insideThreads:hidden").length == 0){ // check if any hidden divs still exist
            alert("All are shown"); // alert if there are none left
        }
    });
});

