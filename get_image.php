<?php
// get_image.php
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) die("DB Error");

$id = intval($_GET['id']); // Get resident ID

$query = $conn->prepare("SELECT profile_photo FROM residents WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$query->store_result();

if ($query->num_rows > 0) {
    $query->bind_result($photo);
    $query->fetch();

    header("Content-Type: image/jpeg"); // Adjust based on your actual image type
    echo $photo;
} else {
    // Show default placeholder if not found
    header("Content-Type: image/png");
    readfile("default.png");
}
?>
