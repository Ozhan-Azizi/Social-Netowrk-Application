<?php
ob_start(); // turn on buffering
session_start(); // starting session
require_once 'functions.php';
$whosLoggedIn = "(Guest)";

if(isset($_SESSION['user']))
{
    $user = $_SESSION['user'];
    $loggedin = TRUE;
    $whosLoggedIn = "($user)";
}
else
{
    $loggedin = FALSE;
}
$forComments = 0;

?>
