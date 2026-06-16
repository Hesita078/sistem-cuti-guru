<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a1a; padding: 20px; }

        .kop { text-align: center; margin-bottom: 6px; }
        .kop h1 { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .kop h2 { font-size: 12px; text-transform: uppercase; }
        .kop p  { font-size: 9px; color: #444; }
        hr.tebal { border: none; border-top: 2.5px solid #1a1a1a; margin: 5px 0 2px; }
        hr.tipis  { border: none; border-top: 1px solid #1a1a1a; margin-bottom: 12px; }

        .judul     { text-align: center; font-size: 11px; font-weight: bold; text-decoration: underline; margin-bottom: 4px; }
        .sub-judul { text-align: center; font-size: 9px; color: #555; margin-bottom: 14px; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th {
            background: #2c3e50; color: #fff;
            padding: 5px 6px; text-align: center;
            font-size: 9px; border: 1px solid #2c3e50;
        }
        table.data td {
            padding: 4px 6px; border: 1px solid #ccc;
            font-size: 9px; vertical-align: middle;
        }
        table.data tbody tr:nth-child(even) { background: #f7f9fc; }
        table.data tfoot td {
            background: #ecf0f1; font-weight: bold;
            border: 1px solid #bbb; padding: 5px 6px;
        }

        .ttd-area { margin-top: 28px; }
        .ttd-area table { width: 100%; border: none; }
        .ttd-area td { border: none; vertical-align: top; font-size: 9px; }
        .ttd-area .kanan { text-align: center; width: 35%; }
        .ttd-area .garis-ttd {
            margin-top: 48px; border-top: 1px solid #1a1a1a;
            padding-top: 3px; font-weight: bold;
        }
        .footer-note { margin-top: 12px; font-size: 8px; color: #888; text-align: center; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop">
        <h2>Pemerintah Kabupaten</h2>
        <h1>SD Negeri Kincang 01</h1>
        <p>Jl. Contoh No. 1, Kota &nbsp;|&nbsp; Telp. (0xxx) xxxx &nbsp;|&nbsp; {{ $email }}</p>
    </div>
    <hr class="tebal">
    <hr class="tipis">

    {{-- ══════════════════════════════════════ --}}
    {{-- BULANAN                                --}}
    {{-- ══════════════════════════════════════ --}}
    @if($tipe === 'bulanan')

        @php $namaBulan = \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F'); @endphp

        <div class="judul">LAPORAN CUTI GURU</div>
        <div class="sub-judul">Bulan {{ strtoupper($namaBulan) }} Tahun {{ $tahun }}</div>

        <table class="data">
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:11%">Kode</th>
                    <th style="width:18%">Nama Guru</th>
                    <th style="width:14%">Jenis Cuti</th>
                    <th style="width:10%">Tgl Mulai</th>
                    <th style="width:10%">Tgl Selesai</th>
                    <th style="width:6%">Hari</th>
                    <th style="width:8%">Hak Sebelum</th>
                    <th style="width:8%">Hak Sesudah</th>
                    <th style="width:11%">Tgl Disetujui</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $item)
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $item->kode_pengajuan }}</td>
                    <td>{{ $item->user->nama ?? '-' }}</td>
                    <td>{{ $item->jenis_cuti }}</td>
                    <td style="text-align:center">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</td>
                    <td style="text-align:center">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
                    <td style="text-align:center">{{ $item->jumlah_hari }}</td>
                    <td style="text-align:center">{{ $item->hak_cuti_sebelum }}</td>
                    <td style="text-align:center">{{ $item->hak_cuti_sesudah }}</td>
                    <td style="text-align:center">{{ \Carbon\Carbon::parse($item->tanggal_persetujuan)->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding:12px; color:#888;">
                        Tidak ada data cuti pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right">Total Hari Cuti :</td>
                    <td style="text-align:center">{{ $data->sum('jumlah_hari') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>

        @php $namaBulanTtd = $namaBulan; @endphp

    {{-- ══════════════════════════════════════ --}}
    {{-- TAHUNAN                                --}}
    {{-- ══════════════════════════════════════ --}}
    @elseif($tipe === 'tahunan')

        <div class="judul">REKAP CUTI GURU TAHUN {{ $tahun }}</div>
        <div class="sub-judul">Rekapitulasi Penggunaan Hak Cuti Seluruh Guru</div>

        <table class="data">
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:20%">Nama Guru</th>
                    <th style="width:15%">NIP</th>
                    <th style="width:13%">Jabatan</th>
                    <th style="width:7%">Hak Cuti</th>
                    <th style="width:8%">Cuti Diambil</th>
                    <th style="width:7%">Sisa</th>
                    <th style="width:16%">Jenis Cuti</th>
                    <th style="width:10%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $item)
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->nip ?? '-' }}</td>
                    <td>{{ $item->jabatan ?? '-' }}</td>
                    <td style="text-align:center">{{ $item->hak_cuti_tahunan }}</td>
                    <td style="text-align:center">{{ $item->cuti_diambil }}</td>
                    <td style="text-align:center">{{ $item->sisa_cuti }}</td>
                    <td>{{ $item->jenis_cuti_diambil }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:12px; color:#888;">
                        Tidak ada data guru.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right">Total Hari Diambil :</td>
                    <td style="text-align:center">{{ $data->sum('cuti_diambil') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>

        @php $namaBulanTtd = \Carbon\Carbon::create($tahun, date('m'))->translatedFormat('F'); @endphp

    @endif

    {{-- TANDA TANGAN --}}
    <div class="ttd-area">
        <table>
            <tr>
                <td></td>
                <td class="kanan">
                    <p>{{ $namaBulanTtd }} {{ $tahun }}</p>
                    <p style="margin-top:4px;">Kepala Sekolah,</p>
                    <div class="garis-ttd">{{ $kepala_sekolah }}</div>
                    <p>NIP. {{ $nip_kepala }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
        &nbsp;|&nbsp; {{ $email }}
    </div>

</body>
</html>
