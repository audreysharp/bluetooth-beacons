<?php

// Get request
$request = $_POST;

// Create response array
$response = array('function' => 'checkin', 'code' => -1, 'result' => array());

// Include Database handler
require_once 'include/DBFunctions.php';
$db = new DBFunctions();

//Check-in request paramters
$onyen = $request['onyen'];
$course = $request['course'];

// Verify and store credentials
$result = $db->addCheckIn($onyen, $course);

// Begin creating response
$code = $result['code'];
$response['code'] = $code;
if($code == 0) {
  // Create successful checkin response
  $response['message'] = 'Check-in Successful';
  $response['result'] = $result['record'];
} else {
  if($code == 5) {
    $response['message'] = 'Couldn\'t store user';
  }
}

// Return response
echo json_encode($response);
