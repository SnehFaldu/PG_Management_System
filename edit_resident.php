<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM residents WHERE id = ? AND status = 'active'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $editData = $result->fetch_assoc();
    } else {
        die("Resident not found or already marked as left.");
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Resident</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 40px;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: #fff;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 36px;
            text-shadow: 0 2px 8px #000;
        }

        .container {
            max-width: 800px;
            background: rgba(255, 255, 255, 0.05);
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
        }

        form label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            font-size: 16px;
            border-radius: 6px;
            border: none;
            outline: none;
            background: rgba(255, 255, 255, 0.8);
            color: #000;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-update {
            background: #00ffcc;
            color: #003366;
        }

        .btn-delete {
            background: #ff4d4d;
            color: white;
        }

        .btn-update:hover {
            background: #00e6b8;
        }

        .btn-delete:hover {
            background: #ff1a1a;
        }
    </style>
</head>
<body>
    <h1>Edit Resident Details</h1>

    <div class="container">
        <?php if ($editData): ?>
        <form action="update_resident.php" method="POST">
            <input type="hidden" name="resident_id" value="<?= $editData['id'] ?>">

            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($editData['name']) ?>">

            <label>Age</label>
            <input type="number" name="age" value="<?= $editData['age'] ?>">

            <label>Gender</label>
            <select name="gender">
                <option value="Male" <?= $editData['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $editData['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $editData['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>

            <label>Room Number</label>
            <input type="text" name="room" value="<?= $editData['room_number'] ?>">

            <label>Contact</label>
            <input type="text" name="contact" value="<?= $editData['contact'] ?>">

            <label>Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($editData['address']) ?>">

            <label>Monthly Amount</label>
            <input type="number" name="advance" value="<?= $editData['advance_amount'] ?>">

            <label>Deposit Amount</label>
            <input type="number" name="deposit" value="<?= $editData['deposit_amount'] ?>">

            <label>Fixed Amount</label>
            <input type="number" name="remaining" value="<?= $editData['remaining_amount'] ?>">

            <div class="button-group">
                <button type="submit" name="update" class="btn btn-update">Update</button>
                <button type="submit" name="delete" class="btn btn-delete" onclick="return confirm('Are you sure to mark this resident as left?');">Mark as Left</button>
            </div>
        </form>
        <?php else: ?>
            <p style="text-align:center; color: red;">No resident found or already left.</p>
        <?php endif; ?>
    </div>
</body>
</html>
