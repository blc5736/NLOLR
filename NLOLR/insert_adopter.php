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
$adopterID = isset($_POST['AdopterID']) ? intval($_POST['AdopterID']) : 0;
$firstName = isset($_POST['FirstName']) ? trim($_POST['FirstName']) : '';
$lastName = isset($_POST['LastName']) ? trim($_POST['LastName']) : '';
$address = isset($_POST['Address']) ? trim($_POST['Address']) : '';
$phoneNumber = isset($_POST['PhoneNumber']) ? trim($_POST['PhoneNumber']) : '';
$email = isset($_POST['Email']) ? trim($_POST['Email']) : '';
$applicationStatus = isset($_POST['ApplicationStatus']) ? trim($_POST['ApplicationStatus']) : null;
$householdInspectionStatus = isset($_POST['HouseholdInspectionStatus']) ? trim($_POST['HouseholdInspectionStatus']) : null;

// Validate input data
if ($adopterID <= 0 || empty($firstName) || empty($lastName) || empty($address) || empty($phoneNumber) || empty($email)) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO Adopters (AdopterID, FirstName, LastName, Address, PhoneNumber, Email, ApplicationStatus, HouseholdInspectionStatus)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$params = array($adopterID, $firstName, $lastName, $address, $phoneNumber, $email, $applicationStatus, $householdInspectionStatus);

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
echo json_encode(array('success' => true, 'message' => 'Adopter information submitted successfully!'));
?>
