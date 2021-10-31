<?php
Class Project{

    private $conn;
    private $table_name = "projects";

    // object properties
    public $pro_id;
    public $pro_title;
    public $pro_description;
    public $pro_photo;
    public $cat_id;
    public $created;
    public $updated;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readAll($from_record_num, $records_per_page){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY pro_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
    
    function create()
    {

        //write query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    pro_title=:pro_title, pro_description=:pro_description,pro_photo=:pro_photo,cat_id=:cat_id";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->pro_title = htmlspecialchars(strip_tags($this->pro_title));
        $this->pro_description = htmlspecialchars(strip_tags($this->pro_description));
        $this->pro_photo = htmlspecialchars(strip_tags($this->pro_photo));
        $this->cat_id = htmlspecialchars(strip_tags($this->cat_id));


        // bind values
        $stmt->bindParam(":pro_title", $this->pro_title);
        $stmt->bindParam(":pro_description", $this->pro_description);
        $stmt->bindParam(":pro_photo", $this->pro_photo);
        $stmt->bindParam(":cat_id", $this->cat_id);


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
        if ($this->pro_photo) {

            // sha1_file() function is used to make a unique file name
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->pro_photo;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error message is empty
            $file_upload_error_messages = "";

            // make sure that file is a real photo
//            $check = getimagesize($_FILES["photo"]["tmp_name"]);
//            if ($check !== false) {
//                // submitted file is an photo
//            } else {
//                $file_upload_error_messages .= "<div>Submitted file is not an photo.</div>";
//            }

// make sure certain file types are allowed
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
            }

// make sure file does not exist
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>photo already exists. Try to change file name.</div>";
            }

// make sure submitted file is not too large, can't be larger than 1 MB
            if ($_FILES['pro_photo']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>photo must be less than 1 MB in size.</div>";
            }

// make sure the 'uploads' folder exists
// if not, create it
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }
            // if $file_upload_error_messages is still empty
            if (empty($file_upload_error_messages)) {
                // it means there are no errors, so try to upload the file
                if (move_uploaded_file($_FILES["pro_photo"]["tmp_name"], $target_file)) {
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
                pro_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->pro_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->pro_title = $row['pro_title'];
        $this->pro_description = $row['pro_description'];
        $this->pro_photo = $row['pro_photo'];
        $this->cat_id = $row['cat_id'];

    }
    
    function update($id, $title , $description  , $cat_id, $photo){

        $query = "UPDATE " . $this->table_name . " SET pro_title = '". $title ."', pro_description = '".
            $description ."',pro_photo = '". $photo ."', cat_id  = '". $cat_id ."' WHERE pro_id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->pro_title=htmlspecialchars(strip_tags($this->pro_title));
        $this->pro_description=htmlspecialchars(strip_tags($this->pro_description));
        $this->pro_photo=htmlspecialchars(strip_tags($this->pro_photo));
        $this->cat_id=htmlspecialchars(strip_tags($this->cat_id));
        $this->pro_id=htmlspecialchars(strip_tags($this->pro_id));


        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }
    
    function delete($id){

        $query = "DELETE FROM " . $this->table_name . " WHERE pro_id = $id";

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
                pro_title LIKE ? 
            ORDER BY
                pro_title ASC";

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
    function readCompleted(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . " WHERE cat_id = (SELECT cat_id FROM categories WHERE cat_name = \"مشاريع منجزة\") 
            ORDER BY pro_id DESC
            LIMIT 6";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    function readInProgress(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . " WHERE (SELECT cat_id FROM categories WHERE cat_name = \"مشاريع قيد الانجاز\") = cat_id
            ORDER BY pro_id DESC
            LIMIT 6";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    function readFuture(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . " WHERE (SELECT cat_id FROM categories WHERE cat_name = \"مشاريع مستقبلية\") = cat_id
            ORDER BY pro_id DESC
            LIMIT 6";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
    
    function readAllForWeb(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "
            ORDER BY pro_id DESC
            LIMIT 6";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
      function readAllForApp(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT p.pro_title title ,p.pro_description details,p.pro_photo imageUrl,c.cat_name projectType
        
            FROM projects p,categories c
            where p.cat_id=c.cat_id
            ORDER BY pro_id DESC
            LIMIT 6";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
}
?>