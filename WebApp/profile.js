
function submitChat()
{
    if(form3.text.value =='' || form3.name.value =='') 
    {
        alert("Fields are not filled in");
        return;
    }
    var details = form3.text.value;
    var name = form3.name.value;

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
            document.getElementById('inputDetails').innerHTML = xmlhttp.responseText;
        }
    }
    
    xmlhttp.open('GET', 'insertDetails.php?details='+details+'&name='+name, true);
    xmlhttp.send();


}
            