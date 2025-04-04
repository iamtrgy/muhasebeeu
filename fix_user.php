<?php

// Connect to the SQLite database
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');

// Update user with ID 2 (Annika) to be an accountant
$stmt = $db->prepare("UPDATE users SET is_accountant = 1 WHERE id = 2");
$result = $stmt->execute();

if ($result) {
    echo "User ID 2 (Annika) has been updated to have accountant privileges.\n";
} else {
    echo "Error updating user. " . print_r($stmt->errorInfo(), true) . "\n";
}

// Verify the change
$stmt = $db->prepare("SELECT id, name, email, is_admin, is_accountant FROM users WHERE id = 2");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Updated user data:\n";
echo "ID: " . $user['id'] . ", Name: " . $user['name'] . ", Email: " . $user['email'] . 
     ", Admin: " . ($user['is_admin'] ? "Yes" : "No") . 
     ", Accountant: " . ($user['is_accountant'] ? "Yes" : "No") . "\n"; 