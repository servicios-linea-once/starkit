<script setup lang="ts">
import { ref, computed  } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import type { PageProps } from '@inertiajs/core'
import FlashToast   from '@/components/ui/FlashToast.vue'


const page = usePage<PageProps>()
const user = computed(() => page.props.auth.user!)
const can  = computed(() => page.props.auth.can!)

// ── Sidebar ───────────────────────────────────────────────────────────
const sidebarOpen = ref(false)

function closeSidebar() { sidebarOpen.value = false }

// Cada item puede tener children (grupo)
const nav = computed(() => [
    {
        label: 'Principal',
        items: [
            {
                key:   'dashboard',
                label: 'Dashboard',
                href:  route('dashboard'),
                icon:  'dashboard',
                show:  true,
            },
        ],
    },
    {
        label: 'Administración',
        items: [
            {
                key:   'users',
                label: 'Usuarios',
                href:  route('admin.users.index'),
                icon:  'users',
                show:  can.value['users.view'] ?? false,
            },
            {
                key:   'roles',
                label: 'Roles y permisos',
                href:  route('admin.roles.index'),
                icon:  'shield',
                show:  can.value['roles.view'] ?? false,
            },
            {
                key:   'login-history',
                label: 'Historial de sesiones',
                href:  route('admin.login-history.index'),
                icon:  'history',
                show:  can.value['users.view'] ?? false,
            },
        ],
    },
    {
        label: 'Cuenta',
        items: [
            {
                key:   'settings.profile',
                label: 'Perfil',
                href:  route('settings.profile.edit'),
                icon:  'user',
                show:  true,
            },
            {
                key:   'settings.password',
                label: 'Contraseña',
                href:  route('settings.password.edit'),
                icon:  'lock',
                show:  true,
            },
            {
                key:   'settings.login-history',
                label: 'Mis sesiones',
                href:  route('settings.login-history.mine'),
                icon:  'clock',
                show:  true,
            },
        ],
    },
])

// ── Logout ────────────────────────────────────────────────────────────
function logout() {
    router.post(route('logout'))
}

// ── Dark mode ─────────────────────────────────────────────────────────
const isDark = ref(
    document.documentElement.getAttribute('data-theme') === 'dark'
    || (!document.documentElement.getAttribute('data-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
)

function toggleTheme() {
    isDark.value = !isDark.value
    document.documentElement.setAttribute('data-theme', isDark.value ? 'dark' : 'light')
}

// ── Helper: ruta activa ───────────────────────────────────────────────
function isActive(href: string): boolean {
    return page.url.startsWith(new URL(href).pathname)
}

</script>

<template>
    <!-- ── Overlay mobile ───────────────────────────────────────────── -->
    <div
        v-if="sidebarOpen"
        class="sidebar-overlay"
        aria-hidden="true"
        @click="closeSidebar"
    />

    <div class="app-shell">
        <!-- ════════════════════════════════════════════════════════
             SIDEBAR
        ════════════════════════════════════════════════════════ -->
        <aside
            class="sidebar"
            :class="{ 'sidebar--open': sidebarOpen }"
            aria-label="Navegación principal"
        >
            <!-- Logo -->
            <div class="sidebar__logo">
                <Link :href="route('dashboard')" class="logo-link" @click="closeSidebar">
                    <svg class="logo-icon" width="28" height="28" viewBox="0 0 28 28"
                         fill="none" aria-label="StarKit logo">
                        <rect width="28" height="28" rx="7" fill="var(--color-primary)" />
                        <path d="M14 6l2.4 5.1 5.6.8-4 3.9.9 5.6L14 18.7l-4.9 2.7.9-5.6-4-3.9 5.6-.8L14 6z"
                              fill="white" />
                    </svg>
                    <span class="logo-text">StarKit</span>
                </Link>

                <!-- Botón cerrar (solo mobile) -->
                <button class="sidebar__close" aria-label="Cerrar menú" @click="closeSidebar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- Navegación -->
            <nav class="sidebar__nav">
                <template v-for="group in nav" :key="group.label">
                    <!-- Grupo visible con al menos un item activo -->
                    <template v-if="group.items.some(i => i.show)">
                        <p class="nav-group-label">{{ group.label }}</p>
                        <ul role="list">
                            <li v-for="item in group.items.filter(i => i.show)" :key="item.key">
                                <Link
                                    :href="item.href"
                                    :class="['nav-link', { 'nav-link--active': isActive(item.href) }]"
                                    :aria-current="isActive(item.href) ? 'page' : undefined"
                                    @click="closeSidebar"
                                >
                                    <!-- Iconos inline SVG -->
                                    <!-- dashboard -->
                                    <svg v-if="item.icon === 'dashboard'" class="nav-link__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                                    </svg>
                                    <!-- users -->
                                    <svg v-else-if="item.icon === 'users'" class="nav-link__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    <!-- shield -->
                                    <svg v-else-if="item.icon === 'shield'" class="nav-link__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    </svg>
                                    <!-- history -->
                                    <svg v-else-if="item.icon === 'history'" class="nav-link__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <polyline points="1 4 1 10 7 10"/>
                                        <path d="M3.51 15a9 9 0 1 0 .49-4.95"/>
                                    </svg>
                                    <!-- user -->
                                    <svg v-else-if="item.icon === 'user'" class="nav-link__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <!-- lock -->
                                    <svg v-else-if="item.icon === 'lock'" class="nav-link__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <!-- clock -->
                                    <svg v-else-if="item.icon === 'clock'" class="nav-link__icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>

                                    <span>{{ item.label }}</span>
                                </Link>
                            </li>
                        </ul>
                    </template>
                </template>
            </nav>

            <!-- Footer sidebar: avatar + nombre + logout -->
            <div class="sidebar__footer">
                <div class="sidebar-user">
                    <div class="sidebar-user__avatar">
                        <img v-if="user.avatar_url"
                             :src="user.avatar_url"
                             :alt="user.name"
                             width="32" height="32" loading="lazy" />
                        <span v-else>{{ user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                    <div class="sidebar-user__info">
                        <p class="sidebar-user__name">{{ user.name }}</p>
                        <p class="sidebar-user__email">{{ user.email }}</p>
                    </div>
                </div>

                <div class="sidebar-footer-actions">
                    <!-- Toggle dark mode -->
                    <button
                        class="sidebar-action-btn"
                        :aria-label="isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
                        data-theme-toggle
                        @click="toggleTheme"
                    >
                        <!-- Sol -->
                        <svg v-if="isDark" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                        <!-- Luna -->
                        <svg v-else width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>

                    <!-- Logout -->
                    <button
                        class="sidebar-action-btn sidebar-action-btn--logout"
                        aria-label="Cerrar sesión"
                        title="Cerrar sesión"
                        @click="logout"
                    >
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <!-- ════════════════════════════════════════════════════════
             MAIN
        ════════════════════════════════════════════════════════ -->
        <div class="app-main">

            <!-- Header mobile -->
            <header class="topbar">
                <button
                    class="topbar__menu-btn"
                    aria-label="Abrir menú"
                    @click="sidebarOpen = true"
                >
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6"  x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>

                <!-- Logo solo en mobile -->
                <Link :href="route('dashboard')" class="topbar__logo">
                    <svg width="22" height="22" viewBox="0 0 28 28" fill="none">
                        <rect width="28" height="28" rx="7" fill="var(--color-primary)" />
                        <path d="M14 6l2.4 5.1 5.6.8-4 3.9.9 5.6L14 18.7l-4.9 2.7.9-5.6-4-3.9 5.6-.8L14 6z"
                              fill="white" />
                    </svg>
                    <span class="topbar__logo-text">StarKit</span>
                </Link>

                <!-- Acciones topbar -->
                <div class="topbar__actions">
                    <button
                        class="topbar__icon-btn"
                        :aria-label="isDark ? 'Modo claro' : 'Modo oscuro'"
                        @click="toggleTheme"
                    >
                        <svg v-if="isDark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                        </svg>
                        <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>

                    <div class="topbar__avatar">
                        <img v-if="user.avatar_url"
                             :src="user.avatar_url"
                             :alt="user.name"
                             width="28" height="28" loading="lazy" />
                        <span v-else>{{ user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                </div>
            </header>

            <!-- Flash messages -->
            <FlashToast/>

            <!-- Contenido de la página -->
            <main id="main-content" class="page-content">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* ── Shell ──────────────────────────────────────────────────────────── */
.app-shell {
    display:    flex;
    min-height: 100dvh;
    background: var(--color-bg);
}

/* ── Sidebar ────────────────────────────────────────────────────────── */
.sidebar {
    width:          240px;
    flex-shrink:    0;
    background:     var(--color-surface);
    border-right:   1px solid var(--color-border);
    display:        flex;
    flex-direction: column;
    position:       sticky;
    top:            0;
    height:         100dvh;
    overflow-y:     auto;
    scrollbar-width: thin;
    scrollbar-color: var(--color-divider) transparent;
    z-index:        40;
}

/* ── Sidebar Logo ───────────────────────────────────────────────────── */
.sidebar__logo {
    display:        flex;
    align-items:    center;
    justify-content: space-between;
    padding:        var(--space-4) var(--space-4) var(--space-3);
    border-bottom:  1px solid var(--color-divider);
    flex-shrink:    0;
}
.logo-link {
    display:     flex;
    align-items: center;
    gap:         var(--space-2);
    text-decoration: none;
}
.logo-text {
    font-size:   var(--text-sm);
    font-weight: 700;
    color:       var(--color-text);
    letter-spacing: -0.02em;
}
.logo-icon { flex-shrink: 0; }
.sidebar__close {
    display:        none;
    width:          28px;
    height:         28px;
    border-radius:  var(--radius-md);
    align-items:    center;
    justify-content: center;
    color:          var(--color-text-muted);
}
.sidebar__close:hover { background: var(--color-surface-offset); color: var(--color-text); }

/* ── Sidebar Nav ────────────────────────────────────────────────────── */
.sidebar__nav {
    flex:       1;
    padding:    var(--space-3) var(--space-3);
    display:    flex;
    flex-direction: column;
    gap:        var(--space-4);
    overflow-y: auto;
}

.nav-group-label {
    font-size:      10px;
    font-weight:    600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color:          var(--color-text-faint);
    padding:        0 var(--space-2);
    margin-bottom:  var(--space-1);
}

ul { display: flex; flex-direction: column; gap: 2px; }

.nav-link {
    display:        flex;
    align-items:    center;
    gap:            var(--space-3);
    padding:        var(--space-2) var(--space-3);
    border-radius:  var(--radius-md);
    font-size:      var(--text-sm);
    font-weight:    400;
    color:          var(--color-text-muted);
    text-decoration: none;
    transition:     background var(--transition-interactive),
    color    var(--transition-interactive);
}
.nav-link:hover {
    background: var(--color-surface-offset);
    color:      var(--color-text);
}
.nav-link--active {
    background: var(--color-primary-highlight);
    color:      var(--color-primary);
    font-weight: 500;
}
.nav-link--active:hover {
    background: var(--color-primary-highlight);
    color:      var(--color-primary);
}
.nav-link__icon { flex-shrink: 0; }

/* ── Sidebar Footer ─────────────────────────────────────────────────── */
.sidebar__footer {
    padding:        var(--space-3) var(--space-3);
    border-top:     1px solid var(--color-divider);
    display:        flex;
    flex-direction: column;
    gap:            var(--space-3);
    flex-shrink:    0;
}
.sidebar-user          { display: flex; align-items: center; gap: var(--space-3); }
.sidebar-user__avatar  {
    width:           32px;
    height:          32px;
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
.sidebar-user__avatar img  { width: 100%; height: 100%; object-fit: cover; }
.sidebar-user__info        { flex: 1; min-width: 0; }
.sidebar-user__name        { font-size: var(--text-xs); font-weight: 600; color: var(--color-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sidebar-user__email       { font-size: 10px; color: var(--color-text-faint); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.sidebar-footer-actions    { display: flex; gap: var(--space-2); }
.sidebar-action-btn {
    flex:           1;
    height:         32px;
    border-radius:  var(--radius-md);
    display:        flex;
    align-items:    center;
    justify-content: center;
    color:          var(--color-text-muted);
    transition:     background var(--transition-interactive), color var(--transition-interactive);
}
.sidebar-action-btn:hover              { background: var(--color-surface-offset); color: var(--color-text); }
.sidebar-action-btn--logout:hover      { background: color-mix(in oklch, var(--color-error) 10%, transparent); color: var(--color-error); }

/* ── Main ───────────────────────────────────────────────────────────── */
.app-main {
    flex:           1;
    min-width:      0;
    display:        flex;
    flex-direction: column;
}

/* ── Topbar (mobile only) ───────────────────────────────────────────── */
.topbar {
    display:         none;
    align-items:     center;
    gap:             var(--space-3);
    padding:         var(--space-3) var(--space-4);
    background:      var(--color-surface);
    border-bottom:   1px solid var(--color-border);
    position:        sticky;
    top:             0;
    z-index:         30;
    flex-shrink:     0;
}
.topbar__menu-btn  { width: 36px; height: 36px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--color-text-muted); }
.topbar__menu-btn:hover { background: var(--color-surface-offset); }
.topbar__logo      { display: flex; align-items: center; gap: var(--space-2); text-decoration: none; flex: 1; }
.topbar__logo-text { font-size: var(--text-sm); font-weight: 700; color: var(--color-text); }
.topbar__actions   { display: flex; align-items: center; gap: var(--space-2); margin-left: auto; }
.topbar__icon-btn  { width: 32px; height: 32px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--color-text-muted); }
.topbar__icon-btn:hover { background: var(--color-surface-offset); }
.topbar__avatar    { width: 28px; height: 28px; border-radius: var(--radius-full); background: var(--color-primary-highlight); color: var(--color-primary); font-size: var(--text-xs); font-weight: 700; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.topbar__avatar img { width: 100%; height: 100%; object-fit: cover; }

/* ── Flash messages ─────────────────────────────────────────────────── */
.flash-wrap { padding: var(--space-4) var(--space-6) 0; display: flex; flex-direction: column; gap: var(--space-2); }
.flash {
    display:       flex;
    align-items:   center;
    gap:           var(--space-2);
    padding:       var(--space-3) var(--space-4);
    border-radius: var(--radius-md);
    font-size:     var(--text-sm);
}
.flash--success { background: var(--color-success-highlight); color: var(--color-success); }
.flash--error   { background: var(--color-error-highlight);   color: var(--color-error); }

/* ── Page content ───────────────────────────────────────────────────── */
.page-content {
    flex:    1;
    padding: var(--space-6);
}

/* ── Overlay mobile ─────────────────────────────────────────────────── */
.sidebar-overlay {
    position:   fixed;
    inset:      0;
    background: oklch(0 0 0 / 0.4);
    z-index:    39;
    backdrop-filter: blur(2px);
}

/* ── Responsive ─────────────────────────────────────────────────────── */
@media (max-width: 768px) {
    .sidebar {
        position:   fixed;
        inset-block: 0;
        left:       0;
        transform:  translateX(-100%);
        transition: transform 280ms cubic-bezier(0.16, 1, 0.3, 1);
        height:     100dvh;
        z-index:    40;
    }
    .sidebar--open    { transform: translateX(0); }
    .sidebar__close   { display: flex; }

    .topbar { display: flex; }

    .page-content { padding: var(--space-4); }
}
</style>
