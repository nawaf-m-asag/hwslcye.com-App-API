<?php
class Slider
{

    // database connection and table name
    private $conn;
    private $table_name = "slider";

    // object properties
    public $slider_id;
    public $slider_title;
    public $slider_subtitle;
    public $slider_photo;
    public $photo_num;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function readAll()
    {

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY slider_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    public function countAll()
    {

        // query to select all user records
        $query = "SELECT slider_id FROM " . $this->table_name . "";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // return row count
        return $num;
    }

    function create()
    {

        //write query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    slider_title=:slider_title, slider_subtitle=:slider_subtitle,slider_photo=:slider_photo,photo_num=:photo_num";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->slider_title = htmlspecialchars(strip_tags($this->slider_title));
        $this->slider_subtitle = htmlspecialchars(strip_tags($this->slider_subtitle));
        $this->slider_photo = htmlspecialchars(strip_tags($this->slider_photo));
        $this->photo_num = htmlspecialchars(strip_tags($this->photo_num));


        // bind values
        $stmt->bindParam(":slider_title", $this->slider_title);
        $stmt->bindParam(":slider_subtitle", $this->slider_subtitle);
        $stmt->bindParam(":slider_photo", $this->slider_photo);
        $stmt->bindParam(":photo_num", $this->photo_num);


        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }

    function uploadPhoto()
    {

        $result_message = "";

        // now, if image is not empty, try to upload the image
        if ($this->slider_photo) {

            // sha1_file() function is used to make a unique file name
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->slider_photo;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error message is empty
            $file_upload_error_messages = "";

            // make sure that file is a real slider_photo
            $check = getimagesize($_FILES["slider_photo"]["tmp_name"]);
            if ($check !== false) {
                // submitted file is an slider_photo
            } else {
                $file_upload_error_messages .= "<div>Submitted file is not an slider_photo.</div>";
            }

// make sure certain file types are allowed
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
            }

// make sure file does not exist
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>slider_photo already exists. Try to change file name.</div>";
            }

// make sure submitted file is not too large, can't be larger than 1 MB
            if ($_FILES['slider_photo']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>slider_photo must be less than 1 MB in size.</div>";
            }

// make sure the 'uploads' folder exists
// if not, create it
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }
            // if $file_upload_error_messages is still empty
            if (empty($file_upload_error_messages)) {
                // it means there are no errors, so try to upload the file
                if (move_uploaded_file($_FILES["slider_photo"]["tmp_name"], $target_file)) {
                    // it means photo was uploaded
                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "<div>Unable to upload photo.</div>";
                    $result_message .= "<div>Update the record to upload photo.</div>";
                    $result_message .= "</div>";
                }
            } // if $file_upload_error_messages is NOT empty
            else {
                // it means there are some errors, so show them to user
                $result_message .= "<div class='alert alert-danger'>";
                $result_message .= "{$file_upload_error_messages}";
                $result_message .= "<div>Update the record to upload photo.</div>";
                $result_message .= "</div>";
            }
        }

    }

    function readOne(){

        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE
                slider_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->slider_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->slider_title = $row['slider_title'];
        $this->slider_subtitle = $row['slider_subtitle'];
        $this->slider_photo = $row['slider_photo'];
        $this->photo_num = $row['photo_num'];

    }

    function update($id, $title , $subtitle  , $photo_num , $photo){

        $query = "UPDATE " . $this->table_name . " SET slider_title = '". $title ."', slider_subtitle = '".
            $subtitle ."',slider_photo = '". $photo ."', photo_num  = '". $photo_num ."' WHERE slider_id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->slider_title=htmlspecialchars(strip_tags($this->slider_title));
        $this->slider_subtitle=htmlspecialchars(strip_tags($this->slider_subtitle));
        $this->slider_photo=htmlspecialchars(strip_tags($this->slider_photo));
        $this->photo_num=htmlspecialchars(strip_tags($this->photo_num));
        $this->slider_id=htmlspecialchars(strip_tags($this->slider_id));


        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;


    }

    function delete($id){

        $query = "DELETE FROM " . $this->table_name . " WHERE slider_id = $id";

        $stmt = $this->conn->prepare($query);


        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    function readForWeb()
    {

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY photo_num ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

}
?>