<?php

class Response
{

    // database connection and table name
    private $conn;
    private $table_name = "responses";

    // object properties
    public $res_id;
    public $res_body;
    public $user_id;
    public $com_id;
    public $created;
    public $updated;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    function read($id){

        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE
                com_id = ". $id . "";

        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;

    }

    public function countAllWhere($id){

        $query = "SELECT res_id FROM " . $this->table_name . " WHERE com_id = ". $id ."";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }

    function readOne(){

        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE
                res_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->res_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->res_body = $row['res_body'];

    }

    function update($id , $body ){

        $query = "UPDATE " . $this->table_name . " SET res_body = '". $body ."' WHERE res_id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->res_body=htmlspecialchars(strip_tags($this->res_body));

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;

    }

    function create(){

        //write query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    res_body=:res_body, com_id=:com_id , user_id=:user_id";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->res_body=htmlspecialchars(strip_tags($this->res_body));
        $this->com_id=htmlspecialchars(strip_tags($this->com_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // bind values
        $stmt->bindParam(":res_body", $this->res_body);
        $stmt->bindParam(":com_id", $this->com_id);
        $stmt->bindParam(":user_id", $this->user_id);


        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    function readWithComID($num){
        $query = "SELECT res_body, res_id,com_id FROM responses WHERE com_id = $num";
        

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return($stmt);
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $this->res_body = $row['res_body'];
        // $this->res_id = $row['res_id'];
        // $this->com_id = $row['com_id'];
    }
    function readWithComIDForApp($num){
        $query = "SELECT res_body, res_id,com_id FROM responses WHERE com_id = $num";
        

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return($row);
        
    }
    
    
   
}