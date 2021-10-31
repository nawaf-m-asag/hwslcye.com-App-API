<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate product object class Request

include_once '../objects/complain.php';
  
     $database = new database();
     $db = $database->getConnection();
      
     $Complain = new complain($db);
  
    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // set  property values
    $Complain->com_id= $data->id;
    $Complain->com_name= $data->submitterName;
    $Complain->com_phone = $data->phoneNo;
    $Complain->com_subject = $data->subject;
    $Complain->complain = $data->complaintText;
    $Complain->com_type = $data->complaintType;
    $Complain->com_cat = $data->complain;
    $Complain->com_title = $data->address;
    $Complain->agreement_num = $data->agreementNo;
    $Complain->administration = $data->management;
     $Complain->status = $data->status;
    $Complain->created = $data->addedDate;
     
     if($Complain->create()){

    
     echo json_encode(array("message" => "The data has been sent successfully" ));
       
     }
     else{
          echo json_encode(array("message" => "The data dont   sent " ));
       
     }
     
    


    

?>