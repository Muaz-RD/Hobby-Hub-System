<?php
session_start();

// Kullanıcı oturum açmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('baglanti.php');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $action = $_POST['action'];

    if ($action === 'join') {
        // Etkinliğe katılma
        $stmt = $conn->prepare("INSERT INTO participations (user_id, event_id, status) VALUES (?, ?, 'confirmed')");
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'cancel') {
        // Katılımı iptal etme
        $stmt = $conn->prepare("DELETE FROM participations WHERE user_id = ? AND event_id = ?");
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Etkinlikleri ve katılım durumlarını veritabanından çek
$sql = "SELECT events.*, users.first_name, users.last_name, 
        (SELECT COUNT(*) FROM participations WHERE participations.event_id = events.event_id AND status = 'confirmed') AS participant_count,
        (SELECT status FROM participations WHERE participations.event_id = events.event_id AND participations.user_id = ?) AS user_participation_status
        FROM events 
        JOIN users ON events.created_by = users.user_id
        ORDER BY events.date, events.time";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
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
        .event-card {
            margin-bottom: 20px;
        }
        .event-header {
            background-color: #343a40;
            color: white;
            padding: 10px;
        }
        .event-body {
            padding: 15px;
            background-color: white;
            border: 1px solid #e9ecef;
            border-top: none;
        }
        .highlight {
            color: #e60000; 
        }

        .yellow {
            color: #ffcc00; 
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
    <div class="container mt-5">
    <h1>Welcome to <span class="highlight">Ho<span class="yellow">b</span><span class="yellow">b</span>y Hu<span class="yellow">b!</span></span></h1>
        <p>This is the main page.</p>
        <hr>
        <h3>Upcoming Events</h3>
        <div class="events">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card event-card">
                        <div class="card-header event-header">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </div>
                        <div class="card-body event-body">
                            <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                            <p class="card-text"><strong>Time:</strong> <?php echo htmlspecialchars($row['time']); ?></p>
                            <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                            <p class="card-text"><strong>Created by:</strong> <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></p>
                            <p class="card-text"><strong>Participants:</strong> <?php echo $row['participant_count']; ?></p>
                            <form method="post">
                                <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                <?php if ($row['user_participation_status'] === 'confirmed'): ?>
                                    <button type="submit" name="action" value="cancel" class="btn btn-danger">Cancel Participation</button>
                                <?php else: ?>
                                    <button type="submit" name="action" value="join" class="btn btn-primary">Join Event</button>
                                <?php endif; ?>
                            </form>
                            <!-- Katılımcıları göster butonu -->
                            <button class="btn btn-info mt-2" onclick="showParticipants(<?php echo $row['event_id']; ?>)">Show Participants</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No upcoming events.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="participantsModal" tabindex="-1" role="dialog" aria-labelledby="participantsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="participantsModalLabel">Participants</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul id="participantsList" class="list-group">
                    <!-- Katılımcılar burada görünecek -->
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="footer mt-5">
    <p>&copy; 2024 MUAZ RADWAN . All Rights Reserved. | <a href="https://github.com/Muaz-RD/Hobby-Hub-System" target="_blank">View on GitHub</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleSidebar() {
    document.getElementById("mySidebar").classList.toggle("open-sidebar");
    document.getElementById("mainContent").classList.toggle("shift-main");
}

function showParticipants(eventId) {
    $.ajax({
        url: 'get_participants.php',
        type: 'POST',
        data: { event_id: eventId },
        success: function(data) {
            var participants = JSON.parse(data);
            var participantsList = $('#participantsList');
            participantsList.empty();

            if (participants.length > 0) {
                participants.forEach(function(participant) {
                    var listItem = $('<li class="list-group-item"></li>');
                    listItem.text(participant.first_name + ' ' + participant.last_name);
                    participantsList.append(listItem);
                });
            } else {
                var noParticipants = $('<li class="list-group-item"></li>');
                noParticipants.text('No participants');
                participantsList.append(noParticipants);
            }

            $('#participantsModal').modal('show');
        }
    });
}
</script>

</body>
</html>
