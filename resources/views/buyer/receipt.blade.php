@extends('layouts.app')
@section('content')
<div id="receiptModal" class="fixed inset-0 z-50 bg-[#4A152C]/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-[2rem] border-2 border-[#F8BBD0] shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-[#F8BBD0] to-[#F06292] px-6 py-4 text-white flex items-center justify-between">
            <span class="text-xs font-extrabold tracking-wide uppercase flex items-center gap-1.5">
                <i data-lucide="receipt" class="w-4 h-4"></i> Bukti Resi Digital TitipGo
            </span>
            <a href="{{ route('buyer.track') }}" class="w-7 h-7 rounded-full bg-white/25 hover:bg-white/40 flex items-center justify-center text-white">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
        </div>

        <div id="receiptCaptureArea" class="p-7 bg-white space-y-4">
            <div class="text-center border-b-2 border-dashed border-[#F8BBD0] pb-4">
                <span class="px-3 py-1 bg-[#FCE4EC] text-[#C2185B] text-[11px] font-extrabold rounded-full">TRANSAKSI LUNAS</span>
                <h2 class="font-serif-title text-2xl font-bold text-[#522238] mt-2">TitipGo Official Receipt</h2>
                <p class="text-xs font-mono text-[#8E4A67] mt-1">{{ $order->order_code }} • {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            <div class="space-y-2.5 text-xs text-[#522238]">
                <div class="flex justify-between"><span class="opacity-70">Nama Pemesan:</span><strong>{{ $order->buyer_name }}</strong></div>
                <div class="flex justify-between"><span class="opacity-70">Email:</span><strong>{{ $order->buyer_email }}</strong></div>
                <div class="flex justify-between gap-4"><span class="opacity-70 shrink-0">Makanan Dipesan:</span><strong class="text-right">{{ $order->item->name }} ({{ $order->quantity }}x)</strong></div>
                <div class="flex justify-between"><span class="opacity-70">Asal & Estimasi:</span><strong>{{ $order->item->estimated_shipping }}</strong></div>
                <div class="flex justify-between"><span class="opacity-70">Metode Pembayaran:</span><strong>{{ $order->payment_method }}</strong></div>
                <div class="flex justify-between text-base font-black text-[#D81B60] pt-3 border-t border-dashed border-[#F8BBD0]">
                    <span>Total Dibayarkan:</span>
                    <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <p class="text-[10px] text-center text-[#8E4A67] pt-2">
                Lolos Pemeriksaan Bea Cukai & Karantina Indonesia • TitipGo
            </p>
        </div>

        <div class="px-6 pb-6 pt-2 flex gap-3 bg-white">
            <button type="button" onclick="downloadReceiptFile('{{ $order->order_code }}')" class="flex-1 py-3 bg-[#EC407A] hover:bg-[#D81B60] text-white font-extrabold text-xs rounded-full shadow flex items-center justify-center gap-1.5 transition">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span id="dlBtnText">Download Resi</span>
            </button>
            <a href="{{ route('buyer.track') }}" class="px-5 py-3 bg-[#FCE4EC] text-[#C2185B] font-extrabold text-xs rounded-full">
                Tutup
            </a>
        </div>
    </div>
</div>

<script>
function downloadReceiptFile(orderCode) {
    const btnText = document.getElementById('dlBtnText');
    btnText.textContent = 'Mengunduh...';
    const target = document.getElementById('receiptCaptureArea');

    html2canvas(target, { scale: 2, backgroundColor: '#ffffff' }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'Resi-TitipGo-' + orderCode + '.png';
        link.href = canvas.toDataURL('image/png');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        btnText.textContent = 'Tersimpan di Downloads ✓';
    });
}
</script>
@endsection