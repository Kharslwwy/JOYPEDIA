<?php
include '../db.php'; // koneksi $koneksi dari mysqli_connect
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];
    $password = password_hash($password_raw, PASSWORD_BCRYPT);

    // Cek apakah username atau email sudah terpakai
    $username = mysqli_real_escape_string($koneksi, $username);
    $email = mysqli_real_escape_string($koneksi, $email);

    $sql_check = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result_check = mysqli_query($koneksi, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $message = "Username atau Email sudah digunakan.";
    } else {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $username, $password, $email);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: login.php?signup=success");
                exit();
            } else {
                $message = "Terjadi kesalahan saat sign-up.";
            }
        } else {
            $message = "Query tidak valid.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Sign Up | JoyApp</title>
    <link rel="icon" href="../Utama/JOY.png" type="image/png" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

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
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .toggle-password {
            cursor: pointer;
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .position-relative {
            position: relative;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="text-center mb-3">
        <img src="../Utama/JOY.png" alt="JoyApp" width="60" />
        <h4 class="mt-2">Buat Akun JoyApp</h4>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required />
            <label for="username">Username</label>
        </div>

        <div class="form-floating mb-3 position-relative">
            <input type="password" class="form-control pe-5" id="password" name="password" placeholder="Password" required />
            <label for="password">Password</label>

            <!-- Icon Toggle Password -->
            <span class="toggle-password" onclick="togglePassword()">
                <i id="eyeIcon" class="bi bi-eye-slash fs-5"></i>
            </span>
        </div>

        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
            <label for="email">Email</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Daftar</button>

        <div class="text-center mt-3">
            <small>Sudah punya akun? <a href="login.php">Masuk sekarang</a></small>
        </div>
    </form>
</div>

<!-- Bootstrap Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
