<div class="tanda-tangan" style="margin-top: 40px; float: right; width: 300px; text-align: center;">
    <p style="margin-bottom: 80px;">
        {{ config('village.nama_desa') }}, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
        Kepala Desa {{ config('village.nama_desa') }}
    </p>
    <p style="margin: 0;"><strong><u>{{ config('village.nama_kades') }}</u></strong></p>
    <p style="margin: 0;">NIP. {{ config('village.nip_kades') }}</p>
</div>
<div style="clear: both;"></div>
