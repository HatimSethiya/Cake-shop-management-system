document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.main-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('nav-open');
            toggle.setAttribute('aria-expanded', String(isOpen));
            toggle.textContent = isOpen ? '✕' : '☰';
        });

        nav.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                nav.classList.remove('nav-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.textContent = '☰';
            });
        });
    }
});
