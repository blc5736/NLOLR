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
$recordId = isset($_POST['record_id']) ? intval($_POST['record_id']) : 0;
$checkupDate = isset($_POST['checkup_date']) ? $_POST['checkup_date'] : '';
$vaccinationDetails = isset($_POST['vaccination_details']) ? trim($_POST['vaccination_details']) : '';
$treatmentDetails = isset($_POST['treatment_details']) ? trim($_POST['treatment_details']) : '';
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
$animalId = isset($_POST['animal_id']) ? intval($_POST['animal_id']) : 0;

// Validate input data
if ($recordId <= 0 || empty($checkupDate) || $animalId <= 0) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO MedicalRecords (RecordID, CheckupDate, VaccinationDetails, TreatmentDetails, Notes, AnimalID) VALUES (?, ?, ?, ?, ?, ?)";
$params = array($recordId, $checkupDate, $vaccinationDetails, $treatmentDetails, $notes, $animalId);

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
echo json_encode(array('success' => true, 'message' => 'Medical record added successfully!'));
?>
