<script setup lang="ts">
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AppButton  from '@/components/ui/AppButton.vue'

const props  = defineProps<{ status?: string }>()
const form   = useForm({})
const logoutForm = useForm({})
const justSent   = computed(() => props.status === 'verification-link-sent')

function resend() { form.post(route('verification.send')) }
function logout()  { logoutForm.post(route('logout')) }
</script>

<template>
    <AuthLayout title="Verifica tu correo" subtitle="Antes de continuar, revisa tu bandeja de entrada.">

        <Transition name="fade">
            <div v-if="justSent" class="alert-success" role="status">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Enlace de verificación enviado. Revisa tu correo.
            </div>
        </Transition>

        <div class="info-box">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="info-icon">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
            <p class="info-text">Te enviamos un enlace de verificación. Si no lo recibiste, solicita uno nuevo.</p>
        </div>

        <AppButton variant="primary" size="md" :loading="form.processing" :block="true" @click="resend">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 4 23 10 17 10"/>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
            </svg>
            Reenviar enlace
        </AppButton>

        <div class="divider"><div class="line" /><span>o</span><div class="line" /></div>

        <AppButton variant="ghost" size="md" :loading="logoutForm.processing" :block="true" @click="logout">
            Cerrar sesión
        </AppButton>

    </AuthLayout>
</template>

<style scoped>
.info-box  { display: flex; align-items: flex-start; gap: var(--space-3); padding: var(--space-4); background: var(--color-surface-offset); border: 1px solid var(--color-divider); border-radius: var(--radius-lg); }
.info-icon { color: var(--color-primary); flex-shrink: 0; margin-top: 1px; }
.info-text { font-size: var(--text-xs); color: var(--color-text-muted); line-height: 1.7; }
.divider   { display: flex; align-items: center; gap: var(--space-3); }
.line      { flex: 1; height: 1px; background: var(--color-divider); }
.divider span { font-size: var(--text-xs); color: var(--color-text-faint); }
.alert-success { display: flex; align-items: center; gap: var(--space-2); padding: var(--space-3) var(--space-4); background: color-mix(in oklch, var(--color-success) 12%, transparent); color: var(--color-success); border: 1px solid color-mix(in oklch, var(--color-success) 25%, transparent); border-radius: var(--radius-md); font-size: var(--text-xs); font-weight: 500; }
.fade-enter-active, .fade-leave-active { transition: opacity 180ms; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
