<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengambilan Barang Gudang - Mie Gacoan</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                font-size: 11pt;
            }
            .print-border {
                border-color: #cbd5e1 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen p-4 md:p-8 font-sans">

    <!-- Top Action Floating Toolbar (Hidden during Print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 flex items-center justify-between bg-slate-900 text-white p-4 rounded-2xl shadow-xl">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-rose-600 flex items-center justify-center text-white font-bold">
                <i class="fa-solid fa-file-pdf text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-sm">Pratinjau Cetak Laporan Pengambilan</h3>
                <p class="text-xs text-slate-400">Siap diunduh / dicetak ke PDF atau Printer</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition">
                <i class="fa-solid fa-xmark mr-1.5"></i> Tutup
            </button>
            <button onclick="window.print()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-lg flex items-center border border-slate-700">
                <i class="fa-solid fa-print mr-2"></i> Cetak Tabel
            </button>
        </div>
    </div>

    <!-- Printable Paper Sheet Container -->
    <div class="max-w-5xl mx-auto bg-white p-8 md:p-12 rounded-3xl shadow-2xl print-border border border-slate-200 space-y-6">
        
        <!-- REPORT HEADER: LOGO MIE GACOAN & SYSTEM DETAILS -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pb-6 border-b-2 border-slate-900">
            <!-- Left Logo Mie Gacoan -->
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/LogoMieGacoan.png') }}" alt="Logo Mie Gacoan" class="h-20 w-auto object-contain">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-slate-900 uppercase">MIE GACOAN</h1>
                    <p class="text-xs font-bold text-rose-600 uppercase tracking-wider">Inventory Control & Warehouse Management System</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">PT Pesta Pora Abadi &bull; Logistik & Pengendalian Stok Gudang</p>
                </div>
            </div>

            <!-- Right Title & Date Metadata -->
            <div class="text-center sm:text-right space-y-1">
                <div class="inline-block px-3 py-1 bg-slate-900 text-white text-xs font-black uppercase tracking-wider rounded-lg">
                    Laporan Resmi Pengambilan Barang
                </div>
                <div class="text-xs font-semibold text-slate-600 pt-1">
                    Periode Filter: <span class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
                </div>
                <div class="text-[10px] text-slate-500">
                    Dicetak Pada: {{ $printedAt }}
                </div>
            </div>
        </div>

        <!-- FILTER SUMMARY METADATA BANNER -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs">
            <div>
                <span class="text-slate-500 uppercase font-semibold block text-[10px]">Barang / Item Filtered:</span>
                <span class="font-bold text-slate-900 text-sm">
                    {{ $selectedItem ? '[' . $selectedItem->sku . '] ' . $selectedItem->name : 'Semua Barang / Item' }}
                </span>
            </div>

            <div>
                <span class="text-slate-500 uppercase font-semibold block text-[10px]">Total Transaksi Pengambilan:</span>
                <span class="font-bold text-slate-900 text-sm">{{ count($logs) }} Transaksi</span>
            </div>

            <div>
                <span class="text-slate-500 uppercase font-semibold block text-[10px]">Total Barang Diambil:</span>
                <span class="font-bold text-rose-600 text-sm">{{ number_format($totalQtyPicked) }} Unit</span>
            </div>
        </div>

        <!-- MAIN TABLE REPORT (EXACT 5 COLUMNS REQUESTED) -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-900 text-white uppercase text-[11px] tracking-wider font-bold">
                        <th class="border border-slate-800 px-3 py-3 text-center w-12">1. Nomor</th>
                        <th class="border border-slate-800 px-4 py-3">2. Item</th>
                        <th class="border border-slate-800 px-4 py-3 text-center min-w-[150px]">3. Tanggal Pengambilan</th>
                        <th class="border border-slate-800 px-4 py-3">4. SPV Saat Mengambil Barang</th>
                        <th class="border border-slate-800 px-4 py-3 text-center min-w-[130px]">5. Jumlah Barang Diambil</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($logs as $index => $log)
                        <tr class="{{ $loop->even ? 'bg-slate-50/60' : 'bg-white' }}">
                            <!-- 1. Nomor -->
                            <td class="border border-slate-300 px-3 py-3 text-center font-bold text-slate-600">
                                {{ $index + 1 }}
                            </td>

                            <!-- 2. Item -->
                            <td class="border border-slate-300 px-4 py-3">
                                <div class="font-bold text-slate-900">{{ $log->item->name ?? 'N/A' }}</div>
                                <div class="text-[10px] font-mono text-cyan-700 font-bold">SKU: {{ $log->item->sku ?? '-' }}</div>
                            </td>

                            <!-- 3. Tanggal Pengambilan (real-time) -->
                            <td class="border border-slate-300 px-4 py-3 text-center font-mono whitespace-nowrap">
                                <div class="font-bold text-slate-800">
                                    {{ optional($log->picked_at ?? $log->created_at)->setTimezone('Asia/Jakarta')->format('d M Y') }}
                                </div>
                                <div class="text-[10px] text-slate-500">
                                    {{ optional($log->picked_at ?? $log->created_at)->setTimezone('Asia/Jakarta')->format('H:i:s') }} WIB
                                </div>
                            </td>

                            <!-- 4. SPV saat mengambil barang -->
                            <td class="border border-slate-300 px-4 py-3">
                                <span class="font-semibold text-slate-900">
                                    {{ $log->supervisor->name ?? 'SPV Authorized' }}
                                </span>
                                @if($log->supervisor && $log->supervisor->email)
                                    <div class="text-[10px] text-slate-400">{{ $log->supervisor->email }}</div>
                                @endif
                            </td>

                            <!-- 5. Jumlah barang diambil berdasarkan filtering -->
                            <td class="border border-slate-300 px-4 py-3 text-center font-black text-rose-600 text-sm whitespace-nowrap">
                                {{ $log->quantity_picked }} Unit
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-slate-300 px-4 py-8 text-center text-slate-400 font-medium italic">
                                Tidak ada transaksi pengambilan barang pada filter tanggal & item ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100 text-slate-900 font-bold border-t-2 border-slate-900 text-xs">
                        <td colspan="4" class="border border-slate-300 px-4 py-3 text-right uppercase tracking-wider font-extrabold">
                            TOTAL JUMLAH BARANG DIAMBIL:
                        </td>
                        <td class="border border-slate-300 px-4 py-3 text-center font-black text-rose-600 text-sm">
                            {{ number_format($totalQtyPicked) }} Unit
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>



        <!-- Footer Notice -->
        <div class="text-[10px] text-slate-400 text-center pt-4 border-t border-slate-100">
            Dokumen ini diterbitkan secara resmi oleh Sistem Inventory Control Mie Gacoan. Validitas data terenkripsi & tersinkronisasi otomatis dengan Database Server.
        </div>

    </div>

    <!-- Auto Print Script on Load if triggered with print param -->
    @if(request()->has('autoprint'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
    @endif

</body>
</html>
