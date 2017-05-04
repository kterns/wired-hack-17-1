<?php
session_start();
// include_once("includes/class.auth.php");

// $auth = new auth();

logout();  //Do the logout
header("Location: login.php"); //Send them to login page
exit();


/*
* Logs the user out by destroying their session, unsetting all array keys and setting user to Anonymous.
*/
function logout()
{
    session_destroy();
    // $_SESSION = array(); //Unset all session vars now
    // return true;
}