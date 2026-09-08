(function () {
    'use strict'; // Enable strict mode

    // Run script after page is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    function boot() {
        try {
            handleEnterNavigation();     // Enter key moves between form fields
            handleAutoScrollFromQuery(); // Auto scroll using URL parameters
            runToastFromQuery();         // Show success message
            handleOtherCoffeeName();     // Show input when "Other" coffee name is selected
            handleOtherBean();           // Show input when "Other" bean is selected
        } catch (err) {
            console.warn('app boot error', err);
        }
    }

    function handleEnterNavigation() {
        const form = document.getElementById('coffee-form');
        if (!form) return;

        const inputs = Array.from(form.querySelectorAll('input, select, textarea'))
            .filter(el => el.type !== 'hidden' && !el.disabled);

        inputs.forEach((el, index) => {
            el.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter') return;

                if (el.tagName.toLowerCase() === 'textarea' && event.shiftKey) return;

                event.preventDefault();

                const next = inputs[index + 1];
                if (next) {
                    next.focus();
                } else {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.click();
                }
            });
        });
    }

    function handleAutoScrollFromQuery() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('focus') === 'list') {
            const listSection = document.getElementById('coffee-list');
            if (listSection) {
                setTimeout(() => {
                    listSection.scrollIntoView({ behavior: 'smooth' });
                }, 60);
            }
        }
    }

    function runToastFromQuery() {
        if (document.querySelector('.toast-modern')) return;

        const params = new URLSearchParams(window.location.search);
        const kind = params.get('success');
        if (!kind) return;

        const messages = {
            added:   'Coffee added successfully',
            updated: 'Coffee updated successfully',
            deleted: 'Coffee deleted successfully'
        };

        const toast = document.createElement('div');
        toast.className = 'toast-modern';
        toast.textContent = messages[kind] || 'Action completed';
        document.body.appendChild(toast);

        requestAnimationFrame(() => toast.classList.add('show'));

        setTimeout(() => {
            toast.classList.remove('show');
            toast.remove();
        }, 2500);
    }

    // NEW: show input when coffee name is "Other"
    function handleOtherCoffeeName() {
        const nameInput = document.getElementById('name');
        const otherBox  = document.getElementById('otherNameBox');

        if (!nameInput || !otherBox) return;

        nameInput.addEventListener('input', function () {
            if (this.value === 'Other') {
                otherBox.style.display = 'block';
            } else {
                otherBox.style.display = 'none';
            }
        });
    }

})();

// Auto scroll on choice page after selecting type
document.addEventListener('DOMContentLoaded', function () {
    if (!window.location.pathname.includes("choice.php")) return;

    const section = document.querySelector('.coffee-list');
    if (section) {
        setTimeout(() => {
            section.scrollIntoView({ behavior: 'smooth' });
        }, 100);
    }
});