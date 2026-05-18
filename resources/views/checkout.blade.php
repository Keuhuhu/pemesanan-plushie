<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user.css'])
</head>
<body class="page-checkout">


    <form id="checkoutForm" class="checkout-container" action="{{ route('transaksis.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-sections">
            <div class="anim-fade-up anim-item">
                <div class="section-header">
                    <i class="fa-solid fa-truck-fast"></i> Shipping Details
                </div>
                
                <div class="form-card">
                    <div class="input-row">
                        <div class="input-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="custom-input" placeholder="" required>
                        </div>
                        <div class="input-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="custom-input" placeholder="" required>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-group full-width">
                            <label>Street Address</label>
                            <input type="text" name="address" class="custom-input" placeholder="" required>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-group">
                            <label>City</label>
                            <input type="text" name="city" class="custom-input" placeholder="" required>
                        </div>
                        <div class="input-group">
                            <label>Postal Code</label>
                            <input type="text" name="postal_code" class="custom-input" placeholder="" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="anim-fade-up anim-item">
                <div class="section-header">
                    <i class="fa-regular fa-credit-card"></i> Payment Method
                </div>
                
                <div class="form-card">
                    <div class="input-row">
                        <div class="input-group full-width">
                            <label>Name on Card</label>
                            <input type="text" name="card_name" class="custom-input" placeholder="" required>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-group full-width">
                            <label>Card Number</label>
                            <input type="text" name="card_number" class="custom-input" placeholder="0000 0000 0000 0000" required>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-group">
                            <label>Expiration Date</label>
                            <input type="text" name="expiry_date" class="custom-input" placeholder="MM/YY" required>
                        </div>
                        <div class="input-group">
                            <label>CVC</label>
                            <input type="text" name="cvv" class="custom-input" placeholder="CVV" required maxlength="3">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="summary-card anim-fade-up anim-item">
            <h2 class="summary-title">Order Summary</h2>
            
            <div class="summary-items">
                @foreach($carts as $cart)
                <div class="summary-item">
                    <div class="item-img">
                        @if($cart->product->gambar)
                            @if(\Illuminate\Support\Str::startsWith($cart->product->gambar, ['http://', 'https://']))
                                <img src="{{ $cart->product->gambar }}" alt="{{ $cart->product->nama }}">
                            @else
                                <img src="{{ asset('images/' . $cart->product->gambar) }}" alt="{{ $cart->product->nama }}">
                            @endif
                        @else
                            <img src="https://via.placeholder.com/100x100/fce4e6/8c2a38?text={{ urlencode(substr($cart->product->nama, 0, 3)) }}" alt="{{ $cart->product->nama }}">
                        @endif
                    </div>
                    <div class="item-info">
                        <div class="item-name">{{ $cart->product->nama }}</div>
                        <div class="item-variant">Qty: {{ $cart->kuantitas }}</div>
                    </div>
                    <div class="item-price">Rp {{ number_format($cart->product->harga * $cart->kuantitas, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>

            <div class="cost-row">
                <span class="label">Subtotal</span>
                <span class="value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="cost-row">
                <span class="label">Shipping</span>
                <span class="value" style="color: #28a745;">Free Delivery</span>
            </div>
            <div class="cost-row">
                <span class="label">Taxes</span>
                <span class="value">Included</span>
            </div>

            <div class="total-row">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <button type="button" class="confirm-btn" onclick="openModal()">
                <i class="fa-solid fa-lock"></i> Confirm Purchase
            </button>

            <div class="back-to-cart-wrapper">
                <a href="{{ url('/cart') }}" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Cart
                </a>
            </div>
        </div>

        {{-- INPUT FILE DI DALAM FORM (wajib agar ikut tersubmit) --}}
        <input type="file" name="bukti_pembayaran" id="buktiInput"
               accept="image/jpeg,image/png,image/webp"
               style="display:none;"
               onchange="previewImage(event)">
    </form>

    <div class="modal-overlay" id="qrModal">
        <div class="modal-card">
            <button class="close-btn" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>

            <div class="icon-wrapper">
                <i class="fa-solid fa-qrcode"></i>
            </div>

            <h2 class="modal-title">Scan to pay</h2>
            <p class="modal-subtitle">Open your digital wallet app to complete the purchase.</p>

            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=Pembayaran+Tactile+Whisper+Rp{{ $total }}" alt="QR Code">
            </div>

            <div class="total-label">TOTAL TO PAY</div>
            <div class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</div>

            {{-- ====== UPLOAD BUKTI PEMBAYARAN ====== --}}
            <div class="upload-section" id="uploadSection">
                <p class="upload-label"><i class="fa-solid fa-image"></i> Upload Bukti Pembayaran</p>
                <div class="upload-dropzone" id="dropzone" onclick="document.getElementById('buktiInput').click()">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Klik atau drag foto ke sini</span>
                        <small>JPG, PNG, WEBP &mdash; Maks. 5MB</small>
                    </div>
                    <img id="previewImg" src="" alt="Preview"
                         style="display:none; width:100%; max-height:180px; object-fit:contain; border-radius:8px;">
                </div>
                {{-- Input file sudah ada di dalam <form> di atas --}}
                <p class="upload-error" id="uploadError"
                   style="display:none; color:#e53e3e; font-size:12px; margin-top:6px;"></p>

                @error('bukti_pembayaran')
                <p style="color:#e53e3e; font-size:12px; margin-top:6px;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </p>
                @enderror
            </div>
            {{-- ======================================= --}}

            <button type="button" class="confirm-btn" style="padding: 16px; margin-bottom: 15px;" onclick="submitFinalOrder()">
                I Have Paid <i class="fa-solid fa-check"></i>
            </button>

            <a class="cancel-link" onclick="closeModal()">Cancel Payment</a>
        </div>
    </div>

    <style>
        /* ======== UPLOAD BUKTI PEMBAYARAN ======== */
        .upload-section {
            width: 100%;
            margin: 16px 0 12px 0;
            text-align: left;
        }
        .upload-label {
            font-size: 13px;
            font-weight: 700;
            color: #4a3060;
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .upload-dropzone {
            border: 2px dashed #c4b0d8;
            border-radius: 12px;
            background: #f9f4ff;
            cursor: pointer;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 110px;
            transition: border-color 0.2s, background 0.2s;
            position: relative;
            overflow: hidden;
        }
        .upload-dropzone:hover, .upload-dropzone.dragover {
            border-color: #7c3aed;
            background: #f0e8ff;
        }
        .upload-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: #9b7ec8;
            pointer-events: none;
        }
        .upload-placeholder i {
            font-size: 28px;
            color: #7c3aed;
        }
        .upload-placeholder span {
            font-size: 13px;
            font-weight: 600;
        }
        .upload-placeholder small {
            font-size: 11px;
            color: #b39ddb;
        }
        /* ======================================== */
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Animasi masuk berurutan untuk form dan summary
            const animItems = document.querySelectorAll('.anim-item');
            animItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('show');
                }, 100 + (index * 150));
            });

            // Buka modal otomatis jika ada error validasi dari server
            @if($errors->has('bukti_pembayaran'))
                document.getElementById('qrModal').classList.add('show');
            @endif

            // Drag & drop support
            const dropzone = document.getElementById('dropzone');
            if (dropzone) {
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('dragover');
                });
                dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('dragover');
                    const file = e.dataTransfer.files[0];
                    if (file) setPreview(file);
                });
            }
        });

        function openModal() {
            const form = document.getElementById('checkoutForm');
            if (form.checkValidity()) {
                document.getElementById('qrModal').classList.add('show');
            } else {
                form.reportValidity();
            }
        }

        function closeModal() {
            document.getElementById('qrModal').classList.remove('show');
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) setPreview(file);
        }

        function setPreview(file) {
            // Sync ke input file asli jika drop
            const input = document.getElementById('buktiInput');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;

            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('uploadPlaceholder').style.display = 'none';
                const preview = document.getElementById('previewImg');
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
            document.getElementById('uploadError').style.display = 'none';
        }

        function submitFinalOrder() {
            const input = document.getElementById('buktiInput');
            const errorEl = document.getElementById('uploadError');

            // Validasi: wajib upload bukti
            if (!input || !input.files || input.files.length === 0) {
                errorEl.textContent = 'Wajib upload foto bukti pembayaran sebelum melanjutkan.';
                errorEl.style.display = 'block';
                return;
            }

            // Validasi ukuran file < 5MB
            if (input.files[0].size > 5 * 1024 * 1024) {
                errorEl.textContent = 'Ukuran file melebihi 5MB. Pilih gambar yang lebih kecil.';
                errorEl.style.display = 'block';
                return;
            }

            errorEl.style.display = 'none';

            // Animasi loading pada tombol saat diklik
            const btn = document.querySelector('#qrModal .confirm-btn');
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
            
            setTimeout(() => {
                document.getElementById('checkoutForm').submit();
            }, 800);
        }
    </script>
</body>
</html>