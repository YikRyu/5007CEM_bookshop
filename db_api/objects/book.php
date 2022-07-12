<?php
class Book{
  
    // database connection and table name
    private $conn;
    private $table_name = "book";
  
    // object properties
    public $bookID;
    public $bookISBN;
    public $bookName;
    public $bookAuthor;
    public $bookGenre;
    public $bookDesc;
    public $bookPrice;
    public $bookAmount;
    public $bookCover;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read books
    function read(){
    
        // select all query
        $query = "SELECT
                    g.genreName as genreName, b.bookID, b.bookISBN ,b.bookName, b.bookAuthor, b.bookGenre, b.bookDesc, b.bookPrice, b.bookAmount
                FROM
                    " . $this->table_name . " b
                    LEFT JOIN
                        book_genre g
                            ON b.bookGenre = g.genreID
                ORDER BY
                    b.bookName ASC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create book
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    bookISBN=:bookISBN, bookName=:bookName, bookAuthor=:bookAuthor, bookGenre=:bookGenre, bookDesc=:bookDesc, bookPrice=:bookPrice, bookAmount=:bookAmount";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->bookISBN=htmlspecialchars(strip_tags($this->bookISBN));
        $this->bookName=htmlspecialchars(strip_tags($this->bookName));
        $this->bookAuthor=htmlspecialchars(strip_tags($this->bookAuthor));
        $this->bookGenre=htmlspecialchars(strip_tags($this->bookGenre));
        $this->bookDesc=htmlspecialchars(strip_tags($this->bookDesc));
        $this->bookPrice=htmlspecialchars(strip_tags($this->bookPrice));
        $this->bookAmount=htmlspecialchars(strip_tags($this->bookAmount));
    
        // bind values
        $stmt->bindParam(":bookISBN", $this->bookISBN);
        $stmt->bindParam(":bookName", $this->bookName);
        $stmt->bindParam(":bookAuthor", $this->bookAuthor);
        $stmt->bindParam(":bookGenre", $this->bookGenre);
        $stmt->bindParam(":bookDesc", $this->bookDesc);
        $stmt->bindParam(":bookPrice", $this->bookPrice);
        $stmt->bindParam(":bookAmount", $this->bookAmount);

        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;    
    }

    // used when filling up the update book form
    function readOne(){
        // query to read single record
        $query = "SELECT
                    g.genreName as genreName, b.bookID, b.bookISBN ,b.bookName, b.bookAuthor, b.bookGenre, b.bookDesc, b.bookPrice, b.bookAmount
                FROM
                    " . $this->table_name . " b
                    LEFT JOIN
                        book_genre g
                            ON b.bookGenre = g.genreID
                WHERE
                    b.bookID = ?
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of book to be updated
        $stmt->bindParam(1, $this->bookID);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties //for books
        $this->bookISBN = $row['bookISBN'];
        $this->bookName = $row['bookName'];
        $this->bookAuthor = $row['bookAuthor'];
        $this->bookDesc = $row['bookDesc'];
        $this->bookPrice = $row['bookPrice'];
        $this->bookAmount = $row['bookAmount'];
        //for genre
        $this->bookGenre = $row['bookGenre'];
        $this->genreName = $row['genreName'];
    }

    // update the book
    function update(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    bookAmount = :bookAmount
                WHERE
                    bookID = :bookID";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->bookAmount=htmlspecialchars(strip_tags($this->bookAmount));
        $this->bookID=htmlspecialchars(strip_tags($this->bookID));
        
    
        // bind values
        $stmt->bindParam(":bookAmount", $this->bookAmount);
        $stmt->bindParam(":bookID", $this->bookID);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // delete the book
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE bookID = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->bookID=htmlspecialchars(strip_tags($this->bookID));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->bookID);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // search books
    function search($keywords){
    
        // select all query
        $query = "SELECT
                    g.genreName as genreName, b.bookID, b.bookISBN ,b.bookName, b.bookAuthor, b.bookGenre, b.bookDesc, b.bookPrice, b.bookAmount
                FROM
                    " . $this->table_name . " b
                    LEFT JOIN
                        book_genre g
                            ON b.bookGenre = g.genreID
                WHERE
                    b.bookName LIKE ? OR b.bookDesc LIKE ? OR g.genreName LIKE ?
                ORDER BY
                    b.bookName ASC";
    
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

    // read books with pagination
    public function readPaging($from_record_num, $records_per_page){
    
        // select query
        $query = "SELECT
                    g.genreName as genreName, b.bookID, b.bookISBN ,b.bookName, b.bookAuthor, b.bookDesc, b.bookGenre, b.bookPrice, b.bookAmount
                FROM
                    " . $this->table_name . " b
                    LEFT JOIN
                        book_genre g
                            ON b.bookGenre = g.genreID
                ORDER BY b.bookName ASC
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

    // used for paging books
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }

    }

?>