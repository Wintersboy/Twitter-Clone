<?php
$conn = mysqli_connect('localhost', 'awintersf20', 'awintersf20424', 'C354_awintersf20');

function check_validity($u, $p) 
{
    global $conn;
    
    $sql = "select * from Users where Username = '$u' and Password = '$p'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

function check_username_existence($u) 
{
    global $conn;
    
    $sql = "select * from Users where Username = '$u'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

function check_email_existence($email) 
{
    global $conn;
    
    $sql = "select * from Users where Email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

function join_a_user($u, $p, $email) 
{
    global $conn;
    
    $date = date("Ymd");
    
    $sql = "INSERT INTO Users VALUES (NULL, '$u', '$p', '$email', ' ', $date)";
    $result = mysqli_query($conn, $sql);
    
    return $result;
}

function get_user_id($u) 
{
    global $conn;
    
    $sql = "select * from Users where Username = '$u'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['Id'];
    } else
        return -1;
}

function get_likes($postId)
{
    global $conn;
    
    $sql = "SELECT PostId FROM Likes WHERE PostId = $postId";
    $result = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($result);

    return $num_rows;
}

function get_followers($user)
{
    global $conn;
    
    $uid = get_user_id($user);
    $sql = "SELECT UserId FROM Followers WHERE UserId = $uid";
    $result = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($result);

    return $num_rows;
}

function get_following($user)
{
    global $conn;
    
    $uid = get_user_id($user);
    $sql = "SELECT FollowId FROM Followers WHERE FollowId = $uid";
    $result = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($result);

    return $num_rows;
}

function post_a_tweet($t, $u) 
{
    global $conn;
    
    $uid = get_user_id($u);
    $current_date = date("Ymd");
    $sql = "insert into Posts values(NULL, '$t', $uid, $current_date)";
    $result = mysqli_query($conn, $sql);
    if ($result)
        return true;
    else
        return false;
}

function search_tweet($user, $term) 
{
    global $conn;
    
    $sql = "select * from Posts where Tweet like '%$term%' order by Date DESC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    $i = 0;
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        $data[$i]['Username'] = get_user_name($data[$i]['UserId']);
        $data[$i]['NumLikes'] = get_likes($data[$i]['Id']);

        if (!check_like($user, $data[$i]['Id']))
            $data[$i]['Status'] = "Like";

        else 
            $data[$i]['Status'] = "Unlike";
        $i++;
    }
    
    return $data;
}

function show_tweets($user) 
{
    global $conn;
    
    $uid = get_user_id($user);
    $sql = "SELECT Posts.Id, Posts.Tweet, Posts.UserId, Posts.Date FROM Posts INNER JOIN Followers ON Posts.UserId=Followers.UserId WHERE Followers.FollowId = $uid order by Date DESC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    $i = 0;

    while($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;
        $data[$i]['Username'] = get_user_name($data[$i]['UserId']);
        $data[$i]['NumLikes'] = get_likes($data[$i]['Id']);

        if (!check_like($user, $data[$i]['Id']))
            $data[$i]['Status'] = "Like";

        else 
            $data[$i]['Status'] = "Unlike";

        $i++;
    }
    
    return $data;
}

function show_users($user) 
{
    global $conn;
    
    $sql = "SELECT * FROM Users WHERE Username NOT IN ('$user') ORDER BY RAND() LIMIT 5";    
    $result = mysqli_query($conn, $sql);
    $data = [];
    $uid = get_user_id($user);
    $i = 0;
    while($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;
        $username = get_user_name($data[$i]['Id']);
        $data[$i]['NumFol'] = get_followers($username);

        if (!check_follow($user, $data[$i]['Id']))
            $data[$i]['Status'] = "Follow";

        else 
            $data[$i]['Status'] = "Unfollow";

        $i++;
    }
    
    return $data;
}

function show_user_tweets($u) 
{
    global $conn;
    
    $uid = get_user_id($u);
    $sql = "select * from Posts where UserId = $uid order by Date DESC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    $i = 0;

    while($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;
        $data[$i]['Username'] = get_user_name($data[$i]['UserId']);
        $data[$i]['NumLikes'] = get_likes($data[$i]['Id']);

        if (!check_like($u, $data[$i]['Id']))
            $data[$i]['Status'] = "Like";

        else 
            $data[$i]['Status'] = "Unlike";
        $i++;
    }
    
    return $data;
}

function get_user_name($uid)
{
    global $conn;
    
    $sql = "select * from Users where Id = $uid";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0)
        return "";
    else {
        $row = mysqli_fetch_assoc($result);
        return($row['Username']);
    }
}

function get_user_email($u) 
{
    global $conn;

    $sql = "select Email from Users where Username = '$u'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0)
        return "";
    else {
        $row = mysqli_fetch_assoc($result);
        return($row['Email']);
    }
}

function get_user_bio($u)
{
    global $conn;
    
    $sql = "select Biography from Users where Username = '$u'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0)
        return "";
    else {
        $row = mysqli_fetch_assoc($result);
        return($row['Biography']);
    }
}

function change_profile($user, $u, $p, $em, $bio) 
{
    global $conn;

    $uid = get_user_id($user);
    $result;

    $password = trim(isset($_POST['password']) ? $_POST['password'] : '');
    $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $biography = trim(isset($_POST['userBio']) ? $_POST['userBio'] : '');
    $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
    

    if($username)
    {
        $sql = "update Users set Username = '$u' where Id = $uid";
        $result += mysqli_query($conn, $sql);
    }

    if($password)
    {
        $sql = "update Users set Password = '$p' where Id = $uid";
        $result += mysqli_query($conn, $sql);
    }

    if($email)
    {
        $sql = "update Users set Email = '$em' where Id = $uid";
        $result += mysqli_query($conn, $sql);
    }
    

    if($biography)
    {
        $sql = "update Users set Biography ='$bio' where Id = $uid";
        $result += mysqli_query($conn, $sql);
    }

    if ($result)
        return true;
    else
        return false;
}

function follow_user($user, $followId) {
    global $conn;
    
    $uid = get_user_id($user);
    $sql = "insert into Followers values(NULL, $uid, $followId)";
    $result = mysqli_query($conn, $sql);
     
    if ($result)
        return true;
    else
        return false;
}

function unfollow_user($user, $followId) 
{
    global $conn;

    $uid = get_user_id($user);
    $sql = "DELETE FROM Followers WHERE FollowId = $uid and UserId = $followId";
    $result = mysqli_query($conn, $sql);

    if ($result)
        return true;
    else
        return false;
}

function check_follow($user, $followId) 
{
    global $conn;
    
    $uid = get_user_id($user);
    $sql = "select * from Followers where FollowId = $uid and UserId = $followId";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

function like_post($user, $likeId) {
    global $conn;
    
    $uid = get_user_id($user);
    $sql = "insert into Likes values(NULL, $likeId, $uid)";
    $result = mysqli_query($conn, $sql);

    if ($result)
        return true;
    else
        return false;
}

function unlike_post($user, $likeId) 
{
    global $conn;

    $uid = get_user_id($user);
    $sql = "DELETE FROM Likes WHERE PostId = $likeId and UserId = $uid";
    $result = mysqli_query($conn, $sql);

    if ($result)
        return true;
    else
        return false;
}

function check_like($user, $likeId) 
{
    global $conn;
    
    $uid = get_user_id($user);
    $sql = "select * from Likes where UserId = $uid and PostId = $likeId";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

function delete_tweet($postId) 
{
    global $conn;

    $sql = "DELETE FROM Posts WHERE Id = $postId";
    $result = mysqli_query($conn, $sql);

    if ($result)
        return true;
    else
        return false;
}

function delete_user_tweets($user) 
{
    global $conn;

    $uid = get_user_id($user);
    $sql = "DELETE FROM Posts WHERE UserId = $uid";
    $result = mysqli_query($conn, $sql);

    if ($result)
        return true;
    else
        return false;
}

function unsubscribe($user) 
{
    global $conn;

    delete_user_tweets($user);

    $uid = get_user_id($user);
    $sql = "DELETE FROM Users WHERE Id = $uid";
    $result = mysqli_query($conn, $sql);

    if ($result) 
        return true;
    else
        return false;
}
?>   