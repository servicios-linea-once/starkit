<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppLayout  from '@/layouts/AppLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'

const form = useForm({
    current_password:      '',
    password:              '',
    password_confirmation: '',
})

function save() {
    form.put(route('settings.password.update'), {
        onSuccess: () => form.reset(),
        onError:   () => form.reset('password', 'password_confirmation'),
    })
}

// Indicador de fuerza de contraseña
function strength(pwd: string): { level: number; label: string; color: string } {
    if (!pwd) return { level: 0, label: '', color: '' }
    let score = 0
    if (pwd.length >= 8)          score++
    if (pwd.length >= 12)         score++
    if (/[A-Z]/.test(pwd))        score++
    if (/[0-9]/.test(pwd))        score++
    if (/[^A-Za-z0-9]/.test(pwd)) score++

    if (score <= 1) return { level: 1, label: 'Muy débil',  color: 'var(--color-error)' }
    if (score === 2) return { level: 2, label: 'Débil',     color: 'var(--color-warning)' }
    if (score === 3) return { level: 3, label: 'Regular',   color: 'var(--color-gold)' }
    if (score === 4) return { level: 4, label: 'Fuerte',    color: 'var(--color-success)' }
    return               { level: 5, label: 'Muy fuerte', color: 'var(--color-success)' }
}

const MAX_BARS = 5
</script>

<template>
    <AppLayout>
        <div class="settings">

            <!-- Header -->
            <div class="settings-header">
                <h1 class="settings-header__title">Contraseña</h1>
                <p class="settings-header__sub">Actualiza tu contraseña de acceso regularmente para mantener tu cuenta segura.</p>
            </div>

            <!-- Card contraseña -->
            <section class="card">
                <div class="card__head">
                    <h2 class="card__title">Cambiar contraseña</h2>
                    <p class="card__desc">Usa una contraseña larga con letras, números y símbolos.</p>
                </div>

                <form @submit.prevent="save" class="form" novalidate>

                    <AppInput id="current_password" v-model="form.current_password"
                              label="Contraseña actual" type="password"
                              placeholder="••••••••" autocomplete="current-password"
                              :error="form.errors.current_password">
                        <template #icon>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </template>
                    </AppInput>

                    <div class="field-group">
                        <AppInput id="password" v-model="form.password"
                                  label="Nueva contraseña" type="password"
                                  placeholder="••••••••" autocomplete="new-password"
                                  :error="form.errors.password">
                            <template #icon>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </template>
                        </AppInput>

                        <!-- Indicador de fuerza -->
                        <Transition name="fade">
                            <div v-if="form.password" class="strength">
                                <div class="strength__bars">
                                    <div
                                        v-for="i in MAX_BARS"
                                        :key="i"
                                        class="strength__bar"
                                        :style="{
                                            background: i <= strength(form.password).level
                                                ? strength(form.password).color
                                                : 'var(--color-surface-dynamic)'
                                        }"
                                    />
                                </div>
                                <span class="strength__label"
                                      :style="{ color: strength(form.password).color }">
                                    {{ strength(form.password).label }}
                                </span>
                            </div>
                        </Transition>
                    </div>

                    <AppInput id="password_confirmation" v-model="form.password_confirmation"
                              label="Confirmar nueva contraseña" type="password"
                              placeholder="••••••••" autocomplete="new-password"
                              :error="form.errors.password_confirmation">
                        <template #icon>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </template>
                    </AppInput>

                    <div class="form__footer">
                        <Transition name="fade">
                            <p v-if="form.recentlySuccessful" class="form__saved" role="status">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Contraseña actualizada
                            </p>
                        </Transition>
                        <AppButton type="submit" variant="primary" size="md"
                                   :loading="form.processing">
                            Actualizar contraseña
                        </AppButton>
                    </div>

                </form>
            </section>

            <!-- Card consejos -->
            <section class="card card--tips">
                <h2 class="card__title">Consejos de seguridad</h2>
                <ul class="tips-list">
                    <li class="tip-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        Usa al menos 12 caracteres
                    </li>
                    <li class="tip-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        Combina letras mayúsculas, minúsculas, números y símbolos
                    </li>
                    <li class="tip-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        No uses la misma contraseña en otros sitios
                    </li>
                    <li class="tip-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        Activa la verificación en dos pasos desde tu perfil
                    </li>
                </ul>
            </section>

        </div>
    </AppLayout>
</template>

<style scoped>
.settings        { display: flex; flex-direction: column; gap: var(--space-6); max-width: 640px; }
.settings-header { display: flex; flex-direction: column; gap: var(--space-1); }
.settings-header__title { font-size: var(--text-xl); font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
.settings-header__sub   { font-size: var(--text-sm); color: var(--color-text-muted); }

/* Card */
.card {
    background:     var(--color-surface);
    border:         1px solid var(--color-border);
    border-radius:  var(--radius-xl);
    padding:        var(--space-6);
    display:        flex;
    flex-direction: column;
    gap:            var(--space-5);
    box-shadow:     var(--shadow-sm);
}
.card--tips { background: var(--color-surface-offset); }
.card__head { display: flex; flex-direction: column; gap: var(--space-1); }
.card__title { font-size: var(--text-base); font-weight: 600; color: var(--color-text); }
.card__desc  { font-size: var(--text-xs); color: var(--color-text-muted); }

/* Form */
.form        { display: flex; flex-direction: column; gap: var(--space-4); }
.field-group { display: flex; flex-direction: column; gap: var(--space-2); }
.form__footer { display: flex; align-items: center; justify-content: space-between; gap: var(--space-3); }
.form__saved  { display: flex; align-items: center; gap: var(--space-1); font-size: var(--text-xs); font-weight: 500; color: var(--color-success); }

/* Indicador de fuerza */
.strength       { display: flex; align-items: center; gap: var(--space-3); }
.strength__bars { display: flex; gap: var(--space-1); }
.strength__bar  { width: 28px; height: 4px; border-radius: var(--radius-full); transition: background 300ms; }
.strength__label { font-size: var(--text-xs); font-weight: 600; }

/* Tips */
.tips-list  { display: flex; flex-direction: column; gap: var(--space-3); list-style: none; }
.tip-item   { display: flex; align-items: center; gap: var(--space-3); font-size: var(--text-xs); color: var(--color-text-muted); }
.tip-item svg { color: var(--color-primary); flex-shrink: 0; }

/* Transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 180ms; }
.fade-enter-from,   .fade-leave-to     { opacity: 0; }

/* Responsive */
@media (max-width: 480px) {
    .form__footer { flex-direction: column-reverse; align-items: stretch; }
}
</style>
