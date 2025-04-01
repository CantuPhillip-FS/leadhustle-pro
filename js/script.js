// Function to show notification with optional duration
function showNotification(message, type = 'error', duration = 3000) {
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

        // If duration is specified, hide the notification after the duration
        if (duration > 0) {
            setTimeout(hideNotification, duration);
        }
    }
}

function hideNotification() {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.style.display = 'none';
    }
}

// Define the togglePassword function in the global scope
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function closePopup() {
    const popupOverlay = document.getElementById('popup-overlay');
    const loader = document.getElementById('loader');
    if (popupOverlay && loader) {
        popupOverlay.style.display = 'none';
        loader.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const accountForm = document.getElementById('accountForm');
    const loginForm = document.getElementById('loginForm');
    const createAccountBtn = document.getElementById('createAccountBtn'); // Added ID for the button

    if (accountForm) {
        // Check if the URL has an 'error' parameter and show the notification
        if (params.has('error')) {
            showNotification(decodeURIComponent(params.get('error')), 'error', 3000);
        }

        if (params.has('success')) {
            showNotification(decodeURIComponent(params.get('success')), 'success', 3000);
        }

        accountForm.addEventListener('submit', function (event) {
            // Disable the Create Account button to prevent multiple submissions
            if (createAccountBtn) {
                createAccountBtn.disabled = true;
                createAccountBtn.innerText = 'Creating Account...'; // Optional: Change button text
            }

            // Show overlay and loader
            const popupOverlay = document.getElementById('popup-overlay');
            const loader = document.getElementById('loader');
            if (popupOverlay && loader) {
                popupOverlay.style.display = 'block';
                loader.style.display = 'block';
            }

            // Proceed with form validation
            hideNotification(); // Hide any existing notifications
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let isValid = true;

            const emailRegex = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email)) {
                showNotification('Please enter a valid email address.', 'error', 3000);
                isValid = false;
            }

            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                showNotification('Password has not met the requirements. Try again.', 'error', 3000);
                isValid = false;
            }

            if (!isValid) {
                // Re-enable the Create Account button and hide the overlay
                if (createAccountBtn) {
                    createAccountBtn.disabled = false;
                    createAccountBtn.innerText = 'Create Account'; // Reset button text
                }
                if (popupOverlay && loader) {
                    popupOverlay.style.display = 'none';
                    loader.style.display = 'none';
                }
                event.preventDefault(); // Prevent form submission
            } else {
                // Allow form submission
                // Optionally, you can handle form submission via AJAX here
            }
        });
    }

    if (loginForm) {
        // Similar implementation for login form if needed
        if (params.has('error')) {
            showNotification(decodeURIComponent(params.get('error')), 'error', 3000);
        }

        loginForm.addEventListener('submit', function (event) {
            // Show overlay and loader
            const popupOverlay = document.getElementById('popup-overlay');
            const loader = document.getElementById('loader');
            if (popupOverlay && loader) {
                popupOverlay.style.display = 'block';
                loader.style.display = 'block';
            }

            hideNotification(); // Hide any existing notifications
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let isValid = true;

            const emailRegex = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email)) {
                showNotification('Please enter a valid email address.', 'error', 3000);
                isValid = false;
            }

            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                showNotification('Password has not met the requirements. Try again.', 'error', 3000);
                isValid = false;
            }

            if (!isValid) {
                // Re-enable the login button and hide the overlay
                const loginBtn = document.querySelector('#loginForm button[type="submit"]');
                if (loginBtn) {
                    loginBtn.disabled = false;
                    loginBtn.innerText = 'Login'; // Reset button text
                }
                if (popupOverlay && loader) {
                    popupOverlay.style.display = 'none';
                    loader.style.display = 'none';
                }
                event.preventDefault(); // Prevent form submission
            } else {
                // Allow form submission
                // Optionally, you can handle form submission via AJAX here
            }
        });
    }

    // Password Checklist Functionality (Assuming it's already implemented)
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        const checklist = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            lowercase: document.getElementById('lowercase'),
            number: document.getElementById('number'),
            special: document.getElementById('special')
        };

        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;

            // Check Length
            if (value.length >= 8) {
                setValid(checklist.length);
            } else {
                setInvalid(checklist.length);
            }

            // Check Uppercase Letter
            if (/[A-Z]/.test(value)) {
                setValid(checklist.uppercase);
            } else {
                setInvalid(checklist.uppercase);
            }

            // Check Lowercase Letter
            if (/[a-z]/.test(value)) {
                setValid(checklist.lowercase);
            } else {
                setInvalid(checklist.lowercase);
            }

            // Check Number
            if (/\d/.test(value)) {
                setValid(checklist.number);
            } else {
                setInvalid(checklist.number);
            }

            // Check Special Character
            if (/[@$!%*?&]/.test(value)) {
                setValid(checklist.special);
            } else {
                setInvalid(checklist.special);
            }
        });

        function setValid(element) {
            element.classList.remove('invalid');
            element.classList.add('valid');
            element.querySelector('i').classList.remove('fa-times-circle');
            element.querySelector('i').classList.add('fa-check-circle');
        }

        function setInvalid(element) {
            element.classList.remove('valid');
            element.classList.add('invalid');
            element.querySelector('i').classList.remove('fa-check-circle');
            element.querySelector('i').classList.add('fa-times-circle');
        }
    }
});