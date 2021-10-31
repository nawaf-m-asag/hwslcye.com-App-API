<?php
class Category{

    // database connection and table name
    private $conn;
    private $table_name = "categories";

    // object properties
    public $cat_id;
    public $cat_name;
    public $cat_description;
    public $cat_type;

    public function __construct($db){
        $this->conn = $db;
    }

    // used by select drop-down list
    function read(){
        //select all data
        $query = "SELECT
                    cat_id, cat_name,cat_type
                FROM
                    " . $this->table_name . "
                ORDER BY
                    cat_name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }

    // used to read category name by its ID
    function readName($id){

        $query = "SELECT cat_name FROM " . $this->table_name . " WHERE cat_id = $id limit 0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->cat_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->cat_name = $row['cat_name'];
    }

    function readAll($from_record_num, $records_per_page){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY cat_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    public function countAll(){

        // query to select all user records
        $query = "SELECT cat_id FROM " . $this->table_name . "";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // return row count
        return $num;
    }

    function readOne(){

        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE
                cat_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->cat_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->cat_name = $row['cat_name'];
        $this->cat_description = $row['cat_description'];
        $this->cat_type = $row['cat_type'];

    }

    function update($id, $name , $desc,$cat_type){

        $query = "UPDATE " . $this->table_name . " SET cat_name = '". $name ."', cat_description = '".
            $desc ."' , cat_type = '". $cat_type ."' WHERE cat_id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->cat_name=htmlspecialchars(strip_tags($this->cat_name));
        $this->cat_description=htmlspecialchars(strip_tags($this->cat_description));
        $this->cat_type=htmlspecialchars(strip_tags($this->cat_type));
        $this->cat_id=htmlspecialchars(strip_tags($this->cat_id));


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
                    cat_name=:cat_name, cat_description=:cat_description , cat_type=:cat_type";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->cat_name=htmlspecialchars(strip_tags($this->cat_name));
        $this->cat_description=htmlspecialchars(strip_tags($this->cat_description));
        $this->cat_type=htmlspecialchars(strip_tags($this->cat_type));





        // bind values
        $stmt->bindParam(":cat_name", $this->cat_name);
        $stmt->bindParam(":cat_description", $this->cat_description);
        $stmt->bindParam(":cat_type", $this->cat_type);


        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    function delete($id){

        $query = "DELETE FROM " . $this->table_name . " WHERE cat_id = $id";

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
                cat_name LIKE ? 
            ORDER BY
                cat_name ASC";

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

    function readPost(){
        //select all data
        $query = "SELECT
                    cat_id, cat_name,cat_type
                FROM
                    " . $this->table_name . " WHERE cat_type = \"منشور\"
                ORDER BY
                    cat_name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }

    function readProject(){
        //select all data
        $query = "SELECT
                    cat_id, cat_name,cat_type
                FROM
                    " . $this->table_name . " WHERE cat_type = \"مشروع\"
                ORDER BY
                    cat_name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }
    
    function readAlbum(){
        //select all data
        $query = "SELECT
                    cat_id, cat_name,cat_type
                FROM
                    " . $this->table_name . " WHERE cat_type = \"البوم\"
                ORDER BY
                    cat_name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }
}
?>