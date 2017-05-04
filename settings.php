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
   if(isset($_POST['listToRemoveHelp'])){
       $userInfo->deleteFromHelpRequests($_POST['listToRemoveHelp']);
   }
   if(isset($_POST['edit-email'])){
       $userInfo->updateEmail($_POST['edit-email']);
   }
   if(isset($_POST['edit-phone'])){
       $userInfo->updatePhone($_POST['edit-phone']);
   }
   if(isset($_POST['edit-bio'])){
       $userInfo->updateBio($_POST['edit-bio']);
   }
   if(isset($_POST['edit-address'])){
       $userInfo->updateAddress($_POST['edit-address'],$_POST['edit-address2'], $_POST['edit-city'], $_POST['edit-state'], $_POST['edit-zipcode']);
   }
   if(isset($_POST['add-rec-serv'])){
       $userInfo->insertIntoHelpRequestList($_POST['add-rec-serv'],$_POST['add-rec-title'], $_POST['add-rec-desc']);
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
            <?php  if($_SESSION['type'] == "giver") {?>
            <div class="row" id="giver-help">
                <div class="col-sm-12">
                    <h2>How can you help?</h2>
                    
                    <form id="add-helping" method="POST">
                        <div class="row">
                           <div class="col-xs-11">
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
                               <button class="add-btn btn btn-primary" type="submit"><span class="glyphicon glyphicon-plus"></span></button>
                           </div>
                        </div>
                    </form>
                    <h4>With these designations, people who need help in these areas in your community will be shown on the requests page.</h4>
                    <div class="well" id="settings-box">
                        <?php
                            $rows = $userInfo->getServicesList();
                            foreach($rows as $row){
                                echo "<span class='help'>".$row['name']. "<form name='deleteShit' method='POST'> <input type='hidden' name='listToRemove' value='".$row['slid'] ."'> <button class='remove' type='submit'>x</button> </form> </span>";

                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-12" id="help-list">
                                
                            </div>
                        </div>
                    </div>
                    <form id="edit-area" method="POST">
                        <div class="form-group inline">
                            <h4>Help those who are</h4>
                            <select class="form-control area-away" name="edit-area">
                                <option value="5">5 Miles</option>
                                <option value="10">10 Miles</option>
                                <option value="15" selected="selected">15 Miles</option>
                                <option value="20">20 Miles</option>
                                <option value="25">25 Miles</option>
                                <option value="30">30 Miles</option>
                                <option value="35">35 Miles</option>
                            </select>
                            <h4>away from you.</h4>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-sm-12">
                    <h2>Manage User Settings</h2>
                    <ul class="well-list">
                        <li class="well">Email address <span class="data"><?php echo $infoRow['email']; ?></span><span class="glyphicon glyphicon-pencil edit" data-toggle="modal" data-target="#email"></span></li>
                        <li class="well">Phone <span class="data"><?php echo $infoRow['phone']; ?></span><span class="glyphicon glyphicon-pencil edit" data-toggle="modal" data-target="#phone"></span></li>
                        <li class="well">Location <span class="data"><?php echo $infoRow['address1'] . " ". $infoRow['city']." ".$infoRow['state'].", ". $infoRow['zipcode'] ?></span><span class="glyphicon glyphicon-pencil edit" data-toggle="modal" data-target="#location-edit"></span></li>
                        <li class="well">Bio Summary <span class="data"><?php echo $infoRow['bio']; ?></span></span><span class="glyphicon glyphicon-pencil edit" data-toggle="modal" data-target="#summary"></span></li>
                    </ul>
                </div>
            </div>
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
<div class="modal fade" id="email">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Email 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                   </button>
                </h3>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Current email : <?php echo $infoRow['email']; ?> </label> 
                        <input type="email" class="form-control" name="edit-email">
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
<div class="modal fade" id="phone">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Phone
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">x</span>
                    </button>
                </h3>
            </div>
             <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Current phone : <?php echo $infoRow['phone']; ?></label> 
                        <input type="phone" class="form-control" name="edit-phone">
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
<div class="modal fade" id="summary">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Bio Summary
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">x</span>
                    </button>
                </h3>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Bio Summary</label> 
                        <textarea name="edit-bio" class="form-control" placeholder="bio-summary-goes-here"> <?php echo $infoRow['bio']; ?></textarea>
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
<div class="modal fade" id="location-edit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Location
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">x</span>
                    </button>
                </h3>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="edit-address" class="form-control" placeholder="data address" id="address1-edit" value="<?php echo $infoRow['address1'] ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="edit-address2" class="form-control" placeholder="Address 2" id="address2-edit" value="<?php echo $infoRow['address2'] ?>" >
                    </div>
                    <div class="form-group">
                        <input type="text" name="edit-city" class="form-control city" placeholder="data city" id="city-edit" value="<?php echo $infoRow['city'] ?>"  required>
                        <select class="form-control state" name="edit-state" id="state-edit">
                        	<option value="AL">AL</option>
                        	<option value="AK">AK</option>
                        	<option value="AZ">AZ</option>
                        	<option value="AR">AR</option>
                        	<option value="CA">CA</option>
                        	<option value="CO">CO</option>
                        	<option value="CT">CT</option>
                        	<option value="DE">DE</option>
                        	<option value="DC">DC</option>
                        	<option value="FL">FL</option>
                        	<option value="GA">GA</option>
                        	<option value="HI">HI</option>
                        	<option value="ID">ID</option>
                        	<option value="IL">IL</option>
                        	<option value="IN">IN</option>
                        	<option value="IA">IA</option>
                        	<option value="KS">KS</option>
                        	<option value="KY">KY</option>
                        	<option value="LA">LA</option>
                        	<option value="ME">ME</option>
                        	<option value="MD">MD</option>
                        	<option value="MA">MA</option>
                        	<option value="MI">MI</option>
                        	<option value="MN">MN</option>
                        	<option value="MS">MS</option>
                        	<option value="MO">MO</option>
                        	<option value="MT">MT</option>
                        	<option value="NE">NE</option>
                        	<option value="NV">NV</option>
                        	<option value="NH">NH</option>
                        	<option value="NJ">NJ</option>
                        	<option value="NM">NM</option>
                        	<option value="NY">NY</option>
                        	<option value="NC">NC</option>
                        	<option value="ND">ND</option>
                        	<option value="OH">OH</option>
                        	<option value="OK">OK</option>
                        	<option value="OR">OR</option>
                        	<option value="PA">PA</option>
                        	<option value="RI">RI</option>
                        	<option selected value="SC">SC</option>
                        	<option value="SD">SD</option>
                        	<option value="TN">TN</option>
                        	<option value="TX">TX</option>
                        	<option value="UT">UT</option>
                        	<option value="VT">VT</option>
                        	<option value="VA">VA</option>
                        	<option value="WA">WA</option>
                        	<option value="WV">WV</option>
                        	<option value="WI">WI</option>
                        	<option value="WY">WY</option>
                        </select>
                        <input type="text" name="edit-zipcode" class="form-control zipcode" placeholder="Zipcode" id="zipcode-edit" value="<?php echo $infoRow['zipcode'] ?>"  required>
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

<?php require("includes/footer.php"); ?>