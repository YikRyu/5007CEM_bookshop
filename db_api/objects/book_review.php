<?php
class Review{
  
    // database connection and table name
    private $conn;
    private $table_name = "book_review";
  
    // object properties
    public $reviewID;
    public $bookID;
    public $reviewUser;
    public $reviewContent;
    public $reviewRating;
    public $created;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read reviews
    function read(){
    
        // select all query
        $query = "SELECT
                    b.bookName as bookName, r.reviewID, r.bookID, r.reviewUser ,r.reviewContent, r.reviewRating, r.created
                FROM
                    " . $this->table_name . " r
                    LEFT JOIN
                        book b
                            ON r.bookID = b.bookID
                ORDER BY
                    r.created DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create review
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    reviewID=:reviewID, bookID=:bookID, reviewUser=:reviewUser, reviewContent=:reviewContent, reviewRating=:reviewRating, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->reviewID=htmlspecialchars(strip_tags($this->reviewID));
        $this->bookID=htmlspecialchars(strip_tags($this->bookID));
        $this->reviewUser=htmlspecialchars(strip_tags($this->reviewUser));
        $this->reviewContent=htmlspecialchars(strip_tags($this->reviewContent));
        $this->reviewRating=htmlspecialchars(strip_tags($this->reviewRating));
        $this->created=htmlspecialchars(strip_tags($this->created));
        
    
        // bind values
        $stmt->bindParam(":reviewID", $this->reviewID);
        $stmt->bindParam(":bookID", $this->bookID);
        $stmt->bindParam(":reviewUser", $this->reviewUser);
        $stmt->bindParam(":reviewContent", $this->reviewContent);
        $stmt->bindParam(":reviewRating", $this->reviewRating);
        $stmt->bindParam(":created", $this->created);

    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;    
    }


    // read reviews with pagination
    public function readPaging($from_record_num, $records_per_page){
    
        // select query
        $query = "SELECT
                    b.bookName as bookName, r.reviewID, r.bookID, r.reviewUser ,r.reviewContent, r.reviewRating, r.created
                FROM
                    " . $this->table_name . " r
                    LEFT JOIN
                        book b
                            ON r.bookID = b.bookID
                ORDER BY r.created DESC
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

    // used for paging reviews
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }

    }

?>