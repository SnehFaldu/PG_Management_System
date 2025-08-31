<?php
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['resident_id'];

if (isset($_POST['delete'])) {
    // Mark as left
    $stmt = $conn->prepare("UPDATE residents SET status = 'left' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_resident.php?left=1");
    exit();
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $room = $_POST['room'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $advance = $_POST['advance'];
    $deposit = $_POST['deposit'];
    $remaining = $_POST['remaining'];

    $stmt = $conn->prepare("UPDATE residents SET name=?, age=?, gender=?, room_number=?, contact=?, address=?, advance_amount=?, deposit_amount=?, remaining_amount=? WHERE id=?");
    $stmt->bind_param("sisssssddi", $name, $age, $gender, $room, $contact, $address, $advance, $deposit, $remaining, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_resident.php?id=$id&success=1");
    exit();
}
?>
