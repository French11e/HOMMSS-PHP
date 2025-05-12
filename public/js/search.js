/*  */// Enhanced Search Functionality
document.addEventListener("DOMContentLoaded", function () {
    // Elements
    const searchInputs = document.querySelectorAll(".search-field__input");
    const searchResetBtns = document.querySelectorAll(".search-popup__reset");

    // Initialize search functionality for all search inputs
    searchInputs.forEach((input) => {
        const searchField = input.closest(".search-field");
        const searchResults =
            searchField.querySelector(".box-content-search") ||
            document.getElementById("box-content-search");
        const resetBtn = searchField.querySelector(".search-popup__reset");

        // Focus event
        input.addEventListener("focus", function () {
            if (this.value.trim().length > 2) {
                searchResults.classList.add("active");
            }
        });

        // Input event for live search
        input.addEventListener(
            "input",
            debounce(function () {
                const query = this.value.trim();

                // Show/hide reset button
                if (resetBtn) {
                    if (query.length > 0) {
                        resetBtn.style.opacity = "1";
                        resetBtn.style.visibility = "visible";
                    } else {
                        resetBtn.style.opacity = "0";
                        resetBtn.style.visibility = "hidden";
                    }
                }

                // Only search if query is at least 3 characters
                if (query.length > 2) {
                    performSearch(query, searchResults);
                } else {
                    searchResults.classList.remove("active");
                }
            }, 300)
        );

        // Reset button click
        if (resetBtn) {
            resetBtn.addEventListener("click", function () {
                input.value = "";
                searchResults.classList.remove("active");
                resetBtn.style.opacity = "0";
                resetBtn.style.visibility = "hidden";
                input.focus();
            });
        }
    });

    // Close search results when clicking outside
    document.addEventListener("click", function (e) {
        if (!e.target.closest(".search-field")) {
            document.querySelectorAll(".box-content-search").forEach((box) => {
                box.classList.remove("active");
            });
        }
    });

    // Perform AJAX search
    function performSearch(query, resultsContainer) {
        // Show loading indicator
        let loadingIndicator =
            resultsContainer.querySelector(".search-loading");
        if (!loadingIndicator) {
            loadingIndicator = document.createElement("div");
            loadingIndicator.className = "search-loading";
            loadingIndicator.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="6" x2="12" y2="12"></line>
                </svg>
                <p>Searching...</p>
            `;
            resultsContainer.appendChild(loadingIndicator);
        }
        loadingIndicator.classList.add("active");

        // Make AJAX request
        $.ajax({
            type: "GET",
            url: "/search", // Make sure this matches your route
            data: { query: query },
            dataType: "json",
            success: function (data) {
                // Remove loading indicator
                loadingIndicator.classList.remove("active");

                // Clear previous results
                resultsContainer.innerHTML = "";

                // Show results container
                resultsContainer.classList.add("active");

                // Check if we have results
                if (data.length > 0) {
                    // Create search results container
                    const searchResultsContainer = document.createElement("div");
                    
                    // Add header
                    const header = document.createElement("div");
                    header.className = "search-results-header";
                    header.innerHTML = `
                        <h4>Search Results</h4>
                        <span class="search-results-count">${data.length}</span>
                    `;
                    searchResultsContainer.appendChild(header);
                    
                    // Create results list
                    const resultsList = document.createElement("ul");
                    resultsList.className = "search-results-list";

                    // Add results
                    data.forEach((item) => {
                        const link = `/product/${item.slug}`;
                        const price = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD'
                        }).format(item.regular_price);
                        
                        const resultItem = document.createElement("li");
                        resultItem.innerHTML = `
                            <a href="${link}" class="product-item">
                                <div class="image">
                                    <img src="/uploads/products/thumbnails/${item.image}" alt="${item.name}" loading="lazy">
                                </div>
                                <div class="content">
                                    <div class="name">${item.name}</div>
                                    <div class="price">${price}</div>
                                    ${item.category ? `<div class="category">${item.category.name}</div>` : ''}
                                </div>
                            </a>
                        `;
                        resultsList.appendChild(resultItem);
                    });
                    
                    searchResultsContainer.appendChild(resultsList);
                    
                    // Add "View All Results" button if there are more than 10 results
                    if (data.length >= 10) {
                        const viewAllLink = document.createElement("a");
                        viewAllLink.href = `/shop?search=${encodeURIComponent(query)}`;
                        viewAllLink.className = "view-all-results";
                        viewAllLink.textContent = "View All Results";
                        searchResultsContainer.appendChild(viewAllLink);
                    }
                    
                    resultsContainer.appendChild(searchResultsContainer);
                } else {
                    // No results found
                    const noResults = document.createElement("div");
                    noResults.className = "search-empty-results";
                    noResults.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        <h4>No results found</h4>
                        <p>Try different keywords or check spelling</p>
                    `;
                    resultsContainer.appendChild(noResults);
                }
            },
            error: function (error) {
                console.error("Search error:", error);
                loadingIndicator.classList.remove("active");

                // Show error message
                resultsContainer.innerHTML = `
                    <div class="search-empty-results">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <h4>Something went wrong</h4>
                        <p>Please try again later</p>
                    </div>
                `;
                resultsContainer.classList.add("active");
            }
        });
    }

    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
});

