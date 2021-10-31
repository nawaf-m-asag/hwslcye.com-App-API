<?php

Class Word{

    private $conn;
    private $table_name = "word";

    // object properties
    public $word_id;
    public $content;
    public $manager_name;
    public $photo;
    

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readAll(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY word_id DESC";

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
                word_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->word_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->content = $row['content'];
        $this->manager_name = $row['manager_name'];
        $this->photo = $row['photo'];
        
    }
    
    function update($id, $content ,$manager_name , $photo){

        $query = "UPDATE " . $this->table_name . " SET content = '". $content ."' , manager_name = '". $manager_name ."' , photo = '". $photo ."' WHERE word_id = $id";

        $stmt = $this->conn->prepare($query);

    
        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
        
        // var_dump($stmt);

    }
    
     function uploadPhoto()
    {

        $result_message = "";

        // now, if image is not empty, try to upload the image
        if ($this->photo) {

            // sha1_file() function is used to make a unique file name
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->photo;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error message is empty
            $file_upload_error_messages = "";

            // make sure that file is a real user_img
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if ($check !== false) {
                // submitted file is an user_img
            } else {
                $file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
            }

// make sure certain file types are allowed
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
            }

// make sure file does not exist
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>image already exists. Try to change file name.</div>";
            }

// make sure submitted file is not too large, can't be larger than 1 MB
            if ($_FILES['photo']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>image must be less than 1 MB in size.</div>";
            }

// make sure the 'uploads' folder exists
// if not, create it
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }
            // if $file_upload_error_messages is still empty
            if (empty($file_upload_error_messages)) {
                // it means there are no errors, so try to upload the file
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    // it means user_img was uploaded

                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "<div>Unable to upload image.</div>";
                    $result_message .= "<div>Update the record to upload image.</div>";
                    $result_message .= "</div>";
                }
            } // if $file_upload_error_messages is NOT empty
            else {
                // it means there are some errors, so show them to user
                $result_message .= "<div class='alert alert-danger'>";
                $result_message .= "{$file_upload_error_messages}";
                $result_message .= "<div>Update the record to upload image.</div>";
                $result_message .= "</div>";
            }
        }

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
        $this->manager_name = $row['manager_name'];
        $this->photo = $row['photo'];
        
    }
}
?>