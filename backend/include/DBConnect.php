<?php
class DBConnect {

	// constructor
	function __construct() {

	}

	// destructor
	function __destruct() {
		// $this->close();
	}

	// Connecting to database
	public function connect() {
		require_once 'include/config.php';
		// connecting to mysql
		$con = mysqli_connect("localhost", "root", "", "sampledb");

		// return database handler
		return $con;
	}

	// Closing database connection
	public function close() {
		mysqli_close();
	}

}
?>