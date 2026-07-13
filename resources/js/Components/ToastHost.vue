<script setup>
import { useToast } from '@/Composables/useToast';
import { CheckCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const { toasts, remove } = useToast();
</script>

<template>
    <div class="fixed right-4 top-4 z-50 space-y-2">
        <TransitionGroup
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="flex items-center gap-2 rounded-lg bg-emerald-600 py-2 pl-3 pr-2 text-sm font-medium text-white shadow-lg dark:bg-emerald-500"
            >
                <CheckCircleIcon class="h-5 w-5 flex-none" />
                <span>{{ toast.message }}</span>
                <button type="button" @click="remove(toast.id)" class="rounded p-1 hover:bg-white/20">
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>
