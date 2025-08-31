<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PG Management - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #fff;
        }

        header {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
            color: #64ffda;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 40px;
        }

        .menu-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 30px 20px;
            text-decoration:none;
            text-align: center;
            transition: transform 0.3s ease, background-color 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            cursor: pointer;
        }

        .menu-item:hover {
            transform: translateY(-8px);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-item i {
            font-size: 2rem;
            color: #64ffda;
            margin-bottom: 10px;
        }

        .menu-item h2 {
            font-size: 1.2rem;
            color: #ffffff;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 0.9rem;
            color: #ccc;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        @media (max-width: 600px) {
            .menu {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>PG Management Dashboard</h1>
    </header>

    <div class="menu">
        <a href="add_resident.php" class="menu-item">
            <i class="fas fa-user-plus"></i>
            <h2>Add Resident</h2>
        </a>
        <a href="view_resident.php" class="menu-item">
            
            <i class="fas fa-user-edit"></i>
            <h2>View / Edit / Remove Resident</h2>
        </a>
        <a href="view_profiles.php" class="menu-item">
            <i class="fas fa-id-badge"></i>
            <h2>Resident Profiles</h2>
        </a>
        <a href="notification.php" class="menu-item">
            <i class="fas fa-sort-amount-down-alt"></i>
            <h2>Remaining Amount (High to Low)</h2>
        </a>
        <a href="past_residents.php" class="menu-item">
            <i class="fas fa-history"></i>
            <h2>Past Residents</h2>
        </a>
    </div>

    <footer>
        &copy; <?php echo date('Y'); ?> PG Management System. All rights reserved.
    </footer>
</body>
</html>
