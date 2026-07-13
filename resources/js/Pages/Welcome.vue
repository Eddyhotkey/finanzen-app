<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FinloraLogo from '@/Components/FinloraLogo.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
});

const features = [
    {
        title: 'Finanzen im Überblick',
        description: 'Buchungen, Budgets, Konten und Sparziele an einem Ort — mit Monats- und Jahresprognose.',
    },
    {
        title: 'Planer für Tag bis Jahr',
        description: 'Aufgaben, Termine, Urlaube und Jahresziele planen, von der Tagesübersicht bis zur Jahresansicht.',
    },
    {
        title: 'Alles an einem Ort',
        description: 'Keine zweite App nötig — Finanzen und Planung teilen sich einen Account und eine Oberfläche.',
    },
];
</script>

<template>
    <Head title="Willkommen" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-background px-4 py-16">
        <div class="w-full max-w-3xl text-center">
            <FinloraLogo class="mx-auto mb-10 h-12 w-auto" />

            <h1 class="text-3xl font-bold text-foreground sm:text-4xl">
                Finanzen und Planung an einem Ort.
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-muted-foreground">
                Behalte deine Finanzen im Blick und plane deinen Alltag, deine Woche und dein Jahr — in einer App.
            </p>

            <div v-if="canLogin" class="mt-8">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-flex items-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-primary-foreground transition hover:bg-primary-hover"
                >
                    Zum Dashboard
                </Link>
                <Link
                    v-else
                    :href="route('login')"
                    class="inline-flex items-center rounded-lg bg-primary px-6 py-3 text-sm font-medium text-primary-foreground transition hover:bg-primary-hover"
                >
                    Anmelden
                </Link>
            </div>

            <div class="mt-16 grid gap-4 text-left sm:grid-cols-3">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="rounded-xl border border-border bg-card p-6 shadow-sm"
                >
                    <h2 class="font-semibold text-foreground">{{ feature.title }}</h2>
                    <p class="mt-2 text-sm text-muted-foreground">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
