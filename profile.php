<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
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
        .btn-secondary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        #mainContent {
            margin-top: 20px;
        }
        #signupForm {
            max-width: 400px;
            margin: auto;
        }
        .form-group {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 35px;
            right: 10px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            font-size: 1rem;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="main" id="mainContent">
    <div class="container" id="signupForm">
        <h2>Profile</h2>
        <?php
        session_start();
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="saveprofileindatabase.php" method="post">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="date" class="form-control" id="birthday" name="birthday" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Account</button>
            <a href="login.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const passwordFieldType = passwordField.getAttribute('type');
        if (passwordFieldType === 'password') {
            passwordField.setAttribute('type', 'text');
        } else {
            passwordField.setAttribute('type', 'password');
        }
    }
</script>
</body>
</html>
