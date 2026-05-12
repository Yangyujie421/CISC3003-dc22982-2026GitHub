document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form[data-validate="true"]');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const requiredInputs = form.querySelectorAll('[required]');
            let valid = true;

            requiredInputs.forEach(function (input) {
                if (!input.value.trim() && input.type !== 'checkbox') {
                    valid = false;
                    input.style.borderColor = '#dc2626';
                } else if (input.type === 'checkbox' && !input.checked) {
                    valid = false;
                } else {
                    input.style.borderColor = '#cbd5e1';
                }
            });

            if (!valid) {
                event.preventDefault();
                alert('Please complete all required fields.');
            }
        });
    });
});
