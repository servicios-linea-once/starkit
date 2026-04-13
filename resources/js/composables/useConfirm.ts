import { ref } from 'vue'

interface ConfirmOptions {
    title:    string
    message:  string
    confirm?: string
    cancel?:  string
    variant?: 'danger' | 'warning'
}

const visible  = ref(false)
const options  = ref<ConfirmOptions>({ title: '', message: '' })
let   resolver: ((value: boolean) => void) | null = null

export function useConfirm() {

    function confirm(opts: ConfirmOptions): Promise<boolean> {
        options.value = opts
        visible.value = true

        return new Promise((resolve) => {
            resolver = resolve
        })
    }

    function accept() {
        visible.value = false
        resolver?.(true)
        resolver = null
    }

    function cancel() {
        visible.value = false
        resolver?.(false)
        resolver = null
    }

    return { visible, options, confirm, accept, cancel }
}
