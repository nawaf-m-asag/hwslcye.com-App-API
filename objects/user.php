<?php
// 'user' object
class User{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $contact_number;
    public $address;
    public $user_img;
    public $password;
    public $access_level;
    public $access_code;
    public $status;
    public $created;
    public $modified;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // check if given email exist in the database
    function emailExists(){

        // query to check if email exists
        $query = "SELECT id, firstname, lastname, access_level, password, status,user_img
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));

        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign values to object properties
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->access_level = $row['access_level'];
            $this->password = $row['password'];
            $this->status = $row['status'];
            $this->user_img = $row['user_img'];

            // return true because email exists in the database
            return true;
        }

        // return false if email does not exist in the database
        return false;
    }


    // create new user record
    function create($fname , $lname , $email , $con , $acc , $add , $img , $pass , $stat){

        // to get time stamp for 'created' field
        $created=date('Y-m-d H:i:s');
        $password_hash = password_hash($pass, PASSWORD_BCRYPT);
        // insert query
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                firstname = '". $fname."',lastname = '". $lname."',  email = '". $email."', contact_number = '". $con."',
                address = '". $add."', user_img = '". $img."', password = '". $password_hash."', access_level = '". $acc."',
                status = '". $stat."',  created = '". $created ."'";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->user_img=htmlspecialchars(strip_tags($this->user_img));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->access_level=htmlspecialchars(strip_tags($this->access_level));
        $this->access_code=htmlspecialchars(strip_tags($this->access_code));
        $this->status=htmlspecialchars(strip_tags($this->status));

        // bind the values
        $stmt->bindParam(':created', $this->created);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }else{
            $this->showError($stmt);
            return false;
        }



    }

    public function showError($stmt){
        echo "<pre>";
        print_r($stmt->errorInfo());
        echo "</pre>";
    }



    function readAll($from_record_num, $records_per_page){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                id,
                firstname,
                lastname,
                email,
                contact_number,
                address,
                user_img,
                access_level,
                status,
                created
            FROM " . $this->table_name . "
            ORDER BY id DESC   ";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );




        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    // check if given access_code exist in the database
    function accessCodeExists(){

        // query to check if access_code exists
        $query = "SELECT id
            FROM " . $this->table_name . "
            WHERE access_code = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->access_code=htmlspecialchars(strip_tags($this->access_code));

        // bind given access_code value
        $stmt->bindParam(1, $this->access_code);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if access_code exists
        if($num>0){

            // return true because access_code exists in the database
            return true;
        }

        // return false if access_code does not exist in the database
        return false;

    }

    // used in email verification feature
    function updateStatusByAccessCode(){

        // update query
        $query = "UPDATE " . $this->table_name . "
            SET status = :status
            WHERE access_code = :access_code";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->access_code=htmlspecialchars(strip_tags($this->access_code));

        // bind the values from the form
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':access_code', $this->access_code);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // used in forgot password feature
    function updatePassword(){

        // update query
        $query = "UPDATE " . $this->table_name . "
            SET password = :password
            WHERE access_code = :access_code";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->access_code=htmlspecialchars(strip_tags($this->access_code));

        // bind the values from the form
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':access_code', $this->access_code);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // used in forgot password feature
    function updateAccessCode(){

        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                access_code = :access_code
            WHERE
                email = :email";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->access_code=htmlspecialchars(strip_tags($this->access_code));
        $this->email=htmlspecialchars(strip_tags($this->email));

        // bind the values from the form
        $stmt->bindParam(':access_code', $this->access_code);
        $stmt->bindParam(':email', $this->email);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;

    }

    function readOne(){

        $query = "SELECT
                firstname, lastname, email, contact_number,address,user_img,access_level,status,password
            FROM
                " . $this->table_name . "
            WHERE
                id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->email = $row['email'];
        $this->contact_number = $row['contact_number'];
        $this->address = $row['address'];
        $this->user_img = $row['user_img'];
        $this->access_level = $row['access_level'];
        $this->status = $row['status'];
    }

    function update($id, $fname , $lname , $email , $contact , $address, $access , $status , $img){

        $query = "UPDATE " . $this->table_name . " SET firstname = '". $fname ."', lastname = '".
            $lname ."',email = '". $email ."', contact_number  = ". $contact .", address  = '". $address . "', user_img  = '". $img . "',
                access_level  = '". $access . "',  status  = ". $status . " WHERE id = $id";

        $stmt = $this->conn->prepare($query);

        // posted values

        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->user_img=htmlspecialchars(strip_tags($this->user_img));
        $this->access_level=htmlspecialchars(strip_tags($this->access_level));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind parameters




        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;



    }

    function delete(){

        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

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
                firstname LIKE ? 
            ORDER BY
                firstname ASC";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind variable values
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
//        $stmt->bindParam(2, $search_term);
//        $stmt->bindParam(3, $search_term);


        // execute query
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    public function countAll_BySearch($search_term){

        // select query
        $query = "SELECT
                COUNT(*) as total_rows
            FROM
                " . $this->table_name . "
            WHERE
                firstname LIKE ? OR lastname LIKE ? OR email LIKE ? ";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind variable values
        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $search_term);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    function uploadPhoto()
    {

        $result_message = "";

        // now, if image is not empty, try to upload the image
        if ($this->user_img) {

            // sha1_file() function is used to make a unique file name
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->user_img;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error message is empty
            $file_upload_error_messages = "";

            // make sure that file is a real user_img
            $check = getimagesize($_FILES["user_img"]["tmp_name"]);
            if ($check !== false) {
                // submitted file is an user_img
            } else {
                $file_upload_error_messages .= "<div>Submitted file is not an user_img.</div>";
            }

// make sure certain file types are allowed
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
            }

// make sure file does not exist
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>user_img already exists. Try to change file name.</div>";
            }

// make sure submitted file is not too large, can't be larger than 1 MB
            if ($_FILES['user_img']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>user_img must be less than 1 MB in size.</div>";
            }

// make sure the 'uploads' folder exists
// if not, create it
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }
            // if $file_upload_error_messages is still empty
            if (empty($file_upload_error_messages)) {
                // it means there are no errors, so try to upload the file
                if (move_uploaded_file($_FILES["user_img"]["tmp_name"], $target_file)) {
                    // it means user_img was uploaded

                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "<div>Unable to upload user_img.</div>";
                    $result_message .= "<div>Update the record to upload user_img.</div>";
                    $result_message .= "</div>";
                }
            } // if $file_upload_error_messages is NOT empty
            else {
                // it means there are some errors, so show them to user
                $result_message .= "<div class='alert alert-danger'>";
                $result_message .= "{$file_upload_error_messages}";
                $result_message .= "<div>Update the record to upload user_img.</div>";
                $result_message .= "</div>";
            }
        }

    }

    function readName($id){

        $query = "SELECT firstname FROM " . $this->table_name . " WHERE id = $id limit 0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->firstname = $row['firstname'];
    }
}
?>