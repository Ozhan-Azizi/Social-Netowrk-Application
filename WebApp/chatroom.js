
function addToChat($titleOfChat, $user)
{
    if(form1.message.value =='')
    {
        alert("Fields are not filled in");
        return;
    }
    
    var msg = form1.message.value;
    
    var title = $titleOfChat;
    var uname = $user;
    
    if(title == '')
    {
        alert("Chat room don't exist");
        return;
    }
    
    if(uname == '')
    {
        alert("You are not logged in. Please log in");
        return;
    }

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
            document.getElementById('chatlogs').innerHTML = xmlhttp.responseText;
        }
    }
    
    xmlhttp.open('GET', 'chatinsert.php?uname='+uname+'&msg='+msg+'&title='+title, true);
    xmlhttp.send();


}
            