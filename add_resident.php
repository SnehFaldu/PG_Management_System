<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $age      = $_POST['age'];
    $gender   = $_POST['gender'];
    $room     = $_POST['room'];
    $contact  = $_POST['contact'];
    $address  = $_POST['address'];
    $advance  = $_POST['advance'];
    $deposit  = $_POST['deposit'];
    $remaining = $_POST['remaining'];

    // Read file contents
    $photoData  = file_get_contents($_FILES['photo']['tmp_name']);
    $aadharData = file_get_contents($_FILES['aadhar']['tmp_name']);

    $sql = "INSERT INTO residents 
        (name, age, gender, room_number, contact, address, advance_amount, deposit_amount, remaining_amount, profile_photo, aadhar_pdf)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssdddbb", $name, $age, $gender, $room, $contact, $address, $advance, $deposit, $remaining, $null, $null);
    
    // Send long data separately
    $stmt->send_long_data(9, $photoData);
    $stmt->send_long_data(10, $aadharData);

    if ($stmt->execute()) {
        echo "<script>alert('Resident added successfully'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add resident');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Resident</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            padding: 20px;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #64ffda;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
        }
        input[type="submit"] {
            background-color: #64ffda;
            color: #003b46;
            font-weight: bold;
            transition: 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #52e5c7;
            transform: scale(1.03);
        }
        label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-user-plus"></i> Add Resident</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Full Name</label>
            <input type="text" name="name" required>
            
            <label>Age</label>
            <input type="number" name="age" required>

            <label>Gender</label>
            <select name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label>Room Number</label>
            <input type="text" name="room" required>

            <label>Contact Number</label>
            <input type="text" name="contact" required>

            <label>Permanent Address</label>
            <textarea name="address" rows="2" required></textarea>

            <label>Monthly Amount</label>
            <input type="number" name="advance" step="0.01">

            <label>Deposit Amount</label>
            <input type="number" name="deposit" step="0.01">

            <label>Fixed Amount</label>
            <input type="number" name="remaining" step="0.01">

            <label>Passport Size Photo</label>
            <input type="file" name="photo" accept=".jpg,.jpeg,.png" required>

            <label>Aadhar Card (PDF)</label>
            <input type="file" name="aadhar" accept=".pdf" required>

            <input type="submit" value="Add Resident">
        </form>
    </div>
</body>
</html>
