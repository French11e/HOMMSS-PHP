document.addEventListener("DOMContentLoaded", function () {
    const ratingStars = document.querySelectorAll(".rating-star");
    const ratingInput = document.getElementById("form-input-rating");

    if (ratingStars.length > 0) {
        ratingStars.forEach((star) => {
            star.addEventListener("click", function () {
                const rating = this.getAttribute("data-rating");
                ratingInput.value = rating;

                // Reset all stars
                ratingStars.forEach((s) => {
                    s.style.fill = "#ccc";
                });

                // Fill stars up to selected rating
                for (let i = 0; i < rating; i++) {
                    ratingStars[i].style.fill = "#ffc107";
                }
            });

            star.addEventListener("mouseover", function () {
                const rating = this.getAttribute("data-rating");

                // Reset all stars
                ratingStars.forEach((s) => {
                    s.style.fill = "#ccc";
                });

                // Fill stars up to hovered rating
                for (let i = 0; i < rating; i++) {
                    ratingStars[i].style.fill = "#ffc107";
                }
            });

            star.addEventListener("mouseout", function () {
                const selectedRating = ratingInput.value;

                // Reset all stars
                ratingStars.forEach((s) => {
                    s.style.fill = "#ccc";
                });

                // Fill stars up to selected rating (if any)
                if (selectedRating) {
                    for (let i = 0; i < selectedRating; i++) {
                        ratingStars[i].style.fill = "#ffc107";
                    }
                }
            });
        });
    }
});
