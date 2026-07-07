<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import CategoryIcon from '@/Components/CategoryIcon.vue';

defineProps({
    categories: Array,
});

const deleteCategory = (category) => {
    if (confirm(`Kategorie "${category.name}" wirklich löschen?`)) {
        router.delete(route('categories.destroy', category.id));
    }
};
</script>

<template>
    <AppLayout>
        <template #title>
            Kategorien
        </template>

        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">
                Kategorien
            </h2>

            <Link
                href="/categories/create"
                class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800"
            >
                Neue Kategorie
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-3">Farbe</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Typ</th>
                    <th class="px-6 py-3">Icon</th>
                    <th class="px-6 py-3 text-right">Aktionen</th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="category in categories"
                    :key="category.id"
                    class="border-t"
                >
                    <td class="px-6 py-4">
                            <span
                                class="block h-5 w-5 rounded-full"
                                :style="{ backgroundColor: category.color }"
                            ></span>
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ category.name }}
                    </td>

                    <td class="px-6 py-4">
                            <span v-if="category.type === 'income'">
                                Einnahme
                            </span>
                        <span v-else>
                                Ausgabe
                            </span>
                    </td>

                    <td class="px-6 py-4">
                        <CategoryIcon :icon="category.icon" />
                    </td>

                    <td class="space-x-3 px-6 py-4 text-right">
                        <Link
                            :href="route('categories.edit', category.id)"
                            class="text-blue-600 hover:underline"
                        >
                            Bearbeiten
                        </Link>

                        <button
                            @click="deleteCategory(category)"
                            class="text-red-600 hover:underline"
                        >
                            Löschen
                        </button>
                    </td>
                </tr>

                <tr v-if="categories.length === 0">
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        Noch keine Kategorien vorhanden.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
