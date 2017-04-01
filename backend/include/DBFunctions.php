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
	public function addCheckIn($onyen, $role, $course_dept, $course_num, $course_sec) {
		$db = $this->__construct();

		$query_id = $db->query("SELECT sno FROM courses WHERE department = '$course_dept' AND number = '$course_num' AND section = '$course_sec'") or die(mysqli_error());
		if($query_id && $query_id->num_rows > 0){
			$course_id = $course_id->fetch_array();
			$query = $db->query("INSERT INTO attendance(onyen, course_id), timestamp) VALUES('$onyen', '$course_id', CURRENT_TIMESTAMP())") or die(mysqli_error());
			if($query) {
				// Successful insert
				$result['code'] = 0;
				$query = $db->query("SELECT * FROM attendance WHERE onyen = '$onyen'") or die(mysqli_error());
				$record = $query->fetch_array(MYSQLI_ASSOC);
				$result['record'] = $record;
			} else {
				// Insert failed
				$result['code'] = 1;
			}
		} else {
			// Course doesn't exist
			$result['code'] = 2;
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
