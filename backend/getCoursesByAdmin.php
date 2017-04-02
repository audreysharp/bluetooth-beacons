<?php

// Get request
$request = $_POST;

// Create response array
$response = array('function' => 'getCoursesByAdmin', 'code' => -1, 'result' => array());

// Include Database handler
require_once 'include/DBFunctions.php';
$db = new DBFunctions();

// Check-in request paramters
$creator = $request['creator'];

// Verify and store credentials
$result = $db->getCoursesByAdmin($creator);

// Begin creating response
$code = $result['code'];
$response['code'] = $code;
if($code == 0) {
  // Create successful retrieval response
  $response['message'] = 'Course retrieval successful';
  $response['result'] = $result['records'];
} else {
  if($code == 5) {
    $response['message'] = 'Couldn\'t find any records';
  }
}

// Return response
echo json_encode($response);
