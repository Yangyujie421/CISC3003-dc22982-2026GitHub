document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#contactForm');
    if (!form) return;

    form.addEventListener('submit', function (event) {
        const name = document.querySelector('#name').value.trim();
        const email = document.querySelector('#email').value.trim();
        const subject = document.querySelector('#subject').value.trim();
        const message = document.querySelector('#message').value.trim();

        if (!name || !email || !subject || !message) {
            event.preventDefault();
            alert('Please complete all fields before submitting.');
            return;
        }

        if (!email.includes('@') || !email.includes('.')) {
            event.preventDefault();
            alert('Please enter a valid email address.');
        }
    });
});
