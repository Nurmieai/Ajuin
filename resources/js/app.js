import './bootstrap';

function initThemeDropdown() {
    const themeRadios = document.querySelectorAll('input[name="theme-dropdown"]');
    const currentSaved = localStorage.getItem('theme') || 'system';

    themeRadios.forEach(radio => {
        if (radio.value === currentSaved) radio.checked = true;

        radio.addEventListener('change', (e) => {
            localStorage.setItem('theme', e.target.value);
            // Panggil dengan TRUE agar ada animasi halus saat diklik
            if (window.applyTheme) window.applyTheme(true);
        });
    });
}

document.addEventListener('DOMContentLoaded', initThemeDropdown);

// SAAT PINDAH HALAMAN
document.addEventListener('livewire:navigated', () => {
    // Panggil dengan FALSE agar TIDAK ada transisi kedip saat pindah halaman
    if (window.applyTheme) window.applyTheme(false);
    initThemeDropdown();
});