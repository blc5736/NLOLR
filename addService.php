<?php
// Database connection parameters
$serverName = "tcp:nlolr.database.windows.net,1433";
$connectionOptions = array(
    "UID" => "nlolr",
    "pwd" => "Password123!",
    "Database" => "NLOLR",
    "LoginTimeout" => 30,
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

// Establish the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    // If connection fails
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . print_r(sqlsrv_errors(), true))));
}

// Fetch and decode input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['name']) || !isset($data['description']) || !isset($data['fee']) || !isset($data['duration'])) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

$name = trim($data['name']);
$description = trim($data['description']);
$fee = floatval($data['fee']); // Assuming fee is a numeric value
$duration = trim($data['duration']);

// Prepare SQL query
$sql = "INSERT INTO Services (Name, Description, Fee, Duration) VALUES (?, ?, ?, ?)";
$params = array($name, $description, $fee, $duration);

// Execute the query
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    // If query fails
    die(json_encode(array('success' => false, 'message' => 'Query failed: ' . print_r(sqlsrv_errors(), true))));
}

// Close the connection
sqlsrv_close($conn);

// Return success message
echo json_encode(array('success' => true, 'message' => 'Service added successfully!'));
?>
