@extends('layouts.backend.app')

@section('title', 'Daftar Pengajuan Kendala')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<!-- Sweetalert 2 -->
<link rel="stylesheet" type="text/css" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/sweetalert2/sweetalert2.min.css">
@endpush

@section('content_title', 'Daftar Pengajuan Kendala')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
    <div class="card-header">
    <a href="{{ route('pengajuankendala.index') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus fa-fw"></i> Tambah Pengajuan
    </a>
</div>

      <!-- /.card-header -->
      <div class="card-body">
        <table id="dataTable2" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Lengkap</th>
              <th>Email</th>
              <th>Kendala</th>
              <th>Deskripsi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pengajuanKendala as $pengajuan)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pengajuan->nama_lengkap }}</td>
                <td>{{ $pengajuan->email_pengguna }}</td>
                <td>{{ $pengajuan->kendala }}</td>
                <td>{{ $pengajuan->deskripsi }}</td>
                <td>{{ $pengajuan->status }}</td>
                <td>
                  @if ($pengajuan->status != 'Selesai')
                    <form action="{{ route('pengajuan-kendala.update-status', $pengajuan->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="Selesai">
                      <button type="submit" class="btn btn-success btn-sm">Tandai Selesai</button>
                    </form>
                  @else
                    <span class="badge badge-success">Selesai</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
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
<!-- DataTables  & Plugins -->
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- Sweetalert 2 -->
<script type="text/javascript" src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- Custom Update Status script -->
<script src="{{ asset('js/updateStatus.js') }}"></script>

@endpush
