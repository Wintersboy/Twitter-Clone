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
</style>
</head>

<body>

    <div class="jumbotron text-center">
        <div class="container" >
            <h1 style="font-size:60px;">Twitter</h1>
            <img src="images/twitter-logo.png" width="150px" height="150px">
            <h3>Sign In or Create an Account to get started.</h3>
        </div>
    </div>

    <div class='container'>
        <div class="row justify-content-md-center" id="pane-result">
            <?php
                if (!empty($result)) {
                    echo $result;
                } else {

                } 
            ?>
        </div>
        </br></br>
        <div class='row' id='pane-navigation'>
            <div class='col-md-3'></div>
            <div class='col-md-3 text-center'>
                <button class='btn btn-lg btn-primary' id='signin' data-toggle='modal' data-target='#modal-signin'>Sign In</button>
            </div>
            <div class='col-md-3 text-center'>
                <button class='btn btn-lg btn-primary' id='signup' data-toggle='modal' data-target='#modal-signup'>Sign Up</button>
            </div>
            <div class='col-md-3'></div>
        </div>
    </div>
    
    <!-- Modal Window for SignIn -->
    <div class='modal fade' id='modal-signin'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                    <div class='modal-header'>
                        <h2 class='modal-title'>Sign In</h2>
                    </div>
                    <div class='modal-body'>
                        <form id='form-signin' method='POST' action='controller.php'>
                            <input type='hidden' name='page' value='StartPage'>
                            <input type='hidden' name='command' value='SignIn'>
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name='username'>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name='password'>
                            </div>
                            <div class='modal-footer'>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Window for SignUp -->
    <div class='modal fade' id='modal-signup'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                    <div class='modal-header'>
                        <h2 class='modal-title'>Sign Up</h2>
                    </div>
                        <div class='modal-body'>
                        <form id='form-signin' method='POST' action='controller.php'>
                            <input type='hidden' name='page' value='StartPage'>
                            <input type='hidden' name='command' value='SignUp'>
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name='username' required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" id="email" name='email' required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name='password' required>
                            </div>
                            <div class='modal-footer'>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</body>
</html>

