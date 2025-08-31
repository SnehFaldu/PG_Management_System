<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch past residents
$sql = "SELECT * FROM residents WHERE status = 'left'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Past Residents - PG Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f5f8ff;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        color: #0a3d62;
        margin: 30px 0;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
    }

    .card {
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        padding: 20px;
        transition: transform 0.3s ease;
        position: relative;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .profile-pic {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #0a3d62;
        margin-bottom: 15px;
        display: block;
    }

    .info {
        line-height: 1.6;
        color: #333;
    }

    .info strong {
        color: #0a3d62;
    }

    .aadhar-link {
        display: inline-block;
        margin-top: 10px;
        color: #0a3d62;
        text-decoration: none;
        font-weight: bold;
    }

    .aadhar-link:hover {
        text-decoration: underline;
    }

    .empty {
        text-align: center;
        font-size: 18px;
        color: #999;
    }
  </style>
</head>
<body>

  <h1>Past Residents</h1>

  <div class="container">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <img src="get_image.php?id=<?= $row['id'] ?>" class="profile-pic" alt="Profile">

          <div class="info">
            <strong>Name:</strong> <?= htmlspecialchars($row['name']) ?><br>
            <strong>Age:</strong> <?= htmlspecialchars($row['age']) ?><br>
            <strong>Gender:</strong> <?= htmlspecialchars($row['gender']) ?><br>
            <strong>Room No:</strong> <?= htmlspecialchars($row['room_number']) ?><br>
            <strong>Contact:</strong> <?= htmlspecialchars($row['contact']) ?><br>
            <strong>Address:</strong> <?= htmlspecialchars($row['address']) ?><br>
            <strong>Advance:</strong> ₹<?= $row['advance_amount'] ?><br>
            <strong>Deposit:</strong> ₹<?= $row['deposit_amount'] ?><br>
            <strong>Left Date:</strong> <?= $row['left_date'] ?? 'N/A' ?><br>
            <strong>Aadhar:</strong> 
            <a href="get_aadhar.php?id=<?= $row['id'] ?>" target="_blank" class="aadhar-link">View Aadhar PDF</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="empty">No past residents found.</p>
    <?php endif; ?>
  </div>

</body>
</html>
