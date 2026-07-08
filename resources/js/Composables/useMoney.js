export function useMoney() {
    const money = (value) =>
        Number(value || 0).toLocaleString('de-DE', {
            style: 'currency',
            currency: 'EUR',
        });

    return { money };
}
