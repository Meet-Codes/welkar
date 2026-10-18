/**
 * Card-7 Interactive 3D Perspective Tilt Engine
 * Exact mathematics from card-7.tsx:
 * - rotateX = ((y - height/2) / (height/2)) * -8 deg
 * - rotateY = ((x - width/2) / (width/2)) * 8 deg
 * - transform: perspective(1000px) rotateX(...) rotateY(...) scale3d(1.04, 1.04, 1.04)
 * - transition: transform 0.1s ease-out on mousemove
 * - on mouseleave: perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1) with 0.4s ease-in-out
 */
(function() {
    function initCard7Tilt() {
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        const cards = document.querySelectorAll('.card-7-interactive, [data-card-7="true"], .stitch-product-card, .pillar-card, .hero-08-card');

        cards.forEach(card => {
            if (card.dataset.card7Initialized) return;
            card.dataset.card7Initialized = 'true';

            let isHovered = false;

            card.addEventListener('mouseenter', () => {
                isHovered = true;
            });

            card.addEventListener('mousemove', (e) => {
                if (!isHovered) return;
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const rotateX = ((y - rect.height / 2) / (rect.height / 2)) * -8;
                const rotateY = ((x - rect.width / 2) / (rect.width / 2)) * 8;

                card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.04, 1.04, 1.04)`;
                card.style.transition = 'transform 0.1s ease-out';
            });

            card.addEventListener('mouseleave', () => {
                isHovered = false;
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                card.style.transition = 'transform 0.4s ease-in-out';
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCard7Tilt);
    } else {
        initCard7Tilt();
    }

    window.initCard7Tilt = initCard7Tilt;
})();
