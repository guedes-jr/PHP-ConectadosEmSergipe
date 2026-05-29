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

    // Hero Slider Carousel
    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        const slides = heroSection.querySelectorAll('.hero-slide');
        const dots = heroSection.querySelectorAll('.hero-dot');
        const prevBtn = heroSection.querySelector('.hero-arrow.prev');
        const nextBtn = heroSection.querySelector('.hero-arrow.next');
        let currentSlide = 0;
        let slideInterval;

        const showSlide = (index) => {
            if (slides.length === 0) return;
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            
            currentSlide = (index + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            if (dots[currentSlide]) dots[currentSlide].classList.add('active');
        };

        const nextSlide = () => showSlide(currentSlide + 1);
        const prevSlide = () => showSlide(currentSlide - 1);

        if (nextBtn) nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            nextSlide();
            resetInterval();
        });

        if (prevBtn) prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            prevSlide();
            resetInterval();
        });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', (e) => {
                e.preventDefault();
                showSlide(i);
                resetInterval();
            });
        });

        const startInterval = () => {
            slideInterval = setInterval(nextSlide, 60000); // 1 minute interval
        };

        const resetInterval = () => {
            clearInterval(slideInterval);
            startInterval();
        };

        slides.forEach(slide => {
            slide.addEventListener('click', () => {
                nextSlide();
                resetInterval();
            });
        });

        startInterval();
    }

    // Back to Top Logic
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

window.shareAd = function() {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback: copiar para o clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copiado para a área de transferência!');
        });
    }
};

let galleryIndex = 0;
let galleryImages = [];

document.addEventListener('keydown', e => {
    const modal = document.getElementById('galleryModal');
    if (!modal || !modal.classList.contains('open')) return;
    if (e.key === 'ArrowLeft') prevGallery();
    else if (e.key === 'ArrowRight') nextGallery();
    else if (e.key === 'Escape') closeGallery();
});

window.openGallery = function(index) {
    const imgs = document.querySelectorAll('.gallery-card img');
    galleryImages = Array.from(imgs).map(img => img.src);
    galleryIndex = index;
    
    const modal = document.getElementById('galleryModal');
    const modalImg = document.getElementById('galleryImg');
    const indicators = document.querySelector('.gallery-indicators');
    
    if (modal && modalImg && galleryImages[index]) {
        modalImg.src = galleryImages[index];
        
        // Create indicators if they don't exist
        if (indicators) {
            indicators.innerHTML = '';
            galleryImages.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.className = `indicator ${i === index ? 'active' : ''}`;
                dot.onclick = (e) => {
                    e.stopPropagation();
                    goToGallery(i);
                };
                indicators.appendChild(dot);
            });
        }
        
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
};

window.closeGallery = function() {
    const modal = document.getElementById('galleryModal');
    if (modal) {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }
};

window.goToGallery = function(index) {
    galleryIndex = index;
    const modalImg = document.getElementById('galleryImg');
    if (modalImg) modalImg.src = galleryImages[galleryIndex];
    
    // Update indicators
    const dots = document.querySelectorAll('.indicator');
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === galleryIndex);
    });
};

window.prevGallery = function() {
    const index = (galleryIndex - 1 + galleryImages.length) % galleryImages.length;
    goToGallery(index);
};

window.nextGallery = function() {
    const index = (galleryIndex + 1) % galleryImages.length;
    goToGallery(index);
};
