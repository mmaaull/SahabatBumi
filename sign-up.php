<?php
// Include database configuration
include './config.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: user/profil.php");
    exit();
}

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username) || empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_msg = "Semua field harus diisi!";
    } elseif (strlen($username) < 4) {
        $error_msg = "Username minimal 4 karakter!";
    } elseif (!preg_match('/^[a-zA-Z0-9_]*$/', $username)) {
        $error_msg = "Username hanya boleh menggunakan huruf, angka, dan underscore!";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Kata sandi tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error_msg = "Kata sandi minimal 6 karakter!";
    } else {
        // Check if username already exists
        $check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $result_username = $check_username->get_result();

        if ($result_username->num_rows > 0) {
            $error_msg = "Username sudah terdaftar!";
        } else {
            // Check if email already exists
            $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $result_email = $check_email->get_result();

            if ($result_email->num_rows > 0) {
                $error_msg = "Email sudah terdaftar!";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into database (default role = 'user')
                $role = 'user';
                $insert_user = $conn->prepare("INSERT INTO users (username, nama_lengkap, email, password, role) VALUES (?, ?, ?, ?, ?)");
                $insert_user->bind_param("sssss", $username, $fullname, $email, $hashed_password, $role);

                if ($insert_user->execute()) {
                    $success_msg = "Daftar berhasil! Silahkan login.";
                    // Redirect to login after 2 seconds (root login.php)
                    header("refresh:2; url=login.php");
                } else {
                    $error_msg = "Error: " . $insert_user->error;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sahabat Bumi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style_sign-up.css">
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
            <h2 class="form-title">Daftar</h2>
            
            <!-- Social Login Buttons -->
            <div class="social-buttons">
                <button class="social-btn google-btn">
                    <i class="fab fa-google"></i>
                    Daftar dengan Google
                </button>
                <button class="social-btn facebook-btn">
                    <i class="fab fa-facebook-f"></i>
                    Daftar dengan Facebook
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

            <!-- Success Message -->
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Buat Username" required>
                    <small>4-50 karakter, huruf, angka, underscore</small>
                </div>

                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Masukkan Nama Lengkap" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan Email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Buat Kata Sandi" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Kata Sandi</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Kata Sandi" required>
                </div>

                <button type="submit" class="submit-btn">Daftar</button>
            </form>

            <p class="login-link">
                Sudah punya akun? <a href="login.php">Masuk</a>
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

        .alert-success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .alert i {
            font-size: 16px;
        }
    </style>
</body>
</html>
