<?php
require_once 'all.php';
$title = $_SESSION['threadTitle'];

        $result = queryMysql("SELECT id, comment,rate,user FROM commentThread WHERE title='$title'");
                                // Retrieving all comments from the thread. Using title as a filter
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No Comments</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) // used to display all comments
            {  
                $comment = $row['comment'];
                $rate = $row['rate'];
                $user = $row['user'];
                $cid = $row['id'];
                /// First displaying the comment
                // Second a like button
                // Third a like button
                // the buttons are wrapped in the a form.  
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
                        <a href="#" onclick="likeDislikeTheComment('$cid', '1')">Like</a>
                    </div>
                        
                    <div class='individualUser'>
                        <a href="#" onclick="likeDislikeTheComment('$cid', '0')">Dislike</a>   
                    </div>
                </div>
_A;
            }
            
        }
?>