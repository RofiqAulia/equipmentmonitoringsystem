/* Stock Retrieval JavaScript - Inventory Control System */

let currentScannedItem = null;

function autoScanPayload(payload) {
    const input = document.getElementById('qr_payload');
    if (input) {
        input.value = payload;
        handleScanItem(new Event('submit'));
    }
}

async function handleScanItem(e) {
    if (e) e.preventDefault();
    const payloadInput = document.getElementById('qr_payload');
    const feedback = document.getElementById('scan-feedback');
    const scanRoute = window.RETRIEVAL_ROUTES ? window.RETRIEVAL_ROUTES.scan : '/stock/scan';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!payloadInput) return;
    const payload = payloadInput.value.trim();
    if (!payload) return;

    if (feedback) {
        feedback.className = 'p-3 rounded-xl text-xs font-medium bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20';
        feedback.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Memindai detail barang...';
        feedback.classList.remove('hidden');
    }

    try {
        const response = await fetch(scanRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ payload })
        });

        const data = await response.json();

        if (data.success) {
            if (feedback) feedback.classList.add('hidden');
            currentScannedItem = data.item;
            renderItemDetail(data.item);
        } else {
            if (feedback) {
                feedback.className = 'p-3 rounded-xl text-xs font-medium bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
                feedback.innerHTML = `<i class="fa-solid fa-circle-xmark mr-1"></i> ${data.message}`;
            }
        }
    } catch (err) {
        if (feedback) {
            feedback.className = 'p-3 rounded-xl text-xs font-medium bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
            feedback.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> Terjadi kesalahan jaringan.';
        }
    }
}

function renderItemDetail(item) {
    const emptyState = document.getElementById('empty-state');
    const detailCard = document.getElementById('item-detail-card');
    if (emptyState) emptyState.classList.add('hidden');
    if (detailCard) detailCard.classList.remove('hidden');

    const itemIdInput = document.getElementById('retrieval-item-id');
    const itemImg = document.getElementById('item-image');
    const skuBadge = document.getElementById('item-sku-badge');
    const itemName = document.getElementById('item-name');
    const itemLoc = document.getElementById('item-location');
    const availableStock = document.getElementById('item-available-stock');
    const minimumStock = document.getElementById('item-minimum-stock');

    if (itemIdInput) itemIdInput.value = item.id;
    if (itemImg) itemImg.src = item.image_url || 'https://placehold.co/300x300/1e293b/06b6d4?text=No+Photo';
    if (skuBadge) skuBadge.textContent = item.sku;
    if (itemName) itemName.textContent = item.name;
    if (itemLoc && itemLoc.querySelector('span')) itemLoc.querySelector('span').textContent = item.location_bin;
    if (availableStock) availableStock.textContent = item.available_stock;
    if (minimumStock) minimumStock.textContent = item.minimum_stock;

    // Badge Status Stok
    const statusBadge = document.getElementById('item-status-badge');
    if (statusBadge) {
        if (item.available_stock <= 0) {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30';
            statusBadge.textContent = 'Out of Stock';
        } else if (item.available_stock <= item.minimum_stock) {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30';
            statusBadge.textContent = 'Low Stock';
        } else {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30';
            statusBadge.textContent = 'In Stock';
        }
    }

    const confirmBtn = document.getElementById('btn-confirm-retrieval');
    if (confirmBtn) {
        if (item.available_stock <= 0) {
            confirmBtn.disabled = true;
            confirmBtn.className = 'w-full py-3.5 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-bold rounded-xl text-sm cursor-not-allowed';
            confirmBtn.innerHTML = '<i class="fa-solid fa-ban mr-1.5"></i> Stok Habis - Tidak Dapat Diambil';
        } else {
            confirmBtn.disabled = false;
            confirmBtn.className = 'w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-extrabold rounded-xl text-sm shadow-xl shadow-emerald-500/20 transition transform active:scale-[0.98]';
            confirmBtn.innerHTML = '<i class="fa-solid fa-circle-check mr-1.5"></i> Konfirmasi Pengambilan & Kurangi Stok';
        }
    }
}

async function handleConfirmRetrieval(e) {
    e.preventDefault();

    const selectedSupervisorId = window.SELECTED_SPV_ID;
    const confirmRoute = window.RETRIEVAL_ROUTES ? window.RETRIEVAL_ROUTES.confirm : '/stock/confirm';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!selectedSupervisorId) {
        alert('Silakan pilih Supervisor (SPV) penanggung jawab terlebih dahulu!');
        openSpvModal();
        return;
    }

    const itemId = document.getElementById('retrieval-item-id')?.value;
    const quantityPicked = parseInt(document.getElementById('quantity_picked')?.value || 1);
    const notes = document.getElementById('notes')?.value || '';

    try {
        const response = await fetch(confirmRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                item_id: itemId,
                quantity_picked: quantityPicked,
                supervisor_id: selectedSupervisorId,
                notes: notes
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('BERHASIL: ' + data.message);
            if (data.data && data.data.low_stock_alert) {
                alert(data.data.warning_message);
            }
            if (data.data && data.data.item) {
                currentScannedItem = data.data.item;
                renderItemDetail(data.data.item);
            }
            const notesInput = document.getElementById('notes');
            if (notesInput) notesInput.value = '';
        } else {
            alert('GAGAL: ' + data.message);
        }
    } catch (err) {
        alert('Terjadi kesalahan jaringan.');
    }
}

function openSpvModal() {
    const modal = document.getElementById('spv-modal');
    if (modal) modal.classList.remove('hidden');
}

function closeSpvModal() {
    const modal = document.getElementById('spv-modal');
    if (modal) modal.classList.add('hidden');
}

async function saveSelectedSpv() {
    const select = document.getElementById('spv-select-option');
    if (!select) return;

    const newSpvId = select.value;
    const spvName = select.options[select.selectedIndex].text.split('(')[0].trim();
    const selectSpvRoute = window.RETRIEVAL_ROUTES ? window.RETRIEVAL_ROUTES.selectSpv : '/user/select-supervisor';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        await fetch(selectSpvRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ supervisor_id: newSpvId })
        });

        window.SELECTED_SPV_ID = newSpvId;
        const spvDisplay = document.getElementById('spv-display-name');
        if (spvDisplay) spvDisplay.textContent = spvName;
        closeSpvModal();
    } catch (e) {
        alert('Gagal menyimpan SPV.');
    }
}
