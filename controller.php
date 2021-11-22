<?php
if (empty($_POST['page'])) {  // When no page is sent from the client; The initial display
                                // You may use if (!isset($_POST['page'])) instead of empty(...).
    $display_type = 'none';  // This variable will be used in 'view_startpage.php'.
                              // It will display the start page without any box, i.e., no SignIn box, no Join box, ...
    $error_message_username = "";
    $error_message_password = "";
    include ('startpage.php');
    exit();
}


require('model.php');  // This file includes some routines to use DB.

session_start();

// When commands come from StartPage
if ($_POST['page'] == 'StartPage')
{
    $command = $_POST['command'];
    switch($command) {  // When a command is sent from the client
        case 'SignIn':  // With username and password
            // if (there is an error in username and password) {
            if (!check_validity($_POST['username'], $_POST['password'])) {
                $result = "Wrong username or password. Try again.";
                $display_type = 'signin'; 
                include('startpage.php');
            } 
            else {
                $_SESSION['SignIn'] = 'Yes';
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['email'] = get_user_email($_POST['username']);
                $_SESSION['biography'] = get_user_bio($_POST['username']);
                $_SESSION['followers'] = get_followers($_POST['username']);
                $_SESSION['following'] = get_following($_POST['username']);

                include('mainpage.php');
            }
            exit();

        case 'SignUp':  
            
            if (check_username_existence($_POST['username'])) {
                $result = 'Username already exists';
                $display_type = 'signup';  
                include('startpage.php');
            } 

            else if (check_email_existence($_POST['email'])) {
                $result = 'Email already exists';
                $display_type = 'signup';  
                include('startpage.php');
            } 

            else if (!filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $result = 'Invalid Email';
                $display_type = 'signup';  
                include('startpage.php');
            }

            else if (join_a_user($_POST['username'], $_POST['password'], $_POST['email'])) {
                $result = 'Accounted created';
                $display_type = 'signin';
                include('startpage.php');
            } 

            else {
                $result = 'Something went wrong';
                $display_type = 'signup';  
                include('startpage.php');
            }
            exit();
        //...
    }
}

else if ($_POST['page'] == 'MainPage') 
{
    if (!isset($_SESSION['SignIn'])) {
        $display_type = 'none';
        include('startpage.php');
        exit();
    }

    $command = $_POST['command'];
    switch($command) {  
        case 'PostATweet':
            $result = post_a_tweet($_POST['tweet'], $_SESSION['username']);

            if ($result) {
                $result = "Tweet Posted!";
            } else {
                $result = "Tweet not Posted!";
            }
            echo json_encode($result);
            break;

        case 'SearchTweet':
            $data = search_tweet($_SESSION['username'], $_POST['searchTweet']);
            echo json_encode($data);
            break;
         
        case 'ShowTweets':
            $data = show_tweets($_SESSION['username']);   
            echo json_encode($data);
            break;

        case 'ShowUsers':
            $data = show_users($_SESSION['username']);   
            echo json_encode($data);
            break;

        case 'FollowUser':
            if (check_follow($_SESSION['username'], $_POST['follow'])) {
                unfollow_user($_SESSION['username'], $_POST['follow']);
                $result = "User unfollowed";
                echo json_encode($result);
                break;

            } else {
                follow_user($_SESSION['username'], $_POST['follow']); 
                $result = "User Followed";
                echo json_encode($result);
                break;
            }
            
        
        case 'LikePost':
            if (check_like($_SESSION['username'], $_POST['like'])) {
                unlike_post($_SESSION['username'], $_POST['like']);
                $result = "Post Unliked";
                echo json_encode($result);
                break;

            } else {
                like_post($_SESSION['username'], $_POST['like']);
                $result = "Post Liked";
                echo json_encode($result);
                break;
            }

        case 'UserButton':
            include ('profile.php');
            break;

        case 'HomeButton':  
            include ('mainpage.php');
            break;

        case 'ProfileButton':
            include ('profile.php');
            break;

        case 'SignOut':  
            session_unset();
            session_destroy();  
            $display_type = 'none';
            include ('startpage.php');
            break;
    }
}


else if ($_POST['page'] == 'Profile') 
{
    if (!isset($_SESSION['SignIn'])) {
        $display_type = 'none';
        include('startpage.php');
        exit();
    }

    $command = $_POST['command'];
    switch($command) {  
        case 'ShowUserTweets':
            $data = show_user_tweets($_SESSION['username']);   
            echo json_encode($data);
            break;

        case 'PostATweet':
            $result = post_a_tweet($_POST['tweet'], $_SESSION['username']);

            if ($result) {
                $result = "Tweet Posted";
            } else {
                $result = "Tweet not Posted";
            }
            echo json_encode($result);
            break;  

        case 'DeleteTweet': 
            $result = delete_tweet($_POST['delete']);

            if ($result) {
                $result = "Tweet Deleted";
            } else {
                $result = "Tweet not Deleted";
            }
            echo json_encode($result);
            break;  
        
        case 'ChangeProfile':
            if (empty($_POST['username'])) {
                $name = $_SESSION['username'];

            } else if (check_username_existence($_POST['username'])) {
                $name = $_SESSION['username'];
                $result = "Name already taken";
                echo json_encode($result);
            
            } else {
                $name = $_POST['username'];
            }
            
            if (empty($_POST['email'])) {
                $email = $_SESSION['email'];

            } else if (check_email_existence($_POST['email'])) {
                $email = $_SESSION['email'];
                $result = "Email already taken";
                echo json_encode($result);

            } else if (!filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $email = $_SESSION['email'];
                $result = 'Invalid Email';
                echo json_encode($result);

            }else {
                $email = $_POST['email'];
            }
            
            if (empty($_POST['userBio']) ) {
                $bio = $_SESSION['biography'];
            } else {
                $bio = $_POST['userBio'];
            }

            $result = change_profile($_SESSION['username'], $name, $_POST['password'], $email, $bio);
            $_SESSION['username'] = $name;
            $_SESSION['email'] = get_user_email($name);
            $_SESSION['biography'] = get_user_bio($name);
            

            $result = "Profile Changed";
            echo json_encode($result);
            break;

        case 'Unsubscribe':  
            $result = unsubscribe($_SESSION['username']);
            
            if ($result) {
                $result = "Profile Deleted";
            } else {
                $result = "Profile not Deleted";
            }
            session_unset();
            session_destroy();  
            $display_type = 'none';
            include ('startpage.php');
            break;
            
        case 'HomeButton':  
            include ('mainpage.php');
            break;
    
        case 'ProfileButton':
            include ('profile.php');
            break;
    
        case 'SignOut':  
            session_unset();
            session_destroy(); 
            $display_type = 'none';
            include ('startpage.php');
            break;
    }
}