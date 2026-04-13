import { computed } from 'vue'
import { usePage }  from '@inertiajs/vue3'

export function useFlash() {
    const page = usePage()
    const success = computed(() => page.props.flash?.success ?? null)
    const error   = computed(() => page.props.flash?.error   ?? null)
    const warning = computed(() => page.props.flash?.warning ?? null) // ✅
    const info    = computed(() => page.props.flash?.info    ?? null) // ✅
    return { success, error, warning, info }
}
