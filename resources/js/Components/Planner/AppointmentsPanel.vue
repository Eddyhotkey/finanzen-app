<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import { useConfirm } from '@/Composables/useConfirm';

const { confirm } = useConfirm();

const props = defineProps({
    appointments: Array,
    date: String,
});

const showAdd = ref(false);
const title = ref('');
const startTime = ref('08:00');
const endTime = ref('08:30');

const addAppointment = () => {
    if (!title.value.trim()) return;

    router.post(route('planner-appointments.store'), {
        title: title.value,
        date: props.date,
        start_time: startTime.value,
        end_time: endTime.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            title.value = '';
            showAdd.value = false;
        },
    });
};

const destroy = async (appointment) => {
    if (await confirm('Termin wirklich löschen?')) {
        router.delete(route('planner-appointments.destroy', appointment.id), { preserveScroll: true });
    }
};
</script>

<template>
    <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
        <div class="mb-3 flex items-center justify-between">
            <h3 class="font-semibold text-foreground">Termine</h3>

            <button
                type="button"
                @click="showAdd = !showAdd"
                class="rounded-lg border border-border px-3 py-1 text-sm font-medium text-foreground hover:bg-muted"
            >
                + Termin
            </button>
        </div>

        <div v-if="showAdd" class="mb-4 space-y-2">
            <input
                v-model="title"
                type="text"
                placeholder="Termin"
                class="block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
            />
            <div class="flex gap-2">
                <input v-model="startTime" type="time" class="w-full rounded-lg border-border text-sm" />
                <input v-model="endTime" type="time" class="w-full rounded-lg border-border text-sm" />
            </div>
            <div class="flex gap-2">
                <button
                    type="button"
                    @click="addAppointment"
                    class="rounded-lg bg-primary px-3 py-1 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
                >
                    Speichern
                </button>
                <button
                    type="button"
                    @click="showAdd = false"
                    class="rounded-lg border border-border px-3 py-1 text-sm text-foreground hover:bg-muted"
                >
                    Abbrechen
                </button>
            </div>
        </div>

        <p v-if="!appointments.length" class="text-sm text-muted-foreground">Keine Termine.</p>

        <div v-else class="space-y-2">
            <div
                v-for="appointment in appointments"
                :key="appointment.id"
                class="flex items-center justify-between rounded-lg border border-border px-3 py-2 text-sm"
            >
                <div>
                    <p class="font-medium text-foreground">{{ appointment.title }}</p>
                    <p class="text-xs text-muted-foreground">
                        {{ appointment.start_time.slice(0, 5) }}–{{ appointment.end_time.slice(0, 5) }}
                    </p>
                </div>

                <button type="button" @click="destroy(appointment)" class="text-muted-foreground hover:text-red-600">
                    <TrashIcon class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>
</template>
