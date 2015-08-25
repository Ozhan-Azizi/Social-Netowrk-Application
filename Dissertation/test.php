<?php

session_start();

echo "<!DOCTYPE html>";
echo "\n";

//require_once 'functions.php'; /// set up mysql 

$whosLoggedIn = "(Guest)";

if(isset($_SESSION['user']))
{
    $user = $_SESSION['user'];
    $loggedin = TRUE;
    $whosLoggedIn = "($user)";
}

echo <<<_END
<html>
	<head>
		<link rel="stylesheet" href="jquery-ui.min.css">
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="jquery-ui.min.js"></script>		
		
		<link type="text/css" rel="stylesheet" href="stylesheet.css" />
		<script type="text/javascript" src="script.js"></script>
		
		</head> 
	<body id="background">
	
	    <div id="header">
			<p id="name">Home Page</p>
			
			<div class="prf">Profile
                            <a href="signup.php">Sign up</a></div>
     <!-- GIVE SIGN UP OWN SECTION HERE  <div><a href="signup.php">Sign up</a> </div> --> 
		</div>

		<div class="undHeader">
				
		</div>
		
		<div class="left">
			<div id ="menu">
            	<h3>Live</h3>
            	<div class="overMenuBefore">
                	<li>Movies</li>
                	<li>TV shows</li>
                	<li>Anime</li>
            	</div>
            	<h3>Relevance</h3>
            	<div class="overMenuBefore">
                	<li>Most Viewed</li>
                	<li>Top Rated</li>
            	</div>
            	<h3>Time</h3>
            	<div class="overMenuBefore">
                	<li>Today</li>
                	<li>This Week</li>
                	<li>This Month</li>
                	<li>This year</li>
            	</div>
						
			</div>
		
		</div>
		
		<div class="right">
				
										
		</div>
		
		<div id="footer">
			<p>The footer is here</p>
		</div>
	
	
		
	</body> 
</html>
_END;
?>