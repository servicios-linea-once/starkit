<script setup lang="ts">
import { ref, computed }      from 'vue'
import { useForm, usePage }   from '@inertiajs/vue3'
import AppLayout  from '@/layouts/AppLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'
import AppBadge   from '@/components/ui/AppBadge.vue'
import AppModal   from '@/components/ui/AppModal.vue'
import type { PageProps } from '@inertiajs/core'
const page = usePage<PageProps>()

const user = computed(() => page.props.auth.user!)
// ── Formulario de perfil ──────────────────────────────────────────────
const profileForm = useForm({
    name:  user.value.name,
    email: user.value.email,
})

function saveProfile() {
    profileForm.patch(route('settings.profile.update'))
}

// ── Avatar ───────────────────────────────────────────────────────────
const avatarInput    = ref<HTMLInputElement | null>(null)
const avatarPreview  = ref<string | null>(user.value.avatar_url)
const avatarForm     = useForm<{ avatar: File | null }>({ avatar: null })
const removeAvatarForm = useForm({})

const initials = computed(() =>
    user.value.name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((w: string) => w[0].toUpperCase())
        .join('')
)

function pickAvatar() {
    avatarInput.value?.click()
}

function onAvatarChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0]
    if (!file) return
    avatarForm.avatar = file
    avatarPreview.value = URL.createObjectURL(file)
}

function uploadAvatar() {
    avatarForm.post(route('settings.avatar.update'), {
        forceFormData: true,
        onSuccess: () => { avatarForm.reset() },
    })
}

function removeAvatar() {
    removeAvatarForm.delete(route('settings.avatar.destroy'), {
        onSuccess: () => { avatarPreview.value = null },
    })
}

// ── 2FA ──────────────────────────────────────────────────────────────
const twoFactorForm  = useForm({})
const showDisable2FA = ref(false)

function toggle2FA() {
    if (user.value.two_factor_enabled) {
        showDisable2FA.value = true
    } else {
        twoFactorForm.post(route('settings.two-factor.enable'), {
            onSuccess: () => showDisable2FA.value = false,
        })
    }
}

function disable2FA() {
    twoFactorForm.delete(route('settings.two-factor.disable'), {
        onSuccess: () => { showDisable2FA.value = false },
    })
}

// ── Eliminar cuenta ───────────────────────────────────────────────────
const showDeleteModal  = ref(false)
const deleteForm       = useForm({ password: '' })

function deleteAccount() {
    deleteForm.delete(route('settings.account.destroy'), {
        onSuccess: () => { showDeleteModal.value = false },
    })
}
</script>

<template>
    <AppLayout>
        <div class="settings">

            <!-- Page title -->
            <div class="settings-header">
                <h1 class="settings-header__title">Perfil</h1>
                <p class="settings-header__sub">Gestiona tu información personal y preferencias de cuenta.</p>
            </div>

            <!-- ── Avatar ─────────────────────────────────────────────── -->
            <section class="card">
                <div class="card__head">
                    <h2 class="card__title">Foto de perfil</h2>
                    <p class="card__desc">Tu foto es visible para otros usuarios del sistema.</p>
                </div>

                <div class="avatar-row">
                    <!-- Preview -->
                    <div class="avatar-lg" @click="pickAvatar" role="button" tabindex="0"
                         aria-label="Cambiar foto de perfil" @keydown.enter="pickAvatar">
                        <img v-if="avatarPreview" :src="avatarPreview"
                             :alt="user.name" width="80" height="80" loading="lazy" />
                        <span v-else class="avatar-lg__initials">{{ initials }}</span>
                        <div class="avatar-lg__overlay" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </div>
                    </div>

                    <input ref="avatarInput" type="file" accept="image/*"
                           class="sr-only" @change="onAvatarChange" aria-label="Seleccionar imagen" />

                    <div class="avatar-actions">
                        <AppButton variant="secondary" size="sm" @click="pickAvatar">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                            </svg>
                            Subir imagen
                        </AppButton>

                        <AppButton v-if="avatarForm.avatar" variant="primary" size="sm"
                                   :loading="avatarForm.processing" @click="uploadAvatar">
                            Guardar foto
                        </AppButton>

                        <AppButton v-if="avatarPreview && !avatarForm.avatar"
                                   variant="ghost" size="sm" :loading="removeAvatarForm.processing"
                                   @click="removeAvatar">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                            </svg>
                            Eliminar
                        </AppButton>
                    </div>
                </div>

                <Transition name="err">
                    <p v-if="avatarForm.errors.avatar" class="form-error" role="alert">
                        {{ avatarForm.errors.avatar }}
                    </p>
                </Transition>
            </section>

            <!-- ── Datos personales ───────────────────────────────────── -->
            <section class="card">
                <div class="card__head">
                    <h2 class="card__title">Datos personales</h2>
                    <p class="card__desc">Actualiza tu nombre y dirección de correo electrónico.</p>
                </div>

                <form @submit.prevent="saveProfile" class="form" novalidate>
                    <AppInput id="name" v-model="profileForm.name" label="Nombre completo"
                              placeholder="Tu nombre" autocomplete="name"
                              :error="profileForm.errors.name">
                        <template #icon>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                            </svg>
                        </template>
                    </AppInput>

                    <AppInput id="email" v-model="profileForm.email" label="Correo electrónico"
                              type="email" placeholder="tu@correo.com" autocomplete="email"
                              :error="profileForm.errors.email">
                        <template #icon>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </template>
                    </AppInput>

                    <div class="form__footer">
                        <Transition name="fade">
                            <p v-if="profileForm.recentlySuccessful" class="form__saved" role="status">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Guardado
                            </p>
                        </Transition>
                        <AppButton type="submit" variant="primary" size="md"
                                   :loading="profileForm.processing">
                            Guardar cambios
                        </AppButton>
                    </div>
                </form>
            </section>

            <!-- ── Verificación en dos pasos ─────────────────────────── -->
            <section class="card">
                <div class="card__head">
                    <h2 class="card__title">Verificación en dos pasos</h2>
                    <p class="card__desc">Agrega una capa extra de seguridad enviando un código a tu correo al iniciar sesión.</p>
                </div>

                <div class="two-factor-row">
                    <div class="two-factor-info">
                        <AppBadge :variant="user.two_factor_enabled ? 'success' : 'default'">
                            {{ user.two_factor_enabled ? '✓ Activado' : 'Desactivado' }}
                        </AppBadge>
                        <p class="two-factor-desc">
                            {{ user.two_factor_enabled
                            ? 'Tu cuenta está protegida con verificación en dos pasos.'
                            : 'Activa la verificación para mayor seguridad en tu cuenta.' }}
                        </p>
                    </div>
                    <AppButton
                        :variant="user.two_factor_enabled ? 'secondary' : 'primary'"
                        size="sm"
                        :loading="twoFactorForm.processing"
                        @click="toggle2FA"
                    >
                        {{ user.two_factor_enabled ? 'Desactivar' : 'Activar 2FA' }}
                    </AppButton>
                </div>
            </section>

            <!-- ── Zona de peligro ────────────────────────────────────── -->
            <section class="card card--danger">
                <div class="card__head">
                    <h2 class="card__title card__title--danger">Zona de peligro</h2>
                    <p class="card__desc">Una vez eliminada tu cuenta, todos los datos serán borrados permanentemente.</p>
                </div>
                <AppButton variant="danger" size="sm" @click="showDeleteModal = true">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                    </svg>
                    Eliminar mi cuenta
                </AppButton>
            </section>

        </div>

        <!-- ── Modal desactivar 2FA ───────────────────────────────────── -->
        <AppModal :show="showDisable2FA" title="Desactivar verificación en dos pasos"
                  size="sm" @close="showDisable2FA = false">
            <p class="modal-text">¿Estás seguro? Tu cuenta quedará menos protegida sin la verificación en dos pasos.</p>
            <template #footer>
                <AppButton variant="ghost" size="sm" @click="showDisable2FA = false">Cancelar</AppButton>
                <AppButton variant="danger" size="sm" :loading="twoFactorForm.processing" @click="disable2FA">
                    Sí, desactivar
                </AppButton>
            </template>
        </AppModal>

        <!-- ── Modal eliminar cuenta ──────────────────────────────────── -->
        <AppModal :show="showDeleteModal" title="Eliminar cuenta" size="sm"
                  @close="showDeleteModal = false">
            <div class="modal-danger-body">
                <div class="modal-danger-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <p class="modal-text">Esta acción es <strong>irreversible</strong>. Todos tus datos serán eliminados permanentemente. Confirma tu contraseña para continuar.</p>
                <AppInput id="delete-password" v-model="deleteForm.password"
                          label="Contraseña actual" type="password" placeholder="••••••••"
                          autocomplete="current-password" :error="deleteForm.errors.password">
                    <template #icon>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </template>
                </AppInput>
            </div>
            <template #footer>
                <AppButton variant="ghost" size="sm" @click="showDeleteModal = false">Cancelar</AppButton>
                <AppButton variant="danger" size="sm" :loading="deleteForm.processing" @click="deleteAccount">
                    Eliminar definitivamente
                </AppButton>
            </template>
        </AppModal>

    </AppLayout>
</template>

<style scoped>
.settings        { display: flex; flex-direction: column; gap: var(--space-6); max-width: 720px; margin: auto }

/* Header */
.settings-header      { display: flex; flex-direction: column; gap: var(--space-1); }
.settings-header__title { font-size: var(--text-xl); font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
.settings-header__sub   { font-size: var(--text-sm); color: var(--color-text-muted); }

/* Card */
.card {
    background:    var(--color-surface);
    border:        1px solid var(--color-border);
    border-radius: var(--radius-xl);
    padding:       var(--space-6);
    display:       flex;
    flex-direction: column;
    gap:           var(--space-5);
    box-shadow:    var(--shadow-sm);
}
.card--danger { border-color: color-mix(in oklch, var(--color-error) 30%, transparent); }
.card__head   { display: flex; flex-direction: column; gap: var(--space-1); }
.card__title  { font-size: var(--text-base); font-weight: 600; color: var(--color-text); }
.card__title--danger { color: var(--color-error); }
.card__desc   { font-size: var(--text-xs); color: var(--color-text-muted); }

/* Avatar */
.avatar-row     { display: flex; align-items: center; gap: var(--space-5); }
.avatar-lg {
    width:           80px;
    height:          80px;
    border-radius:   var(--radius-full);
    background:      var(--color-primary-highlight);
    color:           var(--color-primary);
    font-size:       var(--text-xl);
    font-weight:     700;
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
    overflow:        hidden;
    cursor:          pointer;
    position:        relative;
    border:          2px solid var(--color-border);
    transition:      border-color var(--transition-interactive);
}
.avatar-lg:hover     { border-color: var(--color-primary); }
.avatar-lg img       { width: 100%; height: 100%; object-fit: cover; }
.avatar-lg__initials { line-height: 1; }
.avatar-lg__overlay  {
    position:        absolute;
    inset:           0;
    background:      oklch(0 0 0 / 0.45);
    display:         flex;
    align-items:     center;
    justify-content: center;
    color:           white;
    opacity:         0;
    transition:      opacity var(--transition-interactive);
}
.avatar-lg:hover .avatar-lg__overlay { opacity: 1; }
.avatar-actions { display: flex; flex-wrap: wrap; gap: var(--space-2); }

/* Form */
.form        { display: flex; flex-direction: column; gap: var(--space-4); }
.form__footer { display: flex; align-items: center; justify-content: space-between; gap: var(--space-3); }
.form__saved  { display: flex; align-items: center; gap: var(--space-1); font-size: var(--text-xs); font-weight: 500; color: var(--color-success); }
.form-error   { display: flex; align-items: center; gap: var(--space-1); font-size: var(--text-xs); color: var(--color-error); }

/* 2FA */
.two-factor-row  { display: flex; align-items: center; justify-content: space-between; gap: var(--space-4); }
.two-factor-info { display: flex; flex-direction: column; gap: var(--space-2); }
.two-factor-desc { font-size: var(--text-xs); color: var(--color-text-muted); max-width: 36ch; }

/* Modal */
.modal-text        { font-size: var(--text-sm); color: var(--color-text-muted); line-height: 1.7; margin-bottom: var(--space-4); }
.modal-danger-body { display: flex; flex-direction: column; gap: var(--space-4); }
.modal-danger-icon {
    width:           44px;
    height:          44px;
    border-radius:   var(--radius-full);
    background:      color-mix(in oklch, var(--color-error) 12%, transparent);
    color:           var(--color-error);
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
}

/* Transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 180ms; }
.fade-enter-from,   .fade-leave-to     { opacity: 0; }
.err-enter-active,  .err-leave-active  { transition: opacity 150ms, transform 150ms; }
.err-enter-from,    .err-leave-to      { opacity: 0; transform: translateY(-4px); }

/* Responsive */
@media (max-width: 480px) {
    .avatar-row      { flex-direction: column; align-items: flex-start; }
    .two-factor-row  { flex-direction: column; align-items: flex-start; }
    .form__footer    { flex-direction: column-reverse; align-items: stretch; }
}
</style>
