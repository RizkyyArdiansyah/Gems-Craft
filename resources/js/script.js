document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const mobileMenu = document.getElementById("mobile-menu");

    menuToggle.addEventListener("click", function () {
        mobileMenu.classList.toggle("hidden"); // Toggle class hidden
    });

    // Menutup dropdown jika klik di luar menu
    document.addEventListener("click", function (event) {
        if (!menuToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
            mobileMenu.classList.add("hidden");
        }
    });
});
