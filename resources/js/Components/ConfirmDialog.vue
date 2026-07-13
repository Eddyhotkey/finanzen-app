<script setup>
import Modal from '@/Components/Modal.vue';
import { useConfirm } from '@/Composables/useConfirm';

const { show, message, respond } = useConfirm();
</script>

<template>
    <Modal :show="show" max-width="sm" @close="respond(false)">
        <div class="p-6">
            <p class="text-sm text-foreground">{{ message }}</p>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    @click="respond(false)"
                    class="rounded-lg border border-border px-3 py-1.5 text-sm text-foreground hover:bg-muted"
                >
                    Abbrechen
                </button>
                <button
                    type="button"
                    @click="respond(true)"
                    class="rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700"
                >
                    Löschen
                </button>
            </div>
        </div>
    </Modal>
</template>
