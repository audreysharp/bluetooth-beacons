<?php
/**
Drop attendance table
**/

// Include Database handler
require_once 'include/DBConnect.php';
$db = new DBConnect();
$q = "DROP TABLE attendance";
$db->connect()->query($q);
