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

	// Add a check in record
	public function addCheckIn($onyen, $course) {
		$db = $this->__construct();
		$query = $db->query("INSERT INTO attendance(onyen, course, timestamp) VALUES('$onyen', '$course', CURRENT_TIMESTAMP())") or die(mysqli_error());
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

}
