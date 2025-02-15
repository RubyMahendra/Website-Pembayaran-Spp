@extends('layouts.backend.app')
@section('title', 'Dashboard')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/chart.js/Chart.min.css">
@endpush

@section('content_title', 'Dashboard')
@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $total_siswa }}</h3>

        <p>Siswa</p>
      </div>
      <div class="icon">
        <i class="fas fa-users"></i>
      </div>
      <a href="{{ route('siswa.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $total_kelas }}</h3>

        <p>Kelas</p>
      </div>
      <div class="icon">
        <i class="fas fa-school"></i>
      </div>
      <a href="{{ route('kelas.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $total_petugas }}</h3>

        <p>Petugas</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-tie"></i>
      </div>
      <a href="{{ route('petugas.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $total_admin }}</h3>

        <p>Admin</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-secret"></i>
      </div>
      <a href="{{ route('admin-list.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>
<!-- /.row -->

<div class="container mt-5">
    <div class="row">
        <!-- First Chart (Siswa) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Siswa</div>
                <div class="card-body">
                    <canvas id="canvas" height="500" width="600"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Second Chart (Total Pembayaran) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Total Pembayaran</div>
                <div class="card-body">
                    <canvas id="paymentChart" height="500" width="00"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>





@endsection

@push('js')
<script type="text/javascript" src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/chart.js/Chart.min.js"></script>
<script>
var ctx = document.getElementById("canvas").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Siswa Laki-laki", "Siswa Perempuan"],
      datasets: [{
        label: '',
        data: [
        {!! $siswa_laki_laki !!},
        {!! $siswa_perempuan !!},
        ],
        backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        ],
        borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true
          }
        }]
      }
    }
  });

  var paymentCtx = document.getElementById("paymentChart").getContext('2d');
var paymentChart = new Chart(paymentCtx, {
  type: 'bar',
  data: {
    labels: ["Total Pembayaran", "Pembayaran Ganjil", "Pembayaran Genap"],
    datasets: [{
      label: 'Total Pembayaran',
      data: [
        {!! $total_nominal !!}, // Total nominal pembayaran
        {!! $total_nominal_ganjil !!}, // Pembayaran semester Ganjil
        {!! $total_nominal_genap !!}, // Pembayaran semester Genap
      ],
      backgroundColor: [
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
      ],
      borderColor: [
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
      ],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero:true,
          callback: function(value) {
            return 'Rp ' + value.toLocaleString();
          }
        }
      }]
    }
  }
});
</script>
@endpush