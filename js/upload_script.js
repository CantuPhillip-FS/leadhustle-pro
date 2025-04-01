// Function to show notification
function showNotification(message, type = 'error') {
    const notification = document.getElementById('notification');
    const messageSpan = document.getElementById('message');
    if (notification && messageSpan) {
        // Set innerHTML with the appropriate icon
        messageSpan.innerHTML = type === 'error' 
            ? `<i class="fa-solid fa-circle-exclamation"></i> ${message}` 
            : `<i class="fa-solid fa-circle-check"></i> ${message}`;
        
        // Remove 'error-notification' and 'success-notification', then add based on type
        notification.classList.remove('error-notification', 'success-notification');
        if (type === 'success') {
            notification.classList.add('success-notification');
        } else {
            notification.classList.add('error-notification');
        }
        notification.style.display = 'flex'; // Ensure flex display for alignment
    }
}

function hideNotification() {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.style.display = 'none';
    }
}

// Define the closePopup function
function closePopup() {
    const popupOverlay = document.getElementById('popup-overlay');
    const loader = document.getElementById('loader');
    if (popupOverlay && loader) {
        popupOverlay.style.display = 'none';
        loader.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');

    // Function to toggle navigation links on hamburger click
    function toggleNav() {
        navLinks.classList.toggle('active');
        // Update ARIA attribute
        const isActive = navLinks.classList.contains('active');
        hamburger.setAttribute('aria-expanded', isActive);
    }

    // Event listener for hamburger icon
    if (hamburger) {
        hamburger.addEventListener('click', toggleNav);
    }

    // Function to close nav-links when a link is clicked (optional)
    if (navLinks) {
        const navLinkElements = navLinks.querySelectorAll('a');
        navLinkElements.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) { // Only on mobile
                    navLinks.classList.remove('active');
                    hamburger.setAttribute('aria-expanded', false);
                }
            });
        });
    }

    // Handle Upload Page Logic
    const dropzone = document.getElementById('dropzone');
    if (dropzone) {
        const fileInput = document.getElementById('fileInput');
        dropzone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', handleFileUpload);

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragging');
        });

        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragging'));

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragging');
            const files = e.dataTransfer.files;
            handleFileUpload({ target: { files } });
        });

        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
                    showNotification('Unsupported file type. Please upload a CSV file.', 'error');
                    return;
                }

                // Parse CSV file using PapaParse
                Papa.parse(file, {
                    complete: function (results) {
                        const headers = results.data[0];
                        const firstRow = results.data[1]; // Assuming the first row is the data
                        const data = results.data.slice(1); // Get all rows except the headers

                        // Store headers, first row, and data in localStorage
                        localStorage.setItem('csvHeaders', JSON.stringify(headers));
                        localStorage.setItem('csvFirstRow', JSON.stringify(firstRow));
                        localStorage.setItem('csvData', JSON.stringify(data)); // Store all data rows

                        // Redirect to confirm.php
                        window.location.href = 'confirm.php';
                    }
                });
            }
        }
    }

    // Back to Upload.php page Button Functionality
    const backToUpload = document.querySelector('.back-to-upload');
    if (backToUpload) {
        backToUpload.addEventListener('click', () => {
            window.location.href = 'upload.php';
        });
    }

    // Handle Confirm Page Logic
    const headersColumn = document.getElementById('headersColumn');
    const dataColumn = document.getElementById('dataColumn');

    if (headersColumn && dataColumn) {
        const headers = JSON.parse(localStorage.getItem('csvHeaders'));
        const firstRow = JSON.parse(localStorage.getItem('csvFirstRow'));

        if (headers && firstRow) {
            headers.forEach(header => {
                const headerElement = document.createElement('p');
                headerElement.textContent = header;
                headersColumn.appendChild(headerElement);
            });

            firstRow.forEach(data => {
                const dataElement = document.createElement('p');
                dataElement.textContent = data || ''; // Handle missing values
                dataColumn.appendChild(dataElement);
            });
        }
    }

    // Confirm Button (confirm.php)
    const confirmData = document.querySelector('.confirm-data');
    if (confirmData) {
        confirmData.addEventListener('click', () => {
            // Retrieve headers and data from localStorage
            const headers = JSON.parse(localStorage.getItem('csvHeaders'));
            const csvData = JSON.parse(localStorage.getItem('csvData'));
            const requiredColumns = ['Name', 'Phone'];
            const missingColumns = requiredColumns.filter(col => !headers.includes(col));

            // Debugging: Log data before fetch
            console.log('Headers:', headers);
            console.log('Data:', csvData);

            if (missingColumns.length > 0) {
                const missingList = missingColumns.join(', ');
                showNotification(`The following required columns are missing: ${missingList}. Please check your CSV file.`, 'error');
                return;
            }

            if (!headers || !csvData) {
                showNotification('Headers or data are missing before the fetch request.', 'error');
                return;
            }
            
            if (!Array.isArray(headers) || !Array.isArray(csvData)) {
                showNotification("Data is not correctly formatted as arrays.", 'error');
                return;
            }

            // Send headers and data to process_csv.php via AJAX
            fetch('../php/process_csv.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    headers: headers || [],
                    csvData: csvData || []
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text); });
                }
                return response.text();
            })
            .then(data => {
                // Set success message in localStorage
                localStorage.setItem('uploadSuccess', data);
                // Redirect immediately to dashboard.php
                window.location.href = 'dashboard.php';
            })
            .catch(error => {
                console.error('Error:', error);
                const message = error.message.startsWith('Error: ') ? error.message.replace('Error: ', '') : 'An error occurred while processing your data.';
                showNotification(message, 'error');
            });
        });
    }

    // Handle Success Notification on Upload Page
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        const successMessage = decodeURIComponent(urlParams.get('success'));
        showNotification(successMessage, 'success');

        // Optionally, remove the 'success' parameter from the URL to prevent the notification from showing again on refresh
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
});