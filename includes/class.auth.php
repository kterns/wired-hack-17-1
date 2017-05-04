<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    class auth
    {
        private $dbConn;
        private $siteKey;
        
        public function __construct()
        {
            //Site Key was randomly generated at random.org
            try
            {
                $db = new PDO("mysql:host=127.0.0.1;dbname=c9", "diquirk", "");
                //Makes PDO throw exceptions for invalid SQL
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbConn = $db;
                //Site Key was randomly generated at random.org
                $this->siteKey = "MVQdJoSWloveSLyHUlgG";
            }
            catch(Exception $ex)
            {
                die($ex->getMessage());
            }
        }
        //Function generates a random 50 char string comprised of lowercase letters and number 0-9
        private function randomString($length =50)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $string = ''; 

            for($p = 0; $p<$length; $p++)
            {
                $string .= $characters[mt_rand(0, strlen($characters)-1)];
            }

            return $string;
        }

        //returns Sha256 value of the data entered combined with the sites key.
        protected function hashData($data)
        {
            return hash_hmac('sha256', $data, $this->siteKey);
        }

        //Creates a user.
        public function createUser($email,$password, $firstName, $lastName, $address1, $address2, $city, $state, $zipcode, $isGiver, $bio, $pathToPicture, $phone)
        {
            $conn = $this->dbConn;
            //Generate user salt
            $user_salt = $this->randomString();
            $originalpw = $password;
            //Salt and Hash the password
            $password = $user_salt . $password;
            $password = $this->hashData($password);
            
            // Get lat and long from address provided
            $address = $address1 . ", " . $city . ", " . $state . " " . $zipcode;
            $geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false");

            $output = json_decode($geocode);
            
            $lat = $output->results[0]->geometry->location->lat;
            $lng = $output->results[0]->geometry->location->lng;
            
            
            $stmt = $conn->prepare("Insert into users(email, firstName, lastName, address1, address2, city, state, zipcode, lat, lon, hash, salt, isGiver, bio, pathToPicture, phone) values (:email, :firstName, :lastName, :address1, :address2, :city, :state, :zipcode, :lat, :lon, :hash, :salt, :isGiver, :bio, :pathToPicture, :phone)");
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":firstName", $firstName);
            $stmt->bindValue(":lastName", $lastName);
            $stmt->bindValue(":address1", $address1);
            $stmt->bindValue(":address2", $address2);
            $stmt->bindValue(":city", $city);
            $stmt->bindValue(":state", $state);
            $stmt->bindValue(":zipcode", $zipcode);
            $stmt->bindValue(":lat", $lat);
            $stmt->bindValue(":lon", $lng);
            $stmt->bindValue(":hash", $password);
            $stmt->bindValue(":salt", $user_salt);
            $stmt->bindValue(":isGiver", $isGiver);
            $stmt->bindValue(":bio", $bio);
            $stmt->bindValue(":pathToPicture", $pathToPicture);
            $stmt->bindValue(":phone", $phone);
            $stmt->execute();

            $this->login($email, $originalpw);
        
            
        }
        
        //Changes a users password
        public function changePW($pw1, $pw2, $uID)
        {
            if($pw1==$pw2)
            {
                $conn = $this->dbConn;
                $user_salt = $this->randomString();
                $password = $user_salt . $pw1;
                $password = $this->hashData($password);
                $query = $conn->prepare("UPDATE users SET password= :pw , salt = :salt WHERE uid = :uid ");
                $stmt->bindValue(":pw", $password);
                $stmt->bindValue(":salt", $user_salt);
                $stmt->bindValue(":uid", $uID);
                $query->execute();

            }
        }
    
    //Function to validate user data.
    // A flag value of 1 means there are data errors somewhere.
    // A flag value of 0 means the data has been input corectly.
    function validateUser($email,$pw1, $pw2, $firstName, $lastName, $address1, $address2, $city, $state, $zipcode, $isGiver, $desc, $phone){
        
        $flag = 0;
        $errorList = "";
        if(empty($email)){
            $flag = 1;
            $errorList = $errorList . "<br> Please Enter in an Email.";
        }else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $flag = 1;
                $errorList = $errorList . "<br> Email address not valid.";
            }
        }
        if(empty($pw1)){
            $flag = 1;
            $errorList = $errorList . "<br> Please enter in a password.";
        }elseif($pw1 != $pw2){
            $flag = 1;
            $errorList = $errorList . "<br> Passwords do not match.";
        }
        if(empty($firstName)){
            $flag = 1;
            $errorList = $errorList. "<br> Please enter in a first name.";
        }
        if(empty($lastName)){
            $flag = 1;
            $errorList = $errorList . "<br> Please enter in a last name.";
        }
        if(empty($address1)){
            $flag = 1;
            $errorList = $errorList . "<br Please enter in an address.";
        }
        if(empty($address2)){
            $address2 = " ";
        }
        if(empty($city)){
            $flag = 1;
            $errorList = $errorList . "<br Please enter in a city.";
        }
        if(empty($zipcode)){
            $flag = 1;
            $errorList = $errorList . "<br Please enter in a zipcode.";
        }
        
        if(empty($isGiver)){
            $flag = 1;
            $errorList = $errorList . "<br Please selecct an account type.";
        }
        
        if($flag==1){
            return false;}
        else{
            return true;
        } 
    }
        public function login($email, $password)
        {
            $conn = $this->dbConn;
            //Pull the user's row from the database
            
            $stmt = $conn->prepare("Select uid, hash, salt, email, isGiver, isAdmin from users where email= :email");
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $uID = $row['uid'];
            $actualPw = $row['hash'];
            $email = $row['email'];
            $salt = $row['salt'];
            if($row['isGiver']==1){
                $type = "reciever";
            }else{
                $type = "giver";
            }
            $admin = $row['isAdmin'];
            $password = $salt . $password;
            $password = $this->hashData($password);


            if ($actualPw == $password){$match=TRUE;}
            else{$match = FALSE;}

            if($match == TRUE){
                //Username/Password combination exists, set sessions
                //First generate a random string
                $random = $this->randomString();
                //Build the token
                $random2 = $this->randomString();
                $token =  $random2. $random;
                $token = $this->hashData($token);
                 
                //Setup Session Vars
                session_start();
                $_SESSION['token'] = $token;
                $_SESSION['user_id'] = $uID;
                $_SESSION['email'] = $email;
                $_SESSION['type'] = $type;
                $_SESSION['isAdmin'] = $admin;
                
                return true; 
            }
            //No match in server, reject
            return false;
        }
        
        public function logout()
        {
            //Deleted row from loggedin
            $conn = $this->dbConn;
            $uID = $_SESSION['user_id'];
            $stmt = $conn->prepare("DELETE FROM loggedIn WHERE user_id = :uid");
            $stmt->bindValue(":uid", $uid);
            $stmt ->execute();
            if(!$query){echo "Deletion of loggedIn row not successful.";}
            else{session_destroy();}
        }
        
        /*
        * Logs the user out by destroying their session, unsetting all array keys and setting user to Anonymous.
        */
        // public function logout()
        // {
        //     session_destroy();
        //     // $_SESSION = array(); //Unset all session vars now
        //     // return true;
        // }
        
    }
?>

