<?php
require_once 'all.php';

if($loggedin)
{
     die("<!DOCTYPE html>
  <html>
  <head>
      <title>Log in</title>
  </head>
    <body class='overbody'>
        <link type='text/css' rel='stylesheet' href='login.css'/>  
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
           
        <div class='topbar'>
        <div class='container'>
                <ul class='menu'>
                    <li><a href='index.php'>Home</a></li>
                    <li><a href='thread.php'>Create Thread</a></li>
                    <li><a href='createLiveThread.php'>Create Live Thread</a></li>
                    <li><a href='groups.php'>Groups</a></li>
                    <li><a href='allChatRooms.php'>Chat</a></li>
                    <li><a href='activity.php'>Activity</a></li>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle'>Me<b class='caret'></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href='profile.php'>Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='myvideos.php'>My videos</a></li>
                            <li><a href='watching.php'>Watching</a></li>
                            <li><a href='watchers.php'>My Watchers</a></li>
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
            <div class='logmain'>
                You are already logged in. Please <a href='profile.php?view=$user'>
                    click here</a> to continue.<br><br>
            </div>
        </div>");
}

  $error = $user = $password = $email = "";
    
if(isset($_POST['user']))
{
    $user = sanitizeString($_POST['user']);
    $password = sanitizeString($_POST['password']);
    $email = sanitizeString($_POST['email']);
    
    if($user == '' || $email == '' || $password == '')
    {
        $error = "All fields are not entered<br><br>";
    }
    else
    {
        $result = queryMySQL("SELECT user,password,email FROM members WHERE user='$user' AND password='$password' AND email='$email'");
    }
    
    if ($result->num_rows == 0)
    {
        $error = "<span class='error'>Username/Password/email invalid</span><br><br>";
    }
    else
    {
        $_SESSION['user'] = $user;
        $_SESSION['password'] = $password;
        $_SESSION['email'] = $email;
        $loggedin = TRUE;
        die("<!DOCTYPE html>
  <html>
  <head>
      <title>Log in</title>
  </head>
    <body class='overbody'>
        <link type='text/css' rel='stylesheet' href='login.css'/>  
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
           
        <div class='topbar'>
        <div class='container'>
                <ul class='menu'>
                    <li><a href='index.php'>Home</a></li>
                    <li><a href='thread.php'>Create Thread</a></li>
                    <li><a href='createLiveThread.php'>Create Live Thread</a></li>
                    <li><a href='groups.php'>Groups</a></li>
                    <li><a href='allChatRooms.php'>Chat</a></li>
                    <li><a href='activity.php'>Activity</a></li>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle'>Me<b class='caret'></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href='profile.php'>Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='viewWatching.php'>Watching</a></li>
                            <li><a href='viewWatchers.php'>My Watchers</a></li>
                            <li><a href='groups.php'>Groups</a></li>
                            <li><a href='logout.php'>Log out</a></li>
			    <li><a href='messages.php'>Messages</a></li>
                        </ul>
                    </li>
                </ul>
                
        </div>
        </div>
        <div class='inputEverything'>
            <div class='logmain'>
                You are now logged in. Please <a href='profile.php?view=$user'>
                    click here</a> to continue.<br><br>
            </div>
        </div>");
    }
    
}


echo <<<_A
  <!DOCTYPE html>
  <html>
  <head>
      <title>Log in</title>
  </head>
    <body class='overbody'>
        <link type='text/css' rel='stylesheet' href='login.css'/>  
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
           
        <div class='topbar'>
        <div class='container'>
                <ul class='menu'>
                    <li><a href='index.php'>Home</a></li>
		    <li><a href='signup.php'>Sign up</a></li>
                    <li class="dropdown">
                        <a href='#' class='dropdown-toggle'>Me<b class='caret'></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href='profile.php'>Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='viewWatching.php'>Watching</a></li>
                            <li><a href='viewWatchers.php'>My Watchers</a></li>
                            <li><a href='groups.php'>Groups</a></li>
                            <li><a href='chooseThreads.php'>Choose Threads</a></li>
                            <li><a href='logout.php'>Log out</a></li>
			    <li><a href='messages.php'>Messages</a></li>
                        </ul>
                    </li>
                </ul>
                
        </div>
        </div>
        <div class='inputEverything'>
            <div class='logmain'>
                    Enter your details to login!<br><br>
                    <form method='post' action='login.php'>$error

                    <span class='fieldname'>Account Name:</span>
                    <input type='text' maxlength='20' name='user' value='$user'

                        onBlur='checkUser(this)'<span id='info'></span><br>

                    <span class='fieldname'>Password:</span>
                    <input type='text' maxlength='20' name='password' value='$password'><br> 

                    <span class='fieldname'>Email:</span>
                    <input type='text' maxlength='20' name='email' value='$email'><br>  

                    <span class='fieldname'>&nbsp;</span>
                    <input type='submit' value='Log in'>
                    </form>
            </div>
        </div>

</body>
</html>
_A;


?>
