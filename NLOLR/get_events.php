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

// SQL Query to fetch events
$sql = "
SELECT 'Inspection' AS type, InspectionID AS event_id, InspectionDate AS event_date, InspectionStatus AS event_name
FROM inspections
UNION ALL
SELECT 'Event' AS type, EventID AS event_id, Date AS event_date, Name AS event_name
FROM events
UNION ALL
SELECT 'Animal Service' AS type, AnimalServiceID AS event_id, ServiceDate AS event_date, Name AS event_name
FROM animal_services
ORDER BY event_date
";

// Execute the query
$stmt = sqlsrv_query($conn, $sql);

// Check for query execution errors
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch results
$events = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Convert event_date to a string format
    $eventDate = $row['event_date'];
    $eventDateFormatted = $eventDate ? $eventDate->format('Y-m-d') : '';

    $event = array(
        'title' => $row['event_name'],
        'description' => 'Type: ' . $row['type'],
        'start' => $eventDateFormatted,
        'url' => '#' // Placeholder for URL if needed
    );
    $events[] = $event;
}

// Free the statement and close the connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Output JSON
header('Content-Type: application/json');
echo json_encode(array('events' => $events));
?>

