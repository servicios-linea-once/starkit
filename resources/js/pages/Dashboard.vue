<script setup lang="ts">
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppBadge  from '@/components/ui/AppBadge.vue'
import type { PageProps } from '@inertiajs/core'

const page = usePage<PageProps>()
const user = computed(() => page.props.auth.user!)
const can  = computed(() => page.props.auth.can!)

// Hora del día para el saludo
const greeting = computed(() => {
    const h = new Date().getHours()
    if (h < 12) return 'Buenos días'
    if (h < 18) return 'Buenas tardes'
    return 'Buenas noches'
})

// Formato de fecha
const today = new Date().toLocaleDateString('es-PE', {
    weekday: 'long',
    year:    'numeric',
    month:   'long',
    day:     'numeric',
})

const stats = [
    {
        label:   'Módulo',
        value:   'Activo',
        icon:    'M5 12h14M12 5l7 7-7 7',
        variant: 'success' as const,
    },
    {
        label:   'Sesión',
        value:   'Verificada',
        icon:    'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
        variant: 'info' as const,
    },
    {
        label:   '2FA',
        value:   user.value.two_factor_enabled ? 'Activado' : 'Desactivado',
        icon:    'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10zM9 12l2 2 4-4',
        variant: user.value.two_factor_enabled ? 'success' as const : 'warning' as const,
    },
    {
        label:   'Correo',
        value:   user.value.email_verified_at ? 'Verificado' : 'Sin verificar',
        icon:    'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm16 2l-8 5-8-5',
        variant: user.value.email_verified_at ? 'success' as const : 'warning' as const,
    },
]

const quickLinks = computed(() => {
    const links = [
        {
            label:       'Mi perfil',
            description: 'Actualiza tu nombre, avatar y datos personales.',
            href:        route('settings.profile.edit'),
            icon:        'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
        },
        {
            label:       'Contraseña',
            description: 'Cambia tu contraseña de acceso.',
            href:        route('settings.password.update'),
            icon:        'M19 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2zM7 11V7a5 5 0 0 1 10 0v4',
        },
    ]

    if (can.value['users.view']) {
        links.push({
            label:       'Usuarios',
            description: 'Gestiona los usuarios del sistema.',
            href:        route('admin.users.index'),
            icon:        'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
        })
    }

    return links
})
</script>

<template>
    <AppLayout>
        <div class="dashboard">

            <!-- ── Encabezado ────────────────────────────────────────── -->
            <div class="dash-header">
                <div class="dash-header__left">
                    <p class="dash-header__date">{{ today }}</p>
                    <h1 class="dash-header__title">
                        {{ greeting }}, <span class="accent">{{ user.name.split(' ')[0] }}</span> 👋
                    </h1>
                    <div class="dash-header__role">
                        <AppBadge variant="info">
                            {{ user.roles?.[0] ?? 'usuario' }}
                        </AppBadge>
                        <AppBadge :variant="user.is_active ? 'success' : 'error'">
                            {{ user.is_active ? 'Activo' : 'Inactivo' }}
                        </AppBadge>
                    </div>
                </div>

                <!-- Avatar grande -->
                <div class="dash-header__avatar" aria-hidden="true">
                    <img
                        v-if="user.avatar_url"
                        :src="user.avatar_url"
                        :alt="user.name"
                        width="72" height="72"
                        loading="lazy"
                    />
                    <span v-else class="dash-header__initials">
                        {{ user.name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase() }}
                    </span>
                </div>
            </div>

            <!-- ── Stats ─────────────────────────────────────────────── -->
            <div class="stats-grid">
                <div v-for="stat in stats" :key="stat.label" class="stat-card">
                    <div class="stat-card__icon-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path :d="stat.icon" />
                        </svg>
                    </div>
                    <div class="stat-card__body">
                        <p class="stat-card__label">{{ stat.label }}</p>
                        <AppBadge :variant="stat.variant">{{ stat.value }}</AppBadge>
                    </div>
                </div>
            </div>

            <!-- ── Quick links ────────────────────────────────────────── -->
            <section class="section">
                <h2 class="section__title">Accesos rápidos</h2>
                <div class="quick-grid">
                    <Link
                        v-for="link in quickLinks"
                        :key="link.href"
                        :href="link.href"
                        class="quick-card"
                    >
                        <div class="quick-card__icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path :d="link.icon" />
                            </svg>
                        </div>
                        <div>
                            <p class="quick-card__label">{{ link.label }}</p>
                            <p class="quick-card__desc">{{ link.description }}</p>
                        </div>
                        <svg class="quick-card__arrow" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </section>

            <!-- ── Info de cuenta ─────────────────────────────────────── -->
            <section class="section">
                <h2 class="section__title">Información de cuenta</h2>
                <div class="info-card">

                    <div class="info-row">
                        <span class="info-row__label">Nombre</span>
                        <span class="info-row__value">{{ user.name }}</span>
                    </div>
                    <div class="info-divider" />

                    <div class="info-row">
                        <span class="info-row__label">Correo</span>
                        <span class="info-row__value">{{ user.email }}</span>
                    </div>
                    <div class="info-divider" />

                    <div class="info-row">
                        <span class="info-row__label">Verificación 2FA</span>
                        <AppBadge :variant="user.two_factor_enabled ? 'success' : 'warning'">
                            {{ user.two_factor_enabled ? 'Activado' : 'Desactivado' }}
                        </AppBadge>
                    </div>
                    <div class="info-divider" />

                    <div class="info-row">
                        <span class="info-row__label">Correo verificado</span>
                        <AppBadge :variant="user.email_verified_at ? 'success' : 'warning'">
                            {{ user.email_verified_at
                            ? new Date(user.email_verified_at).toLocaleDateString('es-PE')
                            : 'Pendiente' }}
                        </AppBadge>
                    </div>
                    <div class="info-divider" />

                    <div class="info-row">
                        <span class="info-row__label">Miembro desde</span>
                        <span class="info-row__value">
                            {{ new Date(user.created_at).toLocaleDateString('es-PE', {
                            year: 'numeric', month: 'long', day: 'numeric'
                        }) }}
                        </span>
                    </div>

                </div>
            </section>

        </div>
    </AppLayout>
</template>

<style scoped>
.dashboard {
    display:        flex;
    flex-direction: column;
    gap:            var(--space-8);
}

/* ── Header ── */
.dash-header {
    display:         flex;
    align-items:     flex-start;
    justify-content: space-between;
    gap:             var(--space-4);
    background:      var(--color-surface);
    border:          1px solid var(--color-border);
    border-radius:   var(--radius-xl);
    padding:         var(--space-6) var(--space-8);
}

.dash-header__left  { display: flex; flex-direction: column; gap: var(--space-2); }
.dash-header__date  { font-size: var(--text-xs); color: var(--color-text-faint); text-transform: capitalize; }
.dash-header__title { font-size: var(--text-xl); font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; }
.accent             { color: var(--color-primary); }
.dash-header__role  { display: flex; gap: var(--space-2); flex-wrap: wrap; }

.dash-header__avatar {
    width:           72px;
    height:          72px;
    border-radius:   var(--radius-full);
    background:      var(--color-primary-highlight);
    color:           var(--color-primary);
    font-size:       var(--text-lg);
    font-weight:     700;
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
    overflow:        hidden;
    border:          2px solid var(--color-border);
}
.dash-header__avatar img     { width: 100%; height: 100%; object-fit: cover; }
.dash-header__initials       { line-height: 1; }

/* ── Stats ── */
.stats-grid {
    display:               grid;
    grid-template-columns: repeat(auto-fill, minmax(min(200px, 100%), 1fr));
    gap:                   var(--space-4);
}

.stat-card {
    background:    var(--color-surface);
    border:        1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding:       var(--space-4) var(--space-5);
    display:       flex;
    align-items:   center;
    gap:           var(--space-4);
    box-shadow:    var(--shadow-sm);
}

.stat-card__icon-wrap {
    width:           36px;
    height:          36px;
    border-radius:   var(--radius-md);
    background:      var(--color-surface-offset);
    color:           var(--color-text-muted);
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
}

.stat-card__body  { display: flex; flex-direction: column; gap: var(--space-1); }
.stat-card__label { font-size: var(--text-xs); color: var(--color-text-muted); font-weight: 500; }

/* ── Sections ── */
.section        { display: flex; flex-direction: column; gap: var(--space-4); }
.section__title { font-size: var(--text-base); font-weight: 600; color: var(--color-text); }

/* ── Quick links ── */
.quick-grid {
    display:               grid;
    grid-template-columns: repeat(auto-fill, minmax(min(260px, 100%), 1fr));
    gap:                   var(--space-3);
}

.quick-card {
    display:         flex;
    align-items:     center;
    gap:             var(--space-4);
    padding:         var(--space-4) var(--space-5);
    background:      var(--color-surface);
    border:          1px solid var(--color-border);
    border-radius:   var(--radius-lg);
    text-decoration: none;
    cursor:          pointer;
    transition:      background var(--transition-interactive),
    box-shadow  var(--transition-interactive),
    transform   var(--transition-interactive);
    box-shadow:      var(--shadow-sm);
}
.quick-card:hover {
    background:  var(--color-surface-offset);
    box-shadow:  var(--shadow-md);
    transform:   translateY(-1px);
}

.quick-card__icon {
    width:           38px;
    height:          38px;
    border-radius:   var(--radius-md);
    background:      var(--color-primary-highlight);
    color:           var(--color-primary);
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
}

.quick-card__label { font-size: var(--text-sm); font-weight: 600; color: var(--color-text); }
.quick-card__desc  { font-size: var(--text-xs); color: var(--color-text-muted); margin-top: 2px; }
.quick-card__arrow { color: var(--color-text-faint); margin-left: auto; flex-shrink: 0;
    transition: transform var(--transition-interactive), color var(--transition-interactive); }
.quick-card:hover .quick-card__arrow { color: var(--color-primary); transform: translateX(3px); }

/* ── Info card ── */
.info-card {
    background:    var(--color-surface);
    border:        1px solid var(--color-border);
    border-radius: var(--radius-lg);
    overflow:      hidden;
    box-shadow:    var(--shadow-sm);
}

.info-row {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    padding:         var(--space-4) var(--space-5);
    gap:             var(--space-4);
}

.info-row__label { font-size: var(--text-xs); font-weight: 500; color: var(--color-text-muted); }
.info-row__value { font-size: var(--text-sm); color: var(--color-text); }
.info-divider    { height: 1px; background: var(--color-divider); }

/* ── Responsive ── */
@media (max-width: 640px) {
    .dash-header          { flex-direction: column-reverse; align-items: flex-start; padding: var(--space-5); }
    .dash-header__avatar  { width: 56px; height: 56px; }
    .dash-header__title   { font-size: var(--text-lg); }
    .info-row             { flex-direction: column; align-items: flex-start; gap: var(--space-1); }
}
</style>
