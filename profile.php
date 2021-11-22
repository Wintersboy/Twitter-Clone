<?php
    if (!isset($_SESSION['SignIn'])) {
        include('startpage.php');
        exit();
    }
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>

body {
    background-color: rgb(245,248,250);
}

.jumbotron {
    background-color: rgb(225,232,237);
    border-bottom:1px solid black;
}

.navbar {
    border-bottom:1px solid black;
}


.modal-content {
    border:1px solid black;
}

</style>

</head>
<body>

<nav class="navbar sticky-top navbar-expand-sm bg-light">
    <span class="navbar-brand">
        <button class="btn btn-none" type="button">
            <img src="images/twitter-logo.png" id="homeButton" alt="Logo" style="width:40px;">
        </button>
    </span>
    <span class="navbar-text">
            <?php echo $_SESSION['username']; ?>
    </span>
    
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <button class="btn btn-link" type="button" id="profileButton">Profile</button>
        </li>
        <li class="nav-item">
            <button class='btn btn-link' id='sign-out'>Sign Out</button>
        </li>
    </ul>
</nav>

<div class="jumbotron text-center">
        <div class="container" >
            <h1><?php echo $_SESSION['username']; ?></h1></br>
            <img src="images/default-image.png" width="150px" height="150px"></br></br>
            <h2><?php echo $_SESSION['biography']; ?></h2>
            </br><h4>Following: <?php echo $_SESSION['following']; ?> &nbsp &nbsp Followers: <?php echo $_SESSION['followers']; ?></h4></br>

            <div class="row justify-content-md-center">   
                <div class='col-md-3 text-center'></div>
                <div class='col-md-2 text-center'>
                    </br><button class='btn btn-md btn-primary' id='postTweet' data-toggle='modal' data-target='#modal-post-tweet'>Post A Tweet</button>
                </div>
                <div class='col-md-2 text-center'>
                    </br><button class='btn btn-md btn-primary' id='editProfile' data-toggle='modal' data-target='#modal-edit-profile'>Edit Profile</button>
                </div>
                <div class='col-md-2 text-center'>
                    </br><button class='btn btn-md btn-primary' id='unsubscribe'>Unsubscribe</button>
                </div>
                <div class='col-md-3 text-center'></div>
            </div>
        </div>
</div>

<div class="container">
    <div class="row justify-content-md-center" id="pane-result">
        <?php
            if (!empty($result)) {
                echo $result;
            } else {    
                echo "</br><h3 style='text-align:center'>User has not made any posts.</h3>";
            }  
        ?>
    </div>

<!-- Modal Window for EditProfile -->
<div class='modal fade' id='modal-edit-profile'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                    <div class='modal-header'>
                        <h2 class='modal-title'>Edit Profile</h2>
                    </div>
                    <div class='modal-body'>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name='username' placeholder="<?php echo $_SESSION['username']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name='password' placeholder="*******">
                        </div>
                        <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" id="email" name='email' placeholder="<?php echo $_SESSION['email']; ?>">
                        </div>
                        <div class='form-group'>
                            <label for="tweet">Biography:</label> 
                            <textarea col="50" rows="5" class="form-control" id="userBio" name='userBio' placeholder="<?php echo $_SESSION['biography']; ?>"></textarea>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="form-group"> 
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" id="change-submit" class="btn btn-default" data-dismiss="modal">Submit</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>

<!-- Modal Window for PostATweet -->
<div class='modal fade' id='modal-post-tweet'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                    <div class='modal-header'>
                        <h2 class='modal-title'>Post A Tweet</h2>
                    </div>
                    <div class='modal-body'>
                        <input type='hidden' name='page' value='MainPage'>
                        <input type='hidden' name='command' value='PostATweet'>
                        <div class='form-group'>
                            <label class="control-label" for="tweet">Tweet:</label> 
                            <textarea col="50" rows="5" class="form-control" id="tweet" name='tweet' placeholder="Enter Tweet here..."></textarea>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="form-group"> 
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" id="post-submit" class="btn btn-default" data-dismiss="modal">Submit</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>


    <!-- SignOut form -->
    <form method='post' action='controller.php' id='form-sign-out' style='display:none'>
            <input type='hidden' name='page' value='MainPage'>
            <input type='hidden' name='command' value='SignOut'>
    </form>
    
    <!-- HomeButton form -->
    <form method='post' action='controller.php' id='form-home-button' style='display:none'>
            <input type='hidden' name='page' value='MainPage'>
            <input type='hidden' name='command' value='HomeButton'>
    </form>

    <!-- ProfileButton form -->
    <form method='post' action='controller.php' id='form-profile-button' style='display:none'>
            <input type='hidden' name='page' value='MainPage'>
            <input type='hidden' name='command' value='ProfileButton'>
    </form>

     <!-- EditProfile form -->
     <form method='post' action='controller.php' id='form-edit' style='display:none'>
            <input type='hidden' name='page' value='MainPage'>
            <input type='hidden' name='command' value='EditProfile'>
    </form>

     <!-- Unsubscribe form -->
     <form method='post' action='controller.php' id='form-delete-profile' style='display:none'>
            <input type='hidden' name='page' value='Profile'>
            <input type='hidden' name='command' value='Unsubscribe'>
    </form>

</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

    $('#unsubscribe').click(function() {
        if (confirm("Are you sure you want to delete your profile?")) {
            $('#form-delete-profile').submit();
        }
    })

    $('#sign-out').click(function() {
        $('#form-sign-out').submit();
    })

    $('#profileButton').click(function() {
        $('#form-profile-button').submit();
    })

    $('#homeButton').click(function() {
        $('#form-home-button').submit();
    })

    $(document).ready(show_user_tweets);  
        function show_user_tweets() {
            var url = "controller.php";
            var query = { page: 'Profile', command: 'ShowUserTweets'};
            $.post(url, query, 
                function(data) {
                    var rows = JSON.parse(data);
                    if(rows.length > 0) {
                        var t = "<table class='table table-borderless'>";
                        for (var i = 0; i < rows.length; i++) {
                            t += "<tr><td>"  
                            t += "<div class='modal-content'>";
                            t += "<div class='modal-header'>";
                            t += "<h2 class='modal-title'>" + rows[i]['Username'] + "</h2></div>";
                            t += "<div class='modal-body'>";
                            t += "<div class='form-group'>" + rows[i]['Tweet'] + "</div></div>";
                            t += "<div class='modal-footer justify-content-between'>";
                            t += "<div class='form-group'>" + "<p style='font-size: 20px;'>Likes: " + rows[i]['NumLikes'] + " " + "<input type='button' id=" + rows[i]['Id'] + " value='Delete' class='deleteBtn btn btn-danger'></input>" + "</div>";
                            t += "<div class='form-group'>" + rows[i]['Date'] + "</div></div>";
                            t += "</div></div></td></tr>";
                        }
                        t += '</table>';
                        $('#pane-result').html(t); 
                    }

                    $('.deleteBtn').click(function() {
                        $btnId = this.id;
                        if (confirm("Are you sure you want to delete this post?")) {
                            delete_tweet();
                        }
                    })
                });
    }

    function delete_tweet() {
        var url = "controller.php";
        var query = { page: 'Profile', command: 'DeleteTweet', delete: $btnId};
        $.post(url, query, 
            function(data) {  
                var result = JSON.parse(data);
                $('#pane-result').html(result);  
            });
    }     

    $('#post-submit').click(post_tweet);
    function post_tweet() {
        var url = "controller.php";
        var query = { page: 'Profile', command: 'PostATweet', tweet: $('#tweet').val()};
        $.post(url, query, 
            function(data) {  
                var result = JSON.parse(data);
                $('#pane-result').html(result);  
            });
    }

    $('#change-submit').click(change_profile);
    function change_profile() {
        var url = "controller.php";
        var query = { page: 'Profile', command: 'ChangeProfile', username: $('#username').val(), password: $('#password').val(), email: $('#email').val(), userBio: $('#userBio').val()};
        $.post(url, query, 
            function(data) {  
                var result = JSON.parse(data);
                $('#pane-result').html(result);  
            });
    }

</script>