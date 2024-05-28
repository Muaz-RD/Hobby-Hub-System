<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .bg-image {
            background-image: url('JPG/login.jpg');
            background-size: cover;
            background-position: center;
            height: 80vh;
        }
        .error-message {
            color: red;
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="login-container bg-image">
                    <h2 class="text-center mb-4">Login</h2>
                    <?php if (isset($_GET['error'])): ?>
                        <p class="error-message text-center"><?php echo htmlspecialchars($_GET['error']); ?></p>
                    <?php endif; ?>
                    <form action="authenticate.php" method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <a href="profile.php" class="btn btn-link btn-block mt-3">Create Account</a>
                </div>
            </div>
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
