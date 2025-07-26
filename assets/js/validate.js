/**
 * HiiStyle Form Validation - Extracted from main.js
 * Handles form validation and input checking
 */

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

    static validatePhone(phone) {
        // Indonesian phone number validation
        const re = /^(\+62|62|0)8[1-9][0-9]{6,9}$/;
        return re.test(phone.replace(/\s|-/g, ''));
    }

    static validateUsername(username, minLength = 3) {
        const re = /^[a-zA-Z0-9_]+$/;
        return username.length >= minLength && re.test(username);
    }

    static validateName(name) {
        const re = /^[a-zA-Z\s]+$/;
        return name.trim().length >= 2 && re.test(name);
    }

    static validatePasswordMatch(password, confirmPassword) {
        return password === confirmPassword;
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

    static showSuccess(element, message = '') {
        // Remove existing errors
        const existingError = element.parentNode.querySelector('.alert-danger');
        if (existingError) {
            existingError.remove();
        }

        element.classList.remove('is-invalid');
        element.classList.add('is-valid');

        if (message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'alert alert-success mt-2';
            successDiv.textContent = message;
            element.parentNode.appendChild(successDiv);
        }
    }

    static clearErrors(form) {
        form.querySelectorAll('.alert-danger, .alert-success').forEach(alert => alert.remove());
        form.querySelectorAll('.is-invalid, .is-valid').forEach(element => {
            element.classList.remove('is-invalid', 'is-valid');
        });
    }

    static validateForm(form, rules = {}) {
        let isValid = true;
        this.clearErrors(form);

        Object.keys(rules).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (!field) return;

            const rule = rules[fieldName];
            const value = field.value;

            // Required validation
            if (rule.required && !this.validateRequired(value)) {
                this.showError(field, rule.messages?.required || `${fieldName} wajib diisi`);
                isValid = false;
                return;
            }

            // Skip other validations if field is empty and not required
            if (!rule.required && !value.trim()) return;

            // Email validation
            if (rule.email && !this.validateEmail(value)) {
                this.showError(field, rule.messages?.email || 'Format email tidak valid');
                isValid = false;
                return;
            }

            // Password validation
            if (rule.password && !this.validatePassword(value, rule.minLength)) {
                this.showError(field, rule.messages?.password || `Password minimal ${rule.minLength || 6} karakter`);
                isValid = false;
                return;
            }

            // Phone validation
            if (rule.phone && !this.validatePhone(value)) {
                this.showError(field, rule.messages?.phone || 'Format nomor telepon tidak valid');
                isValid = false;
                return;
            }

            // Username validation
            if (rule.username && !this.validateUsername(value, rule.minLength)) {
                this.showError(field, rule.messages?.username || `Username minimal ${rule.minLength || 3} karakter dan hanya boleh huruf, angka, dan underscore`);
                isValid = false;
                return;
            }

            // Name validation
            if (rule.name && !this.validateName(value)) {
                this.showError(field, rule.messages?.name || 'Nama hanya boleh berisi huruf dan spasi');
                isValid = false;
                return;
            }

            // Password match validation
            if (rule.passwordMatch) {
                const passwordField = form.querySelector(`[name="${rule.passwordMatch}"]`);
                if (passwordField && !this.validatePasswordMatch(passwordField.value, value)) {
                    this.showError(field, rule.messages?.passwordMatch || 'Konfirmasi password tidak cocok');
                    isValid = false;
                    return;
                }
            }

            // Custom validation
            if (rule.custom && typeof rule.custom === 'function') {
                const customResult = rule.custom(value, field, form);
                if (customResult !== true) {
                    this.showError(field, customResult || 'Input tidak valid');
                    isValid = false;
                    return;
                }
            }

            // If validation passes, show success
            if (isValid) {
                this.showSuccess(field);
            }
        });

        return isValid;
    }

    // Real-time validation
    static setupRealTimeValidation(form, rules = {}) {
        Object.keys(rules).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (!field) return;

            field.addEventListener('blur', () => {
                this.validateForm(form, { [fieldName]: rules[fieldName] });
            });

            field.addEventListener('input', () => {
                // Clear errors on input
                const existingError = field.parentNode.querySelector('.alert-danger');
                if (existingError) {
                    existingError.remove();
                    field.classList.remove('is-invalid');
                }
            });
        });
    }
}

// Common form validation rules
const CommonValidationRules = {
    login: {
        email: {
            required: true,
            email: true,
            messages: {
                required: 'Email wajib diisi',
                email: 'Format email tidak valid'
            }
        },
        password: {
            required: true,
            password: true,
            minLength: 6,
            messages: {
                required: 'Password wajib diisi',
                password: 'Password minimal 6 karakter'
            }
        }
    },
    register: {
        nama: {
            required: true,
            name: true,
            messages: {
                required: 'Nama lengkap wajib diisi',
                name: 'Nama hanya boleh berisi huruf dan spasi'
            }
        },
        username: {
            required: true,
            username: true,
            minLength: 3,
            messages: {
                required: 'Username wajib diisi',
                username: 'Username minimal 3 karakter dan hanya boleh huruf, angka, dan underscore'
            }
        },
        email: {
            required: true,
            email: true,
            messages: {
                required: 'Email wajib diisi',
                email: 'Format email tidak valid'
            }
        },
        no_telp: {
            required: true,
            phone: true,
            messages: {
                required: 'Nomor telepon wajib diisi',
                phone: 'Format nomor telepon tidak valid'
            }
        },
        password: {
            required: true,
            password: true,
            minLength: 6,
            messages: {
                required: 'Password wajib diisi',
                password: 'Password minimal 6 karakter'
            }
        },
        confirm_password: {
            required: true,
            passwordMatch: 'password',
            messages: {
                required: 'Konfirmasi password wajib diisi',
                passwordMatch: 'Konfirmasi password tidak cocok'
            }
        }
    }
};

// Auto-setup validation for common forms
document.addEventListener('DOMContentLoaded', () => {
    // Login form
    const loginForm = document.querySelector('#loginForm, form[action*="login"]');
    if (loginForm) {
        FormValidator.setupRealTimeValidation(loginForm, CommonValidationRules.login);
        
        loginForm.addEventListener('submit', (e) => {
            if (!FormValidator.validateForm(loginForm, CommonValidationRules.login)) {
                e.preventDefault();
            }
        });
    }

    // Register form
    const registerForm = document.querySelector('#registerForm, form[action*="register"]');
    if (registerForm) {
        FormValidator.setupRealTimeValidation(registerForm, CommonValidationRules.register);
        
        registerForm.addEventListener('submit', (e) => {
            if (!FormValidator.validateForm(registerForm, CommonValidationRules.register)) {
                e.preventDefault();
            }
        });
    }

    // Generic form validation
    document.querySelectorAll('form[data-validate]').forEach(form => {
        const rules = JSON.parse(form.dataset.validate || '{}');
        FormValidator.setupRealTimeValidation(form, rules);
        
        form.addEventListener('submit', (e) => {
            if (!FormValidator.validateForm(form, rules)) {
                e.preventDefault();
            }
        });
    });
});

// Expose globally
window.FormValidator = FormValidator;
window.CommonValidationRules = CommonValidationRules;
