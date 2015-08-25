<?php
require_once 'all.php';

$name = $_REQUEST['name'];
$details = nl2br($_REQUEST['details']);
$num = $_SESSION['user_id'];

$result = queryMysql("SELECT * FROM profile where user='$name'");

if($result->num_rows)
{
    queryMysql("UPDATE profile SET details='$details' where user='$name'");
}
else
{
    queryMysql("INSERT INTO profile VALUES('$user','$details','$num' '', '', '')");
}

$result2 = queryMysql("SELECT * FROM profile WHERE user='$name'");
if($result2->num_rows)
{
    while($row = $result2->fetch_array(MYSQLI_ASSOC))
    {
        echo $row['details'];
    }
}
else
{

}



?>