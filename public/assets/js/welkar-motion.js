/**
 * Welkar Horological Motion & Interaction Engine
 * Ultra-smooth, 60fps micro-animations, scroll reveals, and 3D sapphire tilt.
 */
(function() {
    'use strict';

    // 1. Scroll-Driven Stagger Reveal Engine
    function initScrollReveal() {
        const revealElements = document.querySelectorAll('.reveal-on-scroll');
        if (!revealElements.length) return;

        if (!('IntersectionObserver' in window)) {
            revealElements.forEach(el => el.classList.add('is-visible'));
            return;
        }

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry, idx) => {
                if (entry.isIntersecting) {
                    // Stagger sibling elements slightly
                    const parent = entry.target.parentElement;
                    const siblings = parent ? Array.from(parent.querySelectorAll('.reveal-on-scroll')) : [];
                    const index = siblings.indexOf(entry.target);
                    const delay = index >= 0 ? (index % 6) * 80 : 0;

                    setTimeout(() => {
                        entry.target.classList.add('is-visible');
                    }, delay);

                    obs.unobserve(entry.target);
                }
            });
        }, {
            root: null,
            rootMargin: '0px 0px -40px 0px',
            threshold: 0.1
        });

        revealElements.forEach(el => observer.observe(el));
    }

    // 2. 3D Perspective Sapphire Glass Tilt Engine
    function initCardTilt() {
        const tiltCards = document.querySelectorAll('.card-tilt-3d, .product-card');
        if (!tiltCards.length) return;

        // Skip on mobile / touch devices for optimal performance
        if (window.matchMedia('(hover: none)').matches) return;

        tiltCards.forEach(card => {
            let bounds = null;

            function onMouseEnter() {
                bounds = card.getBoundingClientRect();
                card.style.transition = 'transform 0.15s ease-out, box-shadow 0.3s ease';
            }

            function onMouseMove(e) {
                if (!bounds) bounds = card.getBoundingClientRect();
                const mouseX = e.clientX - bounds.left;
                const mouseY = e.clientY - bounds.top;

                const xPct = mouseX / bounds.width - 0.5;
                const yPct = mouseY / bounds.height - 0.5;

                const rotateX = -yPct * 7; // Max 7 deg
                const rotateY = xPct * 7;

                card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-4px)`;
            }

            function onMouseLeave() {
                bounds = null;
                card.style.transition = 'transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.45s ease';
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
            }

            card.addEventListener('mouseenter', onMouseEnter);
            card.addEventListener('mousemove', onMouseMove);
            card.addEventListener('mouseleave', onMouseLeave);
        });
    }

    // 3. Navbar Glassmorphic Elevation on Scroll
    function initNavbarElevation() {
        const navbar = document.querySelector('.welkar-navbar');
        if (!navbar) return;

        function updateNavbar() {
            if (window.scrollY > 30) {
                navbar.style.boxShadow = '0 8px 24px -4px rgba(18, 19, 22, 0.08)';
                navbar.style.borderBottomColor = 'rgba(226, 226, 232, 0.8)';
            } else {
                navbar.style.boxShadow = 'none';
                navbar.style.borderBottomColor = 'var(--color-border-default, #E2E2E8)';
            }
        }

        window.addEventListener('scroll', updateNavbar, { passive: true });
        updateNavbar();
    }

    // Initialize on DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initScrollReveal();
            initCardTilt();
            initNavbarElevation();
        });
    } else {
        initScrollReveal();
        initCardTilt();
        initNavbarElevation();
    }
})();
