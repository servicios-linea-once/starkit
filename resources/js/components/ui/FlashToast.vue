<script setup lang="ts">
import { watch }    from 'vue'
import { useFlash } from '@/composables/useFlash'
import { useToast } from '@/composables/useToast'
import AppToast     from '@/components/ui/AppToast.vue'

const { success, error, warning, info } = useFlash()
const { add } = useToast()

watch(success, (msg) => { if (msg) add(msg, 'success') })
watch(error,   (msg) => { if (msg) add(msg, 'error') })
watch(warning, (msg) => { if (msg) add(msg, 'warning') })
watch(info,    (msg) => { if (msg) add(msg, 'info') })
</script>

<template>
    <AppToast/>
</template>
