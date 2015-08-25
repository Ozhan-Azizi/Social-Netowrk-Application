<?php

require_once 'all.php';
$result = queryMysql("SELECT * FROM profile WHERE user='$user'");
$thisRow = $result->fetch_array(MYSQLI_ASSOC);
$details = $thisRow['details'];



$result1 = queryMysql("SELECT * FROM members WHERE user='$user'");
if($result1->num_rows)
{
    $row = $result1->fetch_array(MYSQLI_ASSOC);
    $score = $row['score'];
	$num = $row['user_id'];
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

$user = $_SESSION['user'];
$queryResult = queryMysql("SELECT * FROM profile where user='$user'");
    if(isset($_POST['text']))
    {
       // $details = sanitizeString($_POST['text']);
        //$det = $details;
	//$details = preg_replace('/\s\s+/', ' ', $_POST['text']);
	$details = sanitizeString($_POST['text']);

	$result2 = queryMysql("SELECT * FROM profile WHERE user='$user'");

        if($result2->num_rows)
        {
            queryMysql("UPDATE profile SET details='$details' where user='$user'");
        }
        else
        {
            queryMysql("INSERT INTO profile VALUES('$user','$details','$num', '0', '0', '0')");
        }
    }


 //    $detailsPrint = stripslashes(preg_replace('/\s\s+/', ' ', $details));

  if (isset($_FILES['image']['name']))
  {
    $saveto = "$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;

    switch($_FILES['image']['type'])
    {
      case "image/gif":   $src = imagecreatefromgif($saveto); break;
      case "image/jpeg":  // Both regular and progressive jpegs
      case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
      case "image/png":   $src = imagecreatefrompng($saveto); break;
      default:            $typeok = FALSE; break;
    }

    if ($typeok)
    {
      list($w, $h) = getimagesize($saveto);

      $max = 500;
      $tw  = $w;
      $th  = $h;

      if ($w > $h && $max < $w)
      {
        $th = $max / $w * $h;
        $tw = $max;
      }
      elseif ($h > $w && $max < $h)
      {
        $tw = $max / $h * $w;
        $th = $max;
      }
      elseif ($max < $w)
      {
        $tw = $th = $max;
      }

      $tmp = imagecreatetruecolor($tw, $th);
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
      imageconvolution($tmp, array(array(-1, -1, -1),
        array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
      imagejpeg($tmp, $saveto);
      imagedestroy($tmp);
      imagedestroy($src); 
        }
        
    }
    
    if(isset($_POST['checkPost']))
    {
        $use = $_POST['checkPost'];
        $selection1 = $use[0];
        $selection2 = $use[1];
        $selection3 = $use[2];
        $check = 0;
        foreach ($_POST['checkPost'] as $get)
        {
            $queryResult1 = queryMysql("SELECT * FROM profile where user='$user'");
            if($queryResult1->num_rows)
            {
                $row = $queryResult1->fetch_array(MYSQLI_ASSOC);
                $firstID = $row['thread_id'];
                $secondID = $row['thread_id2'];
                $thirdID = $row['thread_id3'];
                
                if($firstID == 0 || (($firstID != $get) && ($check ==0)) )
                {
                    queryMysql("UPDATE profile SET thread_id='$get' where user='$user'");
                }
                elseif($secondID == 0 || (($secondID != $get) && ($check ==1)) )
                {
                    queryMysql("UPDATE profile SET thread_id2='$get' where user='$user'");
                }
                elseif($thirdID == 0 || (($thirdID != $get) && ($check ==2)) )
                {
                    queryMysql("UPDATE profile SET thread_id3='$get' where user='$user'");
                }
                $check++;
            //    queryMysql("UPDATE profile SET thread_id='$get' where user='$user'");
            }
            else
            {
                queryMysql("INSERT INTO profile VALUES('$user','','$num' '$get', '', '')");
            }
        }
    }
    
    if(isset($_POST['gtitle']))
    {
        $gtitle = sanitizeString($_POST['gtitle']);
        $gtitle1 = $_POST['gtitle'];
	
        if($gtitle == '')
        {
            $errorA = "Field is not entered";
        }
        else
        {
            $resultA = queryMysql("SELECT * FROM groups WHERE title='$gtitle'");
            if($resultA->num_rows)
            {
                $errorA = "That Group already Exist. Please choose another one";
            }
            else
            {
                queryMysql("INSERT INTO groups VALUES('0', '$gtitle', '$user')");
                queryMysql("INSERT INTO individual_group VALUES('0', '$gtitle', '$user')");
                $errorA = "Group Created.";
            }
        
        }
    }


echo <<<_END
<!DOCTYPE html>
<head>
    <title>Profile</title>
    <link href="profile.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
    <link href="http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type='text/javascript' src='jquery.js'></script>
    <script src='menu.js'></script>
    <script src='profile.js'></script>
  
</head>
<body class='overbody'>
    
        <div class='topbar'>
            <div class='container'>
                <ul class='menu'>
                    <li>Welcome: $user</li>
                    <li><a href='index.php'>Home</a></li>
                    <li><a href='thread.php'>Create Thread</a></li>
                    <li><a href='createLiveThread.php'>Create Live Thread</a></li>
                    <li><a href='groups.php'>Groups</a></li>
                    <li><a href='allChatRooms.php'>Chat</a></li>
                    <li><a href='findMembers.php'>Find Members</a></li>
                    <li><a href='activity.php'>Activity</a></li>
                    <li class="dropdown">
                        <a href='#' class='dropdown-toggle'>Me<b class='caret'></b></a>
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
            <div class='slider'><br><br>
                <div class='main'>
_END;
showPicture($user);
//$det = $_SESSION['details'];
    echo <<<_BA
                    
                    <div id='main2'>
                        <form method='post' action='profile.php' enctype='multipart/form-data'>
                        Upload New Profile Picture: <input type='file' name='image' size='14'>
                        <input type='submit' value='Save Picture'>
                        </form>
                    </div>
                    <span id='score'>YOUR SCORE : $score</span>
                </div>
                
                <div class='details'>
                <textarea rows='5' cols='50' readonly="readonly">$details</textarea>
                <input type="hidden" name='name' value="$user">
                
                <form method='post' action='profile.php'>$error
                <span class='putdetails'>Details</span>
                <br>
                <textarea name='text' rows='5' cols='50' value='$details'> </textarea>
                <input type='submit' value='Update'>
                </form>
                
                </div>
        <div class='coverBoxes'>
            <h6 class='watch'>WATCHING</h6>
            <div class='watchingBox'>
_BA;
            viewWatching($user);
    echo <<<_BA
            <br>
            <a id='viewMembers' href="viewWatching.php?view=$user">View All Watching Members</a>
            </div>            
                
            <h6 class='watch'><br>WATCHERS</h6>
            <div class='watchersBox'>
_BA;
            viewWatchers($user);
    echo <<<_BA
            <br>
            <a id='viewWatchers' href="viewWatchers.php?view=$user">View All Watchers</a>
            </div>
            
            <h6 class='watch'>MY GROUPS</h6>
            <div class='groupsBox'>
_BA;
                viewGroups($user);
    echo <<<_BA
                <br><a href='groups.php?view=$user'>View all my groups</a>
            </div>            
        </div>
            <form method='post' action='photos.php?view=$user'>
                <input type='submit' class='toPics' value='My Photos'>
            </form>
            <div class='createGroup'>
                <b style='float:left'>Create Group</b><br>
                <form method='post' action='profile.php?view=$user'>
                    Title: <br><textarea type='text' rows='4' cols='60' name='gtitle' value='$gtitle'>$errorA</textarea>
                    <input type='submit' value='Create Group'>
                </form>
            </div>
            <div class='homeThread'>
_BA;
        displayProfileThreads($user);
    echo <<<_BA
            </div>
        
    </div>

            
        
</body>
</html>

_BA;
?>

