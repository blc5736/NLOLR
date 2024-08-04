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
$volunteerID = isset($_POST['VolunteerID']) ? intval($_POST['VolunteerID']) : 0;
$firstName = isset($_POST['FirstName']) ? trim($_POST['FirstName']) : '';
$lastName = isset($_POST['LastName']) ? trim($_POST['LastName']) : '';
$phoneNumber = isset($_POST['PhoneNumber']) ? trim($_POST['PhoneNumber']) : '';
$email = isset($_POST['Email']) ? trim($_POST['Email']) : '';
$hoursWorked = isset($_POST['HoursWorked']) ? intval($_POST['HoursWorked']) : 0;

// Validate the input
if (empty($firstName) || empty($lastName) || empty($phoneNumber) || empty($email) || $hoursWorked <= 0) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO Volunteers (VolunteerID, FirstName, LastName, PhoneNumber, Email, HoursWorked) VALUES (?, ?, ?, ?, ?, ?)";
$params = array($volunteerID, $firstName, $lastName, $phoneNumber, $email, $hoursWorked);

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
echo json_encode(array('success' => true, 'message' => 'Volunteer information added successfully!'));
?>
