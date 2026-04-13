<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'

const props = defineProps<{ token: string; email: string }>()

const form = useForm({
    token:                 props.token,
    email:                 props.email,
    password:              '',
    password_confirmation: '',
})

function submit() {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <AuthLayout title="Nueva contraseña" subtitle="Establece tu nueva contraseña de acceso.">
        <form @submit.prevent="submit" class="form" novalidate>

            <AppInput id="email" v-model="form.email" label="Correo electrónico"
                      type="email" autocomplete="email" :error="form.errors.email">
                <template #icon>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </template>
            </AppInput>

            <AppInput id="password" v-model="form.password" label="Nueva contraseña"
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
                Restablecer contraseña
            </AppButton>

        </form>
    </AuthLayout>
</template>

<style scoped>
.form { display: flex; flex-direction: column; gap: var(--space-4); }
</style>
