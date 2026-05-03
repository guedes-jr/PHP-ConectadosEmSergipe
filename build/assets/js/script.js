document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileMenu = document.querySelector('#mobileMenu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });
    }

    // Theme Toggle Logic (Restored to Dropdown version)
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

        document.addEventListener('click', (e) => {
            if (!themeContainer.contains(e.target)) {
                themeContainer.classList.remove('active');
                themeBtn.setAttribute('aria-expanded', 'false');
            }
        });

        const getPreferredTheme = () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) return savedTheme;
            return 'system';
        };

        const applyTheme = (theme) => {
            if (theme === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                html.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                html.setAttribute('data-theme', theme);
            }

            themeOptions.forEach(opt => {
                if (opt.dataset.themeValue === theme) opt.classList.add('active');
                else opt.classList.remove('active');
            });

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

        const initialTheme = getPreferredTheme();
        applyTheme(initialTheme);

        themeOptions.forEach(opt => {
            opt.addEventListener('click', () => {
                const theme = opt.dataset.themeValue;
                localStorage.setItem('theme', theme);
                applyTheme(theme);
                themeContainer.classList.remove('active');
                themeBtn.setAttribute('aria-expanded', 'false');
            });
        });

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (localStorage.getItem('theme') === 'system' || !localStorage.getItem('theme')) {
                applyTheme('system');
            }
        });
    }

    // Favorites Logic (Keep this as it's a new feature but non-intrusive)
    const initFavorites = () => {
        const favorites = JSON.parse(localStorage.getItem('sergipe_favs') || '[]');
        document.querySelectorAll('.btn-favorite').forEach(btn => {
            const id = btn.dataset.id;
            if (favorites.includes(id)) {
                btn.classList.add('active');
            }
            
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const index = favorites.indexOf(id);
                if (index > -1) {
                    favorites.splice(index, 1);
                    btn.classList.remove('active');
                } else {
                    favorites.push(id);
                    btn.classList.add('active');
                    btn.style.transform = 'scale(1.3)';
                    setTimeout(() => btn.style.transform = '', 200);
                }
                localStorage.setItem('sergipe_favs', JSON.stringify(favorites));
            });
        });
    };
    initFavorites();
});
