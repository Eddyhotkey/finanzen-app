export function useDate() {
    const formatDate = (date) => {
        if (!date) return '';

        return new Date(date).toLocaleDateString('de-DE');
    };

    return { formatDate };
}
