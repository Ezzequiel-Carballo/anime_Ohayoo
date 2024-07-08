document.addEventListener("DOMContentLoaded", function () {
    console.log('el js se esta ejecutando');

    // Configuración para el primer carrusel (exhibition)
    function setupExhibitionCarousel() {
        const carouselItems = document.querySelectorAll(".exhibition .carousel-item");
        const prevButton = document.getElementById("prevButton");
        const nextButton = document.getElementById("nextButton");
        let currentIndex = 0;

        function showItem(index) {
            carouselItems.forEach((item, i) => {
                item.classList.toggle("active", i === index);
            });
        }

        prevButton.addEventListener("click", function () {
            currentIndex = (currentIndex === 0) ? carouselItems.length - 1 : currentIndex - 1;
            showItem(currentIndex);
        });

        nextButton.addEventListener("click", function () {
            currentIndex = (currentIndex === carouselItems.length - 1) ? 0 : currentIndex + 1;
            showItem(currentIndex);
        });

        function autoSlide() {
            currentIndex = (currentIndex === carouselItems.length - 1) ? 0 : currentIndex + 1;
            showItem(currentIndex);
        }

        setInterval(autoSlide, 10000); // Cambia la imagen cada 10 segundos
        showItem(currentIndex);
    }

    // Configuración para el segundo carrusel
    function setupSecondCarousel() {
        const carousel = document.querySelector(".carousel-container .carousel");
        const sections = document.querySelectorAll(".carousel-container .carousel-section");
        const prevButton = document.querySelector(".carousel-container .prev");
        const nextButton = document.querySelector(".carousel-container .next");
        let currentIndex = 0;
        const totalSections = sections.length;

        function updateCarousel() {
            const offset = currentIndex * -100; // Adjust the percentage based on the section index
            carousel.style.transform = `translateX(${offset}%)`;
        }

        prevButton.addEventListener("click", function () {
            currentIndex = (currentIndex === 0) ? totalSections - 1 : currentIndex - 1;
            updateCarousel();
        });

        nextButton.addEventListener("click", function () {
            currentIndex = (currentIndex === totalSections - 1) ? 0 : currentIndex + 1;
            updateCarousel();
        });

        updateCarousel(); // Mostrar la primera sección al cargar la página
    }

    setupExhibitionCarousel();
    setupSecondCarousel();
});
