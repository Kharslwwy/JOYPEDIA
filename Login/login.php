<?php
session_start();
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];

    // reCAPTCHA validation
    $recaptcha_secret = '6LdylA8sAAAAAKyUDdiidsFkuy_J9gWmti4rY-Gr';
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptcha_response)) {
        $message = 'Harap centang reCAPTCHA!';
    } else {
        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}"
        );
        $captcha_success = json_decode($verify);

        if (!$captcha_success->success) {
            $message = 'Verifikasi reCAPTCHA gagal, silakan coba lagi.';
        }
    }

    // Login logic only if captcha passed
    if (empty($message)) {
        if (empty($username) || empty($email) || empty($password_raw)) {
            $message = 'Semua field wajib diisi!';
        } else {
            $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $data = $result->fetch_assoc();
                if ($data['password'] === $password_raw || password_verify($password_raw, $data['password'])) {
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['role'] = $data['role'];

                    $redirect = ($data['role'] === 'admin') ? '../Admin/utama.php' : '../Utama/index.php';
                    header("Location: $redirect");
                    exit();
                } else {
                    $message = "Password salah!";
                }
            } else {
                $message = "Username atau Email tidak ditemukan!";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | JoyApp</title>
    <link rel="icon" href="../Utama/JOY.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #6fb1fc, #4364f7, #0052d4);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.8s ease;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* ReCAPTCHA full width */
        .recaptcha-container {
            display: flex;
            justify-content: center;
            width: 100%;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .g-recaptcha {
            transform: scale(1.0);
            transform-origin: 0 0;
        }

        /* Sesuaikan skala untuk full width (opsional) */
        @media (max-width: 400px) {
            .g-recaptcha {
                transform: scale(0.88);
            }
        }
    </style>
</head>
<body>

<div class="card p-4" style="width: 100%; max-width: 400px;">
    <div class="text-center mb-3">
        <img src="../Utama/JOY.png" alt="JoyApp" width="60">
        <h4 class="mt-2">Masuk ke JoyApp</h4>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            <label for="username">Username</label>
        </div>

        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>

        <div class="form-floating mb-3 position-relative">
            <input class="form-control pe-5" id="password" name="password" placeholder="Password" type="password" required>
            <label for="password">Password</label>
            <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor: pointer;" onclick="togglePassword()">
                <i id="eyeIcon" class="bi bi-eye-slash fs-5 text-secondary"></i>
            </span>
        </div>

        <!-- reCAPTCHA -->
        <div class="recaptcha-container">
            <div class="g-recaptcha" data-sitekey="6LdylA8sAAAAAFYh83zvv027Tq5kgayCHZlZf95l"></div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>

        <div class="text-center mt-3">
            <small>Belum punya akun? <a href="signup.php">Daftar sekarang</a></small>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    } else {
        password.type = "password";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
}
</script>

</body>
</html>
