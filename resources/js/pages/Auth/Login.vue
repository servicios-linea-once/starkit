<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'

defineProps<{
    canResetPassword: boolean
    status?:          string
}>()

const form = useForm({
    email:    '',
    password: '',
    remember: false,
})

function submit() {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <AuthLayout title="Iniciar sesión" subtitle="Ingresa tus credenciales para continuar.">

        <Transition name="fade">
            <div v-if="status" class="alert-success" role="status">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                {{ status }}
            </div>
        </Transition>

        <form @submit.prevent="submit" class="form" novalidate>

            <AppInput
                id="email"
                v-model="form.email"
                label="Correo electrónico"
                type="email"
                placeholder="tu@correo.com"
                autocomplete="email"
                :error="form.errors.email"
            >
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </template>
            </AppInput>

            <AppInput
                id="password"
                v-model="form.password"
                label="Contraseña"
                type="password"
                placeholder="••••••••"
                autocomplete="current-password"
                :error="form.errors.password"
            >
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </template>
            </AppInput>

            <div class="form__row">
                <label class="checkbox">
                    <input type="checkbox" v-model="form.remember" />
                    <span>Recordarme</span>
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')" class="link">
                    ¿Olvidaste tu contraseña?
                </Link>
            </div>

            <AppButton type="submit" variant="primary" size="md"
                       :loading="form.processing" :block="true">
                Iniciar sesión
            </AppButton>

        </form>

        <p class="auth-link">
            ¿No tienes cuenta?
            <Link :href="route('register')" class="link">Regístrate</Link>
        </p>

    </AuthLayout>
</template>

<style scoped>
.form         { display: flex; flex-direction: column; gap: var(--space-4); }
.form__row    { display: flex; align-items: center; justify-content: space-between; }
.checkbox     { display: flex; align-items: center; gap: var(--space-2); font-size: var(--text-xs); color: var(--color-text-muted); cursor: pointer; }
.checkbox input { accent-color: var(--color-primary); }
.link         { font-size: var(--text-xs); font-weight: 500; color: var(--color-primary); text-decoration: none; }
.link:hover   { text-decoration: underline; }
.auth-link    { font-size: var(--text-xs); color: var(--color-text-muted); text-align: center; }
.alert-success { display: flex; align-items: center; gap: var(--space-2); padding: var(--space-3) var(--space-4); background: color-mix(in oklch, var(--color-success) 12%, transparent); color: var(--color-success); border: 1px solid color-mix(in oklch, var(--color-success) 25%, transparent); border-radius: var(--radius-md); font-size: var(--text-xs); font-weight: 500; }
.fade-enter-active, .fade-leave-active { transition: opacity 180ms; }
.fade-enter-from,   .fade-leave-to     { opacity: 0; }
</style>
