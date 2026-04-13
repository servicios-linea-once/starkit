export function useInitials() {

    /**
     * Obtiene las iniciales de un nombre completo.
     * "Juan Pérez López" → "JP"
     * "Juan"             → "JU"
     */
    function getInitials(name: string): string {
        const parts = name.trim().split(/\s+/).filter(Boolean)

        if (parts.length === 0) return '?'

        if (parts.length === 1) {
            // Una sola palabra → primeras 2 letras
            return parts[0].slice(0, 2).toUpperCase()
        }

        // Varias palabras → primera letra de las dos primeras palabras
        return (parts[0][0] + parts[1][0]).toUpperCase()
    }

    return { getInitials }
}
