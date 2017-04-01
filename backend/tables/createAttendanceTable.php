<?php
/**
Create attendance table
**/

// Include Database handler
require_once INCLUDE_PATH.'DBConnect.php';
$db = new DBConnect();
$q = "CREATE TABLE attendance (
    sno int(11) NOT NULL AUTO_INCREMENT,
    onyen varchar(50) NOT NULL,
    course_id int(11) NOT NULL,
    timestamp datetime DEFAULT NULL,
    PRIMARY KEY (sno)
)";
$db->connect()->query($q);
