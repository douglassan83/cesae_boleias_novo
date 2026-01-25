import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createInertiaApp({
    title: (title) => `${title} - Teste React`,
    resolve: (name) => {
    console.log('🔍 PAGE NAME:', name);
    const pages = import.meta.glob('./pages/**/*.jsx');
    console.log('📁 TODAS PÁGINAS:', Object.keys(pages));
    return resolvePageComponent(`./pages/${name}.jsx`, pages);
},
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
});
