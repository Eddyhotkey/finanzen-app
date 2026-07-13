<script setup>
import FinloraIcon from '@/Components/FinloraIcon.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-background pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <FinloraIcon class="h-20 w-20" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-card px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
