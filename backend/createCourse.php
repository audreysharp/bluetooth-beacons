<?php

// Get request
$request = $_POST;

// Create response array
$response = array('function' => 'createCourse', 'code' => -1, 'result' => array());

// Include Database handler
require_once 'include/DBFunctions.php';
$db = new DBFunctions();

//Check-in request paramters
$department = $request['department'];
$number = $request['number'];
$section = $request['section'];
$creator = $request['creator'];

$result = $db->addCourse($department, $number, $section, $creator);

// Begin creating response
$code = $result['code'];
$response['code'] = $code;
if($code == 0) {
  // Create successful course creation response
  $response['message'] = 'Course Creation Successful';
  $response['result'] = $result['record'];
} else {
  if($code == 1) {
    $response['message'] = 'Couldn\'t create course';
  }
}

// Return response
echo json_encode($response);
