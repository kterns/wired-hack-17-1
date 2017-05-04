<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    class admin
    {
        private $dbConn;
        
        public function __construct() {
            try
            {
                $db = new PDO("mysql:host=127.0.0.1;dbname=c9", "diquirk", "");
                //Makes PDO throw exceptions for invalid SQL
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbConn = $db;
                
            }
            catch(Exception $ex)
            {
                die($ex->getMessage());
            }
        }
        
        public function getUsers() {
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("Select * from users");
            $stmt->execute();
            $row = $stmt->fetchAll();
            return $row;
        }
        
        public function addService($name) {
            $conn = $this->dbConn;
            
            $stmt = $conn->prepare("Insert into services(name) values (:name)");
            $stmt->bindValue(":name", $name);
            $stmt->execute();
        }
    }
?>