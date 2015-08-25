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



//$convo_id = $_SESSION['thread_id'];
if(isset($_GET['view']))
{

    $convo_id = $_GET['view'];

    queryMysql("UPDATE Thread SET views=views+1 where thread_id='$convo_id'");
    
}

$user = $_SESSION['user'];
$result = queryMysql("SELECT * FROM Thread where thread_id='$convo_id'");
$row = $result->fetch_array(MYSQLI_ASSOC);
$title = $row['title'];
$comment = nl2br($row['comment']);
$views = $row['views'];
$rate = $row['rate'];
$user3 = $row['user'];
$thread_id = $row['thread_id'];
$category = $row['category'];

$_SESSION['threadTitle'] = $title;

if(isset($_POST['increase'])) // liking thread page
{   
    $result2 = queryMysql("SELECT * FROM likeThread where user='$user' AND Title='$title'");
    if ($result2->num_rows == 0)
    {
        queryMysql("INSERT INTO likeThread VALUES('$title', '$user', '0', '1')");
        queryMysql("INSERT INTO activity VALUES ('0', '$user', 'Liked Thread Page', '$title', '')");

    }
    else 
    {
        $row2 = $result2->fetch_array(MYSQLI_ASSOC);
        $like = $row2['rate'];
        if($like == 1)
        {
            queryMysql("UPDATE likeThread SET rate='0' where user='$user' AND Title='$title'");
        }
        elseif($like == 0)
        {
            queryMysql("UPDATE likeThread SET rate='1' where user='$user' AND Title='$title'");
        }
        elseif($like ==-1)
        {
            queryMysql("UPDATE likeThread SET rate='1' where user='$user' AND Title='$title'");
        }
    }
    countLikeThreads($title, $convo_id);
    
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
    
}

if(isset($_POST['decrease']))
{
    $result3 = queryMysql("SELECT * FROM likeThread where user='$user' AND Title='$title'");
    if ($result3->num_rows == 0)
    {
        queryMysql("INSERT INTO likeThread VALUES('$title', '$user', '0', '-1')");

    }
    else 
    {
        $row3 = $result3->fetch_array(MYSQLI_ASSOC);
        $disLike = $row3['rate'];
        if($disLike == 1)
        {
            queryMysql("UPDATE likeThread SET rate='-1' where user='$user' AND Title='$title'");
        }
        elseif($disLike == 0)
        {
            queryMysql("UPDATE likeThread SET rate='-1' where user='$user' AND Title='$title'");
        }
        elseif($disLike ==-1)
        {
            queryMysql("UPDATE likeThread SET rate='0' where user='$user' AND Title='$title'");
        }
    }
    countLikeThreads($title, $convo_id);
    
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
}

$resultB = queryMysql("SELECT * FROM Thread where thread_id='$convo_id'");
$rowB = $resultB->fetch_array(MYSQLI_ASSOC);
$rate = $rowB['rate'];

if(isset($_POST['increaseEach']))
{   
    $user = $_SESSION['user'];
    $c = $_POST['increaseEach'];
    $together = "$user$c";
    $result4 = queryMysql("SELECT * FROM likeCommentThread where user='$together'");
    if ($result4->num_rows == 0)
    {
        queryMysql("INSERT INTO likeCommentThread VALUES('0', '$c', '$together', '1')");
        queryMysql("INSERT INTO activity VALUES ('0', '$user', 'Liked a comment on Thread Page', '$title', '$c')");
    }
    else 
    {
        $row4 = $result4->fetch_array(MYSQLI_ASSOC);
        $like = $row4['rate'];
        if($like == 1)
        {
            queryMysql("UPDATE likeCommentThread SET rate='0' where user='$together' AND commentThread_id='$c'");
        }
        elseif($like == 0)
        {
            queryMysql("UPDATE likeCommentThread SET rate='1' where user='$together' AND commentThread_id='$c'");
        }
        elseif($like ==-1)
        {
            queryMysql("UPDATE likeCommentThread SET rate='1' where user='$together' AND commentThread_id='$c'");
        }
    }
    countCommentLike($c);
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
    
}

if(isset($_POST['decreaseEach']))
{   
    $user = $_SESSION['user'];
    $c = $_POST['decreaseEach'];
    $together = "$user$c";
    $result5 = queryMysql("SELECT * FROM likeCommentThread where user='$together'");
    if ($result5->num_rows == 0)
    {
        queryMysql("INSERT INTO likeCommentThread VALUES('0', '$c', '$together', '-1')");
    }
    else 
    {
        $row5 = $result5->fetch_array(MYSQLI_ASSOC);
        $dislike = $row5['rate'];
        if($dislike == 1)
        {
            queryMysql("UPDATE likeCommentThread SET rate='-1' where user='$together' AND commentThread_id='$c'");
        }
        elseif($dislike == 0)
        {
            queryMysql("UPDATE likeCommentThread SET rate='-1' where user='$together' AND commentThread_id='$c'");
        }
        elseif($dislike ==-1)
        {
            queryMysql("UPDATE likeCommentThread SET rate='0' where user='$together' AND commentThread_id='$c'");
        }
    }
    countCommentLike($c);
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
}


if(isset($_POST['message']))
{   
    $otherComments = sanitizeString($_POST['message']);
    $otherComments = preg_replace('/\s\s+/', ' ', $otherComments);
    
    if($result->num_rows)
    {
        queryMysql("INSERT INTO commentThread VALUES('0', '$title', '$otherComments','0','$user')");
    }
    
    addPointsToUser($user);
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
    queryMysql("INSERT INTO activity VALUES ('0', '$user', 'Commented on Thread Page', '$title', '$otherComments')");
}

if(isset($_POST['gethighrate']))
{   
    $theTitle = $_POST['title'];
    $forComments = 1;
    
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
}

if(isset($_POST['lowrate']))
{   
    $theTitle = $_POST['title'];
    $forComments = 2;
    
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
}

if(isset($_POST['checkBox']))
{
    $gtitle = $_POST['checkBox'];
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
    
    $result = queryMysql("SELECT * FROM groupThreads WHERE thread_title='$title' AND group_title='$gtitle'");
    if($result->num_rows)
    {
        $reply = "Therad already exist on group page";
    }
    else
    {
        queryMysql("INSERT INTO groupThreads VALUES('0', '$gtitle', '$title', '$category', '$thread_id', '$views', '$rate', '$comment')");
        $reply = "Uploaded to group page";
    }
    
    addPointsToUser($user);
}


echo <<<_END
<!DOCTYPE html>
<html>
    <head>
        <title>Viewing Title: $title </title>
        <link href='viewThread.css' rel='stylesheet'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
        <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='jquery-ui.min.css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type='text/javascript' src='jquery.js'></script>
        <script type='text/javascript' src='jquery-ui.min.js'></script>
        <script type='text/javascript' src='addAndDisableButtons.js'></script>
        <script src='viewLike.js'></script>
        <script src='menu.js'></script>
        <script src='displayGroups.js'></script>
        <script src='likeComment.js'></script>
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
            $reply <br>
            Title:  $title <br><br>
            Comment:  $comment <br><br>
            Views: $views <br><br>
            Rate: $rate <br><br>
                    
            <form method='post' action='viewThread.php?view=$convo_id'>
            <input type='hidden' name='increase' value='1'>
            <input type='submit' id="hitlike" value='Like'>
            </form>
            <form method='post' action='viewThread.php?view=$convo_id'>
            <input type='hidden' name='decrease' value='-1'> 
            <input type='submit' id="hitDislike" value='Dislike'>
            </form>
        </div>
            <button id='shareToGroup'>Share To Group</button><br>
            <div id='groups'>
_E;
    displayGroupsWithUser($user, $convo_id);
    echo <<<_E
   </div>
    <br>   
   <br>
    <div class='boxForSort'>
        <div id='sort'>
            <h3 id='sortTitle'>Sort Comments</h3>
                <div>
                        <ul class='menu'>
                            <form method='post' action='viewThread.php?view=$convo_id'>
                            <input type="hidden" maxlength='20' name='gethighrate' value='$title'>
                            <input type="submit" name='highrate' value='High Rate'>
                            </form>
                            <form method='post' action='viewThread.php?view=$convo_id'>
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
         showAllComments($title, $convo_id);
    }
    elseif($forComments == 1)
    {
        showHigherRatedComments($title, $convo_id);
    }
    elseif($forComments == 2)
    {
        showLeastRatedComments($title, $convo_id);
    }
    
    echo <<<_E
        </div>
            <div class="firstDetails">
                Comment Here!
                <form name ='mycomment' id='forForm' method='post' action='viewThread.php?view=$convo_id'>
                <textarea type="text" name="message" cols='79' rows='5'></textarea><br>
                <input type='hidden' name='threadTitle' value='$title'>
                <button id="sub">Add!</button><br/>  
                </form>
            </div>
        
        </div>
            
_E;
    if($forComments == 0)
    {
        echo "<script src='torefreshComments.js'></script>";
    }
    elseif($forComments == 1)
    {
        echo "<script src='refreshHighestComments.js'></script>";
    }
    elseif($forComments == 2)
    {
        echo "<script src='refreshLowestComments.js'></script>";
    }
    echo <<<_E
            
    </body>  
    
</html>       
_E;


?>





