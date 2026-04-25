document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileMenu = document.querySelector('#mobileMenu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });
    }

    // Hero Slider
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const prevBtn = document.querySelector('.hero-arrow.prev');
    const nextBtn = document.querySelector('.hero-arrow.next');

    if (slides.length > 0) {
        let currentSlide = 0;
        let slideInterval;

        function goToSlide(n) {
            slides[currentSlide].classList.remove('active');
            dots[currentSlide]?.classList.remove('active');
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            dots[currentSlide]?.classList.add('active');
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        function startInterval() {
            stopInterval();
            slideInterval = setInterval(nextSlide, 5000);
        }

        function stopInterval() {
            if (slideInterval) clearInterval(slideInterval);
        }

        // Event Listeners
        if (nextBtn) nextBtn.addEventListener('click', () => {
            nextSlide();
            startInterval();
        });
        
        if (prevBtn) prevBtn.addEventListener('click', () => {
            prevSlide();
            startInterval();
        });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                goToSlide(i);
                startInterval();
            });
        });

        startInterval();
    }

    // Filters
    const categoryFilter = document.querySelector('#categoryFilter');
    const cityFilter = document.querySelector('#cityFilter');

    if (categoryFilter) {
        categoryFilter.addEventListener('change', () => {
            const val = categoryFilter.value;
            if (val) window.location.href = `/buscar?categoria=${val}`;
        });
    }

    if (cityFilter) {
        cityFilter.addEventListener('change', () => {
            const val = cityFilter.value;
            if (val) window.location.href = `/buscar?cidade=${val}`;
        });
    }

    // Theme Toggle Logic
    const themeContainer = document.querySelector('.theme-toggle-container');
    const themeBtn = document.getElementById('themeToggleBtn');
    const themeOptions = document.querySelectorAll('.theme-option');
    const html = document.documentElement;

    if (themeContainer && themeBtn) {
        themeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            themeContainer.classList.toggle('active');
            const expanded = themeBtn.getAttribute('aria-expanded') === 'true';
            themeBtn.setAttribute('aria-expanded', !expanded);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!themeContainer.contains(e.target)) {
                themeContainer.classList.remove('active');
                themeBtn.setAttribute('aria-expanded', 'false');
            }
        });

        const getPreferredTheme = () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                return savedTheme;
            }
            return 'system';
        };

        const applyTheme = (theme) => {
            if (theme === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                html.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                html.setAttribute('data-theme', theme);
            }

            // Update active option in dropdown
            themeOptions.forEach(opt => {
                if (opt.dataset.themeValue === theme) {
                    opt.classList.add('active');
                } else {
                    opt.classList.remove('active');
                }
            });

            // Update button icon
            const iconSun = themeBtn.querySelector('.icon-sun');
            const iconMoon = themeBtn.querySelector('.icon-moon');
            const iconSystem = themeBtn.querySelector('.icon-system');

            if(iconSun && iconMoon && iconSystem) {
                iconSun.style.display = 'none';
                iconMoon.style.display = 'none';
                iconSystem.style.display = 'none';

                if (theme === 'light') iconSun.style.display = 'block';
                else if (theme === 'dark') iconMoon.style.display = 'block';
                else iconSystem.style.display = 'block';
            }
        };

        // Initialize theme
        const initialTheme = getPreferredTheme();
        applyTheme(initialTheme);

        // Handle option click
        themeOptions.forEach(opt => {
            opt.addEventListener('click', () => {
                const theme = opt.dataset.themeValue;
                localStorage.setItem('theme', theme);
                applyTheme(theme);
                themeContainer.classList.remove('active');
                themeBtn.setAttribute('aria-expanded', 'false');
            });
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (localStorage.getItem('theme') === 'system' || !localStorage.getItem('theme')) {
                applyTheme('system');
            }
        });
    }
});
