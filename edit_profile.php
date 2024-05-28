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
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email, $birthday);
$stmt->fetch();
$stmt->close();
$conn->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('baglanti.php');

    // Form verilerini al
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];

    // Kullanıcı bilgilerini güncelle
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, birthday = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $birthday, $user_id);
    if ($stmt->execute()) {
        // Güncelleme başarılı
        header("Location: myprofile.php");
        exit();
    } else {
        // Hata oluştu
        echo "Error: " . htmlspecialchars($stmt->error);
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
    <title>Edit Profile</title>
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
        .openbtn {
            font-size: 20px;
            cursor: pointer;
            background-color: #343a40;
            color: white;
            border: none;
            padding: 10px 15px;
            transition: 0.3s;
        }
        .openbtn:hover {
            background-color: #007bff;
        }
        .main {
            margin-left: 0;
            transition: margin-left 0.5s;
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
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="mySidebar">
    <a href="main.php">Main Page</a>
    <a href="myprofile.php">Profile</a>
    <a href="events.php">Events</a>
    <a href="create_event.php">Create Event</a>
    <a href="logout.php">Log Out</a>
</div>

<!-- Main content -->
<div class="main" id="mainContent">
    <button class="openbtn" onclick="toggleSidebar()">☰ Menu</button>
    <div class="container profile-container">
        <h2>Edit Profile</h2>
        <form action="edit_profile.php" method="post">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo htmlspecialchars($birthday); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="myprofile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("mySidebar").classList.toggle("open-sidebar");
        document.getElementById("mainContent").classList.toggle("shift-main");
    }
</script>

</body>
</html>
