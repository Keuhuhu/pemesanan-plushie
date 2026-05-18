@extends('layouts.admin')

@section('pageTitle', 'All Transactions')
@section('pageSubtitle', 'Pantau arus transaksi masuk tokomu.')

@section('content')
    <div class="section-header">
        {{-- Baris 1: 3 Kotak Status --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 20px; margin-top: 15px; width: 100%;">
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

        {{-- Baris 2: 2 Kotak Pendapatan (full-width di bawah) --}}
        <div style="display: flex; gap: 24px; margin-bottom: 30px; width: 100%;">

            {{-- Kotak: Total Pendapatan Sepanjang Masa --}}
            <div style="flex: 1; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%); padding: 28px 36px; border-radius: 14px; box-shadow: 0 8px 32px rgba(15,52,96,0.25); display: flex; align-items: center; gap: 24px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -20px; right: -20px; width: 140px; height: 140px; background: rgba(255,255,255,0.04); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30px; right: 80px; width: 90px; height: 90px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
                <div style="background: rgba(255,215,0,0.15); border: 1px solid rgba(255,215,0,0.3); border-radius: 14px; padding: 16px; flex-shrink: 0;">
                    <i class="fa-solid fa-coins" style="color: #ffd700; font-size: 30px;"></i>
                </div>
                <div style="z-index: 1;">
                    <p style="margin: 0 0 8px 0; color: rgba(255,255,255,0.6); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Total Pendapatan Sepanjang Masa</p>
                    <h2 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 800; letter-spacing: -0.5px;">
                        Rp {{ number_format($totalPendapatanSepanjangMasa, 0, ',', '.') }}
                    </h2>
                    <p style="margin: 8px 0 0 0; color: rgba(255,215,0,0.7); font-size: 12px; font-weight: 600;">
                        <i class="fa-solid fa-circle-check" style="margin-right: 4px;"></i> Dari seluruh transaksi sukses
                    </p>
                </div>
            </div>

            {{-- Kotak: Pendapatan Bulan Ini --}}
            <div style="flex: 1; background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 60%, #40916c 100%); padding: 28px 36px; border-radius: 14px; box-shadow: 0 8px 32px rgba(27,67,50,0.3); display: flex; align-items: center; gap: 24px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -20px; right: -20px; width: 140px; height: 140px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30px; right: 80px; width: 90px; height: 90px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
                <div style="background: rgba(144,238,144,0.15); border: 1px solid rgba(144,238,144,0.3); border-radius: 14px; padding: 16px; flex-shrink: 0;">
                    <i class="fa-solid fa-calendar-check" style="color: #90ee90; font-size: 30px;"></i>
                </div>
                <div style="z-index: 1;">
                    <p style="margin: 0 0 8px 0; color: rgba(255,255,255,0.6); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Pendapatan Bulan Ini &mdash; {{ \Illuminate\Support\Carbon::now()->translatedFormat('F Y') }}</p>
                    <h2 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 800; letter-spacing: -0.5px;">
                        Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                    </h2>
                    <p style="margin: 8px 0 0 0; color: rgba(144,238,144,0.8); font-size: 12px; font-weight: 600;">
                        <i class="fa-solid fa-arrow-trend-up" style="margin-right: 4px;"></i> Transaksi sukses {{ \Illuminate\Support\Carbon::now()->translatedFormat('F') }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd;">
        <form action="{{ route('admin.all') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <div>
                <label style="font-size: 11px; font-weight: bold; display: block; margin-bottom: 5px;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" style="padding: 8px;" value="{{ request('start_date') }}">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: bold; display: block; margin-bottom: 5px;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" style="padding: 8px;" value="{{ request('end_date') }}">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: bold; display: block; margin-bottom: 5px;">Status</label>
                <select name="status_filter" class="form-control" style="padding: 8px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sukses" {{ request('status_filter') == 'sukses' ? 'selected' : '' }}>Sukses</option>
                </select>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="approve-btn btn-green" style="padding: 9px 15px;"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('admin.all') }}" class="approve-btn" style="background-color: #6c757d; text-decoration: none; padding: 9px 15px;"><i class="fa-solid fa-rotate-right"></i> Reset</a>
            </div>
            <div style="margin-left: auto; display: flex; gap: 8px;">
                <a href="{{ route('admin.export', request()->all()) }}" class="approve-btn" style="background-color: #17a2b8; text-decoration: none; padding: 9px 15px; display: inline-block;">
                    <i class="fa-solid fa-file-export"></i> Export Laporan (.csv)
                </a>
                <a href="{{ route('admin.export.pdf', request()->all()) }}" class="approve-btn" style="background-color: #b81717; text-decoration: none; padding: 9px 15px;">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('admin.export.excel', request()->all()) }}" class="approve-btn" style="background-color: #00b62a; text-decoration: none; padding: 9px 15px;">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
            </div>
        </form>
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
