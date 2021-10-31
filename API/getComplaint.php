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
include_once '../objects/response.php';
        
     $database = new database();
     $db = $database->getConnection();
  
      
     $Complain = new complain($db);
     $Response = new response($db);
    // get posted data
    $data = json_decode(file_get_contents("php://input"));

   
    // set  property values
    $row=[];
    if(isset($data->agreement_num)&&$data->agreement_num!=null){
        $row=$Complain->readWithAgreeNumForApp($data->agreement_num);
    }
   
    else if(isset($data->com_phone)&&$data->com_phone!=null){

        $row=$Complain->readWithPhoneNumForApp($data->com_phone); 
    }
$ComplainData=[];
foreach ($row as $key=> $value) {
       
  $ComplainData[$key]['id']  = $value->com_id;
  $ComplainData[$key]['submitterName'] = $value->com_name;
  $ComplainData[$key]['phoneNo']=$value->com_phone;
  $ComplainData[$key]['subject']= $value->com_subject;
  $ComplainData[$key]['address']= $value->com_title;
  $ComplainData[$key]['complaintText'] =$value->complain;
  $ComplainData[$key]['complaintType'] = $value->com_type;
  $ComplainData[$key]['complain']= $value->com_cat;
  $ComplainData[$key]['agreementNo']= $value->agreement_num;
  $ComplainData[$key]['management']= $value->administration;
  $ComplainData[$key]['status']= $value->status;
  $ComplainData[$key]['addedDate']= $value->created;
  $ComplainData[$key]['response']= $Response->readWithComIDForApp($value->com_id); 
   }

   
    if($ComplainData!=null){
        echo json_encode(["complaints"=>$ComplainData],200);
    }
    else
    {
        echo json_encode(array("complaints" => "No complain data"),500);
    }

  
      

?>