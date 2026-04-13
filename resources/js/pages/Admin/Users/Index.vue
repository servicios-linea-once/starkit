<script setup lang="ts">
import { ref, watch }          from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import AppLayout  from '@/layouts/AppLayout.vue'
import AppBadge   from '@/components/ui/AppBadge.vue'
import AppButton  from '@/components/ui/AppButton.vue'
import AppModal   from '@/components/ui/AppModal.vue'
import { useInitials } from '@/composables/useInitials'
import type { PaginatedData, User, Role } from '@/types'
import type { PageProps } from '@inertiajs/core'

const props = defineProps<{
    users:   PaginatedData<User & { roles: Role[] }>
    roles:   Role[]
    filters: { search?: string; role?: string; status?: string }
}>()

const page          = usePage<PageProps>()
const { getInitials } = useInitials()

// ── Filtros ───────────────────────────────────────────────────────────
const search = ref(props.filters.search ?? '')
const role   = ref(props.filters.role   ?? '')
const status = ref(props.filters.status ?? '')

let debounceTimer: ReturnType<typeof setTimeout>

watch([search, role, status], () => {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => applyFilters(), 350)
})

function applyFilters() {
    router.get(
        route('admin.users.index'),
        { search: search.value, role: role.value, status: status.value },
        { preserveState: true, replace: true }
    )
}

function clearFilters() {
    search.value = ''
    role.value   = ''
    status.value = ''
}

// ── Eliminar ──────────────────────────────────────────────────────────
const showDeleteModal = ref(false)
const userToDelete    = ref<User | null>(null)
const deleteLoading   = ref(false)

function confirmDelete(user: User) {
    userToDelete.value  = user
    showDeleteModal.value = true
}

function deleteUser() {
    if (!userToDelete.value) return
    deleteLoading.value = true
    router.delete(route('admin.users.destroy', userToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false
            userToDelete.value    = null
        },
        onFinish: () => { deleteLoading.value = false },
    })
}

// ── Toggle estado ─────────────────────────────────────────────────────
function toggleStatus(user: User) {
    router.patch(route('admin.users.toggle-status', user.id), {}, {
        preserveScroll: true,
    })
}

const can = page.props.auth.can
</script>

<template>
    <AppLayout>
        <div class="admin-page">

            <!-- ── Cabecera ──────────────────────────────────────────── -->
            <div class="page-head">
                <div>
                    <h1 class="page-head__title">Usuarios</h1>
                    <p class="page-head__sub">{{ users.total }} usuarios registrados en el sistema.</p>
                </div>
                <Link v-if="can['users.create']" :href="route('admin.users.create')">
                    <AppButton variant="primary" size="md">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Nuevo usuario
                    </AppButton>
                </Link>
            </div>

            <!-- ── Filtros ───────────────────────────────────────────── -->
            <div class="filters">
                <div class="filters__search">
                    <svg class="filters__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input v-model="search" type="search" placeholder="Buscar por nombre o correo…"
                           class="filters__input" aria-label="Buscar usuarios" />
                </div>

                <select v-model="role" class="filters__select" aria-label="Filtrar por rol">
                    <option value="">Todos los roles</option>
                    <option v-for="r in roles" :key="r.id" :value="r.name">
                        {{ r.name }}
                    </option>
                </select>

                <select v-model="status" class="filters__select" aria-label="Filtrar por estado">
                    <option value="">Todos los estados</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>

                <AppButton v-if="search || role || status"
                           variant="ghost" size="sm" @click="clearFilters">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Limpiar
                </AppButton>
            </div>

            <!-- ── Tabla ─────────────────────────────────────────────── -->
            <div class="table-wrap">
                <table class="table" aria-label="Lista de usuarios">
                    <thead class="table__head">
                    <tr>
                        <th class="table__th">Usuario</th>
                        <th class="table__th">Rol</th>
                        <th class="table__th">Estado</th>
                        <th class="table__th">Registro</th>
                        <th class="table__th table__th--right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Empty state -->
                    <tr v-if="users.data.length === 0">
                        <td colspan="5" class="table__empty">
                            <div class="empty-state">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                                </svg>
                                <p>No se encontraron usuarios</p>
                                <AppButton variant="ghost" size="sm" @click="clearFilters">
                                    Limpiar filtros
                                </AppButton>
                            </div>
                        </td>
                    </tr>

                    <!-- Filas -->
                    <tr v-for="user in users.data" :key="user.id" class="table__row">
                        <!-- Usuario -->
                        <td class="table__td">
                            <div class="user-cell">
                                <div class="user-avatar">
                                    <img v-if="user.avatar_url" :src="user.avatar_url"
                                         :alt="user.name" width="34" height="34" loading="lazy" />
                                    <span v-else>{{ getInitials(user.name) }}</span>
                                </div>
                                <div class="user-info">
                                    <p class="user-name">{{ user.name }}</p>
                                    <p class="user-email">{{ user.email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Rol -->
                        <td class="table__td">
                            <AppBadge variant="info">
                                {{ user.roles?.[0]?.name ?? '—' }}
                            </AppBadge>
                        </td>

                        <!-- Estado -->
                        <td class="table__td">
                            <AppBadge :variant="user.is_active ? 'success' : 'error'">
                                {{ user.is_active ? 'Activo' : 'Inactivo' }}
                            </AppBadge>
                        </td>

                        <!-- Registro -->
                        <td class="table__td table__td--muted">
                            {{ new Date(user.created_at).toLocaleDateString('es-PE', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        }) }}
                        </td>

                        <!-- Acciones -->
                        <td class="table__td table__td--right">
                            <div class="actions">
                                <!-- Toggle estado -->
                                <button v-if="can['users.edit']"
                                        class="action-btn"
                                        :title="user.is_active ? 'Desactivar' : 'Activar'"
                                        @click="toggleStatus(user)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path v-if="user.is_active" d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"/>
                                        <path v-else d="M12 2v10m6.36-5.36a9 9 0 1 1-12.73 0"/>
                                    </svg>
                                </button>

                                <!-- Editar -->
                                <Link v-if="can['users.edit']"
                                      :href="route('admin.users.edit', user.id)"
                                      class="action-btn" title="Editar usuario">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </Link>

                                <!-- Eliminar -->
                                <button v-if="can['users.delete']"
                                        class="action-btn action-btn--danger"
                                        title="Eliminar usuario"
                                        @click="confirmDelete(user)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6M9 6V4h6v2"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- ── Paginación ────────────────────────────────────────── -->
            <div v-if="users.last_page > 1" class="pagination">
                <p class="pagination__info">
                    Mostrando {{ users.from }}–{{ users.to }} de {{ users.total }}
                </p>
                <div class="pagination__links">
                    <Link
                        v-for="link in users.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        :class="['page-btn', { 'page-btn--active': link.active, 'page-btn--disabled': !link.url }]"
                        v-html="link.label"
                        :aria-current="link.active ? 'page' : undefined"
                    />
                </div>
            </div>

        </div>

        <!-- ── Modal eliminar ───────────────────────────────────────── -->
        <AppModal :show="showDeleteModal" title="Eliminar usuario"
                  size="sm" @close="showDeleteModal = false">
            <p class="modal-text">
                ¿Estás seguro de eliminar a
                <strong>{{ userToDelete?.name }}</strong>?
                Esta acción no se puede deshacer.
            </p>
            <template #footer>
                <AppButton variant="ghost" size="sm" @click="showDeleteModal = false">
                    Cancelar
                </AppButton>
                <AppButton variant="danger" size="sm"
                           :loading="deleteLoading" @click="deleteUser">
                    Eliminar
                </AppButton>
            </template>
        </AppModal>

    </AppLayout>
</template>

<style scoped>
.admin-page { display: flex; flex-direction: column; gap: var(--space-6); }

/* Head */
.page-head       { display: flex; align-items: flex-start; justify-content: space-between; gap: var(--space-4); flex-wrap: wrap; }
.page-head__title { font-size: var(--text-xl); font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
.page-head__sub   { font-size: var(--text-xs); color: var(--color-text-muted); margin-top: var(--space-1); }

/* Filtros */
.filters {
    display:     flex;
    align-items: center;
    gap:         var(--space-3);
    flex-wrap:   wrap;
}
.filters__search {
    position:    relative;
    flex:        1;
    min-width:   200px;
}
.filters__icon {
    position:  absolute;
    left:      var(--space-3);
    top:       50%;
    transform: translateY(-50%);
    color:     var(--color-text-faint);
    pointer-events: none;
}
.filters__input {
    width:         100%;
    height:        36px;
    padding:       0 var(--space-3) 0 calc(var(--space-3) + 14px + var(--space-2));
    font-size:     var(--text-sm);
    color:         var(--color-text);
    background:    var(--color-surface);
    border:        1px solid var(--color-border);
    border-radius: var(--radius-md);
    outline:       none;
    transition:    border-color var(--transition-interactive), box-shadow var(--transition-interactive);
}
.filters__input:focus {
    border-color: var(--color-primary);
    box-shadow:   0 0 0 3px color-mix(in oklch, var(--color-primary) 15%, transparent);
}
.filters__select {
    height:        36px;
    padding:       0 var(--space-3);
    font-size:     var(--text-sm);
    color:         var(--color-text);
    background:    var(--color-surface);
    border:        1px solid var(--color-border);
    border-radius: var(--radius-md);
    outline:       none;
    cursor:        pointer;
    transition:    border-color var(--transition-interactive);
}
.filters__select:focus { border-color: var(--color-primary); }

/* Tabla */
.table-wrap {
    background:    var(--color-surface);
    border:        1px solid var(--color-border);
    border-radius: var(--radius-xl);
    overflow:      hidden;
    box-shadow:    var(--shadow-sm);
}
.table          { width: 100%; border-collapse: collapse; }
.table__head    { background: var(--color-surface-offset); }
.table__th      { padding: var(--space-3) var(--space-4); font-size: var(--text-xs); font-weight: 600; color: var(--color-text-muted); text-align: left; white-space: nowrap; border-bottom: 1px solid var(--color-divider); }
.table__th--right { text-align: right; }
.table__row     { transition: background var(--transition-interactive); }
.table__row:not(:last-child) { border-bottom: 1px solid var(--color-divider); }
.table__row:hover { background: var(--color-surface-offset); }
.table__td      { padding: var(--space-3) var(--space-4); font-size: var(--text-sm); color: var(--color-text); vertical-align: middle; }
.table__td--muted { color: var(--color-text-muted); font-size: var(--text-xs); }
.table__td--right { text-align: right; }
.table__empty   { padding: var(--space-12) var(--space-4); text-align: center; }

/* User cell */
.user-cell  { display: flex; align-items: center; gap: var(--space-3); }
.user-avatar {
    width:           34px;
    height:          34px;
    border-radius:   var(--radius-full);
    background:      var(--color-primary-highlight);
    color:           var(--color-primary);
    font-size:       var(--text-xs);
    font-weight:     700;
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
    overflow:        hidden;
}
.user-avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-name       { font-size: var(--text-sm); font-weight: 500; color: var(--color-text); }
.user-email      { font-size: var(--text-xs); color: var(--color-text-muted); }

/* Acciones */
.actions    { display: flex; align-items: center; justify-content: flex-end; gap: var(--space-1); }
.action-btn {
    width:         30px;
    height:        30px;
    border-radius: var(--radius-md);
    display:       flex;
    align-items:   center;
    justify-content: center;
    color:         var(--color-text-muted);
    text-decoration: none;
    cursor:        pointer;
    transition:    background var(--transition-interactive), color var(--transition-interactive);
}
.action-btn:hover              { background: var(--color-surface-dynamic); color: var(--color-text); }
.action-btn--danger:hover      { background: color-mix(in oklch, var(--color-error) 12%, transparent); color: var(--color-error); }

/* Empty state */
.empty-state { display: flex; flex-direction: column; align-items: center; gap: var(--space-3); color: var(--color-text-faint); }
.empty-state p { font-size: var(--text-sm); color: var(--color-text-muted); }

/* Paginación */
.pagination       { display: flex; align-items: center; justify-content: space-between; gap: var(--space-4); flex-wrap: wrap; }
.pagination__info { font-size: var(--text-xs); color: var(--color-text-muted); }
.pagination__links { display: flex; gap: var(--space-1); flex-wrap: wrap; }
.page-btn {
    min-width:     32px;
    height:        32px;
    padding:       0 var(--space-2);
    border-radius: var(--radius-md);
    font-size:     var(--text-xs);
    font-weight:   500;
    color:         var(--color-text-muted);
    text-decoration: none;
    display:       flex;
    align-items:   center;
    justify-content: center;
    border:        1px solid transparent;
    transition:    background var(--transition-interactive), color var(--transition-interactive);
}
.page-btn:hover:not(.page-btn--disabled):not(.page-btn--active) {
    background: var(--color-surface-offset);
    color:      var(--color-text);
}
.page-btn--active   { background: var(--color-primary); color: white; border-color: var(--color-primary); }
.page-btn--disabled { opacity: 0.4; pointer-events: none; }

/* Modal */
.modal-text { font-size: var(--text-sm); color: var(--color-text-muted); line-height: 1.7; }

/* Responsive */
@media (max-width: 768px) {
    .table__th:nth-child(3),
    .table__td:nth-child(3),
    .table__th:nth-child(4),
    .table__td:nth-child(4) { display: none; }
}
</style>
