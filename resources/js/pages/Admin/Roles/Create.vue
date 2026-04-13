<!-- Create.vue -->
<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout  from '@/layouts/AppLayout.vue'
import AppInput   from '@/components/ui/AppInput.vue'
import AppButton  from '@/components/ui/AppButton.vue'

interface Permission { id: number; name: string }
defineProps<{ permissions: Permission[] }>()

const form = useForm({ name: '', permissions: [] as string[] })

function toggle(name: string) {
    const idx = form.permissions.indexOf(name)
    idx === -1 ? form.permissions.push(name) : form.permissions.splice(idx, 1)
}

function submit() {
    form.post(route('admin.roles.store'))
}
</script>

<template>
    <AppLayout>
        <div class="page">
            <div class="page-head">
                <div>
                    <h1 class="page-head__title">Nuevo rol</h1>
                    <p class="page-head__sub">Define el nombre y los permisos del nuevo rol.</p>
                </div>
                <Link :href="route('admin.roles.index')">
                    <AppButton variant="ghost" size="sm">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Volver
                    </AppButton>
                </Link>
            </div>

            <form @submit.prevent="submit" class="card" novalidate>

                <div class="section">
                    <h2 class="section__title">Nombre del rol</h2>
                    <AppInput id="name" v-model="form.name" label="Nombre"
                              placeholder="ej: editor" :error="form.errors.name">
                        <template #icon>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </template>
                    </AppInput>
                </div>

                <div class="divider" />

                <div class="section">
                    <h2 class="section__title">Permisos</h2>
                    <div class="perms-grid">
                        <label v-for="perm in permissions" :key="perm.id" class="perm-check">
                            <input type="checkbox"
                                   :value="perm.name"
                                   :checked="form.permissions.includes(perm.name)"
                                   @change="toggle(perm.name)"
                            />
                            <span class="perm-check__box" aria-hidden="true" />
                            <span class="perm-check__label">{{ perm.name }}</span>
                        </label>
                    </div>
                    <p v-if="form.errors.permissions" class="field-error">{{ form.errors.permissions }}</p>
                </div>

                <div class="divider" />

                <div class="form-footer">
                    <Link :href="route('admin.roles.index')">
                        <AppButton variant="ghost" size="md" type="button">Cancelar</AppButton>
                    </Link>
                    <AppButton type="submit" variant="primary" size="md" :loading="form.processing">
                        Crear rol
                    </AppButton>
                </div>

            </form>
        </div>
    </AppLayout>
</template>

<style scoped>
.page        { display: flex; flex-direction: column; gap: var(--space-6); max-width: 640px; }
.page-head   { display: flex; align-items: flex-start; justify-content: space-between; gap: var(--space-4); }
.page-head__title { font-size: var(--text-xl); font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
.page-head__sub   { font-size: var(--text-xs); color: var(--color-text-muted); margin-top: var(--space-1); }
.card    { background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-xl); padding: var(--space-6); display: flex; flex-direction: column; gap: var(--space-5); box-shadow: var(--shadow-sm); }
.section { display: flex; flex-direction: column; gap: var(--space-4); }
.section__title { font-size: var(--text-sm); font-weight: 600; color: var(--color-text); }
.divider { height: 1px; background: var(--color-divider); }
.form-footer { display: flex; justify-content: flex-end; gap: var(--space-3); }
.field-error { font-size: var(--text-xs); color: var(--color-error); }

.perms-grid {
    display:               grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap:                   var(--space-2);
}

.perm-check { display: flex; align-items: center; gap: var(--space-2); cursor: pointer; padding: var(--space-2) var(--space-3); border-radius: var(--radius-md); transition: background var(--transition-interactive); }
.perm-check:hover { background: var(--color-surface-offset); }
.perm-check input { position: absolute; opacity: 0; width: 0; height: 0; }
.perm-check__box {
    width:         16px;
    height:        16px;
    border:        2px solid var(--color-border);
    border-radius: var(--radius-sm);
    flex-shrink:   0;
    background:    var(--color-surface);
    transition:    border-color var(--transition-interactive), background var(--transition-interactive);
    position:      relative;
}
.perm-check input:checked ~ .perm-check__box {
    background:   var(--color-primary);
    border-color: var(--color-primary);
}
.perm-check input:checked ~ .perm-check__box::after {
    content:    '';
    position:   absolute;
    left:       3px; top: 1px;
    width:      6px; height: 9px;
    border:     2px solid white;
    border-top: none; border-left: none;
    transform:  rotate(45deg);
}
.perm-check__label { font-size: var(--text-xs); font-family: 'Courier New', monospace; color: var(--color-text-muted); }
.perm-check input:checked ~ .perm-check__label { color: var(--color-text); font-weight: 500; }
</style>
