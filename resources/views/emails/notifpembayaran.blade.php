<!DOCTYPE html>
<html>
<head>
    <title>GENERATE PDF</title>
</head>
<body>
<br><br>
<center>
  <h2 style="font-family: sans-serif;">Pembayaran SPP Kelas {{ $kelas }}</h2>
</center>
<br>
<div style="float: left;">
  <b style="font-family: sans-serif;">Nama Siswa : {{ $nama_siswa }}</b><br>
  <b style="font-family: sans-serif;">Kelas : {{ $kelas }}</b><br>
  <b style="font-family: sans-serif;">Nisn : {{ $nisn }}</b><br>
  <b style="font-family: sans-serif;">Nis : {{ $nis }}</b><br>
</div>

<br><br><br><br><br>
<table style="" border="1" cellspacing="0" cellpadding="10" width="100%">
  <thead>
    <tr>
      <th scope="col" style="font-family: sans-serif;">Petugas</th>
      <th scope="col" style="font-family: sans-serif;">Untuk Tahun</th>
      <th scope="col" style="font-family: sans-serif;">Untuk Semester</th>
      <th scope="col" style="font-family: sans-serif;">Jumlah Bayar</th>
      <th scope="col" style="font-family: sans-serif;">Kode Pembayaran</th>
      <th scope="col" style="font-family: sans-serif;">Tanggal Bayar</th>
      <th scope="col" style="font-family: sans-serif;">Bukti Pembayaran</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="font-family: sans-serif;">{{ $petugas }}</td>
      <td style="font-family: sans-serif;">{{ $tahun_bayar }}</td>
      <td style="font-family: sans-serif;">{{ $semester_bayar }}</td>
      <td style="font-family: sans-serif;">Rp {{ number_format($jumlah_bayar, 0, ',', '.') }}</td>
      <td style="font-family: sans-serif;">{{ $kode_pembayaran }}</td>
      <td style="font-family: sans-serif;">{{ \Carbon\Carbon::parse($tanggal_bayar)->format('d-m-Y') }}</td>
      <td style="font-family: sans-serif;">
        {{ $bukti_pembayaran ? 'Ada' : 'Tidak Ada' }}
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>
