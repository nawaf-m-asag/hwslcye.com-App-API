<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; Accept: application/json;charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate product object class Request

include_once '../objects/bill.php';
  
     $database = new database();
     $db = $database->getConnection();
      
     $Bill = new bill($db);
  
    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // set  property values
  

   $Bill->readOne($data->agreementNumber);
    
class BillData{
           
  public $agreementNumber;
  public $branchNumber;
  public $subscriberName;
  public $counterNumber;
  public $periodNumber;
  public $roundNumber;
  public $moneyAmount;


     }
      $BillData->agreementNumber= $Bill->BRANCH_ID;
   $BillData->branchNumber= $Bill->BRANCH_ID;
   $BillData->subscriberName= $Bill->CUSTOMER_NAME;
   $BillData->counterNumber= $Bill->METER_SERIAL_NO;
   $BillData->periodNumber= $Bill->PERIOD;
   $BillData->roundNumber= $Bill->CYCLE_NO;
   $BillData->moneyAmount= $Bill->AMOUNT;
     
if($BillData->agreementNumber!=null){
    echo json_encode(array( $BillData ),200);
}
else
{
    echo json_encode(array("Bill" => "No bill data"),500);
}

  
       
    
     
    


    

?>