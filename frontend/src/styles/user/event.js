let currentSlideIndex = 1;
showSlide(currentSlideIndex);

function moveSlide(n) {
  showSlide(currentSlideIndex += n);
}

function currentSlide(n) {
  showSlide(currentSlideIndex = n);
}

function showSlide(n) {
  const slides = document.getElementsByClassName("slide");
  const dots = document.getElementsByClassName("dot");
  
  if (n > slides.length) {
    currentSlideIndex = 1;
  }
  if (n < 1) {
    currentSlideIndex = slides.length;
  }
  
  // Hide all slides
  for (let i = 0; i < slides.length; i++) {
    slides[i].classList.remove("active");
  }
  
  // Deactivate all dots
  for (let i = 0; i < dots.length; i++) {
    dots[i].classList.remove("active");
  }
  
  // Show current slide and activate corresponding dot
  slides[currentSlideIndex-1].classList.add("active");
  dots[currentSlideIndex-1].classList.add("active");
}

// Optional: Auto-advance slides every 5 seconds
setInterval(() => {
  moveSlide(1);
}, 5000);