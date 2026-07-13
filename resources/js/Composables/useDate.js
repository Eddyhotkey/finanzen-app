export function useDate() {
    const formatDate = (date) => {
        if (!date) return '';

        return new Date(date).toLocaleDateString('de-DE');
    };

    const formatDayHeader = (date) => {
        if (!date) return '';

        return new Date(date).toLocaleDateString('de-DE', {
            day: 'numeric',
            weekday: 'long',
        });
    };

    const formatMonthHeader = (date) => {
        if (!date) return '';

        return new Date(date).toLocaleDateString('de-DE', {
            month: 'long',
            year: 'numeric',
        });
    };

    const formatDateTime = (date) => {
        if (!date) return '';

        return new Date(date).toLocaleString('de-DE', {
            dateStyle: 'medium',
            timeStyle: 'short',
        });
    };

    const formatMonthName = (date) => {
        if (!date) return '';

        return new Date(date).toLocaleDateString('de-DE', { month: 'long' });
    };

    return { formatDate, formatDayHeader, formatMonthHeader, formatDateTime, formatMonthName };
}
