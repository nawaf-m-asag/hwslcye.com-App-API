<?php

class Complain{
    private $conn;
    private $table_name = "complains";

    // object properties
    public $com_id;
    public $com_name;
    public $com_phone;
    public $com_subject;
    public $com_title;
    public $complain;
    public $com_type;
    public $com_cat;
    public $administration;
    public $agreement_num;
    public $status;
    public $created;


    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    function readAll($from_record_num, $records_per_page){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY com_id DESC";

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
                com_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->com_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->com_name = $row['com_name'];
        $this->com_phone = $row['com_phone'];
        $this->com_subject = $row['com_subject'];
        $this->complain = $row['complain'];

    }

    function update($id , $name , $phone , $subject , $com){

        $query = "UPDATE " . $this->table_name . " SET com_name = '". $name ."', com_phone = '".
            $phone ."',com_subject = '". $subject ."', complain  = '". $com ."' WHERE com_id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->com_name=htmlspecialchars(strip_tags($this->com_name));
        $this->com_phone=htmlspecialchars(strip_tags($this->com_phone));
        $this->com_subject=htmlspecialchars(strip_tags($this->com_subject));
        $this->complain=htmlspecialchars(strip_tags($this->complain));
        $this->com_id=htmlspecialchars(strip_tags($this->com_id));


        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;


    }

    public function search($search_term){

        // select query
        $query = "SELECT * FROM
                " . $this->table_name . "
            WHERE
                com_name LIKE ? 
            ORDER BY
                com_name DESC";

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
    
    public function searchID($id){

        // select query
        $query = "SELECT * FROM
                " . $this->table_name . "
            WHERE
                com_id LIKE ? 
            ORDER BY
                com_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind variable values
        $id = "%{$id}%";
        $stmt->bindParam(1, $id);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }



    function readForHead(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . " WHERE status = 0
            ORDER BY created DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
    public function countAll(){

        $query = "SELECT com_id FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }

    public function countAllNotRead(){

        $query = "SELECT com_id FROM " . $this->table_name . " WHERE status = 0";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }

    function create(){

        //write query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    com_name=:com_name, com_phone=:com_phone , com_subject=:com_subject, complain=:complain, com_type=:com_type , com_cat=:com_cat , com_title=:com_title 
                    , agreement_num=:agreement_num , administration=:administration";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->com_name=htmlspecialchars(strip_tags($this->com_name));
        $this->com_phone=htmlspecialchars(strip_tags($this->com_phone));
        $this->com_subject=htmlspecialchars(strip_tags($this->com_subject));
        $this->complain=htmlspecialchars(strip_tags($this->complain));
        $this->com_type=htmlspecialchars(strip_tags($this->com_type));
        $this->com_cat=htmlspecialchars(strip_tags($this->com_cat));
        $this->com_title=htmlspecialchars(strip_tags($this->com_title));
        $this->agreement_num=htmlspecialchars(strip_tags($this->agreement_num));
        $this->administration=htmlspecialchars(strip_tags($this->administration));

        // bind values
        $stmt->bindParam(":com_name", $this->com_name);
        $stmt->bindParam(":com_phone", $this->com_phone);
        $stmt->bindParam(":com_subject", $this->com_subject);
        $stmt->bindParam(":complain", $this->complain);
        $stmt->bindParam(":com_type", $this->com_type);
        $stmt->bindParam(":com_cat", $this->com_cat);
        $stmt->bindParam(":com_title", $this->com_title);
        $stmt->bindParam(":agreement_num", $this->agreement_num);
        $stmt->bindParam(":administration", $this->administration);


        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
        
        // var_dump($stmt);
    }
    
    function updateStatus($id){
        $query = "Update ". $this->table_name ." SET status = 1 WHERE com_id = " . $id ;

        $stmt = $this->conn->prepare($query);



        if($stmt->execute()){
            return true;
        }

        return false;

//        var_dump($stmt);
    }

    function agreeInquire($num){
        $query = "SELECT * FROM " . $this->table_name . " WHERE agreement_num = " . $num ." LIMIT 0,1";

        $stmt = $this->conn->prepare( $query );

        if($stmt->execute()){
            return true;
        }
        else
            return false;


    }

    function phoneInquire($num){
        $query = "SELECT * FROM " . $this->table_name . " WHERE com_phone = " . $num ." LIMIT 0,1";

        $stmt = $this->conn->prepare( $query );

        if($stmt->execute()){
            return true;
        }
        else
            return false;


    }
    
    function readFromRes($id){
        $query = "SELECT complain,com_subject FROM " . $this->table_name . " WHERE com_id = " . $id . "";
        
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->complain = $row['complain'];
        $this->com_subject = $row['com_subject'];
        
    }
    
    function readWithAgreeNum($agreenum){
        $query = "SELECT * FROM " . $this->table_name . " WHERE agreement_num = $agreenum ORDER BY com_id DESC";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return($stmt);
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $this->res_body = $row['res_body'];
        // $this->res_id = $row['res_id'];
        // $this->com_id = $row['com_id'];
    }
    
    
    function readWithPhoneNum($num){
        $query = "SELECT * FROM " . $this->table_name . " WHERE com_phone = $num ORDER BY com_id DESC";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return($stmt);

        // $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $this->res_body = $row['res_body'];
        // $this->res_id = $row['res_id'];
        // $this->com_id = $row['com_id'];
    }





    function readWithAgreeNumForApp($agreenum){
        $query = "SELECT * FROM " . $this->table_name . " WHERE agreement_num = $agreenum ORDER BY com_id DESC";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $row;
       
    }
    
   
    function readWithPhoneNumForApp($num){
        $query = "SELECT * FROM " . $this->table_name . " WHERE com_phone = $num ORDER BY com_id DESC";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $row;
        
    }


}

?>