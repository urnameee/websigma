document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeBtn = document.querySelector('.close-btn');

    // Fungsi untuk membuka dan menutup menu mobile
    function toggleMenu() {
        mobileMenu.classList.toggle('active');
    }

    // Open mobile menu when hamburger icon is clicked
    hamburger.addEventListener('click', toggleMenu);

    // Close mobile menu when close button is clicked
    closeBtn.addEventListener('click', function() {
        mobileMenu.classList.remove('active');
    });

    // Close mobile menu when clicking outside of it
    document.addEventListener('click', function(event) {
        if (!mobileMenu.contains(event.target) && !hamburger.contains(event.target)) {
            mobileMenu.classList.remove('active');
        }
    });
});
