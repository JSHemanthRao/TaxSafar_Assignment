// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Form validation helper
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validateMobile(mobile) {
    const re = /^[0-9]{10}$/;
    return re.test(mobile.replace(/\D/g, ''));
}

// Close alerts after 5 seconds
window.addEventListener('load', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Prevent form submission with validation
document.addEventListener('submit', function(e) {
    const form = e.target;
    
    // Only validate inquiry forms, not delete/update forms
    if (form.classList.contains('inquiry-form')) {
        const fullName = form.querySelector('#full_name')?.value.trim();
        const email = form.querySelector('#email')?.value.trim();
        const mobile = form.querySelector('#mobile')?.value.trim();
        
        if (fullName && fullName.length < 3) {
            e.preventDefault();
            alert('Full Name must be at least 3 characters');
            return false;
        }
        
        if (email && !validateEmail(email)) {
            e.preventDefault();
            alert('Please enter a valid email');
            return false;
        }
        
        if (mobile && !validateMobile(mobile)) {
            e.preventDefault();
            alert('Please enter a valid 10-digit mobile number');
            return false;
        }
    }
});
