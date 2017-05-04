<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
        header("Location: login.php");
        exit();
    }
    
    require("includes/header.php");
    require("includes/nav.php");
    require("includes/class.userInfo.php");
    $userInfo = new userInfo();
     
    if(isset($_POST['add-help'])){
        if(!empty($_POST['add-help'])){
            //adding a new entry
            $userInfo->insertIntoGiverList($_POST['add-help']);
            
        }
    }
   
   if(isset($_POST['listToRemove'])){
       $userInfo->deleteFromServiceList($_POST['listToRemove']);
   }
   
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
            <h2>Messages</h2>
            <div class="row">
                <div class="col-sm-12" id="message-list">
                    <?php
                    $uniqueTransactions = $userInfo->getUniqueTransactions();
                    foreach($uniqueTransactions as $transaction){
                        $rowInfo = $userInfo->getTransactionInfo($transaction['tid']);
                        
                        // select u.firstName, u.lastName, t.timeRequested, s.name, t.tid from transactions t left join users u on t.giverId=u.uid left join helpRequests hr on t.helpId=hr.hrid left join services s on hr.sid=s.sid where t.tid= :tid
                        
                        echo '<div class="well message-box" data-toggle="modal" data-target="#message'.$transaction['tid'].'">';
                            $ts = $rowInfo['timeRequested'];
                            $date = date('m/d/Y', strtotime($ts));
                            $time = date('h:i A', strtotime($ts));
                            echo "<div class='row'>";
                                echo "<div class='col-xs-6'>";
                                    echo "<h4>" .$rowInfo['firstName']. " " . substr($infoRow['lastName'],0,1)."."."</h4><h5><span class='dash'>-</span>".$rowInfo['name']."</h5>";
                                echo "</div>";
                                echo "<div class='col-xs-6' text-right>";
                                   echo "<span class='date'>". $date ."</span><span class='time'>".$time."</span>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    ?>
                    
                    <div class="modal fade" id="<?php echo "message".$transaction['tid']; ?>">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">Message with <?php echo $rowInfo['firstName']. " " . substr($infoRow['lastName'],0,1) . " about " . $rowInfo['name'] ?>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">x</span>
                                       </button>
                                    </h3>
                                </div>
                                <form id="add-message" method="POST">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xs-12" id="message-history">
                                                <div class="message-box">
                                                    <div class="well me">
                                                        At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. 
                                                    </div>
                                                    <span class="text-time">04/02/17, 12:00 am</span>
                                                </div>
                                                <div class="message-box">
                                                    <div class="well you">
                                                        At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis.
                                                    </div>
                                                    <span class="text-time">04/02/17, 12:00 am</span>
                                                </div>
                                                <div class="message-box">
                                                    <div class="well you">
                                                         praesentium voluptatum deleniti atque corrupti
                                                    </div>
                                                    <span class="text-time">04/02/17, 12:00 am</span>
                                                </div>
                                                <div class="message-box">
                                                    <div class="well me">
                                                        It's a deal.
                                                    </div>
                                                    <span class="text-time">04/02/17, 12:00 am</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                           <div class="col-xs-12">
                                               <div class="form-group">
                                                   <label>Send a message</label>
                                                   <textarea class="form-control" name="add-message"></textarea>
                                               </div>
                                           </div>
                                        </div>
                                       
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-modal">Send</button>
                                        <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } // End of For ?>
                </div>
            </div>        
        </div>
    </div>
</div>

<?php require("includes/footer.php"); ?>