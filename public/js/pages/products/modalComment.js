document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("review-modal-container");
    const openModalBtn = document.getElementById("addComment-btn");
    const closeModalBtn = document.querySelector(".close-btn");

    if (openModalBtn) {
        openModalBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const stars = document.querySelectorAll(".rating-select .star");
    const ratingInput = document.getElementById("rating-input");

    stars.forEach(star => {
        star.addEventListener("mouseover", () => {
            const value = star.dataset.value;
            stars.forEach(s => s.classList.toggle("active", parseInt(s.dataset.value) <= value));
        });

        star.addEventListener("click", () => {
            ratingInput.value = star.dataset.value;
        });
    });
});

