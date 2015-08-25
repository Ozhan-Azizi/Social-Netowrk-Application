<?php

require_once 'all.php';
if(isset($_POST['lowrate']))
{
    $theTitle = $_POST['title'];
    $forComments = 2;
}

require_once 'viewThread.php';

?>

