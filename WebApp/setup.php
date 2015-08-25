<!DOCTYPE html>
<html>
    <head>
        <title> Setting up the database</title>
    </head>
    <body>
        
        <h4> Setting up... </h4>
        
<?php 
    require_once 'functions.php'; // so it connects to mysql. function to create table is there.

//    queryMysql("DROP TABLE profile");
//    queryMysql("DROP TABLE members");
//    queryMysql("DROP TABLE ");
	//queryMysql("DROP TABLE category");
//	queryMysql("DROP TABLE chat");
//	queryMysql("DROP TABLE commentThread");
	//queryMysql("DROP TABLE friends");
//	queryMysql("DROP TABLE groups");
//	queryMysql("DROP TABLE individual_group");
//	queryMysql("DROP TABLE likeCommentThread");
//	queryMysql("DROP TABLE likeThread");
//	queryMysql("DROP TABLE liveThread");
//	queryMysql("DROP TABLE members");
//	queryMysql("DROP TABLE message");
//	queryMysql("DROP TABLE overChat");
//	queryMysql("DROP TABLE pic");
	queryMysql("DROP TABLE profile");
//	queryMysql("DROP TABLE Thread");
	//queryMysql("DROP TABLE liveCommentThread");
	//queryMysql("DROP TABLE liveLikeComment");
	//queryMysql("DROP TABLE activity");
	//queryMysql("DROP TABLE groupThreads");

    createTheTable('profile', 
            'user varchar(30) DEFAULT NULL,
                details varchar(1000) DEFAULT NULL,
              member_id int(10),
                thread_id int(11) NOT NULL,
		thread_id2 int(11) NOT NULL,
		thread_id3 int(11) NOT NULL,
                INDEX(user(6))');

    
    createTheTable('category', 
                    'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,'
            . 'type varchar(30) NOT NULL,'
            . 'title varchar(50) NOT NULL,'
            . 'INDEX(title(6))');
    
    createTheTable('chat', 
            'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user varchar(30) NOT NULL,
            message text NOT NULL,
            title varchar(250) NOT NULL');
    
    createTheTable('commentThread', 
            'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title varchar(50) NOT NULL,
            comment text NOT NULL,
            rate int(11) NOT NULL,
            user varchar(30) NOT NULL');
    
    createTheTable('friends', 
            'user varchar(20) NOT NULL,
            friend varchar(20) NOT NULL');
    
    createTheTable('groups', 
            'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title varchar(250) NOT NULL,
            user varchar(250) NOT NULL,
            INDEX(title(6))');
    
    createTheTable('individual_group', 
            'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title varchar(250) NOT NULL,
            user varchar(50) NOT NULL');
    
    createTheTable('likeCommentThread', 
            'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  commentThread_id int(11) NOT NULL,
  user varchar(30) NOT NULL,
  rate int(11) NOT NULL,
  INDEX(user(6))');
    
    createTheTable('likeThread', 
            'Title varchar(50) NOT NULL,
                user varchar(30) NOT NULL,
              id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                rate int(11) NOT NULL');
    
    createTheTable('liveThread', 
            'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title varchar(50) NOT NULL,
            time_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            duration int(11) NOT NULL,
            comment varchar(1000) NOT NULL,
            INDEX(title(6))');
    
    createTheTable('members', 
                    'user_id int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        user varchar(20) DEFAULT NULL,
                        password varchar(20) DEFAULT NULL,
                        email varchar(20) DEFAULT NULL,
                        score int(11) NOT NULL,
                        INDEX(user(6))');
    
    createTheTable('message', 
                    'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    creater varchar(30) NOT NULL,
                    sendto varchar(30) NOT NULL,
                    themessage varchar(1000) NOT NULL,
                    readMessage varchar(100) NOT NULL');
    
    createTheTable('overChat', 
            '`id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `title` varchar(250) NOT NULL,
            `type` varchar(20) NOT NULL,
            `user` varchar(100) NOT NULL,
            INDEX(title(6))');

    createTheTable('pic', 
            'pic_id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user varchar(16) DEFAULT NULL,
            picture varchar(30) DEFAULT NULL,
            resource varchar(30) DEFAULT NULL,
            text varchar(100) DEFAULT NULL');
    
    
    createTheTable('Thread', 
            'thread_id int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title varchar(50) NOT NULL,
            comment text NOT NULL,
            views int(11) NOT NULL,
            rate int(11) NOT NULL,
            user varchar(30) NOT NULL,
            category varchar(30) NOT NULL,
            INDEX(title(6))');
    
    createTheTable('liveCommentThread', 
            'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title varchar(450) NOT NULL,
            comment varchar(1000) NOT NULL,
            rate int(11) NOT NULL,
            user varchar(30) NOT NULL');
    
    createTheTable('liveLikeComment', 
            '`id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `comment_id` int(11) NOT NULL,
            `user` varchar(30) NOT NULL,
            `rate` int(11) NOT NULL');
    
    createTheTable('activity', 
            '`id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `member` varchar(30) NOT NULL,
            `type` varchar(1000) NOT NULL,
            `details1` varchar(1200) NOT NULL,
            `details2` varchar(1200) NOT NULL');
    
    createTheTable('groupThreads', 
            'id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            group_title varchar(250) NOT NULL,
            thread_title varchar(250) NOT NULL,
            category varchar(50) NOT NULL,
            thread_id int(11) NOT NULL,
            views int(11) NOT NULL,
            rate int(11) NOT NULL,
            comment varchar(250) NOT NULL');
   // createTheTable('members',
//              'user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//               user VARCHAR(20),
//               password VARCHAR(20),
//               email VARCHAR(20),
//               INDEX(user(6))');
//   // echo "Reached here";
//    
//    createTheTable('profile',
//              'user VARCHAR(20),
//              details TEXT,
//              user_id INT UNSIGNED,
//              INDEX(user(6))'); 
//        //      FOREGIN KEY (user_id) REFERENCES members (user_id)');
              

?>
        <br> Done..    
    </body>
    
</html>


