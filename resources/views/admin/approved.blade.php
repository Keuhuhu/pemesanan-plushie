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
            <tr><th>INVOICE</th><th>CUSTOMER</th><th>TOTAL PRICE</th><th>STATUS</th><th>BUKTI</th><th>ACTION</th></tr>
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

                {{-- Kolom Bukti Pembayaran --}}
                <td>
                    @if($order->bukti_pembayaran)
                        <button type="button"
                            class="approve-btn"
                            style="background:#6c3fc5; color:white; border:none; border-radius:8px; padding:7px 12px; cursor:pointer; font-size:12px; display:inline-flex; align-items:center; gap:5px;"
                            onclick="showBukti('{{ $order->bukti_pembayaran }}', '{{ $order->invoice }}')">
                            <i class="fa-solid fa-image"></i> Detail
                        </button>
                    @else
                        <span style="color:#aaa; font-size:11px; font-style:italic;">Belum ada</span>
                    @endif
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
            <tr><td colspan="6" style="text-align:center;">Tidak ada data transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===== MODAL BUKTI PEMBAYARAN ===== --}}
    <div id="buktiModal" style="
        display:none; position:fixed; inset:0; z-index:9999;
        background:rgba(0,0,0,0.65); backdrop-filter:blur(4px);
        align-items:center; justify-content:center;">
        <div style="
            background:#fff; border-radius:20px; padding:32px 28px;
            max-width:520px; width:90%; position:relative;
            box-shadow:0 25px 60px rgba(0,0,0,0.25); animation: fadeUp 0.25s ease;">
            <button onclick="closeBukti()" style="
                position:absolute; top:14px; right:16px; background:none;
                border:none; font-size:20px; cursor:pointer; color:#888;">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px;">
                <div style="background:#f0e8ff; border-radius:10px; padding:10px 12px;">
                    <i class="fa-solid fa-image" style="color:#6c3fc5; font-size:18px;"></i>
                </div>
                <div>
                    <p style="margin:0; font-size:11px; color:#888; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Bukti Pembayaran</p>
                    <p id="buktiInvoice" style="margin:0; font-size:15px; font-weight:700; color:#232d3f;"></p>
                </div>
            </div>
            <div style="border-radius:12px; overflow:hidden; border:1px solid #ede8f5;">
                <img id="buktiImg" src="" alt="Bukti Pembayaran"
                    style="width:100%; max-height:400px; object-fit:contain; display:block; background:#f9f4ff;">
            </div>
            <button id="buktiDownload" onclick="downloadBukti()"
                style="display:inline-flex; align-items:center; gap:7px; margin-top:16px;
                background:#6c3fc5; color:white; padding:10px 20px; border-radius:10px;
                border:none; cursor:pointer; font-size:13px; font-weight:600;">
                <i class="fa-solid fa-download"></i> Download Gambar
            </button>
        </div>
    </div>

    <style>
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }
    </style>

    <script>
        let currentBuktiSrc = '';
        let currentBuktiInvoice = '';

        function showBukti(src, invoice) {
            currentBuktiSrc = src;
            currentBuktiInvoice = invoice;
            document.getElementById('buktiImg').src = src;
            document.getElementById('buktiInvoice').textContent = invoice;
            document.getElementById('buktiModal').style.display = 'flex';
        }
        function closeBukti() {
            document.getElementById('buktiModal').style.display = 'none';
        }
        function downloadBukti() {
            const btn = document.getElementById('buktiDownload');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengunduh...';
            btn.disabled = true;

            fetch(currentBuktiSrc)
                .then(res => res.blob())
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'bukti_' + currentBuktiInvoice + '.jpg';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                })
                .catch(() => alert('Gagal mengunduh gambar.'))
                .finally(() => {
                    btn.innerHTML = '<i class="fa-solid fa-download"></i> Download Gambar';
                    btn.disabled = false;
                });
        }
        document.getElementById('buktiModal').addEventListener('click', function(e) {
            if (e.target === this) closeBukti();
        });
    </script>
@endsection
