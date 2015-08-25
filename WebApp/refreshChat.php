<?php
require_once 'all.php';
$titleOfChat = $_SESSION['titleOfChatRoom'];
$result = queryMysql("SELECT * FROM chat where title='$titleOfChat' ORDER by id DESC");
if($result->num_rows)
{
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        echo "<span class='chats'><a href='otherProfile.php?view=".$row['user']."'>".$row['user'] ."</a> : " . $row['message'] . "<br></span>";
    }  
}
else
{
}
?>