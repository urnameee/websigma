// Untuk slider otomatis
let currentSlide = 0;
const totalSlides = document.querySelectorAll('.banner').length;
const slider = document.querySelector('.banner-slider');
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');

function showSlide(index) {
    const offset = -index * 100; // Menghitung offset
    slider.style.transform = `translateX(${offset}%)`;
}

prevButton.addEventListener('click', () => {
    currentSlide = (currentSlide > 0) ? currentSlide - 1 : totalSlides - 1;
    showSlide(currentSlide);
});

nextButton.addEventListener('click', () => {
    currentSlide = (currentSlide < totalSlides - 1) ? currentSlide + 1 : 0;
    showSlide(currentSlide);
});

// Slide otomatis setiap 5 detik
setInterval(() => {
    currentSlide = (currentSlide < totalSlides - 1) ? currentSlide + 1 : 0;
    showSlide(currentSlide);
}, 5000);
