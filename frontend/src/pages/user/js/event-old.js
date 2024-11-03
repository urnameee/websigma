document.addEventListener('DOMContentLoaded', async function() {
    // Fungsi untuk mengambil data dari API
    async function fetchTimelineData() {
        try {
            const response = await fetch('/backend/controllers/admin-ukm/timeline.php?limit=3&status=inactive');
            const result = await response.json();
            
            if (result.status === 'success') {
                return result.data;
            } else {
                console.error('Error fetching data:', result.message);
                return [];
            }
        } catch (error) {
            console.error('Error:', error);
            return [];
        }
    }

    // Fungsi untuk membuat slide HTML
    function createSlideHTML(timeline) {
        return `
            <div class="carousel-slide">
                <img src="/frontend/public/assets/${timeline.image_path}" alt="${timeline.judul_kegiatan}">
                <div class="slide-description">
                    <h3>${timeline.judul_kegiatan}</h3>
                    <p>${timeline.deskripsi}</p>
                </div>
            </div>
        `;
    }

    // Inisialisasi carousel dengan data
    const track = document.querySelector('.carousel-track');
    
    // Ambil dan tampilkan data
    const timelineData = await fetchTimelineData();
    if (timelineData.length > 0) {
        track.innerHTML = ''; // Bersihkan carousel
        timelineData.forEach((timeline, index) => {
            const slideHTML = createSlideHTML(timeline);
            track.insertAdjacentHTML('beforeend', slideHTML);
        });
    }

    // Setelah data dimuat, inisialisasi carousel
    const slides = Array.from(track.children);
    const nextButton = document.querySelector('.carousel-button.next');
    const prevButton = document.querySelector('.carousel-button.prev');

    // Set slide pertama sebagai active
    if (slides.length > 0) {
        slides[0].classList.add('active');
    }

    const updateSlidePositions = () => {
        const activeSlide = track.querySelector('.active');
        const activeIndex = slides.indexOf(activeSlide);
        
        slides.forEach((slide, index) => {
            slide.classList.remove('prev', 'active', 'next');
            if (index === activeIndex) {
                slide.classList.add('active');
            } else if (index === (activeIndex - 1 + slides.length) % slides.length) {
                slide.classList.add('prev');
            } else if (index === (activeIndex + 1) % slides.length) {
                slide.classList.add('next');
            }
        });
    };

    const moveToSlide = (direction) => {
        const currentSlide = track.querySelector('.active');
        if (!currentSlide) return; // Guard clause jika tidak ada slide aktif

        let targetSlide;
        if (direction === 'next') {
            targetSlide = currentSlide.nextElementSibling || slides[0];
        } else {
            targetSlide = currentSlide.previousElementSibling || slides[slides.length - 1];
        }

        // Hide all slides except the current and target
        slides.forEach(slide => {
            if (slide !== currentSlide && slide !== targetSlide) {
                slide.style.opacity = '0';
            }
        });

        // Move to the target slide
        currentSlide.classList.remove('active');
        targetSlide.classList.add('active');

        // Update positions after a short delay
        setTimeout(() => {
            updateSlidePositions();
            // Restore opacity of all slides
            slides.forEach(slide => {
                slide.style.opacity = '';
            });
        }, 50);
    };

    // Event listeners
    nextButton.addEventListener('click', () => moveToSlide('next'));
    prevButton.addEventListener('click', () => moveToSlide('prev'));

    // Initialize
    updateSlidePositions();

    // Auto slide every 5 seconds
    let autoSlideInterval = setInterval(() => moveToSlide('next'), 5000);

    // Pause auto-slide when hovering over carousel
    track.addEventListener('mouseenter', () => {
        clearInterval(autoSlideInterval);
    });

    track.addEventListener('mouseleave', () => {
        autoSlideInterval = setInterval(() => moveToSlide('next'), 5000);
    });
});