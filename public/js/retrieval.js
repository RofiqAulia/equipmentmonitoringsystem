/* Stock Retrieval JavaScript - Inventory Control System */

let currentScannedItem = null;
let html5QrScannerInstance = null;
let toastTimeout = null;

// Toast Alert Display Function
function setPresetNote(text, targetId = 'notes') {
    const el = document.getElementById(targetId);
    if (el) {
        el.value = text;
        el.focus();
    }
}

function showToast(title, message, type = 'info') {
    const toast = document.getElementById('toast-alert');
    const toastIconBg = document.getElementById('toast-icon-bg');
    const toastIcon = document.getElementById('toast-icon');
    const toastTitle = document.getElementById('toast-title');
    const toastMsg = document.getElementById('toast-message');

    if (!toast) return;

    if (toastTimeout) clearTimeout(toastTimeout);

    if (toastTitle) toastTitle.textContent = title;
    if (toastMsg) toastMsg.textContent = message;

    toast.className = 'p-4 rounded-2xl border transition-all duration-300 transform scale-100 shadow-xl flex items-center justify-between ';
    
    if (type === 'success') {
        toast.className += 'bg-emerald-500/10 border-emerald-500/30 text-emerald-900 dark:text-emerald-200';
        if (toastIconBg) toastIconBg.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400';
        if (toastIcon) toastIcon.className = 'fa-solid fa-circle-check';
    } else if (type === 'error') {
        toast.className += 'bg-rose-500/10 border-rose-500/30 text-rose-900 dark:text-rose-200';
        if (toastIconBg) toastIconBg.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold bg-rose-500/20 text-rose-600 dark:text-rose-400';
        if (toastIcon) toastIcon.className = 'fa-solid fa-circle-xmark';
    } else if (type === 'warning') {
        toast.className += 'bg-amber-500/10 border-amber-500/30 text-amber-900 dark:text-amber-200';
        if (toastIconBg) toastIconBg.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold bg-amber-500/20 text-amber-600 dark:text-amber-400';
        if (toastIcon) toastIcon.className = 'fa-solid fa-triangle-exclamation';
    } else {
        toast.className += 'bg-cyan-500/10 border-cyan-500/30 text-cyan-900 dark:text-cyan-200';
        if (toastIconBg) toastIconBg.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold bg-cyan-500/20 text-cyan-600 dark:text-cyan-400';
        if (toastIcon) toastIcon.className = 'fa-solid fa-circle-info';
    }

    toast.classList.remove('hidden');

    toastTimeout = setTimeout(() => {
        closeToast();
    }, 6000);
}

function closeToast() {
    const toast = document.getElementById('toast-alert');
    if (toast) {
        toast.classList.add('hidden');
    }
}

/* Kamera Scanner HTML5 Handler */
async function startCameraScanner() {
    const modal = document.getElementById('camera-modal');
    if (modal) modal.classList.remove('hidden');

    if (typeof Html5Qrcode === 'undefined') {
        showToast('Library Belum Terload', 'Scanner kamera memerlukan script HTML5 QR Code library. Pastikan perangkat terhubung ke internet.', 'error');
        return;
    }

    try {
        if (!html5QrScannerInstance) {
            html5QrScannerInstance = new Html5Qrcode("qr-reader");
        }

        if (html5QrScannerInstance.isScanning) {
            return;
        }

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            stopCameraScanner();
            autoScanPayload(decodedText);
            showToast('QR Code Terdeteksi!', `Payload QR: ${decodedText}`, 'success');
        };

        const config = {
            fps: 10,
            qrbox: { width: 220, height: 220 },
            aspectRatio: 1.0
        };

        // Try environment (rear) camera first
        try {
            await html5QrScannerInstance.start({ facingMode: "environment" }, config, qrCodeSuccessCallback, () => {});
        } catch (envErr) {
            console.warn("Environment camera failed, attempting camera fallback...", envErr);
            const devices = await Html5Qrcode.getCameras();
            if (devices && devices.length > 0) {
                const cameraId = devices[0].id;
                await html5QrScannerInstance.start(cameraId, config, qrCodeSuccessCallback, () => {});
            } else {
                await html5QrScannerInstance.start({ facingMode: "user" }, config, qrCodeSuccessCallback, () => {});
            }
        }
    } catch (err) {
        console.error("Camera Access Error:", err);
        showToast('Akses Kamera Gagal', 'Pastikan izin kamera (Camera Permission) diizinkan di browser Anda.', 'error');
        stopCameraScanner();
    }
}

function stopCameraScanner() {
    const modal = document.getElementById('camera-modal');
    if (modal) modal.classList.add('hidden');

    if (html5QrScannerInstance) {
        try {
            if (html5QrScannerInstance.isScanning) {
                html5QrScannerInstance.stop().then(() => {
                    console.log("Camera Scanner Stopped.");
                }).catch(err => console.error("Stop scanner error:", err));
            }
        } catch (e) {
            console.error("Camera stop error:", e);
        }
    }
}

async function handleScanItem(e) {
    if (e) e.preventDefault();
    const payloadInput = document.getElementById('qr_payload');
    const feedback = document.getElementById('scan-feedback');
    const scanRoute = window.RETRIEVAL_ROUTES ? window.RETRIEVAL_ROUTES.scan : '/stock/scan';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!payloadInput || !payloadInput.value.trim()) {
        showToast('Input Kosong', 'Harap masukkan payload QR Code atau Kode SKU terlebih dahulu.', 'warning');
        return;
    }

    const payload = payloadInput.value.trim();

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
            body: JSON.stringify({ payload: payload })
        });

        const data = await response.json();

        if (data.success && data.item) {
            if (feedback) feedback.classList.add('hidden');
            currentScannedItem = data.item;
            renderItemDetail(data.item);
            showToast('Barang Ditemukan!', `[${data.item.sku}] ${data.item.name} - Stok: ${data.item.available_stock} unit`, 'success');
        } else {
            if (feedback) {
                feedback.className = 'p-3 rounded-xl text-xs font-medium bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
                feedback.innerHTML = `<i class="fa-solid fa-circle-xmark mr-1"></i> ${data.message || 'Barang tidak ditemukan'}`;
            }
            showToast('Pencarian Gagal', data.message || 'Barang tidak ditemukan', 'error');
        }
    } catch (err) {
        console.error("Scan API Error:", err);
        if (feedback) {
            feedback.className = 'p-3 rounded-xl text-xs font-medium bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
            feedback.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> Terjadi kesalahan jaringan saat menghubungi server.';
        }
        showToast('Kesalahan Sistem', 'Tidak dapat terhubung ke server database gudang.', 'error');
    }
}

function autoScanPayload(payload) {
    const input = document.getElementById('qr_payload');
    if (input) {
        input.value = payload;
        handleScanItem(new Event('submit'));
    }
}

function adjustQty(delta) {
    const qtyInput = document.getElementById('quantity_picked');
    const modalQtyInput = document.getElementById('modal_quantity_picked');
    let currentVal = parseInt((modalQtyInput && modalQtyInput.value ? modalQtyInput.value : (qtyInput ? qtyInput.value : 1)) || 1);
    let newVal = currentVal + delta;
    if (newVal < 1) newVal = 1;
    if (currentScannedItem && newVal > currentScannedItem.available_stock) {
        newVal = currentScannedItem.available_stock;
        showToast('Batas Stok Maksimum', `Stok barang hanya tersedia ${currentScannedItem.available_stock} unit.`, 'warning');
    }
    if (qtyInput) qtyInput.value = newVal;
    if (modalQtyInput) modalQtyInput.value = newVal;
}

function adjustModalQty(delta) {
    adjustQty(delta);
}

function openItemModal() {
    const modal = document.getElementById('item-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeItemModal() {
    const modal = document.getElementById('item-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function renderItemDetail(item) {
    const emptyState = document.getElementById('empty-state');
    const detailCard = document.getElementById('item-detail-card');
    if (emptyState) emptyState.classList.add('hidden');
    if (detailCard) detailCard.classList.remove('hidden');

    // Populate Page Card Elements
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

    // Populate Modal Elements
    const modalItemIdInput = document.getElementById('modal-retrieval-item-id');
    const modalItemImg = document.getElementById('modal-item-image');
    const modalSkuBadge = document.getElementById('modal-item-sku-badge');
    const modalItemName = document.getElementById('modal-item-name');
    const modalItemLoc = document.getElementById('modal-item-location');
    const modalAvailableStock = document.getElementById('modal-item-available-stock');
    const modalMinimumStock = document.getElementById('modal-item-minimum-stock');

    if (modalItemIdInput) modalItemIdInput.value = item.id;
    if (modalItemImg) modalItemImg.src = item.image_url || 'https://placehold.co/300x300/1e293b/06b6d4?text=No+Photo';
    if (modalSkuBadge) modalSkuBadge.textContent = item.sku;
    if (modalItemName) modalItemName.textContent = item.name;
    if (modalItemLoc && modalItemLoc.querySelector('span')) modalItemLoc.querySelector('span').textContent = item.location_bin;
    if (modalAvailableStock) modalAvailableStock.textContent = item.available_stock;
    if (modalMinimumStock) modalMinimumStock.textContent = item.minimum_stock;

    // Reset Qty inputs to 1
    const qtyInput = document.getElementById('quantity_picked');
    if (qtyInput) qtyInput.value = 1;
    const modalQtyInput = document.getElementById('modal_quantity_picked');
    if (modalQtyInput) modalQtyInput.value = 1;

    // Badge Status Stok for both Card and Modal
    const statusBadge = document.getElementById('item-status-badge');
    const modalStatusBadge = document.getElementById('modal-item-status-badge');
    
    let statusClass = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30';
    let statusText = 'In Stock (Tersedia)';
    if (item.available_stock <= 0) {
        statusClass = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30';
        statusText = 'Out of Stock (Habis)';
    } else if (item.available_stock <= item.minimum_stock) {
        statusClass = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30';
        statusText = 'Low Stock (Menipis)';
    }
    if (statusBadge) { statusBadge.className = statusClass; statusBadge.textContent = statusText; }
    if (modalStatusBadge) { modalStatusBadge.className = statusClass; modalStatusBadge.textContent = statusText; }

    const confirmBtn = document.getElementById('btn-confirm-retrieval');
    const modalConfirmBtn = document.getElementById('modal-btn-confirm-retrieval');
    
    if (item.available_stock <= 0) {
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.className = 'w-full py-3.5 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-bold rounded-xl text-sm cursor-not-allowed';
            confirmBtn.innerHTML = '<i class="fa-solid fa-ban mr-1.5"></i> Stok Habis - Tidak Dapat Diambil';
        }
        if (modalConfirmBtn) {
            modalConfirmBtn.disabled = true;
            modalConfirmBtn.className = 'w-2/3 py-3.5 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-bold rounded-xl text-xs cursor-not-allowed';
            modalConfirmBtn.innerHTML = '<i class="fa-solid fa-ban mr-1.5"></i> Stok Habis';
        }
    } else {
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.className = 'w-full py-4 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-2xl text-sm shadow-xl shadow-sky-600/25 transition transform active:scale-[0.98]';
            confirmBtn.innerHTML = '<i class="fa-solid fa-circle-check mr-2 text-base"></i> Konfirmasi Pengambilan & Kurangi Stok Database';
        }
        if (modalConfirmBtn) {
            modalConfirmBtn.disabled = false;
            modalConfirmBtn.className = 'w-2/3 py-3.5 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-sky-600/25 transition transform active:scale-[0.98] flex items-center justify-center space-x-2';
            modalConfirmBtn.innerHTML = '<i class="fa-solid fa-circle-check text-sm"></i> <span>Konfirmasi & Kurangi Stok</span>';
        }
    }

    // Direct Popup Modal for Immediate User-Friendly Action
    openItemModal();

    if (detailCard) {
        detailCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

async function handleConfirmRetrieval(e) {
    if (e) e.preventDefault();

    const selectedSupervisorId = window.SELECTED_SPV_ID;
    const confirmRoute = window.RETRIEVAL_ROUTES ? window.RETRIEVAL_ROUTES.confirm : '/stock/confirm';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!selectedSupervisorId) {
        showToast('Supervisor Belum Dipilih', 'Silakan pilih Supervisor (SPV) penanggung jawab terlebih dahulu sebelum mengambil barang!', 'warning');
        openSpvModal();
        return;
    }

    const itemId = document.getElementById('modal-retrieval-item-id')?.value || document.getElementById('retrieval-item-id')?.value;
    const modalQty = document.getElementById('modal_quantity_picked')?.value;
    const cardQty = document.getElementById('quantity_picked')?.value;
    const quantityPicked = parseInt(modalQty || cardQty || 1);

    const modalNotes = document.getElementById('modal_notes')?.value;
    const cardNotes = document.getElementById('notes')?.value;
    const notes = modalNotes || cardNotes || '';

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
            closeItemModal();
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
                closeItemModal(); // Keep modal closed after post-confirm refresh
            }

            const modalNotesInput = document.getElementById('modal_notes');
            if (modalNotesInput) modalNotesInput.value = '';
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
