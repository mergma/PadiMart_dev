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
    <title>Login - PADI MART</title>
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
            background: linear-gradient(180deg, #f7f9f6 0%, #ffffff 60%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-wrapper {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 1.5rem;
            align-items: stretch;
            padding: 1rem;
            box-sizing: border-box;
        }

        .login-panel {
            background: linear-gradient(135deg, rgba(88,194,52,0.06), rgba(237,160,36,0.03));
            border-radius: 16px;
            padding: 2.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1rem;
            box-shadow: 0 6px 24px rgba(16,24,32,0.06);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #eef2f5;
            overflow: hidden;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, var(--padi-green) 0%, var(--padi-orange) 100%);
            color: white;
            padding: 1.75rem 1.5rem;
            text-align: center;
        }

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        .login-header p {
            margin: 0.35rem 0 0 0;
            opacity: 0.95;
            font-size: 0.95rem;
        }

        .login-body {
            padding: 1.5rem;
        }

        .form-control:focus {
            border-color: var(--padi-green);
            box-shadow: 0 0 0 0.2rem rgba(88, 194, 52, 0.18);
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
            box-shadow: 0 6px 18px rgba(88, 194, 52, 0.22);
        }

        .alert { border-radius: 8px; }

        .input-group-text { background-color: #f8f9fa; border-right: none; color: var(--padi-green); }
        .form-control { border-left: none; }

        .register-link { text-align: center; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #eef2f5; }
        .register-link a { color: var(--padi-green); text-decoration: none; font-weight: 600; }
        .register-link a:hover { color: var(--padi-orange); text-decoration: underline; }

        .panel-brand { display:flex; gap:1rem; align-items:center; }
        .panel-brand img { height:56px; width:auto; }
        .panel-title { font-size:1.3rem; font-weight:700; color:#0f1724; }
        .panel-sub { color: rgba(15,23,36,0.7); margin-top:0.25rem; }

        .panel-features { margin-top:1rem; display:flex; flex-direction:column; gap:0.75rem; }
        .feat { display:flex; gap:0.75rem; align-items:flex-start; }
        .feat i { color: var(--padi-green); font-size:1.15rem; margin-top:2px; }
        .feat .ftext { font-weight:600; color:#0b1220; }
        .feat .fsub { color: rgba(11,18,32,0.65); font-size:0.92rem; }

        .btn-wa { background:#25D366; color:#fff; border:none; padding:0.5rem 0.75rem; border-radius:8px; font-weight:700; }

        @media (max-width: 992px) {
            .login-wrapper { grid-template-columns: 1fr; max-width: 720px; }
            .login-panel { order: 2; padding: 1.5rem; }
            .login-card { order: 1; }
        }
        @media (max-width: 480px) {
            .login-header h1 { font-size: 1.25rem; }
            .panel-brand img { height:48px; }
            .login-body { padding: 1rem; }
        }
    </style>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-wrapper">
        <aside class="login-panel">
            <div class="panel-brand">
                <img src="img/PADI%20MART.png" alt="PADI MART logo">
                <div>
                    <div class="panel-title">PADI MART</div>
                    <div class="panel-sub">Dukung petani lokal — belanja berkualitas</div>
                </div>
            </div>

            <div class="panel-features">
                <div class="feat">
                    <i class="fas fa-seedling"></i>
                    <div>
                        <div class="ftext">Produk Lokal</div>
                        <div class="fsub">Beras, camilan, dan hasil pertanian dari petani terverifikasi.</div>
                    </div>
                </div>

                <div class="feat">
                    <i class="fas fa-shipping-fast"></i>
                    <div>
                        <div class="ftext">Pengiriman Cepat</div>
                        <div class="fsub">Pengemasan baik dan layanan pengiriman andal.</div>
                    </div>
                </div>

                <div class="feat">
                    <i class="fas fa-handshake"></i>
                    <div>
                        <div class="ftext">Langsung ke Petani</div>
                        <div class="fsub">Dukung keberlanjutan dan ekonomi lokal.</div>
                    </div>
                </div>
            </div>

            <div style="margin-top:1rem;">
                <div class="panel-cta">Dukung petani lokal — masuk sekarang untuk dapatkan penawaran khusus!</div>
            </div>
        </aside>

        <div class="login-card">
            <div class="login-header">
                <h1><img src="img/PADI%20MART.png" alt="logo" style="height:28px;"> PADI MART</h1>
                <p>Masuk untuk mengelola akun dan berbelanja</p>
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

                    

                <div class="register-link">
                    <div style="display:flex; gap:1rem; justify-content:center; align-items:center;">
                        <a href="index.php" class="text-muted">Kembali ke Beranda</a>
                        <span style="color:#e6e6e6">•</span>
                        <a href="register.php">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

