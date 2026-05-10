@extends('layouts.admin')

@section('pageTitle', 'Manage Customers')
@section('pageSubtitle', 'Lihat dan kelola akun pengguna terdaftar.')

@section('content')
    <div class="section-header">
        <h3>Daftar User</h3>
        <a href="{{ route('admin.users.create') }}" class="approve-btn btn-green" style="text-decoration: none; font-size: 13px; padding: 10px 15px;">
            <i class="fa-solid fa-plus"></i> Add New User
        </a>
    </div>
    <table class="data-table">
        <thead>
            <tr><th>ID</th><th>USERNAME</th><th>EMAIL</th><th>ROLE</th><th>ACTION</th></tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td style="display:flex; align-items:center; gap:10px;">
                    <div class="customer-avatar">{{ strtoupper(substr($u->username, 0, 1)) }}</div> {{ $u->username }}
                </td>
                <td>{{ $u->email }}</td>
                <td><span class="status-badge {{ $u->role == 'admin' ? 'delivered' : 'pending' }}">{{ strtoupper($u->role) }}</span></td>
                <td style="display: flex; gap: 8px;">
                    <a href="{{ route('admin.users.edit', $u->id) }}" class="approve-btn btn-yellow" style="text-decoration: none; padding: 8px 12px;"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="approve-btn btn-red" style="padding: 8px 12px;"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
