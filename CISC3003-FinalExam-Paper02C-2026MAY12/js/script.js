document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.querySelector('#email[data-check-email="true"]');
    const emailFeedback = document.querySelector('#emailFeedback');
    let timer = null;

    if (emailInput && emailFeedback) {
        emailInput.addEventListener('input', function () {
            clearTimeout(timer);
            const email = emailInput.value.trim();
            emailFeedback.textContent = '';

            if (email.length < 5 || !email.includes('@')) return;

            timer = setTimeout(function () {
                fetch('check-email.php?email=' + encodeURIComponent(email))
                    .then(response => response.json())
                    .then(data => {
                        emailFeedback.textContent = data.message;
                        emailFeedback.style.color = data.available ? '#065f46' : '#991b1b';
                    })
                    .catch(() => {
                        emailFeedback.textContent = 'Unable to check email now.';
                        emailFeedback.style.color = '#991b1b';
                    });
            }, 350);
        });
    }

    const registerForm = document.querySelector('#registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            const password = document.querySelector('#password').value;
            const confirmPassword = document.querySelector('#confirm_password').value;
            if (password.length < 6) {
                event.preventDefault();
                alert('Password must be at least 6 characters.');
                return;
            }
            if (password !== confirmPassword) {
                event.preventDefault();
                alert('Passwords do not match.');
            }
        });
    }
});
