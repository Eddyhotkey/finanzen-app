import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { useToast } from './Composables/useToast';

// Subscribed once here (the true persistent app root) rather than inside
// AppLayout.vue, since every page wraps itself in <AppLayout> manually
// instead of using Inertia's persistent-layout pattern — AppLayout is
// remounted on every navigation, which would make an onMounted subscription
// there fire/tear-down on every single visit.
router.on('success', (event) => {
    const message = event.detail.page.props.flash?.success;
    if (message) useToast().push(message);
});

// Registered manually with an explicit path/scope rather than via
// `virtual:pwa-register`: the plugin's generated register call assumes the
// service worker lives alongside the build output (/build/sw.js), but it's
// actually emitted at the web root (/sw.js) so its scope covers the whole
// app, not just /build/.
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js', { scope: '/' });
    });
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
