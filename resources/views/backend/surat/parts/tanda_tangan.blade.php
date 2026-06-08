<div class="tanda-tangan" style="margin-top: 40px; float: right; width: 300px; text-align: center;">
    <p style="margin-bottom: 80px;">
        {{ \App\Facades\Setting::get('village_nama', config('village.nama_desa')) }}, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
        Kepala Desa {{ \App\Facades\Setting::get('village_nama', config('village.nama_desa')) }}
    </p>
    <p style="margin: 0;"><strong><u>{{ \App\Facades\Setting::get('village_nama_kades', config('village.nama_kades')) }}</u></strong></p>
    <p style="margin: 0;">NIP. {{ \App\Facades\Setting::get('village_nip_kades', config('village.nip_kades')) }}</p>
</div>
<div style="clear: both;"></div>
