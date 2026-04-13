export interface Role {
    id: number
    name: string
    permissions: Permission[]
}

export interface Permission {
    id: number
    name: string
}

export interface PaginatedData<T> {
    data: T[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
    links: {
        url: string | null
        label: string
        active: boolean
    }[]
}
