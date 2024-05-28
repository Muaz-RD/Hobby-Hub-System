<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('baglanti.php');

// Kullanıcı ID'sini al
$user_id = $_SESSION['user_id'];

// Kullanıcı hesabını sil
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Oturumu sonlandır
session_destroy();

// Login sayfasına yönlendir
header("Location: login.php");
exit();
?>
