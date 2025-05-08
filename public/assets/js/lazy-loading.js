document.addEventListener("DOMContentLoaded", function () {
    // Check if IntersectionObserver is supported
    if ("IntersectionObserver" in window) {
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');

        const imageObserver = new IntersectionObserver(function (
            entries,
            observer
        ) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    const lazyImage = entry.target;

                    // Only replace src if data-src exists
                    if (lazyImage.dataset.src) {
                        lazyImage.src = lazyImage.dataset.src;
                    }

                    // If there's a data-srcset attribute
                    if (lazyImage.dataset.srcset) {
                        lazyImage.srcset = lazyImage.dataset.srcset;
                    }

                    lazyImage.classList.remove("lazy");
                    imageObserver.unobserve(lazyImage);
                }
            });
        });

        lazyImages.forEach(function (lazyImage) {
            imageObserver.observe(lazyImage);
        });
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        // This will load all images immediately
        let lazyImages = document.querySelectorAll('img[loading="lazy"]');
        lazyImages.forEach(function (lazyImage) {
            if (lazyImage.dataset.src) {
                lazyImage.src = lazyImage.dataset.src;
            }
            if (lazyImage.dataset.srcset) {
                lazyImage.srcset = lazyImage.dataset.srcset;
            }
        });
    }
});
