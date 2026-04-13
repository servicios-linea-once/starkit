<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'

const form = useForm({
    name:                  '',
    email:                 '',
    password:              '',
    password_confirmation: '',
})

function submit() {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <AuthLayout title="Crear cuenta" subtitle="Completa el formulario para registrarte.">

        <form @submit.prevent="submit" class="form" novalidate>

            <AppInput id="name" v-model="form.name" label="Nombre completo"
                      placeholder="Juan Pérez" autocomplete="name" :error="form.errors.name">
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                    </svg>
                </template>
            </AppInput>

            <AppInput id="email" v-model="form.email" label="Correo electrónico"
                      type="email" placeholder="tu@correo.com" autocomplete="email" :error="form.errors.email">
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </template>
            </AppInput>

            <AppInput id="password" v-model="form.password" label="Contraseña"
                      type="password" placeholder="••••••••" autocomplete="new-password" :error="form.errors.password">
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </template>
            </AppInput>

            <AppInput id="password_confirmation" v-model="form.password_confirmation"
                      label="Confirmar contraseña" type="password" placeholder="••••••••"
                      autocomplete="new-password" :error="form.errors.password_confirmation">
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </template>
            </AppInput>

            <AppButton type="submit" variant="primary" size="md"
                       :loading="form.processing" :block="true">
                Crear cuenta
            </AppButton>

        </form>

        <p class="auth-link">
            ¿Ya tienes cuenta?
            <Link :href="route('login')" class="link">Inicia sesión</Link>
        </p>

    </AuthLayout>
</template>

<style scoped>
.form      { display: flex; flex-direction: column; gap: var(--space-4); }
.auth-link { font-size: var(--text-xs); color: var(--color-text-muted); text-align: center; }
.link      { font-size: var(--text-xs); font-weight: 500; color: var(--color-primary); text-decoration: none; }
.link:hover { text-decoration: underline; }
</style>
