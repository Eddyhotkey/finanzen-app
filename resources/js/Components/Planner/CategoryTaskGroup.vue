<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import TaskRow from '@/Components/Planner/TaskRow.vue';
import ColorPicker from '@/Components/ColorPicker.vue';
import { useConfirm } from '@/Composables/useConfirm';

const { confirm } = useConfirm();

const props = defineProps({
    category: Object,
    tasks: Array,
    date: String,
});

const showAdd = ref(false);
const title = ref('');
const showDescription = ref(false);
const description = ref('');

const editing = ref(false);
const editName = ref(props.category.name);
const editColor = ref(props.category.color || '#111827');

const addTask = () => {
    if (!title.value.trim()) return;

    router.post(route('planner-tasks.store'), {
        planner_category_id: props.category.id,
        title: title.value,
        description: description.value || null,
        date: props.date,
        priority: 'mittel',
        is_done: false,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            title.value = '';
            description.value = '';
            showDescription.value = false;
            showAdd.value = false;
        },
    });
};

const saveCategory = () => {
    if (!editName.value.trim()) return;

    router.patch(route('planner-categories.update', props.category.id), {
        name: editName.value,
        color: editColor.value,
        redirect_date: props.date,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            editing.value = false;
        },
    });
};

const destroyCategory = async () => {
    if (await confirm(`Kategorie "${props.category.name}" inklusive aller Aufgaben wirklich löschen?`)) {
        router.delete(route('planner-categories.destroy', props.category.id), { preserveScroll: true });
    }
};
</script>

<template>
    <div
        class="rounded-xl border-l-4 bg-card p-6 shadow-sm"
        :style="{ borderLeftColor: category.color || '#111827' }"
    >
        <div v-if="!editing" class="mb-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="font-semibold text-foreground">{{ category.name }}</h3>
                <button type="button" @click="editing = true" class="text-muted-foreground hover:text-foreground">
                    <PencilSquareIcon class="h-4 w-4" />
                </button>
                <button type="button" @click="destroyCategory" class="text-muted-foreground hover:text-red-600">
                    <TrashIcon class="h-4 w-4" />
                </button>
            </div>

            <button
                type="button"
                @click="showAdd = !showAdd"
                class="rounded-lg border border-border px-3 py-1 text-sm font-medium text-foreground hover:bg-muted"
            >
                + Aufgabe
            </button>
        </div>

        <div v-else class="mb-4 space-y-3">
            <input
                v-model="editName"
                type="text"
                class="block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
            />
            <ColorPicker v-model="editColor" />
            <div class="flex gap-2">
                <button
                    type="button"
                    @click="saveCategory"
                    class="rounded-lg bg-primary px-3 py-1 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
                >
                    Speichern
                </button>
                <button
                    type="button"
                    @click="editing = false"
                    class="rounded-lg border border-border px-3 py-1 text-sm text-foreground hover:bg-muted"
                >
                    Abbrechen
                </button>
            </div>
        </div>

        <div v-if="showAdd" class="mb-3 space-y-2">
            <div class="flex gap-2">
                <input
                    v-model="title"
                    type="text"
                    placeholder="Neue Aufgabe..."
                    class="block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
                    @keyup.enter="addTask"
                />
                <button
                    type="button"
                    @click="addTask"
                    class="rounded-lg bg-primary px-3 py-1 text-sm font-medium text-primary-foreground hover:bg-primary-hover"
                >
                    Speichern
                </button>
            </div>

            <button
                v-if="!showDescription"
                type="button"
                @click="showDescription = true"
                class="text-xs text-muted-foreground hover:text-foreground hover:underline"
            >
                + Beschreibung
            </button>
            <textarea
                v-else
                v-model="description"
                rows="2"
                placeholder="Beschreibung (optional)..."
                class="mb-3 block w-full rounded-lg border-border text-sm shadow-sm focus:border-ring focus:ring-ring"
            />
        </div>

        <div v-if="tasks.length" class="space-y-2">
            <TaskRow v-for="task in tasks" :key="task.id" :task="task" :date="date" />
        </div>
    </div>
</template>
