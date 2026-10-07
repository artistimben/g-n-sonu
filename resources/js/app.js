const liveClock = document.querySelector('#live-clock');

if (liveClock) {
    const baseTime = Number(liveClock.dataset.now) * 1000;
    const pageStartedAt = Date.now();
    const formatter = new Intl.DateTimeFormat('tr-TR', {
        timeZone: 'Europe/Istanbul',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hourCycle: 'h23',
    });

    const updateClock = () => {
        liveClock.textContent = formatter.format(new Date(baseTime + (Date.now() - pageStartedAt)));
    };

    updateClock();
    window.setInterval(updateClock, 1000);
}

document.querySelectorAll('.flash-close').forEach((button) => {
    button.addEventListener('click', () => button.closest('.flash')?.remove());
});

const periodForm = document.querySelector('[data-period-form]');

if (periodForm && !periodForm.querySelector('.locked-period')) {
    const dateInput = periodForm.querySelector('[name="date"]');
    const typeInputs = [...periodForm.querySelectorAll('[name="type"]')];
    const preview = periodForm.querySelector('[data-period-preview]');
    const periodLabel = periodForm.querySelector('.form-period-row > label');

    const formatDate = (date) => [date.getDate(), date.getMonth() + 1, date.getFullYear()]
        .map((part, index) => String(part).padStart(index < 2 ? 2 : 4, '0'))
        .join('.');

    const updatePeriod = () => {
        if (!dateInput?.value || !preview) return;

        const [year, month, day] = dateInput.value.split('-').map(Number);
        const start = new Date(year, month - 1, day);
        const end = new Date(year, month - 1, day);
        const type = typeInputs.find((input) => input.checked)?.value ?? 'daily';

        if (type === 'weekly') {
            const offsetFromMonday = (start.getDay() + 6) % 7;
            start.setDate(start.getDate() - offsetFromMonday);
            end.setTime(start.getTime());
            end.setDate(start.getDate() + 6);
        } else if (type === 'monthly') {
            start.setDate(1);
            end.setDate(1);
            end.setMonth(end.getMonth() + 1);
            end.setDate(0);
        }

        preview.textContent = formatDate(start) + (start.getTime() === end.getTime() ? '' : ` – ${formatDate(end)}`);
        if (periodLabel) {
            periodLabel.textContent = type === 'daily' ? 'Rapor tarihi' : 'Dönem içinden bir tarih seç';
        }
    };

    dateInput?.addEventListener('change', updatePeriod);
    typeInputs.forEach((input) => input.addEventListener('change', () => {
        typeInputs.forEach((item) => item.closest('.type-choice')?.classList.toggle('is-selected', item.checked));
        updatePeriod();
    }));
    updatePeriod();
}
