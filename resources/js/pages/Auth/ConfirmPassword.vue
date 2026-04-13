<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'

const form = useForm({ password: '' })

function submit() {
    form.post(route('password.confirm'), { onFinish: () => form.reset() })
}
</script>

<template>
    <AuthLayout title="Confirma tu contraseña" subtitle="Por seguridad, confirma tu contraseña para continuar.">

        <div class="info-box">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="info-icon">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <p class="info-text">Estás accediendo a una zona segura. Esta confirmación expira tras un período de inactividad.</p>
        </div>

        <form @submit.prevent="submit" class="form" novalidate>
            <AppInput id="password" v-model="form.password" label="Contraseña actual"
                      type="password" placeholder="••••••••" autocomplete="current-password"
                      :error="form.errors.password">
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </template>
            </AppInput>

            <AppButton type="submit" variant="primary" size="md"
                       :loading="form.processing" :block="true">
                Confirmar y continuar
            </AppButton>
        </form>

    </AuthLayout>
</template>

<style scoped>
.form      { display: flex; flex-direction: column; gap: var(--space-4); }
.info-box  { display: flex; align-items: flex-start; gap: var(--space-3); padding: var(--space-4); background: color-mix(in oklch, var(--color-primary) 8%, transparent); border: 1px solid color-mix(in oklch, var(--color-primary) 20%, transparent); border-radius: var(--radius-lg); }
.info-icon { color: var(--color-primary); flex-shrink: 0; margin-top: 1px; }
.info-text { font-size: var(--text-xs); color: var(--color-text-muted); line-height: 1.7; }
</style>
