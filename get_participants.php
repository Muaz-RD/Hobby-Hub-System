<?php
include('baglanti.php');

if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    $stmt = $conn->prepare("SELECT users.first_name, users.last_name 
                            FROM participations 
                            JOIN users ON participations.user_id = users.user_id 
                            WHERE participations.event_id = ? AND participations.status = 'confirmed'");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $participants = [];
    while ($row = $result->fetch_assoc()) {
        $participants[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($participants);
}
?>
