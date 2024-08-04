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
$inspectionID = isset($_POST['InspectionID']) ? intval($_POST['InspectionID']) : 0;
$inspectionDate = isset($_POST['InspectionDate']) ? $_POST['InspectionDate'] : '';
$inspectionStatus = isset($_POST['InspectionStatus']) ? trim($_POST['InspectionStatus']) : '';
$report = isset($_POST['Report']) ? trim($_POST['Report']) : '';
$volunteerID = isset($_POST['VolunteerID']) ? intval($_POST['VolunteerID']) : 0;
$adopterID = isset($_POST['AdopterID']) ? intval($_POST['AdopterID']) : 0;

// Validate input data
if ($inspectionID <= 0 || empty($inspectionDate) || empty($inspectionStatus) || $volunteerID <= 0 || $adopterID <= 0) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO Inspections (InspectionID, InspectionDate, InspectionStatus, Report, VolunteerID, AdopterID) VALUES (?, ?, ?, ?, ?, ?)";
$params = array($inspectionID, $inspectionDate, $inspectionStatus, $report, $volunteerID, $adopterID);

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
echo json_encode(array('success' => true, 'message' => 'Inspection record added successfully!'));
?>
