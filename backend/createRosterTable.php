<?php
/**
Create course table
**/

// Include Database handler
require_once 'include/DBConnect.php';
$db = new DBConnect();
$q = "CREATE TABLE roster (
    sno int(11) NOT NULL AUTO_INCREMENT,
    courseID int(11) NOT NULL,
    onyen varchar(50) NOT NULL,
    PRIMARY KEY (sno),
    UNIQUE KEY (onyen, courseID)
)";
$db->connect()->query($q);
