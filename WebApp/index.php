<?php
require_once 'all.php';
removeOldLiveThreads();
    $whatToDisplay = 0;
    if(isset($_GET['title']))
    {
        $title = $_GET['title'];
        $title = sanitizeString($title);
        if($title == '')
        {
            $error = "Nothing was entered";
        }
        else 
        {
            $result = queryMysql("SELECT * FROM Thread WHERE title LIKE '%$title%' OR user LIKE '%$title%'"); 
            
            if($result->num_rows == 0)
            {
                $error = "None found";
                
            }
            else
            {
                $whatToDisplay = 7;
                $useTitle = $title;
            }
        }
    }

    if(isset($_GET['checkPost']))
    {
        $get = $_GET['checkPost'];
        $whatToDisplay = 1;
                
    }
    
    if(isset($_GET['checkBox']))
    {
        $get = $_GET['checkBox'];
        if($get == "Views")
        {
            $whatToDisplay = 2;
        }
        elseif($get == "Rate")
        {
            $whatToDisplay = 3;
        }
                
    }
    
    if(isset($_GET['checkPost'])&&isset($_GET['checkBox']))
    {
        $category = $_GET['checkPost'];
        $get = $_GET['checkBox'];
        if($get == "Views")
        {
            $whatToDisplay = 5;
        }
        elseif($get == "Rate")
        {
            $whatToDisplay = 6;
        }
    }
    
    if(isset($_GET['live']))
    {
        $whatToDisplay = 4;
    }
       
if($loggedin)
{
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home Page</title>
        <link type='text/css' rel='stylesheet' href='Home.css'/>  
        <link rel='stylesheet' href='jquery-ui.min.css'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
        <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
        <link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.csszengarden.com/zengarden.xml">
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
                
        <br>
<?php
    echo <<<_E
            <div class='boxSearch'>
                <form id='newSearch' method="get" action="index.php">
                    <input type="text" id="sbox" name='title' size='21' maxlength='30' value='$title'>$error
                        <input type='submit' value="GO" class="sbutton">
                </form>
                <div class='clearIt'></div>
            </div>
            <br>
            <button id='loadfilter'>Filter</button>   
            <div class='homeFilter'>
                <div class='firstFilter'>
                    <h4>Sort by</h4>
                <form method='get' action='index.php?'>
                    <input id="checkbox" name="checkBox" type="checkbox" value="Views" />Views<br>
                    <input id="checkbox" name="checkBox" type="checkbox" value="Rate" />Rate<br>
                    <input id="checkbox" name="checkBox" type="checkbox" value="Relevance" />Relevance<br>
                </div>
                    <input type='submit' value='Sort by'>
                
                <div class='secondFilter'>
                    <h4>Category</h4>
                    <input id="checkbox" name="checkPost" type="checkbox" value="Random"/>Random<br>
                    <input id="checkbox" name="checkPost" type="checkbox" value="Funny" />Funny<br>
                    <input id="checkbox" name="checkPost" type="checkbox" value="Controversial" />Controversial<br>
                    <input id="checkbox" name="checkPost" type="checkbox" value="TV_Shows" />TV Shows<br>
                    <input id="checkbox" name="checkPost" type="checkbox" value="Movies" />Movies<br>
                    <input id="checkbox" name="checkPost" type="checkbox" value="Games" />Games<br>
                    <input id="checkbox" name="checkPost" type="checkbox" value="Web_Video" />Web Video<br>
                </div>
                    <input type='submit' value='Change Category'>
                </form>   
                <div class='thirdFilter'>
                <form method='get' action='index.php?'>
                    <input type='hidden' name='live' value='live'>
                    <input type='submit' value='LIVE'>
                </form>
                </div>
            </div>
            
            <div class='homeThread'>
_E;
    if($whatToDisplay ==0)
    {
        showAllThreadsUse();
    }
    elseif($whatToDisplay ==1)
    {
        displayCategories($get);
    }
    elseif($whatToDisplay ==2)
    {
        displayByViews();
    }
    elseif($whatToDisplay == 3)
    {
        displayByRatings();
    }
    elseif($whatToDisplay ==4)
    {
        removeOldLiveThreads();
        displayAllLiveThreads();
    }
    elseif($whatToDisplay ==5)
    {
        displayViewsAndCategory($category);
    }
    elseif($whatToDisplay ==6)
    {
        displayRateAndCategory($category);
    }
    elseif($whatToDisplay == 7)
    {
        displaySearch($useTitle);
    }
        
    echo <<<_F
            
            
        </div>
    
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script type='text/javascript' src='jquery.js'></script>
        <script src='menu.js'></script>
        <script src='filter.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src='insideFilter.js'></script>
    </body>
    
</html>
_F;
}  
 else {
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home Page</title>
        <link type='text/css' rel='stylesheet' href='Home.css'/>  
        <link rel='stylesheet' href='jquery-ui.min.css'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet'>
        <link href='http://s3.amazonaws.com/codecademy-content/courses/ltp2/css/bootstrap.min.css' rel='stylesheet'>
        <link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.csszengarden.com/zengarden.xml">
    </head>
    <body class='overbody'>
        <div class='topbar'>
            <div class='container'>
                <ul class='menu'>
                    <li><a href='index.php'>Home</a></li>
                    <li><a href='signup.php'> Sign up</a></li>
                    <li><a href='login.php'>Log in</a></li>
                    <li><a href='thread.php'>Create Thread</a></li>
                    <li class="dropdown">
                        <a href='#' class='dropdown-toggle'>Me<b class="caret"></b></a>
                        <ul class='dropdown-menu'>
                            <li><a href='profile.php'>Profile</a></li>
                            <li><a href='photos.php'>My Photos</a></li>
                            <li><a href='viewWatching.php'>Watching</a></li>
                            <li><a href='viewWatchers.php'>My Watchers</a></li>
                            <li><a href='groups.php'>My Groups</a></li>
                            <li><a href='messages.php'>Messages</a></li>
                            <li><a href='logout.php'>Log out</a></li>
                        </ul>
                    </li>
                </ul>
                
            </div>
        </div>
        <div class='inputEverything'>
<?php
    echo <<<_E
             <div class='boxSearch'>
                <form id='newSearch' method="get" action="index.php">
                    <input type="text" id="sbox" name='title' size='21' maxlength='30' value='$title'>
                        <input type='submit' value="GO" class="sbutton">
                </form>
                <div class='clearIt'></div>
            </div>
            <br>
            <button id='loadfilter'>Filter</button>   
            <div class='homeFilter'>
                <div class='firstFilter'>
                            <h4>Sort by</h4>
                        <form method='get' action='index.php?'>
                            <input id="checkbox" name="checkBox" type="checkbox" value="Views" />Views<br>
                            <input id="checkbox" name="checkBox" type="checkbox" value="Rate" />Rate<br>
                            <input id="checkbox" name="checkBox" type="checkbox" value="Relevance" />Relevance<br>
                        </div>
                            <input type='submit' value='Sort by'>
                     
                        <div class='secondFilter'>
                            <h4>Category</h4>
                        
                            <input id="checkbox" name="checkPost" type="checkbox" value="Random"/>Random<br>
                            <input id="checkbox" name="checkPost" type="checkbox" value="Funny" />Funny<br>
                            <input id="checkbox" name="checkPost" type="checkbox" value="Controversial" />Controversial<br>
                            <input id="checkbox" name="checkPost" type="checkbox" value="TV_Shows" />TV Shows<br>
                            <input id="checkbox" name="checkPost" type="checkbox" value="Movies" />Movies<br>
                            <input id="checkbox" name="checkPost" type="checkbox" value="Games" />Games<br>
                            <input id="checkbox" name="checkPost" type="checkbox" value="Web_Video" />Web Video<br>
                        </div>
                            <input type='submit' value='Change Category'>
                        </form>
                            <div class='thirdFilter'>
                            <form method='get' action='index.php?'>
                                <input type='hidden' name='live' value='live'>
                                <input type='submit' value='LIVE'>
                            </form>
                            </div>
            </div>   
            
            <div class='homeThread'>
_E;
    if($whatToDisplay ==0)
    {
        showAllThreadsUse();
    }
    elseif($whatToDisplay ==1)
    {
        displayCategories($get);
    }
    elseif($whatToDisplay ==2)
    {
        displayByViews();
    }
    elseif($whatToDisplay == 3)
    {
        displayByRatings();
    }
    elseif($whatToDisplay ==4)
    {
        removeOldLiveThreads();
        displayAllLiveThreads();
    }
    elseif($whatToDisplay ==5)
    {
        displayViewsAndCategory($category);
    }
    elseif($whatToDisplay ==6)
    {
        displayRateAndCategory($category);
    }
    elseif($whatToDisplay == 7)
    {
        displaySearch($useTitle);
    }
        
    echo <<<_F
            
            
        </div>
    
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script type='text/javascript' src='jquery.js'></script>
        <script src='menu.js'></script>
        <script src='filter.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src='insideFilter.js'></script>
    </body>
    
</html>
_F;
 }
?>
