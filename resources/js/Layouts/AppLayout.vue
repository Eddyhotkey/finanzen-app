<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <div class="flex">
            <aside class="min-h-screen w-64 bg-gray-900 text-white">
                <div class="p-6 text-xl font-bold">
                    Finanzen
                </div>

                <nav class="space-y-1 px-4">
                    <Link href="/dashboard" class="block rounded px-4 py-2 hover:bg-gray-800">
                        Dashboard
                    </Link>

                    <Link href="/transactions" class="block rounded px-4 py-2 hover:bg-gray-800">
                        Buchungen
                    </Link>

                    <Link href="/categories" class="block rounded px-4 py-2 hover:bg-gray-800">
                        Kategorien
                    </Link>

                    <Link href="/reports/month" class="block rounded px-4 py-2 hover:bg-gray-800">
                        Monatsübersicht
                    </Link>

                    <Link href="/reports/year" class="block rounded px-4 py-2 hover:bg-gray-800">
                        Jahresübersicht
                    </Link>
                </nav>
            </aside>

            <main class="flex-1">
                <header class="border-b bg-white px-8 py-4">
                    <h1 class="text-xl font-semibold text-gray-800">
                        <slot name="title" />
                    </h1>
                </header>

                <section class="p-8">
                    <slot />
                </section>
            </main>
        </div>
    </div>
</template>
