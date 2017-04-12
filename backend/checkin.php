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
$beaconID = $request['beaconID'];
if($role == 'instructor') {
  $course_dept = $request['department'];
  $course_num = $request['number'];
  $course_sec = $request['section'];
  $result = $db->addInstructorCheckIn($onyen, $role, $beaconID, $course_dept, $course_num, $course_sec);
} else {
  $result = $db->addStudentCheckIn($onyen, $role, $beaconID);
}

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
  if($code == 3) {
    $response['message'] = 'Beacon already being used';
  }
}

// Return response
echo json_encode($response);
