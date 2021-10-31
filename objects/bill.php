<?php 

Class Bill{
 
    private $conn;
    private $table_name = "billing";
    
    

    // object properties
    public $bill_id;
    public $BRANCH_ID;
    public $CONTRACT_NO;
    public $CUSTOMER_NAME;
    public $METER_SERIAL_NO;
    public $PERIOD;
    public $CYCLE_NO;
    public $AMOUNT;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }   
    
    function readAll($from_record_num, $records_per_page){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY bill_id 
        LIMIT {$from_record_num}, {$records_per_page}";
        
        $stmt = $this->conn->prepare( $query );

        $stmt->execute();

        return $stmt;
    }
    
    function readAllMonth($from_record_num, $records_per_page,$month){
        $query = "SELECT * FROM ". $this->table_name ." WHERE PERIOD Like '%{$month}%' ORDER BY bill_id 
        LIMIT {$from_record_num}, {$records_per_page}";
        
        $stmt = $this->conn->prepare( $query );

        $stmt->execute();

        return $stmt;
        // var_dump($stmt);
    }
    
    function create($BRANCH_ID,$CONTRACT_NO,$CUSTOMER_NAME,$METER_SERIAL_NO,$PERIOD,$CYCLE_NO,$AMOUNT){

        //write query
        $query =  $query = "insert into billing(BRANCH_ID,CONTRACT_NO,CUSTOMER_NAME,METER_SERIAL_NO,PERIOD,CYCLE_NO,AMOUNT) 
        values('".$BRANCH_ID."','".$CONTRACT_NO."','".$CUSTOMER_NAME."','".$METER_SERIAL_NO."','".$PERIOD."','".$CYCLE_NO."','".$AMOUNT."')";
                   

        $stmt = $this->conn->prepare($query);



        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }
    
     public function countAll(){

        $query = "SELECT bill_id FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }
    
    public function countAllMonth($month){

        $query = "SELECT bill_id FROM " . $this->table_name . " WHERE PERIOD Like '%{$month}%'";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
        // var_dump($stmt);
    }
    
    public function search($search_term, $from_record_num, $records_per_page){

        // select query
        $query = "SELECT * FROM
                " . $this->table_name . "
            WHERE
                CONTRACT_NO = ? 
            ORDER BY
                CONTRACT_NO ASC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind variable values
        $search_term = "$search_term";
        $stmt->bindParam(1, $search_term);

        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }
    
     function contractInquire($num){
        $query = "SELECT * FROM " . $this->table_name . " WHERE CONTRACT_NO = " . $num ;

        $stmt = $this->conn->prepare( $query );
        
        $stmt->execute();
        
        return $stmt;
        


    }
    
     function readOne($num){

        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE
                CONTRACT_NO = $num
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->BRANCH_ID = $row['BRANCH_ID'];
        $this->CONTRACT_NO = $row['CONTRACT_NO'];
        $this->CUSTOMER_NAME = $row['CUSTOMER_NAME'];
        $this->METER_SERIAL_NO = $row['METER_SERIAL_NO'];
        $this->PERIOD = $row['PERIOD'];
        $this->CYCLE_NO = $row['CYCLE_NO'];
        $this->AMOUNT = $row['AMOUNT'];
    }
    
    function delete($month){

        $query = "DELETE FROM " . $this->table_name . " WHERE PERIOD LIKE '%{$month}%'";

        $stmt = $this->conn->prepare($query);


        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}

?>