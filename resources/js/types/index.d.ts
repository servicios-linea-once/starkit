// ════════════════════════════════════════════════════════
//  StarKit — Global Types
// ════════════════════════════════════════════════════════

// ── Auth ─────────────────────────────────────────────────
export interface AuthUser {
    id:                 number
    name:               string
    email:              string
    avatar_url:         string | null
    roles:              string[]
    is_active:          boolean
    email_verified_at:  string | null
    two_factor_enabled: boolean,
    created_at:         string
}

// ── Modelos ───────────────────────────────────────────────
export interface Role {
    id:   number
    name: string
}

export interface Permission {
    id:   number
    name: string
}

export interface User {
    id:               number
    name:             string
    email:            string
    avatar_url:       string | null
    avatar_public_id: string | null
    is_active:        boolean
    email_verified_at: string | null
    two_factor_enabled: boolean
    created_at:       string
    updated_at:       string
    roles?:           Role[]
    permissions?:     Permission[]
}

export interface LoginHistory {
    id:           number
    user_id:      number
    ip_address:   string
    user_agent:   string
    browser:      string
    os:           string
    location:     string | null
    successful:   boolean
    logged_in_at: string
    created_at:   string
    user?:        Pick<User, 'id' | 'name' | 'email' | 'avatar_url'>
}

// ── Paginación ────────────────────────────────────────────
export interface PaginationLink {
    url:    string | null
    label:  string
    active: boolean
}

export interface PaginatedData<T> {
    data:         T[]
    current_page: number
    last_page:    number
    per_page:     number
    total:        number
    from:         number
    to:           number
    links:        PaginationLink[]
}

// ── Formularios ───────────────────────────────────────────
export interface UserForm {
    name:                  string
    email:                 string
    password:              string
    password_confirmation: string
    role:                  string
    is_active:             boolean
    avatar:                File | null
}

export interface RoleForm {
    name:        string
    permissions: string[]
}

// ── Variantes UI ──────────────────────────────────────────
export type ButtonVariant = 'primary' | 'secondary' | 'ghost' | 'danger'
export type ButtonSize    = 'sm' | 'md' | 'lg'
export type BadgeVariant  = 'default' | 'success' | 'error' | 'warning' | 'info'

// ════════════════════════════════════════════════════════
//  Module Augmentation — Inertia PageProps
//  ✅ Esta es la forma CORRECTA en Inertia v2
// ════════════════════════════════════════════════════════
declare module '@inertiajs/core' {
    interface PageProps {
        auth: {
            user: AuthUser | null
            can:  Record<string, boolean>
        }
        flash: {
            success: string | null
            error:   string | null
            warning:   string | null
            info:   string | null
        }
    }
}
