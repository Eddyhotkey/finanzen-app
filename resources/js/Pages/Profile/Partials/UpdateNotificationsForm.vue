<script setup>
import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
import { getPushSubscriptionState, subscribeToPush, unsubscribeFromPush } from '@/push';
import { onMounted, ref } from 'vue';

const props = defineProps({
    webPushPublicKey: {
        type: String,
        default: null,
    },
});

const state = ref('checking'); // checking | unsupported | subscribed | unsubscribed | denied
const errorMessage = ref('');

onMounted(async () => {
    state.value = await getPushSubscriptionState();
    if (state.value === 'unsubscribed' && Notification.permission === 'denied') {
        state.value = 'denied';
    }
});

const enable = async () => {
    errorMessage.value = '';
    try {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            state.value = 'denied';
            return;
        }
        await subscribeToPush(props.webPushPublicKey);
        state.value = 'subscribed';
    } catch (e) {
        errorMessage.value = 'Benachrichtigungen konnten nicht aktiviert werden.';
    }
};

const disable = async () => {
    errorMessage.value = '';
    try {
        await unsubscribeFromPush();
        state.value = 'unsubscribed';
    } catch (e) {
        errorMessage.value = 'Benachrichtigungen konnten nicht deaktiviert werden.';
    }
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-foreground">Benachrichtigungen</h2>

            <p class="mt-1 text-sm text-muted-foreground">
                Erhalte Push-Erinnerungen für anstehende Termine und fällige Aufgaben im Planer.
            </p>
        </header>

        <div class="mt-6">
            <p v-if="state === 'unsupported'" class="text-sm text-muted-foreground">
                Dein Browser unterstützt keine Push-Benachrichtigungen.
            </p>

            <p v-else-if="state === 'denied'" class="text-sm text-muted-foreground">
                Benachrichtigungen sind in deinem Browser blockiert. Bitte in den Browser-Einstellungen erlauben.
            </p>

            <PrimaryButton v-else-if="state === 'unsubscribed'" @click="enable">
                Benachrichtigungen aktivieren
            </PrimaryButton>

            <div v-else-if="state === 'subscribed'" class="flex items-center gap-4">
                <span class="text-sm text-green-700">Benachrichtigungen sind aktiv.</span>
                <PrimaryButton @click="disable">Deaktivieren</PrimaryButton>
            </div>

            <p v-if="errorMessage" class="mt-2 text-sm text-red-600">{{ errorMessage }}</p>
        </div>
    </section>
</template>
