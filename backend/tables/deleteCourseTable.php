<?php
/**
Drop instructor course table
**/

// Include Database handler
require_once '/backend/include/DBConnect.php';
$db = new DBConnect();
$q = "DROP TABLE courses";
$db->connect()->query($q);
