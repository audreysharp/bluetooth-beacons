<?php
/**
Create course table
**/

// Include Database handler
require_once 'include/DBConnect.php';
$db = new DBConnect();
$q = "CREATE TABLE courses (
    sno int(11) NOT NULL AUTO_INCREMENT,
    instructor varchar(50) NOT NULL,
    department varchar(4) NOT NULL,
    number smallint(3) UNSIGNED NOT NULL,
    section smallint(3) UNSIGNED ZEROFILL NOT NULL,
    timestamp datetime DEFAULT NULL,
    PRIMARY KEY (sno),
    UNIQUE KEY (department, number, section)
)";
$db->connect()->query($q);
