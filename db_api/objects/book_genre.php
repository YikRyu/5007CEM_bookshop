<?php
    class Genre{
    
        // database connection and table name
        private $conn;
        private $table_name = "book_genre";
    
        // object properties
        public $genreID;
        public $genreName;
        public $genreDesc;
    
        public function __construct($db){
            $this->conn = $db;
        }
    
        // used by select drop-down list
        public function readAll(){
            //select all data
            $query = "SELECT
                        genreID, genreName, genreDesc
                    FROM
                        " . $this->table_name . "
                    ORDER BY
                        genreName";
    
            $stmt = $this->conn->prepare( $query );
            $stmt->execute();
    
            return $stmt;
        }

        // used by select drop-down list
        public function read(){
        
            //select all data
            $query = "SELECT
                        genreID, genreName, genreDesc
                    FROM
                        " . $this->table_name . "
                    ORDER BY
                        genreName";
        
            $stmt = $this->conn->prepare( $query );
            $stmt->execute();
        
            return $stmt;
        }

    }
?>