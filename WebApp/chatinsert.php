<?php
require_once 'all.php';
$uname = $_REQUEST['uname'];
$msg = $_REQUEST['msg'];
$title = $_REQUEST['title'];

$msg = sanitizeString($msg);

queryMysql("INSERT INTO chat VALUES('0','$uname','$msg', '$title')");

$result = queryMysql("SELECT * FROM chat where title='$title' ORDER by id DESC");
if($result->num_rows)
{
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        echo "<span class='chats'><a href='otherProfile.php?view=".$row['user'].'>'.$row['user'].'</a>' . ": " . $row['message'] . "<br></span>";
    }  
}
else
{
    
}



?>
