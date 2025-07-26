/**
 * HiiStyle Main JavaScript - Optimized
 * Consolidated hamburger panel system for both admin and user
 */


// Global variable to access from other scripts
let hamburgerPanel;

// Note: HamburgerPanel will be initialized by hamburger-admin.js
// This is just a placeholder for global access

// Form validation utilities
class FormValidator {
    static validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    static validatePassword(password, minLength = 6) {
        return password.length >= minLength;
    }

    static validateRequired(value) {
        return value.trim().length > 0;
    }

    static showError(element, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger mt-2';
        errorDiv.textContent = message;
        
        // Remove existing error
        const existingError = element.parentNode.querySelector('.alert-danger');
        if (existingError) {
            existingError.remove();
        }
        
        element.parentNode.appendChild(errorDiv);
        element.classList.add('is-invalid');
    }

    static clearErrors(form) {
        form.querySelectorAll('.alert-danger').forEach(error => error.remove());
        form.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid');
        });
    }
}

// Alert system
class AlertSystem {
    static show(message, type = 'info', duration = 5000) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alert.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 10000;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        // Auto remove after duration
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, duration);
    }

    static success(message, duration = 3000) {
        this.show(message, 'success', duration);
    }

    static error(message, duration = 5000) {
        this.show(message, 'danger', duration);
    }

    static warning(message, duration = 4000) {
        this.show(message, 'warning', duration);
    }

    static info(message, duration = 4000) {
        this.show(message, 'info', duration);
    }
}

// Loading utility
class LoadingUtil {
    static show(text = 'Loading...') {
        const loader = document.createElement('div');
        loader.id = 'global-loader';
        loader.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
        loader.style.cssText = `
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(4px);
            z-index: 10001;
        `;
        
        loader.innerHTML = `
            <div class="text-center text-light">
                <div class="spinner-border text-warning mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>${text}</div>
            </div>
        `;
        
        document.body.appendChild(loader);
    }

    static hide() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.remove();
        }
    }
}

// Page transition effects
function addPageTransitions() {
    // Add smooth transitions for page navigation
    window.addEventListener('beforeunload', () => {
        document.body.style.opacity = '0.8';
        document.body.style.transform = 'scale(0.98)';
    });

    // Add page load animation
    window.addEventListener('load', () => {
        document.body.style.opacity = '1';
        document.body.style.transform = 'scale(1)';
        document.body.style.transition = 'all 0.3s ease';
    });
}

// Initialize page transitions
addPageTransitions();

// Helper function to update cart count from anywhere
function updateCartCount(count) {
    if (hamburgerPanel && hamburgerPanel.type === 'user') {
        hamburgerPanel.updateCartCount(count);
    }
}

// Expose utilities globally
window.FormValidator = FormValidator;
window.AlertSystem = AlertSystem;
window.LoadingUtil = LoadingUtil;
window.updateCartCount = updateCartCount;
