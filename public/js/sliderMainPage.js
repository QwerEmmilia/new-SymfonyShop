function initializeSlider() {
    const sliderContainer = document.querySelector('.slider');
    const slider = document.querySelector('#slider');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const slides = document.querySelectorAll('#slider img');

    let currentSlideIndex = 0;

    prevBtn.addEventListener('click', showPreviousSlide);
    nextBtn.addEventListener('click', showNextSlide);

    function showPreviousSlide() {
        currentSlideIndex--;
        if (currentSlideIndex < 0) {
            currentSlideIndex = slides.length - 1;
        }
        updateSlideVisibility();
    }

    function showNextSlide() {
        currentSlideIndex++;
        if (currentSlideIndex >= slides.length) {
            currentSlideIndex = 0;
        }
        updateSlideVisibility();
    }

    function updateSlideVisibility() {
        slides.forEach((slide, index) => {
            if (index === currentSlideIndex) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
            }
        });
    }

    autoChangeSlide();

    function autoChangeSlide() {
        showNextSlide();
    }

    setInterval(autoChangeSlide, 10000);
}

document.addEventListener('DOMContentLoaded', () => {
    initializeSlider();
});
