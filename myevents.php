<?php
session_start();

// Kullanıcı oturum açmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('baglanti.php');

// Kullanıcının oluşturduğu etkinlikleri veritabanından çek
$user_id = $_SESSION['user_id'];
$sql_created_events = "SELECT * FROM events WHERE created_by = ?";
$stmt_created_events = $conn->prepare($sql_created_events);
if ($stmt_created_events === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_created_events->bind_param("i", $user_id);
$stmt_created_events->execute();
$result_created_events = $stmt_created_events->get_result();
$stmt_created_events->close();

// Kullanıcının katıldığı etkinlikleri veritabanından çek
$sql_participated_events = "SELECT events.* FROM events 
                            JOIN participations ON events.event_id = participations.event_id 
                            WHERE participations.user_id = ? AND participations.status = 'confirmed'";
$stmt_participated_events = $conn->prepare($sql_participated_events);
if ($stmt_participated_events === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt_participated_events->bind_param("i", $user_id);
$stmt_participated_events->execute();
$result_participated_events = $stmt_participated_events->get_result();
$stmt_participated_events->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #343a40;
            padding-top: 60px;
            transition: 0.5s;
            z-index: 1;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #f8f9fa;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            color: #007bff;
        }
        .openbtn, .closebtn {
            font-size: 20px;
            cursor: pointer;
            background-color: #343a40;
            color: white;
            border: none;
            padding: 10px 15px;
            transition: 0.3s;
        }
        .openbtn:hover, .closebtn:hover {
            background-color: #007bff;
        }
        .main {
            transition: margin-left 0.5s;
            padding: 20px;
        }
        .open-sidebar {
            left: 0;
        }
        .shift-main {
            margin-left: 250px;
        }
        .events-container {
            margin-top: 20px;
        }
        .hidden {
            display: none;
        }
        .footer {
        background-color: #343a40;
        color: white;
        padding: 10px 0;
        text-align: center;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .footer a:hover {
            color: #0056b3;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                left: -100%;
            }
            .open-sidebar {
                left: 0;
            }
            .shift-main {
                margin-left: 0;
            }
            .openbtn {
                width: 100%;
                text-align: left;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="mySidebar">
    <button class="closebtn" onclick="toggleSidebar()">←</button>
    <a href="main.php">Main Page</a>
    <a href="myprofile.php">Profile</a>
    <a href="myevents.php">Events</a>
    <a href="create_event.php">Create Event</a>
    <a href="logout.php">Log Out</a>
</div>

<!-- Main content -->
<div class="main" id="mainContent">
    <button class="openbtn" onclick="toggleSidebar()">☰ Menu</button>
    <div class="container events-container">
        <h2>My Events</h2>
        
        <h3>Created Events</h3>
        <div class="row">
            <?php if ($result_created_events->num_rows > 0): ?>
                <?php while ($row = $result_created_events->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                                <p class="card-text"><strong>Time:</strong> <?php echo htmlspecialchars($row['time']); ?></p>
                                <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You have not created any events.</p>
            <?php endif; ?>
        </div>

        <h3>Participated Events</h3>
        <div class="row">
            <?php if ($result_participated_events->num_rows > 0): ?>
                <?php while ($row = $result_participated_events->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                                <p class="card-text"><strong>Time:</strong> <?php echo htmlspecialchars($row['time']); ?></p>
                                <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You have not participated in any events.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="footer mt-5">
    <p>&copy; 2024 MUAZ RADWAN . All Rights Reserved. | <a href="https://github.com/Muaz-RD/Hobby-Hub-System" target="_blank">View on GitHub</a></p>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("mySidebar").classList.toggle("open-sidebar");
        document.getElementById("mainContent").classList.toggle("shift-main");
    }
</script>

</body>
</html>
