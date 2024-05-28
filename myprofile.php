<?php
session_start();

// Kullanıcı oturum açmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('baglanti.php');

// Kullanıcı bilgilerini veritabanından çek
$user_id = $_SESSION['user_id'];
$sql = "SELECT first_name, last_name, email, birthday FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email, $birthday);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
        .profile-container {
            margin-top: 20px;
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
    <div class="container profile-container">
        <h2>My Profile</h2>
        <div class="card">
            <div class="card-body">
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Birthday:</strong> <?php echo htmlspecialchars($birthday); ?></p>
                <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
                <form action="delete_account.php" method="post" style="display:inline;">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</button>
                </form>
            </div>
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
