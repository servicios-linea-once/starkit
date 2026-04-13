import { route as routeFn } from 'ziggy-js';

// ✅ Hace que route() funcione dentro de <script setup>
declare global {
    const route: typeof routeFn;
}

// ✅ Hace que route() funcione dentro de <template>
declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: typeof routeFn;
    }
}
