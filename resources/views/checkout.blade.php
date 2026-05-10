<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Tactile Whisper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- RESET & ROOT VARIABEL (Tema Tactile Whisper) --- */
        :root {
            --primary-pink: #f18d96;
            --primary-dark: #8c2a38;
            --text-dark: #333;
            --text-gray: #666;
            --bg-page: #f5f5f5;
            --bg-card: #ffffff; 
            --bg-input: #f8f9fa; 
            --font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-family); background-color: var(--bg-page); color: var(--text-dark); overflow-x: hidden; padding-bottom: 60px; }

        /* --- NAVBAR --- */
        .navbar {
            background-color: white; padding: 20px 40px; padding-right: 100px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); display: flex; justify-content: flex-end; gap: 40px; position: sticky; top: 0; z-index: 10; margin-bottom: 40px;
        }
        .navbar a { font-size: 16px; color: #999; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .navbar a:hover, .navbar button:hover { color: var(--primary-pink); }

        /* --- LAYOUT UTAMA --- */
        .checkout-container { max-width: 1100px; width: 100%; margin: 0 auto; padding: 0 30px; display: grid; grid-template-columns: 1fr 420px; gap: 40px; align-items: start; }

        /* --- KOLOM KIRI: FORM SECTIONS --- */
        .form-sections { display: flex; flex-direction: column; gap: 30px; }

        .section-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; font-size: 20px; font-weight: 700; color: var(--primary-dark); }
        .section-header i { font-size: 20px; }

        .form-card { background-color: var(--bg-card); border-radius: 24px; padding: 35px 40px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); }

        .input-row { display: flex; gap: 20px; margin-bottom: 20px; }
        .input-group { display: flex; flex-direction: column; flex: 1; }
        .input-group.full-width { width: 100%; }
        
        .input-group label { font-size: 14px; color: var(--text-gray); margin-bottom: 8px; margin-left: 15px; font-weight: 600; }

        .custom-input {
            background-color: var(--bg-input); border: 1px solid #eee; border-radius: 16px; padding: 14px 20px; font-size: 14px; color: var(--text-dark); outline: none; width: 100%; transition: all 0.3s ease;
        }
        .custom-input:focus { background-color: white; border-color: var(--primary-pink); box-shadow: 0 0 0 4px rgba(241, 141, 150, 0.15); }

        /* --- KOLOM KANAN: ORDER SUMMARY --- */
        .summary-card { background-color: var(--bg-card); border-radius: 24px; padding: 40px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03); position: sticky; top: 100px; }
        
        .summary-title { font-size: 22px; font-weight: 800; color: var(--text-dark); margin-bottom: 30px; text-align: center; }

        .summary-items { display: flex; flex-direction: column; gap: 20px; margin-bottom: 35px; max-height: 300px; overflow-y: auto; padding-right: 10px; }
        .summary-item { display: flex; align-items: center; gap: 15px; }

        .item-img { width: 65px; height: 65px; border-radius: 12px; overflow: hidden; background-color: #fce4e6; flex-shrink: 0; }
        .item-img img { width: 100%; height: 100%; object-fit: cover; }

        .item-info { flex: 1; }
        .item-name { font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
        .item-variant { font-size: 13px; color: var(--primary-dark); font-weight: 600; }
        .item-price { font-size: 15px; font-weight: 700; color: var(--text-dark); }

        .cost-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 15px; }
        .cost-row .label { color: var(--text-gray); }
        .cost-row .value { font-weight: 600; color: var(--text-dark); }

        .total-row { display: flex; justify-content: space-between; align-items: center; font-size: 18px; font-weight: 800; color: var(--primary-dark); border-top: 1px solid #eee; padding-top: 25px; margin-top: 10px; margin-bottom: 35px; }

        .confirm-btn {
            background-color: var(--primary-dark); color: white; border: none; border-radius: 30px; width: 100%; padding: 16px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 10px; transition: all 0.3s ease; box-shadow: 0 6px 15px rgba(140, 42, 56, 0.2);
        }
        .confirm-btn:hover { background-color: var(--primary-pink); transform: translateY(-3px); box-shadow: 0 8px 20px rgba(241, 141, 150, 0.4); }
        .confirm-btn:active { transform: translateY(0); }

        .back-to-cart-wrapper { text-align: center; margin-top: 25px; }
        .back-link { color: var(--text-gray); font-size: 14px; font-weight: 600; text-decoration: none; transition: color 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .back-link:hover { color: var(--primary-pink); }

        /* --- MODAL POP-UP STYLES --- */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(6px); opacity: 0; visibility: hidden; display: flex; justify-content: center; align-items: center; z-index: 1000; transition: opacity 0.3s ease, visibility 0.3s ease; }
        .modal-card { background-color: #ffffff; width: 100%; max-width: 420px; border-radius: 32px; padding: 45px 35px; text-align: center; position: relative; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15); transform: scale(0.8) translateY(20px); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .modal-overlay.show { opacity: 1; visibility: visible; }
        .modal-overlay.show .modal-card { transform: scale(1) translateY(0); }
        
        .close-btn { position: absolute; top: 25px; right: 25px; background: none; border: none; font-size: 20px; color: #aaa; cursor: pointer; transition: color 0.3s; }
        .close-btn:hover { color: var(--primary-dark); }

        .icon-wrapper { width: 60px; height: 60px; background-color: #fce4e6; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px auto; color: var(--primary-dark); font-size: 24px; }
        .modal-title { font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 10px; }
        .modal-subtitle { font-size: 14px; color: var(--text-gray); margin-bottom: 35px; }

        .qr-box { background-color: white; border-radius: 20px; padding: 15px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08); margin: 0 auto 35px auto; width: fit-content; display: inline-block; border: 2px solid #f8f9fa; }
        .qr-box img { width: 200px; height: 200px; border-radius: 12px; object-fit: cover; display: block; }

        .total-label { font-size: 12px; font-weight: 700; color: var(--text-gray); letter-spacing: 1px; margin-bottom: 5px; text-transform: uppercase; }
        .total-amount { font-size: 36px; font-weight: 800; color: var(--primary-dark); margin-bottom: 30px; }
        .cancel-link { display: inline-block; color: var(--text-gray); font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; transition: color 0.3s; margin-top: 15px; }
        .cancel-link:hover { color: var(--primary-pink); }

        /* --- ANIMASI JS --- */
        .anim-fade-up { opacity: 0; transform: translateY(30px); transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .anim-fade-up.show { opacity: 1; transform: translateY(0); }

        /* Custom Scrollbar untuk kotak summary item */
        .summary-items::-webkit-scrollbar { width: 6px; }
        .summary-items::-webkit-scrollbar-track { background: transparent; }
        .summary-items::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
        .summary-items::-webkit-scrollbar-thumb:hover { background: var(--primary-pink); }

        @media (max-width: 768px) { .checkout-container { grid-template-columns: 1fr; } .input-row { flex-direction: column; gap: 20px; } .summary-card { position: relative; top: 0; } }
    </style>
</head>
<body>


    <form id="checkoutForm" class="checkout-container" action="{{ route('transaksis.store') }}" method="POST">
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

            <button type="button" class="confirm-btn" style="padding: 16px; margin-bottom: 15px;" onclick="submitFinalOrder()">
                I Have Paid <i class="fa-solid fa-check"></i>
            </button>

            <a class="cancel-link" onclick="closeModal()">Cancel Payment</a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Animasi masuk berurutan untuk form dan summary
            const animItems = document.querySelectorAll('.anim-item');
            animItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('show');
                }, 100 + (index * 150));
            });
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

        function submitFinalOrder() {
            // Animasi loading pada tombol saat diklik
            const btn = document.querySelector('#qrModal .confirm-btn');
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Processing...';
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
            
            setTimeout(() => {
                document.getElementById('checkoutForm').submit();
            }, 800); // Simulasi delay pendek sebelum submit
        }
    </script>
</body>
</html>