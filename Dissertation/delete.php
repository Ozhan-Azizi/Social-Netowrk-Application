<?php

    require_once 'all.php';

    if(!$loggedin)
    {
        die("You are not logged in");
    }

 if(isset($_GET['delete']))
  {
      $user1 = $_SESSION['user'];
      $qu = "DELETE FROM MEMBERS WHERE user ='$user1'";
      $qu2 = "DELETE FROM FRIENDS where friend ='$user1'";
      $qu3 = "delete from profiles where user='$user1'";
      $qu4 = "delete from messages where auth='$user1'";
      $resultDel = queryMysql($qu);
      $resultDel2 = queryMysql($qu2);
      $resultDel3 = queryMysql($qu3);
      $resultDel4 = queryMysql($qu4);
      if(!$resultDel || (!$resultDel2)||(!$resultDel3)||(!$resultDel4))
      {
          echo "did not work";
      }     
  }
