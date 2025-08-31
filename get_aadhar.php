<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "pg_management");

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);

// Fetch the Aadhar PDF from DB
$sql = "SELECT aadhar_pdf FROM residents WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($aadharData);
    $stmt->fetch();

    // Set headers
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=aadhar_$id.pdf");
    echo $aadharData;
} else {
    echo "Aadhar card not found.";
}

$stmt->close();
$conn->close();
?>
