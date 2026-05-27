<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10.5px;
            color: #000;
            padding: 25px 30px;
        }

        /* ── KOP ── */
        .kop-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .kop-table td { vertical-align: middle; padding: 3px 6px; border: none; }
        .kop-logo { width: 70px; text-align: center; }
        .kop-logo img { width: 60px; }
        .kop-text { text-align: center; }
        .kop-text .instansi   { font-size: 10px; font-weight: normal; }
        .kop-text .nama-dinas { font-size: 11px; font-weight: bold; }
        .kop-text .nama-sd    { font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop-text .kecamatan  { font-size: 10px; }
        .kop-text .alamat     { font-size: 9px; color: #333; margin-top: 2px; }

        hr.kop-line-tebal { border: none; border-top: 3px solid #000; margin: 5px 0 1px; }
        hr.kop-line-tipis { border: none; border-top: 1px solid #000; margin: 0 0 10px; }

        /* ── TANGGAL KANAN ── */
        .header-row { width: 100%; margin-bottom: 10px; }
        .header-row table { width: 100%; border: none; }
        .header-row td { border: none; }
        .tgl-kanan { text-align: right; font-size: 10px; }

        /* ── JUDUL ── */
        .judul {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 8px 0 14px;
            letter-spacing: 0.5px;
        }

        /* ── SECTION ── */
        .section-title {
            font-weight: bold;
            font-size: 10.5px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        /* ── TABEL DATA PEGAWAI ── */
        table.data-pegawai { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.data-pegawai td { padding: 3px 4px; font-size: 10.5px; border: none; }
        table.data-pegawai .label { width: 22%; font-weight: bold; }
        table.data-pegawai .sep   { width: 2%; }
        table.data-pegawai .value { width: 26%; }

        /* ── JENIS CUTI ── */
        table.jenis-cuti { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.jenis-cuti td { padding: 3px 6px; font-size: 10.5px; }
        .cb { display: inline-block; width: 14px; height: 14px; border: 1.5px solid #000; margin-right: 5px; text-align: center; line-height: 12px; font-size: 12px; vertical-align: middle; }
        .cb.checked::after { content: "✓"; font-weight: bold; }

        /* ── ALASAN ── */
        .box-alasan {
            border: 1px solid #000;
            min-height: 40px;
            padding: 6px 8px;
            margin-bottom: 12px;
            font-size: 10.5px;
        }

        /* ── LAMA CUTI ── */
        table.lama-cuti { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.lama-cuti td { padding: 3px 6px; font-size: 10.5px; border: none; }
        .underline-field {
            display: inline-block;
            min-width: 120px;
            border-bottom: 1px solid #000;
            padding: 0 4px;
        }

        /* ── CATATAN CUTI ── */
        table.catatan-cuti {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
        }
        table.catatan-cuti th, table.catatan-cuti td {
            border: 1px solid #000;
            padding: 3px 6px;
            text-align: center;
        }
        table.catatan-cuti th { background: #f0f0f0; font-size: 9.5px; }
        table.catatan-cuti .left { text-align: left; }

        /* ── ALAMAT & TTD PENGAJU ── */
        table.alamat-ttd { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.alamat-ttd td { padding: 3px 6px; border: none; vertical-align: top; font-size: 10.5px; }

        /* ── PERTIMBANGAN ── */
        table.pertimbangan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        table.pertimbangan th, table.pertimbangan td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: center;
            font-size: 10px;
        }
        table.pertimbangan th { background: #f0f0f0; font-weight: bold; }
        .centang { font-size: 16px; font-weight: bold; }

        /* ── TTD KEPALA ── */
        .ttd-kepala { text-align: center; padding-top: 6px; font-size: 10px; }
        .ttd-kepala .garis {
            margin-top: 50px;
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: bold;
        }

        /* ── CATATAN BAWAH ── */
        .catatan-bawah { font-size: 8.5px; color: #444; margin-top: 8px; line-height: 1.6; }
    </style>
</head>
<body>

    {{-- ════════════════ KOP SURAT ════════════════ --}}
    <table class="kop-table">
        <tr>
            <td class="kop-text">
                <div class="instansi">PEMERINTAH KABUPATEN MADIUN</div>
                <div class="nama-dinas">DINAS PENDIDIKAN</div>
                <div class="nama-sd">SEKOLAH DASAR NEGERI KINCANG 01</div>
                <div class="kecamatan">KECAMATAN JIWAN</div>
                <div class="alamat">
                    Alamat : Jl. Marsma TNI Anumerta R. Iswahjudi No.50, Pare, Kincang Wetan, Kec. Jiwan, Kabupaten Madiun, Jawa Timur 63161
                </div>
            </td>
        </tr>
    </table>

    <hr class="kop-line-tebal">
    <hr class="kop-line-tipis">

    {{-- TANGGAL KANAN --}}
    <table style="width:100%;border:none;">
        <tr>
            <td style="border:none;"></td>
            <td style="border:none;text-align:right;font-size:10px;">
                Madiun, {{ \Carbon\Carbon::parse($pengajuan->tanggal_persetujuan)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <div class="judul">Formulir Permintaan Cuti dan Izin</div>

    {{-- ════════════════ I. DATA PEGAWAI ════════════════ --}}
    <div class="section-title">I. DATA PEGAWAI</div>
    <table class="data-pegawai">
        <tr>
            <td class="label">Nama</td>
            <td class="sep">:</td>
            <td class="value">{{ $pengajuan->user->nama }}</td>
            <td class="label">NIP</td>
            <td class="sep">:</td>
            <td>{{ $pengajuan->user->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="sep">:</td>
            <td class="value">{{ $pengajuan->user->jabatan ?? 'Guru' }}</td>
            <td class="label">Masa Kerja</td>
            <td class="sep">:</td>
            <td>
                @if($pengajuan->user->tanggal_masuk)
                    {{ \Carbon\Carbon::parse($pengajuan->user->tanggal_masuk)->diffInYears(now()) }} Tahun
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Unit Kerja</td>
            <td class="sep">:</td>
            <td colspan="3">SD Negeri Kincang 01</td>
        </tr>
    </table>

    {{-- ════════════════ II. JENIS CUTI ════════════════ --}}
    <div class="section-title">II. JENIS CUTI YANG DIAMBIL</div>
    <table class="jenis-cuti">
        <tr>
            <td style="width:50%">
                <span class="cb {{ $pengajuan->jenis_cuti == 'Cuti Tahunan' ? 'checked' : '' }}"></span>
                1. Cuti Tahunan
            </td>
            <td style="width:50%">
                <span class="cb {{ $pengajuan->jenis_cuti == 'Cuti Sakit' ? 'checked' : '' }}"></span>
                2. Cuti Sakit
            </td>
        </tr>
        <tr>
            <td>
                <span class="cb {{ $pengajuan->jenis_cuti == 'Cuti Melahirkan' ? 'checked' : '' }}"></span>
                3. Cuti Melahirkan
            </td>
            <td>
                <span class="cb {{ $pengajuan->jenis_cuti == 'Cuti Ibadah Haji' ? 'checked' : '' }}"></span>
                4. Cuti Ibadah Haji
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="cb {{ $pengajuan->jenis_cuti == 'Cuti Penting' ? 'checked' : '' }}"></span>
                5. Cuti karena Alasan Penting
            </td>
        </tr>
    </table>

    {{-- ════════════════ III. ALASAN CUTI ════════════════ --}}
    <div class="section-title">III. ALASAN CUTI</div>
    <div class="box-alasan">{{ $pengajuan->alasan }}</div>

    {{-- ════════════════ IV. LAMA CUTI ════════════════ --}}
    <div class="section-title">IV. LAMANYA CUTI</div>
    <table class="lama-cuti">
        <tr>
            <td>Selama</td>
            <td><span class="underline-field">{{ $pengajuan->jumlah_hari }} hari</span></td>
            <td>Mulai tanggal</td>
            <td><span class="underline-field">{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') }}</span></td>
            <td>s.d</td>
            <td><span class="underline-field">{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}</span></td>
        </tr>
    </table>

    {{-- ════════════════ V. CATATAN CUTI ════════════════ --}}
    <div class="section-title">V. CATATAN CUTI</div>
    <table class="catatan-cuti">
        <thead>
            <tr>
                <th colspan="3" class="left" style="width:45%">1. CUTI TAHUNAN</th>
                <th colspan="2" style="width:55%">Keterangan Cuti Lainnya</th>
            </tr>
            <tr>
                <th style="width:10%">Tahun</th>
                <th style="width:18%">Sisa</th>
                <th style="width:17%">Keterangan</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>N-2</td>
                <td>-</td>
                <td>-</td>
                <td class="left">2. Cuti Sakit</td>
                <td>
                    @if($pengajuan->jenis_cuti == 'Cuti Sakit')
                        {{ $pengajuan->jumlah_hari }} hari
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>N-1</td>
                <td>-</td>
                <td>-</td>
                <td class="left">3. Cuti Melahirkan</td>
                <td>
                    @if($pengajuan->jenis_cuti == 'Cuti Melahirkan')
                        {{ $pengajuan->jumlah_hari }} hari
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>N</td>
                <td>
                    @if($histori)
                        {{ $histori->hak_cuti_sesudah }} hari
                    @else
                        -
                    @endif
                </td>
                <td>Tahun ini</td>
                <td class="left">4. Cuti Ibadah Haji</td>
                <td>
                    @if($pengajuan->jenis_cuti == 'Cuti Ibadah Haji')
                        {{ $pengajuan->jumlah_hari }} hari
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="left">5. Cuti Alasan Penting</td>
                <td>
                    @if($pengajuan->jenis_cuti == 'Cuti Penting')
                        {{ $pengajuan->jumlah_hari }} hari
                    @else
                        -
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    {{-- ════════════════ VI. ALAMAT & TTD PENGAJU ════════════════ --}}
    <div class="section-title">VI. ALAMAT SELAMA MENJALANI CUTI</div>
    <table class="alamat-ttd">
        <tr>
            <td style="width:60%;vertical-align:top;">
                <div style="border-bottom:1px solid #000;min-width:200px;padding:2px 4px;min-height:20px;">
                    {{ $pengajuan->user->alamat ?? '-' }}
                </div>
                <div style="margin-top:6px;">
                    Telp./HP :
                    <span style="border-bottom:1px solid #000;padding:0 4px;">
                        {{ $pengajuan->user->telepon ?? '-' }}
                    </span>
                </div>
            </td>
            <td style="width:40%;text-align:center;vertical-align:top;">
                <p>Hormat saya,</p>
                <br><br><br>
                <div style="border-top:1px solid #000;padding-top:3px;font-weight:bold;">
                    {{ $pengajuan->user->nama }}
                </div>
                <div>NIP. {{ $pengajuan->user->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

    {{-- ════════════════ VII. PERTIMBANGAN KEPALA SEKOLAH ════════════════ --}}
    <div class="section-title">VII. PERTIMBANGAN ATASAN LANGSUNG</div>
    <table class="pertimbangan">
        <thead>
            <tr>
                <th style="width:25%">DISETUJUI</th>
                <th style="width:25%">PERUBAHAN</th>
                <th style="width:25%">DITANGGUHKAN</th>
                <th style="width:25%">TIDAK DISETUJUI</th>
            </tr>
        </thead>
        <tbody>
            <tr style="height:36px;">
                <td>
                    @if(str_contains($pengajuan->status, 'Disetujui'))
                        <span class="centang">✓</span>
                    @endif
                </td>
                <td></td>
                <td>
                    @if($pengajuan->status === 'Ditangguhkan')
                        <span class="centang">✓</span>
                    @endif
                </td>
                <td>
                    @if(str_contains($pengajuan->status, 'Ditolak'))
                        <span class="centang">✓</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left;padding:4px 8px;">
                    Alasan :
                    {{ $pengajuan->catatan_kepala_sekolah ?? '-' }}
                </td>
                <td>
                    <div class="ttd-kepala">
                        <p>Kepala Sekolah,</p>
                        <br><br><br>
                        <div class="garis">{{ $kepala_sekolah }}</div>
                        <div>NIP. {{ $nip_kepala }}</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- CATATAN BAWAH --}}
    <div class="catatan-bawah">
        <strong>Catatan :</strong><br>
        * &nbsp;&nbsp; Pilih salah satu dengan memberi tanda centang ( √ )<br>
        ** &nbsp;&nbsp; Diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti<br>
        *** &nbsp; Beri tanda centang dan alasannya<br>
        N = Cuti tahun berjalan &nbsp;|&nbsp; N-1 = Sisa cuti 1 tahun sebelumnya &nbsp;|&nbsp; N-2 = Sisa cuti 2 tahun sebelumnya
    </div>

</body>
</html>
