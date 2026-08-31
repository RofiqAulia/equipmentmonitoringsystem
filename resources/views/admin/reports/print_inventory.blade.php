<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Gudang - {{ date('d-m-Y') }}</title>
    <!-- Font & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Action bar for screen view */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #0284c7;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #0369a1;
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        /* Kop Surat & Header */
        .report-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 3px double #0f172a;
            margin-bottom: 20px;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-title img {
            height: 46px;
            width: auto;
            object-fit: contain;
        }

        .header-title h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .header-title p {
            margin: 4px 0 0 0;
            color: #64748b;
            font-size: 12px;
        }

        .header-meta {
            text-align: right;
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }

        /* Summary statistics */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
        }

        .card-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
        }

        .card-value {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2px;
        }

        /* Inventory Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        td {
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
            color: #1e293b;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: 700; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-danger { background: #ffe4e6; color: #be123c; border: 1px solid #fecdd3; }

        /* Signature block */
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: 700;
            text-decoration: underline;
            color: #0f172a;
        }

        .signature-role {
            font-size: 10px;
            color: #64748b;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 10pt;
            }
            .container {
                max-width: 100%;
                box-shadow: none;
                padding: 0;
                border-radius: 0;
            }
            .action-bar {
                display: none !important;
            }
            th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-success { background: #dcfce7 !important; color: #15803d !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-warning { background: #fef3c7 !important; color: #b45309 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-danger { background: #ffe4e6 !important; color: #be123c !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Action Bar (Hides when printing) -->
        <div class="action-bar">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>

        <!-- Kop / Header Laporan -->
        <div class="report-header">
            <div class="header-title">
                <img src="{{ asset('images/LogoMieGacoan.png') }}" alt="Logo Mie Gacoan">
                <h1>Laporan Inventaris Gudang</h1>
            </div>
            <div class="header-meta">
                <div><strong>Tanggal Cetak:</strong> {{ $printedAt }}</div>
                <div><strong>Pencetak:</strong> {{ $printedBy }}</div>
                <div><strong>Filter Status:</strong> {{ strtoupper($filterStatus) }}</div>
            </div>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="summary-cards">
            <div class="card">
                <div class="card-label">Total Jenis Barang</div>
                <div class="card-value">{{ number_format($summary['total_items']) }} SKU</div>
            </div>
            <div class="card">
                <div class="card-label">Total Unit Available</div>
                <div class="card-value">{{ number_format($summary['total_stock']) }} Unit</div>
            </div>
            <div class="card">
                <div class="card-label">Stok Menipis</div>
                <div class="card-value" style="color: #d97706;">{{ $summary['low_stock'] }} SKU</div>
            </div>
            <div class="card">
                <div class="card-label">Stok Habis</div>
                <div class="card-value" style="color: #dc2626;">{{ $summary['out_of_stock'] }} SKU</div>
            </div>
        </div>

        <!-- Tabel Data Barang -->
        <table>
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th style="width: 110px;">SKU</th>
                    <th>Nama Barang</th>
                    <th style="width: 140px;">Lokasi Gudang / Rak</th>
                    <th style="width: 90px;" class="text-center">Stok Available</th>
                    <th style="width: 80px;" class="text-center">Min. Stock</th>
                    <th style="width: 100px;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-mono font-bold">{{ $item->sku }}</td>
                        <td class="font-bold">{{ $item->name }}</td>
                        <td>{{ $item->location_bin }}</td>
                        <td class="text-center font-bold">{{ number_format($item->available_stock) }}</td>
                        <td class="text-center">{{ number_format($item->minimum_stock) }}</td>
                        <td class="text-center">
                            @if($item->available_stock <= 0)
                                <span class="badge badge-danger">Out of Stock</span>
                            @elseif($item->available_stock <= $item->minimum_stock)
                                <span class="badge badge-warning">Low Stock</span>
                            @else
                                <span class="badge badge-success">In Stock</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 20px; color: #64748b;">
                            Tidak ada data inventaris barang yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Kolom Tanda Tangan -->
        <div class="signature-section">
            <div class="signature-box">
                <div>Disetujui oleh,</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $printedBy }}</div>
                <div class="signature-role">Supervisor</div>
            </div>
        </div>
    </div>

    <script>
        // Auto print prompt when loaded if query parameter auto_print=1 is present
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auto_print') === '1') {
            window.addEventListener('load', () => {
                window.print();
            });
        }
    </script>
</body>
</html>
