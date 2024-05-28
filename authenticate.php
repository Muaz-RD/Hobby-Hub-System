<?php
    session_start();  

    include("baglanti.php");

    // Form verilerini al  id
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kullanıcıyı veritabanından seç
    $sql = "SELECT user_id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            // Giriş başarılı
            $_SESSION['user_id'] = $id;
            header("Location: main.php");
            exit();
        } else {
            // Yanlış şifre
            header("Location: login.php?error=Incorrect password.");
            exit();
        }
    } else {
        // Kullanıcı bulunamadı
        header("Location: login.php?error=No account found with that email.");
        exit();
    }

    $stmt->close();
    $conn->close();
?>
