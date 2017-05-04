<?php
include_once("includes/class.auth.php");


if(array_key_exists("username", $_POST) && array_key_exists("password", $_POST))  //See if they posted a username and password
{
    $auth = new auth();
    $success = $auth->login($_POST["username"], $_POST["password"]);
    if($success)  //Successfully logged in
    {
        if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
            //Redirect to admin page
            header("Location: admin.php");
            exit();
        }
        else
        {
            //Redirect to messages
            header("Location: messages.php");
            exit();
        }
    }
    else
    {
        $error = true;  //User of pass is invalid
    }
}
else
{
    $error = false; //No error because no login attempted.
}

?>

<?php 
    require("includes/header.php");
    require("includes/nav.php");
?>

    <div class="row">
        <div class="container">
          <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Please sign in</h2>
            <label for="inputEmail" class="sr-only">Username</label>
            <input type="email" id="inputEmail" class="form-control" name="username" placeholder="E-mail address" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me"> Remember me
              </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            <a href="create-account.php" class="btn btn-lg btn-info btn-block" role="button">Create new account</a>
          </form>
    
        </div>
    </div>

<?php require("includes/footer.php"); ?>


