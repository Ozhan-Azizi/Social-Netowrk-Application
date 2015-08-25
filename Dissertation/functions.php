<?php
    // my details
    $dataBaseHost = 'localhost';
    $dataBaseName = 'test2';
    $dataBaseUser = 'root';
    $dataBasePass = 'root';
    $appName = 'Dissertation';
    
    
    // now attempt connection with the database
    $connection = new mysqli($dataBaseHost, $dataBaseUser, $dataBasePass, $dataBaseName);
    // checking connection         
    if($connection->connect_error)
    {
        die($connection->connect_error);
    }
    // this function is from the Web Programming Coursework 1, used to create the table if the name given does not exist
    function createTheTable($name, $query)
    {
        
        queryMysql("create table if not exists $name($query)"); // calls function queryMysql
    }
    // this function is from the Web Programming Coursework 1, used to queryMysql 
    function queryMysql($query)
    {
    //    echo "here 1<br>";    TESTING
        global $connection; // able to use this everywhere in the class. 
        $result = $connection->query($query);
      //  echo "here 2<br>";    TESTING
        if(!$result)
        {
            die($connection->error);
           // echo "here 3";    TESTING
        }
        return $result;
    }
    // this function is from the Web Programming Coursework 1, used to destroy the session
    function destroySession()
    {
        $_SESSION = array();
        
        if(session_id() != "" || isset($_COOKIE[session_name()]))
        {
            setcookie(session_name(), '', time()-10000000, '/');
        }
    }
    // this function is from the Web Programming Coursework 1
    function sanitizeString($word)
    {
        global $connection;
        $word = strip_tags($word);
        $word = htmlentities($word);
        $word = stripslashes($word);
        
        return $connection->real_escape_string($word);
    }
    // This functions shows the users picture. 
    function showPicture($user)
    {
        if (file_exists("$user.jpg")) // checks if the file exist
        {
            echo "<img class='images' src='$user.jpg' style='float:left;'>";
        }
        else // If the file does not exist, using a default image with a fixed size
        {
            echo "<img class='images' src='temporary1.jpg' style='float:left;' width= '50px' height='50px'>";
        }
    }
    
    //Initially, decided to create a group picture for every group
    function showGroupPicture($title)
    {
        if (file_exists("$title.jpg"))
        {
            echo "<img class='images' src='$title.jpg' style='float:left;'>";
        }
        else
        {
            echo "<img class='images2' src='temporary1.jpg' width='250px' height='300px'>";
        }
    }
    
    function showPictureHome($user) // This functiion is used to display the users image on the
    {                               // header of the page. Will be floated on the left side of the header
        if (file_exists("$user.jpg"))
        {
          echo "<img class='images2' src='$user.jpg' width='50px' height='50px'>";
        }
        else
        {
            echo "<img class='images2' src='temporary1.jpg' width='50px' height='50px'>";
        }
    }    
    
    function showThreads($user)
    {
        $result = queryMysql("SELECT title,views,rate FROM Thread WHERE user='$user'");
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
               // die("here");
                echo  '<a href="viewThread.php"><div class="insideThreadsMine">Title: '
                 . $row['title'] . '</a><br>'
                       .'Views: ' . $row['views'] . ' Rating:' . $row['rate'] . '</div></a>';

            }
        
        }
    }
    
    function showThreadsToCheck($user)
    {
        $result = queryMysql("SELECT * FROM Thread WHERE user='$user'"); /// an array of quering mysql
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            echo "<form method='post' action='profile.php'>";
            echo "<input type='submit' value='Display'>";
            while($row = $result->fetch_array(MYSQLI_ASSOC)) // Performing a while loop through the result of array/
            {                                               // Storing the result in the variable row
                $title = $row['title']; // retrieving the title column result from that row
                $views = $row['views'];
                $rate = $row['rate'];
                $t_id = $row['thread_id'];
                echo  <<<_END
                <a href="viewThread.php?view=$t_id"><div class="insideThreads">Title:
                  $title</a><br>
                       Views: $views     Rating: $rate
                        <br><input name="checkPost[]" value='$t_id' type="checkbox" type="submit"/></div></a>
_END;
            }
            
            echo "</form>";
        
        }
    }
      
    function showAllThreads()
    {
        $result = queryMysql("SELECT title,views,rate,user,thread_id FROM Thread"); //
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {                
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                echo <<<_A
                    <form method='post' action='index.php?'>
                        <div class='insideThreads'>
                            Title:  $theTitle  </a><br>
                       Views: $theViews  Rating: $theRatings <br>
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                          Created by: $theUser 
_A;
                        // $_SESSION['thread_id'] = $thre_id;
                echo <<<_B
                        
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>                         
_B;
            }
        }   
    } // end of showAllThreads function
    
// Paingation adapted and modified 
// Reference below
//PHP: Pagination - YouTube. 2015. PHP: Pagination - YouTube. [ONLINE] Available at: https://www.youtube.com/watch?v=KhHdt8CM4LU&spfreload=10.     
    
    
    function showAllThreadsUse()    // Will be used on the home page (index.php)
    {                               // This function will be initially called first
        $per_page=5;    // Each page will have 5 results
        $counter = 0;   /// Used to count how many threads exist so far 
        $pages_query = queryMysql("SELECT thread_id FROM Thread");  // Get all the results from the table Thread
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        
        if ($pages_query->num_rows == 0)
        {
           // die("no results");  // If a thread does not exist 
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++; /// Counting how many threads there are
            }
        }
        
        // Now I am able to calculate how many pages they are
        // E.g. If 20 threads exist (using counter) and I want to display 5 per page
        /// Calculating how many pages exist: 20/5 = 4 ... Hence counter/per_page
        // Used ceil to round up the value if we get a decimal value
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; 
        /// If a page hasn't be selectd. Default page is 1
        $start = ($page-1) * $per_page;     // Used to find what limit to start with to display threadslimit how many threads will exist
                                            // E.g. page=3, per_page=5, start = (3-1)*5 = 2*5= 10
                                            // Hence, limit from 10 - 15 
        $result = queryMysql("SELECT * FROM Thread LIMIT $start,$per_page"); // Limit how many Threads will be displayed on the page
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {                
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                $category1 = $row['category'];
                $comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewThread.php?view=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Views: $theViews  Rating: $theRatings  Category: $category1
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                          Created by: $theUser 
                            <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            } //// Used to display the threads on the page
          
            echo "</div>";
            // Now dealing with to select other pages, Previous page, and Next page
            $prev = $page - 1;
            $next = $page + 1;
            echo "<div class='homePage'>"; // Will be set inside the div
            if(!($page<=1)) // Only to be displayed if you are not viewing the 1st page
            {    
                echo "<a href='?page=$prev'>Prev</a>  ";       
            }
            if($pages>=1) // Display all page numbers
            {
                for($i=1; $i<=$pages; $i++) // Used for loop to display each number
                {
                    echo ($i==$page) ? '<b><a href="?page='.$i.'">'.$i.'</a></b> ' : '<a href="?page='.$i.'">'.$i.'</a> ';
                }
            }
            if(!($page>=$pages)) // Only to be displayed if you are not viewing the last page
            {
            echo "<a href='index.php?page=$next'>Next</a>  ";   
            }
            echo "</div>"; 
        }   
    } // end function showAllThreadsUse
    
    function displayByViews() // Used in the home page (index.php) Will be called as a filter 
    {                           // Most of the code is the same as above (function showAllThreadsUse )
                                // Differences will be explained by the line of code
        $per_page=5;
        $counter = 0;
        $pages_query = queryMysql("SELECT thread_id FROM Thread");
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        if ($pages_query->num_rows == 0)
        {
            die("no results");
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++;
            }
        }
        
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page;
                                            //// Showing all threads but in order of views (highest-lowest). 
        $result = queryMysql("SELECT * FROM Thread ORDER BY -(views+0) LIMIT $start,$per_page");
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {                
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                $category1 = $row['category'];
                $comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewThread.php?view=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Views: $theViews  Rating: $theRatings  Category: $category1
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                          Created by: $theUser 
                            <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            }
          
            echo "</div>";
            $prev = $page - 1;
            $next = $page + 1;
            echo "<div class='homePage'>";
            /// Will send two variables to the url. One is the page number. 
                                            // Second is to show a the views check box has been selected
            if(!($page<=1))
            {    
                echo "<a href='?page=$prev&checkBox=Views'>Prev</a>  ";       
            }
            if($pages>=1)
            {
                for($i=1; $i<=$pages; $i++)
                {
                    echo ($i==$page) ? '<b><a href="?page='.$i.'&checkBox=Views">'.$i.'</a></b> ' : '<a href="?page='.$i.'&checkBox=Views">'.$i.'</a> ';
                }
            }
            if(!($page>=$pages))
            {
            echo "<a href='index.php?page=$next&checkBox=Views'>Next</a>  ";   
            }
            echo "</div>";
        }  
    } // end function displayByViews
    
    function displayByRatings($rate) // Used in the home page (index.php) Will be called as a filter 
    {                               // Most of the code is the same as above (function displayByViews )
                                    // Differences will be explained by the line of code
        $per_page=5;
        $counter = 0;
        $pages_query = queryMysql("SELECT thread_id FROM Thread");
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        if ($pages_query->num_rows == 0)
        {
            die("no results");
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++;
            }
        }
        
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page;            // Showing from the order of rate order of (highest - lowest)
        $result = queryMysql("SELECT * FROM Thread ORDER BY -(rate+0) LIMIT $start,$per_page");
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {                
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                $category1 = $row['category'];
                $comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewThread.php?view=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Views: $theViews  Rating: $theRatings  Category: $category1
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                          Created by: $theUser  
                            <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            }
          
            echo "</div>";
            $prev = $page - 1;
            $next = $page + 1;
            echo "<div class='homePage'>";
            // sending both the page number and the checkbox equal to Rate
            if(!($page<=1))
            {    
                echo "<a href='?page=$prev&checkBox=Rate'>Prev</a>  ";       
            }
            if($pages>=1)
            {
                for($i=1; $i<=$pages; $i++) 
                {
                    echo ($i==$page) ? '<b><a href="?page='.$i.'&checkBox=Rate">'.$i.'</a></b> ' : '<a href="?page='.$i.'&checkBox=Rate">'.$i.'</a> ';
                }
            }
            if(!($page>=$pages))
            {
            echo "<a href='index.php?page=$next&checkBox=Rate'>Next</a>  ";   
            }
            echo "</div>";
        }  
    } // end function displayByRatings
    
    function showAllComments($title, $convo_id) // Called from the page viewThread.php
    {                                           // Used to display all comments from a thread 
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
                        <a href="#" onclick="likeDislikeTheComment('$cid', '1', 'normal')">Like</a>
                    </div>
                        
                    <div class='individualUser'>
                        <a href="#" onclick="likeDislikeTheComment('$cid', '0', 'normal')">Dislike</a>   
                    </div>
                </div>
_A;
            }
            
        }
        
    } // end function showAllComments
    
    function showLeastRatedComments($title, $convo_id)  // Called from the page viewThread.php
    {
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
    } // end of least higher rated comments
    
    function showLeastRatedCommentsLive($title, $convo_id)  // Called from the page viewThread.php
    {
        // Used to display in a order of the least rated comments. (lowest-highest). 
        $result = queryMysql("SELECT id, comment,rate,user FROM liveCommentThread WHERE title='$title' ORDER BY (rate+0)");
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
                    <textarea type="text" readonly="readonly" cols='79' rows='3'>$comment</textarea> </div>  
            <div class='individualUser'>  <span id='liveDetails'>     Rate: $rate </span>
             <br>   <span id='liveDetails'>  Posted by $user </span></div>
                        <div class='individualUser'>
                <form method='post' action='viewLiveThread.php?id=$convo_id'>
                    <input type='hidden' name='increaseEach' value='$cid'>
                    <input type='submit' id="hitlike" value='Like'>
                </form> </div>
                        <div class='individualUser'>
                <form method='post' action='viewLiveThread.php?id=$convo_id'>
                    <input type='hidden' name='decreaseEach' value='$cid'> 
                    <input type='submit' id="hitDislike" value='Dislike'>
                </form>    
                    </div>
                </div>
_A;
            }

        }
    } // end of least higher rated comments
    
    function showHigherRatedComments($title, $convo_id) // Called from the page viewThread.php
    {
        // Used to display in a order of the highest rated comments. (highest-lowest).
        $result = queryMysql("SELECT id, comment,rate,user FROM commentThread WHERE title='$title' ORDER BY -(rate+0)");
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
                        <a href="#" onclick="likeDislikeTheComment('$cid', '1', 'highest')">Like</a>
                    </div>
                        
                    <div class='individualUser'>
                        <a href="#" onclick="likeDislikeTheComment('$cid', '0', 'highest')">Dislike</a>   
                    </div>
                </div>
_A;
            }
        }
    } // end of higher rated comments 
    
    
    function showHigherRatedCommentsLive($title, $convo_id) // Called from the page viewLiveThread.php
    {
        // Used to display in a order of the highest rated comments. (highest-lowest).
        $result = queryMysql("SELECT * FROM liveCommentThread WHERE title='$title' ORDER BY -(rate+0)");
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
                    <textarea type="text" readonly="readonly" cols='79' rows='3'>$comment</textarea> </div>  
            <div class='individualUser'>  <span id='liveDetails'>     Rate: $rate </span>
             <br>   <span id='liveDetails'>  Posted by $user </span></div>
                        <div class='individualUser'>
                <form method='post' action='viewLiveThread.php?id=$convo_id'>
                    <input type='hidden' name='increaseEach' value='$cid'>
                    <input type='submit' id="hitlike" value='Like'>
                </form> </div>
                        <div class='individualUser'>
                <form method='post' action='viewLiveThread.php?id=$convo_id'>
                    <input type='hidden' name='decreaseEach' value='$cid'> 
                    <input type='submit' id="hitDislike" value='Dislike'>
                </form>    
                    </div>
                </div>
_A;
            }
        }
    } // end of higher rated comments 
    
    function showMostViewsThreads()
    {
        $result = queryMysql("SELECT title,views,rate,user,thread_id FROM Thread ORDER BY -(views+0)");
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {                
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                echo <<<_A
                    <form method='post' action='index.php?'>
                        <div class='insideThreads'>
                        ID: $thre_id
                            Title:  $theTitle  </a><br>
                       Views: $theViews  Rating: $theRatings <br>
                          Created by: $theUser 
                        <a href='viewThread.php?view=$thre_id'>Click</a> 
_A;
                        // $_SESSION['thread_id'] = $thre_id;
                echo <<<_B
                        <input type='submit' value='View'>
                        <input type='text' maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>                         
_B;
            }
        }   
    } // end of MostViewsThreads function 
    
    function showLeastViewsThreads()
    {
        $result = queryMysql("SELECT title,views,rate,user,thread_id FROM Thread ORDER BY (views+0)");
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {                
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                echo <<<_A
                    <form method='post' action='index.php?'>
                        <div class='insideThreads'>
                        ID: $thre_id
                            Title:  $theTitle  </a><br>
                       Views: $theViews  Rating: $theRatings <br>
                          Created by: $theUser 
                        <a href='viewThread.php?view=$thre_id'>Click</a> 
_A;
                        // $_SESSION['thread_id'] = $thre_id;
                echo <<<_B
                        <input type='submit' value='View'>
                        <input type='text' maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>                         
_B;
            }
        }   
    } // end of LeastViewsThreads function 
    
    function addPointsToUser($user) // Multiple pages call this function to increase the user score
    {
        $result = queryMysql("SELECT * FROM members where user='$user'");
        if ($result->num_rows)
        {
            // setting the score to increase by 1 for the user from the table members
            queryMysql("UPDATE members SET score=score+1 WHERE user='$user'");
        }
    } // end function addPointsToUser
    
    function decreasePointsToUser($user)
    {
        $result = queryMysql("SELECT * FROM members where user='$user'");
        if ($result->num_rows)
        {
            // setting the score to decrease by 1 for the user from the table members
            queryMysql("UPDATE members SET score=score-1 WHERE user='$user'");
        }
    } /// end function decreasePointsToUser
    
    function showPictures($user) // Called from the page photos.php
    {
        // pictures are saved in the table pic. Hence a user can have multiple pictures
       $result = queryMysql("SELECT * FROM pic where user='$user'");
       
        if ($result->num_rows)
        {
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $getid = $row['pic_id'];
                $t1 = $row['text'];
                echo "<div class='perPic'>";
                echo '<img src="' . $row['picture'] . '" width="400px" height="400px" />';
                echo <<<_END
                
                <div class='photoText'>
                <textarea cols='60' rows='5'>$t1 </textarea>
                <br><br>
_END;
                if($user == $_SESSION['user'])
                {
                // give an option to add in details for each image
                echo <<<_END
                
                <form method='post' action='photos.php'>$error
                <span class='putdetails'>Details</span>
                <br>
                <textarea name='texta' cols='60' rows='5 value='$text'> </textarea>
                <input name ='p_id' type='hidden' value='$getid'>
                <input type='submit' value='Update'>
                </form>
_END;
                }
                echo "</div>";
                echo "</div>";
                echo "<br><br><br>";
            }
        }
    } // end function showPictures
    
    function showCategories($category, $counter)
    {
        $per_page=5;        
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page; 
         // end if statement
        makePrevNextPages($pages, $page);
    } // end function showCategories
    
    function makePages($counter)
    {
        $per_page=5;
        
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page;
        makePrevNextPages($pages, $page);
    } // end function makePages
    
    function makePrevNextPages($pages, $page)
    {
        $prev = $page - 1;
        $next = $page + 1;
        echo "<div class='homePage'>";
        if(!($page<=1))
        {    
            echo "<a href='?page=$prev'>Prev</a>  ";       
        }
        if($pages>=1)
        {
            for($x=1; $x<=$pages; $x++)
            {
                echo ($x==$page) ? '<b><a href="?page='.$x.'">'.$x.'</a></b> ' : '<a href="?page='.$x.'">'.$x.'</a> ';
            }
        }
        if(!($page>=$pages))
        {
        echo "<a href='index.php?page=$next'>Next</a>  ";   
        }
        echo "</div>";
    } // end function makePrevNextPages
    
    function countCategories($category, $counter)
    {
        $pages_query = queryMysql("SELECT thread_id FROM Thread WHERE category='$category'");
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        if ($pages_query->num_rows == 0)
        {
            
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++;
            }
        }
        

        
        return $counter;
    } // end function countCategories
    
    function displayCategories($category) // called by the home page (index.php)
    {
        /// Works similar as the function showAllThreadsUse, but displays only one category 
        $pages_query = queryMysql("SELECT thread_id FROM Thread WHERE category='$category'");
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        $counter =0;
        if ($pages_query->num_rows == 0)
        {           
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++;
            }
        }        
        $per_page=5;
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page;
        
        $result = queryMysql("SELECT * FROM Thread where category='$category' LIMIT $start,$per_page");      
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                $category1 = $row['category'];
                $comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewThread.php?view=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Views: $theViews  Rating: $theRatings  Category: $category1
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                          Created by: $theUser     
                            <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            }
        }
          
        $prev = $page - 1;
        $next = $page + 1;
        echo "<div class='homePage'>";
        // Going to send to the url both page number and the category chosen
        if(!($page<=1))
        {    
            echo '<a href="?page='.$prev.'&checkPost='.$category.'">Prev</a> ';       
        }
        if($pages>=1)
        {
            for($i=1; $i<=$pages; $i++)
            {
                echo ($i==$page) ? ' <b><a href="?page='.$i.'&checkPost='.$category.'">'.$i.'</a></b> ' : ' <a href="?page='.$i.'&checkPost='.$category.'">'.$i.'</a> ';
            }
        }
        if(!($page>=$pages))
        {
        echo ' <a href="?page='.$next.'&checkPost='.$category.'">Next</a> ';   
        }
        echo "</div>";
    } // END FUNCTION
    
   function viewWatching($user) // function is called from profile.php and otherProfile.php
   {
        $count = 0;
        // friend is listed as watcher member.
        // Therefore I am retrieving all the rows that the user is a watcher
        // Hence, I can see who they are watching by getting the column 'user'
        $result = queryMysql("SELECT * FROM friends where friend='$user'");
        if ($result->num_rows == 0)
        {
            $error1 = "<span class='error'>Not Watching any</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {       
                // Going through all the rows and storing who the user is watching
                // in the variabele $watching
                $theuser = $row['friend'];
                $watching = $row['user'];
                // I only want to show the first 12 members. 
                // Therefore this list acts as a preview all the watching members
                if($count < 12)
                {
                    watchingshowPicture($watching); // Get the watching member profile picture
                }
                $count++; // increasing counter
            }
        }
   } // end function viewWatching
   
    function watchingshowPicture($user) // called by the function above (viewWatching)
    {
        if (file_exists("$user.jpg"))
        {
            echo "<div class='watchingimages2'>";
            echo "<a href='otherProfile.php?view=$user'><img src='$user.jpg' style='float:left;' width='50px' height='50px'></a>";
            echo "<br><a href='otherProfile.php?view=$user'>$user</a></div>";
        }
        else 
        {
            echo "<div class='watchingimages2'>";
            echo "<a href='otherProfile.php?view=$user'><img src='temporary1.jpg' style='float:left;' width='50px' height='50px'></a>";
            echo "<br><a href='otherProfile.php?view=$user'>$user</a></div>";
        }
 
    } // end function watchingshowPicture
    
   function viewWatchers($user) // called by the page profile.php and otherProfile.php
   {
        // works similar as viewWatching function
        // but getting all the watchers from the users.
        $count = 0;
        $result = queryMysql("SELECT * FROM friends where user='$user'");
        if ($result->num_rows == 0)
        {
            $error1 = "<span class='error'>Not any Watchers</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {       
                $theuser = $row['friend'];
                $watcher = $row['user'];
                if($count < 12)
                {
                    watchingshowPicture($theuser);
                }
                $count++;
            }
        }
   } // end function viewWatchers
    
    function countLikeThreads($title, $convo_id) // counting all the likes from a thread
    {
        $i =0;
        $result = queryMysql("SELECT * FROM likeThread where title='$title'");
        // retrieving all the likes using title = thread title
        if ($result->num_rows == 0)
        {
        }
        else 
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $i = $i + $row['rate'];
                // Adding all the rates from the rows by using the for loop
                // storing this in the variable $i
            }  
        }
        // After all the rates has been added up. Now I am able to set the chosen thread rate
        // if there are no change in threads. $i has already been set to 0 before the while loop
        queryMysql("UPDATE Thread SET rate='$i' where thread_id='$convo_id'");
    } // end function countLikeThreads
    
    function countCommentLike($c) // This function is called from viewThread
    {
        /// This function is to count the likes for a comment in a thread 
        $i = 0;
        $result = queryMysql("SELECT * FROM likeCommentThread where commentThread_id='$c'");
        // find the comment using the comment id = $c 
        if ($result->num_rows == 0)
        {
        }
        else 
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $i = $i + $row['rate']; // Counting the rate for comments
            }  
        }
        // Once the rate has been added up. Set the rate into the table commentThread
        queryMysql("UPDATE commentThread SET rate='$i' where id='$c'");
    } // end function countCommentLike
    
    function countLiveCommentLike($c) // This function is called from viewThread
    {
        /// This function is to count the likes for a comment in a thread 
        $i = 0;
        $result = queryMysql("SELECT * FROM liveLikeComment where comment_id='$c'");
        // find the comment using the comment id = $c 
        if ($result->num_rows == 0)
        {
        }
        else 
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $i = $i + $row['rate']; // Counting the rate for comments
            }  
        }
        // Once the rate has been added up. Set the rate into the table commentThread
        queryMysql("UPDATE liveCommentThread SET rate='$i' where id='$c'");
    } // end function countCommentLike
    
    
    
    
    function displayProfileThreads($user) // called by the profile.php and otherProfile.php
    {
        $result = queryMysql("SELECT * FROM profile where user='$user'");
        if ($result->num_rows == 0)
        {
            echo "<h6>No thread chosen yet</h6>";
        }
        else 
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $thread_id = $row['thread_id'];
            $thread_id2 = $row['thread_id2'];
            $thread_id3 = $row['thread_id3'];
                $result2 = queryMysql("SELECT * FROM Thread WHERE thread_id='$thread_id'");
                $row2 = $result2->fetch_array(MYSQLI_ASSOC);
                $title = $row2['title'];
                $views = $row2['views'];
                $rate = $row2['rate'];
                $comment = $row2['comment'];
                
                $result3 = queryMysql("SELECT * FROM Thread WHERE thread_id='$thread_id2'");
                $row3 = $result3->fetch_array(MYSQLI_ASSOC);
                $title2 = $row3['title'];
                $views2 = $row3['views'];
                $rate2 = $row3['rate'];
                $comment2 = $row3['comment'];
                
                $result4 = queryMysql("SELECT * FROM Thread WHERE thread_id='$thread_id3'");
                $row4 = $result4->fetch_array(MYSQLI_ASSOC);
                $title3 = $row4['title'];
                $views3 = $row4['views'];
                $rate3 = $row4['rate'];
                $comment3 = $row4['comment'];
                echo "<h3>Threads chosen to be displayed</h3>";
            if($thread_id2 !=0)
            {
            echo <<<_A
             <div class='insideThreads'>   <a href='viewThread.php?view=$thread_id2'>$title2</a><br>
                Views: $views2   <br>
                Rating: $rate2
                    <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment2</p></div>         
_A;
                
            }
            if($thread_id3 !=0)
            {
            echo <<<_A
             <div class='insideThreads'>   <a href='viewThread.php?view=$thread_id3'>$title3</a><br>
                Views: $views3   <br>
                Rating: $rate3
                    <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment3</p></div>         
_A;
            }
                
            if($title != '')
            {
            echo <<<_A
             <div class='insideThreads'>   <a href='viewThread.php?view=$thread_id'>$title</a><br>
                Views: $views   <br>
                Rating: $rate
                    <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p></div>         
_A;
            }
            elseif($user == $_SESSION['user'])
            {
            echo <<<_B
            <div class='insideThreads'> <br> No Thread has been selected<br>
                <a href='chooseThreads.php'>Select Thread</a>
            </div>
_B;
            }
            else
            {
            echo <<<_B
            <p div='insideThreads'> <br> $user has not selected any yet!<br>
                    Why don't You sent him a message to upload one!
            </div>
_B;
            }
        }
    } // end function displayProfileThreads
    
    function displayChatRooms() // called by the function allChatRooms.php 
    {
        // Displayed all the chat rooms in order of the latest chat room
        $result = queryMysql("SELECT * FROM overChat WHERE type='public' ORDER by id DESC");
        if ($result->num_rows == 0)
        {
        }
        else 
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $t = $row['title'];
                $createdby = $row['user'];
                $type = $row['type'];
                $chat_id = $row['id'];
                echo <<<_END
                    <div class='individualChat'>
                        <a href='chatroom.php?view=$t'>$t</a><br>
                        Created by: $createdby <br>
                        Type: $type
                
                    </div>
   
_END;
            }  
        }

    } // end function displayChatRooms
    
    function viewGroups($user) // This function is called from the profile.php and otherProfile.php
    {
        $count = 0;
        $result = queryMysql("SELECT * FROM individual_group where user='$user'");
        if ($result->num_rows == 0)
        {
            $error1 = "<span class='error'>Not in any groups</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {       
                $user = $row['user'];
                $title = $row['title'];
                if($count < 12) // only displaying 12 groups
                {
                    groupsShowPicture($title);
                }
                $count++;
            }
        }
    } // end function viewGroups
    
    function groupsShowPicture($title) // 
    {
        if (file_exists("$title.jpg"))
        {
            echo "<div class='watchingimages2'>";
            echo "<a href='group.php?view=$title'><img src='$title.jpg' style='float:left;' width='50px' height='50px'></a>";
            echo "<br><a href='group.php?view=$title'>$title</a></div>";
        }
        else 
        {
            echo "<div class='watchingimages2'>";
            echo "<a href='group.php?view=$title'><img src='temporary1.jpg' style='float:left;' width='50px' height='50px'></a>";
            echo "<br><a href='group.php?view=$title'>$title</a></div>";
        }
 
    } // end function groupsShowPicture
    
    function displayAllGroups() // function called by the page groups.php
    {
        $result = queryMysql("SELECT * FROM groups");
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $title = $row['title'];
                $createdby = $row['user'];
                
                echo <<<_END
                    <div class='perLine'>
                        <a href='group.php?view=$title'>$title</a><br>
                        Creater: $createdby <br>
                    </div>
_END;
            
            }
        }
    } // end function displayAllGroups
    
    function countMembersOfGroup($title) // function called by the group.php 
    {
        // counting all the members that exist in the group
        // The $count variable is returned and displayed in group.php
        $result = queryMysql("SELECT * FROM individual_group WHERE title='$title'");
        $count = 0;
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $count++;
            }
        }
        return $count;
    } // end function countMembersOfGroup
    
    function displayGroupChatRooms($title) // used by the page allChatRooms.php
    {
        // Used to only display the private chat rooms if the user exist in a group
        //
        $result = queryMysql("SELECT * FROM individual_group WHERE title='$title'");
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $user = $row['user'];
                $result2 = queryMysql("SELECT * FROM overChat WHERE user='$user' ORDER by id DESC");
                     while($row2 = $result2->fetch_array(MYSQLI_ASSOC))
                     {
                        $title1 = $row2['title'];
                        $createdby = $row2['user'];
                        $type = $row2['type'];
                        if($type == "Private")
                        {
                            echo <<<_END
                                <div class='individualChat'>
                                    <a href='chatroom.php?view=$title1'>$title1</a><br>
                                    Created by: $createdby <br>
                                    Type : $type
                                </div>
_END;
                        }
                     }
            }
        }
    } // end function displayGroupChatRooms
    
    function removeOldLiveThreads() // Called by multiple pages
    {
        // Check if the live Thread is expired.. Checking its type(duration) first
        // If expired, delete from the table liveThread
        queryMysql("DELETE FROM liveThread WHERE duration=600 AND time_created < (NOW() - INTERVAL 10 MINUTE)");
        queryMysql("DELETE FROM liveThread WHERE duration=1800 AND time_created < (NOW() - INTERVAL 30 MINUTE)");
        queryMysql("DELETE FROM liveThread WHERE duration=3600 AND time_created < (NOW() - INTERVAL 60 MINUTE)");
        
        // Now Will check their comments
        removeOldLiveComments();
        /// Now remove the likes from the comment
        removeLikesFromComment();
    } // ends function removeOldLiveThreads
    
    function removeOldLiveComments()
    {
        $result = queryMysql("SELECT * FROM liveCommentThread");
        
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $title = $row['title'];
                $id = $row['id'];
                $result2 = queryMysql("SELECT * FROM liveThread WHERE title='$title'");
                if(!($result2->num_rows))
                {
                    /// Now remove the comment 
                    queryMysql("DELETE FROM liveCommentThread WHERE title='$title'");
                }
            }
        }
        else 
        {
            $reply = "No comments to delete";
        }     
    } // end function removeOldLiveComments
    
    function removeLikesFromComment()
    {
        $result = queryMysql("SELECT * FROM liveLikeComment");
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $id = $row['comment_id'];
                $result2 = queryMysql("SELECT * FROM liveCommentThread WHERE id='$id'");
                if(!($result2->num_rows))
                {
                    queryMysql("DELETE FROM liveLikeComment WHERE comment_id='$id'");
                }
            }
        }
    }
    
    function displayAllLiveThreads() // function called by the home page (index.php) 
    {
        // Using pages is explained line by line in the function showAllThreadsUse
        // But this is only displaying threads from the table liveThread
        // This has been selected from filter option
        $per_page=5;
        $counter = 0;
        $pages_query = queryMysql("SELECT id FROM liveThread");
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        if ($pages_query->num_rows == 0)
        {
            die("no results");
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++;
            }
        }
        
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page;
        $result = queryMysql("SELECT * FROM liveThread LIMIT $start,$per_page");
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No threads</span><br><br>";
        }
        elseif($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {                
                $thre_id = $row['id']; 
                $theTitle = $row['title'];
                $duration = $row['duration'];
		$comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewLiveThread.php?id=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Duration: $duration
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                            <h5 id='LiveComment'>LIVE</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            }
          
            echo "</div>";
            $prev = $page - 1;
            $next = $page + 1;
            echo "<div class='homePage'>";
            if(!($page<=1))
            {    
                echo "<a href='?page=$prev&live=live'>Prev</a>  ";       
            }
            if($pages>=1)
            {
                for($i=1; $i<=$pages; $i++)
                {
                    echo ($i==$page) ? '<b><a href="?page='.$i.'&live=live">'.$i.'</a></b> ' : '<a href="?page='.$i.'&live=live">'.$i.'</a> ';
                }
            }
            if(!($page>=$pages))
            {
            echo "<a href='index.php?page=$next&live=live'>Next</a>  ";   
            }
            echo "</div>";
        }   
        
    } // end function displayAllLiveThreads 
    
    function displayGroupsWithUser($user, $convo_id) // called by the page viewThread
    {
        // Used to share a thread from viewThread page to the selected group.
        $result = queryMysql("SELECT * FROM individual_group WHERE user='$user'");
        
        if($result ->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $gtitle = $row['title'];
                echo <<<_END
                <form method='post' action='viewThread.php?view=$convo_id'>
                <input id="checkbox" name="checkBox" type="checkbox" value="$gtitle" />$gtitle<br>
_END;
            }
            echo <<<_A
                <input type='submit' value='Share'>
            </form>
_A;
        }
    } // end function displayGroupsWithUser
    
    function displayGroupMembers($group_title) // called by the page group.php
    {
        // Displaying each member and able to click on their name to lead to their profile
        $result = queryMysql("SELECT * FROM individual_group WHERE title='$group_title'");
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $member = $row['user'];
                echo <<<_END
                <span id='members'><a href='otherProfile.php?view=$member'> $member</a></span><br>
                
_END;
            }
        }
        
    } // end function displayGroupMembers
    
    function displaySharedThreads($group_title) // Called by the page group.php
    {
        // Once a thread has been shared. Retrieve all the threads that exist in the group and display on the group page
        $result = queryMysql("SELECT * FROM groupThreads WHERE group_title='$group_title'");
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $thre_id = $row['thread_id']; 
                $theTitle = $row['thread_title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $category1 = $row['category'];
                $comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewThread.php?view=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Views: $theViews  Rating: $theRatings  Category: $category1
                        <input type='submit' class='inputbutton' value='View'>
                        <br> 
                            <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            }
        }
    } // end function displaySharedThreads
    
    function displayMembersRecommended($user) // Called by the page findMembers.php
    {
        $result1 = queryMysql("SELECT score FROM members WHERE user='$user'");
        $row = $result1->fetch_array(MYSQLI_ASSOC);
        $score = $row['score'];
        $max = $score + 5;
        $min = $score - 5;
        // To find the members recommended, is to to find if their score is in the range of max and min
        // Reason for this: To find a member who uses the web application as much as the user 
        if($min < 0)
        {
            $min = -1;
        }
        
        $result2 = queryMysql("SELECT * FROM members WHERE '$min'<score AND score<'$max' AND NOT user='$user'");
        
        if($result2->num_rows)
        {
            while($row2 = $result2->fetch_array(MYSQLI_ASSOC))
            {
                $name = $row2['user'];
                $score = $row2['score'];
                echo <<<_END
                <div class='individualUser'>
                Score: $score<br>
_END;
                showUserPicture($name);
                $reply = getWatching($name, $user);
                if($reply == "Watching this user")
                {
                    echo <<<_END
                    <a href='otherProfile.php?view=$name'>$name</a><br>
                    <a href='findMembers.php?stopWatching=$name'>[Stop Watching]</a>
                   </div>
_END;
                }
                else
                {
                    echo <<<_END
                    <a href='otherProfile.php?view=$name'>$name</a><br>
                    <a href='findMembers.php?startWatching=$name'>[Watch]</a>
                   </div>
_END;
                } // end else statement
            }
        }
        else // If no recommended members were found. Display all members
        {
            displayAllMembers($user);
        }
    } // end function displayMembersRecommended
    
    function showUserPicture($user)
    {
        if (file_exists("$user.jpg"))
        {
            echo <<<_A
            <img src='$user.jpg' width="50" height="50">
_A;
        }
        else
        {
            echo "<img src='temporary1.jpg' width='50px' height='50px'>";
        }
    }
    
    function getWatching($watching, $watcher) // Check if the user is watching this member
    {
        $result = queryMysql("SELECT * FROM friends where friend='$watcher' AND user='$watching'");
        if ($result->num_rows == 0)
        {
            $reply = "Not Watching this user";
        }
        elseif($result->num_rows)
        {
            $reply = "Watching this user";
        }
        return $reply;
   } // end function getWatching
   
   function displayAllMembers($user) // called by the page findMembers.php
   {
       // Display all members. Then checks using function getWatching, if the user is watching them.
        $result2 = queryMysql("SELECT * FROM members WHERE NOT user='$user'");
        
        if($result2->num_rows)
        {
            while($row2 = $result2->fetch_array(MYSQLI_ASSOC))
            {
                $name = $row2['user'];
                $score = $row2['score'];
                echo <<<_END
                <div class='individualUser'>
                Score: $score<br>
_END;
                showUserPicture($name);
                $reply = getWatching($name, $user);
                if($reply == "Watching this user")
                {
                    // Gives an option to stop watching the member.
                    echo <<<_END
                    <a href='otherProfile.php?view=$name'>$name</a><br>
                    <a href='findMembers.php?stopWatching=$name'>[Stop Watching]</a>
                   </div>
_END;
                }
                else
                {
                    // Gives an option to start watching the member.
                    echo <<<_END
                    <a href='otherProfile.php?view=$name'>$name</a><br>
                    <a href='findMembers.php?startWatching=$name'>[Watch]</a>
                   </div>
_END;
                } // end else statement
            }
        }
   } // end function displayAllMembers
   
   function displayOnlyWatchingMembers($user) // called by the page viewWatching.php
   {
       // display all the watching members from the user
       $result = queryMysql("SELECT * FROM friends WHERE friend='$user'");
       
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $name = $row['user'];
                $score = $row['score'];
                echo <<<_END
                <div class='individualUser'>
_END;
                showUserPicture($name);
                $reply = getWatching($name, $user);
                if($reply == "Watching this user")
                {
                    // gives an option to stop watching the member
                    // If clicked on, the member would be removed from the list.
                    // Option: Member can be followed again from findMembers.php page
                    echo <<<_END
                    <a href='otherProfile.php?view=$name'>$name</a><br>
                    <a href='viewWatching.php?stopWatching=$name'>[Stop Watching]</a>
                   </div>
_END;
                }
            }
        }
   } // end function displayOnlyWatchingMembers 
   
   function displayOnlyWatchersMembers($user) // called by the page viewWatchers.php
   {
       // displaying all the members that is watching the user
       $result = queryMysql("SELECT * FROM friends WHERE user='$user'");
       
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $name = $row['friend'];
                $score = $row['score'];
                echo <<<_END
                <div class='individualUser'>
_END;
                showUserPicture($name);
                $reply = getWatching($user, $name); //// swapped name and user. To get correct reply
                if($reply == "Watching this user")
                {
                    echo <<<_END
                    <a href='otherProfile.php?view=$name'>$name</a><br>
                    
                   </div>
_END;
                }
            }
        }
   } // end function displayOnlyWatchersMembers
   
   function displayViewsAndCategory($category) // called by the home page (index.php) 
   {
        // Works similar as showAllThreadsUse. But using two uses to filters out the threads
        // filtering out views and a chosen category
        // Count the threads that exist with the selected category
        $pages_query = queryMysql("SELECT thread_id FROM Thread WHERE category='$category'");
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        $counter =0;
        if ($pages_query->num_rows == 0)
        {           
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++;
            }
        }        
        $per_page=5;
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page;
        
        // Therefore display the threads in order of the highest viewed with the selected category
        $result = queryMysql("SELECT * FROM Thread where category='$category' ORDER BY -(views+0) LIMIT $start,$per_page");      
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                $category1 = $row['category'];
                $comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewThread.php?view=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Views: $theViews  Rating: $theRatings  Category: $category1
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                          Created by: $theUser     
                            <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            }
        }
          
        $prev = $page - 1;
        $next = $page + 1;
        echo "<div class='homePage'>";
        if(!($page<=1))
        {    
            echo '<a href="?page='.$prev.'&checkPost='.$category.'&checkBox=Views">Prev</a> ';       
        }
        if($pages>=1)
        {
            for($x=1; $x<=$pages; $x++)
            {
                echo ($x==$page) ? ' <b><a href="?page='.$x.'&checkPost='.$category.'&checkBox=Views">'.$x.'</a></b> ' : ' <a href="?page='.$x.'&checkPost='.$category.'&checkBox=Views">'.$x.'</a> ';
            }
        }
        if(!($page>=$pages))
        {
        echo ' <a href="?page='.$next.'&checkPost='.$category.'&checkBox=Views">Next</a> ';   
        }
        echo "</div>";
   } // end function displayViewsAndCategory
   
   function displayRateAndCategory($category) // called by the home page (index.php) 
   {
        // Works similar as showAllThreadsUse. But using two uses to filters out the threads
        // filtering out rate and a chosen category
        // Count the threads that exist with the selected category
        $pages_query = queryMysql("SELECT thread_id FROM Thread WHERE category='$category'");
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        $counter =0;
        if ($pages_query->num_rows == 0)
        {           
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++;
            }
        }        
        $per_page=5;
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page;
        
        // Now retrieve all the threads with the selected category in order of highest rated to lowest rated
        $result = queryMysql("SELECT * FROM Thread where category='$category' ORDER BY -(rate+0) LIMIT $start,$per_page");      
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                $category1 = $row['category'];
                $comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewThread.php?view=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Views: $theViews  Rating: $theRatings  Category: $category1
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                          Created by: $theUser     
                            <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            }
        }
          
        $prev = $page - 1;
        $next = $page + 1;
        echo "<div class='homePage'>";
        if(!($page<=1))
        {    
            echo '<a href="?page='.$prev.'&checkPost='.$category.'&checkBox=Rate">Prev</a> ';       
        }
        if($pages>=1)
        {
            for($x=1; $x<=$pages; $x++)
            {
                echo ($x==$page) ? ' <b><a href="?page='.$x.'&checkPost='.$category.'&checkBox=Rate">'.$x.'</a></b> ' : ' <a href="?page='.$x.'&checkPost='.$category.'&checkBox=Rate">'.$x.'</a> ';
            }
        }
        if(!($page>=$pages))
        {
        echo ' <a href="?page='.$next.'&checkPost='.$category.'&checkBox=Rate">Next</a> ';   
        }
        echo "</div>";
   } // end function displayRateAndCategory
   
   function displayMessages($receiver) // called by the page messages.php
   {
       /// Show all the messages that the user has not replied to, and give an option to write back
       $result = queryMysql("SELECT * FROM message WHERE sendto='$receiver' AND readMessage='no'");
       
       if($result->num_rows)
       {
           while($row = $result->fetch_array(MYSQLI_ASSOC))
           {
                $message = $row['themessage'];
                $creater = $row['creater'];
                $m_id = $row['id'];

                echo <<<_DISPLAY
                <div class='message'>
                
                <textarea rows='3' cols='79' readonly="readonly">$message</textarea>           
                    <div class='replyMessage'>
                        <form method='post' action='messages.php?checkBox=0'>
                            <textarea name='text' rows='3' cols='60' value='$reply'></textarea>
                            <input type='submit' value='Reply'>
                            <input type='hidden' name='sendingTo' value="$creater">
                            <input type='hidden' name='replied' value="$m_id">
                        </form>
                    </div><br>
                Sent by: $creater
                </div>
                
_DISPLAY;
           }
       }
        else 
        {
            echo <<<_DISPLAY
            <div class='message'>
            YOU HAVE NO NEW MESSAGES
            </div>
_DISPLAY;
        }
   } // end function displayMessages
   
   function displayAllMessages($receiver) // called by the page messages.php
   {
       // display all messages, even if the user has replied to already 
       $result = queryMysql("SELECT * FROM message WHERE sendto='$receiver'");
       
       if($result->num_rows)
       {
           while($row = $result->fetch_array(MYSQLI_ASSOC))
           {
                $message = $row['themessage'];
                $creater = $row['creater'];
                $m_id = $row['id'];

                echo <<<_DISPLAY
                <div class='message'>
                
                <textarea rows='3' cols='79' readonly="readonly">$message</textarea>
                
                

                    <div class='replyMessage'>
                        <form method='post' action='messages.php?checkBox=1'>
                            <textarea name='text' rows='3' cols='60' value='$reply'></textarea>
                            <input type='submit' value='Reply'>
                            <input type='hidden' name='sendingTo' value="$creater">
                            <input type='hidden' name='replied' value="$m_id">
                        </form>
                    </div><br>
                Sent by: $creater
                </div>
                
_DISPLAY;
           }
       }
        else 
        {
            echo <<<_DISPLAY
            <div class='message'>
            YOU HAVE NO MESSAGES
            </div>
_DISPLAY;
        }
   } // end function displayAllMessages
   
   function DisplayActivity($user) // called by the page activity.php
   {
       // to find what the members are doing that the user is watching
       // E.g. if the member is commenting on a page, liking a thread, creating a new thread
       $result = queryMysql("SELECT * FROM friends WHERE friend='$user'");
       
       if($result->num_rows)
       {
           while($row = $result->fetch_array(MYSQLI_ASSOC))
           {
               $member = $row['user'];
               $result2 = queryMysql("SELECT * FROM activity WHERE member='$member' ORDER BY id DESC");
               if($result2->num_rows)
               {
                   while($row2 = $result2->fetch_array(MYSQLI_ASSOC))
                   {
                       $type = $row2['type'];
                       $details = $row2['details1'];
                       $details2 = $row2['details2'];
                       $id = getIdWithThreadTitle($details);
                       $liveId = getLiveId($details);
                       if($type == "New Thread Page")
                       {
                       echo <<<_DISPLAY
                           <div class='insideThreads'>
                           $member has created a new Thread called $details
                            <br> Comment: $details2
                            <br> <a href='viewThread.php?view=$id'>Click Here</a> to view The page
                           </div>
                       
_DISPLAY;
                       }
                       elseif($type == "Commented on Thread Page")
                       {
                       echo <<<_DISPLAY
                           <div class='insideThreads'>
                           $member has commented on the Thread page $details
                            <br> Comment : $details2
                            <br> <a href='viewThread.php?view=$id'>Click Here</a> to view The page
                           </div>
_DISPLAY;
                       }
                       elseif($type == "Liked Thread Page")
                       {
                       echo <<<_DISPLAY
                           <div class='insideThreads'>
                           $member has liked $details Thread page
                            <br> <a href='viewThread.php?view=$id'>Click Here</a> to view The page
                           </div>
                       
_DISPLAY;
                           
                       }
                       elseif($type == "Liked a comment on Thread Page")
                       {
                           
                           $comment = getCommentFromThread($details2);
                        echo <<<_DISPLAY
                           <div class='insideThreads'>
                           $member has liked the comment from the Thread page $details
                            <br> Comment: $comment
                            <br> <a href='viewThread.php?view=$id'>Click Here</a> to view The page
                           </div>
                       
_DISPLAY;
                           
                       }
                       elseif($type == "Created a Live Thread")
                       {
                       echo <<<_DISPLAY
                           <div class='insideThreads'>
                           $member has started a live Thread called $details
                            <br> <a href='viewLiveThread.php?id=$liveId'>Click Here</a> to view The page
                           </div>
                       
_DISPLAY;
                           
                       }
                       elseif($type == "Commented on Live Thread")
                       {
                       echo <<<_DISPLAY
                       
                       
_DISPLAY;
                           
                       }
                       
                   }
               }
               
           }
       }
   } // end function DisplayActivity 
   
   function getCommentFromThread($id) // Retrieve the comment from the table commentThread using its id
   {
       $result = queryMysql("SELECT * FROM commentThread WHERE id='$id'");
        if($result !="")
        {
           $row = $result->fetch_array(MYSQLI_ASSOC);
           $comment = $row['comment'];
           return $comment;
        }   
   } // end function getCommentFromThread
   
   function getLiveId($title)
   {
       $result = queryMysql("SELECT * FROM liveThread WHERE title='$title'");
       if($result->num_rows)
       {
           $row = $result->fetch_array(MYSQLI_ASSOC);
           $id = $row['id'];
           return $id;
       }
         
   }
   
   function getIdWithThreadTitle($title) // Retrieve the Id from the table Thread using the title of the Thread
   {
       $result = queryMysql("SELECT * FROM Thread WHERE title='$title'");
       if($result->num_rows)
       {
           $row = $result->fetch_array(MYSQLI_ASSOC);
           $id = $row['thread_id'];
           return $id;
       }
   } // end function getIdWithThreadTitle
   
    function showAllCommentsLive($title, $convo_id) // Called from the page viewThread.php
    {                                           // Used to display all comments from a thread 
        $result = queryMysql("SELECT * FROM liveCommentThread WHERE title='$title'");
                                // Retrieving all comments from the thread. Using title as a filter
        if ($result->num_rows == 0)
        {
            $error = "<span class='error'>No Comments</span><br><br>";
        }
        elseif($result->num_rows)
        {
            echo <<<_B
_B;
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
                    <textarea type="text" readonly="readonly" cols='79' rows='3'>$comment</textarea> </div>  
            <div class='individualUser'>  <span id='liveDetails'>     Rate: $rate </span>
             <br>   <span id='liveDetails'>  Posted by $user </span></div>
                        <div class='individualUser'>
                <form method='post' action='viewLiveThread.php?id=$convo_id'>
                    <input type='hidden' name='increaseEach' value='$cid'>
                    <input type='submit' id="hitlike" value='Like'>
                </form> </div>
                        <div class='individualUser'>
                <form method='post' action='viewLiveThread.php?id=$convo_id'>
                    <input type='hidden' name='decreaseEach' value='$cid'> 
                    <input type='submit' id="hitDislike" value='Dislike'>
                </form>    
                    </div>
                </div>
_A;
            }
        }
    } // end function showAllComments
    
    function displaySearch($useTitle)
    {
        /// Works similar as the function showAllThreadsUse, but displays only one category 
        $pages_query = queryMysql("SELECT thread_id FROM Thread WHERE title LIKE '%$useTitle%' OR user LIKE '%$useTitle%'");
        $row = $pages_query->fetch_array(MYSQLI_ASSOC);
        $counter =0;
        if ($pages_query->num_rows == 0)
        {           
        }
        elseif($pages_query->num_rows)
        {
            while($row = $pages_query->fetch_array(MYSQLI_ASSOC))
            {
                $counter++;
            }
        }        
        $per_page=5;
        $pages = ceil($counter / $per_page);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $start = ($page-1) * $per_page;
        
        $result = queryMysql("SELECT * FROM Thread where title LIKE '%$useTitle%' OR user LIKE '%$useTitle%' LIMIT $start,$per_page");      
        if($result->num_rows)
        {
            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {   
                $thre_id = $row['thread_id']; 
                $theTitle = $row['title'];
                $theViews = $row['views'];
                $theRatings = $row['rate'];
                $theUser = $row['user'];
                $category1 = $row['category'];
                $comment = $row['comment'];
                echo <<<_A
                    <form method='post' action='viewThread.php?view=$thre_id'>
                        <div class='insideThreads'>
                            <b>Title:  $theTitle</b>  </a><br><br>
                       Views: $theViews  Rating: $theRatings  Category: $category1
                        <input type='submit' class='inputbutton' value='View'>
                        <br>
                          Created by: $theUser     
                            <h5 id='comment'>Comment:</h5>
                            <p class='homeComment'>$comment</p>
                        <input type=hidden maxlength='20' name='id' value='$thre_id'>
                        </div>
                    </form>     
_A;
            }
        }
          
        $prev = $page - 1;
        $next = $page + 1;
        echo "<div class='homePage'>";
        // Going to send to the url both page number and the category chosen
        if(!($page<=1))
        {    
            echo '<a href="?page='.$prev.'&title='.$useTitle.'">Prev</a> ';       
        }
        if($pages>=1)
        {
            for($i=1; $i<=$pages; $i++)
            {
                echo ($i==$page) ? ' <b><a href="?page='.$i.'&title='.$useTitle.'">'.$i.'</a></b> ' : ' <a href="?page='.$i.'&title='.$useTitle.'">'.$i.'</a> ';
            }
        }
        if(!($page>=$pages))
        {
        echo ' <a href="?page='.$next.'&title='.$useTitle.'">Next</a> ';   
        }
        echo "</div>";
    }
   
   
   
?>

