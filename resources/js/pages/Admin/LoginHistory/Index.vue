<script setup lang="ts">
import { ref, watch }   from 'vue'
import { router }       from '@inertiajs/vue3'
import AppLayout             from '@/layouts/AppLayout.vue'
import AppButton             from '@/components/ui/AppButton.vue'
import LoginHistoryTable     from '@/components/ui/LoginHistoryTable.vue'

const props = defineProps<{
    histories: any
    filters:   { search?: string; status?: string }
}>()

const search = ref(props.filters.search ?? '')
const status = ref(props.filters.status ?? '')
let debounce: ReturnType<typeof setTimeout>

watch([search, status], () => {
    clearTimeout(debounce)
    debounce = setTimeout(() => {
        router.get(route('admin.login-history.index'),
            { search: search.value, status: status.value },
            { preserveState: true, replace: true }
        )
    }, 350)
})
</script>

<template>
    <AppLayout>
        <div class="admin-page">
            <div class="page-head">
                <div>
                    <h1 class="page-head__title">Historial de sesiones</h1>
                    <p class="page-head__sub">{{ histories.total }} registros en total.</p>
                </div>
            </div>

            <div class="filters">
                <div class="filters__search">
                    <svg class="filters__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input v-model="search" type="search" placeholder="Buscar por usuario o correo…"
                           class="filters__input" />
                </div>
                <select v-model="status" class="filters__select">
                    <option value="">Todos</option>
                    <option value="1">Exitosos</option>
                    <option value="0">Fallidos</option>
                </select>
                <AppButton v-if="search || status" variant="ghost" size="sm"
                           @click="search = ''; status = ''">
                    Limpiar
                </AppButton>
            </div>

            <LoginHistoryTable :histories="histories" :show-user="true" />
        </div>
    </AppLayout>
</template>

<style scoped>
.admin-page { display: flex; flex-direction: column; gap: var(--space-6); }
.page-head  { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: var(--space-4); }
.page-head__title { font-size: var(--text-xl); font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
.page-head__sub   { font-size: var(--text-xs); color: var(--color-text-muted); margin-top: var(--space-1); }
.filters     { display: flex; align-items: center; gap: var(--space-3); flex-wrap: wrap; }
.filters__search { position: relative; flex: 1; min-width: 200px; }
.filters__icon   { position: absolute; left: var(--space-3); top: 50%; transform: translateY(-50%); color: var(--color-text-faint); pointer-events: none; }
.filters__input  { width: 100%; height: 36px; padding: 0 var(--space-3) 0 calc(var(--space-3) + 14px + var(--space-2)); font-size: var(--text-sm); color: var(--color-text); background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); outline: none; transition: border-color var(--transition-interactive); }
.filters__input:focus { border-color: var(--color-primary); }
.filters__select { height: 36px; padding: 0 var(--space-3); font-size: var(--text-sm); color: var(--color-text); background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); outline: none; cursor: pointer; }
</style>
