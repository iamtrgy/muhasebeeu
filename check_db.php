<?php

// Connect to the SQLite database
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');

// Get the columns in the users table
$stmt = $db->prepare("PRAGMA table_info(users)");
$stmt->execute();
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Columns in users table:\n";
foreach ($columns as $column) {
    echo $column['name'] . " (" . $column['type'] . ")\n";
}

// Check if the is_accountant column exists
$hasAccountantColumn = false;
foreach ($columns as $column) {
    if ($column['name'] === 'is_accountant') {
        $hasAccountantColumn = true;
        break;
    }
}

echo "\nis_accountant column exists: " . ($hasAccountantColumn ? "YES" : "NO") . "\n";

// If the column doesn't exist, try to add it
if (!$hasAccountantColumn) {
    try {
        $db->exec("ALTER TABLE users ADD COLUMN is_accountant INTEGER DEFAULT 0");
        echo "Added is_accountant column to users table\n";
    } catch (Exception $e) {
        echo "Error adding column: " . $e->getMessage() . "\n";
    }
}

// List some users and their accountant status
$stmt = $db->prepare("SELECT id, name, email, is_admin, is_accountant FROM users LIMIT 5");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\nSample users:\n";
foreach ($users as $user) {
    echo "ID: " . $user['id'] . ", Name: " . $user['name'] . ", Email: " . $user['email'] . 
         ", Admin: " . ($user['is_admin'] ? "Yes" : "No") . 
         (isset($user['is_accountant']) ? ", Accountant: " . ($user['is_accountant'] ? "Yes" : "No") : ", Accountant: <column not found>") . 
         "\n";
} 