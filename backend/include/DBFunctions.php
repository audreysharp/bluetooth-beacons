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

		$query_id = $db->query("SELECT sno FROM courses WHERE department = '$course_dept' AND number = '$course_num' AND section = '$course_sec'") or die(mysqli_error($db));
		if($query_id && $query_id->num_rows > 0){
			$course_id = $query_id->fetch_assoc()['sno'];
			if($role == "teacher") {
				$openTime = date("Y-m-d H:i:s", strtotime("now"));
				$closedTime = date("Y-m-d H:i:s", strtotime("+10 minutes"));
				$db->query("UPDATE courses SET openTime = '$openTime', closedTime = '$closedTime' WHERE sno = '$course_id'") or die(mysqli_error($db));
			}

			if($role == "teacher" || $this->isCourseOpen($course_id, $db)) {
				$query = $db->query("INSERT INTO attendance(onyen, role, courseID, timestamp) VALUES('$onyen', '$role', '$course_id', CURRENT_TIMESTAMP())") or die(mysqli_error($db));
				if($query) {
					// Successful insert
					$result['code'] = 0;
					$query = $db->query("SELECT * FROM attendance WHERE onyen = '$onyen'") or die(mysqli_error($db));
					$record = $query->fetch_array(MYSQLI_ASSOC);
					$result['record'] = $record;
				}
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

	function isCourseOpen($course_id, $db) {
		$query = $db->query("SELECT openTime, closedTime FROM courses WHERE sno = '$course_id'");
		if($query) {
			$record = $query->fetch_array(MYSQLI_ASSOC);
			$openTime = strtotime($record['openTime']);
			$closedTime = strtotime($record['closedTime']);
			$currentTime = strtotime("now");
			return ($currentTime >= $openTime && $currentTime <= $closedTime);
		}
		return false;
	}

	// Get student attendance records
	public function getStudentAttendance($onyen) {
		$role = 'student';
		return $this->getAttendance($onyen, $role);
	}

	// Get instructor attendance records
	public function getInstructorAttendance($onyen) {
		$role = 'teacher';
		return $this->getAttendance($onyen, $role);
	}

	public function getRosterAttendance($department, $number, $section, $roster) {
		$db = $this->__construct();

		$query_id = $db->query("SELECT sno FROM courses WHERE department = '$department' AND number = '$number' AND section = '$section'") or die(mysqli_error($db));
		if($query_id && $query_id->num_rows > 0) {
			$course_id = $query_id->fetch_assoc()['sno'];
			$this->uploadRoster($course_id, $roster, $db);
			$query = $db->query("SELECT onyen, COUNT(*) AS count FROM attendance WHERE courseID = '$course_id' AND role = 'student' GROUP BY onyen") or die(mysqli_error($db));
			$records = $query->fetch_all(MYSQLI_ASSOC);
			$result['records'] = $records;
			$result['code'] = 0;
			return $result;
		}
	}

	// Upload roster
	function uploadRoster($courseID, $roster, $db) {
		$roster = explode(",", $roster);
		foreach($roster as $onyen) {
			$query = $db->query("INSERT INTO roster(courseID, onyen) VALUES('$courseID', '$onyen')") or die(mysqli_error($db));
		}
	}

	function getAttendance($onyen, $role) {
		$db = $this->__construct();
		$query = $db->query("SELECT attendance.onyen AS onyen, attendance.role AS role, courses.department AS department, courses.number AS number, courses.section AS section, attendance.timestamp AS timestamp FROM attendance LEFT JOIN courses ON attendance.courseID = courses.sno WHERE onyen = '$onyen' AND role = '$role'") or die(mysqli_error($db));
		if($query) {
			$result['code'] = 0;
			$records = $query->fetch_all(MYSQLI_ASSOC);
			$result['records'] = $records;
		}
		return $result;
	}

	// Add course
	public function addCourse($department, $number, $section, $creator) {
		$db = $this->__construct();

		$query = $db->query("INSERT INTO courses(department, number, section, creator) VALUES('$department', '$number', '$section', '$creator')") or die(mysqli_error($db));
		if($query) {
			// Successful insert
			$result['code'] = 0;
			$query = $db->query("SELECT * FROM courses WHERE department = '$department' AND number = '$number' AND section = '$section'") or die(mysqli_error($db));
			$record = $query->fetch_all(MYSQLI_ASSOC);
			$result['record'] = $record;
		} else {
			// Insert failed
			$result['code'] = 1;
		}
		return $result;
	}

	// Get course information
	public function getCoursesByAdmin($creator) {
		$db = $this->__construct();

		$query = $db->query("SELECT * FROM courses WHERE creator = '$creator'") or die(mysqli_error($db));
		if($query) {
			$result['code'] = 0;
			$records = $query->fetch_all(MYSQLI_ASSOC);
			$result['records'] = $records;
		}
		return $result;
	}

}
