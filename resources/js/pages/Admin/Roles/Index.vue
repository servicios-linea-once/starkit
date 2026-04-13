<script setup lang="ts">
import { ref }           from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import AppLayout  from '@/layouts/AppLayout.vue'
import AppBadge   from '@/components/ui/AppBadge.vue'
import AppButton  from '@/components/ui/AppButton.vue'
import AppModal   from '@/components/ui/AppModal.vue'
import type { PageProps } from '@inertiajs/core'
const page = usePage<PageProps>()

interface Permission { id: number; name: string }
interface Role {
    id:              number
    name:            string
    users_count:     number
    permissions:     Permission[]
}

defineProps<{ roles: Role[] }>()

const can  = page.props.auth.can

const showDeleteModal = ref(false)
const roleToDelete    = ref<Role | null>(null)
const deleteLoading   = ref(false)

const systemRoles = ['admin', 'manager', 'user']

function confirmDelete(role: Role) {
    roleToDelete.value  = role
    showDeleteModal.value = true
}

function deleteRole() {
    if (!roleToDelete.value) return
    deleteLoading.value = true
    router.delete(route('admin.roles.destroy', roleToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false
            roleToDelete.value    = null
        },
        onFinish: () => { deleteLoading.value = false },
    })
}

// Agrupar permisos por módulo (ej: "users.view" → "users")
function groupLabel(name: string): string {
    return name.split('.')[0]
}
</script>

<template>
    <AppLayout>
        <div class="admin-page">

            <!-- Cabecera -->
            <div class="page-head">
                <div>
                    <h1 class="page-head__title">Roles y permisos</h1>
                    <p class="page-head__sub">{{ roles.length }} roles configurados en el sistema.</p>
                </div>
                <Link v-if="can['roles.create']" :href="route('admin.roles.create')">
                    <AppButton variant="primary" size="md">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Nuevo rol
                    </AppButton>
                </Link>
            </div>

            <!-- Grid de roles -->
            <div class="roles-grid">
                <div v-for="role in roles" :key="role.id" class="role-card">

                    <div class="role-card__head">
                        <div class="role-card__info">
                            <div class="role-card__icon" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="role-card__name">{{ role.name }}</p>
                                <p class="role-card__users">
                                    {{ role.users_count }}
                                    {{ role.users_count === 1 ? 'usuario' : 'usuarios' }}
                                </p>
                            </div>
                        </div>

                        <div class="role-card__actions" v-if="!systemRoles.includes(role.name) || can['roles.edit']">
                            <Link v-if="can['roles.edit']"
                                  :href="route('admin.roles.edit', role.id)"
                                  class="action-btn" title="Editar rol">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </Link>
                            <button v-if="can['roles.delete'] && !systemRoles.includes(role.name)"
                                    class="action-btn action-btn--danger"
                                    title="Eliminar rol"
                                    @click="confirmDelete(role)">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Permisos -->
                    <div class="role-card__perms">
                        <span
                            v-for="perm in role.permissions"
                            :key="perm.id"
                            class="perm-chip"
                            :data-group="groupLabel(perm.name)"
                        >
                            {{ perm.name }}
                        </span>
                        <span v-if="role.permissions.length === 0" class="perm-empty">
                            Sin permisos asignados
                        </span>
                    </div>

                    <!-- Badge sistema -->
                    <div v-if="systemRoles.includes(role.name)" class="role-card__badge">
                        <AppBadge variant="default">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            Rol del sistema
                        </AppBadge>
                    </div>

                </div>
            </div>

        </div>

        <!-- Modal eliminar -->
        <AppModal :show="showDeleteModal" title="Eliminar rol" size="sm" @close="showDeleteModal = false">
            <p class="modal-text">
                ¿Eliminar el rol <strong>{{ roleToDelete?.name }}</strong>?
                Los usuarios asignados a este rol perderán sus permisos.
            </p>
            <template #footer>
                <AppButton variant="ghost" size="sm" @click="showDeleteModal = false">Cancelar</AppButton>
                <AppButton variant="danger" size="sm" :loading="deleteLoading" @click="deleteRole">
                    Eliminar
                </AppButton>
            </template>
        </AppModal>

    </AppLayout>
</template>

<style scoped>
.admin-page  { display: flex; flex-direction: column; gap: var(--space-6); }
.page-head   { display: flex; align-items: flex-start; justify-content: space-between; gap: var(--space-4); flex-wrap: wrap; }
.page-head__title { font-size: var(--text-xl); font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
.page-head__sub   { font-size: var(--text-xs); color: var(--color-text-muted); margin-top: var(--space-1); }

.roles-grid {
    display:               grid;
    grid-template-columns: repeat(auto-fill, minmax(min(320px, 100%), 1fr));
    gap:                   var(--space-4);
}

.role-card {
    background:     var(--color-surface);
    border:         1px solid var(--color-border);
    border-radius:  var(--radius-xl);
    padding:        var(--space-5);
    display:        flex;
    flex-direction: column;
    gap:            var(--space-4);
    box-shadow:     var(--shadow-sm);
    transition:     box-shadow var(--transition-interactive);
}
.role-card:hover { box-shadow: var(--shadow-md); }

.role-card__head    { display: flex; align-items: flex-start; justify-content: space-between; gap: var(--space-3); }
.role-card__info    { display: flex; align-items: center; gap: var(--space-3); }
.role-card__icon    { width: 36px; height: 36px; border-radius: var(--radius-md); background: var(--color-primary-highlight); color: var(--color-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.role-card__name    { font-size: var(--text-sm); font-weight: 600; color: var(--color-text); text-transform: capitalize; }
.role-card__users   { font-size: var(--text-xs); color: var(--color-text-muted); }
.role-card__actions { display: flex; gap: var(--space-1); }
.role-card__badge   { display: flex; }

.action-btn { width: 28px; height: 28px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--color-text-muted); text-decoration: none; cursor: pointer; transition: background var(--transition-interactive), color var(--transition-interactive); }
.action-btn:hover              { background: var(--color-surface-dynamic); color: var(--color-text); }
.action-btn--danger:hover      { background: color-mix(in oklch, var(--color-error) 12%, transparent); color: var(--color-error); }

.role-card__perms { display: flex; flex-wrap: wrap; gap: var(--space-1); }
.perm-chip {
    display:       inline-flex;
    align-items:   center;
    padding:       2px var(--space-2);
    font-size:     10px;
    font-weight:   500;
    border-radius: var(--radius-full);
    background:    var(--color-surface-offset);
    color:         var(--color-text-muted);
    white-space:   nowrap;
    font-family:   'Courier New', monospace;
}
.perm-chip[data-group="users"]     { background: color-mix(in oklch, var(--color-blue)    12%, transparent); color: var(--color-blue); }
.perm-chip[data-group="roles"]     { background: color-mix(in oklch, var(--color-warning)  12%, transparent); color: var(--color-warning); }
.perm-chip[data-group="dashboard"] { background: color-mix(in oklch, var(--color-success)  12%, transparent); color: var(--color-success); }
.perm-chip[data-group="settings"]  { background: color-mix(in oklch, var(--color-primary)  12%, transparent); color: var(--color-primary); }
.perm-empty { font-size: var(--text-xs); color: var(--color-text-faint); font-style: italic; }

.modal-text { font-size: var(--text-sm); color: var(--color-text-muted); line-height: 1.7; }
</style>
