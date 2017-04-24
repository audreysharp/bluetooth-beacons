<?php
/**Create course table**/

// Include Database handler
require_once 'include/DBConnect.php';
$db = new DBConnect();
$q = "CREATE TABLE courses (
    sno int(11) NOT NULL AUTO_INCREMENT,
    department varchar(4) NOT NULL,
    number smallint(3) UNSIGNED NOT NULL,
    section smallint(3) UNSIGNED ZEROFILL NOT NULL,
    beaconID varchar(50) NOT NULL,
    creator varchar(50) NOT NULL,
    openTime datetime DEFAULT NULL,
    closedTime datetime DEFAULT NULL,
    PRIMARY KEY (sno),
    UNIQUE KEY (department, number, section)
)";
$db->connect()->query($q);
?>