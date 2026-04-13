<script setup lang="ts">
import type { ButtonVariant, ButtonSize } from '@/types'

withDefaults(defineProps<{
    variant?:  ButtonVariant
    size?:     ButtonSize
    loading?:  boolean
    disabled?: boolean
    type?:     'button' | 'submit' | 'reset'
}>(), {
    variant:  'primary',
    size:     'md',
    loading:  false,
    disabled: false,
    type:     'button',
})
</script>

<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="['btn', `btn--${variant}`, `btn--${size}`, { 'btn--loading': loading }]"
        :aria-busy="loading"
    >
        <!-- Spinner -->
        <svg v-if="loading"
             class="btn__spinner"
             width="14" height="14"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2.5"
             aria-hidden="true">
            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
        </svg>

        <slot />
    </button>
</template>

<style scoped>
.btn {
    display:         inline-flex;
    align-items:     center;
    justify-content: center;
    gap:             var(--space-2);
    font-weight:     500;
    border-radius:   var(--radius-md);
    border:          1px solid transparent;
    white-space:     nowrap;
    cursor:          pointer;
    text-decoration: none;
    transition:      background      var(--transition-interactive),
    color           var(--transition-interactive),
    border-color    var(--transition-interactive),
    box-shadow      var(--transition-interactive),
    opacity         var(--transition-interactive);
}

/* ── Disabled ──────────────────────────────────────────────── */
.btn:disabled {
    opacity:        0.5;
    cursor:         not-allowed;
    pointer-events: none;
}

/* ── Sizes ─────────────────────────────────────────────────── */
.btn--sm { height: 32px; padding: 0 var(--space-3); font-size: var(--text-xs); }
.btn--md { height: 38px; padding: 0 var(--space-4); font-size: var(--text-sm); }
.btn--lg { height: 44px; padding: 0 var(--space-5); font-size: var(--text-base); }

/* ── Variants ──────────────────────────────────────────────── */
.btn--primary {
    background:   var(--color-primary);
    color:        var(--color-text-inverse);
    border-color: var(--color-primary);
}
.btn--primary:hover:not(:disabled) {
    background:   var(--color-primary-hover);
    border-color: var(--color-primary-hover);
}
.btn--primary:active:not(:disabled) {
    background:   var(--color-primary-active);
    border-color: var(--color-primary-active);
}

.btn--secondary {
    background:   var(--color-surface);
    color:        var(--color-text);
    border-color: var(--color-border);
    box-shadow:   var(--shadow-sm);
}
.btn--secondary:hover:not(:disabled) {
    background: var(--color-surface-offset);
}
.btn--secondary:active:not(:disabled) {
    background: var(--color-surface-dynamic);
}

.btn--ghost {
    background:   transparent;
    color:        var(--color-text-muted);
    border-color: transparent;
}
.btn--ghost:hover:not(:disabled) {
    background: var(--color-surface-offset);
    color:      var(--color-text);
}

.btn--danger {
    background:   var(--color-error);
    color:        #fff;
    border-color: var(--color-error);
}
.btn--danger:hover:not(:disabled) {
    background:   var(--color-error-hover);
    border-color: var(--color-error-hover);
}
.btn--danger:active:not(:disabled) {
    background:   var(--color-error-active);
    border-color: var(--color-error-active);
}

/* ── Loading spinner ───────────────────────────────────────── */
.btn__spinner {
    animation:    spin 0.7s linear infinite;
    flex-shrink:  0;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
