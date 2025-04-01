document.addEventListener('DOMContentLoaded', () => {
    // Element References
    const hamburger = document.getElementById('hamburger');
    const searchBar = document.getElementById('searchBar');
    const exportButton = document.querySelector('.export-btn');
    const leadsTable = document.getElementById('leadsTable').getElementsByTagName('tbody')[0];
    const viewSelect = document.getElementById('viewSelect');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const currentPageSpan = document.getElementById('currentPage');
    const totalPagesSpan = document.getElementById('totalPages');
    const notification = document.getElementById('notification');
    const messageSpan = document.getElementById('message');
    const closeNotificationButton = document.querySelector('.notification .close-btn');
    const SaveBtn = document.getElementById('SaveBtn');
    const CancelBtn = document.getElementById('CancelBtn');

    let currentPage = 1;
    let leadsPerPage = parseInt(viewSelect.value);
    let filteredLeads = [...leads]; // Clone of leads array
    let totalPages = Math.ceil(filteredLeads.length / leadsPerPage) || 1;

    let unsavedChanges = false;

    // Toggle navigation links on hamburger click
    const navLinks = document.getElementById('navLinks'); // Ensure you have this element
    const toggleNav = () => {
        navLinks.classList.toggle('active');
        const isActive = navLinks.classList.contains('active');
        hamburger.setAttribute('aria-expanded', isActive);
    };

    hamburger.addEventListener('click', toggleNav);

    // Close nav-links when a link is clicked on mobile
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (isMobile()) {
                navLinks.classList.remove('active');
                hamburger.setAttribute('aria-expanded', false);
            }
        });
    });

    // Function to Detect if the Device is Mobile
    function isMobile() {
        return window.innerWidth <= 768;
    }

    // Initialize Pagination
    updatePagination();

    // Event Listener for View Select
    viewSelect.addEventListener('change', () => {
        leadsPerPage = parseInt(viewSelect.value);
        currentPage = 1;
        totalPages = Math.ceil(filteredLeads.length / leadsPerPage) || 1;
        updatePagination();
        renderLeads();
    });

    // Event Listeners for Pagination Buttons
    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
            renderLeads();
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
            renderLeads();
        }
    });

    // Function to Update Pagination Controls
    function updatePagination() {
        totalPages = Math.ceil(filteredLeads.length / leadsPerPage) || 1;
        currentPageSpan.textContent = currentPage;
        totalPagesSpan.textContent = totalPages;

        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;
    }

    // Function to Render Leads Based on Current Page and Filters
    function renderLeads() {
        leadsTable.innerHTML = '';

        const start = (currentPage - 1) * leadsPerPage;
        const end = start + leadsPerPage;
        const leadsToDisplay = filteredLeads.slice(start, end);

        leadsToDisplay.forEach(lead => {
            const row = leadsTable.insertRow();
            row.dataset.id = lead.id; // Assign lead ID to the row for easy reference

            // Inside renderLeads() function
            headers.forEach(header => {
                const cell = row.insertCell();
                if (header === 'Follow_Up_Added_On') {
                    cell.textContent = lead[header] || '';
                } else if (header === 'Follow_Up') {
                    // Create radio buttons for Follow_Up
                    const yesLabel = document.createElement('label');
                    yesLabel.textContent = 'Yes';
                    const yesRadio = document.createElement('input');
                    yesRadio.type = 'radio';
                    yesRadio.name = `follow_up_${lead.id}`; // Unique name per lead
                    yesRadio.value = 'Yes';
                    if (lead[header].toLowerCase() === 'yes') {
                        yesRadio.checked = true;
                    }
                    yesLabel.prepend(yesRadio);
                
                    const noLabel = document.createElement('label');
                    noLabel.textContent = 'No';
                    const noRadio = document.createElement('input');
                    noRadio.type = 'radio';
                    noRadio.name = `follow_up_${lead.id}`;
                    noRadio.value = 'No';
                    if (lead[header].toLowerCase() === 'no') {
                        noRadio.checked = true;
                    }
                    noLabel.prepend(noRadio);
                
                    cell.appendChild(yesLabel);
                    cell.appendChild(noLabel);
                } else {
                    const span = document.createElement('span');
                    span.classList.add('editable');
                    span.dataset.field = header;
                    span.textContent = lead[header] || '';
                    span.setAttribute('tabindex', '0'); // Make focusable for accessibility
                    cell.appendChild(span);

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.classList.add('edit-input');
                    input.dataset.field = header;
                    input.value = lead[header] || '';
                    input.style.display = 'none';
                    input.setAttribute('aria-label', `Edit ${header}`);
                    cell.appendChild(input);
                }
            });
        });
    }

    // Initial Render
    renderLeads();

    // Debounce function to limit the rate at which a function can fire.
    function debounce(func, delay) {
        let debounceTimer;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        }
    }

    // Search Functionality
    searchBar.addEventListener('input', debounce(() => {
        const searchText = searchBar.value.toLowerCase();
        filteredLeads = leads.filter(lead =>
            Object.values(lead).some(value =>
                value && value.toLowerCase().includes(searchText)
            )
        );
        currentPage = 1;
        totalPages = Math.ceil(filteredLeads.length / leadsPerPage) || 1;
        updatePagination();
        renderLeads();
    }, 300));

    // Export Leads Functionality
    exportButton.addEventListener('click', () => {
        if (!filteredLeads || filteredLeads.length === 0) {
            showNotification('error', 'No leads available to export.');
            return;
        }

        const csvContent = convertLeadsToCSV(headers, filteredLeads);
        const filename = `Leads_${new Date().toISOString().slice(0,10)}.csv`;
        downloadCSV(csvContent, filename);
    });

    // Function to Convert Leads to CSV
    function convertLeadsToCSV(headers, data) {
        const csvRows = [headers.join(',')];
        data.forEach(lead => {
            const row = headers.map(header => {
                const escaped = ('' + lead[header]).replace(/"/g, '""');
                return `"${escaped}"`;
            });
            csvRows.push(row.join(','));
        });
        return csvRows.join('\n');
    }

    // Function to Download CSV
    function downloadCSV(csvContent, filename) {
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        if (navigator.msSaveBlob) { // IE 10+
            navigator.msSaveBlob(blob, filename);
        } else {
            const link = document.createElement("a");
            if (link.download !== undefined) { // Feature detection
                const url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    }

    // Notification Functions
    function showNotification(type, message) {
        messageSpan.textContent = message;

        // Remove existing type classes
        notification.classList.remove('success-notification', 'error-notification', 'show', 'hide');

        // Add the new type class
        if (type === 'success') {
            notification.classList.add('success-notification');
        } else if (type === 'error') {
            notification.classList.add('error-notification');
        }

        // Show the notification
        notification.classList.add('show');

        // Automatically hide after 5 seconds
        setTimeout(() => {
            hideNotification();
        }, 5000);
    }

    function hideNotification() {
        // Add hide animation
        notification.classList.add('hide');

        // Remove show class after animation completes
        notification.addEventListener('animationend', () => {
            notification.classList.remove('show', 'hide', 'success-notification', 'error-notification');
            notification.style.display = 'none';
        }, { once: true });
    }

    // Close Button Event Listener
    closeNotificationButton.addEventListener('click', hideNotification);

    // Editable Fields Functionality
    leadsTable.addEventListener('click', (e) => {
        if (e.target.classList.contains('editable')) {
            const span = e.target;
            const input = span.nextElementSibling;
            const row = span.closest('tr');

            span.style.display = 'none';
            input.style.display = 'inline-block';
            input.focus();

            // Enable Save and Cancel Buttons
            SaveBtn.disabled = false;
            CancelBtn.disabled = false;

            // Set unsavedChanges flag
            unsavedChanges = true;
        }
    });

    // Handle Enter and Space keys for accessibility
    leadsTable.addEventListener('keydown', (e) => {
        if (e.target.classList.contains('editable') && (e.key === 'Enter' || e.key === ' ')) {
            e.preventDefault();
            e.target.click();
        }
    });

    // Track changes in input fields
    leadsTable.addEventListener('input', (e) => {
        if (e.target.classList.contains('edit-input')) {
            unsavedChanges = true;
            SaveBtn.disabled = false;
            CancelBtn.disabled = false;
        }
    });

    // Handle Save Button Click
    SaveBtn.addEventListener('click', () => {
        if (!unsavedChanges) {
            showNotification('error', 'No changes to save.');
            return;
        }

        // Gather all modified leads
        const modifiedLeads = [];
        const rows = leadsTable.querySelectorAll('tr');

        rows.forEach(row => {
            const leadId = row.dataset.id;
            const inputs = row.querySelectorAll('.edit-input');
            const updatedData = {};

            inputs.forEach(input => {
                const field = input.dataset.field;
                const newValue = input.value.trim();
                const originalValue = leads.find(lead => lead.id == leadId)[field] || '';

                if (newValue !== originalValue) {
                    updatedData[field] = newValue;
                }
            });

            // Handle Follow_Up radio buttons
            const followUpRadios = row.querySelectorAll(`input[name="follow_up_${leadId}"]`);
            followUpRadios.forEach(radio => {
                if (radio.checked) {
                    const newFollowUp = radio.value;
                    const originalFollowUp = leads.find(lead => lead.id == leadId)['Follow_Up'] || '';

                    if (newFollowUp.toLowerCase() !== originalFollowUp.toLowerCase()) {
                    updatedData['Follow_Up'] = newFollowUp;
                    }
            }
        });

            if (Object.keys(updatedData).length > 0) {
                modifiedLeads.push({ leadId, updatedData });
            }
        });

        if (modifiedLeads.length === 0) {
            showNotification('error', 'No changes to save.');
            SaveBtn.disabled = true;
            CancelBtn.disabled = true;
            unsavedChanges = false;
            return;
        }

        // Disable Save and Cancel buttons to prevent multiple submissions
        SaveBtn.disabled = true;
        CancelBtn.disabled = true;

        // Send POST request to edit_leads_function.php
        fetch('../php/edit_leads_function.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ updates: modifiedLeads })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', 'Leads updated successfully!');

                // Update the leads array and UI
                modifiedLeads.forEach(update => {
                    const { leadId, updatedData } = update;
                    const leadIndex = leads.findIndex(lead => lead.id == leadId);
                    if (leadIndex !== -1) {
                        Object.assign(leads[leadIndex], updatedData);

                        // Handle Follow_Up display
                    if (updatedData.hasOwnProperty('Follow_Up')) {
                        leads[leadIndex]['Follow_Up'] = updatedData['Follow_Up'] === 'Yes' ? 'Yes' : 'No';
                        // 'Follow_Up_Added_On' is handled by the backend
                    }

                    // Update the table display
                    const row = leadsTable.querySelector(`tr[data-id="${update.leadId}"]`);
                    if (row) {
                        headers.forEach(header => {
                            if (header === 'Follow_Up_Added_On') return; // Skip non-editable fields

                            if (header === 'Follow_Up') {
                                // Update radio buttons
                                const followUpRadios = row.querySelectorAll(`input[name="follow_up_${leadId}"]`);
                                followUpRadios.forEach(radio => {
                                    if (radio.value === leads[leadIndex]['Follow_Up']) {
                                        radio.checked = true;
                                    } else {
                                        radio.checked = false;
                                    }
                                });
                            } else {
                                const cell = row.querySelector(`td:nth-child(${headers.indexOf(header) + 1})`);
                                const span = cell.querySelector('.editable');
                                const input = cell.querySelector('.edit-input');

                                if (updatedData.hasOwnProperty(header)) {
                                    span.textContent = updatedData[header];
                                    input.value = updatedData[header];
                                }
                            }
                        });
                    }
                }
            });

                // Reset unsavedChanges flag
                unsavedChanges = false;

                // Disable Save and Cancel buttons
                SaveBtn.disabled = true;
                CancelBtn.disabled = true;
            } else {
                // Handle errors returned from the server
                const errorMessage = data.message || 'Failed to update leads.';
                showNotification('error', errorMessage);

                // Re-enable Save and Cancel buttons to allow retry
                SaveBtn.disabled = false;
                CancelBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error updating leads:', error);
            showNotification('error', 'An error occurred while updating leads.');

            // Re-enable Save and Cancel buttons to allow retry
            SaveBtn.disabled = false;
            CancelBtn.disabled = false;
        });
    });

    // Handle Cancel Button Click
    CancelBtn.addEventListener('click', () => {
        if (!unsavedChanges) {
            showNotification('error', 'No changes to cancel.');
            return;
        }

        // Revert all inputs to their original spans
        const rows = leadsTable.querySelectorAll('tr');

        rows.forEach(row => {
            const leadId = row.dataset.id;
            const inputs = row.querySelectorAll('.edit-input');

            inputs.forEach(input => {
                const field = input.dataset.field;
                const span = input.previousElementSibling;
                const originalValue = leads.find(lead => lead.id == leadId)[field] || '';

                span.textContent = originalValue;
                span.style.display = 'inline-block';
                input.style.display = 'none';
                input.value = originalValue;
            });
        });

        // Reset unsavedChanges flag
        unsavedChanges = false;

        // Disable Save and Cancel buttons
        SaveBtn.disabled = true;
        CancelBtn.disabled = true;

        showNotification('success', 'All changes have been canceled.');
    });

    // Prevent navigating away with unsaved changes
    window.addEventListener('beforeunload', (e) => {
        if (unsavedChanges) {
            e.preventDefault();
            e.returnValue = ''; // Required for Chrome
        }
    });
});