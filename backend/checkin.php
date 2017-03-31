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
$role = $request['role'];
$course_dept = $request['department'];
$course_num = $request['number'];
$course_sec = $request['section'];

// Verify and store credentials
$result = $db->addCheckIn($onyen, $role, $course_dept, $course_num, $course_sec);

// Begin creating response
$code = $result['code'];
$response['code'] = $code;
if($code == 0) {
  // Create successful checkin response
  $response['message'] = 'Check-in Successful';
  $response['result'] = $result['record'];
} else {
  if($code == 1) {
    $response['message'] = 'Couldn\'t check in';
  }
  if($code == 2) {
    $response['message'] = 'Course doesn\'t exist';
  }
}

// Return response
echo json_encode($response);
