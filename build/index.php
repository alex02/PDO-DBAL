<?php

include('dbal.php'); // Include PDO DBAL

$details = array(
    'hostname'    =>    'localhost', // Host name
    'dbname'    =>    'dbal', // Database name
    'dbuser'    =>    'root', // Database user
    'dbpass'    =>    'asd123', // Database password
    'dbtype'    =>    'mysql', // MySQL, SQLite, PostgreSQL, default MySQL
);

$db = new dbal;

$db->dbal_connect($details);

$sql = "SELECT * FROM users WHERE username = 'Alex'";

$result = $db->query($sql, PDO::FETCH_ASSOC, true);

var_dump($result);

?>