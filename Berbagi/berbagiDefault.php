<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Berbagi Informasi</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to bottom right, #dfe9f3, #ffffff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 20px;
    }

    .card {
      background-color: #fff;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      text-align: center;
      max-width: 400px;
      width: 100%;
      animation: fadeIn 0.8s ease;
    }

    .icon {
      font-size: 50px;
      margin-bottom: 20px;
    }

    .card h2 {
      font-size: 24px;
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .card h4 {
      font-size: 16px;
      color: #7f8c8d;
      margin-bottom: 30px;
    }

    .login-button {
      padding: 12px 25px;
      background-color: #1abc9c;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      text-decoration: none;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
      display: inline-block;
    }

    .login-button:hover {
      background-color: #16a085;
      transform: translateY(-2px);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 480px) {
      .card {
        padding: 30px 20px;
      }

      .card h2 {
        font-size: 20px;
      }

      .login-button {
        width: 100%;
        font-size: 18px;
        padding: 14px;
      }
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="icon">📢</div>
    <h2>Berbagi Informasi</h2>
    <h4>Fitur ini hanya bisa digunakan setelah login</h4>
    <a class="login-button" href="../Login/login.php" target="_parent">Login Sekarang</a>
  </div>

</body>
</html>
