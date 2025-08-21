let currentStep = 1;
const totalSteps = 3;

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step').forEach(el => el.classList.add('hidden'));
    // Show active step
    document.getElementById('step' + step).classList.remove('hidden');
    
    // Update navigation buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    prevBtn.classList.toggle('hidden', step === 1);
    nextBtn.classList.toggle('hidden', step === totalSteps);
    submitBtn.classList.toggle('hidden', step !== totalSteps);

    // Update progress indicators
    document.querySelectorAll('.step-indicator').forEach((el, index) => {
        if (index + 1 === step) {
            el.classList.add('bg-blue-600', 'text-white');
            el.classList.remove('bg-gray-200', 'text-gray-700');
        } else if (index + 1 < step) {
            el.classList.add('bg-green-500', 'text-white');
            el.classList.remove('bg-gray-200', 'text-gray-700', 'bg-blue-600');
        } else {
            el.classList.add('bg-gray-200', 'text-gray-700');
            el.classList.remove('bg-blue-600', 'bg-green-500', 'text-white');
        }
    });

    // Update progress lines
    document.querySelectorAll('.step-line').forEach((el, index) => {
        if (index < step - 1) {
            el.classList.add('bg-green-500');
            el.classList.remove('bg-gray-200');
        } else {
            el.classList.add('bg-gray-200');
            el.classList.remove('bg-green-500');
        }
    });
}

function navigateStep(direction) {
    // Validate current step before proceeding
    if (direction > 0 && !validateStep(currentStep)) {
        return false;
    }

    currentStep += direction;
    if (currentStep < 1) currentStep = 1;
    if (currentStep > totalSteps) currentStep = totalSteps;
    showStep(currentStep);
}

function validateStep(step) {
    let isValid = true;
    const requiredFields = document.querySelectorAll(`#step${step} [required]`);
    
    requiredFields.forEach(field => {
        if (!field.value) {
            field.classList.add('border-red-500');
            isValid = false;
            
            // Show error message
            let errorDiv = field.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('error-message')) {
                errorDiv = document.createElement('div');
                errorDiv.classList.add('error-message', 'text-red-500', 'text-sm', 'mt-1');
                field.parentNode.insertBefore(errorDiv, field.nextSibling);
            }
            errorDiv.textContent = 'Field ini wajib diisi';
        } else {
            field.classList.remove('border-red-500');
            const errorDiv = field.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('error-message')) {
                errorDiv.remove();
            }
        }
    });

    return isValid;
}

// Initialize form
document.addEventListener('DOMContentLoaded', () => {
    showStep(1);

    // Add date validation
    const tanggalMulai = document.getElementById('tanggal_mulai');
    if (tanggalMulai) {
        const today = new Date().toISOString().split('T')[0];
        tanggalMulai.setAttribute('min', today);
    }

    // Add input validation listeners
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('input', function() {
            if (this.hasAttribute('required')) {
                if (this.value) {
                    this.classList.remove('border-red-500');
                    const errorDiv = this.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('error-message')) {
                        errorDiv.remove();
                    }
                }
            }
        });
    });
});
