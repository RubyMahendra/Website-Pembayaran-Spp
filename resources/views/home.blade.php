@extends('layouts.backend.app')
@section('title', 'Home')
@section('content_title', 'Home')
@section('content')
<x-alert></x-alert>
<div class="row">
<div class="col-lg">
    <div class="jumbotron">
    @role('admin|petugas')
        <h1 class="display-4">
            Hello, {{ Universe::petugas() ? Universe::petugas()->nama_petugas : 'Petugas Tidak Ditemukan' }}!
        </h1>
    @endrole

    @role('siswa')
        <h1 class="display-4">
            Hello, {{ Universe::siswa() ? Universe::siswa()->nama_siswa : 'Siswa Tidak Ditemukan' }}!
        </h1>
    @endrole
        <p class="lead">Selamat datang di Web Pembayaran SPP SMP Negeri 2 Tabanan</p>
        <hr class="my-4">
    </div>
</div>

</div>
@endsection