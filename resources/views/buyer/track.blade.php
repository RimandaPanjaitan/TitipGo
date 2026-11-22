@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="font-serif-title text-2xl font-bold text-[#522238]">Lacak Pesanan & Bukti Resi</h1>
    @foreach($orders as $order)
        <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-4 shadow-2xs">
            <div class="flex flex-col sm:flex-row justify-between gap-2">
                <div>
                    <span class="text-xs font-extrabold text-[#D81B60]">{{ $order->order_code }} • {{ $order->item->estimated_shipping }}</span>
                    <h3 class="font-bold text-base">{{ $order->item->name }} ({{ $order->quantity }}x)</h3>
                </div>
                <div class="flex items-center gap-2">
                    @if($order->payment_status === 'Menunggu Pembayaran')
                        <a href="{{ route('buyer.payment', $order->id) }}" class="px-4 py-2 bg-[#EC407A] text-white text-xs font-extrabold rounded-full">Bayar Sekarang</a>
                    @else
                        <button type="button"
                            onclick="openReceiptPopup('{{ $order->order_code }}', '{{ addslashes($order->buyer_name) }}', '{{ addslashes($order->item->name) }} ({{ $order->quantity }}x)', '{{ $order->payment_method }}', 'Rp {{ number_format($order->total_price, 0, ',', '.') }}', '{{ $order->created_at->format('d M Y, H:i') }} WIB')"
                            class="px-4 py-2 bg-[#FCE4EC] hover:bg-[#F8BBD0] text-[#C2185B] text-xs font-extrabold rounded-full flex items-center gap-1.5 transition">
                            <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                            <span>Lihat Resi</span>
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                @foreach([1 => '1. Dibeli di Asal', 2 => '2. Bea Cukai & Terbang', 3 => '3. Kurir Lokal Antar', 4 => '4. Selesai Diterima'] as $step => $label)
                    <div class="p-2.5 rounded-2xl border {{ $order->step_status >= $step ? 'bg-[#FCE4EC] border-[#F48FB1] font-extrabold text-[#880E4F]' : 'bg-[#FFF7FA] border-[#FAD2E1] opacity-55' }}">
                        {{ $label }}
                    </div>
                @endforeach
            </div>

            @if($order->step_status >= 3 && $order->courier_vehicle)
                <div class="bg-[#FFF0F5] border border-[#F8BBD0] p-3.5 rounded-2xl flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <i data-lucide="bike" class="w-5 h-5 text-[#D81B60]"></i>
                        <div>
                            <p class="font-extrabold">Kurir Lokal Menuju Alamat Anda</p>
                            <p class="opacity-80">Kendaraan: <strong>{{ $order->courier_vehicle }}</strong> • Plat Nomor: <strong class="bg-white px-2 py-0.5 rounded border border-[#F8BBD0]">{{ $order->courier_plate }}</strong></p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>

<!-- POP-UP MODAL RESI IN-APP -->
<div id="inAppReceiptModal" class="fixed inset-0 z-50 bg-[#4A152C]/40 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-[2rem] border-2 border-[#F8BBD0] shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-[#F8BBD0] to-[#F06292] px-6 py-4 text-white flex items-center justify-between">
            <span class="text-xs font-extrabold uppercase flex items-center gap-1.5"><i data-lucide="receipt" class="w-4 h-4"></i> Bukti Resi Pembayaran</span>
            <button type="button" onclick="closeReceiptPopup()" class="w-7 h-7 rounded-full bg-white/25 hover:bg-white/40 flex items-center justify-center">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div id="popupReceiptCapture" class="p-7 bg-white space-y-4">
            <div class="text-center border-b-2 border-dashed border-[#F8BBD0] pb-4">
                <span class="px-3 py-1 bg-[#FCE4EC] text-[#C2185B] text-[11px] font-extrabold rounded-full">PEMBAYARAN LUNAS</span>
                <h3 class="font-serif-title text-2xl font-bold text-[#522238] mt-2">Bukti Resi TitipGo</h3>
                <p id="popCodeDate" class="text-xs font-mono text-[#8E4A67] mt-1"></p>
            </div>
            <div class="space-y-2.5 text-xs text-[#522238]">
                <div class="flex justify-between"><span class="opacity-70">Pemesan:</span><strong id="popBuyer"></strong></div>
                <div class="flex justify-between gap-4"><span class="opacity-70 shrink-0">Makanan Dipesan:</span><strong id="popFood" class="text-right"></strong></div>
                <div class="flex justify-between"><span class="opacity-70">Metode Pembayaran:</span><strong id="popMethod"></strong></div>
                <div class="flex justify-between text-base font-black text-[#D81B60] pt-3 border-t border-dashed border-[#F8BBD0]">
                    <span>Total Dibayarkan:</span>
                    <span id="popTotal"></span>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6 pt-1 flex gap-3">
            <button type="button" onclick="downloadPopupReceipt()" class="flex-1 py-3 bg-[#EC407A] hover:bg-[#D81B60] text-white font-extrabold text-xs rounded-full shadow flex items-center justify-center gap-1.5">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span id="popDlLabel">Download ke Downloads</span>
            </button>
            <button type="button" onclick="closeReceiptPopup()" class="px-5 py-3 bg-[#FCE4EC] text-[#C2185B] font-extrabold text-xs rounded-full">Tutup</button>
        </div>
    </div>
</div>

<script>
let currentOrderCode = 'TG-RESI';

function openReceiptPopup(code, buyer, food, method, total, dateStr) {
    currentOrderCode = code;
    document.getElementById('popCodeDate').textContent = code + ' • ' + dateStr;
    document.getElementById('popBuyer').textContent = buyer;
    document.getElementById('popFood').textContent = food;
    document.getElementById('popMethod').textContent = method;
    document.getElementById('popTotal').textContent = total;
    document.getElementById('popDlLabel').textContent = 'Download ke Downloads';

    const modal = document.getElementById('inAppReceiptModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReceiptPopup() {
    const modal = document.getElementById('inAppReceiptModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function downloadPopupReceipt() {
    const label = document.getElementById('popDlLabel');
    label.textContent = 'Mengunduh...';
    html2canvas(document.getElementById('popupReceiptCapture'), { scale: 2, backgroundColor: '#ffffff' }).then(canvas => {
        const a = document.createElement('a');
        a.download = 'Resi-TitipGo-' + currentOrderCode + '.png';
        a.href = canvas.toDataURL('image/png');
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        label.textContent = 'Tersimpan di Downloads ✓';
    });
}
</script>
@endsection