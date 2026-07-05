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

$result = $mysqli->query('SELECT id, name, email, role, is_active FROM users');
if (!$result) {
    die('Query failed: ' . $mysqli->error);
}

echo "Created users:\n";
echo str_repeat("-", 70) . "\n";
printf("%-5s | %-20s | %-25s | %-15s | %-10s\n", "ID", "Name", "Email", "Role", "Active");
echo str_repeat("-", 70) . "\n";

while ($row = $result->fetch_assoc()) {
    printf("%-5d | %-20s | %-25s | %-15s | %-10s\n", 
        $row['id'], 
        $row['name'], 
        $row['email'], 
        $row['role'], 
        $row['is_active'] ? 'Yes' : 'No'
    );
}

$mysqli->close();
