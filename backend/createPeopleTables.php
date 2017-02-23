<?php
/**
Create people tables
**/

// Include Database handler
require_once 'include/DBConnect.php';
$db = new DBConnect();
$q = "CREATE TABLE students (
    sno int(11) NOT NULL AUTO_INCREMENT,
    pid int(9) NOT NULL,
    firstName varchar(50) NOT NULL,
    lastName varchar(50) NOT NULL,
    onyen varchar(50) NOT NULL,
    encrypted_password varchar(256) NOT NULL,
    salt varchar(10) NOT NULL,
    created_at datetime DEFAULT NULL,
    PRIMARY KEY (sno)
)";
$db->connect()->query($q);
