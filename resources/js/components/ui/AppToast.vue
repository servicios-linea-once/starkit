<script setup lang="ts">
import { useToast } from '@/composables/useToast'

const { toasts, remove } = useToast()
</script>

<template>
    <Teleport to="body">
        <div class="toast-stack" role="region" aria-label="Notificaciones" aria-live="polite">
            <TransitionGroup name="toast" tag="div" class="toast-group">
                <div
                    v-for="t in toasts"
                    :key="t.id"
                    :class="['toast', `toast--${t.variant}`]"
                    role="alert"
                >
                    <!-- Ícono -->
                    <div class="toast__icon-wrap">
                        <!-- Success -->
                        <svg v-if="t.variant === 'success'" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <!-- Error -->
                        <svg v-else-if="t.variant === 'error'" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        <!-- Warning -->
                        <svg v-else-if="t.variant === 'warning'" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <!-- Info -->
                        <svg v-else width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>

                    <!-- Mensaje -->
                    <span class="toast__msg">{{ t.message }}</span>

                    <!-- Botón cerrar -->
                    <button class="toast__close" aria-label="Cerrar notificación" @click="remove(t.id)">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>

                    <!-- Barra de progreso -->
                    <div class="toast__bar"/>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<style scoped>
/* ── Contenedor ─────────────────────────────────────── */
.toast-stack {
    position: fixed;
    top: var(--space-3);
    right:  var(--space-6);
    z-index: 9999;
    pointer-events: none;
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.toast-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

/* ── Toast base ─────────────────────────────────────── */
.toast {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-3) var(--space-3) var(--space-4);
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    box-shadow:
        0 2px 8px  oklch(0.15 0.01 80 / 0.08),
        0 8px 24px oklch(0.15 0.01 80 / 0.10);
    max-width: 380px;
    min-width: 280px;
    pointer-events: all;
    position: relative;
    overflow: hidden;
    cursor: default;
}

/* ── Acento lateral por variante ────────────────────── */
.toast::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    border-radius: var(--radius-lg) 0 0 var(--radius-lg);
}

.toast--success::before { background: var(--color-success); }
.toast--error::before   { background: var(--color-error); }
.toast--warning::before { background: var(--color-warning); }
.toast--info::before    { background: var(--color-blue); }

/* ── Ícono ──────────────────────────────────────────── */
.toast__icon-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: var(--radius-md);
    flex-shrink: 0;
}

.toast--success .toast__icon-wrap { background: color-mix(in oklch, var(--color-success) 12%, transparent); color: var(--color-success); }
.toast--error   .toast__icon-wrap { background: color-mix(in oklch, var(--color-error)   12%, transparent); color: var(--color-error); }
.toast--warning .toast__icon-wrap { background: color-mix(in oklch, var(--color-warning) 12%, transparent); color: var(--color-warning); }
.toast--info    .toast__icon-wrap { background: color-mix(in oklch, var(--color-blue)    12%, transparent); color: var(--color-blue); }

/* ── Mensaje ────────────────────────────────────────── */
.toast__msg {
    flex: 1;
    font-size: var(--text-sm);
    color: var(--color-text);
    line-height: 1.45;
    font-weight: 450;
}

/* ── Botón cerrar ───────────────────────────────────── */
.toast__close {
    width: 26px;
    height: 26px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-text-faint);
    flex-shrink: 0;
    transition: background var(--transition-interactive), color var(--transition-interactive);
}

.toast__close:hover {
    background: var(--color-surface-offset);
    color: var(--color-text);
}

/* ── Barra de progreso ──────────────────────────────── */
.toast__bar {
    position: absolute;
    bottom: 0; left: 0;
    height: 2px;
    width: 100%;
    transform-origin: left center;
    animation: toast-progress 4s linear forwards;
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
}

.toast--success .toast__bar { background: var(--color-success); }
.toast--error   .toast__bar { background: var(--color-error); }
.toast--warning .toast__bar { background: var(--color-warning); }
.toast--info    .toast__bar { background: var(--color-blue); }

@keyframes toast-progress {
    from { transform: scaleX(1); }
    to   { transform: scaleX(0); }
}

/* ── Animaciones entrada / salida ───────────────────── */
.toast-enter-active {
    transition: opacity 300ms cubic-bezier(0.16, 1, 0.3, 1),
    transform 300ms cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-leave-active {
    transition: opacity 200ms ease-in,
    transform 200ms ease-in;
    position: absolute;
}
.toast-move {
    transition: transform 300ms cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-enter-from {
    opacity: 0;
    transform: translateX(20px) scale(0.95);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(24px) scale(0.94);
}

/* ── Mobile ─────────────────────────────────────────── */
@media (max-width: 480px) {
    .toast-stack {
        bottom: var(--space-4);
        right:  var(--space-4);
        left:   var(--space-4);
    }
    .toast {
        max-width: 100%;
        min-width: unset;
        width: 100%;
    }
}
</style>
