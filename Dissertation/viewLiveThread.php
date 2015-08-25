<?php
require_once 'all.php';
removeOldLiveThreads();

if(isset($_GET['id']))
{

    $convo_id = $_GET['id'];

	if($convo_id == '')
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
            <div id='response'>Live Thread has expired. Thanks for joining!<br>
            <br> Please <a href='index.php'>Click Here</a> to return to Home page
            </div>
                </div>
            </div>
    </body>
</html>
    ");
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
            <div id='response'>Live Thread has expired. Thanks for joining!<br>
            <br> Please <a href='index.php'>Click Here</a> to return to Home page
            </div>
                </div>
            </div>
    </body>
</html>
    ");
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

$user = $_SESSION['user'];
$result = queryMysql("SELECT * FROM liveThread where id='$convo_id'");
$row = $result->fetch_array(MYSQLI_ASSOC);
$title = $row['title'];
$comment = nl2br($row['comment']);
$timecreated = $row['time_created'];

    if(isset($_POST['increaseEach']))
    {   
        $user = $_SESSION['user'];
        $c = $_POST['increaseEach'];
        $together = "$user$c";
        $result4 = queryMysql("SELECT * FROM liveLikeComment where user='$together'");
        if ($result4->num_rows == 0)
        {
            queryMysql("INSERT INTO liveLikeComment VALUES('0', '$c', '$together', '1')");
        }
        else 
        {
            $row4 = $result4->fetch_array(MYSQLI_ASSOC);
            $like = $row4['rate'];
            if($like == 1)
            {
                queryMysql("UPDATE liveLikeComment SET rate='0' where user='$together' AND comment_id='$c'");
            }
            elseif($like == 0)
            {
                queryMysql("UPDATE liveLikeComment SET rate='1' where user='$together' AND comment_id='$c'");
            }
            elseif($like ==-1)
            {
                queryMysql("UPDATE liveLikeComment SET rate='1' where user='$together' AND comment_id='$c'");
            }
        }
        countLiveCommentLike($c);
    }

if(isset($_POST['decreaseEach']))
{   
    $user = $_SESSION['user'];
    $c = $_POST['decreaseEach'];
    $together = "$user$c";
    $result5 = queryMysql("SELECT * FROM liveLikeComment where user='$together'");
    if ($result5->num_rows == 0)
    {
        queryMysql("INSERT INTO liveLikeComment VALUES('0', '$c', '$together', '-1')");
    }
    else 
    {
        $row5 = $result5->fetch_array(MYSQLI_ASSOC);
        $dislike = $row5['rate'];
        if($dislike == 1)
        {
            queryMysql("UPDATE liveLikeComment SET rate='-1' where user='$together' AND comment_id='$c'");
        }
        elseif($dislike == 0)
        {
            queryMysql("UPDATE liveLikeComment SET rate='-1' where user='$together' AND comment_id='$c'");
        }
        elseif($dislike ==-1)
        {
            queryMysql("UPDATE liveLikeComment SET rate='0' where user='$together' AND comment_id='$c'");
        }
    }
    countLiveCommentLike($c);
}


if(isset($_POST['message']))
{   
    $otherComments = sanitizeString($_POST['message']);
    $otherComments = preg_replace('/\s\s+/', ' ', $otherComments);
    
    if($result->num_rows)
    {
        queryMysql("INSERT INTO liveCommentThread VALUES('0', '$title', '$otherComments','0','$user')");
    }
    
    addPointsToUser($user);
    queryMysql("INSERT INTO activity VALUES ('0', '$user', 'Commented on Live Thread', '$title', '$otherComments')");
}

if(isset($_POST['gethighrate']))
{   
    $theTitle = $_POST['title'];
    $forComments = 1;
    
}

if(isset($_POST['lowrate']))
{   
    $theTitle = $_POST['title'];
    $forComments = 2;
    
}

echo <<<_END
<!DOCTYPE html>
<html>
    <head>
        <title>Viewing Title: $title </title>
        <link href='viewLiveThread.css' rel='stylesheet'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
        <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='jquery-ui.min.css'>
        <script type='text/javascript' src='jquery.js'></script>
        <script type='text/javascript' src='jquery-ui.min.js'></script>
        <script type='text/javascript' src='addAndDisableButtons.js'></script>
        <script src='viewLike.js'></script>
        <script src='menu.js'></script>
        <script src='displayGroups.js'></script>
    </head>
    <body class='overbody'>
            <div class='topbar'>
            <div class='container'>
_END;
    showPictureHome($user);
    echo <<<_E
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
        <div class='firstDetails'>
    Title:  $title <br><br>
    Comment: <br><br><textarea type="text" readonly="readonly" cols='79' rows='3'>$comment</textarea> <br><br>
    Time Created: $timecreated
        </div>
    <div id='groups'>
_E;
    echo <<<_E
   </div>
    <br>   
    
   <br>
    <div class='boxForSort'>
        <div id='sort'>
            <h3 id='sortTitle'>Sort Comments</h3>
                <div>
                        <ul class='menu'>
                            <form method='post' action='viewLiveThread.php?id=$convo_id'>
                            <input type="hidden" maxlength='20' name='gethighrate' value='$title'>
                            <input type="submit" name='highrate' value='High Rate'>
                            </form>
                            <form method='post' action='viewLiveThread.php?id=$convo_id'>
                            <input type="hidden" maxlength='20' name='lowrate' value='$title'>
                            <input type="submit" name='lowrate' value='Low Rate'>
                            </form>
                        </ul>
                </div>
        </div>
    </div>
        <div class="homeThread">
_E;
    if($forComments == 0)
    {
         showAllCommentsLive($title, $convo_id);
    }
    elseif($forComments == 1)
    {
        showHigherRatedCommentsLive($title, $convo_id);
    }
    elseif($forComments == 2)
    {
        showLeastRatedCommentsLive($title, $convo_id);
    }
    
    echo <<<_E
        </div>
        <br><br><br>
            <div class="firstDetails">
                Add in your comment here!
                <form id='forForm' method='post' action='viewLiveThread.php?id=$convo_id'>
                <textarea type="text" name="message" cols='79' rows='3'></textarea>
                <button id="sub">Add!</button><br/>  
                </form>
            </div>
        
        </div>
    </body>  
    
</html>       
_E;



?>





