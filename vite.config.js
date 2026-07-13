import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';
import { createHash } from 'node:crypto';
import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';

// `revision: null` tells Workbox a URL is immutable and to never re-fetch it —
// wrong for these static files, whose filenames stay the same even when their
// content changes (a new icon, a tweaked offline page, ...). Hashing the
// actual file content makes the service worker pick up such changes on the
// next build instead of serving stale bytes forever.
const publicDir = fileURLToPath(new URL('./public', import.meta.url));
const revisionOf = (relativePath) =>
    createHash('md5').update(readFileSync(`${publicDir}/${relativePath}`)).digest('hex');

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            strategies: 'injectManifest',
            srcDir: 'resources/js',
            filename: 'sw.js',
            registerType: 'autoUpdate',
            injectRegister: false,
            outDir: 'public',
            scope: '/',
            // Hand-authored as a static public/manifest.webmanifest instead of
            // letting the plugin generate+self-precache it: the plugin always
            // injects a bare "manifest.webmanifest" (no /build/ prefix) into the
            // precache list, which 404s because the file it generates actually
            // lives under public/build/ — breaking the whole SW install. A plain
            // static file at the web root sidesteps that entirely.
            manifest: false,
            devOptions: {
                enabled: true,
                type: 'module',
            },
            injectManifest: {
                globDirectory: 'public/build',
                globPatterns: ['**/*.{js,css,woff,woff2}'],
                modifyURLPrefix: {
                    '': '/build/',
                },
                // includeAssets isn't reliable for files living directly in
                // public/ (outside globDirectory) in this setup, so the static
                // root-level files (favicon, manifest, app icons, offline
                // fallback) are precached explicitly here instead, with their
                // real absolute paths.
                additionalManifestEntries: [
                    { url: '/favicon.ico', revision: revisionOf('favicon.ico') },
                    { url: '/favicon-16.png', revision: revisionOf('favicon-16.png') },
                    { url: '/favicon-32.png', revision: revisionOf('favicon-32.png') },
                    { url: '/apple-touch-icon.png', revision: revisionOf('apple-touch-icon.png') },
                    { url: '/icon-192.png', revision: revisionOf('icon-192.png') },
                    { url: '/icon-512.png', revision: revisionOf('icon-512.png') },
                    { url: '/icon-maskable-512.png', revision: revisionOf('icon-maskable-512.png') },
                    { url: '/manifest.webmanifest', revision: revisionOf('manifest.webmanifest') },
                    { url: '/offline.html', revision: revisionOf('offline.html') },
                ],
            },
        }),
    ],
});
