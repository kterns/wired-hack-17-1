<?php
    include_once("includes/class.auth.php");
    
    if(isset($_POST['new-first'])){
        $auth = new auth();
        $flag = $auth->validateUser($_POST['new-email'],$_POST['new-pw1'], $_POST['new-pw2'], $_POST['new-first'],$_POST['new-last'],$_POST['new-address'],$_POST['new-address2'],$_POST['new-city'], $_POST['new-state'], $_POST['new-zipcode'], $_POST['new-type'], $_POST['new-description'], $_POST['new-phone']);
        if($flag == true){
            $target_dir = "images/user_pictures/user_profile_pictures/";
            //do picture shit
            if(empty($_FILES['new-photo']['name'])){
               //default profile picture
                $uniqId = "default.png";
                $filePath = $target_dir.$uniqId;
            }else{
                $target_file = $target_dir . basename($_FILES["new-photo"]["name"]);
                $ext = end((explode(".", $target_file)));
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                
                $check = getimagesize($_FILES["new-photo"]["tmp_name"]);
                if($check !== false) {
                    $uniqId = uniqid();
                    $filePath = $target_dir.$uniqId.".".$ext;
                    move_uploaded_file($_FILES["new-photo"]["tmp_name"], $filePath);
                    
                }
            }
            $auth->createUser($_POST['new-email'],$_POST['new-pw1'], $_POST['new-first'],$_POST['new-last'],$_POST['new-address'],$_POST['new-address2'],$_POST['new-city'], $_POST['new-state'], $_POST['new-zipcode'], $_POST['new-type'], $_POST['new-description'], $filePath, $_POST['new-phone']);
            header("Location: profile.php");
            exit();
                
        }
    }
?>


<?php 
    require("includes/header.php");
    require("includes/nav.php");
?>

    <div class="container" id="main-page">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h2>Create an Account</h2>
                <form id="new-account" method="POST" enctype="multipart/form-data">
                    <div class="form-group name">
                        <label>First Name <span class="red">*</span></label>
                        <input type="text" name="new-first" class="form-control" id="new-first" required>
                    </div>
                    <div class="form-group name">
                        <label>Last Name <span class="red">*</span></label>
                        <input type="text" name="new-last" class="form-control" id="new-last" required>
                    </div>
                    <div class="form-group name">
                        <label>Password <span class="red">*</span></label>
                        <input type="password" name="new-pw1" class="form-control" id="new-pw1" required>
                    </div>
                    <div class="form-group name">
                        <label>Enter Password Again <span class="red">*</span></label>
                        <input type="password" name="new-pw2" class="form-control" id="new-pw2" required>
                    </div>
                    <div class="form-group contact">
                        <label>Email <span class="red">*</span></label>
                        <input type="email" name="new-email" class="form-control" id="new-email" required>
                    </div>
                    <div class="form-group contact">
                        <label>Phone <span class="red">*</span></label>
                        <input type="tel" pattern="^[0-9\-\+\s\(\)]*$" name="new-phone" class="form-control" id="new-phone" required>
                    </div>
                    <div class="form-group">
                        <label>Address <span class="red">*</span></label>
                        <input type="text" name="new-address" class="form-control" placeholder="Address 1" id="address1-new" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="new-address2" class="form-control" placeholder="Address 2" id="address2-new">
                    </div>
                    <div class="form-group">
                        <input type="text" name="new-city" class="form-control city" placeholder="City" id="city-new" required>
                        <select class="form-control state" name="new-state" id="state-new"">
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
                            	<option value="SC">SC</option>
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
                        <input type="text" name="new-zipcode" class="form-control zipcode" placeholder="Zipcode" id="zipcode-new" required>
                    </div>
                    <div class="form-group">
                        <label>Upload a Photo</label>
                        <input type="file" name="new-photo" class="form-control" id="photo-new">
                    </div>
                    <div class="form-group">
                        <label>Bio Summary <span class="red">*</span></label>
                        <textarea class="form-control" name="new-description" id="bio-new"></textarea>
                    </div>
                    <div class="form-group">
                        <label>User Type <span class="red">*</span></label>
                        <div classs="inline-input">
                            <input type="radio" name="new-type" value="1">
                            <label>I need some help</label>
                        </div>
                        <div classs="inline-input">
                            <input type="radio" name="new-type" value="2">
                            <label>I can give some help</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-submit" id="submit-btn">Create</button>
                </form>
            </div>
        </div>
    </div>
    <footer class="container-fluid">
        <div class="row">
            <div class="col-sm-12 pull-right text-right">
                Created by Team DCKP
            </div>
        </div>
    </footer>
    
    <script>
        var password = document.getElementById("new-pw1"), confirm_password = document.getElementById("new-pw2");
        
        function validatePassword() {
          if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
          } else {
            confirm_password.setCustomValidity('');
          }
        }
        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>

<?php require("includes/footer.php"); ?>