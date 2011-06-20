<?php

require('dbal.php'); // Include PDO DBAL

$details = array(
    'hostname'    =>    '', // Host name, localhost
    'dbname'    =>    '', // Database name
    'dbuser'    =>    '', // Database user
    'dbpass'    =>    '', // Database password
    'dbtype'    =>    'mysql', // types are mysql (also sql), sqlite, postgresql (also psotgre), firebird, odbc and mssql, default mysql
);

// $db->dbal is equivalent to the default PDO functions.
// You can use the other dbal functions via the $db->dbal->* .. and so on..
// Use $db->last_query to return use the last executed query.

$db = new dbal; // Also can use $db = new dbal($details) and then don't use dbal_connect.

$db->dbal_connect($details);

$sql = "SELECT * FROM users WHERE username = 'Alex'";

$result = $db->query($sql, PDO::FETCH_ASSOC);

echo $result['username'];

$sql = "INSERT INTO users (username, user_regdate) VALUES(?, ?)";

$result = $db->prequery($sql, array('Christian', time()));

$sql = "SELECT * FROM users";

$result = $db->query($sql, PDO::FETCH_BOTH, 'fetchAll', true);

$size = $db->fetchsize();

echo $size['rows']; // rowCount alias, $size['columns'] is alias of columnCount

?>