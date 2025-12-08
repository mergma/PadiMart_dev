<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bima's Garage</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/register.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    require_once 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validation
        $errors = [];
        
        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        }
        
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long";
        }
        
        // Check if username exists
        $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username already exists";
        }
        
        // Check if email exists
        $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email already exists";
        }
        
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $con->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $success = "Registration successful! You can now login.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
    ?>

    <div class="register-container">
        <img src="img/logo-b.png" alt="Logo" class="logo">
        <h1 class="register-title">Create Account</h1>
        
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <div class="password-requirements">
                    Password requirements:
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Contains at least one uppercase letter</li>
                        <li>Contains at least one number</li>
                        <li>Contains at least one special character</li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="submit-btn">Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html> 