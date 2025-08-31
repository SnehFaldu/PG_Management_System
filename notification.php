<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "pg_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');
$ten_days_from_now = date('Y-m-d', strtotime('+10 days'));

// Fetch residents whose schedule end_date is within the next 10 days
// Corrected SQL to join residents and schedules
$sql = "SELECT r.id, r.name, r.room_number, r.gender, r.contact, r.profile_photo, s.end_date
        FROM residents r
        JOIN schedules s ON r.id = s.resident_id
        WHERE r.status = 'active'
        AND s.end_date BETWEEN ? AND ?
        ORDER BY s.end_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $today, $ten_days_from_now);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Residents Leaving Soon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 40px;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: white;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 30px;
            color: #64ffda;
        }

        .notifications-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            max-width: 800px;
            margin: auto;
        }

        .notification-card {
            display: flex;
            align-items: flex-start;
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
            transition: transform 0.2s;
            border-left: 5px solid #ff4d4d; /* Highlight for urgency */
        }

        .notification-card:hover {
            transform: scale(1.01);
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #64ffda;
            box-shadow: 0 0 12px rgba(100, 255, 218, 0.8);
            margin-right: 20px;
            flex-shrink: 0;
        }

        .notification-info {
            flex-grow: 1;
        }

        .notification-info h2 {
            margin: 0;
            color: #64ffda;
        }

        .notification-info p {
            margin: 6px 0;
            font-size: 16px;
        }

        .days-remaining {
            font-weight: bold;
            color: #ff4d4d; /* Red for urgency */
            font-size: 1.1em;
        }

        .no-notifications {
            text-align: center;
            color: #ccc;
            font-size: 1.2em;
            margin-top: 50px;
        }

        .btn-view {
            background-color: #64ffda;
            color: #002b36;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-view:hover {
            background-color: #52e5c7;
        }

        @media (max-width: 768px) {
            .notification-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-photo {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

    <h1><i class="fas fa-bell"></i> Residents Leaving Soon</h1>

    <div class="notifications-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()):
                $end_date_obj = new DateTime($row['end_date']);
                $today_obj = new DateTime();
                // Set both dates to midnight to ensure accurate day difference
                $end_date_obj->setTime(0, 0, 0);
                $today_obj->setTime(0, 0, 0);

                $interval = $today_obj->diff($end_date_obj);
                $days_remaining = $interval->days;
                $status_text = "";

                if ($interval->invert == 1) { // If end_date is in the past
                    $status_text = "Left " . $days_remaining . " days ago";
                    // You might choose to filter these out if you only want future notifications
                    // For now, they will still show if their end_date was within the 10-day window
                } else {
                    $status_text = "Leaving in " . $days_remaining . " days";
                }
            ?>
                <div class="notification-card">
                    <!-- Use get_image.php to fetch the binary photo data -->
                    <img class="profile-photo" src="get_image.php?id=<?= $row['id'] ?>" alt="Photo of <?= htmlspecialchars($row['name']) ?>">
                    <div class="notification-info">
                        <h2><?= htmlspecialchars($row['name']) ?></h2>
                        <p><strong>Room:</strong> <?= htmlspecialchars($row['room_number']) ?></p>
                        <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact']) ?></p>
                        <p class="days-remaining"><?= $status_text ?></p>
                        <a href="view_resident.php?id=<?= $row['id'] ?>" class="btn-view">
                            View Profile
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-notifications">No residents are scheduled to leave within the next 10 days.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php $stmt->close(); $conn->close(); ?>