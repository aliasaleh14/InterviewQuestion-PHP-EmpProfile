<?php
$data = json_decode(file_get_contents("php://input"), true);

// Validate data server-side
$errors = [];
if (empty($data['name'])) $errors[] = "Name is required.";
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
if (!preg_match('/^\d{10}$/', $data['phone'])) $errors[] = "Phone must be 10 digits.";

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(["errors" => $errors]);
    exit;
}

// Read existing data
$file = '../data/employees.json';
$employees = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// Append new data
$employees[] = $data;

// Save to file
file_put_contents($file, json_encode($employees, JSON_PRETTY_PRINT));
echo json_encode(["success" => true]);
?>
