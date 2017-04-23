<?php
/**Drop attendance table**/

// Include Database handler
require_once 'include/DBConnect.php';
$db = new DBConnect();
$q = "DROP TABLE roster";
$db->connect()->query($q);
