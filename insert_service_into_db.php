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

// Check the connection
if ($conn === false) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . print_r(sqlsrv_errors(), true))));
}

// Retrieve and sanitize input data
$animalServiceID = isset($_POST['animal_service_id']) ? intval($_POST['animal_service_id']) : 0;
$serviceDate = isset($_POST['service_date']) ? $_POST['service_date'] : '';
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
$animalID = isset($_POST['animal_id']) ? intval($_POST['animal_id']) : 0;
$serviceID = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;

// Validate input data
if ($animalServiceID <= 0 || empty($serviceDate) || empty($notes) || $animalID <= 0 || $serviceID <= 0) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO AnimalServices (AnimalServiceID, ServiceDate, Notes, AnimalID, ServiceID) VALUES (?, ?, ?, ?, ?)";
$params = array($animalServiceID, $serviceDate, $notes, $animalID, $serviceID);

// Execute the query
$stmt = sqlsrv_query($conn, $sql, $params);

// Check for query execution errors
if ($stmt === false) {
    die(json_encode(array('success' => false, 'message' => 'Query failed: ' . print_r(sqlsrv_errors(), true))));
}

// Free the statement and close the connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Return success message
echo json_encode(array('success' => true, 'message' => 'Animal service recorded successfully!'));
?>