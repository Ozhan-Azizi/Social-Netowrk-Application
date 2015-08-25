<?php

require_once 'all.php';

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
    $views = 0;
    $rate = 0;
   // $user = $_SESSION['user'];

    if(isset($_POST['title']))
    {
        $title = sanitizeString($_POST['title']);
        $comment = sanitizeString($_POST['comment']);
        $category = sanitizeString($_POST['category']);
        if($title == '' || $comment =='')
        {
            $error = "All fields are not entered<br>";
        }
        else
        {
		$title=	preg_replace('/\s\s+/', ' ', $title);
            $result = queryMysql("SELECT * FROM Thread WHERE title='$title'");
            if($result->num_rows)
            {
                $error = "This thread title was already taken";
            }
            else 
            {
            
            queryMysql("INSERT INTO Thread VALUES('0', '$title', '$comment', '$views','$rate','$user', '$category')");
            addPointsToUser($user);
            queryMysql("INSERT INTO activity VALUES ('0', '$user', 'New Thread Page', '$title', '$comment')");
                    die("<!DOCTYPE html>
  <html>
  <head>
      <title>Log in</title>
  </head>
    <body class='overbody'>
    <link type='text/css' rel='stylesheet' href='thread.css'/>  
        <link rel='stylesheet' href='jquery-ui.min.css'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
        <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script type='text/javascript' src='jquery.js'></script>
        <script src='menu.js'></script>
            <div class='topbar'>
        <div class='container'>
                <ul class='menu'>
                    <li><a href='index.php'>Home</a></li>
                    <li><a href='profile.php'>Profile</a></li>
                </ul>
                
        </div>
    </div>"
             .   "<div class='inputEverything'>"
                . "<div class='homeThread'>"
                . "Thread created. Please <a href='profile.php?view=$user'>" .
            "click here</a> to continue.<br><br></div></div>");
            }
            }
    }




echo <<<_END
<!DOCTYPE html>
<head>
    <title>Create Thread</title>
    <link type='text/css' rel='stylesheet' href='thread.css'/>  
    <link rel='stylesheet' href='jquery-ui.min.css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
    <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
    <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
    <script type='text/javascript' src='jquery.js'></script>
    <script src='menu.js'></script>
</head>
<body class='overbody'>
        <div class='topbar'>
            <div class='container'>
_END;
    showPictureHome($user);
    echo <<<_END
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
        <div class='homeThread'>
            <h3>Creating Thread</h3> 
            <form method='post' action='thread.php'>$error
                <br>
                <span class='fieldname'>Title:</span>
                <textarea name='title' cols='100' rows='2' value='$title'></textarea><br><br>
                <br>
                <span class='fieldname'>Comment:</span>
                <textarea name='comment' cols='100' rows='5' value='$comment'></textarea><br><br>
                
            
                <span class='fieldname'>Category:</span>
                <select name="category">
                    <option value="Random">Random</option>
                    <option value="Funny">Funny</option>
                    <option value="Controversial">Controversial</option>
                    <option value="TV_Shows">TV Shows</option>
                    <option value="Movies">Movies</option>
                    <option value="Games">Games</option>
                    <option value="Web_Video">Web video</option>
                </select>
                
                <input type='submit' class='post' value='SUBMIT'>
            </form>    
                    
        </div>
        </div>
</body>
</html>


_END;





?>

