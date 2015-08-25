<?php

require_once 'all.php';
removeOldLiveThreads();
if(isset($_POST['liveTitle']))
{

    $liveTitle = sanitizeString($_POST['liveTitle']);
    $time = $_POST['checkBox'];
    $comment = $_POST['comment'];
	$comment = preg_replace('/\s\s+/', ' ', $comment);
    if($liveTitle == '' || $time == '' || $comment == '')
    {
        $error = "Not all details filled in!";
    }
    else 
    {   
        $result = queryMysql("SELECT * FROM liveThread WHERE title='$liveTitle'");
	$liveTitle = preg_replace('/\s\s+/', ' ', $liveTitle);
        if($result->num_rows)
        {
            $error = "This Thread is on going. Please join it";
        }
        else
        {
            queryMysql("INSERT INTO liveThread VALUES('0', '$liveTitle', NOW(), '$time', '$comment')");
            queryMysql("INSERT INTO activity VALUES ('0', '$user', 'Created a Live Thread', '$liveTitle', '$comment')");
		$error = "Live Thread Created. Go to home page, under filter section: click LIVE to view this";
        }   
    }
    addPointsToUser($user);
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
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create Live Thread</title>
        <link type='text/css' rel='stylesheet' href='createLiveThread.css'/>  
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
<?php
    showPictureHome($user);
?>
                <ul class='menu'>
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
    <div class='inputEverything'>
<?php
    echo <<<_END
        <form method='post' class='createLive' action='createLiveThread.php'>
            Title: <input type='text' name='liveTitle' value='$title'>$error<br><br>
            Comment : <br><textarea name='comment' rows='5' cols='79' value='$comment'></textarea><br>
                    <input id="checkbox" name="checkBox" type="checkbox" value="600" />10 miniutes<br>
                    <input id="checkbox" name="checkBox" type="checkbox" value="1800" />30 miniutes<br>
                    <input id="checkbox" name="checkBox" type="checkbox" value="3600" />60 miniutes<br>
            <input type='submit' value='Create'>
        </form> 
_END;
?>              
            
    </div>
    </body>
</html>
