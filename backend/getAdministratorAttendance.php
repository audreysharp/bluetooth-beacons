<?php

// Get request
$request = $_POST;

// Create response array
$response = array('function' => 'getAdministratorAttendance', 'code' => -1, 'result' => array());

// Include Database handler
require_once 'include/DBFunctions.php';
$db = new DBFunctions();

// Check-in request paramters
$onyen = $request['onyen'];

// Verify and store credentials
$result = $db->getAdministratorAttendance($onyen);

// Begin creating response
$code = $result['code'];
$response['code'] = $code;
if($code == 0) {
  // Create successful retrieval response
  $response['message'] = 'Attendance retrieval successful';
  $response['result'] = $result['records'];
} else {
  if($code == 5) {
    $response['message'] = 'Couldn\'t find any records';
  }
}

// Return response
echo json_encode($response);
