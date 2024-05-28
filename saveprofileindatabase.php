<?php
session_start();

include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $birthday = $_POST['birthday'];

    // E-posta adresinin zaten var olup olmadığını kontrol edin
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // E-posta adresi zaten mevcut
        $error_message = "This email address is already registered. Please use a different email.";
        $stmt->close();
        $_SESSION['error_message'] = $error_message;
        header("Location: profile.php");
        exit();
    } else {
        // E-posta adresi mevcut değil, yeni kullanıcıyı ekleyin
        $stmt->close();
        $sql = "INSERT INTO users (first_name, last_name, email, password, birthday) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $birthday);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
            $_SESSION['error_message'] = $error_message;
            header("Location: profile.php");
            exit();
        }

        $stmt->close();
    }

    $conn->close();
}
?>
