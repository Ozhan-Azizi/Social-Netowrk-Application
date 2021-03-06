<?php
require_once 'all.php';
$user = $_SESSION['user'];
$result = queryMysql("SELECT * FROM individual_group WHERE title='$group_title' AND user='$user'");
if(isset($_GET['view']))
{
    $group_title = $_GET['view'];   
    $result1 = queryMysql("SELECT * FROM individual_group WHERE title='$group_title' AND user='$user'");
    $_SESSION['groupTitle'] = $group_title;
    if($result1->num_rows)
    {
        $inGroup = True;
    }

}

if(isset ($_GET['join']))
{
    $resultA = queryMysql("SELECT * FROM individual_group WHERE title='$group_title' AND user='$user'");
    if($resultA->num_rows)
    {
        $message = "Already in group";
        $inGroup = True;
    }
    else 
    {
        $group_title = $_SESSION['groupTitle'];
        $resultB = queryMysql("SELECT * FROM individual_group WHERE title='$group_title' AND user='$user'");
        if($resultB->num_rows)
        {
        $message = "Already in group";
        $inGroup = True;
        }
        else
        {
            queryMysql("INSERT INTO individual_group VALUES('0', '$group_title', '$user')");
            $inGroup = True;
        }
    }
    addPointsToUser($user);
}
elseif(isset($_GET['leave']))
{
    $group_title = $_SESSION['groupTitle'];
    $resultB = queryMysql("SELECT * FROM individual_group WHERE title='$group_title' AND user='$user'");
    
    if($resultB->num_rows)
    {
        queryMysql("DELETE FROM individual_group WHERE title='$group_title' AND user='$user'");
        $inGroup = False;
    }
    decreasePointsToUser($user);
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
                    <li><a href='allChatRooms.php'>Chat</a></li>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle'>Me<b class='caret'></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href='profile.php'>Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='viewwatching.php'>Watching</a></li>
                            <li><a href='viewwatchers.php'>My Watchers</a></li>
                            <li><a href='messages.php'>Messages</a></li>            
                            <li><a href='logout.php'>Log out</a></li>
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
        <title>Group Page</title>
        <link type='text/css' rel='stylesheet' href='group.css'/>  
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
                <div class='main'>
<?php
    displayGroupMembers($group_title);

?>
                </div>
            <div class="info">
<?php
        if($inGroup == True)
        {
    echo <<<_A
            <h4 class='insideInfo'>Group: $group_title<br><br>In Group <a href='group.php?leave=$user'>[ Leave ]</a><br>
_A;
        }
        else
        {
    echo <<<_B
            <h4 class='insideInfo'>Group: $group_title<br><br>Not In Group <a href='group.php?join=$user'>[ Join ]</a><br>
_B;
        }
        
        $count = countMembersOfGroup($group_title);
        echo "<br>There are $count Members in this group<br>";
        
        if($inGroup == True)
        {
            echo "<br><a href='allChatRooms.php?group=$group_title'>View Private Chat rooms from this group</a></h4><br>";
            
        }
?>
                
                
            </div>
            
            <div class="displayThreads">
<?php
        displaySharedThreads($group_title);
?>
        </div>
            
            </div>
    </body>
</html>


