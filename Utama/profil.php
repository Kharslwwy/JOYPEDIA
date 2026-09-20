<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php");
    exit();
}

$username = $_SESSION['username'];

// Proses update bio & foto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = mysqli_real_escape_string($koneksi, $_POST['bio']);

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../uploads/";
        $file_name = time() . "_" . basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $file_name;

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                mysqli_query($koneksi, "UPDATE users SET bio='$bio', profile_pic='$file_name' WHERE username='$username'");
            }
        }
    } else {
        mysqli_query($koneksi, "UPDATE users SET bio='$bio' WHERE username='$username'");
    }
}

// Ambil data user terbaru
$query = mysqli_query($koneksi, "SELECT email, bio, profile_pic FROM users WHERE username = '$username'");
$data = mysqli_fetch_assoc($query);

$email = $data['email'] ?? '';
$bio = $data['bio'] ?? 'Belum diisi';
$foto = !empty($data['profile_pic']) ? $data['profile_pic'] : 'default-profile.jpg';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background: #c5d2d4ff;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: flex-end;
      align-items: flex-start;
      min-height: 100vh;
    }

    .profile-container {
      width: 100%;
      max-width: 380px; /* <-- atur lebar maksimal */
      background: #fff;
      border-radius: 20px 0 0 20px; /* <-- biar sisi kiri melengkung */
      margin: 0; /* hilangkan margin tengah */
      height: 100vh; /* biar full tinggi frame */
      box-shadow: -4px 0 15px rgba(0,0,0,0.15); /* shadow dari kiri */
      animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    .profile-picture {
      position: relative;
      background: #ededed;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px 0;
    }
    .profile-picture img {
      width: 160px;
      height: 160px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid white;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      transition: transform 0.3s ease;
      cursor: pointer;
    }
    .profile-picture img:hover {
      transform: scale(1.05);
    }

    .profile-info {
      text-align: center;
      padding: 20px;
      border-bottom: 1px solid #eee;
    }
    .profile-info h2 {
      margin: 5px 0;
      font-size: 22px;
      font-weight: 600;
      color: #333;
    }
    .profile-info p {
      margin: 0;
      color: #777;
      font-size: 14px;
    }

    .bio-card {
      padding: 20px;
      font-size: 14px;
      color: #444;
      background: #fafafa;
    }
    .bio-card strong {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
    }

    .btn-edit {
      position: fixed;
      bottom: 25px;
      right: 25px;
      width: 55px;
      height: 55px;
      border-radius: 50%;
      background: #138fc9ff;
      border: none;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-size: 22px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      transition: transform 0.3s ease, background 0.3s ease;
    }
    .btn-edit:hover {
      background: #30afb8ff;
      transform: scale(1.1);
    }

    /* Edit form rapi di dalam container */
.edit-form {
  display: none;
  background: white;
  padding: 20px;
  animation: slideUp 0.3s ease forwards;
  box-sizing: border-box; /* supaya padding dihitung */
}

.edit-form label {
  font-weight: 600;
  display: block;
  margin-top: 10px;
}

.edit-form textarea,
.edit-form input[type="file"] {
  width: 100%;
  max-width: 100%;
  margin-top: 6px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 10px;
  font-family: inherit;
  font-size: 14px;
  box-sizing: border-box; /**/ 
  resize: vertical; /**/ 
}

.edit-form button {
  margin-top: 15px;
  width: 100%;
  padding: 12px;
  background: #4a8bc7ff;
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 15px;
  cursor: pointer;
  transition: background 0.3s ease;
  box-sizing: border-box;
}

    .edit-form button:hover {
      background: #1d9fa8ff;
    }

    .preview {
      margin-top: 10px;
      text-align: center;
    }
    .preview img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ccc;
    }

    /* Modal Cropper */
    .modal {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: white;
      border-radius: 16px 16px 0 0;
      box-shadow: 0 -4px 20px rgba(0,0,0,0.2);
      animation: slideUp 0.3s ease;
      padding: 15px;
      z-index: 999;
    }
    .modal img {
      max-width: 100%;
      max-height: 50vh;
      display: block;
      margin: 0 auto;
      border-radius: 8px;
    }
    .modal-actions {
      display: none;
      margin-top: 10px;
      text-align: center;
    }
    .modal-actions button {
      padding: 10px 20px;
      margin: 5px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
    }
    .btn-confirm {
      background: #1c70d1ff;
      color: white;
    }
    .btn-cancel {
      background: #ccc;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>

  <div class="profile-container">
    <div class="profile-picture">
      <img src="../uploads/<?= htmlspecialchars($foto) ?>" alt="Foto Profil">
    </div>

    <div class="profile-info">
      <h2><?= htmlspecialchars($username) ?></h2>
      <p><?= htmlspecialchars($email) ?></p>
    </div>

    <div class="bio-card">
      <strong>Bio:</strong>
      <?= htmlspecialchars($bio) ?>
    </div>

    <div class="edit-form" id="editForm">
      <form method="post" enctype="multipart/form-data">
        <label for="bio">Bio:</label>
        <textarea name="bio" rows="4"><?= htmlspecialchars($bio) ?></textarea>

        <label for="profile_pic">Foto Profil:</label>
        <input type="file" name="profile_pic" accept="image/*" onchange="openCropper(event)">
        <div class="preview" id="previewBox"></div>

        <button type="submit">Simpan</button>
      </form>
    </div>
  </div>

  <button class="btn-edit" onclick="toggleForm()">✏</button>

  <!-- Modal Cropper -->
  <div class="modal" id="cropModal">
    <img id="cropImage" src="">
    <div class="modal-actions" id="modalActions">
      <button class="btn-confirm" onclick="cropImage()">Simpan</button>
      <button class="btn-cancel" onclick="closeCropper()">Batal</button>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
  <script>
    let cropper;

    function toggleForm() {
      const form = document.getElementById("editForm");
      form.style.display = (form.style.display === "block") ? "none" : "block";
    }

    function openCropper(event) {
      const file = event.target.files[0];
      if (!file) return;

      const modal = document.getElementById("cropModal");
      const img = document.getElementById("cropImage");
      const actions = document.getElementById("modalActions");

      img.src = URL.createObjectURL(file);
      actions.style.display = "none";
      modal.style.display = "block";

      img.onload = () => {
        if (cropper) cropper.destroy();
        cropper = new Cropper(img, {
          aspectRatio: 1,
          viewMode: 1,
          autoCropArea: 1,
          responsive: true,
          ready() {
            actions.style.display = "block";
          }
        });
      };
    }

    function closeCropper() {
      document.getElementById("cropModal").style.display = "none";
      if (cropper) cropper.destroy();
    }

    function cropImage() {
      const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
      canvas.toBlob(blob => {
        const fileInput = document.querySelector('input[name="profile_pic"]');
        const file = new File([blob], "cropped.png", { type: "image/png" });

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        // tampilkan preview hasil crop
        const previewBox = document.getElementById("previewBox");
        previewBox.innerHTML = "";
        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        previewBox.appendChild(img);

        closeCropper();
      });
    }
  </script>

</body>
</html>
