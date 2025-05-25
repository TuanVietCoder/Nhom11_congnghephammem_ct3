let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

// Hàm chuyển slide tự động sau 3 giây
function changeSlide() {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % totalSlides;
    slides[currentSlide].classList.add('active');
}

// Chuyển slide khi nhấn vào nút "tiếp"
document.getElementById('next').addEventListener('click', function() {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % totalSlides;
    slides[currentSlide].classList.add('active');
});

// Chuyển slide khi nhấn vào nút "trái"
document.getElementById('prev').addEventListener('click', function() {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    slides[currentSlide].classList.add('active');
});

// Gọi hàm tự động chuyển slide mỗi 3 giây
setInterval(changeSlide, 5000);




// poppup
const openPopup = document.getElementById('openPopup');
    const closePopup = document.getElementById('closePopup');
    const popupOverlay = document.getElementById('popupOverlay');

    openPopup.addEventListener('click', () => {
        popupOverlay.style.display = 'flex';
    });

    closePopup.addEventListener('click', () => {
        popupOverlay.style.display = 'none';
    });

    // Close the popup when clicking outside the form
    popupOverlay.addEventListener('click', (e) => {
        if (e.target === popupOverlay) {
            popupOverlay.style.display = 'none';
        }
    });

 
    