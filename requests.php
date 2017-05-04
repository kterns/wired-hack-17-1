<?php

    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
        header("Location: login.php");
        exit();
    }
    
    require("includes/header.php");
    require("includes/nav.php");
    
    include_once("includes/class.userInfo.php");
    
    $userInfo = new userInfo();
    $infoRow = $userInfo->getUserInfo();
    
    if(isset($_POST['add-rec-serv'])){
        $userInfo->insertIntoHelpRequestList($_POST['add-rec-serv']," "," ");
    }
    
    if(isset($_POST['edit-rec-desc'])){
        $userInfo->updateHelpRequest($_POST['hrid'], $_POST['edit-rec-title'],$_POST['edit-rec-desc']);
    }
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
                <?php if($_SESSION['type'] == "reciever") {  ?>
                    <div class="row" id="receiver-help">
                        <div class="col-sm-12">
                            <h2>What kind of help do you need?</h2>
                            <form  method="POST">
                                <div class="row">
                                   <div class="col-xs-11">
                                       <div class="form-group">
                                           <select name="add-rec-serv" class="form-control">
                                               <?php
                                                $rows = $userInfo->retrieveServicesList();
                                                foreach($rows as $row){
                                                    echo "<option value='".$row['sid']."'>". $row['name']." </option> ";
                                                }
                                               ?>
                                           </select>
                                       </div>
                                   </div>
                                   <div class="col-xs-1 btn-add text-right">
                                       <button class="add-btn btn btn-primary" type="submit"><span class="glyphicon glyphicon-plus"></span></button>
                                   </div>
                                </div>
                            </form>
                            <div id="request-list">
                                 <ul class="well-list">
                                     <?php
                                        $rows = $userInfo->getHelpRequests();
                                        foreach($rows as $row){
                                            if(strlen($row['title'])<2) $title = "There is no title";
                                            else $title = $row['title'];
                                            echo '<li class="well">'.$row['name'].' <span class="data">'.$title.'</span><button class="btn btn-primary edit" data-toggle="modal" data-target="#request'.$row['hrid'].'">Edit Request</button></span></li>';

                                     ?>
                                     <div class="modal fade" id="<?php echo "request". $row['hrid']; ?>">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Edit Request for Service 
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">x</span>
                                                       </button>
                                                    </h3>
                                                </div>
                                                <form method="post">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-sm-5">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="edit-service" value="<?php echo $row['name']; ?>" disabled>
                                                                    <input type="hidden" name="hrid" value="<?php echo $row['hrid']; ?>">
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-7">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control help-title" placeholder="Title - Max Characters 25" maxlength="25" name="edit-rec-title" value="<?php echo $row['title']; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea class="form-control" placeholder="Tell us more about what you need and why." name="edit-rec-desc"> <?php echo $row['description']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-modal">Save changes</button>
                                                        <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php } if($_SESSION['type'] == "giver") {?>
                    <div class="row" id="giver-help">
                        <div class="col-sm-12">
                            <h2>Requests</h2>
                            
                            <form id="filter-requests" method="POST">
                                <div class="row">
                                   <div class="col-xs-6 col-sm-4">
                                       <div class="form-group">
                                           <select name="add-help" class="form-control">
                                               <?php
                                                $rows = $userInfo->retrieveServicesList();
                                                foreach($rows as $row){
                                                    echo "<option value='".$row['sid']."'>". $row['name']." </option> ";
                                                }
                                               ?>
                                           </select>
                                       </div>
                                   </div>
                                   <div class="col-xs-1 btn-add text-right">
                                       <button class="add-btn btn btn-primary" type="submit">Filter</button>
                                   </div>
                                </div>
                            </form>
                            <div class="request-list">
                                <ul class="requests">
                                     <?php
                                                $rows = $userInfo->findNeedy();
                                                foreach($rows as $row){
                                                    
                                                $ts = $row['created'];
                                                $date = date('m/d/Y', strtotime($ts));
                                                
                                        ?>
                                    <li class="well request">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <h4><?php echo $row['name']; ?> -<?php echo $row['title']; ?></h4>
                                                <h5 class="block"><?php echo $infoRow['firstName']. " " . substr($infoRow['lastName'],0,1) ?> from <?php echo $row['city']; ?>, <?php echo $row['state']; ?></h5>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <span class="date"><?php echo $date; ?></span>
                                                <span class="manage">
                                                    <span class="glyphicon glyphicon-eye-open" data-toggle="modal" data-target="#view-request"></span>
                                                    <span class="glyphicon glyphicon-envelope" data-toggle="modal" data-target="#message-request"></span>
                                                    <span class="glyphicon glyphicon-transfer" data-toggle="modal" data-target="#start-transaction"></span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                    </li>
                                    <?php }?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="modal fade" id="view-request">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Service request for help
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">x</span>
                           </button>
                        </h3>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <h3>My name is Name.</h3>
                            <p>Bio Summary</p>
                            
                            <div class="form-group">
                                <h3>Title</h3>
                                <p>Description</p>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="message-request">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Message with First Name L. about service 
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">x</span>
                           </button>
                        </h3>
                    </div>
                    <form id="add-message" method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12" id="message-history">
                                    
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
        <div class="modal fade" id="start-transaction">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Propose a Transaction
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">x</span>
                           </button>
                        </h3>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <h4>To offer a service or good to one who is need, you must propose a transaction, and they have to approve it. After approval, once the transaction has taken place you can come back and confirm that the transaction went through.</h4>
                           <h4>You are currently proposing a transaction to Name about their Service request</h4>
                           <div class="form-group">
                               <label>
                                   Enter in a description for what you are proposing.
                               </label>
                               <textarea class="form-control" name="transaction-description"></textarea>
                           </div>
                           <div class="form-group">
                               <label>If it is a good, what is its estimated value for tax purposes?</label>
                               <input type="text" name="tax-value" class="form-control">
                           </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-modal">Send</button>
                            <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="request">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Request for Service 
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">x</span>
                           </button>
                        </h3>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <input value="service" type="text" class="form-control" name="edit-service" disabled>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <input type="text" class="form-control help-title" placeholder="Title - Max Characters 25" maxlength="25" name="edit-rec-title">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Tell us more about what you need and why." name="edit-rec-desc"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-modal">Save changes</button>
                            <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require("includes/footer.php"); ?>

<!--

SELECT *, distance
    FROM (
       SELECT *,
           ROUND( SQRT( POW( ( (69.1/1.61) * ('34.925' - latitude)), 2)
               + POW(( (53/1.61) * ('-81.026' - longitude)), 2)), 1) AS distance
           FROM lp_relations_addresses
       ) d
   WHERE d.distance < 10
ORDER BY d.distance DESC

-->