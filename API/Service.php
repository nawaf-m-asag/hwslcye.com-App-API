<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';
  
// instantiate product object class Request

include_once '../objects/post.php';
  
     $database = new database();
     $db = $database->getConnection();
      
     $post = new Post($db);
    
    
 

  $stmt = $post->readAllForApp();


  while ($row = $stmt->fetchAll(PDO::FETCH_CLASS)){
      
  echo json_encode($row);
      
  }
 
 
 
     ?>