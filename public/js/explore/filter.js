document.addEventListener('DOMContentLoaded', function () {
    // Restore collapsible states before adding any listeners
    var collapsibles = document.querySelectorAll('.collapsible');
    collapsibles.forEach(function(collapsible, index) {
        var content = collapsible.nextElementSibling;
        var state = localStorage.getItem('collapsible-' + index);
        if (state === 'open') {
            content.style.display = 'block';
            collapsible.classList.add('active');
        }
    });

    // Handle form-check-container click events
    document.querySelectorAll('.form-check-container').forEach(function(container) {
        container.addEventListener('click', function() {
            const checkbox = container.querySelector('.form-check-input');
            checkbox.checked = !checkbox.checked;
            container.classList.toggle('active', checkbox.checked);
            checkbox.dispatchEvent(new Event('change')); // Trigger change event to update filters
        });
    });

    // Apply active class to form-check-container elements based on checked checkboxes
    document.querySelectorAll('.form-check-input:checked').forEach(function(checkbox) {
        const container = checkbox.closest('.form-check-container');
        if (container) {
            container.classList.add('active');
        }
    });

    // AJAX Filter checkboxes
    document.querySelectorAll('.form-check-input').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const form = document.getElementById('filter-form');
            const formData = new FormData(form);
            const url = new URL(form.action, window.location.origin);
            formData.forEach((value, key) => url.searchParams.append(key, value));
            
            // Use Fetch API for AJAX request
            fetch(url, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(response => response.text())
                .then(html => {
                    document.querySelector('.game-cards').innerHTML = html;
                    updateActiveFilters();
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Clear Filters
    document.getElementById('clear-filters').addEventListener('click', function () {
        const form = document.getElementById('filter-form');
        const url = new URL(form.action, window.location.origin);

        // Maintain query and sort parameters
        const query = form.querySelector('input[name="query"]')?.value || '';
        const sort = form.querySelector('input[name="sort"]')?.value || '';
        if (query) url.searchParams.set('query', query);
        if (sort) url.searchParams.set('sort', sort);

        window.location.href = url.toString();
    });

    // Collapsible toggle functionality
    collapsibles.forEach(function(collapsible, index) {
        var content = collapsible.nextElementSibling;
        collapsible.addEventListener('click', function() {
            this.classList.toggle('active');
            if (content.style.display === "block") {
                content.style.display = "none";
                localStorage.setItem('collapsible-' + index, 'closed');
            } else {
                content.style.display = "block";
                localStorage.setItem('collapsible-' + index, 'open');
            }
        });
    });

    // "See More" and "Show Less" buttons
    function handleSeeMore(section) {
        const seeMoreBtn = document.getElementById(`see-more-btn-${section}`);
        if (seeMoreBtn) {
            seeMoreBtn.addEventListener('click', function () {
                const hiddenItems = document.querySelectorAll(`.${section} .hidden-category`);
                hiddenItems.forEach(function (item) {
                    item.style.display = 'block';
                });
                seeMoreBtn.style.display = 'none'; // Hide the "See More" button after clicking

                // Create and insert the "Show Less" button
                const showLessBtn = document.createElement('button');
                showLessBtn.type = 'button';
                showLessBtn.id = `show-less-btn-${section}`;
                showLessBtn.className = 'btn btn-link';
                showLessBtn.textContent = 'Show Less';
                seeMoreBtn.parentNode.appendChild(showLessBtn);

                // Add event listener to the "Show Less" button
                showLessBtn.addEventListener('click', function () {
                    hiddenItems.forEach(function (item) {
                        item.style.display = 'none';
                    });
                    seeMoreBtn.style.display = 'block'; // Show the "See More" button again
                    showLessBtn.remove(); // Remove the "Show Less" button
                });
            });
        }
    }

    handleSeeMore('category');
    handleSeeMore('platform');
    handleSeeMore('language');

    function updateActiveFilters() {
        const activeFiltersContainer = document.querySelector('.active-filters');
        activeFiltersContainer.innerHTML = ''; // Clear previous filters
    
        const filterNames = [];
    
        // Collect selected Categories
        document.querySelectorAll('input[name="categories[]"]:checked').forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`).textContent.trim();
            filterNames.push({ type: 'Category', value: label, id: input.id });
        });
    
        // Collect selected Platforms
        document.querySelectorAll('input[name="platforms[]"]:checked').forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`).textContent.trim();
            filterNames.push({ type: 'Platform', value: label, id: input.id });
        });
    
        // Collect selected Languages
        document.querySelectorAll('input[name="languages[]"]:checked').forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`).textContent.trim();
            filterNames.push({ type: 'Language', value: label, id: input.id });
        });
    
        // Collect selected Players
        document.querySelectorAll('input[name="players[]"]:checked').forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`).textContent.trim();
            filterNames.push({ type: 'Player', value: label, id: input.id });
        });
    
        // Append collected filters to the Active Filters section
        if (filterNames.length === 0) {
            activeFiltersContainer.innerHTML = '<span>No active filters</span>';
        } else {
            filterNames.forEach(filter => {
                const filterElement = document.createElement('div');
                filterElement.className = 'game-tags';
                filterElement.textContent = filter.value; // Display the filter value
            
                // Create the Font Awesome cross icon
                const removeIcon = document.createElement('i');
                removeIcon.className = 'fas fa-times remove-icon';
                removeIcon.style.marginLeft = '8px'; // Add space between the text and the icon
            
                // Add an event listener to remove the filter and uncheck the corresponding checkbox
                filterElement.addEventListener('click', function () {
                    const checkbox = document.getElementById(filter.id);
                    if (checkbox) {
                        checkbox.checked = false;
                        // Remove the active class from the container
                        const container = checkbox.closest('.form-check-container');
                        if (container) {
                            container.classList.remove('active');
                        }
                        // Trigger the filter change so the results update
                        const changeEvent = new Event('change');
                        checkbox.dispatchEvent(changeEvent);
                    }
                });
            
                // Append the Font Awesome icon to the filter tag
                filterElement.appendChild(removeIcon);
            
                // Append the filter element to the active filters container
                activeFiltersContainer.appendChild(filterElement);
            });
            
        }
    }

    updateActiveFilters();
});