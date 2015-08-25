<?php
require_once 'all.php';

$c = $_REQUEST['cid'];
$user = $_SESSION['user'];
$title = $_SESSION['threadTitle'];
$together = "$user$c";

$result4 = queryMysql("SELECT * FROM likeCommentThread where user='$together'");
    if ($result4->num_rows == 0)
    {
        queryMysql("INSERT INTO likeCommentThread VALUES('0', '$c', '$together', '1')");
        queryMysql("INSERT INTO activity VALUES ('0', '$user', 'Liked a comment on Thread Page', '$title', '$c')");
    }
    else 
    {
        $row4 = $result4->fetch_array(MYSQLI_ASSOC);
        $like = $row4['rate'];
        if($like == 1)
        {
            queryMysql("UPDATE likeCommentThread SET rate='0' where user='$together' AND commentThread_id='$c'");
        }
        elseif($like == 0)
        {
            queryMysql("UPDATE likeCommentThread SET rate='1' where user='$together' AND commentThread_id='$c'");
        }
        elseif($like ==-1)
        {
            queryMysql("UPDATE likeCommentThread SET rate='1' where user='$together' AND commentThread_id='$c'");
        }
    }
    countCommentLike($c);

$result = queryMysql("SELECT * FROM commentThread where id='$c'");
if($result->num_rows)
{
    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $rate1 = $row['rate'];
        echo "Rate: $rate1";
    }  
}
else
{
    
}



?>
