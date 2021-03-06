<?php
require_once 'all.php';

if(isset($_SESSION['user']))
{
    destroySession();
    echo <<<_A
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
                            <li><a href='chooseThreads.php'>Choose Threads</a></li>
                            <li><a href='logout.php'>Log out</a></li>
                            <li><a href='messages.php'>Messages</a></li>
                        </ul>
                    </li>
                </ul>
                
            </div>
            </div>
            <div class='inputEverything'>
                <div class='members'>
            <div id='response'>Your have logged out <br>
            <br> Please <a href='login.php'>Click Here</a> to log back in <br>
            <br> Or Please <a href='index.php'>Click Here</a> to go to Home Page
            </div>
                </div>
            </div>
    </body>
</html>
    
    
_A;
}
 else 
     {
     echo <<<_B
<!DOCTYPE html>
<html>
    <head>
        <title>Log out Page</title>
        <link type='text/css' rel='stylesheet' href='Home.css'/>  
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
    </head>
    <body class='overbody'>
        <div class='topbar'>
            <div class='container'>
                <ul class='menu'>
                    <li><a href='index.php'>Home</a></li>
                    <li><a href='signup.php'> Sign up</a></li>
                    <li><a href='login.php'>Log in</a></li>
                    <li><a href='profile.php'>Profile</a></li>
                    <li><a href='thread.php'>Create Thread</a></li>
                    <li><a href='allChatRooms.php'>Chat</a></li>
                    <li class="dropdown">
                        <a href='#' class='dropdown-toggle'>Me<b class="caret"></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='myvideos.php'>My videos</a></li>
                            <li><a href='watching.php'>Watching</a></li>
                            <li><a href='watchers.php'>My Watchers</a></li>
                            <li><a href='groups.php'>My Groups</a></li>
                            <li><a href="chooseThreads.php">Choose Threads</a></li>
                            <li><a href="logout.php">Log out</a></li>
                            <li><a href='messages.php'>Messages</a></li>
                        </ul>
                    </li>
                </ul>
                
            </div>
        </div>
        <div class='inputEverything'>
            <br><br><span id='response'>You are not logged in. <a href='index.php'>Click here to continue</a></span><br>
        </div>
    </body>
</html>
    
     
_B;
    }



?>
