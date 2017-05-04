<?php 
    require("includes/header.php");
    require("includes/nav.php");
    
    include_once("includes/class.userInfo.php");
    
    $userInfo = new userInfo();
    $infoRow = $userInfo->getUserInfo();
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
                                <a href="settings.php">Settings</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 populated-section">
                <h2>Current Transactions</h2>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="well transaction">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4>For your need of Service, Maria M. has offered:</h5>
                                    <p>Description</p>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <span class="date">Sent on: 4/02/2017</span><span class="time">12:07am</span>
                                    <button class="btn btn-close">Awaiting approval</button>
                                </div>
                            </div>
                        </div>
                        <div class="well transaction">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4>For your need of Service, Maria M. has offered:</h5>
                                    <p>Description</p>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <span class="date">Sent on: 4/02/2017</span><span class="time">12:07am</span>
                                    <button class="btn btn-primary">Awaiting approval</button>
                                </div>
                            </div>
                        </div>
                        <div class="well transaction">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4>For your need of Service, Maria M. has offered:</h5>
                                    <p>Description</p>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <span class="date">Sent on: 4/02/2017</span><span class="time">12:07am</span>
                                    <button class="btn btn-close">Awaiting completion</button>
                                </div>
                            </div>
                        </div>
                        <div class="well transaction">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4>For your need of Service, Maria M. has offered:</h5>
                                    <p>Description</p>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <span class="date">Sent on: 4/02/2017</span><span class="time">12:07am</span>
                                    <button class="btn btn-primary">Awaiting completion</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h2>Past Transactions</h2>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="well" id="message-box">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4>For your need of Service, Maria M. has offered:</h5>
                                    <p>Description</p>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <span class="date">Completed on: 4/02/2017</span><span class="time">12:07am</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require("includes/footer.php"); ?>