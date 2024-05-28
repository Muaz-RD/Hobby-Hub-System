<?php
session_start();

// Kullanıcı oturum açmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('baglanti.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $created_by = $_SESSION['user_id'];

    // Etkinliği veritabanına ekle
    $sql = "INSERT INTO events (title, description, date, time, location, created_by) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sssssi", $title, $description, $date, $time, $location, $created_by);
    if ($stmt->execute()) {
        // Etkinlik başarıyla eklendi
        header("Location: main.php");
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
    <title>Create Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .event-container {
            width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="event-container">
        <h2>Create Event</h2>
        <form action="create_event.php" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="main.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
