<?php

Class Message{

    private $conn;
    private $table_name = "message";

    // object properties
    public $message_id;
    public $content;
    

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readAll(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY message_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
    
    function readOne(){

        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE
                message_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->message_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->content = $row['content'];
        
    }
    
    function update($id, $content){

        $query = "UPDATE " . $this->table_name . " SET content = '". $content ."' WHERE message_id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->content=htmlspecialchars(strip_tags($this->content));
        $this->message_id=htmlspecialchars(strip_tags($this->message_id));


        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
        
        // var_dump($stmt);

    }
    
    function readForWeb(){

        $query = "SELECT *
            FROM
                " . $this->table_name . "
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->content = $row['content'];
        
        
    }
}
?>