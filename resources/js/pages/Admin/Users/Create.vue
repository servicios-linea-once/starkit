<script setup lang="ts">
import { ref }           from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout  from '@/layouts/AppLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'
import type { Role }     from '@/types'

defineProps<{ roles: Role[] }>()

const avatarInput   = ref<HTMLInputElement | null>(null)
const avatarPreview = ref<string | null>(null)

const form = useForm<{
    name:                  string
    email:                 string
    password:              string
    password_confirmation: string
    role:                  string
    is_active:             boolean
    avatar:                File | null
}>({
    name:                  '',
    email:                 '',
    password:              '',
    password_confirmation: '',
    role:                  '',
    is_active:             true,
    avatar:                null,
})

function pickAvatar() { avatarInput.value?.click() }

function onAvatarChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0]
    if (!file) return
    form.avatar     = file
    avatarPreview.value = URL.createObjectURL(file)
}

function submit() {
    form.post(route('admin.users.store'), {
        forceFormData: true,
        onSuccess: () => form.reset(),
    })
}
</script>

<template>
    <AppLayout>
        <div class="page">

            <!-- Cabecera -->
            <div class="page-head">
                <div>
                    <h1 class="page-head__title">Nuevo usuario</h1>
                    <p class="page-head__sub">Completa los datos para crear un nuevo usuario.</p>
                </div>
                <Link :href="route('admin.users.index')">
                    <AppButton variant="ghost" size="sm">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Volver
                    </AppButton>
                </Link>
            </div>

            <form @submit.prevent="submit" class="card" novalidate>

                <!-- Avatar -->
                <div class="section">
                    <h2 class="section__title">Foto de perfil</h2>
                    <div class="avatar-row">
                        <div class="avatar-lg" @click="pickAvatar" role="button" tabindex="0"
                             @keydown.enter="pickAvatar" aria-label="Seleccionar foto">
                            <img v-if="avatarPreview" :src="avatarPreview" alt="Vista previa"
                                 width="64" height="64" loading="lazy" />
                            <svg v-else width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </div>
                        <input ref="avatarInput" type="file" accept="image/*"
                               class="sr-only" @change="onAvatarChange" />
                        <div>
                            <AppButton variant="secondary" size="sm" type="button" @click="pickAvatar">
                                Seleccionar imagen
                            </AppButton>
                            <p class="hint">JPG, PNG o WebP. Máx. 2MB.</p>
                        </div>
                    </div>
                    <p v-if="form.errors.avatar" class="field-error">{{ form.errors.avatar }}</p>
                </div>

                <div class="divider" />

                <!-- Datos personales -->
                <div class="section">
                    <h2 class="section__title">Datos personales</h2>
                    <div class="grid-2">
                        <AppInput id="name" v-model="form.name" label="Nombre completo"
                                  placeholder="Juan Pérez" autocomplete="name"
                                  :error="form.errors.name">
                            <template #icon>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                                </svg>
                            </template>
                        </AppInput>

                        <AppInput id="email" v-model="form.email" label="Correo electrónico"
                                  type="email" placeholder="correo@ejemplo.com" autocomplete="email"
                                  :error="form.errors.email">
                            <template #icon>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                            </template>
                        </AppInput>

                        <AppInput id="password" v-model="form.password" label="Contraseña"
                                  type="password" placeholder="••••••••" autocomplete="new-password"
                                  :error="form.errors.password">
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
                    </div>
                </div>

                <div class="divider" />

                <!-- Rol y estado -->
                <div class="section">
                    <h2 class="section__title">Rol y estado</h2>
                    <div class="grid-2">

                        <div class="field">
                            <label for="role" class="field__label">Rol</label>
                            <select id="role" v-model="form.role" class="field__select"
                                    :class="{ 'field__select--error': form.errors.role }">
                                <option value="" disabled>Selecciona un rol</option>
                                <option v-for="r in roles" :key="r.id" :value="r.name">
                                    {{ r.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.role" class="field-error">{{ form.errors.role }}</p>
                        </div>

                        <div class="field">
                            <label class="field__label">Estado</label>
                            <label class="toggle">
                                <input type="checkbox" v-model="form.is_active" role="switch"
                                       :aria-checked="form.is_active" />
                                <span class="toggle__track">
                                    <span class="toggle__thumb" />
                                </span>
                                <span class="toggle__label">
                                    {{ form.is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </label>
                        </div>

                    </div>
                </div>

                <div class="divider" />

                <!-- Footer -->
                <div class="form-footer">
                    <Link :href="route('admin.users.index')">
                        <AppButton variant="ghost" size="md" type="button">Cancelar</AppButton>
                    </Link>
                    <AppButton type="submit" variant="primary" size="md" :loading="form.processing">
                        Crear usuario
                    </AppButton>
                </div>

            </form>
        </div>
    </AppLayout>
</template>

<style scoped>
.page        { display: flex; flex-direction: column; gap: var(--space-6); max-width: 720px; margin: auto }
.page-head   { display: flex; align-items: flex-start; justify-content: space-between; gap: var(--space-4); }
.page-head__title { font-size: var(--text-xl); font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
.page-head__sub   { font-size: var(--text-xs); color: var(--color-text-muted); margin-top: var(--space-1); }

.card    { background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-xl); padding: var(--space-6); display: flex; flex-direction: column; gap: var(--space-5); box-shadow: var(--shadow-sm); }
.section { display: flex; flex-direction: column; gap: var(--space-4); }
.section__title { font-size: var(--text-sm); font-weight: 600; color: var(--color-text); }
.divider { height: 1px; background: var(--color-divider); }
.grid-2  { display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); }

.avatar-row  { display: flex; align-items: center; gap: var(--space-4); }
.avatar-lg   { width: 64px; height: 64px; border-radius: var(--radius-full); background: var(--color-surface-offset); border: 2px dashed var(--color-border); display: flex; align-items: center; justify-content: center; color: var(--color-text-faint); cursor: pointer; overflow: hidden; flex-shrink: 0; transition: border-color var(--transition-interactive); }
.avatar-lg:hover { border-color: var(--color-primary); }
.avatar-lg img { width: 100%; height: 100%; object-fit: cover; }
.hint        { font-size: var(--text-xs); color: var(--color-text-faint); margin-top: var(--space-1); }

.field         { display: flex; flex-direction: column; gap: var(--space-1); }
.field__label  { font-size: var(--text-xs); font-weight: 500; color: var(--color-text); }
.field__select { height: 38px; padding: 0 var(--space-3); font-size: var(--text-sm); color: var(--color-text); background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); outline: none; cursor: pointer; transition: border-color var(--transition-interactive), box-shadow var(--transition-interactive); }
.field__select:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px color-mix(in oklch, var(--color-primary) 15%, transparent); }
.field__select--error { border-color: var(--color-error); }
.field-error { font-size: var(--text-xs); color: var(--color-error); }

.toggle       { display: flex; align-items: center; gap: var(--space-3); cursor: pointer; }
.toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
.toggle__track { width: 36px; height: 20px; background: var(--color-surface-dynamic); border-radius: var(--radius-full); position: relative; transition: background var(--transition-interactive); flex-shrink: 0; }
.toggle input:checked ~ .toggle__track { background: var(--color-primary); }
.toggle__thumb { position: absolute; width: 14px; height: 14px; border-radius: var(--radius-full); background: white; top: 3px; left: 3px; transition: transform var(--transition-interactive); box-shadow: 0 1px 3px oklch(0 0 0 / 0.2); }
.toggle input:checked ~ .toggle__track .toggle__thumb { transform: translateX(16px); }
.toggle__label { font-size: var(--text-sm); color: var(--color-text-muted); }

.form-footer { display: flex; justify-content: flex-end; gap: var(--space-3); }

@media (max-width: 560px) {
    .grid-2 { grid-template-columns: 1fr; }
}
</style>
