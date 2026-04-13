<script setup lang="ts">
import { ref }           from 'vue'
import { useForm }       from '@inertiajs/vue3'
import AppLayout         from '@/layouts/AppLayout.vue'
import LoginHistoryTable from '@/components/ui/LoginHistoryTable.vue'
import AppButton         from '@/components/ui/AppButton.vue'
import AppInput          from '@/components/ui/AppInput.vue'
import AppModal          from '@/components/ui/AppModal.vue'


defineProps<{ histories: any }>()

const showModal = ref(false)
const form = useForm({ password: '' })

function submit() {
    form.delete(route('settings.sessions.destroy'), {
        onSuccess: () => {
            showModal.value = false
            form.reset()
        },
    })
}
</script>

<template>
    <AppLayout>
        <div class="page">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Mis accesos</h1>
                    <p class="page-sub">Historial de inicios de sesión en tu cuenta</p>
                </div>
                <!-- ✅ Botón cerrar otras sesiones -->
                <AppButton variant="danger" @click="showModal = true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Cerrar otras sesiones
                </AppButton>
            </div>
            <LoginHistoryTable :histories="histories" :show-user="false" />
            <!-- Modal de confirmación con contraseña -->
            <AppModal :show="showModal" title="Cerrar otras sesiones" size="sm"
                      @close="showModal = false">
                <div class="modal-body">
                    <p class="modal-desc">
                        Se cerrarán todas las sesiones activas en otros dispositivos.
                        Confirma tu contraseña para continuar.
                    </p>
                    <form @submit.prevent="submit" class="modal-form">
                        <AppInput
                            id="password"
                            label="Contraseña actual"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            :error="form.errors.password"
                        />
                    </form>
                </div>
                <template #footer>
                    <AppButton variant="secondary" @click="showModal = false">
                        Cancelar
                    </AppButton>
                    <AppButton variant="danger" :loading="form.processing" @click="submit">
                        Sí, cerrar sesiones
                    </AppButton>
                </template>
            </AppModal>
        </div>
    </AppLayout>
</template>

<style scoped>
.page       { display:flex; flex-direction:column; gap:var(--space-5); }
.page-head  { display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-4); flex-wrap:wrap; }
.page-title { font-size:var(--text-xl); font-weight:700; }
.page-sub   { font-size:var(--text-sm); color:var(--color-text-muted); margin-top:var(--space-1); }
.modal-desc { font-size:var(--text-sm); color:var(--color-text-muted); margin-bottom:var(--space-4); }
.modal-form { display:flex; flex-direction:column; gap:var(--space-4); }
</style>
