<script setup lang="ts">
import { useTheme } from '@/composables/useTheme'

defineProps<{
    title:    string
    subtitle?: string
}>()

const { isDark, toggle } = useTheme()
</script>

<template>
    <div class="auth">

        <!-- Toggle tema -->
        <button class="auth__theme" @click="toggle" :aria-label="`Cambiar a modo ${isDark ? 'claro' : 'oscuro'}`">
            <svg v-if="isDark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5"/>
                <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
            </svg>
            <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </button>

        <!-- Panel central -->
        <div class="auth__card">

            <!-- Logo -->
            <div class="auth__logo">
                <div class="auth__logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="auth__logo-name">StarKit</span>
            </div>

            <!-- Encabezado -->
            <div class="auth__head">
                <h1 class="auth__title">{{ title }}</h1>
                <p v-if="subtitle" class="auth__subtitle">{{ subtitle }}</p>
            </div>

            <!-- Contenido del formulario -->
            <div class="auth__content">
                <slot />
            </div>

        </div>

        <!-- Pie -->
        <p class="auth__footer">
            © {{ new Date().getFullYear() }} StarKit
        </p>
    </div>
</template>

<style scoped>
.auth {
    min-height:      100dvh;
    display:         flex;
    flex-direction:  column;
    align-items:     center;
    justify-content: center;
    padding:         var(--space-6) var(--space-4);
    background:      var(--color-bg);
    position:        relative;
}

.auth__theme {
    position:      absolute;
    top:           var(--space-4);
    right:         var(--space-4);
    color:         var(--color-text-muted);
    border-radius: var(--radius-md);
    padding:       var(--space-2);
    display:       flex;
}
.auth__theme:hover { color: var(--color-text); background: var(--color-surface-offset); }

.auth__card {
    background:    var(--color-surface);
    border:        1px solid var(--color-border);
    border-radius: var(--radius-xl);
    box-shadow:    var(--shadow-md);
    padding:       var(--space-8);
    width:         100%;
    max-width:     420px;
    display:       flex;
    flex-direction: column;
    gap:           var(--space-6);
}

.auth__logo {
    display:     flex;
    align-items: center;
    gap:         var(--space-2);
}

.auth__logo-icon {
    width:           36px;
    height:          36px;
    background:      var(--color-primary);
    border-radius:   var(--radius-md);
    display:         flex;
    align-items:     center;
    justify-content: center;
    color:           white;
    flex-shrink:     0;
}

.auth__logo-name {
    font-size:   var(--text-lg);
    font-weight: 700;
    color:       var(--color-text);
    letter-spacing: -0.03em;
}

.auth__head { display: flex; flex-direction: column; gap: var(--space-1); }

.auth__title {
    font-size:   var(--text-xl);
    font-weight: 700;
    color:       var(--color-text);
    letter-spacing: -0.02em;
}

.auth__subtitle {
    font-size: var(--text-sm);
    color:     var(--color-text-muted);
}

.auth__content { display: flex; flex-direction: column; gap: var(--space-4); }

.auth__footer {
    margin-top: var(--space-6);
    font-size:  var(--text-xs);
    color:      var(--color-text-faint);
}
</style>
