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

// Disable foreign key checks
$mysqli->query('SET FOREIGN_KEY_CHECKS=0');

// Truncate users table
if ($mysqli->query('TRUNCATE TABLE users')) {
    echo "Users table truncated successfully\n";
} else {
    echo 'Truncate failed: ' . $mysqli->error . "\n";
}

// Re-enable foreign key checks
$mysqli->query('SET FOREIGN_KEY_CHECKS=1');

$mysqli->close();
