

function addComment()
{
    if(mycomment.message.value == ''|| mycomment.threadTitle.vaule =='')
    {
        alert("Fields are not filled in");
        return;
    }
    var title = mycomment.threadTitle.vaule;
    var comment = mycomment.message.value;
    
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
    
    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        {
           // document.getElementById('individualUser').innerHTML = xmlhttp.responseText;
        }
    }
        
}