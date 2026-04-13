import { PageProps as InertiaPageProps } from '@inertiajs/core'

export interface User {
    id: number
    name: string
    email: string
    avatar_url: string | null
    two_factor_enabled: boolean
    roles: string[]
    permissions: string[]
}

export interface Flash {
    success?: string
    error?: string
    warning?: string
}

export interface Auth {
    user: User
}

// Merge con los PageProps globales de Inertia
declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps {
        auth: Auth
        flash: Flash
    }
}
