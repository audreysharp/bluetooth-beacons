<?php

// Get request
$request = $_POST;

// Create response array
$response = array('function' => 'getRosterAttendance', 'code' => -1, 'result' => array());

// Include Database handler
require_once 'include/DBFunctions.php';
$db = new DBFunctions();

// Check-in request paramters
$department = $request['department'];
$number = $request['number'];
$section = $request['section'];

$result = $db->getRosterAttendance($department, $number, $section);

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
