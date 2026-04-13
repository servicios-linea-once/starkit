// resources/js/composables/useToast.ts
import { ref } from 'vue'

export type ToastVariant = 'success' | 'error' | 'warning' | 'info'

export interface Toast {
    id:      number
    message: string
    variant: ToastVariant
}

const toasts = ref<Toast[]>([])
let counter = 0

function playDim(variant: ToastVariant) {
    try {
        const ctx  = new AudioContext()
        const osc  = ctx.createOscillator()
        const gain = ctx.createGain()

        osc.connect(gain)
        gain.connect(ctx.destination)

        // Frecuencia según variante
        const freq: Record<ToastVariant, number> = {
            success: 660,
            error:   300,
            warning: 480,
            info:    540,
        }

        osc.type      = 'sine'
        osc.frequency.setValueAtTime(freq[variant], ctx.currentTime)
        gain.gain.setValueAtTime(0.12, ctx.currentTime)
        gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.35)

        osc.start(ctx.currentTime)
        osc.stop(ctx.currentTime + 0.35)
    } catch {}
}

export function useToast() {
    function add(message: string, variant: ToastVariant = 'success', duration = 4000) {
        const id = ++counter
        toasts.value.push({ id, message, variant })
        playDim(variant)
        setTimeout(() => remove(id), duration)
    }

    function remove(id: number) {
        toasts.value = toasts.value.filter(t => t.id !== id)
    }

    return { toasts, add, remove }
}
