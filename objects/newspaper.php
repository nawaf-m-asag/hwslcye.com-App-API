<?php 
Class Newspaper{

    private $conn;
    private $table_name = "newspaper";

    // object properties
    public $newspaper_id;
    public $newspaper_title;
    public $file;
    

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readAll($from_record_num, $records_per_page){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY newspaper_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
    
    function create(){

        //write query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    newspaper_title=:newspaper_title, file=:file";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->newspaper_title=htmlspecialchars(strip_tags($this->newspaper_title));
        $this->file=htmlspecialchars(strip_tags($this->file));
        




        // bind values
        $stmt->bindParam(":newspaper_title", $this->newspaper_title);
        $stmt->bindParam(":file", $this->file);
        


        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }
    
    function readOne(){

        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE
                newspaper_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->newspaper_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->newspaper_title = $row['newspaper_title'];
        $this->file = $row['file'];
        
    }
    
    function update($id, $title , $file){

        $query = "UPDATE " . $this->table_name . " SET newspaper_title = '". $title ."', file = '".
            $file ."' WHERE newspaper_id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->newspaper_title=htmlspecialchars(strip_tags($this->newspaper_title));
        $this->file=htmlspecialchars(strip_tags($this->file));
        $this->newspaper_id=htmlspecialchars(strip_tags($this->newspaper_id));
        


        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;

        // return var_dump($stmt);
    }
    
    function delete($id){

        $query = "DELETE FROM " . $this->table_name . " WHERE newspaper_id = $id";

        $stmt = $this->conn->prepare($query);


        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    public function search($search_term, $from_record_num, $records_per_page){

        // select query
        $query = "SELECT * FROM
                " . $this->table_name . "
            WHERE
                newspaper_title LIKE ? 
            ORDER BY
                newspaper_title ASC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind variable values
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }
}

?>