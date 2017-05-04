<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
        header("Location: login.php");
        exit();
    }
    
    include_once("includes/class.userInfo.php");
    
    $userInfo = new userInfo();
    $infoRow = $userInfo->getUserInfo();
    
    if($infoRow['isAdmin'] != 1) {
        header("Location: login.php");
        exit();
    }
    
    require("includes/header.php");
    require("includes/nav.php");
    
    // include_once("includes/class.userInfo.php");
    
    // $userInfo = new userInfo();
    // $infoRow = $userInfo->getUserInfo();
    
    // $users = $userInfo->getUsers();
    
    include_once("includes/class.admin.php");
    
    $admin = new admin();
    $users = $admin->getUsers();
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(array_key_exists("name", $_POST)) {
            $admin->addService($_POST['name']);
        }
    }
?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form method="post">
                    <h2>Add Service</h2>
                    <label for="inputService" class="sr-only">Service</label>
                    <input type="text" id="inputService" class="form-control" name="name" placeholder="Service Name" required autofocus>
                    <button class="btn btn-primary" type="submit">Add Service</button>
                </form>
            </div>
            <div class="col-sm-12">
                <h2>Users</h2>
                <ul class="well-list">
                <?php
                    foreach ($users as $user) {
                        echo '<li class="well"><p>'.$user['firstName'] . " " . $user['lastName'].'<button class="btn btn-primary edit">Block</button></p></li>';
                    }
                ?>
                </ul>
            </div>
        </div>
    </div>

<?php require("includes/footer.php"); ?>