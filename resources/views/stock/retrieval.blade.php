@extends('layouts.app')

@section('title', 'Ambil Barang Gudang - Inventory Control System')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Page Header & Active SPV Banner -->
    @include('stock.partials.header_spv')

    <!-- Live Toast Alert Banner (Hidden by default) -->
    @include('stock.partials.toast_alert')

    <!-- Quick Sample Barcode / SKU Test Shortcuts -->
    @include('stock.partials.shortcuts')

    <!-- Scanner Input & Transaction Card Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Camera Scanner & Input Form -->
        <div class="lg:col-span-5">
            @include('stock.partials.scanner_section')
        </div>

        <!-- Right Column: Scanned Item Preview & Retrieval Form -->
        <div class="lg:col-span-7">
            @include('stock.partials.item_details')
        </div>
    </div>
</div>

<!-- Modal Dialogs (Camera QR & SPV Selection) -->
@include('stock.partials.modals')
@endsection

@push('scripts')
<!-- HTML5 QR Code Reader Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
    window.SELECTED_SPV_ID = "{{ Auth::user()->supervisor_id ?? '' }}";
    window.RETRIEVAL_ROUTES = {
        scan: "{{ route('stock.scan') }}",
        confirm: "{{ route('stock.confirm') }}",
        selectSpv: "{{ route('user.select-spv') }}"
    };
</script>
<script src="{{ asset('js/retrieval.js') }}"></script>
@endpush
