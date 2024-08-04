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
$applicationID = isset($_POST['ApplicationID']) ? intval($_POST['ApplicationID']) : 0;
$applicationDate = isset($_POST['ApplicationDate']) ? $_POST['ApplicationDate'] : '';
$status = isset($_POST['Status']) ? trim($_POST['Status']) : '';
$meetAndGreetDate = isset($_POST['MeetAndGreetDate']) ? $_POST['MeetAndGreetDate'] : null;
$homeCheckStatus = isset($_POST['HomeCheckStatus']) ? trim($_POST['HomeCheckStatus']) : null;
$adopterID = isset($_POST['AdopterID']) ? intval($_POST['AdopterID']) : 0;
$animalID = isset($_POST['AnimalID']) ? intval($_POST['AnimalID']) : 0;

// Validate input data
if ($applicationID <= 0 || empty($applicationDate) || empty($status) || $adopterID <= 0 || $animalID <= 0) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO AdoptionApplications (ApplicationID, ApplicationDate, Status, MeetAndGreetDate, HomeCheckStatus, AdopterID, AnimalID)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$params = array($applicationID, $applicationDate, $status, $meetAndGreetDate, $homeCheckStatus, $adopterID, $animalID);

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
echo json_encode(array('success' => true, 'message' => 'Adoption application submitted successfully!'));
?>
