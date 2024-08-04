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
    die(print_r(sqlsrv_errors(), true));
}

// Retrieve and validate GET parameters
$month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
$day = isset($_GET['day']) ? (int)$_GET['day'] : 0;
$year = isset($_GET['year']) ? (int)$_GET['year'] : 0;

// Validate date parameters
if ($month < 1 || $month > 12 || $day < 1 || $day > 31 || $year < 1900 || $year > 2100) {
    die("Invalid date parameters.");
}

// Prepare SQL query
$sql = "SELECT * FROM events WHERE YEAR(event_date) = ? AND MONTH(event_date) = ? AND DAY(event_date) = ?";
$params = array($year, $month, $day);

// Execute the query
$stmt = sqlsrv_query($conn, $sql, $params);

// Check for query execution errors
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch results
$events = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $events[] = $row;
}

// Free the statement and close the connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Output the events (example: JSON encoding)
header('Content-Type: application/json');
echo json_encode($events);
?>
