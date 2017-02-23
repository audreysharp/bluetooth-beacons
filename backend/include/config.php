<?php

/**
* Database config variables
*/
define("DB_HOST", getenv("MYSQL_SERVICE_HOST"));
define("DB_USER", getenv("MYSQL_USER"));
define("DB_PASSWORD", getenv("MYSQL_PASSWORD"));
define("DB_DATABASE", getenv("MYSQL_DATABASE"));
