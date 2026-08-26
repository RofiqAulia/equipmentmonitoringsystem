@extends('layouts.app')

@section('title', 'Manajemen User & Hak Akses - Inventory Control')

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
                        <th class="py-3 px-4">User / Akun</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role Hak Akses</th>
                        <th class="py-3 px-4">Terdaftar Pada</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-xs font-semibold">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
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

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="openEditUserModal({{ json_encode($user) }})" 
                                            class="p-2 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 hover:bg-sky-500 hover:text-white transition shadow-sm"
                                            title="Edit User">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    @if(Auth::id() !== $user->id)
                                        <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')" 
                                                class="p-2 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-sm"
                                                title="Hapus User">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                                <p class="text-xs font-bold">Belum ada data user yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
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

            <!-- 1. Nama Lengkap -->
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

            <!-- 2. Email -->
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

            <!-- 3. Role Selection -->
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

<!-- Hidden Delete Form -->
<form id="delete-user-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<!-- DataTables & SweetAlert2 JS Integration -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#users-datatable').DataTable({
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
            pageLength: 10,
            order: [[3, 'desc']]
        });
    });

    function openCreateUserModal() {
        $('#modal-create-user').removeClass('hidden');
    }

    function closeCreateUserModal() {
        $('#modal-create-user').addClass('hidden');
    }

    function openEditUserModal(user) {
        let actionUrl = "{{ url('/admin/users') }}/" + user.id;
        $('#edit-user-form').attr('action', actionUrl);
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

    function confirmDeleteUser(userId, userName) {
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
