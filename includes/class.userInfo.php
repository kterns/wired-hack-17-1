<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    class userInfo
    {
        private $dbConn;
        
        public function __construct()
        {
            //Site Key was randomly generated at random.org
            try
            {
                $db = new PDO("mysql:host=127.0.0.1;dbname=c9", "diquirk", "");
                //Makes PDO throw exceptions for invalid SQL
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbConn = $db;
                //Site key for hashing stuff
                
            }
            catch(Exception $ex)
            {
                die($ex->getMessage());
            }
        }
        /*
            Function to insert into the list of people who donate goods and services.
            Function takes the id of the service to be inserted.
            The user_id is obtained from the session.
        */
        public function insertIntoGiverList($sid){
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("Insert into servicesList(uid, sid) values (:uid, :sid)");
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->bindValue(":sid", $sid);
            $stmt->execute();
        }
        /*
            Function to insert into the list of people who need goods and services.
            Function takes the id of the needed service, the title of the request, and the description of the request.
            The user_id is obtained from the session.
        */
        public function insertIntoHelpRequestList($sid, $title, $description){
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("Insert into helpRequests(uid,sid, description, title) values (:uid, :sid, :description, :title)");
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->bindValue(":sid", $sid);
            $stmt->bindValue(":description", $description);
            $stmt->bindValue(":title", $title);
            $stmt->execute();
        }
        
        /*
            Function return the row in the database corresponding to a user's id.
            The user_id is obtained from the session.
        */
        public function getUserInfo(){
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("Select * from users where uid= :uid");
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        }
        
        /*
            Function returns the unique id's of all transactions that the user has commented on/associated with (no duplicates).
        */
        public function getUniqueTransactions(){
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("select DISTINCT(tid)  from transactionComments where uid= :uid");
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
            $row = $stmt->fetchAll();
            return $row;
        }
        
        /*
            Function to return the list of services that the user provides.
            The user_id is obtained from the session.
        */
        public function getServicesList(){
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("select s.name, sl.slid, sl.sid from servicesList sl left join services s on sl.sid=s.sid where sl.uid= :uid");
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
            $row = $stmt->fetchAll();
            return $row;
        }
        /*
            Function to return the list of requests that a user has created
            The user_id is obtained from the session.
        */
        public function getHelpRequests(){
            $conn = $this->dbConn;
            $stmt = $conn->prepare("select h.hrid, h.description, h.title, s.name from helpRequests h left join services s on s.sid=h.sid where h.uid= :uid and h.isActive=1");
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
            $row = $stmt->fetchAll();
            return $row;
        }
        
        
        /*
            Function returns the list of requests that users has been created.
            The list is filtered to only show results that correspond with the viewing user's serviceList.
            The user_id is obtained from the session.
            The function takes the viewing user's service configuration as input.
        */
         public function getHelpRequestsFiltered($where){
            $conn = $this->dbConn;
            $stmt = $conn->prepare("select h.hrid, h.description, h.title, s.name, u.city,u.state, h.created from helpRequests h left join services s on s.sid=h.sid left join users u on u.uid=h.uid " .$where);
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
            $row = $stmt->fetchAll();
            return $row;
        }
        
        /*
            Function to update the title and description of a helpRequest entry
            The function takes the id of the request, the title, and the description as inputs.
        */
        public function updateHelpRequest($hrid, $title, $description){
            $conn = $this->dbConn;
            $stmt = $conn->prepare("update helpRequests set title= :title, description= :description where hrid= :hrid");
            $stmt->bindValue(":title", $title);
            $stmt->bindValue(":description", $description);
            $stmt->bindValue(":hrid", $hrid);
            $stmt->execute();
        }
        
        /*
            Function to remove an entry from a user's serviceList.
            The function takes the id of the list entry to be deleted as input.
        */
        public function deleteFromServiceList($slid){
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("delete from servicesList where slid=:slid");
            $stmt->bindValue(":slid", $slid);
            $stmt->execute();
        }
        /*
            Function to soft delete a help request -- toggles the isActive column to false.
            Function takes the id of the request entry to be soft deleted.
        */
        public function deleteFromHelpRequests($hrid){
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("UPDATE helpRequests SET isActive= 0 WHERE hrid=:hrid");
            $stmt->bindValue(":hrid", $hrid);
            $stmt->execute();
        }
        /*
            Function to return the list of information associated with a transaction.
            The function takes the id of the transacation the information is needed from as input.
        */
        public function getTransactionInfo($tid){
            $conn = $this->dbConn;
            $stmt = $conn->prepare("select u.firstName, u.lastName, t.timeRequested, s.name, t.tid from transactions t left join users u on t.giverId=u.uid left join helpRequests hr on t.helpId=hr.hrid left join services s on hr.sid=s.sid where t.tid= :tid");
            $stmt->bindValue(":tid", $tid);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
            
        }
        /*
            Function to insert into the list of people who donate goods and services.
            Function takes the id of the service to be inserted.
            The user_id is obtained from the session.
        */
        public function retrieveServicesList(){
            $conn = $this->dbConn;
            $stmt = $conn->prepare("Select * from services");
            $stmt->execute();
            $row = $stmt->fetchAll();
            return $row;
        }
        
        /*
            Function to update a user's email adddress.
            Function takes the new email address as input.
            The user_id is obtained from the session.
        */
        public function updateEmail($email){
            $conn = $this->dbConn;
             
            $stmt = $conn->prepare("UPDATE users SET email= :email WHERE uid = :uid ");
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
        }
        
       /*
            Function to update a user's phone number.
            Function takes the new phone number as input.
            The user_id is obtained from the session.
        */
        public function updatePhone($phone){
            $conn = $this->dbConn;
             
            $stmt = $conn->prepare("UPDATE users SET phone= :phone WHERE uid = :uid ");
            $stmt->bindValue(":phone", $phone);
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
        }
        /*
            Function to update a user's biography.
            Function takes the new biography as input.
            The user_id is obtained from the session.
        */
        public function updateBio($bio){
            $conn = $this->dbConn;
             
            $stmt = $conn->prepare("UPDATE users SET bio= :bio WHERE uid = :uid ");
            $stmt->bindValue(":bio", $bio);
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
        }
        
        /*
            Function to update a user's physical adddress.
            Function takes the new address, city, state and zip as input.
            The user_id is obtained from the session.
        */
        public function updateAddress($address1, $address2, $city, $state, $zip){
            $conn = $this->dbConn;
             
            $stmt = $conn->prepare("UPDATE users SET address1= :address1, address2 = :address2, city = :city, state= :state, zipcode = :zip WHERE uid = :uid ");
            $stmt->bindValue(":address1", $address1);
            $stmt->bindValue(":address2", $address2);
            $stmt->bindValue(":city", $city);
            $stmt->bindValue(":state", $state);
            $stmt->bindValue(":zip", $zip);
            $stmt->bindValue(":uid", $_SESSION['user_id']);
            $stmt->execute();
        }
        
        
        /*
            Function returns the set of entries from the helpRequests table that the giver is able to provide services for.
            Like all of our functions, the uid is retrieve from the session variable.
        */
        public function findNeedy(){
            $conn = $this->dbConn;
            
            $preferences = $this->getServicesList();
            $whereQuery =" WHERE ";
            $count = 0;
            foreach($preferences as $preference){
                if($count == 0) 
                {
                    $whereQuery = $whereQuery. "h.sid='".$preference['sid']."' ";
                }
                else{
                    $whereQuery = $whereQuery . " OR h.sid='".$preference['sid']."' ";
                }
                $count++;
            }
            $whereQuery = $whereQuery . "and h.isActive=1";
            return $this->getHelpRequestsFiltered($whereQuery);
        }
        
    }
?>