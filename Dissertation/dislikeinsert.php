<?php
require_once 'all.php';
    $user = $_SESSION['user'];
    $c = $_REQUEST['cid'];
    $together = "$user$c";
    
    
    $result5 = queryMysql("SELECT * FROM likeCommentThread where user='$together'");
    if ($result5->num_rows == 0)
    {
        queryMysql("INSERT INTO likeCommentThread VALUES('0', '$c', '$together', '-1')");
    }
    else 
    {
        $row5 = $result5->fetch_array(MYSQLI_ASSOC);
        $dislike = $row5['rate'];
        if($dislike == 1)
        {
            queryMysql("UPDATE likeCommentThread SET rate='-1' where user='$together' AND commentThread_id='$c'");
        }
        elseif($dislike == 0)
        {
            queryMysql("UPDATE likeCommentThread SET rate='-1' where user='$together' AND commentThread_id='$c'");
        }
        elseif($dislike ==-1)
        {
            queryMysql("UPDATE likeCommentThread SET rate='0' where user='$together' AND commentThread_id='$c'");
        }
    }
    countCommentLike($c);
    queryMysql("UPDATE Thread SET views=views-1 where thread_id='$convo_id'");
    
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