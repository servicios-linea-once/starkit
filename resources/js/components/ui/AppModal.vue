<script setup lang="ts">
import { onMounted, onUnmounted, watch } from 'vue'

const props = withDefaults(defineProps<{
    show:   boolean
    title:  string
    size?:  'sm' | 'md' | 'lg'
}>(), {
    size: 'md',
})

const emit = defineEmits<{
    (e: 'close'): void
}>()

// Cerrar con Escape
function onKeydown(e: KeyboardEvent) {
    if (e.key === 'Escape' && props.show) emit('close')
}
onMounted(()  => document.addEventListener('keydown', onKeydown))
onUnmounted(() => document.removeEventListener('keydown', onKeydown))

// Bloquear scroll del body cuando el modal está abierto
watch(() => props.show, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show" class="modal-backdrop" role="dialog"
                 :aria-modal="true" :aria-label="title"
                 @mousedown.self="emit('close')">

                <div :class="['modal', `modal--${size}`]">

                    <!-- Header -->
                    <div class="modal__head">
                        <h2 class="modal__title">{{ title }}</h2>
                        <button
                            class="modal__close"
                            aria-label="Cerrar modal"
                            @click="emit('close')"
                        >
                            <svg width="14" height="14" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal__body">
                        <slot />
                    </div>

                    <!-- Footer (opcional) -->
                    <div v-if="$slots.footer" class="modal__footer">
                        <slot name="footer" />
                    </div>

                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
/* ── Backdrop ──────────────────────────────────────────────── */
.modal-backdrop {
    position:        fixed;
    inset:           0;
    background:      oklch(0 0 0 / 0.45);
    backdrop-filter: blur(3px);
    display:         flex;
    align-items:     center;
    justify-content: center;
    padding:         var(--space-4);
    z-index:         50;
}

/* ── Modal box ─────────────────────────────────────────────── */
.modal {
    background:     var(--color-surface);
    border:         1px solid var(--color-border);
    border-radius:  var(--radius-xl);
    box-shadow:     var(--shadow-lg);
    display:        flex;
    flex-direction: column;
    max-height:     calc(100dvh - var(--space-8));
    width:          100%;
    overflow:       hidden;
}

.modal--sm { max-width: 400px; }
.modal--md { max-width: 560px; }
.modal--lg { max-width: 720px; }

/* ── Head ──────────────────────────────────────────────────── */
.modal__head {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    gap:             var(--space-4);
    padding:         var(--space-4) var(--space-5);
    border-bottom:   1px solid var(--color-divider);
    flex-shrink:     0;
}
.modal__title {
    font-size:   var(--text-sm);
    font-weight: 600;
    color:       var(--color-text);
}
.modal__close {
    width:           28px;
    height:          28px;
    border-radius:   var(--radius-md);
    display:         flex;
    align-items:     center;
    justify-content: center;
    color:           var(--color-text-muted);
    flex-shrink:     0;
    transition:      background var(--transition-interactive),
    color      var(--transition-interactive);
}
.modal__close:hover {
    background: var(--color-surface-offset);
    color:      var(--color-text);
}

/* ── Body ──────────────────────────────────────────────────── */
.modal__body {
    padding:    var(--space-5);
    overflow-y: auto;
    flex:       1;
}

/* ── Footer ────────────────────────────────────────────────── */
.modal__footer {
    display:         flex;
    align-items:     center;
    justify-content: flex-end;
    gap:             var(--space-3);
    padding:         var(--space-4) var(--space-5);
    border-top:      1px solid var(--color-divider);
    flex-shrink:     0;
}

/* ── Transition ────────────────────────────────────────────── */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 200ms cubic-bezier(0.16, 1, 0.3, 1);
}
.modal-enter-active .modal,
.modal-leave-active .modal {
    transition: transform 200ms cubic-bezier(0.16, 1, 0.3, 1),
    opacity   200ms cubic-bezier(0.16, 1, 0.3, 1);
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-from .modal,
.modal-leave-to   .modal {
    transform: scale(0.96) translateY(8px);
    opacity:   0;
}
</style>
