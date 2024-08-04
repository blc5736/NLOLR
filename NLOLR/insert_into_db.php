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
$intakeId = isset($_POST['intake_id']) ? intval($_POST['intake_id']) : 0;
$intakeDate = isset($_POST['intake_date']) ? $_POST['intake_date'] : '';
$broughtBy = isset($_POST['brought_by']) ? trim($_POST['brought_by']) : '';
$initialHealthAssessment = isset($_POST['initial_health_assessment']) ? trim($_POST['initial_health_assessment']) : '';
$microchipCheck = isset($_POST['microchip_check']) ? trim($_POST['microchip_check']) : '';
$intakeNotes = isset($_POST['intake_notes']) ? trim($_POST['intake_notes']) : '';
$animalId = isset($_POST['animal_id']) ? intval($_POST['animal_id']) : 0;

// Validate input data
if ($intakeId <= 0 || empty($intakeDate) || empty($broughtBy) || empty($initialHealthAssessment) || empty($microchipCheck) || empty($intakeNotes) || $animalId <= 0) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO AnimalIntake (IntakeID, IntakeDate, BroughtBy, InitialHealthAssessment, MicrochipCheck, IntakeNotes, AnimalID) VALUES (?, ?, ?, ?, ?, ?, ?)";
$params = array($intakeId, $intakeDate, $broughtBy, $initialHealthAssessment, $microchipCheck, $intakeNotes, $animalId);

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
echo json_encode(array('success' => true, 'message' => 'Animal intake record added successfully!'));
?>
