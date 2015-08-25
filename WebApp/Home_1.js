$(function(){
    $(".insideThreads").slice(0, 5).show(); // select the first ten
    $("#load").click(function(e){ // click event for load more
        e.preventDefault();
        $(".insideThreads:hidden").slice(0, 5).show(); // select next 10 hidden divs and show them
        if($(".insideThreads:hidden").length == 0){ // check if any hidden divs still exist
            alert("No more divs"); // alert if there are none left
        }
    });
});