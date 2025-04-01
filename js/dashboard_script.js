    // Function to show notification
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const messageSpan = document.getElementById('message');
        if (notification && messageSpan) {
            // Set innerHTML with the appropriate icon
            messageSpan.innerHTML = type === 'error' ? `<i class="fa-solid fa-circle-exclamation"></i> ${message}` : `<i class="fa-solid fa-circle-check"></i> ${message}`;
            
            // Remove existing type classes
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

    document.addEventListener('DOMContentLoaded', () => {
        // Check if there's a success message in localStorage
        const successMessage = localStorage.getItem('uploadSuccess');
        if (successMessage) {
            showNotification(successMessage, 'success');
            // Remove the success message from localStorage to prevent it from showing again on refresh
            localStorage.removeItem('uploadSuccess');
        }
    });

    //Hamburger menu functionality for mobile devices
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        const isActive = navLinks.classList.contains('active');
        hamburger.setAttribute('aria-expanded', isActive);
    });

    // Optional: Close the nav menu when a link is clicked (mobile experience)
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
        });
    });

// Update the Follow-ups card header and content
document.addEventListener('DOMContentLoaded', function () {
    const followUpsContainer = document.getElementById('followUpsContainer');

    fetch('/php/get_followups.php')
        .then(response => response.json())
        .then(data => {
            const followUpHeader = document.querySelector('.card h3');
            followUpHeader.textContent = `Follow-ups: ${data.length}`;

            if (data.length) {
                followUpsContainer.innerHTML = data
                    .map(lead => `<div class="contact"><span class="name"><strong>${lead.Name}</strong></span><span class="followup">Follow-Up: Yes</span></div>`)
                    .join('');
            } else {
                followUpsContainer.innerHTML = '<p>No leads require follow-up.</p>';
            }
        })
        .catch(error => console.error('Error fetching follow-ups:', error));
});
