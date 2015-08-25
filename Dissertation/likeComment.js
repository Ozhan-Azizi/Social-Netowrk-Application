
function likeDislikeTheComment($cid, $likeOrNot, $displayThis)
{

    var cid = $cid;
    var likeOrNot = $likeOrNot;
    var displayThis = $displayThis;
    try {
        var xmlhttp = new XMLHttpRequest();
    }
    catch(e1) 
    {
        try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        }   
        catch(e2)
        {
            try
            {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e3) {
                xmlhttp = false;
                }
        }
    } 
    var number = $cid;
    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        {
            document.getElementById(+cid).innerHTML = xmlhttp.responseText;
        }
    }
    
    if(likeOrNot == 1)
    {
        xmlhttp.open('GET', 'likeinsert.php?cid='+cid, true);
        xmlhttp.send();
    }
    else
    {
        xmlhttp.open('GET', 'dislikeinsert.php?cid='+cid, true);
        xmlhttp.send();
    }
    
    

}

function refreshComments(){
    $.ajaxSetup({cache:false});
    setInterval(function(){$('.homeThread').load('refreshComments.php');
    }, 3000);
    
    
}
    
    
function refreshHighestComments(){
    $.ajaxSetup({cache:false});
    setInterval(function(){$('.homeThread').load('refreshHighestComments.php');
    }, 3000);
    
    
}

function refreshLowestComments(){
    $.ajaxSetup({cache:false});
    setInterval(function(){$('.homeThread').load('refreshLowestComments.php');
    }, 3000);
    
    
}