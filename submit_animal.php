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

// Establish connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check connection
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Function to sanitize inputs (Consider more robust validation)
function sanitize($data) {
    return htmlspecialchars(strip_tags($data)); // Simple sanitization
}

// Retrieve and sanitize POST data
$name = sanitize($_POST['name']);
$species = sanitize($_POST['species']);
$breed = sanitize($_POST['breed']);
$age = sanitize($_POST['age']);
$sex = sanitize($_POST['sex']);
$microchipNumber = sanitize($_POST['microchipNumber']);
$healthStatus = sanitize($_POST['healthStatus']);
$intakeDate = $_POST['intakeDate']; // Should be validated for date format
$adoptionStatus = sanitize($_POST['adoptionStatus']);
$specialNeeds = sanitize($_POST['specialNeeds']);
$adoptionPackage = sanitize($_POST['adoptionPackage']);
$transfer = sanitize($_POST['transfer']);
$surrender = sanitize($_POST['surrender']);
$existingVetData = sanitize($_POST['existingVetData']);
$pregnant = sanitize($_POST['pregnant']);
$dewormed = sanitize($_POST['dewormed']);
$paroVaccination = sanitize($_POST['paroVaccination']);
$rabiesVaccination = sanitize($_POST['rabiesVaccination']);
$spayNeuter = sanitize($_POST['spayNeuter']);

// SQL Query
$sql = "INSERT INTO AnimalTable (Name, Species, Breed, Age, Sex, MicrochipNumber, HealthStatus, IntakeDate, AdoptionStatus, SpecialNeeds, AdoptionPackage, Transfer, Surrender, ExistingVetData, Pregnant, Dewormed, ParoVaccination, RabiesVaccination, SpayNeuter)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = array($name, $species, $breed, $age, $sex, $microchipNumber, $healthStatus, $intakeDate, $adoptionStatus, $specialNeeds, $adoptionPackage, $transfer, $surrender, $existingVetData, $pregnant, $dewormed, $paroVaccination, $rabiesVaccination, $spayNeuter);

// Execute query
$stmt = sqlsrv_query($conn, $sql, $params);

// Check query execution
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    echo "Animal information submitted successfully!";
}

// Close the connection
sqlsrv_close($conn);
?>