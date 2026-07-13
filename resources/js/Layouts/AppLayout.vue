<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FinloraIcon from '@/Components/FinloraIcon.vue';
import ToastHost from '@/Components/ToastHost.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';

const open = ref(false);

const page = usePage();
const isPlannerMode = computed(() => page.url.startsWith('/planner'));

const financeLinks = [
    ['Dashboard', '/dashboard'],
    ['Buchungen', '/transactions'],
    ['Kategorien', '/categories'],
    ['Geplante Ausgaben', '/planned-transactions'],
    ['Monatsübersicht', '/reports/month'],
    ['Jahresübersicht', '/reports/year'],
    ['Budgets', '/budgets'],
    ['Konten', '/accounts'],
    ['Sparziele', '/savings-goals'],
];

const plannerLinks = [
    ['Tag', '/planner/day'],
    ['Woche', '/planner/week'],
    ['Monat', '/planner/month'],
    ['Jahr', '/planner/year'],
    ['Ideen', '/planner/ideas'],
];

const links = computed(() => (isPlannerMode.value ? plannerLinks : financeLinks));

const isActive = (href) => page.url === href || page.url.startsWith(`${href}/`);
</script>

<template>
    <div class="min-h-screen bg-background">
        <ToastHost />
        <ConfirmDialog />

        <div class="lg:flex">
            <aside class="hidden min-h-screen w-64 flex-col bg-sidebar text-sidebar-foreground lg:flex">
                <div class="flex items-center gap-2 p-6">
                    <FinloraIcon class="h-8 w-8 flex-none" />
                    <span class="text-xl font-bold">Finlora</span>
                </div>

                <div class="mx-4 mb-4 flex gap-1 rounded-lg bg-black/20 p-1 text-sm">
                    <Link
                        href="/dashboard"
                        class="flex-1 rounded px-2 py-1.5 text-center font-medium"
                        :class="!isPlannerMode ? 'bg-white/10 text-sidebar-foreground' : 'text-sidebar-foreground/60 hover:text-sidebar-foreground'"
                    >
                        Finanzen
                    </Link>
                    <Link
                        href="/planner/day"
                        class="flex-1 rounded px-2 py-1.5 text-center font-medium"
                        :class="isPlannerMode ? 'bg-white/10 text-sidebar-foreground' : 'text-sidebar-foreground/60 hover:text-sidebar-foreground'"
                    >
                        Planner
                    </Link>
                </div>

                <nav class="flex-1 space-y-1 px-4">
                    <Link
                        v-for="[label, href] in links"
                        :key="href"
                        :href="href"
                        class="block rounded px-4 py-2 hover:bg-white/10"
                        :class="isActive(href) ? 'bg-white/10' : ''"
                    >
                        {{ label }}
                    </Link>
                </nav>

                <div class="border-t border-white/10 p-4">
                    <div class="flex items-center justify-between px-2">
                        <p class="truncate text-sm text-sidebar-foreground/60">{{ $page.props.auth.user.name }}</p>
                        <DarkModeToggle class="flex-none rounded p-1 text-sidebar-foreground/60 hover:bg-white/10 hover:text-sidebar-foreground" />
                    </div>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="mt-1 block w-full rounded px-2 py-2 text-left text-sm text-sidebar-foreground/80 hover:bg-white/10"
                    >
                        Abmelden
                    </Link>
                </div>
            </aside>

            <main class="min-w-0 flex-1">
                <header class="sticky top-0 z-20 border-b border-border bg-card px-4 py-4 lg:px-8">
                    <div class="flex items-center justify-between">
                        <h1 class="text-lg font-semibold text-foreground lg:text-xl">
                            <slot name="title" />
                        </h1>

                        <div class="flex items-center gap-2">
                            <DarkModeToggle class="hidden rounded p-2 text-muted-foreground hover:bg-muted lg:block" />
                            <button
                                class="rounded bg-sidebar px-3 py-2 text-sm text-sidebar-foreground lg:hidden"
                                @click="open = !open"
                            >
                                Menü
                            </button>
                        </div>
                    </div>

                    <nav v-if="open" class="mt-4 lg:hidden">
                        <div class="mb-3 flex gap-1 rounded-lg bg-muted p-1 text-sm">
                            <Link
                                href="/dashboard"
                                class="flex-1 rounded px-2 py-1.5 text-center font-medium"
                                :class="!isPlannerMode ? 'bg-card text-foreground shadow-sm' : 'text-muted-foreground'"
                                @click="open = false"
                            >
                                Finanzen
                            </Link>
                            <Link
                                href="/planner/day"
                                class="flex-1 rounded px-2 py-1.5 text-center font-medium"
                                :class="isPlannerMode ? 'bg-card text-foreground shadow-sm' : 'text-muted-foreground'"
                                @click="open = false"
                            >
                                Planner
                            </Link>
                        </div>

                        <div class="space-y-1">
                            <Link
                                v-for="[label, href] in links"
                                :key="href"
                                :href="href"
                                class="block rounded px-3 py-2 text-sm text-foreground hover:bg-muted"
                                :class="isActive(href) ? 'bg-muted' : ''"
                                @click="open = false"
                            >
                                {{ label }}
                            </Link>
                        </div>

                        <div class="mt-3 border-t border-border pt-3">
                            <div class="flex items-center justify-between px-3">
                                <p class="truncate text-xs text-muted-foreground">{{ $page.props.auth.user.name }}</p>
                                <DarkModeToggle class="flex-none rounded p-1 text-muted-foreground hover:bg-muted" />
                            </div>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="mt-1 block w-full rounded px-3 py-2 text-left text-sm text-foreground hover:bg-muted"
                            >
                                Abmelden
                            </Link>
                        </div>
                    </nav>
                </header>

                <section class="p-4 lg:p-8">
                    <slot />
                </section>
            </main>
        </div>
    </div>
</template>
