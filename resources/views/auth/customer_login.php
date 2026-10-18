<!-- ==========================================================================
     WELKAR ATELIER — PROGRESSIVE 3-STEP GLASS AUTHENTICATION EXPERIENCE
     Based on 3D Glassmorphism, Floating Ambient Gradient Background & Confetti
     ========================================================================== -->

<!-- canvas-confetti CDN for celebration cannons -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

<style>
/* CSS Variables & Keyframes for 3D Glassmorphic Auth */
@property --angle-1 { syntax: "<angle>"; inherits: false; initial-value: -75deg; }
@property --angle-2 { syntax: "<angle>"; inherits: false; initial-value: -45deg; }

@keyframes float1 {
    0% { transform: translate(0, 0); }
    50% { transform: translate(-15px, 15px); }
    100% { transform: translate(0, 0); }
}
@keyframes float2 {
    0% { transform: translate(0, 0); }
    50% { transform: translate(15px, -15px); }
    100% { transform: translate(0, 0); }
}

/* Base Wrapper & Full Screen Atmosphere */
.auth-page-wrapper {
    position: relative;
    min-height: 85vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 1rem;
    overflow: hidden;
    background: radial-gradient(circle at 50% 20%, #1e2421 0%, #111413 100%);
    color: #F8F6F1;
}

.auth-ambient-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
    opacity: 0.65;
}

/* Glass Card Container */
.auth-glass-container {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 480px;
    background: rgba(26, 32, 29, 0.72);
    backdrop-filter: blur(24px) saturate(160%);
    -webkit-backdrop-filter: blur(24px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 28px;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    padding: 2.75rem 2.25rem;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Tab Switcher: 3-Step Email vs Mobile OTP */
.auth-mode-pills {
    display: inline-flex;
    background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 9999px;
    padding: 4px;
    margin-bottom: 2rem;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
}

.auth-pill-btn {
    border: none;
    background: transparent;
    color: rgba(248, 246, 241, 0.6);
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 8px 18px;
    border-radius: 9999px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.auth-pill-btn.active {
    background: rgba(255, 255, 255, 0.12);
    color: #F8F6F1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

/* 3D Glass Button */
.glass-button-wrap {
    --anim-time: 400ms;
    --anim-ease: cubic-bezier(0.25, 1, 0.5, 1);
    --border-width: 1.5px;
    position: relative;
    z-index: 2;
    transform-style: preserve-3d;
    transition: transform var(--anim-time) var(--anim-ease);
    display: inline-block;
}

.glass-button-wrap:active {
    transform: rotateX(15deg) scale(0.98);
}

.glass-button-shadow {
    position: absolute;
    inset: -4px;
    border-radius: 9999px;
    filter: blur(10px);
    background: radial-gradient(circle, rgba(133, 103, 68, 0.35) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
    transition: opacity var(--anim-time) var(--anim-ease);
}

.glass-button {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.04) 100%);
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 9999px;
    color: #F8F6F1;
    cursor: pointer;
    backdrop-filter: blur(8px);
    box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.3), 0 8px 24px rgba(0, 0, 0, 0.35);
    transition: all var(--anim-time) var(--anim-ease);
}

.glass-button:hover {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%);
    border-color: rgba(255, 255, 255, 0.35);
    box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.4), 0 12px 30px rgba(0, 0, 0, 0.45);
    transform: translateY(-1px);
}

.glass-button-text {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 28px;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: -0.2px;
}

.glass-button-text.sm {
    padding: 8px 18px;
    font-size: 13px;
}

.glass-button-text.icon {
    width: 44px;
    height: 44px;
    padding: 0;
}

/* 3D Glass Input Field */
.glass-input-wrap {
    position: relative;
    z-index: 2;
    border-radius: 9999px;
    margin-bottom: 1.25rem;
    transition: transform 0.3s ease;
}

.glass-input {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    background: rgba(16, 20, 18, 0.7);
    border: 1.5px solid rgba(255, 255, 255, 0.14);
    border-radius: 9999px;
    padding: 6px 8px;
    backdrop-filter: blur(12px);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.4), 0 4px 16px rgba(0, 0, 0, 0.2);
    transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
}

.glass-input-wrap:focus-within .glass-input {
    border-color: rgba(212, 175, 55, 0.6);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.4), 0 0 20px rgba(212, 175, 55, 0.25);
    background: rgba(22, 28, 25, 0.85);
}

.glass-input input {
    flex-grow: 1;
    background: transparent;
    border: none;
    outline: none;
    color: #F8F6F1;
    font-size: 14.5px;
    padding: 8px 12px;
    min-width: 0;
}

.glass-input input::placeholder {
    color: rgba(248, 246, 241, 0.4);
}

.glass-input .input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    color: rgba(248, 246, 241, 0.6);
    flex-shrink: 0;
}

/* Progressive Form Steps */
.auth-step-pane {
    display: none;
    opacity: 0;
    transform: translateY(8px);
    transition: opacity 0.35s ease, transform 0.35s ease;
}

.auth-step-pane.active {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

/* Loading & Celebration Modal */
.auth-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9998;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    align-items: center;
    justify-content: center;
}

.auth-modal-card {
    background: #1A201D;
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 24px;
    padding: 2.5rem;
    width: 90%;
    max-width: 380px;
    text-align: center;
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.8);
    position: relative;
    animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes modalPop {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}

.auth-spinner {
    width: 52px;
    height: 52px;
    border: 3px solid rgba(255, 255, 255, 0.15);
    border-top-color: #D4AF37;
    border-radius: 50%;
    animation: spin 0.85s linear infinite;
    margin: 0 auto 1.5rem;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Text Loop Stage Animation */
.text-loop-container {
    height: 32px;
    overflow: hidden;
    position: relative;
}

.text-loop-stage {
    font-size: 1.15rem;
    font-weight: 500;
    color: #F8F6F1;
    transition: transform 0.3s ease, opacity 0.3s ease;
}
</style>

<div class="auth-page-wrapper">
    <!-- Animated SVG Floating Ambient Background from prompt -->
    <svg class="auth-ambient-svg" viewBox="0 0 800 600" fill="none" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="rev_grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#856744" stop-opacity="0.85" />
                <stop offset="100%" stop-color="#2D3A34" stop-opacity="0.5" />
            </linearGradient>
            <linearGradient id="rev_grad2" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#3D4D44" stop-opacity="0.9" />
                <stop offset="50%" stop-color="#202522" stop-opacity="0.7" />
                <stop offset="100%" stop-color="#856744" stop-opacity="0.6" />
            </linearGradient>
            <radialGradient id="rev_grad3" cx="50%" cy="50%" r="50%">
                <stop offset="0%" stop-color="#A88B67" stop-opacity="0.75" />
                <stop offset="100%" stop-color="#141816" stop-opacity="0.3" />
            </radialGradient>
            <filter id="rev_blur1" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="40"/></filter>
            <filter id="rev_blur2" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="30"/></filter>
            <filter id="rev_blur3" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="55"/></filter>
        </defs>
        <g style="animation: float1 20s ease-in-out infinite;">
            <ellipse cx="200" cy="500" rx="280" ry="200" fill="url(#rev_grad1)" filter="url(#rev_blur1)" transform="rotate(-30 200 500)" />
            <rect x="500" y="100" width="320" height="260" rx="80" fill="url(#rev_grad2)" filter="url(#rev_blur2)" transform="rotate(15 650 225)" />
        </g>
        <g style="animation: float2 25s ease-in-out infinite;">
            <circle cx="650" cy="450" r="160" fill="url(#rev_grad3)" filter="url(#rev_blur3)" opacity="0.65" />
            <ellipse cx="60" cy="150" rx="190" ry="130" fill="#202522" filter="url(#rev_blur2)" opacity="0.75" />
        </g>
    </svg>

    <!-- Main Glassmorphic Auth Container -->
    <div class="auth-glass-container">
        <!-- Brand Header -->
        <div class="text-center mb-4">
            <a href="/" class="d-inline-flex align-items-center gap-2 mb-2 text-decoration-none">
                <img src="/assets/images/welkar-logo.png" alt="Welkar" style="height: 42px; width: auto; object-fit: contain; filter: brightness(1.2);">
            </a>
            <div class="text-uppercase fw-bold" style="font-size: 10.5px; letter-spacing: 2.5px; color: #D4AF37;">CLIENT ATELIER</div>
        </div>

        <!-- Mode Toggle: Progressive 3-Step Email vs Mobile OTP -->
        <div class="text-center">
            <div class="auth-mode-pills">
                <button type="button" class="auth-pill-btn active" id="tab-btn-email" onclick="switchAuthTab('email')">
                    <i class="bi bi-shield-lock me-1"></i> Email &amp; Password
                </button>
                <button type="button" class="auth-pill-btn" id="tab-btn-phone" onclick="switchAuthTab('phone')">
                    <i class="bi bi-phone me-1"></i> Phone OTP (+91)
                </button>
            </div>
        </div>

        <!-- TAB 1: Progressive 3-Step Auth (Email -> Password -> Confirm) -->
        <div id="auth-panel-email">
            <!-- Step 1: Email & Socials -->
            <div class="auth-step-pane active" id="step-pane-email">
                <div class="text-center mb-4">
                    <h2 class="h3 fw-light mb-1" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); letter-spacing: -0.5px; color: #F8F6F1;">
                        Get started with Us
                    </h2>
                    <p class="small text-white-50 mb-3">Continue with your atelier account</p>
                    
                    <!-- Social Buttons with 3D Glass Look -->
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <div class="glass-button-wrap">
                            <button type="button" class="glass-button" onclick="quickSocialNotice('Google')">
                                <span class="glass-button-text sm">
                                    <svg class="me-1" width="16" height="16" viewBox="0 0 64 64"><g fillRule="evenodd" fill="none"><g fillRule="nonzero" transform="translate(3, 2)"><path fill="#4285F4" d="M57.8 30.2c0-2.4-.2-4.2-.6-6H29.5v10.9h16.3c-.3 2.7-2.1 6.8-6 9.6l-.1.4 8.8 6.8.6.1c5.6-5.1 8.8-12.7 8.8-21.8z"/><path fill="#34A853" d="M29.5 59c8 0 14.6-2.6 19.5-7.2l-9.3-7.2c-2.5 1.7-5.8 2.9-10.2 2.9-7.8 0-14.4-5.1-16.8-12.3l-.3 0-9.1 7.1-.1.3C8 52.4 18 59 29.5 59z"/><path fill="#FBBC05" d="M12.7 35.3c-.6-1.8-1-3.8-1-5.8s.4-4 1-5.8v-.4L3.4 16.1l-.3.1C1.1 20.3 0 24.7 0 29.5s1.1 9.2 3.1 13.2l9.6-7.4z"/><path fill="#EB4335" d="M29.5 11.4c5.5 0 9.3 2.4 11.4 4.4l8.3-8.1C44.1 2.9 37.5 0 29.5 0 18 0 8 6.6 3.1 16.3l9.6 7.4c2.4-7.1 9-12.3 16.8-12.3z"/></g></g></svg>
                                    Google
                                </span>
                            </button>
                        </div>
                        <div class="glass-button-wrap">
                            <button type="button" class="glass-button" onclick="quickSocialNotice('GitHub')">
                                <span class="glass-button-text sm">
                                    <svg class="me-1" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"/></svg>
                                    GitHub
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 my-3">
                        <hr class="flex-grow-1 border-secondary opacity-25">
                        <span class="text-uppercase small text-white-50" style="font-size: 11px; letter-spacing: 1px;">OR</span>
                        <hr class="flex-grow-1 border-secondary opacity-25">
                    </div>
                </div>

                <!-- Email Input Field -->
                <div class="glass-input-wrap">
                    <div class="glass-input">
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <input type="email" id="auth-email-input" placeholder="name@domain.com" autocomplete="email" oninput="checkEmailValidity(this.value)" onkeydown="if(event.key==='Enter'){event.preventDefault(); goToStep(2);}">
                        <div class="glass-button-wrap" id="email-next-wrap" style="opacity: 0; pointer-events: none; transition: opacity 0.3s ease;">
                            <button type="button" class="glass-button" onclick="goToStep(2)" title="Continue">
                                <span class="glass-button-text icon">
                                    <i class="bi bi-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Password -->
            <div class="auth-step-pane" id="step-pane-password">
                <div class="text-center mb-4">
                    <h2 class="h3 fw-light mb-1" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F8F6F1;">
                        Create your password
                    </h2>
                    <p class="small text-white-50 mb-0">Your password must be at least 6 characters long.</p>
                </div>

                <div class="glass-input-wrap">
                    <div class="glass-input">
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                        </div>
                        <input type="password" id="auth-password-input" placeholder="Enter password (min 6 chars)" oninput="checkPasswordValidity(this.value)" onkeydown="if(event.key==='Enter'){event.preventDefault(); goToStep(3);}">
                        <button type="button" class="btn text-white-50 p-2 me-1" onclick="togglePasswordVisibility('auth-password-input', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                        <div class="glass-button-wrap" id="password-next-wrap" style="opacity: 0; pointer-events: none; transition: opacity 0.3s ease;">
                            <button type="button" class="glass-button" onclick="goToStep(3)" title="Next">
                                <span class="glass-button-text icon">
                                    <i class="bi bi-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-start mt-3">
                    <button type="button" class="btn btn-link text-white-50 p-0 text-decoration-none small" onclick="goToStep(1)">
                        <i class="bi bi-arrow-left me-1"></i> Back to email
                    </button>
                </div>
            </div>

            <!-- Step 3: Confirm Password & Submit -->
            <div class="auth-step-pane" id="step-pane-confirm">
                <div class="text-center mb-4">
                    <h2 class="h3 fw-light mb-1" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F8F6F1;">
                        One Last Step
                    </h2>
                    <p class="small text-white-50 mb-0">Confirm your password to complete authentication.</p>
                </div>

                <div class="glass-input-wrap">
                    <div class="glass-input">
                        <div class="input-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <input type="password" id="auth-confirm-input" placeholder="Confirm password" oninput="checkConfirmValidity(this.value)" onkeydown="if(event.key==='Enter'){event.preventDefault(); submitFinalAuth();}">
                        <button type="button" class="btn text-white-50 p-2 me-1" onclick="togglePasswordVisibility('auth-confirm-input', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                        <div class="glass-button-wrap" id="confirm-next-wrap" style="opacity: 0; pointer-events: none; transition: opacity 0.3s ease;">
                            <button type="button" class="glass-button" onclick="submitFinalAuth()" title="Complete Sign In">
                                <span class="glass-button-text icon" style="color: #D4AF37;">
                                    <i class="bi bi-arrow-right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-start mt-3">
                    <button type="button" class="btn btn-link text-white-50 p-0 text-decoration-none small" onclick="goToStep(2)">
                        <i class="bi bi-arrow-left me-1"></i> Back to password
                    </button>
                </div>
            </div>
        </div>

        <!-- TAB 2: Phone OTP Authentication (Indian Standard) -->
        <div id="auth-panel-phone" style="display: none;">
            <div class="text-center mb-4">
                <h2 class="h3 fw-light mb-1" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F8F6F1;">
                    Sign In with Phone
                </h2>
                <p class="small text-white-50 mb-0">Enter your 10-digit mobile number for immediate OTP code.</p>
            </div>

            <form action="/account/send-otp" method="POST" id="phone-send-form">
                <?= csrf_field() ?>
                <div class="glass-input-wrap">
                    <div class="glass-input">
                        <span class="px-3 fw-bold" style="color: #D4AF37; font-size: 14px;">+91</span>
                        <input type="tel" name="phone" id="phone-input" placeholder="9876543210" pattern="[0-9]{10}" maxlength="10" required>
                        <div class="glass-button-wrap">
                            <button type="submit" class="glass-button" title="Send OTP">
                                <span class="glass-button-text sm">Send Code</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="border-top border-secondary opacity-25 my-4"></div>

            <div class="text-center mb-3">
                <span class="small text-white-50">Already received your code?</span>
            </div>

            <form action="/account/verify-otp" method="POST" id="phone-verify-form">
                <?= csrf_field() ?>
                <div class="glass-input-wrap mb-2">
                    <div class="glass-input">
                        <div class="input-icon"><i class="bi bi-phone"></i></div>
                        <input type="tel" name="phone" placeholder="Re-enter phone number" pattern="[0-9]{10}" required>
                    </div>
                </div>
                <div class="glass-input-wrap mb-3">
                    <div class="glass-input">
                        <div class="input-icon"><i class="bi bi-key"></i></div>
                        <input type="text" name="otp" placeholder="6-digit OTP code" maxlength="6" required style="letter-spacing: 4px; font-weight: 600;">
                    </div>
                </div>
                <div class="text-center">
                    <div class="glass-button-wrap w-100">
                        <button type="submit" class="glass-button w-100">
                            <span class="glass-button-text w-100">Verify &amp; Enter Atelier</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Dialog with Progressive TextLoop Animation & Confetti Trigger -->
<div class="auth-modal-backdrop" id="auth-modal">
    <div class="auth-modal-card">
        <div id="modal-spinner-section">
            <div class="auth-spinner"></div>
            <div class="text-loop-container">
                <div class="text-loop-stage" id="text-loop-stage">Signing you up...</div>
            </div>
            <p class="small text-white-50 mt-2 mb-0">Calibrating your personal atelier profile</p>
        </div>

        <div id="modal-success-section" style="display: none;">
            <div class="mb-3" style="font-size: 50px; color: #D4AF37;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <h3 class="h4 text-white mb-2" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif);">
                Welcome Aboard!
            </h3>
            <p class="small text-white-50 mb-4" id="success-message-text">Your atelier authentication is confirmed.</p>
            <div class="glass-button-wrap">
                <a href="/account" class="glass-button text-decoration-none">
                    <span class="glass-button-text sm">Open My Atelier Dashboard</span>
                </a>
            </div>
        </div>

        <div id="modal-error-section" style="display: none;">
            <div class="mb-3" style="font-size: 46px; color: #E57373;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <h3 class="h4 text-white mb-2">Notice</h3>
            <p class="small text-white-50 mb-4" id="modal-error-text">Passwords do not match!</p>
            <div class="glass-button-wrap">
                <button type="button" class="glass-button" onclick="closeAuthModal()">
                    <span class="glass-button-text sm">Try Again</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// --- CLIENT-SIDE MULTI-STEP AUTH LOGIC & CONFETTI CELEBRATION ---
let currentStep = 1;
const textLoopStages = [
    "Signing you up...",
    "Onboarding you...",
    "Finalizing...",
    "Welcome Aboard!"
];

function switchAuthTab(tab) {
    const emailPanel = document.getElementById('auth-panel-email');
    const phonePanel = document.getElementById('auth-panel-phone');
    const btnEmail = document.getElementById('tab-btn-email');
    const btnPhone = document.getElementById('tab-btn-phone');

    if (tab === 'email') {
        emailPanel.style.display = 'block';
        phonePanel.style.display = 'none';
        btnEmail.classList.add('active');
        btnPhone.classList.remove('active');
    } else {
        emailPanel.style.display = 'none';
        phonePanel.style.display = 'block';
        btnPhone.classList.add('active');
        btnEmail.classList.remove('active');
    }
}

function checkEmailValidity(val) {
    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim());
    const nextWrap = document.getElementById('email-next-wrap');
    if (valid) {
        nextWrap.style.opacity = '1';
        nextWrap.style.pointerEvents = 'auto';
    } else {
        nextWrap.style.opacity = '0';
        nextWrap.style.pointerEvents = 'none';
    }
}

function checkPasswordValidity(val) {
    const valid = val.length >= 6;
    const nextWrap = document.getElementById('password-next-wrap');
    if (valid) {
        nextWrap.style.opacity = '1';
        nextWrap.style.pointerEvents = 'auto';
    } else {
        nextWrap.style.opacity = '0';
        nextWrap.style.pointerEvents = 'none';
    }
}

function checkConfirmValidity(val) {
    const valid = val.length >= 6;
    const nextWrap = document.getElementById('confirm-next-wrap');
    if (valid) {
        nextWrap.style.opacity = '1';
        nextWrap.style.pointerEvents = 'auto';
    } else {
        nextWrap.style.opacity = '0';
        nextWrap.style.pointerEvents = 'none';
    }
}

function goToStep(step) {
    document.querySelectorAll('.auth-step-pane').forEach(el => el.classList.remove('active'));
    currentStep = step;

    if (step === 1) {
        document.getElementById('step-pane-email').classList.add('active');
        document.getElementById('auth-email-input').focus();
    } else if (step === 2) {
        document.getElementById('step-pane-password').classList.add('active');
        setTimeout(() => document.getElementById('auth-password-input').focus(), 150);
    } else if (step === 3) {
        document.getElementById('step-pane-confirm').classList.add('active');
        setTimeout(() => document.getElementById('auth-confirm-input').focus(), 150);
    }
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

function quickSocialNotice(provider) {
    alert(provider + ' atelier sign-in initialized. Continuing with Welkar credentials.');
}

// Side Canons Confetti Burst
function fireConfettiCanons() {
    if (typeof confetti === 'function') {
        confetti({
            particleCount: 60,
            angle: 60,
            spread: 55,
            origin: { x: 0, y: 0.85 }
        });
        confetti({
            particleCount: 60,
            angle: 120,
            spread: 55,
            origin: { x: 1, y: 0.85 }
        });
    }
}

// Final Submit via AJAX
function submitFinalAuth() {
    const email = document.getElementById('auth-email-input').value.trim();
    const password = document.getElementById('auth-password-input').value;
    const confirm = document.getElementById('auth-confirm-input').value;

    const modal = document.getElementById('auth-modal');
    const spinnerSection = document.getElementById('modal-spinner-section');
    const successSection = document.getElementById('modal-success-section');
    const errorSection = document.getElementById('modal-error-section');
    const stageText = document.getElementById('text-loop-stage');

    modal.style.display = 'flex';
    spinnerSection.style.display = 'block';
    successSection.style.display = 'none';
    errorSection.style.display = 'none';

    if (password !== confirm) {
        setTimeout(() => {
            spinnerSection.style.display = 'none';
            errorSection.style.display = 'block';
            document.getElementById('modal-error-text').innerText = "Passwords do not match! Please check your typing.";
        }, 600);
        return;
    }

    // Run progressive TextLoop animation
    let stageIndex = 0;
    const loopTimer = setInterval(() => {
        stageIndex++;
        if (stageIndex < textLoopStages.length - 1) {
            stageText.innerText = textLoopStages[stageIndex];
        } else {
            clearInterval(loopTimer);
        }
    }, 1100);

    // Send payload to backend /account/email-auth
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    const csrfToken = document.querySelector('input[name="_csrf_token"]')?.value || '';
    if (csrfToken) {
        formData.append('_csrf_token', csrfToken);
    }

    fetch('/account/email-auth', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        clearInterval(loopTimer);
        if (data.success) {
            stageText.innerText = textLoopStages[textLoopStages.length - 1];
            setTimeout(() => {
                spinnerSection.style.display = 'none';
                successSection.style.display = 'block';
                fireConfettiCanons();
                // Auto redirect to account after 2.2 seconds
                setTimeout(() => {
                    window.location.href = '/account';
                }, 2200);
            }, 600);
        } else {
            spinnerSection.style.display = 'none';
            errorSection.style.display = 'block';
            document.getElementById('modal-error-text').innerText = data.message || "Authentication error.";
        }
    })
    .catch(err => {
        clearInterval(loopTimer);
        spinnerSection.style.display = 'none';
        errorSection.style.display = 'block';
        document.getElementById('modal-error-text').innerText = "Network request failed. Please try again.";
    });
}

function closeAuthModal() {
    document.getElementById('auth-modal').style.display = 'none';
}
</script>
