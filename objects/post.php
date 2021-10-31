<?php

Class Post{

    private $conn;
    private $table_name = "posts";

    // object properties
    public $post_id;
    public $post_title;
    public $post_body;
    public $category_id;
    public $image;
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
            ORDER BY post_id DESC";

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
                    post_title=:post_title, post_body=:post_body, category_id=:category_id, image=:image";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->post_title=htmlspecialchars(strip_tags($this->post_title));
        $this->post_body=htmlspecialchars(strip_tags($this->post_body));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
//        $this->image=htmlspecialchars(strip_tags($this->image));




        // bind values
        $stmt->bindParam(":post_title", $this->post_title);
        $stmt->bindParam(":post_body", $this->post_body);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":image", $this->image);


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
                post_id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->post_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->post_title = $row['post_title'];
        $this->post_body = $row['post_body'];
        $this->category_id = $row['category_id'];
        $this->image = $row['image'];
    }

    function update($id, $title , $body , $cat , $image){

        $query = "UPDATE " . $this->table_name . " SET post_title = '". $title ."', post_body = '".
            $body ."',category_id = '". $cat ."', image  = '". $image ."' WHERE post_id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->post_title=htmlspecialchars(strip_tags($this->post_title));
        $this->post_body=htmlspecialchars(strip_tags($this->post_body));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->image=htmlspecialchars(strip_tags($this->image));
        $this->id=htmlspecialchars(strip_tags($this->post_id));


        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;

        // return var_dump($stmt);
    }

    // will upload image file to server
    function uploadPhoto()
    {

        $result_message = "";

        // now, if image is not empty, try to upload the image
        if ($this->image) {

            // sha1_file() function is used to make a unique file name
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->image;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error message is empty
            $file_upload_error_messages = "";

            // make sure that file is a real user_img
            $check = getimagesize($_FILES["image"]["tmp_name"]);
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
            if ($_FILES['image']['size'] > (1024000)) {
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
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
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

    function delete($id){

        $query = "DELETE FROM " . $this->table_name . " WHERE post_id = $id";

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
                post_title LIKE ? 
            ORDER BY
                post_title ASC";

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

    function readAllForWeb(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . "  WHERE (SELECT cat_id FROM categories WHERE cat_name = \"توعية\") = category_id
            OR  (SELECT cat_id FROM categories WHERE cat_name = \"انشطة وفعاليات\" ) = category_id
            OR  (SELECT cat_id FROM categories WHERE cat_name = \"المناقصات\" ) = category_id
            ORDER BY post_id DESC
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
        $query = "SELECT 
        post_title as title ,
    post_body as details,
   
    image as imageUrl 
            FROM " . $this->table_name . "  WHERE (SELECT cat_id FROM categories WHERE cat_name = \"توعية\") = category_id
            OR  (SELECT cat_id FROM categories WHERE cat_name = \"انشطة وفعاليات\" ) = category_id
            OR  (SELECT cat_id FROM categories WHERE cat_name = \"المناقصات\" ) = category_id
            ORDER BY post_id DESC
            LIMIT 6";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    function readAwarness(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . " WHERE (SELECT cat_id FROM categories WHERE cat_name = \"توعية\") = category_id
            ORDER BY post_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    function readEvents(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . " WHERE (SELECT cat_id FROM categories WHERE cat_name = \"انشطة وفعاليات\" ) = category_id
            ORDER BY post_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
    
    function readTerder(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . " WHERE (SELECT cat_id FROM categories WHERE cat_name = \"المناقصات\" ) = category_id
            ORDER BY post_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
    
     function readNews(){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT *
            FROM " . $this->table_name . " WHERE (SELECT cat_id FROM categories WHERE cat_name = \"شريط الاخبار\" ) = category_id
            ORDER BY post_id ";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables


        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }
    
    public function countAll(){

        $query = "SELECT post_id FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }
}


?>
