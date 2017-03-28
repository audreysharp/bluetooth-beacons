<?php

class DBFunctions {

	// constructor
	function __construct() {
		require_once 'DBConnect.php';

		// connecting to database
		$db = new DBConnect();
		$db = $db->connect();
		return $db;
	}

	// destructor
	function __destruct() {

	}

	// Registers user
	public function getStudentByRegister($firstName, $lastName, $onyen, $pid, $password){
		$db = $this->__construct();
		$uuid = uniqid('', true);
		$hash = $this->hashSSHA($password);
		$encrypted_password = $hash["encrypted"];
		$salt = $hash["salt"];

		// Verify email doesn't exist in DB
		$exists = $db->query("SELECT * FROM students WHERE pid = '$pid'") or die(mysqli_error());
		if($exists->num_rows > 0) {
			// Email exists
			$result['code'] = 4;
		} else {
			// Email doesn't exist so attempt insert
			$query = $db->query("INSERT INTO students(pid, firstName, lastName, onyen, encrypted_password, salt, created_at) VALUES('$pid', '$firstName', '$lastName', '$onyen', '$encrypted_password', '$salt', NOW())") or die(mysqli_error());
			if($query) {
				// Successful insert
				$result['code'] = 0;
				$sno = $db->insert_id;
				$query = $db->query("SELECT * FROM students WHERE sno = $sno") or die(mysqli_error());
				$student = $query->fetch_array(MYSQLI_ASSOC);
				$result['student'] = $student;
			} else {
				// Insert failed
				$result['code'] = 5;
			}

		}
		return $result;
	}

	// Add a check in record
	public function addCheckIn($onyen, $course) {
		$db = $this->__construct();
		$query = $db->query("INSERT INTO attendance(onyen, course, timestamp) VALUES('$onyen', '$course', NOW())") or die(mysqli_error());
		if($query) {
			// Successful insert
			$result['code'] = 0;
			$query = $db->query("SELECT * FROM attendance WHERE onyen = '$onyen'") or die(mysqli_error());
			$record = $query->fetch_array(MYSQLI_ASSOC);
			$result['record'] = $record;
		} else {
			// Insert failed
			$result['code'] = 5;
		}
		return $result;
	}

	// Get all student attendance record
	public function getAttendance($onyen) {
		$db = $this->__construct();

		$query = $db->query("SELECT * FROM attendance WHERE onyen = '$onyen'") or die(mysqli_error());
		if($query) {
			$result['code'] = 0;
			$records = $query->fetch_all(MYSQLI_ASSOC);
			$result['records'] = $records;
		}
		return $result;
	}

	/**
	* Encrypting password
	* returns salt and encrypted password
	*/
	public function hashSSHA($password) {

		$salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
		$hash = array("salt" => $salt, "encrypted" => $encrypted);
		return $hash;
	}

	/**
	* Decrypting password
	* returns hash string
	*/
	public function checkhashSSHA($salt, $password) {

		$hash = base64_encode(sha1($password . $salt, true) . $salt);

		return $hash;
	}

}
