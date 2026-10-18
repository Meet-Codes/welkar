/**
 * Logo Cloud 15: BorderBeam & Synchronized Shimmer Wave Engine
 * Welkar Horological Curations Edition
 */
(function () {
    const BEAM_DURATION = 8; // 8 seconds cycle
    const BEAM_SIZE = 140; // beam length in pixels

    function initLogoCloudBeam() {
        const card = document.getElementById('logo-cloud-card');
        const badge = document.getElementById('logo-cloud-badge');
        const span = document.getElementById('beam-wave-span');
        const svg = document.getElementById('border-beam-svg');
        const beamRect = document.getElementById('beam-active-rect');
        const gradient = document.getElementById('beamGradient');

        if (!card || !span || !svg || !beamRect || !gradient) return;

        let startTime = null;

        function getPerimeterPoint(dist, W, H) {
            const P = 2 * (W + H);
            dist = ((dist % P) + P) % P;
            if (dist <= W) {
                return { x: dist, y: 0 };
            } else if (dist <= W + H) {
                return { x: W, y: dist - W };
            } else if (dist <= 2 * W + H) {
                return { x: W - (dist - (W + H)), y: H };
            } else {
                return { x: 0, y: H - (dist - (2 * W + H)) };
            }
        }

        function frame(timestamp) {
            if (!startTime) startTime = timestamp;

            const W = card.offsetWidth;
            const H = card.offsetHeight;
            if (W <= 0 || H <= 0) {
                requestAnimationFrame(frame);
                return;
            }

            const perimeter = 2 * (W + H);

            // Update SVG & rect sizing
            svg.setAttribute('viewBox', `0 0 ${W} ${H}`);
            beamRect.setAttribute('width', Math.max(0, W - 2));
            beamRect.setAttribute('height', Math.max(0, H - 2));

            // Beam cycle progress 0 to 1
            const elapsed = ((timestamp - startTime) / 1000) % BEAM_DURATION;
            const progress = elapsed / BEAM_DURATION;
            const currentDist = progress * perimeter;
            const beamOffset = progress * 100;

            // Stroke dasharray and offset along perimeter
            beamRect.style.strokeDasharray = `${BEAM_SIZE} ${perimeter - BEAM_SIZE}`;
            beamRect.style.strokeDashoffset = `${-currentDist + BEAM_SIZE}`;

            // Update beam gradient direction along perimeter path
            const head = getPerimeterPoint(currentDist, W, H);
            const tail = getPerimeterPoint(currentDist - BEAM_SIZE, W, H);
            gradient.setAttribute('x1', tail.x);
            gradient.setAttribute('y1', tail.y);
            gradient.setAttribute('x2', head.x);
            gradient.setAttribute('y2', head.y);

            // Calculate badge horizontal bounds relative to card left
            if (badge) {
                const cardRect = card.getBoundingClientRect();
                const badgeRect = badge.getBoundingClientRect();

                const textLeft = Math.max(0, badgeRect.left - cardRect.left);
                const textRight = Math.min(W, badgeRect.right - cardRect.left);

                const textStartPercent = (textLeft / perimeter) * 100;
                const textEndPercent = (textRight / perimeter) * 100;

                if (beamOffset >= textStartPercent && beamOffset <= textEndPercent) {
                    const t = (beamOffset - textStartPercent) / (textEndPercent - textStartPercent);
                    span.style.backgroundPosition = `${95 - t * 90}% center`;
                } else if (beamOffset < textStartPercent) {
                    span.style.backgroundPosition = "0% center";
                } else {
                    span.style.backgroundPosition = "100% center";
                }
            }

            requestAnimationFrame(frame);
        }

        requestAnimationFrame(frame);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLogoCloudBeam);
    } else {
        initLogoCloudBeam();
    }
})();
