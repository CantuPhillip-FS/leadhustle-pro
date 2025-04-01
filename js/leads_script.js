document.addEventListener('DOMContentLoaded', () => {
    const searchBar = document.getElementById('searchBar');
    const leadsContainer = document.getElementById('leadsContainer');
    const leadDetailsDiv = document.getElementById('leadDetails');
    const leadDetailsContent = document.getElementById('leadDetailsContent');
    const leadNotes = document.getElementById('leadNotes');
    const saveNotesButton = document.getElementById('saveNotesButton');
    const followUpNo = document.getElementById('followUpNo');
    const followUpYes = document.getElementById('followUpYes');
    // Detect changes in leadNotes textarea
    leadNotes.addEventListener('input', () => {
        hasUnsavedChanges = true;
    });

    // Detect changes in follow-up radio buttons
    followUpNo.addEventListener('change', () => {
        hasUnsavedChanges = true;
    });
    followUpYes.addEventListener('change', () => {
        hasUnsavedChanges = true;
    });
    const filterButton = document.querySelector('.filter');
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    const closeBtn = document.getElementById('closeBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const prevArrow = document.querySelector('.prev-arrow');
    const nextArrow = document.querySelector('.next-arrow');
    const notification = document.getElementById('notification');
    const messageSpan = document.getElementById('message');
    const closeNotificationButton = document.querySelector('.notification .close-btn');


    let lead = null;
    let isFilterActive = false;
    let isDataUpdated = false;
    let currentLeads = leads;
    let hasUnsavedChanges = false;

    // Function to show notifications
    function showNotification(type, message) {
        messageSpan.textContent = message;
    
        // Ensure the notification is displayed
        notification.style.display = 'block';
    
        // Remove existing notification classes
        notification.classList.remove('success-notification', 'error-notification', 'show', 'hide');
    
        // Add the new type class
        if (type === 'success') {
            notification.classList.add('success-notification');
        } else if (type === 'error') {
            notification.classList.add('error-notification');
        }
    
        // Show the notification
        notification.classList.add('show');
    
        // Hide the notification after 5 seconds
        setTimeout(() => {
            hideNotification();
        }, 5000);
    }

    // Function to hide notifications
    function hideNotification() {
        notification.classList.add('hide');

            // Remove show class after animation completes
            notification.addEventListener('animationend', () => {
                notification.classList.remove('show', 'hide', 'success-notification', 'error-notification');
                notification.style.display = 'none';
            }, { once: true });
        }
    
        // **Close Button Event Listener**
        closeNotificationButton.addEventListener('click', hideNotification);

    // **Function to Update Arrows Visibility and State**
    const updateArrows = (currentIndex, totalLeads) => {
        // Previous Arrow
        if (currentIndex === 0) {
            prevArrow.classList.add('disabled', 'hidden');
            prevArrow.classList.remove('visible');
        } else {
            prevArrow.classList.remove('disabled', 'hidden');
            prevArrow.classList.add('visible');
        }

        // Next Arrow
        if (currentIndex === totalLeads - 1) {
            nextArrow.classList.add('disabled', 'hidden');
            nextArrow.classList.remove('visible');
        } else {
            nextArrow.classList.remove('disabled', 'hidden');
            nextArrow.classList.add('visible');
        }

        // If there's only one lead, hide and disable both arrows
        if (totalLeads === 1) {
            prevArrow.classList.add('disabled', 'hidden');
            nextArrow.classList.add('disabled', 'hidden');
            prevArrow.classList.remove('visible');
            nextArrow.classList.remove('visible');
        }
    };

    // Toggle navigation links on hamburger click
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

    // Detect if the device is mobile
    const isMobile = () => window.innerWidth <= 768;

    // Export CSV functionality
    const exportButton = document.querySelector('.Btn');

    const convertLeadsToCSV = (headers, data) => {
        const csvRows = [headers.join(',')];
        data.forEach(lead => {
            const row = headers.map(header => {
                const escaped = ('' + lead[header]).replace(/"/g, '\\"');
                return `"${escaped}"`;
            });
            csvRows.push(row.join(','));
        });
        return csvRows.join('\n');
    };

    const downloadCSV = (csvContent, filename) => {
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        if (navigator.msSaveBlob) {
            navigator.msSaveBlob(blob, filename);
        } else {
            const link = document.createElement("a");
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    };

    exportButton.addEventListener('click', () => {
        if (!leads || leads.length === 0) {
            alert('No leads available to export.');
            return;
        }

        const csvContent = convertLeadsToCSV(headers, leads);
        const filename = `Leads_${new Date().toISOString().slice(0,10)}.csv`;
        downloadCSV(csvContent, filename);
    });

    // Select the Scroll Buttons
    const scrollTopBtn = document.querySelector('.scroll-top');
    const scrollBottomBtn = document.querySelector('.scroll-bottom');

    // Add Click Event Listener to Scroll to Top
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Add Click Event Listener to Scroll to Bottom
    scrollBottomBtn.addEventListener('click', () => {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth'
        });
    });

    // Optional: Add Keyboard Accessibility (Enter and Space Keys)
    scrollTopBtn.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            scrollTopBtn.click();
        }
    });

    scrollBottomBtn.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            scrollBottomBtn.click();
        }
    });

    // Function to Toggle Scroll Buttons Visibility Based on Scroll Position
    const toggleScrollButtons = () => {
        const scrollPosition = window.scrollY || window.pageYOffset;
        const windowHeight = window.innerHeight;
        const documentHeight = document.body.scrollHeight;

        if (scrollPosition > windowHeight / 2) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }

        if (scrollPosition < documentHeight - windowHeight / 2) {
            scrollBottomBtn.classList.add('show');
        } else {
            scrollBottomBtn.classList.remove('show');
        }
    };

    // Initial Check
    toggleScrollButtons();

    // Add Scroll Event Listener
    window.addEventListener('scroll', toggleScrollButtons);

    // Hide Buttons When Modal is Active (Override Any Display Styles)
    const observer = new MutationObserver(() => {
        const isModalActive = modalOverlay.classList.contains('active');
        if (isModalActive) {
            scrollTopBtn.classList.remove('show');
            scrollBottomBtn.classList.remove('show');
        } else {
            toggleScrollButtons();
        }
    });

    // Observe Changes to the Modal's Class List
    observer.observe(modalOverlay, { attributes: true, attributeFilter: ['class'] });

 // Update Lead Details and show modal
const updateLeadDetails = (selectedLead) => {
    lead = selectedLead;

    hasUnsavedChanges = false; // Reset unsaved changes flag

    let leadDetailsHTML = '';
    headers.forEach(header => {
        if (!['Notes', 'Follow_Up'].includes(header) && lead[header] && lead[header] !== 'No Data') {
            const headerLower = header.toLowerCase();
    
            // Check if the current header is "Phone"
            if (headerLower === 'phone') {
                // Create a tel: link for the phone number
                const phoneNumber = lead[header];
                leadDetailsHTML += `
                    <div>
                        <strong>${header.replace(/_/g, ' ')}:</strong> <a href="tel:${phoneNumber}" class="phone-numbers"><i class="fas fa-phone"></i> ${formatPhoneNumber(phoneNumber)}</a>
                    </div>
                `;
            } 
            // Check if the current header is "Email"
            else if (headerLower === 'email') {
                const emailAddress = lead[header];
                leadDetailsHTML += `
                    <div>
                        <strong>${header.replace(/_/g, ' ')}:</strong> <a href="mailto:${emailAddress}" class="email-links"><i class="fas fa-envelope"></i> ${escapeHtml(emailAddress)}</a>
                    </div>
                `;
            } 
            else {
                // Display other fields as usual with HTML escaping
                leadDetailsHTML += `
                    <div>
                        <strong>${header.replace(/_/g, ' ')}:</strong> ${escapeHtml(lead[header])}
                    </div>
                `;
            }
        }
    });

    leadDetailsContent.innerHTML = leadDetailsHTML;
    leadNotes.value = lead.Notes || '';

    if (lead.Follow_Up === "Yes") {
        followUpYes.checked = true;
    } else {
        followUpNo.checked = true;
    }

    modalOverlay.classList.add('active');
    document.body.classList.add('no-scroll');

    // Find the index of the current lead and update arrows
    const currentIndex = leads.findIndex(l => l.id === lead.id);
    updateArrows(currentIndex, leads.length);

    // Scroll the current lead card into view within the leads container
    const currentLeadCard = leadsContainer.querySelector(`.lead-card[data-id="${lead.id}"]`);
    if (currentLeadCard) {
        currentLeadCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
};

/**
 * Formats a phone number from international format to (XXX) XXX-XXXX.
 * Assumes US phone numbers starting with +1.
 * Example: +19283024001 -> (928) 302-4001
 * 
 * @param {string} phone - The phone number in international format.
 * @returns {string} - The formatted phone number.
 */
function formatPhoneNumber(phone) {
    // Remove any non-digit characters
    const cleaned = ('' + phone).replace(/\D/g, '');
    
    // Check if the number starts with '1' and is 11 digits long
    const match = cleaned.match(/^1?(\d{3})(\d{3})(\d{4})$/);
    
    if (match) {
        return `(${match[1]}) ${match[2]}-${match[3]}`;
    }
    
    // If not a valid US number, return as is
    return phone;
}

/**
 * Escapes HTML characters to prevent XSS attacks.
 * 
 * @param {string} unsafe - The string to escape.
 * @returns {string} - The escaped string.
 */
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}


    const closePopup = () => {
        if (hasUnsavedChanges) {
            const confirmClose = confirm("You have unsaved changes. Are you sure you want to close without saving?");
            if (!confirmClose) {
                return; // Do not close the modal
            }
        }

        // Proceed to close the modal
        modalOverlay.classList.remove('active');
        document.body.classList.remove('no-scroll');

        // Check if data was updated and re-render leads if necessary
        if (isDataUpdated) {
            renderLeads(currentLeads);
            isDataUpdated = false; // Reset the flag
        }

        // Reset the unsaved changes flag
        hasUnsavedChanges = false;
    };

    // Event listener for the Close button inside the modal
    closeBtn.addEventListener('click', closePopup);

    // Event listener for clicks outside the modal content to close the modal
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closePopup();
        }
    });

    // Event listener for the 'Escape' key to close the modal on mobile devices
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isMobile()) {
            closePopup();
        }
    });

    // Add Click Event Listener to Previous Arrow
    prevArrow.addEventListener('click', () => {
        if (prevArrow.classList.contains('disabled') || !lead) return; // Prevent action if disabled or no lead


        // Check for unsaved changes
        if (hasUnsavedChanges) {
            const confirmNavigation = confirm("You have unsaved changes. Do you want to discard them and navigate to the previous lead?");
            if (!confirmNavigation) {
                return; // User chose to cancel navigation
            }
        }

        // Find the index of the current lead within currentLeads
        const currentIndex = currentLeads.findIndex(l => l.id === lead.id);
        if (currentIndex <= 0) return; // Already at the first lead

        // Calculate the index of the previous lead
        const prevIndex = currentIndex - 1;
        const prevLead = currentLeads[prevIndex];

        if (prevLead) {
            // Remove 'highlighted' class from all lead cards
            document.querySelectorAll('.lead-card').forEach(card => card.classList.remove('highlighted'));

            // Add 'highlighted' class to the previous lead card
            const prevLeadCard = leadsContainer.querySelector(`.lead-card[data-id="${prevLead.id}"]`);
            if (prevLeadCard) prevLeadCard.classList.add('highlighted');

            // Update the modal with the previous lead's details
            updateLeadDetails(prevLead);
        }
    });
    // Add Keyboard Accessibility to Previous Arrow
    prevArrow.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault(); // Prevent default scroll behavior for Space key

            // Check for unsaved changes
            if (hasUnsavedChanges) {
                const confirmNavigation = confirm("You have unsaved changes. Do you want to discard them and navigate to the previous lead?");
                if (!confirmNavigation) {
                    return; // User chose to cancel navigation
                }
            }

            // Trigger the click event programmatically
            prevArrow.click();
        }
    });

    // Add Click Event Listener to Next Arrow
    nextArrow.addEventListener('click', () => {
        if (nextArrow.classList.contains('disabled') || !lead) return; // Prevent action if disabled or no lead

        // Check for unsaved changes
        if (hasUnsavedChanges) {
            const confirmNavigation = confirm("You have unsaved changes. Do you want to discard them and navigate to the next lead?");
            if (!confirmNavigation) {
                return; // User chose to cancel navigation
            }
        }

        // Find the index of the current lead within currentLeads
        const currentIndex = currentLeads.findIndex(l => l.id === lead.id);
        if (currentIndex === -1 || currentIndex >= currentLeads.length - 1) return; // Already at the last lead

        // Calculate the index of the next lead
        const nextIndex = currentIndex + 1;
        const nextLead = currentLeads[nextIndex];

        if (nextLead) {
            // Remove 'highlighted' class from all lead cards
            document.querySelectorAll('.lead-card').forEach(card => card.classList.remove('highlighted'));

            // Add 'highlighted' class to the next lead card
            const nextLeadCard = leadsContainer.querySelector(`.lead-card[data-id="${nextLead.id}"]`);
            if (nextLeadCard) nextLeadCard.classList.add('highlighted');

            // Update the modal with the next lead's details
            updateLeadDetails(nextLead);
        }
    });
    // Add Keyboard Accessibility to Next Arrow
    nextArrow.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault(); // Prevent default scroll behavior for Space key

            // Check for unsaved changes
            if (hasUnsavedChanges) {
                const confirmNavigation = confirm("You have unsaved changes. Do you want to discard them and navigate to the next lead?");
                if (!confirmNavigation) {
                    return; // User chose to cancel navigation
                }
            }

            // Trigger the click event programmatically
            nextArrow.click();
        }
    });

// Save notes and follow-up status
saveNotesButton.addEventListener('click', () => {
    if (!lead) {
        console.error('Lead is not defined.');
        showNotification('error', 'An unexpected error occurred. Please try again.');
        return;
    }

    const updatedNotes = leadNotes.value;
    const followUp = followUpYes.checked ? "1" : "0";

    console.log('Sending data:', { leadId: lead.id, notes: updatedNotes, followUp });

    fetch('../php/update_leads.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ leadId: lead.id, notes: updatedNotes, followUp })
    })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.text();
        })
        .then(data => {
            showNotification('success', 'Notes and follow-up status saved successfully!');
            console.log('Response:', data);
            lead.Notes = updatedNotes;
            lead.Follow_Up = followUp === "1" ? "Yes" : "No";
            
            // Set the flag to indicate that data has been updated
            isDataUpdated = true;

            // Reset the unsaved changes flag
            hasUnsavedChanges = false;
        })
        .catch(error => {
            console.error('Error saving:', error);
            showNotification('error', 'Failed to save. Please try again.');
        });
    });

    // Get reference to the Cancel button
    const cancelButton = document.getElementById('cancelButton');

    // Add Click Event Listener to Cancel Button
    cancelButton.addEventListener('click', () => {
        closePopup();
    });

    // Add Keyboard Accessibility to Cancel Button
    cancelButton.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault(); // Prevent default scroll behavior for Space key
            cancelButton.click(); // Trigger the click event
        }
    });

    // Render leads to the container
    const renderLeads = (leadsToRender) => {
        leadsContainer.innerHTML = '';

        // Update currentLeads to the leads being rendered
        currentLeads = leadsToRender;

        if (leadsToRender.length === 0) {
            leadsContainer.innerHTML = '<p>No leads available.</p>';
            return;
        }

        leadsToRender.forEach(leadItem => {
            const leadCard = document.createElement('div');
            leadCard.classList.add('lead-card');
            leadCard.dataset.id = leadItem.id;

            let leadContent = '';
            headers.forEach(header => {
                if (leadItem[header] && leadItem[header] !== 'No Data') {
                    leadContent += `
                        <div class="lead-detail-card">
                            <strong>${header.replace(/_/g, ' ')}:</strong> ${leadItem[header]}
                        </div>
                    `;
                }
            });
            leadCard.innerHTML = leadContent;

            leadCard.addEventListener('click', () => {
                document.querySelectorAll('.lead-card').forEach(card => card.classList.remove('highlighted'));
                leadCard.classList.add('highlighted');
                updateLeadDetails(leadItem);
            });

            leadsContainer.appendChild(leadCard);
        });
    };

    // Filter leads based on Follow-Up status
    filterButton.addEventListener('click', () => {
        isFilterActive = !isFilterActive;
        const filteredLeads = isFilterActive
            ? leads.filter(lead => lead.Follow_Up === 'Yes')
            : leads;
        renderLeads(filteredLeads);
        filterButton.classList.toggle('active', isFilterActive);
    });

    // Search leads functionality
    searchBar.addEventListener('input', () => {
        const searchText = searchBar.value.toLowerCase();
        let filteredLeads = leads.filter(lead =>
            Object.values(lead).some(value =>
                value && value.toLowerCase().includes(searchText)
            )
        );

        if (isFilterActive) {
            filteredLeads = filteredLeads.filter(lead => lead.Follow_Up === 'Yes');
        }

        renderLeads(filteredLeads);
    });

    // Handle window resize to adjust behavior
    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closePopup();
            if (lead) {
                leadDetailsDiv.classList.add('show');
                const currentIndex = leads.findIndex(l => l.id === lead.id);
                updateArrows(currentIndex, leads.length);
            } else {
                leadDetailsDiv.classList.remove('show');
                prevArrow.classList.add('hidden');
                nextArrow.classList.add('hidden');
            }
        } else {
            leadDetailsDiv.classList.remove('show');
            prevArrow.classList.add('hidden');
            nextArrow.classList.add('hidden');
        }
    });

    // Initial render of all leads
    renderLeads(leads);
});