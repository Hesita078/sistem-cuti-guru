<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pengajuan Cuti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 24px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 15px 0;
        }
        .status-pending { background: #FFC107; color: #000; }
        .status-success { background: #4CAF50; color: #fff; }
        .status-danger { background: #f44336; color: #fff; }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #666;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sistem Pengajuan Cuti Guru</h1>
        </div>

        @if($tipe == 'pengajuan_baru')
            <h2>Pengajuan Cuti Baru</h2>
            <p>Halo Admin,</p>
            <p>Ada pengajuan cuti baru yang perlu diverifikasi:</p>
            <span class="status-badge status-pending">Menunggu Verifikasi</span>

        @elseif($tipe == 'diverifikasi_admin')
            <h2>Pengajuan Diverifikasi</h2>
            <p>Halo {{ $pengajuan->user->nama }},</p>
            <p>Pengajuan cuti Anda telah diverifikasi oleh Admin dan diteruskan ke Kepala Sekolah untuk persetujuan.</p>
            <span class="status-badge status-pending">Menunggu Persetujuan Kepala Sekolah</span>

        @elseif($tipe == 'ditolak_admin')
            <h2>Pengajuan Ditolak Admin</h2>
            <p>Halo {{ $pengajuan->user->nama }},</p>
            <p>Mohon maaf, pengajuan cuti Anda ditolak oleh Admin dengan alasan:</p>
            <div style="background: #f44336; color: white; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <strong>Catatan Admin:</strong><br>
                {{ $pengajuan->catatan_admin }}
            </div>
            <span class="status-badge status-danger">Ditolak</span>

        @elseif($tipe == 'menunggu_persetujuan')
            <h2>Persetujuan Cuti Diperlukan</h2>
            <p>Halo Kepala Sekolah,</p>
            <p>Ada pengajuan cuti yang telah diverifikasi Admin dan memerlukan persetujuan Anda:</p>
            <span class="status-badge status-pending">Menunggu Persetujuan</span>

        @elseif($tipe == 'disetujui')
            <h2>Pengajuan Cuti Disetujui</h2>
            <p>Halo {{ $pengajuan->user->nama }},</p>
            <p>Selamat! Pengajuan cuti Anda telah disetujui oleh Kepala Sekolah.</p>
            <span class="status-badge status-success">Disetujui</span>

        @elseif($tipe == 'ditolak')
            <h2>Pengajuan Ditolak</h2>
            <p>Halo {{ $pengajuan->user->nama }},</p>
            <p>Mohon maaf, pengajuan cuti Anda ditolak oleh Kepala Sekolah dengan alasan:</p>
            <div style="background: #f44336; color: white; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <strong>Catatan Kepala Sekolah:</strong><br>
                {{ $pengajuan->catatan_kepala_sekolah }}
            </div>
            <span class="status-badge status-danger">Ditolak</span>
        @endif

        <table class="info-table">
            <tr>
                <td>Kode Pengajuan</td>
                <td><strong>{{ $pengajuan->kode_pengajuan }}</strong></td>
            </tr>
            <tr>
                <td>Nama Guru</td>
                <td>{{ $pengajuan->user->nama }}</td>
            </tr>
            <tr>
                <td>Jenis Cuti</td>
                <td>{{ $pengajuan->jenis_cuti }}</td>
            </tr>
            <tr>
                <td>Tanggal Mulai</td>
                <td>{{ $pengajuan->tanggal_mulai->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Tanggal Selesai</td>
                <td>{{ $pengajuan->tanggal_selesai->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Jumlah Hari</td>
                <td>{{ $pengajuan->jumlah_hari }} hari</td>
            </tr>
            <tr>
                <td>Alasan</td>
                <td>{{ $pengajuan->alasan }}</td>
            </tr>
        </table>

        @if($tipe == 'disetujui')
            <div style="background: #E8F5E9; padding: 15px; border-radius: 5px; border-left: 4px solid #4CAF50; margin: 20px 0;">
                <strong>ℹ️ Informasi:</strong><br>
                Hak cuti Anda telah otomatis dikurangi {{ $pengajuan->jumlah_hari }} hari.<br>
                Silakan cek dashboard untuk melihat sisa hak cuti Anda.
            </div>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/') }}" class="button">Lihat Detail di Sistem</a>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh Sistem Pengajuan Cuti Guru.</p>
            <p>Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} SD Example. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
