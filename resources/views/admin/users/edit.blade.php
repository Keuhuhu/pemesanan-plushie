@extends('layouts.admin')

@section('pageTitle', 'Manage Customers')
@section('pageSubtitle', 'Lihat dan kelola akun pengguna terdaftar.')

@section('content')
    <div class="section-header">
        <h3>Edit Data User</h3>
        <a href="{{ route('admin.users') }}" class="approve-btn" style="background-color: #6c757d; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="form-container">
        <form action="{{ route('admin.users.update', $editUser->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" value="{{ $editUser->username }}" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $editUser->email }}" required></div>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" class="form-control" minlength="8" placeholder="Kosongkan jika tidak diubah">
                <small style="color: var(--text-secondary); font-size: 11px; margin-top: 4px; display: block;">
                    * Minimal 8 karakter. Biarkan kosong jika kamu tidak ingin mengganti password user ini.
                </small>
            </div>
                <label>Role</label>
                <div class="role-dropdown" data-target="role-edit">
                    <div class="role-dropdown-trigger">
                        <div class="role-dropdown-selected">
                            <i class="fa-solid {{ $editUser->role == 'admin' ? 'fa-shield-halved' : 'fa-user' }} role-dropdown-icon"></i>
                            <span class="role-dropdown-text">{{ $editUser->role == 'admin' ? 'Admin' : 'User' }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down role-dropdown-arrow"></i>
                    </div>
                    <ul class="role-dropdown-menu">
                        <li class="role-dropdown-option {{ $editUser->role == 'user' ? 'active' : '' }}" data-value="user" data-icon="fa-user">
                            <i class="fa-solid fa-user"></i> User
                        </li>
                        <li class="role-dropdown-option {{ $editUser->role == 'admin' ? 'active' : '' }}" data-value="admin" data-icon="fa-shield-halved">
                            <i class="fa-solid fa-shield-halved"></i> Admin
                        </li>
                    </ul>
                    <input type="hidden" name="role" id="role-edit" value="{{ $editUser->role }}" required>
                </div>
            </div>
            <button type="submit" class="btn-submit">Update Data User</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.role-dropdown').forEach(dropdown => {
            const trigger = dropdown.querySelector('.role-dropdown-trigger');
            const options = dropdown.querySelectorAll('.role-dropdown-option');
            const textEl = dropdown.querySelector('.role-dropdown-text');
            const iconEl = dropdown.querySelector('.role-dropdown-icon');
            const hiddenInput = document.getElementById(dropdown.dataset.target);

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.role-dropdown.open').forEach(d => {
                    if (d !== dropdown) d.classList.remove('open');
                });
                dropdown.classList.toggle('open');
            });

            options.forEach(option => {
                option.addEventListener('click', function() {
                    const val = this.dataset.value;
                    const icon = this.dataset.icon;
                    hiddenInput.value = val;
                    textEl.style.opacity = '0';
                    textEl.style.transform = 'translateY(-5px)';
                    iconEl.style.opacity = '0';
                    setTimeout(() => {
                        textEl.textContent = this.textContent.trim();
                        iconEl.className = 'fa-solid fa-' + icon + ' role-dropdown-icon';
                        textEl.style.opacity = '1';
                        textEl.style.transform = 'translateY(0)';
                        iconEl.style.opacity = '1';
                    }, 150);
                    options.forEach(o => o.classList.remove('active'));
                    this.classList.add('active');
                    dropdown.classList.remove('open');
                });
            });

            window.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
        });
    });
</script>
@endsection
