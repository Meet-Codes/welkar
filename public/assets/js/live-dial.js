/**
 * Welkar Real-Time Watch Animation Dial Engine
 * Precision Horological Live Time Compositing (Sylvi-Grade Architecture)
 */

(function () {
    'use strict';

    // Global Registry of active dial instances
    const activeDials = new Map();
    let isMasterLoopRunning = false;

    // Horological Showcase Initial Angle (Classic 10:10:35 pose)
    const SHOWCASE_TIME = {
        hourDeg: 305.25,
        minDeg: 63,
        secDeg: 210
    };

    /**
     * Compute current visitor local time angles in degrees
     */
    function getCurrentTimeAngles(smoothSweep = true) {
        const now = new Date();
        const ms = now.getMilliseconds();
        const sec = now.getSeconds();
        const min = now.getMinutes();
        const hr = now.getHours() % 12;

        const secFraction = smoothSweep ? (sec + ms / 1000) : sec;
        const minFraction = min + sec / 60;
        const hrFraction = hr + minFraction / 60;

        return {
            hourDeg: hrFraction * 30,             // 360 / 12 = 30 deg/hr
            minDeg: minFraction * 6,              // 360 / 60 = 6 deg/min
            secDeg: secFraction * 6,              // 360 / 60 = 6 deg/sec
            timeString: now.toLocaleTimeString()
        };
    }

    /**
     * Welkar Dial Instance
     */
    class WelkarDial {
        constructor(container) {
            this.container = container;
            this.id = 'dial_' + Math.random().toString(36).substring(2, 9);
            this.centerX = parseFloat(container.dataset.centerX) || 50;
            this.centerY = parseFloat(container.dataset.centerY) || 50;
            this.radiusRatio = parseFloat(container.dataset.radius) || 38; // dial radius as % of min dimension
            this.dialType = container.dataset.dialType || 'Analog';
            this.theme = container.dataset.theme || 'silver'; // silver | gold | rosegold | dark
            this.isLive = true;
            this.isCalibrated = false;
            this.isVisible = true;

            // Custom image needle URLs if uploaded via Admin
            this.hourImg = container.dataset.hourImg || null;
            this.minImg = container.dataset.minImg || null;
            this.secImg = container.dataset.secImg || null;

            this.init();
        }

        init() {
            // Find or create overlay
            let overlay = this.container.querySelector('.welkar-dial-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'welkar-dial-overlay';
                this.container.appendChild(overlay);
            }
            this.overlay = overlay;

            if (this.hourImg && this.minImg) {
                this.initImageHands();
            } else {
                this.initSvgHands();
            }

            // Run Stage 1 Entrance Sweep Calibration
            this.runEntranceCalibration();
        }

        initImageHands() {
            this.overlay.innerHTML = '';

            // Hour Hand
            const hr = document.createElement('img');
            hr.className = 'welkar-img-hand hour-hand';
            hr.src = this.hourImg;
            hr.style.left = `${this.centerX}%`;
            hr.style.top = `${this.centerY}%`;
            hr.style.transformOrigin = '50% 90%';
            hr.style.transform = `translate(-50%, -90%) rotate(${SHOWCASE_TIME.hourDeg}deg)`;
            this.overlay.appendChild(hr);
            this.hourEl = hr;

            // Minute Hand
            const min = document.createElement('img');
            min.className = 'welkar-img-hand min-hand';
            min.src = this.minImg;
            min.style.left = `${this.centerX}%`;
            min.style.top = `${this.centerY}%`;
            min.style.transformOrigin = '50% 92%';
            min.style.transform = `translate(-50%, -92%) rotate(${SHOWCASE_TIME.minDeg}deg)`;
            this.overlay.appendChild(min);
            this.minEl = min;

            // Second Hand
            if (this.secImg) {
                const sec = document.createElement('img');
                sec.className = 'welkar-img-hand sec-hand';
                sec.src = this.secImg;
                sec.style.left = `${this.centerX}%`;
                sec.style.top = `${this.centerY}%`;
                sec.style.transformOrigin = '50% 75%';
                sec.style.transform = `translate(-50%, -75%) rotate(${SHOWCASE_TIME.secDeg}deg)`;
                this.overlay.appendChild(sec);
                this.secEl = sec;
            }
        }

        initSvgHands() {
            this.overlay.innerHTML = '';

            const uid = this.id;
            const cx = this.centerX * 4; // Map 0-100% to 0-400 viewbox
            const cy = this.centerY * 4;
            const r = this.radiusRatio * 4; // Dial radius in viewBox units

            // Theme palette
            let hGrad1 = '#f8fafc', hGrad2 = '#94a3b8', accentSec = '#dc2626', centerCap = '#0f172a';
            if (this.theme === 'gold') {
                hGrad1 = '#fef08a'; hGrad2 = '#ca8a04'; accentSec = '#b45309'; centerCap = '#78350f';
            } else if (this.theme === 'rosegold') {
                hGrad1 = '#fbcfe8'; hGrad2 = '#db2777'; accentSec = '#be185d'; centerCap = '#831843';
            } else if (this.theme === 'dark') {
                hGrad1 = '#334155'; hGrad2 = '#0f172a'; accentSec = '#ef4444'; centerCap = '#020617';
            }

            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('class', 'welkar-dial-svg');
            svg.setAttribute('viewBox', '0 0 400 400');
            svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

            svg.innerHTML = `
                <defs>
                    <!-- Metallic Sword Hand Gradient -->
                    <linearGradient id="grad_h_${uid}" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="${hGrad1}" />
                        <stop offset="50%" stop-color="#ffffff" />
                        <stop offset="100%" stop-color="${hGrad2}" />
                    </linearGradient>
                    <linearGradient id="grad_bevel_${uid}" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="rgba(0,0,0,0.25)" />
                        <stop offset="100%" stop-color="rgba(255,255,255,0.4)" />
                    </linearGradient>
                    <!-- Soft Drop Shadow for Real Physical Depth -->
                    <filter id="shadow_${uid}" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="1" dy="2" stdDeviation="2" flood-color="#000000" flood-opacity="0.35" />
                    </filter>
                </defs>

                <!-- Rotatable Hour Hand Group -->
                <g id="hour_grp_${uid}" filter="url(#shadow_${uid})">
                    <!-- Beveled Faceted Sword Hour Hand -->
                    <path d="M ${cx - 4.5} ${cy} 
                             L ${cx - 3.5} ${cy - r * 0.55} 
                             L ${cx} ${cy - r * 0.65} 
                             L ${cx + 3.5} ${cy - r * 0.55} 
                             L ${cx + 4.5} ${cy} 
                             L ${cx} ${cy + 12} Z" 
                          fill="url(#grad_h_${uid})" />
                    <!-- Luminous Core Strip -->
                    <line x1="${cx}" y1="${cy - 8}" x2="${cx}" y2="${cy - r * 0.52}" stroke="#f1f5f9" stroke-width="2" stroke-linecap="round" opacity="0.9" />
                    <line x1="${cx}" y1="${cy}" x2="${cx}" y2="${cy - r * 0.65}" stroke="url(#grad_bevel_${uid})" stroke-width="1" />
                </g>

                <!-- Rotatable Minute Hand Group -->
                <g id="min_grp_${uid}" filter="url(#shadow_${uid})">
                    <!-- Slender Faceted Sword Minute Hand -->
                    <path d="M ${cx - 3.8} ${cy} 
                             L ${cx - 2.8} ${cy - r * 0.85} 
                             L ${cx} ${cy - r * 0.95} 
                             L ${cx + 2.8} ${cy - r * 0.85} 
                             L ${cx + 3.8} ${cy} 
                             L ${cx} ${cy + 14} Z" 
                          fill="url(#grad_h_${uid})" />
                    <!-- Luminous Core Strip -->
                    <line x1="${cx}" y1="${cy - 8}" x2="${cx}" y2="${cy - r * 0.82}" stroke="#f1f5f9" stroke-width="1.8" stroke-linecap="round" opacity="0.9" />
                    <line x1="${cx}" y1="${cy}" x2="${cx}" y2="${cy - r * 0.95}" stroke="url(#grad_bevel_${uid})" stroke-width="0.8" />
                </g>

                <!-- Rotatable Second Hand Group (Sweeping Needle) -->
                <g id="sec_grp_${uid}" filter="url(#shadow_${uid})">
                    <!-- Counterbalance Tail Ring -->
                    <circle cx="${cx}" cy="${cy + r * 0.22}" r="5" fill="none" stroke="${accentSec}" stroke-width="1.2" />
                    <!-- Needle Rod -->
                    <line x1="${cx}" y1="${cy + r * 0.28}" x2="${cx}" y2="${cy - r * 1.02}" stroke="${accentSec}" stroke-width="1.2" stroke-linecap="round" />
                    <!-- Needle Arrowhead / Tip -->
                    <circle cx="${cx}" cy="${cy - r * 0.82}" r="2.2" fill="${accentSec}" />
                </g>

                <!-- Center Arbor Cap & Jeweled Pinion -->
                <circle cx="${cx}" cy="${cy}" r="7" fill="${centerCap}" />
                <circle cx="${cx}" cy="${cy}" r="5.5" fill="url(#grad_h_${uid})" />
                <circle cx="${cx}" cy="${cy}" r="2" fill="#0f172a" />
            `;

            this.overlay.appendChild(svg);
            this.svg = svg;
            this.svgCx = cx;
            this.svgCy = cy;
            this.hourGrp = svg.querySelector(`#hour_grp_${uid}`);
            this.minGrp = svg.querySelector(`#min_grp_${uid}`);
            this.secGrp = svg.querySelector(`#sec_grp_${uid}`);
        }

        runEntranceCalibration() {
            // Stage 1: Animate smoothly from showcase pose (10:10:35) to real local time over 1.8 seconds
            const target = getCurrentTimeAngles(true);
            const startHour = SHOWCASE_TIME.hourDeg;
            const startMin = SHOWCASE_TIME.minDeg;
            const startSec = SHOWCASE_TIME.secDeg;

            const startTime = performance.now();
            const duration = 1800; // 1.8s smooth entrance sweep

            const step = (now) => {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out cubic
                const ease = 1 - Math.pow(1 - progress, 3);

                const currentHour = startHour + (target.hourDeg - startHour) * ease;
                const currentMin = startMin + (target.minDeg - startMin) * ease;
                const currentSec = startSec + (target.secDeg - startSec) * ease;

                this.setAngles(currentHour, currentMin, currentSec);

                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    this.isCalibrated = true;
                }
            };

            requestAnimationFrame(step);
        }

        setAngles(hourDeg, minDeg, secDeg) {
            if (this.hourEl && this.minEl) {
                this.hourEl.style.transform = `translate(-50%, -90%) rotate(${hourDeg}deg)`;
                this.minEl.style.transform = `translate(-50%, -92%) rotate(${minDeg}deg)`;
                if (this.secEl) {
                    this.secEl.style.transform = `translate(-50%, -75%) rotate(${secDeg}deg)`;
                }
            } else if (this.svg && this.hourGrp) {
                this.hourGrp.setAttribute('transform', `rotate(${hourDeg} ${this.svgCx} ${this.svgCy})`);
                this.minGrp.setAttribute('transform', `rotate(${minDeg} ${this.svgCx} ${this.svgCy})`);
                if (this.secGrp) {
                    this.secGrp.setAttribute('transform', `rotate(${secDeg} ${this.svgCx} ${this.svgCy})`);
                }
            }
        }

        updateLive(angles) {
            if (!this.isCalibrated || !this.isLive || !this.isVisible) return;
            this.setAngles(angles.hourDeg, angles.minDeg, angles.secDeg);
        }

        toggleLiveMode(active) {
            this.isLive = active !== undefined ? active : !this.isLive;
            if (!this.isLive) {
                // Return to horological showcase
                this.setAngles(SHOWCASE_TIME.hourDeg, SHOWCASE_TIME.minDeg, SHOWCASE_TIME.secDeg);
            }
            return this.isLive;
        }
    }

    /**
     * Master High-Performance 60 FPS Loop
     */
    function masterTick() {
        if (activeDials.size === 0) {
            isMasterLoopRunning = false;
            return;
        }

        const angles = getCurrentTimeAngles(true);
        activeDials.forEach((dial) => {
            dial.updateLive(angles);
        });

        requestAnimationFrame(masterTick);
    }

    function startMasterLoop() {
        if (!isMasterLoopRunning) {
            isMasterLoopRunning = true;
            requestAnimationFrame(masterTick);
        }
    }

    /**
     * Viewport Intersection Observer (pause off-screen dials to save CPU/battery)
     */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const dial = activeDials.get(entry.target);
            if (dial) {
                dial.isVisible = entry.isIntersecting;
            }
        });
    }, { rootMargin: '100px' });

    /**
     * Initialize all dials on DOM load
     */
    function initAllDials() {
        const containers = document.querySelectorAll('.welkar-dial-container');
        containers.forEach((container) => {
            if (!activeDials.has(container)) {
                const dial = new WelkarDial(container);
                activeDials.set(container, dial);
                observer.observe(container);
            }
        });

        if (activeDials.size > 0) {
            startMasterLoop();
        }
    }

    // Expose API for dynamic gallery thumbnail switching or interactive buttons
    window.WelkarLiveDial = {
        init: initAllDials,
        getDial: (element) => activeDials.get(element),
        getCurrentTimeAngles
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllDials);
    } else {
        initAllDials();
    }
})();
