document.addEventListener("DOMContentLoaded", async () => {
    const profileImg = document.getElementById("profileImg");
    const uploadImg = document.getElementById("uploadImg");
    const editProfile = document.getElementById("editProfile");
    const userName = document.getElementById("userName");
    const userEmail = document.getElementById("userEmail");
    const userBio = document.getElementById("userBio");

    let isEditing = false;

    // Ambil email dari localStorage (setelah login)
    const email = localStorage.getItem("email");

    // Simulasi mengambil data profil dari database
    async function fetchUserData() {
        const response = await fetch(`get_user_name.php?email=${email}`);
        const name = await response.text();

        // Update data ke halaman profil
        userName.textContent = name;
        userEmail.textContent = email;
        userBio.textContent = "Pengguna yang aktif!";
        profileImg.src = "default-profile.jpg"; // Gambar profil default
    }

    // Load data profil pengguna
    fetchUserData();

    // Edit profil
    editProfile.addEventListener("click", () => {
        if (isEditing) {
            editProfile.textContent = "Edit Profil";
            userName.contentEditable = "false";
            userEmail.contentEditable = "false";
            userBio.contentEditable = "false";
            isEditing = false;
        } else {
            editProfile.textContent = "Simpan";
            userName.contentEditable = "true";
            userEmail.contentEditable = "true";
            userBio.contentEditable = "true";
            isEditing = true;
        }
    });

    // Upload gambar profil
    profileImg.addEventListener("click", () => uploadImg.click());
    uploadImg.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                profileImg.src = e.target.result;
                console.log("New profile picture updated.");
            };
            reader.readAsDataURL(file);
        }
    });
});
