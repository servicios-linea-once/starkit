<!-- Componente compartido: LoginHistoryTable.vue -->
<script setup lang="ts">
interface History {
    id:           number
    ip_address:   string
    user_agent:   string
    browser:      string
    os:           string
    successful:   boolean
    logged_in_at: string
    user?: { name: string; email: string; avatar_url: string | null }
}

defineProps<{
    histories: { data: History[]; total: number; last_page: number; from: number; to: number }
    showUser?: boolean
}>()
</script>

<template>
    <div class="table-wrap">
        <table class="table" aria-label="Historial de sesiones">
            <thead class="table__head">
            <tr>
                <th class="table__th" v-if="showUser">Usuario</th>
                <th class="table__th">IP</th>
                <th class="table__th">Navegador / OS</th>
                <th class="table__th">Estado</th>
                <th class="table__th">Fecha</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="histories.data.length === 0">
                <td :colspan="showUser ? 5 : 4" class="table__empty">
                    <div class="empty-state">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <p>Sin historial de sesiones</p>
                    </div>
                </td>
            </tr>

            <tr v-for="h in histories.data" :key="h.id" class="table__row">
                <td v-if="showUser" class="table__td">
                    <div class="user-mini">
                        <p class="user-mini__name">{{ h.user?.name }}</p>
                        <p class="user-mini__email">{{ h.user?.email }}</p>
                    </div>
                </td>
                <td class="table__td">
                    <code class="ip-code">{{ h.ip_address }}</code>
                </td>
                <td class="table__td">
                    <p class="browser-info">{{ h.browser }}</p>
                    <p class="os-info">{{ h.os }}</p>
                </td>
                <td class="table__td">
                        <span :class="['status-dot', h.successful ? 'status-dot--success' : 'status-dot--error']">
                            {{ h.successful ? 'Exitoso' : 'Fallido' }}
                        </span>
                </td>
                <td class="table__td table__td--muted">
                    {{ new Date(h.logged_in_at).toLocaleString('es-PE', {
                    day: '2-digit', month: 'short', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                }) }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.table-wrap  { background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-sm); }
.table       { width: 100%; border-collapse: collapse; }
.table__head { background: var(--color-surface-offset); }
.table__th   { padding: var(--space-3) var(--space-4); font-size: var(--text-xs); font-weight: 600; color: var(--color-text-muted); text-align: left; white-space: nowrap; border-bottom: 1px solid var(--color-divider); }
.table__row  { transition: background var(--transition-interactive); }
.table__row:not(:last-child) { border-bottom: 1px solid var(--color-divider); }
.table__row:hover { background: var(--color-surface-offset); }
.table__td   { padding: var(--space-3) var(--space-4); font-size: var(--text-sm); color: var(--color-text); vertical-align: middle; }
.table__td--muted { color: var(--color-text-muted); font-size: var(--text-xs); }
.table__empty { padding: var(--space-12) var(--space-4); text-align: center; }

.empty-state { display: flex; flex-direction: column; align-items: center; gap: var(--space-3); color: var(--color-text-faint); }
.empty-state p { font-size: var(--text-sm); color: var(--color-text-muted); }

.user-mini__name  { font-size: var(--text-sm); font-weight: 500; color: var(--color-text); }
.user-mini__email { font-size: var(--text-xs); color: var(--color-text-muted); }

.ip-code     { font-family: 'Courier New', monospace; font-size: var(--text-xs); background: var(--color-surface-offset); padding: 2px var(--space-2); border-radius: var(--radius-sm); color: var(--color-text-muted); }
.browser-info { font-size: var(--text-sm); color: var(--color-text); }
.os-info      { font-size: var(--text-xs); color: var(--color-text-muted); }

.status-dot  { display: inline-flex; align-items: center; gap: var(--space-1); font-size: var(--text-xs); font-weight: 500; padding: 2px var(--space-2); border-radius: var(--radius-full); }
.status-dot::before { content: ''; width: 6px; height: 6px; border-radius: var(--radius-full); flex-shrink: 0; }
.status-dot--success { background: color-mix(in oklch, var(--color-success) 12%, transparent); color: var(--color-success); }
.status-dot--success::before { background: var(--color-success); }
.status-dot--error   { background: color-mix(in oklch, var(--color-error) 12%, transparent); color: var(--color-error); }
.status-dot--error::before   { background: var(--color-error); }
</style>
