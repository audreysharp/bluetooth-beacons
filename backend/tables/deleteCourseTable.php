<?php
/**
Drop instructor course table
**/

// Include Database handler
require_once INCLUDE_PATH.'DBConnect.php';
$db = new DBConnect();
$q = "DROP TABLE courses";
$db->connect()->query($q);
