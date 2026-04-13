<script setup lang="ts">
import type { BadgeVariant } from '@/types'

withDefaults(defineProps<{
    variant?: BadgeVariant
}>(), {
    variant: 'default',
})
</script>

<template>
    <span :class="['badge', `badge--${variant}`]">
        <slot />
    </span>
</template>

<style scoped>
.badge {
    display:       inline-flex;
    align-items:   center;
    gap:           var(--space-1);
    padding:       2px var(--space-2);
    font-size:     var(--text-xs);
    font-weight:   500;
    border-radius: var(--radius-full);
    white-space:   nowrap;
    line-height:   1.5;
}

.badge--default {
    background: var(--color-surface-offset);
    color:      var(--color-text-muted);
}
.badge--success {
    background: color-mix(in oklch, var(--color-success) 12%, transparent);
    color:      var(--color-success);
}
.badge--error {
    background: color-mix(in oklch, var(--color-error) 12%, transparent);
    color:      var(--color-error);
}
.badge--warning {
    background: color-mix(in oklch, var(--color-warning) 12%, transparent);
    color:      var(--color-warning);
}
.badge--info {
    background: color-mix(in oklch, var(--color-blue) 12%, transparent);
    color:      var(--color-blue);
}
</style>
