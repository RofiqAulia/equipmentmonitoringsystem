/* Global Application JavaScript - Inventory Control System */

document.addEventListener('DOMContentLoaded', function () {
    initThemeToggle();
});

// Initialize Theme Switcher (Tampilan Siang & Malam)
function initThemeToggle() {
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
        document.documentElement.classList.add('dark');
        updateThemeIcon('dark');
    } else {
        document.documentElement.classList.remove('dark');
        updateThemeIcon('light');
    }
}

// Toggle between Light Mode (Siang) and Dark Mode (Malam)
function toggleTheme() {
    const isDark = document.documentElement.classList.toggle('dark');
    const themeName = isDark ? 'dark' : 'light';
    
    localStorage.setItem('theme', themeName);
    updateThemeIcon(themeName);
}

// Update Theme Icon & Active Status Label
function updateThemeIcon(theme) {
    const icon = document.getElementById('theme-toggle-icon');
    const label = document.getElementById('theme-toggle-label');
    
    if (icon) {
        if (theme === 'dark') {
            icon.className = 'fa-solid fa-moon text-indigo-400 text-sm';
            if (label) label.textContent = 'Mode: Malam';
        } else {
            icon.className = 'fa-solid fa-sun text-amber-500 text-sm';
            if (label) label.textContent = 'Mode: Siang';
        }
    }
}

// Close Alert Popup
function dismissAlert(element) {
    if (element && element.parentElement) {
        element.parentElement.style.opacity = '0';
        setTimeout(() => element.parentElement.remove(), 200);
    }
}
