<?php

// Get request
$request = $_POST;

// Create response array
$response = array('function' => 'register', 'code' => -1, 'result' => array());

// Include Database handler
require_once 'include/DBFunctions.php';
$db = new DBFunctions();

//Register request paramters

$firstName = $request['firstName'];
$lastName = $request['lastName'];
$onyen = $request['onyen'];
$pid = $request['pid'];
$password = $request['password'];

// Verify and store credentials
$result = $db->getStudentByRegister($firstName, $lastName, $onyen, $pid, $password);

// Begin creating response
$code = $result['code'];
$response['code'] = $code;
if($code == 0) {
  // Create successful register response
  $response['message'] = 'Registration Successful';
  $response['result'] = $result['student'];
} else {
  if($code == 4) {
    // Response with email already exists
    $response['message'] = 'Email already being used';
  } else if($code == 5) {
    $response['message'] = 'Couldn\'t store user';
  }
}

// Return response
echo json_encode($response);
