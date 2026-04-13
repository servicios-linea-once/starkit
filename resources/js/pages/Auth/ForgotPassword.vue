<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'

defineProps<{ status?: string }>()

const form = useForm({ email: '' })

function submit() {
    form.post(route('password.email'))
}
</script>

<template>
    <AuthLayout title="Recuperar contraseña" subtitle="Te enviaremos un enlace para restablecerla.">

        <Transition name="fade">
            <div v-if="status" class="alert-success" role="status">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                {{ status }}
            </div>
        </Transition>

        <form @submit.prevent="submit" class="form" novalidate>

            <AppInput id="email" v-model="form.email" label="Correo electrónico"
                      type="email" placeholder="tu@correo.com" autocomplete="email" :error="form.errors.email">
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </template>
            </AppInput>

            <AppButton type="submit" variant="primary" size="md"
                       :loading="form.processing" :block="true">
                Enviar enlace
            </AppButton>

        </form>

        <p class="auth-link">
            <Link :href="route('login')" class="link">← Volver al login</Link>
        </p>

    </AuthLayout>
</template>

<style scoped>
.form         { display: flex; flex-direction: column; gap: var(--space-4); }
.auth-link    { text-align: center; }
.link         { font-size: var(--text-xs); font-weight: 500; color: var(--color-primary); text-decoration: none; }
.link:hover   { text-decoration: underline; }
.alert-success { display: flex; align-items: center; gap: var(--space-2); padding: var(--space-3) var(--space-4); background: color-mix(in oklch, var(--color-success) 12%, transparent); color: var(--color-success); border: 1px solid color-mix(in oklch, var(--color-success) 25%, transparent); border-radius: var(--radius-md); font-size: var(--text-xs); font-weight: 500; }
.fade-enter-active, .fade-leave-active { transition: opacity 180ms; }
.fade-enter-from,   .fade-leave-to     { opacity: 0; }
</style>
