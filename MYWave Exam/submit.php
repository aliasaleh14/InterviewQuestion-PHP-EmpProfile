<?php
header("Content-Type: application/json");

$data = $_POST;

// Validate backend
$errors = [];

function sanitize($str) {
  return htmlspecialchars(trim($str));
}

$name = sanitize($data['name'] ?? '');
$email = sanitize($data['email'] ?? '');
$phone = sanitize($data['phone'] ?? '');
$countryCode = sanitize($data['country_code'] ?? '');
$dob = sanitize($data['dob'] ?? '');

if (!$name) $errors[] = "Name is required.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
if (!preg_match('/^\+\d{1,4}$/', $countryCode)) $errors[] = "Invalid country code.";
if (!preg_match('/^\d{9,10}$/', $phone)) $errors[] = "Phone number must be 9–10 digits.";
if (!$dob) $errors[] = "Date of birth is required.";

if (!empty($errors)) {
  http_response_code(400);
  echo json_encode(["status" => "error", "errors" => $errors]);
  exit;
}

$fullPhone = $countryCode . $phone;

$employee = [
  "name" => $name,
  "gender" => sanitize($data['gender'] ?? ''),
  "marital_status" => sanitize($data['marital_status'] ?? ''),
  "phone" => $fullPhone,
  "email" => $email,
  "address" => sanitize($data['address'] ?? ''),
  "dob" => $dob,
  "nationality" => sanitize($data['nationality'] ?? ''),
  "hire_date" => sanitize($data['hire_date'] ?? ''),
  "department" => sanitize($data['department'] ?? ''),
  "created_at" => date("Y-m-d H:i:s")
];

// Save to JSON
$file = '../data/employees.json';
$employees = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$employees[] = $employee;
file_put_contents($file, json_encode($employees, JSON_PRETTY_PRINT));

echo json_encode(["status" => "success", "message" => "Employee saved."]);
