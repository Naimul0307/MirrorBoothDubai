document.addEventListener('DOMContentLoaded', function () {

    // ─── NAVBAR SCROLL ───
    const desktopNav = document.querySelector('.desktop-nav .nav-bar');
    const mobileNav  = document.querySelector('.mobile-nav .nav-bar');

    window.addEventListener('scroll', function () {
        const scrolled = window.scrollY > 50;
        if (desktopNav) desktopNav.classList.toggle('scrolled', scrolled);
        if (mobileNav)  mobileNav.classList.toggle('scrolled', scrolled);
    });

    // ─── MOBILE MENU TOGGLE ───
    const menuToggle = document.getElementById('menu-toggle');
    const closeMenu  = document.getElementById('close-menu');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuToggle && closeMenu && mobileMenu) {

        // Open menu
        menuToggle.addEventListener('click', function () {
            mobileMenu.classList.add('show-menu');
            menuToggle.style.display = 'none';
            closeMenu.style.display  = 'block';
            menuToggle.setAttribute('aria-expanded', 'true');
        });

        // Close menu
        closeMenu.addEventListener('click', function () {
            mobileMenu.classList.remove('show-menu');
            menuToggle.style.display = 'block';
            closeMenu.style.display  = 'none';
            menuToggle.setAttribute('aria-expanded', 'false');
        });

        // Close menu when clicking outside the nav
        document.addEventListener('click', function (e) {
            if (
                mobileMenu.classList.contains('show-menu') &&
                !e.target.closest('.mobile-nav')
            ) {
                mobileMenu.classList.remove('show-menu');
                menuToggle.style.display = 'block';
                closeMenu.style.display  = 'none';
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Close menu when a nav link is clicked
        mobileMenu.querySelectorAll('a:not(.dropdown-toggle)').forEach(function (link) {
            link.addEventListener('click', function () {
                mobileMenu.classList.remove('show-menu');
                menuToggle.style.display = 'block';
                closeMenu.style.display  = 'none';
                menuToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // ─── DROPDOWN MENUS ───
    document.querySelectorAll('.dropdown-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const dropdownMenu = this.nextElementSibling;

            if (!dropdownMenu) return;

            // Close all other open dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(function (menu) {
                if (menu !== dropdownMenu) {
                    menu.classList.remove('show');
                }
            });

            // Toggle current dropdown
            dropdownMenu.classList.toggle('show');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(function (menu) {
                menu.classList.remove('show');
            });
        }
    });

    // ─── SEARCH TOGGLE ───
    document.querySelectorAll('.search-form').forEach(function (form) {
        const btn    = form.querySelector('.search-icon-btn');
        const input  = form.querySelector('.search-input');
        const submit = form.querySelector('.search-submit-btn');

        if (!btn || !input || !submit) return;

        // Toggle search input visibility
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const isHidden = input.hasAttribute('hidden');

            if (isHidden) {
                input.removeAttribute('hidden');
                submit.removeAttribute('hidden');
                btn.setAttribute('aria-expanded', 'true');
                input.focus();
            } else {
                input.setAttribute('hidden', '');
                submit.setAttribute('hidden', '');
                btn.setAttribute('aria-expanded', 'false');
            }
        });

        // Close search when clicking outside
        document.addEventListener('click', function (e) {
            if (!form.contains(e.target)) {
                input.setAttribute('hidden', '');
                submit.setAttribute('hidden', '');
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    });

});
