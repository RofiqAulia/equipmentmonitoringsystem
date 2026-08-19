/* Auth Page JavaScript - Inventory Control System */

function switchAuthTab(mode) {
    const classicTab = document.getElementById('tab-classic');
    const quickTab = document.getElementById('tab-quick');
    const classicBtn = document.getElementById('tab-classic-btn');
    const quickBtn = document.getElementById('tab-quick-btn');

    if (!classicTab || !quickTab || !classicBtn || !quickBtn) return;

    if (mode === 'classic') {
        classicTab.classList.remove('hidden');
        quickTab.classList.add('hidden');

        classicBtn.className = 'flex-1 py-2 rounded-lg text-xs sm:text-sm font-semibold transition bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30 shadow';
        quickBtn.className = 'flex-1 py-2 rounded-lg text-xs sm:text-sm font-semibold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white';
    } else {
        classicTab.classList.add('hidden');
        quickTab.classList.remove('hidden');

        quickBtn.className = 'flex-1 py-2 rounded-lg text-xs sm:text-sm font-semibold transition bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 shadow';
        classicBtn.className = 'flex-1 py-2 rounded-lg text-xs sm:text-sm font-semibold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white';
    }
}
