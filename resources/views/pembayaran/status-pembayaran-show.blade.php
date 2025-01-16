@extends('layouts.backend.app')
@section('title', 'Data Pembayaran')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush
@section('content_title', 'Pembayaran Tahun '.$spp->tahun)
@section('content')
<x-alert></x-alert>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <a href="{{ route('pembayaran.status-pembayaran.show',$siswa->nisn) }}" class="btn btn-danger btn-sm">
          <i class="fas fa-fw fa-arrow-left"></i> KEMBALI
        </a>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        @if($pembayaran->count() > 0)
        <table id="dataTable2" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Nisn</th>
            <th>Tanggal Bayar</th>
            <th>Nama Petugas</th>
            <th>Untuk Semester</th>
            <th>Untuk Tahun</th>
            <th>Nominal</th>
            <th>Bukti Pembayaran</th>
            <th>Status</th>
          </tr>
          </thead>
          <tbody>
          @foreach($pembayaran as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->siswa->nama_siswa }}</td>
            <td>{{ $row->siswa->kelas->nama_kelas }}</td>
            <td>{{ $row->nisn }}</td>
            <td>{{ \Carbon\Carbon::parse($row->tanggal_bayar)->format('d-m-Y') }}</td>
            <td>{{ $row->petugas->nama_petugas }}</td>
            <td>{{ $row->semester_bayar }}</td>
            <td>{{ $row->tahun_bayar }}</td>
            <td>{{ $row->jumlah_bayar }}</td>
            <td>
              @if($row->bukti_pembayaran)
                <!-- Tombol untuk membuka modal bukti pembayaran -->
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#buktiPembayaranModal" onclick="showBuktiPembayaran('{{ url('storage/bukti_pembayaran/' . $row->bukti_pembayaran) }}', 'image')">
                  <span class="badge badge-success">Ada</span>
                </a>
              @else
                <span class="badge badge-danger">Tidak Ada</span>
              @endif
            </td>

            <!-- Modal untuk menampilkan bukti pembayaran -->
            <div class="modal fade" id="buktiPembayaranModal" tabindex="-1" aria-labelledby="buktiPembayaranModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="buktiPembayaranModalLabel">Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div id="buktiPembayaranContainer">
                      <!-- Gambar atau PDF akan ditampilkan di sini -->
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <td>
              <a class="btn btn-success btn-sm"><i class=""></i> DIBAYAR</a>
            </td>
          </tr>
          @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-danger" role="alert">
          <h4 class="alert-heading">Data Pembayaran Tidak Tersedia!</h4>
          <p>Pembayaran Spp {{ $siswa->nama_siswa }} di Tahun {{ $spp->tahun }} tidak tersedia.</p>
        </div>
        @endif
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm">
          <i class="fas fa-fw fa-circle"></i> STATUS PEMBAYARAN
        </a>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        @if($pembayaran->count() > 0)
        <table id="" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>Semester</th>
            <th>Status</th>
          </tr>
          </thead>
          <tbody>
          @foreach(Universe::semesterAll() as $key => $value)
          <tr>
            <td>{{ $value['nama_semester'] }}</td>
            <td>
              @if(Universe::statusPembayaran($siswa->id, $spp->tahun, $value['kode_semester']) == 'DIBAYAR')
                <a href="javascript:(0)" class="btn btn-success btn-sm"><i class=""></i> 
                  {{ Universe::statusPembayaran($siswa->id, $spp->tahun, $value['kode_semester']) }}
                </a>
              @else
                <a href="javascript:(0)" class="btn btn-danger btn-sm"><i class=""></i> 
                  {{ Universe::statusPembayaran($siswa->id, $spp->tahun, $value['kode_semester']) }}
                </a>
              @endif
            </td>
          </tr>
          @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-danger" role="alert">
          <h4 class="alert-heading">Data Status Pembayaran Tidak Tersedia!</h4>
          <p>Status Pembayaran Spp {{ $siswa->nama_siswa }} di Tahun {{ $spp->tahun }} tidak tersedia.</p>
        </div>
        @endif
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
@stop

@push('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
  $(function () {
    $("#dataTable1").DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#dataTable2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  function showBuktiPembayaran(url, type) {
    let container = document.getElementById('buktiPembayaranContainer');
    
    if (type === 'image') {
        container.innerHTML = `<img src="${url}" class="img-fluid" alt="Bukti Pembayaran">`;
    } else if (type === 'pdf') {
        container.innerHTML = `<iframe src="${url}" width="100%" height="500px"></iframe>`;
    }
  }

</script>
@endpush
