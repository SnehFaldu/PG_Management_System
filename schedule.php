<?php
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'DB Connection Failed']);
    exit;
}

header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

// 1. INSERT new schedule
if ($request_method === 'POST' && empty($_POST['action'])) {
    $resident_id = $_POST['resident_id'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $note = $_POST['note'] ?? '';

    if (!$resident_id || !$start_date || !$end_date) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO schedules (resident_id, start_date, end_date, note) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $resident_id, $start_date, $end_date, $note);

    if ($stmt->execute()) {
        header("Location: view_resident.php");
        exit(); // Always call exit after header redirect
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

// 2. READ all schedules for a resident
if ($request_method === 'GET' && isset($_GET['resident_id'])) {
    $resident_id = $_GET['resident_id'];
    $stmt = $conn->prepare("SELECT id, start_date, end_date, note FROM schedules WHERE resident_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $resident_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($r = $result->fetch_assoc()) $rows[] = $r;

    echo json_encode($rows);
    $stmt->close();
    exit;
}

// 3. DELETE schedule entry
if ($request_method === 'POST' && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM schedules WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Schedule deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

// 4. UPDATE existing schedule
if ($request_method === 'POST' && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $note = $_POST['note'];

    $stmt = $conn->prepare("UPDATE schedules SET start_date = ?, end_date = ?, note = ? WHERE id = ?");
    $stmt->bind_param("sssi", $start_date, $end_date, $note, $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Schedule updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?>
