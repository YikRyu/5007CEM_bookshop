<?php
class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "user";
  
    // object properties
    public $userID;
    public $username;
    public $name;
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
                    userID, username, name, cardNo, bankName, address
                FROM
                    ". $this->table_name ." 
                ORDER BY
                    username ASC";
    
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
                    username=:username, name=:name, cardNo=:cardNo, bankName=:bankName, address=:address" ;
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->cardNo=htmlspecialchars(strip_tags($this->cardNo));
        $this->bankName=htmlspecialchars(strip_tags($this->bankName));
        $this->address=htmlspecialchars(strip_tags($this->address));
    
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":name", $this->name);
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
                    userID, username, name, cardNo, bankName, address
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
        $this->name = $row['name'];
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
                    username = :username,
                    name = :name,
                    cardNo = :cardNo,
                    bankName = :bankName,
                    address = :address
                WHERE
                    userID = :userID";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->cardNo=htmlspecialchars(strip_tags($this->cardNo));
        $this->bankName=htmlspecialchars(strip_tags($this->bankName));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->userID=htmlspecialchars(strip_tags($this->userID));
        
    
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":name", $this->name);
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

    // read user info with pagination
    public function readPaging($from_record_num, $records_per_page){
    
        // select query
        $query = "SELECT
                    userID, username, name, cardNo, bankName, address
                FROM
                    " . $this->table_name . "
                ORDER BY username ASC
                LIMIT ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        // return values from database
        return $stmt;
    }

    // used for paging user info
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }

    }

?>