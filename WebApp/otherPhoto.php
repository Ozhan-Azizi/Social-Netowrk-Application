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
    die("Profile was not found");
}

  $result1 = queryMysql("SELECT * FROM pic WHERE user='$view'");
    if(isset($_POST['texta']))
    {
        $text1 = sanitizeString($_POST['texta']);
        if($text1 =='a')
        {
          $error = "All fields are not entered<br><br>";      
        }
        else 
        {
            if($result1->num_rows)
            {
                $pic_id = $_POST['p_id'];
                
                queryMysql("UPDATE pic SET text='$text1' where pic_id='$pic_id'");
            }
        }
    }


echo <<<_END
<!DOCTYPE html>
<html>
    <head>
        <title>$view Photos</title>
_END;
?>
        <link type='text/css' rel='stylesheet' href='all.css'/>  
        <link rel='stylesheet' href='jquery-ui.min.css'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
        <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script type='text/javascript' src='jquery.js'></script>
        <script src="photo.js"></script>
        <script src='menu.js'></script>
        
    </head>
    <body>
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
        <br>
        
<?php      
        echo "<div class='photos'><br><h3>$view Pictures</h3>";
        echo "<button id='load'>Load more!</button></div><br>";
showPictures($view);
?>
  
          
            </div>
          </body>
      </html>
            
        

