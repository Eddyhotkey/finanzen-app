<script setup>
const props = defineProps({
    appointments: Array,
});

const START_HOUR = 8;
const END_HOUR = 22;
const HOUR_HEIGHT = 40;
const TOTAL_HEIGHT = (END_HOUR - START_HOUR) * HOUR_HEIGHT;

const hours = Array.from({ length: END_HOUR - START_HOUR + 1 }, (_, i) => START_HOUR + i);

const toMinutes = (time) => {
    const [h, m] = time.split(':').map(Number);
    return h * 60 + m;
};

const style = (appointment) => {
    const startMinutes = toMinutes(appointment.start_time) - START_HOUR * 60;
    const endMinutes = toMinutes(appointment.end_time) - START_HOUR * 60;

    return {
        top: `${(startMinutes / 60) * HOUR_HEIGHT}px`,
        height: `${Math.max(((endMinutes - startMinutes) / 60) * HOUR_HEIGHT, 20)}px`,
    };
};
</script>

<template>
    <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
        <h3 class="mb-3 font-semibold text-foreground">Tagesplan</h3>

        <div class="relative flex" :style="{ height: `${TOTAL_HEIGHT}px` }">
            <div class="w-14 flex-none text-right text-xs text-muted-foreground">
                <div
                    v-for="hour in hours"
                    :key="hour"
                    class="relative"
                    :style="{ height: `${HOUR_HEIGHT}px` }"
                >
                    <span class="absolute -top-2 right-2">{{ String(hour).padStart(2, '0') }}:00</span>
                </div>
            </div>

            <div class="relative flex-1 border-l border-border">
                <div
                    v-for="hour in hours"
                    :key="hour"
                    class="absolute w-full border-t border-border"
                    :style="{ top: `${(hour - START_HOUR) * HOUR_HEIGHT}px` }"
                />

                <div
                    v-for="appointment in appointments"
                    :key="appointment.id"
                    class="absolute left-1 right-1 overflow-hidden rounded border border-border bg-muted px-2 py-0.5 text-xs text-foreground"
                    :style="style(appointment)"
                >
                    {{ appointment.start_time.slice(0, 5) }} – {{ appointment.end_time.slice(0, 5) }}
                    {{ appointment.title }}
                </div>
            </div>
        </div>
    </div>
</template>
