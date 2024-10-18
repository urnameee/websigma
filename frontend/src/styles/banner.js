document.addEventListener("DOMContentLoaded", function () {
    const banners = document.querySelectorAll('.banner');
    const leftArrow = document.querySelector('.left-arrow');
    const rightArrow = document.querySelector('.right-arrow');
    let currentIndex = 0;

    // Menampilkan banner pertama
    banners[currentIndex].classList.add('active');

    function showBanner(index) {
        banners.forEach((banner, i) => {
            banner.classList.remove('active'); // Menghapus kelas active dari semua banner
            if (i === index) {
                banner.classList.add('active'); // Menambahkan kelas active pada banner yang ditentukan
            }
        });
    }

    leftArrow.addEventListener('click', () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : banners.length - 1; // Navigasi ke banner sebelumnya
        showBanner(currentIndex); // Menampilkan banner yang sesuai
    });

    rightArrow.addEventListener('click', () => {
        currentIndex = (currentIndex < banners.length - 1) ? currentIndex + 1 : 0; // Navigasi ke banner berikutnya
        showBanner(currentIndex); // Menampilkan banner yang sesuai
    });
});
