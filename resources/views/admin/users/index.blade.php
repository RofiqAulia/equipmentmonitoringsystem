@extends('layouts.app')

@section('title', 'Manajemen User & Hak Akses - Inventory Control')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* DataTables Layout & Elements Styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1.25rem !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        color: #475569 !important;
    }
    .dark .dataTables_wrapper .dataTables_length,
    .dark .dataTables_wrapper .dataTables_filter {
        color: #cbd5e1 !important;
    }
    .dataTables_wrapper .dataTables_length select {
        padding: 0.4rem 0.8rem !important;
        border-radius: 0.75rem !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.75rem !important;
        outline: none !important;
        margin: 0 0.5rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    .dark .dataTables_wrapper .dataTables_length select {
        background-color: #0f172a !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.4rem 0.85rem !important;
        border-radius: 0.75rem !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        outline: none !important;
        margin-left: 0.5rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        transition: all 0.2s !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.2) !important;
    }
    .dark .dataTables_wrapper .dataTables_filter input {
        background-color: #0f172a !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1rem !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        color: #64748b !important;
    }
    .dark .dataTables_wrapper .dataTables_info,
    .dark .dataTables_wrapper .dataTables_paginate {
        color: #94a3b8 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem !important;
        padding: 0.3rem 0.75rem !important;
        font-weight: 700 !important;
        border: 1px solid transparent !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0284c7 !important;
        color: #ffffff !important;
        border-color: #0284c7 !important;
        border-radius: 0.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 text-xs font-extrabold">
                <i class="fa-solid fa-users-gear text-sm mr-1"></i> User & Privilege Control
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Manajemen User & Akun Sistem</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Kelola pengguna sistem, peran hak akses (Admin, Supervisor/SPV, Operator), serta kredensial akun.</p>
        </div>

        <button onclick="openCreateUserModal()" 
                class="px-5 py-3 bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-500 hover:to-cyan-500 text-white font-extrabold text-xs rounded-2xl shadow-lg shadow-sky-600/25 transition transform active:scale-95 flex items-center justify-center space-x-2 shrink-0">
            <i class="fa-solid fa-user-plus text-sm"></i>
            <span>Tambah User Baru</span>
        </button>
    </div>

    <!-- Statistics Overview Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Users -->
        <a href="{{ route('admin.users.index') }}" class="glass-panel p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-md hover:border-cyan-500/50 transition">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-500 flex items-center justify-center text-xl font-black">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalUsers }}</span>
            </div>
            <div class="mt-4">
                <div class="text-xs font-extrabold text-slate-900 dark:text-white">Total User Sistem</div>
                <div class="text-[10px] text-slate-500 dark:text-slate-400">Seluruh akun terdaftar</div>
            </div>
        </a>

        <!-- Card 2: Administrator -->
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="glass-panel p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-md hover:border-sky-500/50 transition">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-sky-500/20 text-sky-500 flex items-center justify-center text-xl font-black">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <span class="text-2xl font-black text-sky-600 dark:text-sky-400">{{ $totalAdmins }}</span>
            </div>
            <div class="mt-4">
                <div class="text-xs font-extrabold text-slate-900 dark:text-white">Administrator</div>
                <div class="text-[10px] text-slate-500 dark:text-slate-400">Akses penuh sistem</div>
            </div>
        </a>

        <!-- Card 3: Supervisor (SPV) -->
        <a href="{{ route('admin.users.index', ['role' => 'spv']) }}" class="glass-panel p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-md hover:border-pink-500/50 transition">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-pink-500/20 text-pink-500 flex items-center justify-center text-xl font-black">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <span class="text-2xl font-black text-pink-600 dark:text-pink-400">{{ $totalSpvs }}</span>
            </div>
            <div class="mt-4">
                <div class="text-xs font-extrabold text-slate-900 dark:text-white">Supervisor (SPV)</div>
                <div class="text-[10px] text-slate-500 dark:text-slate-400">Penanggung jawab barang</div>
            </div>
        </a>

        <!-- Card 4: Operator Gudang -->
        <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="glass-panel p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-md hover:border-emerald-500/50 transition">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-xl font-black">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $totalOperators }}</span>
            </div>
            <div class="mt-4">
                <div class="text-xs font-extrabold text-slate-900 dark:text-white">Operator Gudang</div>
                <div class="text-[10px] text-slate-500 dark:text-slate-400">User pemindaian / retrival</div>
            </div>
        </a>
    </div>

    <!-- User Data Table Panel -->
    <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center">
                    <i class="fa-solid fa-list-ul text-sky-600 dark:text-sky-400 mr-2"></i> Daftar Pengguna Sistem
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Menampilkan semua akun yang memiliki hak akses di aplikasi.</p>
            </div>

            @if($roleFilter)
                <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-300 transition flex items-center space-x-1.5 self-start">
                    <i class="fa-solid fa-filter-circle-xmark"></i>
                    <span>Hapus Filter Role</span>
                </a>
            @endif
        </div>

        <!-- Table Responsive Container -->
        <div class="table-responsive-touch overflow-x-auto">
            <table id="users-datatable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800">
                        <th class="py-3 px-3 text-center w-10">No</th>
                        <th class="py-3 px-4">User / Akun</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role Hak Akses</th>
                        <th class="py-3 px-4">Terdaftar Pada</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-xs font-semibold">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-3 text-center font-bold text-slate-500 dark:text-slate-400 text-xs" data-order="{{ $loop->iteration }}">
                                {{ $loop->iteration }}
                            </td>
                            <!-- Avatar + Name -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $user->avatar ?: 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0284c7&color=fff' }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-9 h-9 rounded-xl object-cover border border-slate-300 dark:border-slate-700 shadow-sm shrink-0">
                                    <div>
                                        <div class="font-extrabold text-slate-900 dark:text-white flex items-center space-x-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if(Auth::id() === $user->id)
                                                <span class="px-1.5 py-0.5 text-[9px] font-black rounded-md bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30">Anda</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono">@ {{ $user->username ?: 'user_'.$user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-3.5 px-4 text-slate-700 dark:text-slate-300">
                                <span class="font-mono text-xs">{{ $user->email }}</span>
                            </td>

                            <!-- Role Badge -->
                            <td class="py-3.5 px-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-black bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/30">
                                        <i class="fa-solid fa-shield-halved mr-1.5 text-sky-500"></i> Admin
                                    </span>
                                @elseif($user->role === 'spv')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-black bg-pink-500/10 text-pink-600 dark:text-pink-400 border border-pink-500/30">
                                        <i class="fa-solid fa-user-tie mr-1.5 text-pink-500"></i> SPV (Supervisor)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-black bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                        <i class="fa-solid fa-id-badge mr-1.5 text-emerald-500"></i> Operator Gudang
                                    </span>
                                @endif
                            </td>

                            <!-- Created Date -->
                            <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 text-[11px]">
                                {{ $user->created_at ? $user->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>

                            <!-- Actions (Detail, Edit, Hapus) -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <!-- 1. Detail -->
                                    <button onclick="openDetailUserModal({{ json_encode($user) }})" 
                                            class="p-2 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 hover:bg-cyan-500 hover:text-white transition shadow-sm"
                                            title="Lihat Detail User">
                                        <i class="fa-solid fa-circle-info text-xs"></i>
                                    </button>

                                    <!-- 2. Edit -->
                                    <button onclick="openEditUserModal({{ json_encode($user) }})" 
                                            class="p-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-sm"
                                            title="Edit User">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    <!-- 3. Hapus -->
                                    <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', {{ Auth::id() === $user->id ? 'true' : 'false' }})" 
                                            class="p-2 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-sm"
                                            title="Hapus User">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==========================================
     1. MODAL TAMBAH USER BARU
     ========================================== -->
<div id="modal-create-user" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm hidden overflow-y-auto">
    <div class="w-full max-w-lg glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-5 shadow-2xl my-auto max-h-[90vh] overflow-y-auto">
        
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-user-plus text-sky-500 mr-2"></i> Tambah User / Akun Baru
            </h3>
            <button onclick="closeCreateUserModal()" class="text-slate-400 hover:text-rose-500 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- 1. Nama Lengkap -->
            <div>
                <label for="create_name" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-user text-xs"></i>
                    </span>
                    <input type="text" name="name" id="create_name" required placeholder="Contoh: Budi Santoso"
                           class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                </div>
            </div>

            <!-- 2. Email -->
            <div>
                <label for="create_email" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">Email Akses *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-envelope text-xs"></i>
                    </span>
                    <input type="email" name="email" id="create_email" required placeholder="budi@inventory.com"
                           class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                </div>
            </div>

            <!-- 3. Role Selection (SPV / Admin / Operator) -->
            <div>
                <label for="create_role" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">Role / Hak Akses *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-user-shield text-xs"></i>
                    </span>
                    <select name="role" id="create_role" required 
                            class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                        <option value="">-- Pilih Role --</option>
                        <option value="spv">SPV (Supervisor Penanggung Jawab)</option>
                        <option value="admin">Admin (Administrator System)</option>
                        <option value="user">Operator (Operator Gudang / User)</option>
                    </select>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">SPV dan Admin memiliki akses penuh ke Dashboard Kontrol Admin.</p>
            </div>

            <!-- 4. Password -->
            <div>
                <label for="create_password" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">Password *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </span>
                    <input type="password" name="password" id="create_password" required minlength="6" placeholder="Minimal 6 karakter"
                           class="w-full pl-10 pr-10 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                    <button type="button" onclick="togglePasswordVisibility('create_password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeCreateUserModal()" 
                        class="px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-extrabold hover:bg-slate-300 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-500 hover:to-cyan-500 text-white text-xs font-black shadow-md shadow-sky-600/25 transition">
                    Simpan User Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================
     2. MODAL EDIT USER
     ========================================== -->
<div id="modal-edit-user" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm hidden overflow-y-auto">
    <div class="w-full max-w-lg glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-5 shadow-2xl my-auto max-h-[90vh] overflow-y-auto">
        
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-pen-to-square text-pink-500 mr-2"></i> Edit Data User
            </h3>
            <button onclick="closeEditUserModal()" class="text-slate-400 hover:text-rose-500 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="edit-user-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- 1. User / Akun (Username) -->
            <div>
                <label for="edit_username" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">User / Akun (Username Login) *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-at text-xs"></i>
                    </span>
                    <input type="text" name="username" id="edit_username" required placeholder="contoh: rofiq_admin"
                           class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                </div>
            </div>

            <!-- 2. Nama Lengkap -->
            <div>
                <label for="edit_name" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-user text-xs"></i>
                    </span>
                    <input type="text" name="name" id="edit_name" required
                           class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                </div>
            </div>

            <!-- 3. Email -->
            <div>
                <label for="edit_email" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">Email Akses *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-envelope text-xs"></i>
                    </span>
                    <input type="email" name="email" id="edit_email" required
                           class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                </div>
            </div>

            <!-- 4. Role Selection -->
            <div>
                <label for="edit_role" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">Role / Hak Akses *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-user-shield text-xs"></i>
                    </span>
                    <select name="role" id="edit_role" required 
                            class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                        <option value="spv">SPV (Supervisor Penanggung Jawab)</option>
                        <option value="admin">Admin (Administrator System)</option>
                        <option value="user">Operator (Operator Gudang / User)</option>
                    </select>
                </div>
            </div>

            <!-- 4. Password Baru (Optional) -->
            <div>
                <label for="edit_password" class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-1.5">Password Baru (Opsional)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </span>
                    <input type="password" name="password" id="edit_password" minlength="6" placeholder="Kosongkan jika tidak ingin mengubah password"
                           class="w-full pl-10 pr-10 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 shadow-sm">
                    <button type="button" onclick="togglePasswordVisibility('edit_password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </button>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Isi hanya jika ingin mengganti password user ini.</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeEditUserModal()" 
                        class="px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-extrabold hover:bg-slate-300 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white text-xs font-black shadow-md shadow-pink-600/25 transition">
                    Update Data User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================
     3. MODAL DETAIL USER
     ========================================== -->
<div id="modal-detail-user" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm hidden overflow-y-auto">
    <div class="w-full max-w-md glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-5 shadow-2xl my-auto">
        
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center">
                <i class="fa-solid fa-address-card text-cyan-500 mr-2"></i> Detail Informasi User
            </h3>
            <button onclick="closeDetailUserModal()" class="text-slate-400 hover:text-rose-500 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="space-y-4">
            <div class="flex items-center space-x-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <img id="detail_avatar" src="" alt="" class="w-14 h-14 rounded-2xl object-cover border-2 border-cyan-500 shadow-md">
                <div>
                    <h4 id="detail_name" class="text-base font-extrabold text-slate-900 dark:text-white"></h4>
                    <p id="detail_username" class="text-xs font-mono text-cyan-600 dark:text-cyan-400 font-bold"></p>
                    <div id="detail_role_badge" class="mt-1"></div>
                </div>
            </div>

            <div class="space-y-2.5 text-xs">
                <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Email Terdaftar:</span>
                    <span id="detail_email" class="font-bold text-slate-900 dark:text-white font-mono"></span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Role / Hak Akses:</span>
                    <span id="detail_role" class="font-bold text-slate-900 dark:text-white capitalize"></span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Tanggal Registrasi:</span>
                    <span id="detail_created_at" class="font-bold text-slate-900 dark:text-white"></span>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button onclick="closeDetailUserModal()" class="px-5 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-300 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-user-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<!-- DataTables JS Integration -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        var usersTable = $('#users-datatable').DataTable({
            language: {
                search: "Cari User:",
                lengthMenu: "Tampilkan _MENU_ user",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ user",
                infoEmpty: "Tidak ada data user",
                zeroRecords: "User tidak ditemukan",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Kembali"
                }
            },
            pageLength: -1,
            lengthMenu: [[-1, 10, 25, 50], ["Tampilkan Semua", 10, 25, 50]],
            order: [[3, 'desc']],
            columnDefs: [
                { targets: 0, orderable: false }
            ]
        });

        usersTable.on('order.dt search.dt draw.dt', function () {
            var info = usersTable.page.info();
            usersTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = (info ? info.start : 0) + i + 1;
            });
        });
    });

    function openDetailUserModal(user) {
        let avatarUrl = user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&background=0284c7&color=fff';
        $('#detail_avatar').attr('src', avatarUrl);
        $('#detail_name').text(user.name);
        $('#detail_username').text('@' + (user.username || 'user_' + user.id));
        $('#detail_email').text(user.email);
        
        let roleText = 'Operator Gudang';
        let roleBadgeHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30"><i class="fa-solid fa-id-badge mr-1"></i> Operator</span>';
        if (user.role === 'admin') {
            roleText = 'Administrator System';
            roleBadgeHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/30"><i class="fa-solid fa-shield-halved mr-1"></i> Admin</span>';
        } else if (user.role === 'spv') {
            roleText = 'Supervisor (SPV)';
            roleBadgeHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-pink-500/10 text-pink-600 dark:text-pink-400 border border-pink-500/30"><i class="fa-solid fa-user-tie mr-1"></i> Supervisor</span>';
        }
        
        $('#detail_role').text(roleText);
        $('#detail_role_badge').html(roleBadgeHtml);

        let createdAt = user.created_at ? new Date(user.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) : '-';
        $('#detail_created_at').text(createdAt);

        $('#modal-detail-user').removeClass('hidden');
    }

    function closeDetailUserModal() {
        $('#modal-detail-user').addClass('hidden');
    }

    function openCreateUserModal() {
        $('#modal-create-user').removeClass('hidden');
    }

    function closeCreateUserModal() {
        $('#modal-create-user').addClass('hidden');
    }

    function openEditUserModal(user) {
        let actionUrl = "{{ url('/admin/users') }}/" + user.id;
        $('#edit-user-form').attr('action', actionUrl);
        $('#edit_username').val(user.username || '');
        $('#edit_name').val(user.name);
        $('#edit_email').val(user.email);
        $('#edit_role').val(user.role);
        $('#edit_password').val('');
        $('#modal-edit-user').removeClass('hidden');
    }

    function closeEditUserModal() {
        $('#modal-edit-user').addClass('hidden');
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function confirmDeleteUser(userId, userName, isSelf = false) {
        if (isSelf) {
            Swal.fire({
                title: 'Akses Ditolak!',
                text: 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan!',
                icon: 'error',
                confirmButtonColor: '#0284c7',
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800'
                }
            });
            return;
        }

        Swal.fire({
            title: 'Hapus User ini?',
            text: "User \"" + userName + "\" akan dihapus permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let deleteForm = document.getElementById('delete-user-form');
                deleteForm.action = "{{ url('/admin/users') }}/" + userId;
                deleteForm.submit();
            }
        });
    }
</script>
@endpush
@endsection
