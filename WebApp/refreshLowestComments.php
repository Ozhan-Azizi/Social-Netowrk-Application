<?php
require_once 'all.php';

$title = $_SESSION['threadTitle'];
        // Used to display in a order of the least rated comments. (lowest-highest). 
        $result = queryMysql("SELECT id, comment,rate,user FROM commentThread WHERE title='$title' ORDER BY (rate+0)");
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No Comments</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {  
                $comment = $row['comment'];
                $rate = $row['rate'];
                $user = $row['user'];
                $cid = $row['id'];
                echo <<<_A
                <br>                
                <div class='insideThreads'>
                    <div class='individualUser'>
                    <textarea type="text" id="forText" readonly="readonly" cols='79' rows='3'>$comment</textarea> 
                    </div>  
                    <div class='individualUser'>  
                        <span id='$cid'>     Rate: $rate </span>
                        <br>   <span id='liveDetails'>  Posted by $user </span>
                    </div>
                    <div class='individualUser'>
                        <a href="#" onclick="likeDislikeTheComment('$cid', '1', 'lowest')">Like</a>
                    </div>
                        
                    <div class='individualUser'>
                        <a href="#" onclick="likeDislikeTheComment('$cid', '0', 'lowest')">Dislike</a>   
                    </div>
                </div>
_A;
            }
        }