document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById("menuToggle");
    const navLinks = document.getElementById("navLinks");
    const searchBtn = document.getElementById("searchBtn");
    const searchOverlay = document.getElementById("searchOverlay");
    const closeSearch = document.getElementById("closeSearch");
    const searchInput = document.getElementById("searchInput");
    const searchForm = document.getElementById("searchForm");

    // Toggle Menu
    menuToggle.addEventListener("click", () => {
        navLinks.classList.toggle("open");
        menuToggle.textContent = navLinks.classList.contains("open") ? "✕" : "☰";
    });

    // Open Search
    searchBtn.addEventListener("click", () => {
        searchOverlay.classList.add("active");
        setTimeout(() => searchInput.focus(), 400);
    });

    // Close Search
    closeSearch.addEventListener("click", () => {
        searchOverlay.classList.remove("active");
    });

    // Close with ESC
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") searchOverlay.classList.remove("active");
    });

    // Close on overlay click
    searchOverlay.addEventListener("click", (e) => {
        if (e.target === searchOverlay) searchOverlay.classList.remove("active");
    });

    // Handle Search Submit
    searchForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
            console.log("🔍 Searching for:", query);
            // TODO: استبدل هذا بـ API أو إعادة توجيه
            alert(`جاري البحث عن: ${query}`);
            searchOverlay.classList.remove("active");
        }
    });
}
);


document.addEventListener('DOMContentLoaded', function () {

    
});


