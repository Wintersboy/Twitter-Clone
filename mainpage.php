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

.profile-btn {
    font-size: 30px;
}

.loadBtn {
    margin-left: 75px;
    margin-bottom: 20px;
}

.userHeading {
    margin-left: 75px;
}

.fgfollowers p {
    margin-left: 40px;
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
        <form method="post" id="search-tweet" action="controller.php">
            <input type='hidden' name='page' value='MainPage'>
            <input type='hidden' name='command' value='SearchTweet'>
            <input class="form-control mr-sm-2" type="text" id="searchTweet" name="searchTweet" placeholder="Search">
        </form>
        <button class="btn btn-sm btn-primary" type="button" id="search-submit">Search</button>
    </ul>    
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <button class="btn btn-link" type="button" id="profileButton">Profile</button>
        </li>
        <li class="nav-item">
            <button class='btn btn-link' id='sign-out'>Sign Out</button>
        </li>
    </ul>
</nav>

<div class="container">
    <div class="row justify-content-md-center">   
        <div class='col-md-3 text-center'>
            </br><button class='btn btn-md btn-primary' id='postTweet' data-toggle='modal' data-target='#modal-post-tweet'>Post A Tweet</button>
        </div>
    </div>
    </br>
    <div class="row justify-content-lg-center" >
        <div class='col-md-9' id="pane-result">
        <?php
            if (!empty($result)) {
                echo $result;
            } else {

            }       
        ?>
        </div>
        <div class='col-md-3' id="user-result">
        <?php
        
            if (!empty($result)) {
                echo $result;
            } else {

            }       
        ?>
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


<!-- Forms -->

      <!-- Follow Button form -->
    <form method='post' action='controller.php' id='form-follow-btn' style='display:none'>
            <input type='hidden' name='page' value='MainPage'>
            <input type='hidden' name='command' value='FollowUser'>
    </form>

     <!-- Like Button form -->
    <form method='post' action='controller.php' id='form-like-btn' style='display:none'>
            <input type='hidden' name='page' value='MainPage'>
            <input type='hidden' name='command' value='LikeUser'>
    </form>       

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
    <!-- UserButton form -->
    <form method='post' action='controller.php' id='form-user-button' style='display:none'>
            <input type='hidden' name='page' value='MainPage'>
            <input type='hidden' name='command' value='UserButton'>
    </form>
</body>
</html>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

    $('#sign-out').click(function() {
        $('#form-sign-out').submit();
    })

    $('#profileButton').click(function() {
        $('#form-profile-button').submit();
    })

    $('.userButton').click(function() {
        $('#form-user-button').submit();
    })

    $('#homeButton').click(function() {
        $('#form-home-button').submit();
    })

    $('#post-submit').click(post_tweet);
    function post_tweet() {
        var url = "controller.php";
        var query = { page: 'MainPage', command: 'PostATweet', tweet: $('#tweet').val()};
        $.post(url, query, 
            function(data) {  
                var result = JSON.parse(data);
                $('#pane-result').html(result);  
            });
    }

    $('#search-submit').click(search_tweet);  
    function search_tweet() {
        var url = "controller.php";
        var query = { page: 'MainPage', command: 'SearchTweet', searchTweet: $('#searchTweet').val()};
        $.post(url, query, 
            function(data) {  
                var rows = JSON.parse(data);
                    if(rows.length > 0) {
                        var t = "<div class='col'>";
                        t += "<h2>Tweets</h2>";
                        t += "<table class='table table-borderless'>";
                        for (var i = 0; i < rows.length; i++) {
                            t += "<tr><td>";  
                            t += "<div class='modal-content'>";
                            t += "<div class='modal-header'>";
                            t += "<h2 class='modal-title'>" + rows[i]['Username'] + "</h2></div>";
                            t += "<div class='modal-body'>";
                            t += "<div class='form-group'>" + rows[i]['Tweet'] + "</div></div>";
                            t += "<div class='modal-footer justify-content-between'>";
                            t += "<div class='form-group'>" + "<p style='font-size: 20px;'>Likes: " + rows[i]['NumLikes'] + " " + "<input type='button' id=" + rows[i]['Id'] + " value=" + rows[i]['Status'] + " class='likeBtn btn btn-primary'></input></p></div>";
                            t += "<div class='form-group'>" + rows[i]['Date'] + "</div></div>";
                            t += "</div></div></td></tr>";
                        }
                        t += '</table>';
                        $('#pane-result').html(t); 
                    }
                    
                    $('.likeBtn').click(function() {
                        $likeId = this.id;
                            like_post();
                    })
            });
    }
            
    
    $(document).ready(show_tweets);  
    function show_tweets() {
        var url = "controller.php";
        var query = { page: 'MainPage', command: 'ShowTweets'};
        $.post(url, query, 
            function(data) {  
                var rows = JSON.parse(data);
                    if(rows.length > 0) {
                        var t = "<div class='col'>";
                        t += "<h2>Tweets</h2>";
                        t += "<table class='table table-sm table-borderless'>";
                        for (var i = 0; i < rows.length; i++) {
                            t += "<tr><td>"  
                            t += "<div class='modal-content'>";
                            t += "<div class='modal-header '>";
                            t += "<h2 class='modal-title'>" + rows[i]['Username'] + "</h2></div>";
                            t += "<div class='modal-body'>";
                            t += "<div class='form-group'>" + rows[i]['Tweet'] + "</div></div>";
                            t += "<div class='modal-footer justify-content-between'>";
                            t += "<div class='form-group'>" + "<p style='font-size: 20px;'>Likes: " + rows[i]['NumLikes'] + " " + "<input type='button' id=" + rows[i]['Id'] + " value=" + rows[i]['Status'] + " class='likeBtn btn btn-primary'></input></p></div>";
                            t += "<div class='form-group'>" + rows[i]['Date'] + "</div></div>";
                            t += "</div></div></td></tr>";
                        }
                        t += '</table></div>';
                        $('#pane-result').html(t); 
                    }

                    $('.likeBtn').click(function() {
                        $likeId = this.id;
                            like_post();
                    })
            });
    }

    $(document).ready(show_users);  
    function show_users() {
        var url = "controller.php";
        var query = { page: 'MainPage', command: 'ShowUsers'};
        $.post(url, query, 
            function(data) {  
                var rows = JSON.parse(data);
                    if(rows.length > 0) {
                        var t = "<div class='col'>";
                        t += "<table class='table table-sm table-borderless'>";
                        t += "<div class='userHeading'><h2>Users</h2></div>";
                        for (var i = 0; i < rows.length; i++) {
                            t += "<tr><td>"  
                            t += "<div class='modal-content'>";
                            t += "<div class='modal-header justify-content-center'>";
                            t += "<h2 class='modal-title'>" + "<button class='userButton btn profile-btn' id=" + rows[i]['Id'] +">" + rows[i]['Username'] + "</button></h2></div>";
                            t += "<div class='modal-body'>";
                            t += "<div class='form-group fgfollowers'>" + "<p style='font-size: 20px;'>Followers: " + rows[i]['NumFol'] + " " + "</p></div></div>";
                            t += "<div class='modal-footer justify-content-center'>";
                            t += "<div class='form-group'>" + "<input type='button' id=" + rows[i]['Id'] + " value=" + rows[i]['Status'] + " class='followBtn btn btn-primary'></input>" + "</div>";
                            t += "</div></div></td></tr>";
                        }
                        t += '</table></div>';
                        t += "<div>" + "<input type='button' value='Load More' class='loadBtn btn btn-primary'></input>" + "</div>";

                        $('#user-result').html(t); 
                    
                    }   

                    $('.followBtn').click(function() {
                        $followId = this.id;
                            follow_user();
                    })

                    $('.loadBtn').click(function() {
                        show_users();
                    })
            });
    }

    function follow_user() {
        var url = "controller.php";
        var query = { page: 'MainPage', command: 'FollowUser', follow: $followId};
        $.post(url, query, 
            function(data) {  
                var result = JSON.parse(data);
                alert(result);
            });
    }     

    function like_post() {
        var url = "controller.php";
        var query = { page: 'MainPage', command: 'LikePost', like: $likeId};
        $.post(url, query, 
            function(data) {  
                var result = JSON.parse(data);
                alert(result);
            });
    } 
    
    $('#searchTweet').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
    
</script>