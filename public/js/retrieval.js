/* Stock Retrieval JavaScript - Inventory Control System */

let currentScannedItem = null;
let html5QrScannerInstance = null;

function autoScanPayload(payload) {
    const input = document.getElementById('qr_payload');
    if (input) {
        input.value = payload;
        handleScanItem(new Event('submit'));
    }
}

function adjustQty(delta) {
    const qtyInput = document.getElementById('quantity_picked');
    if (!qtyInput) return;
    let currentVal = parseInt(qtyInput.value || 1);
    let newVal = currentVal + delta;
    if (newVal < 1) newVal = 1;
    if (currentScannedItem && newVal > currentScannedItem.available_stock) {
        newVal = currentScannedItem.available_stock;
        showToast('Batas Stok Maksimum', `Stok barang hanya tersedia ${currentScannedItem.available_stock} unit.`, 'warning');
    }
    qtyInput.value = newVal;
}

function showToast(title, message, type = 'success') {
    const toast = document.getElementById('toast-alert');
    const toastTitle = document.getElementById('toast-title');
    const toastMsg = document.getElementById('toast-message');
    const iconBg = document.getElementById('toast-icon-bg');
    const icon = document.getElementById('toast-icon');

    if (!toast || !toastTitle || !toastMsg) {
        alert(`${title}: ${message}`);
        return;
    }

    toastTitle.textContent = title;
    toastMsg.textContent = message;

    if (type === 'success') {
        toast.className = 'p-4 rounded-2xl border bg-emerald-500/10 border-emerald-500/30 text-emerald-800 dark:text-emerald-300 shadow-xl flex items-center justify-between transition-all duration-300 transform scale-100';
        iconBg.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400';
        icon.className = 'fa-solid fa-circle-check';
    } else if (type === 'warning') {
        toast.className = 'p-4 rounded-2xl border bg-amber-500/10 border-amber-500/30 text-amber-800 dark:text-amber-300 shadow-xl flex items-center justify-between transition-all duration-300 transform scale-100';
        iconBg.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold bg-amber-500/20 text-amber-600 dark:text-amber-400';
        icon.className = 'fa-solid fa-triangle-exclamation';
    } else {
        toast.className = 'p-4 rounded-2xl border bg-rose-500/10 border-rose-500/30 text-rose-800 dark:text-rose-300 shadow-xl flex items-center justify-between transition-all duration-300 transform scale-100';
        iconBg.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold bg-rose-500/20 text-rose-600 dark:text-rose-400';
        icon.className = 'fa-solid fa-circle-xmark';
    }

    toast.classList.remove('hidden');

    setTimeout(() => {
        closeToast();
    }, 6000);
}

function closeToast() {
    const toast = document.getElementById('toast-alert');
    if (toast) toast.classList.add('hidden');
}

/* Kamera Scanner HTML5 Handler */
function startCameraScanner() {
    const modal = document.getElementById('camera-modal');
    if (modal) modal.classList.remove('hidden');

    if (typeof Html5Qrcode === 'undefined') {
        showToast('Library Belum Terload', 'Scanner kamera memerlukan koneksi internet untuk memuat library HTML5 QR Code.', 'error');
        return;
    }

    if (!html5QrScannerInstance) {
        html5QrScannerInstance = new Html5Qrcode("qr-reader");
    }

    html5QrScannerInstance.start(
        { facingMode: "environment" }, 
        {
            fps: 10,
            qrbox: { width: 220, height: 220 }
        },
        (decodedText, decodedResult) => {
            // Success QR Scan
            stopCameraScanner();
            autoScanPayload(decodedText);
            showToast('QR Code Terdeteksi!', `Payload QR: ${decodedText}`, 'success');
        },
        (errorMessage) => {
            // Ignore scan errors
        }
    ).catch(err => {
        showToast('Akses Kamera Gagal', 'Pastikan Anda memberikan izin akses kamera pada browser.', 'error');
        stopCameraScanner();
    });
}

function stopCameraScanner() {
    const modal = document.getElementById('camera-modal');
    if (modal) modal.classList.add('hidden');

    if (html5QrScannerInstance && html5QrScannerInstance.isScanning) {
        html5QrScannerInstance.stop().then(() => {
            console.log("Camera Scanner Stopped");
        }).catch(err => console.error(err));
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
        feedback.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Memproses pencarian data barang ke database...';
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
            showToast('Barang Ditemukan!', `[${data.item.sku}] ${data.item.name} - Stok: ${data.item.available_stock} unit`, 'success');
        } else {
            if (feedback) {
                feedback.className = 'p-3 rounded-xl text-xs font-medium bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
                feedback.innerHTML = `<i class="fa-solid fa-circle-xmark mr-1"></i> ${data.message}`;
            }
            showToast('Pencarian Gagal', data.message, 'error');
        }
    } catch (err) {
        if (feedback) {
            feedback.className = 'p-3 rounded-xl text-xs font-medium bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
            feedback.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> Terjadi kesalahan jaringan saat menghubungi server.';
        }
        showToast('Kesalahan Sistem', 'Tidak dapat terhubung ke server database gudang.', 'error');
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

    // Reset Qty input to 1
    const qtyInput = document.getElementById('quantity_picked');
    if (qtyInput) qtyInput.value = 1;

    // Badge Status Stok
    const statusBadge = document.getElementById('item-status-badge');
    if (statusBadge) {
        if (item.available_stock <= 0) {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30';
            statusBadge.textContent = 'Out of Stock (Habis)';
        } else if (item.available_stock <= item.minimum_stock) {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30';
            statusBadge.textContent = 'Low Stock (Menipis)';
        } else {
            statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30';
            statusBadge.textContent = 'In Stock (Tersedia)';
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
            confirmBtn.className = 'w-full py-4 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-2xl text-sm shadow-xl shadow-sky-600/25 transition transform active:scale-[0.98]';
            confirmBtn.innerHTML = '<i class="fa-solid fa-circle-check mr-2 text-base"></i> Konfirmasi Pengambilan & Kurangi Stok Database';
        }
    }
}

async function handleConfirmRetrieval(e) {
    e.preventDefault();

    const selectedSupervisorId = window.SELECTED_SPV_ID;
    const confirmRoute = window.RETRIEVAL_ROUTES ? window.RETRIEVAL_ROUTES.confirm : '/stock/confirm';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!selectedSupervisorId) {
        showToast('Supervisor Belum Dipilih', 'Silakan pilih Supervisor (SPV) penanggung jawab terlebih dahulu sebelum mengambil barang!', 'warning');
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
            let msg = `Stok dikurangi ${quantityPicked} unit. Sisa stok: ${data.data.remaining_stock} unit.`;
            showToast('Transaksi Pengambilan Berhasil!', msg, 'success');

            if (data.data && data.data.low_stock_alert) {
                setTimeout(() => {
                    showToast('PERINGATAN STOK MENIPIS!', data.data.warning_message, 'warning');
                }, 2000);
            }

            if (data.data && data.data.item) {
                currentScannedItem = data.data.item;
                renderItemDetail(data.data.item);
            }

            const notesInput = document.getElementById('notes');
            if (notesInput) notesInput.value = '';
        } else {
            showToast('Transaksi Gagal', data.message, 'error');
        }
    } catch (err) {
        showToast('Kesalahan Jaringan', 'Gagal memproses transaksi pengambilan barang ke server.', 'error');
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
        showToast('SPV Diperbarui', `Supervisor penanggung jawab aktif: ${spvName}`, 'success');
    } catch (e) {
        showToast('Gagal Menyimpan SPV', 'Terjadi kesalahan sistem.', 'error');
    }
}

