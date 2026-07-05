<?php

$mysqli = new mysqli(
    '127.0.0.1',
    'root',
    '',
    'alymart'
);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$result = $mysqli->query('DESCRIBE users');
if (!$result) {
    die('Query failed: ' . $mysqli->error);
}

while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

$mysqli->close();
