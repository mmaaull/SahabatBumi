<?php
include 'config.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on role
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/profil.php");
    }
    exit();
}

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validation
    if (empty($username) || empty($password)) {
        $error_msg = "Username dan kata sandi harus diisi!";
    } else {
        // Check user in database
        $check_user = $conn->prepare("SELECT id, username, nama_lengkap, password, role FROM users WHERE username = ?");
        $check_user->bind_param("s", $username);
        $check_user->execute();
        $result = $check_user->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nama_lengkap'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: user/profil.php");
                }
                exit();
            } else {
                $error_msg = "Username atau kata sandi salah!";
            }
        } else {
            $error_msg = "Username atau kata sandi salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sahabat Bumi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="no-scrollbar">
    <!-- Logo Header -->
     <a href="user/homepage.php">
        <div class="logo-header">
            <img src="img/logo.png" alt="Sahabat Bumi Logo" class="logo">
            <h1>Sahabat Bumi</h1>
        </div>
     </a>

    <div class="container">
        <!-- Right Section -->
        <div class="right-section">
            <h2 class="form-title">Masuk</h2>
            
            <!-- Social Login Buttons -->
            <div class="social-buttons">
                <button class="social-btn google-btn">
                    <i class="fab fa-google"></i>
                    Masuk dengan Google
                </button>
                <button class="social-btn facebook-btn">
                    <i class="fab fa-facebook-f"></i>
                    Masuk dengan Facebook
                </button>
            </div>

            <div class="divider">
                <span>atau</span>
            </div>

            <!-- Error Message -->
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan Username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Kata Sandi" required>
                </div>

                <button type="submit" class="submit-btn">Masuk</button>
            </form>

            <p class="login-link">
                Belum punya akun? <a href="sign-up.php">Daftar</a>
            </p>
        </div>
    </div>

    <script src="js/script_sign-up.js"></script>
    <style>
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert i {
            font-size: 16px;
        }
    </style>
</body>
</html>
