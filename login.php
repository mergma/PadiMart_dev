<?php
session_start();

// If already logged in, redirect to homepage
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: index.php');
    exit();
}

require_once 'api/config.php';

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $userFound = false;
        $isAdmin = false;

        // First, try to find user in admins table
        $stmt = $conn->prepare("SELECT id, username, password, email, full_name FROM admins WHERE username = ? AND is_active = 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $userFound = true;
            $isAdmin = true;
        }
        $stmt->close();

        // If not found in admins, try users table
        if (!$userFound) {
            $stmt = $conn->prepare("SELECT id, username, password, email FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $userFound = true;
                $isAdmin = false;
            }
            $stmt->close();
        }

        if ($userFound) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password correct, create session
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = isset($user['full_name']) ? $user['full_name'] : $user['username'];
                $_SESSION['is_admin'] = $isAdmin;

                // For backward compatibility
                if ($isAdmin) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_name'] = isset($user['full_name']) ? $user['full_name'] : $user['username'];
                }

                // Update last login for admins only (users table doesn't have last_login column)
                if ($isAdmin) {
                    $updateStmt = $conn->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
                    if ($updateStmt) {
                        $updateStmt->bind_param("i", $user['id']);
                        $updateStmt->execute();
                        $updateStmt->close();
                    }
                }

                // Redirect to homepage
                header('Location: index.php');
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PADI MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --padi-green: rgb(88, 194, 52);
            --padi-green-dark: #58b530;
            --padi-orange: rgb(237, 160, 36);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, var(--padi-green) 0%, var(--padi-orange) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }
        .login-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.95;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control:focus {
            border-color: var(--padi-green);
            box-shadow: 0 0 0 0.2rem rgba(88, 194, 52, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, var(--padi-green) 0%, var(--padi-orange) 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, var(--padi-green-dark) 0%, #d89520 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(88, 194, 52, 0.3);
        }
        .alert {
            border-radius: 8px;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: var(--padi-green);
        }
        .form-control {
            border-left: none;
        }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
        }
        .register-link a {
            color: var(--padi-green);
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
            color: var(--padi-orange);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-store"></i> PADI MART</h1>
            <p>Login Page</p>
        </div>
        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Masukkan username" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Masukkan password" required>
                    </div>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary btn-login w-100">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="register-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
            <div class="register-link">
                Go Back <a href="index.php">To Home Page</a>
            </div>
        </div>
    </div>
</body>
</html>

