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
$eventID = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
$eventName = isset($_POST['event_name']) ? trim($_POST['event_name']) : '';
$eventDate = isset($_POST['event_date']) ? $_POST['event_date'] : '';
$eventLocation = isset($_POST['event_location']) ? trim($_POST['event_location']) : '';
$eventDescription = isset($_POST['event_description']) ? trim($_POST['event_description']) : '';

// Validate input data
if ($eventID <= 0 || empty($eventName) || empty($eventDate) || empty($eventLocation) || empty($eventDescription)) {
    die(json_encode(array('success' => false, 'message' => 'Invalid input data.')));
}

// Prepare SQL query
$sql = "INSERT INTO Events (EventID, EventName, EventDate, EventLocation, EventDescription) VALUES (?, ?, ?, ?, ?)";
$params = array($eventID, $eventName, $eventDate, $eventLocation, $eventDescription);

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
echo json_encode(array('success' => true, 'message' => 'Event added successfully!'));
?>
