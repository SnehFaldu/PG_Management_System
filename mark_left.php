<?php
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE residents SET status = 'left' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: view_resident.php?left=1");
        exit();
    } else {
        echo "Error updating record.";
    }
}
?>
