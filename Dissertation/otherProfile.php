<?php
require_once 'all.php';
$view='';
if (isset($_GET['view']))
{
    $view = sanitizeString($_GET['view']);
    
    if ($view == $user) 
    {
        $name = "Your";
    }
    else
    {
        $name = "$view's";
    }

    
}
else
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
            <div id='response'>Your are not logged in <br>
                    Profile was not found
            </div>
                </div>
            </div>
    </body>
</html>
    ");
}

if(isset($_POST['message']))
{
    $message1 = $_POST['message'];
    $creater = $_SESSION['user'];
    queryMysql("INSERT INTO message VALUES('0','$creater','$view', '$message1', 'no')");
    $sentOrNot = "Message has been sent!";
}
else
{
    $sentOrNot = "";
}

$resultScore = queryMysql("SELECT * FROM members WHERE user='$view'");
if($resultScore->num_rows)
{
    $rowScore = $resultScore->fetch_array(MYSQLI_ASSOC);
    $score = $rowScore['score'];
}
else
{
    $score = 0;
}

if(!$loggedin)
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
            <div id='response'>Your are not logged in <br>
            <br> Please <a href='login.php'>Click Here</a> to log in <br>
            <br> Or Please <a href='signup.php'>Click Here</a> to sign up
            </div>
                </div>
            </div>
    </body>
</html>
    ");
} // checked if logged in



$queryResult = queryMysql("SELECT * FROM profile where user='$view'");
//$num = $_SESSION['user_id'];
if ($queryResult->num_rows)
    {
        $row  = $queryResult->fetch_array(MYSQLI_ASSOC);
        $details = stripslashes($row['details']);
    }
    else
    {
        $details = "";
    }


echo <<<_END
<!DOCTYPE html>
<head>
    <title>$view Profile</title>
    <link href="otherProfile.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
    <link href="http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class='overbody'>
    
        <div class='topbar'>
            <div class='container'>
                <ul class='menu'>
                    <li>Viewing: $view</li>
                       <li><a href='index.php'>Home</a></li>
                    <li><a href='groups.php'>Groups</a></li>
                    <li><a href='activity.php'>Activity</a></li>
                    <li><a href='findMembers.php'>Find Members</a></li>
                    <li><a href='allChatRooms.php'>Chat</a></li>
                    <li><a href='thread.php'>Create Thread</a></li>
                    <li><a href='createLiveThread.php'>Create Live Thread</a></li>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle'>Me<b class='caret'></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href='profile.php'>Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='viewWatching.php'>Watching</a></li>
                            <li><a href='viewWatchers.php'>My Watchers</a></li>
                            <li><a href='groups.php'>My Groups</a></li>
                            <li><a href='chooseThreads.php'>Choose Threads</a></li>				
                            <li><a href='messages.php'>Messages</a></li>
                            <li><a href='logout.php'>Log out</a></li>
                        </ul>
                    </li>
                </ul>                
            </div>
        </div> 
            <div class='slider'><br><br>
                <div class='main'>
_END;
showPicture($view);
//$det = $_SESSION['details'];
    echo <<<_BA
                    
                    <div id='main2'>
                    </div>
                    <span id='score'>$view's SCORE : $score</span>
                </div>

                <div class='details'>
                <textarea rows='5' cols='50' readonly="readonly"> $details </textarea>

                
                <form method='post' action='otherProfile.php?view=$view'>
                    <textarea name='message' rows='5' cols='50' value='$message'> </textarea>
                    <input type='submit' value='Send message'>$sentOrNot
                </form>
                
                </div>
        <div class='coverBoxes'>
            <h6 class='watch'>WATCHING</h6>
            <div class='watchingBox'>
_BA;
            viewWatching($view);
    echo <<<_BA
            <br>
            <a id='viewMembers' href="viewWatching.php?view=$view">View All Watching Members</a>
            </div>            
                
            <h6 class='watch'><br>WATCHERS</h6>
            <div class='watchersBox'>
_BA;
            viewWatchers($view);
    echo <<<_BA
            <br>
            <a id='viewWatchers' href="viewWatchers.php?view=$view">View All Watchers</a>
            </div>
            
            <h6 class='watch'>MY GROUPS</h6>
            <div class='groupsBox'>
_BA;
                viewGroups($view);
    echo <<<_BA
                <br><a href='groups.php?view=$view'>View all my groups</a>
            </div>            
        </div>
            <form method='post' action='photos.php?view=$view'>
                <input type='submit' class='toPics' value='$view Photos'>
            </form>
            <div class='homeThread'>
_BA;
        displayProfileThreads($view);
    echo <<<_BA
            </div>
        
    </div>

            
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type='text/javascript' src='jquery.js'></script>
    <script src='menu.js'></script>       
</body>
</html>

_BA;
?>

