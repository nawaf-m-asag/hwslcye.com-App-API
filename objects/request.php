<?php

class Request
{

    // database connection and table name
    private $conn;
    private $table_name = "requests";

    // object properties
    public $request_id;
    public $requester_name;
    public $requester_location;
    public $requester_phone;
    public $requester_identity_num;
    public $bill_counter_num;
    public $identity_photo;
    public $property_photo;
    public $request_type;
    public $status;
    public $printed;
    public $created;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    function readAll($from_record_num, $records_per_page)
    {

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY request_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

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
                request_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->request_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->requester_name = $row['requester_name'];
        $this->requester_phone = $row['requester_phone'];
        $this->requester_location = $row['requester_location'];
        $this->request_type = $row['request_type'];
        $this->requester_identity_num = $row['requester_identity_num'];
        $this->bill_counter_num = $row['bill_counter_num'];
        $this->identity_photo = $row['identity_photo'];
        $this->property_photo = $row['property_photo'];
        $this->printed = $row['printed'];




    }

    function update($id , $name , $phone, $num, $loc,$bill_num,$type){

        $query = "UPDATE " . $this->table_name . " SET requester_name ='". $name ."' , requester_location ='". $loc ."'
         ,requester_phone='". $phone ."' , requester_identity_num='". $num ."' , bill_counter_num='". $bill_num ."'
        , request_type='". $type ."' WHERE request_id =". $id ." ";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->requester_name=htmlspecialchars(strip_tags($this->requester_name));
        $this->requester_location=htmlspecialchars(strip_tags($this->requester_location));
        $this->requester_phone=htmlspecialchars(strip_tags($this->requester_phone));
        $this->requester_identity_num=htmlspecialchars(strip_tags($this->requester_identity_num));
        $this->bill_counter_num=htmlspecialchars(strip_tags($this->bill_counter_num));
        $this->request_type=htmlspecialchars(strip_tags($this->request_type));


        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;

//        var_dump($stmt);
    }

    function delete($id){

        $query = "DELETE FROM " . $this->table_name . " WHERE request_id = $id";

        $stmt = $this->conn->prepare($query);


        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    public function search($search_term){

        // select query
        $query = "SELECT * FROM
                " . $this->table_name . "
            WHERE
                requester_name LIKE ? 
            ORDER BY
                requester_name ASC";

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
                request_id LIKE ? 
            ORDER BY
                request_id DESC";

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

        $query = "SELECT request_id FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }

    public function countAllNotRead(){

        $query = "SELECT request_id FROM " . $this->table_name . " WHERE status = 0";

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
                    requester_name=:requester_name, requester_phone=:requester_phone , requester_location=:requester_location
                    , requester_identity_num=:requester_identity_num, bill_counter_num=:bill_counter_num
                    , identity_photo=:identity_photo, property_photo=:property_photo, request_type=:request_type";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->requester_name=htmlspecialchars(strip_tags($this->requester_name));
        $this->requester_phone=htmlspecialchars(strip_tags($this->requester_phone));
        $this->requester_location=htmlspecialchars(strip_tags($this->requester_location));
        $this->requester_identity_num=htmlspecialchars(strip_tags($this->requester_identity_num));
        $this->bill_counter_num=htmlspecialchars(strip_tags($this->bill_counter_num));
        $this->identity_photo=htmlspecialchars(strip_tags($this->identity_photo));
        $this->property_photo=htmlspecialchars(strip_tags($this->property_photo));
        $this->request_type=htmlspecialchars(strip_tags($this->request_type));

        // bind values
        $stmt->bindParam(":requester_name", $this->requester_name);
        $stmt->bindParam(":requester_phone", $this->requester_phone);
        $stmt->bindParam(":requester_location", $this->requester_location);
        $stmt->bindParam(":requester_identity_num", $this->requester_identity_num);
        $stmt->bindParam(":bill_counter_num", $this->bill_counter_num);
        $stmt->bindParam(":identity_photo", $this->identity_photo);
        $stmt->bindParam(":property_photo", $this->property_photo);
        $stmt->bindParam(":request_type", $this->request_type);


        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    function uploadPhotoIdentity()
    {

        $result_message = "";

        // now, if image is not empty, try to upload the image
        if ($this->identity_photo) {

            // sha1_file() function is used to make a unique file name
            $target_directory = "admin/uploads/";
            $target_file = $target_directory . $this->identity_photo;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error message is empty
            $file_upload_error_messages = "";

            // make sure that file is a real identity_photo
            $check = getimagesize($_FILES["identity_photo"]["tmp_name"]);
            if ($check !== false) {
                // submitted file is an identity_photo
            } else {
                $file_upload_error_messages .= "<div>Submitted file is not an identity_photo.</div>";
            }

// make sure certain file types are allowed
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
            }

// make sure file does not exist
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>identity_photo already exists. Try to change file name.</div>";
            }

// make sure submitted file is not too large, can't be larger than 1 MB
            if ($_FILES['identity_photo']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>identity_photo must be less than 1 MB in size.</div>";
            }

// make sure the 'uploads' folder exists
// if not, create it
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }
            // if $file_upload_error_messages is still empty
            if (empty($file_upload_error_messages)) {
                // it means there are no errors, so try to upload the file
                if (move_uploaded_file($_FILES["identity_photo"]["tmp_name"], $target_file)) {
                    // it means identity_photo was uploaded
                
                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "<div>Unable to upload identity_photo.</div>";
                    $result_message .= "<div>Update the record to upload identity_photo.</div>";
                    $result_message .= "</div>";
                }
            } // if $file_upload_error_messages is NOT empty
            else {
                // it means there are some errors, so show them to user
                $result_message .= "<div class='alert alert-danger'>";
                $result_message .= "{$file_upload_error_messages}";
                $result_message .= "<div>Update the record to upload identity_photo.</div>";
                $result_message .= "</div>";
            }
        }

    }
    
    function uploadPhotoProperty(){
        
        
        if ($this->property_photo) {

            // sha1_file() function is used to make a unique file name
            $target_directory = "admin/uploads/";
            $target_file = $target_directory . $this->property_photo;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error message is empty
            $file_upload_error_messages = "";

            // make sure that file is a real property_photo
            $check = getimagesize($_FILES["property_photo"]["tmp_name"]);
            if ($check !== false) {
                // submitted file is an property_photo
            } else {
                $file_upload_error_messages .= "<div>Submitted file is not an property_photo.</div>";
            }

// make sure certain file types are allowed
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
            }

// make sure file does not exist
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>property_photo already exists. Try to change file name.</div>";
            }

// make sure submitted file is not too large, can't be larger than 1 MB
            if ($_FILES['property_photo']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>property_photo must be less than 1 MB in size.</div>";
            }

// make sure the 'uploads' folder exists
// if not, create it
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }
            // if $file_upload_error_messages is still empty
            if (empty($file_upload_error_messages)) {
                // it means there are no errors, so try to upload the file
                if (move_uploaded_file($_FILES["property_photo"]["tmp_name"], $target_file)) {
                    // it means property_photo was uploaded

                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "<div>Unable to upload property_photo.</div>";
                    $result_message .= "<div>Update the record to upload property_photo.</div>";
                    $result_message .= "</div>";
                }
            } // if $file_upload_error_messages is NOT empty
            else {
                // it means there are some errors, so show them to user
                $result_message .= "<div class='alert alert-danger'>";
                $result_message .= "{$file_upload_error_messages}";
                $result_message .= "<div>Update the record to upload property_photo.</div>";
                $result_message .= "</div>";
            }
        }
    }
    
    function updateStatus($id){
        $query = "Update ". $this->table_name ." SET status = 1 WHERE request_id = " . $id ;

        $stmt = $this->conn->prepare($query);



        if($stmt->execute()){
            return true;
        }

        return false;

//        var_dump($stmt);
    }

    function updatePrinted($id){
        $query = "Update ". $this->table_name ." SET printed = 1 WHERE request_id = " . $id ;

        $stmt = $this->conn->prepare($query);


        if($stmt->execute()){
            return true;
        }

        return false;

//        var_dump($stmt);
    }
}
