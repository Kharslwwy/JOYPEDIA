// Toggle Profile Dropdown
document.getElementById("profileBtn").addEventListener("click", function () {
    document.getElementById("profileDropdown").classList.toggle("show");
});

// Close dropdown when clicking outside
document.addEventListener("click", function (event) {
    if (!event.target.closest(".profile-menu")) {
        document.getElementById("profileDropdown").classList.remove("show");
    }
});

// Search Function
function search() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let contentFrame = document.querySelector("iframe[name='content-frame']").contentWindow.document;
    let newsItems = contentFrame.querySelectorAll(".news-item");
    let noResultsMessage = document.getElementById("noResultsMessage");
    let found = false;

    if (newsItems.length === 0) {
        alert("No search results available on this page.");
        return;
    }

    newsItems.forEach((item) => {
        let title = item.querySelector("h3").innerText.toLowerCase();
        let content = item.querySelector("p").innerText.toLowerCase();

        if (title.includes(input) || content.includes(input)) {
            item.style.display = "flex";
            found = true;
        } else {
            item.style.display = "none";
        }
    });

    noResultsMessage.style.display = found ? "none" : "block";
}

// Responsive Navbar Toggle
document.getElementById("hamburger").addEventListener("click", function () {
    document.getElementById("navLinks").classList.toggle("show");
});
