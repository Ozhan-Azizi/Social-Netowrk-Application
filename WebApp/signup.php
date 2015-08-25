<?php

    require_once 'all.php';
   // require_once 'ajax-lib.js';
if($loggedin)
{
    die("
<!DOCTYPE html>
<html>
    <head>
        <title>View Watching list</title>
        <link type='text/css' rel='stylesheet' href='findMembers.css'/>  
        <link rel='stylesheet' href='jquery-ui.min.css'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
        <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='alternate' type='application/rss+xml' title='RSS' href='http://www.csszengarden.com/zengarden.xml'>
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script type='text/javascript' src='jquery.js'></script>
        <script src='menu.js'></script>
        <script src='filter.js'></script>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script src='insideFilter.js'></script>
    </head>
    <body class='overbody'>
            <div class='topbar'>
            <div class='container'>

                <ul class='menu'>
                    <li><a href='index.php'>Home</a></li>
                    <li><a href='activity.php'>Activity</a></li>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle'>Me<b class='caret'></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href='profile.php'>Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='myvideos.php'>My videos</a></li>
                            <li><a href='viewwatching.php'>Watching</a></li>
                            <li><a href='viewwatchers.php'>My Watchers</a></li>
                            <li><a href='groups.php'>My Groups</a></li>
                            <li><a href='mythread.php'>My Threads</a></li>
                            <li><a href='logout.php'>Log out</a></li>
                            <li><a href='messages.php'>Messages</a></li>
                        </ul>
                    </li>
                </ul>
                
            </div>
            </div>
            <div class='inputEverything'>
                <div class='members'>
            <div id='response'>Your are already logged in <br>
            <br> Please <a href='profile.php'>Click Here</a> to go to Profie Page <br>
            <br> Or Please <a href='index.php'>Click Here</a> to go to Home Page
            </div>
                </div>
            </div>
    </body>
</html>
    ");
}
    
    echo <<<_END
    <!DOCTYPE html>
    <head>
        <title>Sign up</title>
    </head>
    <body class='overbody'>
        <link type='text/css' rel='stylesheet' href='signup.css'/>  
        <link rel='stylesheet' href='jquery-ui.min.css'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
        <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
        <link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.csszengarden.com/zengarden.xml">
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script type='text/javascript' src='jquery.js'></script>
        <script src='menu.js'></script>
        <script src='filter.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src='insideFilter.js'></script>
    <script>
    function checkUser(user)
    {
        if(user.value=='')
        {
            0('info').innerHTML = ''
            return
        }
    
      params  = "user=" + user.value
      request = new ajaxRequest()
      request.open("POST", "checkuser.php", true)
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
      request.setRequestHeader("Content-length", params.length)
      request.setRequestHeader("Connection", "close")

      request.onreadystatechange = function()
      {
        if (this.readyState == 4)
          if (this.status == 200)
            if (this.responseText != null)
              O('info').innerHTML = this.responseText
      }
      request.send(params)
    }

    function ajaxRequest()
    {
      try { var request = new XMLHttpRequest() }
      catch(e1) {
        try { request = new ActiveXObject("Msxml2.XMLHTTP") }
        catch(e2) {
          try { request = new ActiveXObject("Microsoft.XMLHTTP") }
          catch(e3) {
            request = false
      } } }
      return request
    }
  </script>
    
_END;
    
    $error = '';
    $user = '';
    $email = '';
    $password = '';
    if($_SESSION['user'])
    {
        destroySession();
    }
    
    if(isset($_POST['user']))
    {
        $user = sanitizeString($_POST['user']);
      //  $email = sanitizeString($_POST['email']);
        $password = sanitizeString($_POST['password']);
        //die("reached");
        $email = ($_POST["email"]);
      //  die("reached");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format"; 
        }
        
        
        if($user == '' || $emailErr == "Invalid email format" || $password == '')
        {
            $error = "All fields are not entered";
        }
        else
        {
             $result = queryMysql("SELECT * FROM members WHERE user='$user'");
            
            if($result->num_rows)
            {
                $error = "That Account name already exist. Please choose another name.<br><br>";
            }
            else
            {
                queryMysql("INSERT INTO members VALUES('0', '$user', '$password', '$email', '1')");
                die("<h3 class='signbody'>Account Created<br><br>"
                        . "<br><a href='login.php'> Please Log in<br><br></a></h3>");
            }
        
        }
    }
    
    
    
    
    echo <<<_BL
    <div class='topbar'>
        <div class='container'>
                <ul class='menu'>
                    <li><a href='index.php'>Home</a></li>
                    <li><a href='thread.php'>Create Thread</a></li>
                    <li><a href='settings.php'>Settings</a></li>
                    <li><a href='groups.php'>Groups</a></li>
                    <li><a href='activity.php'>Activity</a></li>
                    <li><a href='otherUsers.php'>Uprising users</a></li>
                    <li><a href='members.php'>Members</a></li>
                    <li><a href='allChatRooms.php'>Chat</a></li>
                    <li class="dropdown">
                        <a href='#' class='dropdown-toggle'>Me<b class='caret'></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='myvideos.php'>My videos</a></li>
                            <li><a href='watching.php'>Watching</a></li>
                            <li><a href='watchers.php'>My Watchers</a></li>
                            <li><a href='groups.php'>My Groups</a></li>
                            <li><a href="mythread.php">My Threads</a></li>
                            <li><a href="logout.php">Log out</a></li>
                            <li><a href='messages.php'>Messages</a></li>
                        </ul>
                    </li>
                </ul>
                
        </div>
    </div>
    <div class='inputEverything'>
        <div class='signbody'>
        <h4 class='fieldname'>Enter your details to sign up!</h4>$error<br>
        <form method='post' action='signup.php'>
        <span class='fieldname'>Account Name:</span>
        <input type='text' maxlength='20' name='user' value='$user'
             onBlur='checkUser(this)'<span id='info'></span><br>
        <span class='fieldname'>Email:</span>
        <input type='text' maxlength='20' name='email' value='$email'><br>
        <span class='fieldname'>Password:</span>
        <input type='text' maxlength='20' name='password' value='$password'><br>            
        <span class='fieldname'>&nbsp;</span>
            <input type='submit' class='fieldname1' value='Sign up'>
        </form>
        </div>
    </div>
_BL;

?>

</body>
</html>