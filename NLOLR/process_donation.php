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
$donationID = isset($_POST['donation_id']) ? intval($_POST['donation_id']) : 0;
$donorName = isset($_POST['donor_name']) ? trim($_POST['donor_name']) : '';
$donorEmail = isset($_POST['donor_email']) ? trim($_POST['donor_email']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0.0;
$date = isset($_POST['date']) ? $_POST['date'] : '';
$eventID = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

// Validate input data
if ($donationID <= 0 || empty($donorName) || empty($donorEmail) || $amount <= 0 || empty($date) || $eventID <= 0) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO Donations (DonationID, DonorName, DonorEmail, Amount, Date, EventID) VALUES (?, ?, ?, ?, ?, ?)";
$params = array($donationID, $donorName, $donorEmail, $amount, $date, $eventID);

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
echo json_encode(array('success' => true, 'message' => 'Donation recorded successfully!'));
?>
