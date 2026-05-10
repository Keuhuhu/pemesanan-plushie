@extends('layouts.admin')

@section('pageTitle', 'Approved Orders')
@section('pageSubtitle', 'Pantau arus transaksi masuk tokomu.')

@section('content')
    <div class="section-header">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 30px; margin-top: 15px; width: 100%;">
        <div style="background: white; padding: 24px 30px; border-radius: 12px; border-left: 5px solid #28a745; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: center;">
            <p style="margin: 0 0 8px 0; color: #8a92a6; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Sukses</p>
            <h2 style="margin: 0; color: #232d3f; font-size: 36px; font-weight: 800;">{{ \App\Models\Transaksi::where('status', 'sukses')->count() }}</h2>
        </div>
        <div style="background: white; padding: 24px 30px; border-radius: 12px; border-left: 5px solid #ffc107; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: center;">
            <p style="margin: 0 0 8px 0; color: #8a92a6; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Menunggu Persetujuan</p>
            <h2 style="margin: 0; color: #232d3f; font-size: 36px; font-weight: 800;">{{ \App\Models\Transaksi::where('status', 'pending')->count() }}</h2>
        </div>
        <div style="background: white; padding: 24px 30px; border-radius: 12px; border-left: 5px solid #dc3545; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: center;">
            <p style="margin: 0 0 8px 0; color: #8a92a6; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Ditolak</p>
            <h2 style="margin: 0; color: #232d3f; font-size: 36px; font-weight: 800;">{{ \App\Models\Transaksi::where('status', 'ditolak')->count() }}</h2>
        </div>
    </div>
    </div>

    <table class="data-table">
        <thead>
            <tr><th>INVOICE</th><th>CUSTOMER</th><th>TOTAL PRICE</th><th>STATUS</th><th>ACTION</th></tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->invoice }}</td>
                <td style="display:flex; align-items:center; gap:10px;">
                    <div class="customer-avatar">{{ strtoupper(substr($order->user?->username ?? '?', 0, 1)) }}</div>
                    {{ $order->user?->username ?? 'Deleted User' }}
                </td>
                <td style="font-weight:600;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                <td>
                    <span class="status-badge {{ $order->status == 'pending' ? 'pending' : ($order->status == 'ditolak' ? 'rejected' : 'delivered') }}" 
                            style="{{ $order->status == 'ditolak' ? 'background-color: #fce4e6; color: #dc3545;' : '' }}">
                        {{ strtoupper($order->status) }}
                    </span>
                </td>
                <td>
                    @if($order->status == 'pending')
                        <div style="display: flex; gap: 8px;">
                            <form action="{{ route('admin.approve', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="approve-btn btn-green" title="Setujui Pesanan"><i class="fa-solid fa-check"></i></button>
                            </form>
                            <form action="{{ route('admin.reject', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak transaksi ini?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="approve-btn btn-red" title="Tolak Pesanan"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        </div>
                    @elseif($order->status == 'ditolak')
                        <span style="color:#dc3545; font-size:11px; font-weight:700;"><i class="fa-solid fa-ban"></i> Ditolak</span>
                    @else
                        <span style="color:#28a745; font-size:11px; font-weight:700;"><i class="fa-solid fa-check-double"></i> Verified</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;">Tidak ada data transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
