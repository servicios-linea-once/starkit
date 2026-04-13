/// <reference types="vite/client" />

import '../css/app.css';

import { createApp, h, type DefineComponent } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'

createInertiaApp({
    title: (title: string) => `${title} - My App`,
    resolve: (name: string) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            // ✅ Genérico explícito para que TS infiera el tipo correcto
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },
    progress: {
        color: '#01696f',   // --color-primary
        delay: 100,
        includeCSS: true,
        showSpinner: false,
    },
})
