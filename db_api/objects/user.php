<?php
class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "user";
  
    // object properties
    public $userID;
    public $username;
    public $password;
    public $fullname;
    public $cardNo;
    public $bankName;
    public $address;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read user info
    function read(){
    
        // select all query
        $query = "SELECT
                    userID, password, username, fullname, cardNo, bankName, address
                FROM
                    ". $this->table_name ." 
                ORDER BY
                    userID ASC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create user
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    username=:username, password=:password, fullname=:fullname, cardNo=:cardNo, bankName=:bankName, address=:address" ;
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->fullname=htmlspecialchars(strip_tags($this->fullname));
        $this->cardNo=htmlspecialchars(strip_tags($this->cardNo));
        $this->bankName=htmlspecialchars(strip_tags($this->bankName));
        $this->address=htmlspecialchars(strip_tags($this->address));
    
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":cardNo", $this->cardNo);
        $stmt->bindParam(":bankName", $this->bankName);
        $stmt->bindParam(":address", $this->address);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;    
    }

    // used when filling up the update user form (edit payment info)
    function readOne(){
        // query to read single record
        $query = "SELECT
                    userID, username, fullname, cardNo, bankName, address
                FROM
                    " . $this->table_name . "
                WHERE
                    userID = ?
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of user info to be updated
        $stmt->bindParam(1, $this->userID);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties //for the user
        $this->username = $row['username'];
        $this->name = $row['fullname'];
        $this->cardNo = $row['cardNo'];
        $this->bankName = $row['bankName'];
        $this->address = $row['address'];

    }

    // update the user info
    function update(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    fullname = :fullname,
                    cardNo = :cardNo,
                    bankName = :bankName,
                    address = :address
                WHERE
                    userID = :userID";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->fullname=htmlspecialchars(strip_tags($this->fullname));
        $this->cardNo=htmlspecialchars(strip_tags($this->cardNo));
        $this->bankName=htmlspecialchars(strip_tags($this->bankName));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->userID=htmlspecialchars(strip_tags($this->userID));
        
    
        // bind values
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":cardNo", $this->cardNo);
        $stmt->bindParam(":bankName", $this->bankName);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam("userID", $this->userID);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // delete the user
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE userID = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->userID=htmlspecialchars(strip_tags($this->userID));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->userID);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    function search($keywords){
    
        // select all query
        $query = "SELECT
                    userID, password, username, fullname, cardNo, bankName, address
                FROM
                    ". $this->table_name ." 
                ORDER BY
                    userID ASC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    }

?>