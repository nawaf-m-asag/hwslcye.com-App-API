<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate product object class Request

include_once '../objects/request.php';
  
     $database = new database();
     $db = $database->getConnection();
      
     $Request = new request($db);
  
    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // set  property values
    $Request->requester_name= $data->subscriberName;
    $Request->requester_phone = $data->phoneNumber;
    $Request->requester_location = $data->address;
    $Request->requester_identity_num = $data->personalCardId;
    $Request->bill_counter_num = $data->billNumberNearestCounter;
    $identity_photo= $data->imageOfPersonalCard;
    $property_photo= $data->imageOfRealStateRent;
   // $Request->request_type=$data->request_type;
            $Request->request_type = "اشتراك مياه";

    
    
     if($identity_photo){ $Request->identity_photo =uploadImage($identity_photo,$type="identity_photo");}
     if($property_photo){    $Request->property_photo =uploadImage($property_photo,$type="property_photo");}
     
     if($Request->create()){

    
     echo json_encode(array("message" => "The data has been sent successfully" ));
    
     }
     
     function uploadImage($image,$type)
    {

    
          
        $target_dir = "../admin/uploads/";
        
        $t=time();
        $realImage =base64_decode($image);
        $image_name=$type.$t.".png";
        $image_target_dir=$target_dir.$image_name;
        $image_send=file_put_contents($image_target_dir, $realImage); 
        if($image_send)
         return $image_name;  
        else 
        {
          
                http_response_code(400);
                echo json_encode(array("message" => "Unable to lod image. Data is incomplete."));
          
        }



    }

?>