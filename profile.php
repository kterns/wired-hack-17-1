<?php

    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
        header("Location: login.php");
        exit();
    }
    
    include_once("includes/class.userInfo.php");
    
    $userInfo = new userInfo();
    $infoRow = $userInfo->getUserInfo();
    
    if($infoRow['isAdmin'] == 1) {
        header("Location: admin.php");
        exit();
    }
    
    require("includes/header.php");
    require("includes/nav.php");
?>

    <div class="container">
        <div class="row" id="main-page">
            <div class="col-sm-3 text-center side-section">
                <div class="row">
                    <div class="col-sm-12 profile-summary">
                        <div class="img-circle" style="background-image:url('<?php echo $infoRow['pathToPicture']; ?>')"></div>
                        <h2 id="username"><?php echo $infoRow['firstName']. " " . substr($infoRow['lastName'],0,1)."."; ?></h2>
                        <h3 id="location"><?php echo $infoRow['city'].", ".$infoRow['state'] ?></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 sidebar">
                        <ul>
                            <li>
                                <a href="messages.php">Messages</a>
                            </li>
                            <li>
                                <a href="requests.php">Requests</a></a>
                            </li>
                            <li>
                                <a href="transactions.php">Transactions</a></a>
                            </li>
                            <li>
                                <a href="settings.php">Settings</a></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 populated-section">
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Messages</h2>
                        <div class="well" id="message-box">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h4>Maria M.</h4><h5><span class="dash">-</span>Service</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <span class="date">4/02/2017</span><span class="time">12:07am</span>
                                </div>
                            </div>
                        </div>
                        <div class="well" id="message-box">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h5>Maria M.</h5>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <span class="date">4/02/2017</span><span class="time">12:07am</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="request-box">
                    <div class="col-sm-12">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require("includes/footer.php"); ?>