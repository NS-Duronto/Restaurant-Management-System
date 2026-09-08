<template>
    <Transition name="rms-loader-fade">
        <div v-if="props && props.isActive" 
             :class="[
                 'rms-loader-overlay',
                 isFullScreen ? 'rms-loader-fixed' : 'rms-loader-absolute',
                 isDark ? 'rms-loader-dark' : 'rms-loader-light'
             ]">
            <div class="rms-loader-card">
                <!-- Outer Glowing Spinner Ring -->
                <div class="rms-spinner-wrapper">
                    <div class="rms-spinner-ring"></div>
                    <div class="rms-spinner-ring-inner"></div>
                    <!-- Center Brand Icon with Pulse -->
                    <div class="rms-center-icon">
                        <svg class="rms-utensils-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 3V11C4 12.6569 5.34315 14 7 14C8.65685 14 10 12.6569 10 11V3M7 3V21M4 7H10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 3V21M15 3C16.5 3 20 4.5 20 8C20 11.5 16.5 13 15 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <!-- Brand Name & Status -->
                <div class="rms-loader-brand">
                    <span class="rms-brand-title">Sohoj RMS</span>
                    <div class="rms-loader-status">
                        <span>{{ statusText }}</span>
                        <span class="rms-dots">
                            <span class="rms-dot dot-1"></span>
                            <span class="rms-dot dot-2"></span>
                            <span class="rms-dot dot-3"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    name: "LoadingComponent",
    props: ['props'],
    computed: {
        isFullScreen() {
            return this.props?.isFullScreen !== false;
        },
        isDark() {
            return document.documentElement.classList.contains('dark') || localStorage.getItem('rms_theme') === 'dark';
        },
        statusText() {
            const locale = this.$i18n?.locale || 'bn';
            return locale === 'bn' ? 'লোড হচ্ছে' : 'Loading';
        }
    }
}
</script>

<style scoped>
/* Transition Fade */
.rms-loader-fade-enter-active,
.rms-loader-fade-leave-active {
    transition: opacity 0.25s ease, transform 0.25s ease;
}
.rms-loader-fade-enter-from,
.rms-loader-fade-leave-to {
    opacity: 0;
}

/* Overlay */
.rms-loader-overlay {
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 99999;
}
.rms-loader-fixed {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
}
.rms-loader-absolute {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
}

.rms-loader-light {
    background: rgba(255, 255, 255, 0.72);
}
.rms-loader-dark {
    background: rgba(15, 23, 42, 0.78);
}

/* Glass Card */
.rms-loader-card {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 24px 32px;
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.92);
    box-shadow: 0 20px 40px -15px rgba(249, 115, 22, 0.2), 
                0 0 0 1px rgba(249, 115, 22, 0.12),
                0 10px 25px -5px rgba(0, 0, 0, 0.05);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    animation: rmsCardPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    min-width: 180px;
}

.rms-loader-dark .rms-loader-card {
    background: rgba(24, 24, 27, 0.92);
    border: 1px solid rgba(249, 115, 22, 0.2);
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7), 
                0 0 0 1px rgba(249, 115, 22, 0.18);
}

@keyframes rmsCardPop {
    from {
        transform: scale(0.92);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Spinner Wrapper */
.rms-spinner-wrapper {
    position: relative;
    width: 76px;
    height: 76px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Outer Gradient Ring */
.rms-spinner-ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #f97316;
    border-right-color: #fb923c;
    animation: rmsSpin 1s cubic-bezier(0.55, 0.15, 0.45, 0.85) infinite;
    filter: drop-shadow(0 0 6px rgba(249, 115, 22, 0.4));
}

/* Inner Pulsing Ring */
.rms-spinner-ring-inner {
    position: absolute;
    inset: 6px;
    border-radius: 50%;
    border: 2px dashed rgba(249, 115, 22, 0.35);
    animation: rmsSpinReverse 3s linear infinite;
}

@keyframes rmsSpin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes rmsSpinReverse {
    0% { transform: rotate(360deg); }
    100% { transform: rotate(0deg); }
}

/* Center Icon Badge */
.rms-center-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
    animation: rmsPulse 1.8s ease-in-out infinite;
    z-index: 2;
}

.rms-utensils-icon {
    width: 22px;
    height: 22px;
    color: #ffffff;
}

@keyframes rmsPulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
    }
    50% {
        transform: scale(1.08);
        box-shadow: 0 6px 18px rgba(249, 115, 22, 0.55);
    }
}

/* Brand Text */
.rms-loader-brand {
    margin-top: 14px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.rms-brand-title {
    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.rms-loader-status {
    display: flex;
    align-items: center;
    gap: 2px;
    font-size: 0.78rem;
    font-weight: 500;
    color: #64748b;
}
.rms-loader-dark .rms-loader-status {
    color: #94a3b8;
}

/* Bouncing Dots */
.rms-dots {
    display: inline-flex;
    gap: 3px;
    margin-left: 2px;
}

.rms-dot {
    width: 3.5px;
    height: 3.5px;
    border-radius: 50%;
    background-color: #f97316;
    animation: rmsBounceDot 1.2s infinite ease-in-out both;
}

.rms-dot.dot-1 {
    animation-delay: -0.32s;
}
.rms-dot.dot-2 {
    animation-delay: -0.16s;
}
.rms-dot.dot-3 {
    animation-delay: 0s;
}

@keyframes rmsBounceDot {
    0%, 80%, 100% {
        transform: scale(0);
        opacity: 0.3;
    }
    40% {
        transform: scale(1.2);
        opacity: 1;
    }
}
</style>
