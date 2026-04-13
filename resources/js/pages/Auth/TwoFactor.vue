<script setup lang="ts">
import { computed } from 'vue'
import { useForm }  from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'

const props      = defineProps<{ status?: string }>()
const form       = useForm({ code: '' })
const resendForm = useForm({})
const justResent = computed(() => props.status === 'Código reenviado a tu correo.')

function submit() {
    form.post(route('two-factor.store'), { onFinish: () => form.reset() })
}
function resend() {
    resendForm.post(route('two-factor.resend'))
}
</script>

<template>
    <AuthLayout title="Verificación en dos pasos" subtitle="Ingresa el código de 6 dígitos enviado a tu correo.">

        <Transition name="fade">
            <div v-if="justResent" class="alert-success" role="status">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Código reenviado. Revisa tu correo.
            </div>
        </Transition>

        <div class="info-box">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="info-icon">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <p class="info-text">El código expira en <strong>10 minutos</strong>. Si no lo recibes, puedes reenviarlo.</p>
        </div>

        <form @submit.prevent="submit" class="form" novalidate>
            <AppInput id="code" v-model="form.code" label="Código de verificación"
                      placeholder="000000" maxlength="6" inputmode="numeric"
                      autocomplete="one-time-code" :error="form.errors.code">
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </template>
            </AppInput>
            <AppButton type="submit" variant="primary" size="md" :loading="form.processing" :block="true">
                Verificar código
            </AppButton>
        </form>

        <div class="divider"><div class="line" /><span>¿No recibiste el código?</span><div class="line" /></div>

        <AppButton variant="ghost" size="md" :loading="resendForm.processing" :block="true" @click="resend">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 4 23 10 17 10"/>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
            </svg>
            Reenviar código
        </AppButton>

    </AuthLayout>
</template>

<style scoped>
.form      { display: flex; flex-direction: column; gap: var(--space-4); }
.info-box  { display: flex; align-items: flex-start; gap: var(--space-3); padding: var(--space-4); background: color-mix(in oklch, var(--color-primary) 8%, transparent); border: 1px solid color-mix(in oklch, var(--color-primary) 20%, transparent); border-radius: var(--radius-lg); }
.info-icon { color: var(--color-primary); flex-shrink: 0; margin-top: 1px; }
.info-text { font-size: var(--text-xs); color: var(--color-text-muted); line-height: 1.7; }
.info-text strong { color: var(--color-text); font-weight: 600; }
.divider   { display: flex; align-items: center; gap: var(--space-3); }
.line      { flex: 1; height: 1px; background: var(--color-divider); }
.divider span { font-size: var(--text-xs); color: var(--color-text-faint); white-space: nowrap; }
.alert-success { display: flex; align-items: center; gap: var(--space-2); padding: var(--space-3) var(--space-4); background: color-mix(in oklch, var(--color-success) 12%, transparent); color: var(--color-success); border: 1px solid color-mix(in oklch, var(--color-success) 25%, transparent); border-radius: var(--radius-md); font-size: var(--text-xs); font-weight: 500; }
.fade-enter-active, .fade-leave-active { transition: opacity 180ms; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
