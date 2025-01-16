@extends('layouts.backend.app')
@section('title', 'Data Pembayaran')
@push('css')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush
@section('content_title', 'Tambah Pembayaran')
@section('content')
<x-alert></x-alert>
<div class="row">
	<div class="col-lg">
		<div class="card">
			<div class="card-header">
				<a href="{{ route('pembayaran.index') }}" class="btn btn-danger btn-sm">
				<i class="fas fa-window-close fa-fw"></i> 
			      BATALKAN
			    </a>
			</div>
			<div class="card-body">
				<form method="POST" action="{{ route('pembayaran.proses-bayar', $siswa->nisn) }}" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="nama_siswa">Nama Siswa:</label>
								<input required="" type="hidden" name="siswa_id" value="{{ $siswa->id }}" readonly id="siswa_id" class="form-control">
								<input required="" type="text" name="nama_siswa" value="{{ $siswa->nama_siswa }}" readonly id="nama_siswa" class="form-control">
								@error('nama_siswa')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="nisn">Nisn</label>
								<input required="" type="text" name="nisn" value="{{ $siswa->nisn }}" readonly id="nisn" class="form-control">
								@error('nisn')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="nis">Nis:</label>
								<input required="" type="text" name="nis" value="{{ $siswa->nis }}" readonly id="nis" class="form-control">
								@error('nis')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="kelas">Kelas:</label>
								<input required="" type="text" name="kelas" value="{{ $siswa->kelas->nama_kelas }}" readonly id="kelas" class="form-control">
								@error('kelas')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>	
					</div>
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="tahun_bayar">Untuk Tahun:</label>
								<select required="" name="tahun_bayar" id="tahun_bayar" class="form-control select2bs4">
										<option disabled="" selected="">- PILIH TAHUN -</option>
									@foreach($spp as $row)
										<option value="{{ $row->tahun }}">{{ $row->tahun }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="jumlah_bayar" id="nominal_spp_label">Nominal Spp:</label>
								<input type="" name="nominal" readonly="" id="nominal" class="form-control">
								<input required="" type="hidden" name="jumlah_bayar" readonly="" id="jumlah_bayar" class="form-control">
								@error('jumlah_bayar')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>
						<div class="col-lg-3">
        <div class="form-group select2-purple">
            <label for="semester_bayar">Semester Bayar:</label>
            <select name="semester_bayar[]" id="semester_bayar" class="form-control select2bs4" multiple="multiple" data-dropdown-css-class="select2-purple" data-placeholder="Pilih Semester" style="width: 100%;">
                <option value="1" {{ in_array(1, old('semester_bayar', [])) ? 'selected' : '' }}>Semester 1</option>
                <option value="2" {{ in_array(2, old('semester_bayar', [])) ? 'selected' : '' }}>Semester 2</option>
            </select>
        </div>
    </div>

						<div class="col-lg-3">
							<div class="form-group">
								<label for="total_bayar">Total Bayar:</label>
								<input required="" type="" name="total_bayar" readonly="" id="total_bayar" class="form-control">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="form-group">
								<label for="bukti_pembayaran">Unggah Bukti Pembayaran:</label>
								<input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*,application/pdf">
								@error('bukti_pembayaran')
									<small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>

					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary"><i class="fas fa-save fa-fw"></i> 
							KONFIRMASI
						</button>
					</div>						
				</form>
			</div>
		</div>	
	</div>
</div>
@stop
@push('js')
<!-- Select2 -->
<script src="{{ asset('templates/backend/AdminLTE-3.1.0') }}/plugins/select2/js/select2.full.min.js"></script>
<script>
	//Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    function rupiah(number) {
    	var formatter = new Intl.NumberFormat('ID', {
    		style: 'currency',
    		currency: 'idr',
    	})

    	return formatter.format(number)
    }

    $(document).on("change", "#tahun_bayar", function(){
    	var tahun = $(this).val()

    	$.ajax({
    		url: "/pembayaran/spp/"+tahun,
    		method: "GET",
    		success:function(response){
    			$("#nominal_spp_label").html(`Nominal Spp Tahun `+tahun+':')
    			$("#nominal").val(response.nominal_rupiah)
    			$("#jumlah_bayar").val(response.data.nominal)
    		}
    	})
    })

    $(document).on("change", "#semester_bayar", function(){
    var bulan = $(this).val();  // Ini akan menjadi array
    var total_bulan = bulan ? bulan.length : 0;  // Pastikan bulan memiliki nilai yang benar
    var total_bayar = parseFloat($("#jumlah_bayar").val());  // Pastikan ini angka

    // Debugging - Periksa apakah nilai bulan dan total_bayar valid
    console.log('Bulan dipilih: ' + total_bulan);
    console.log('Jumlah bayar: ' + total_bayar);

    if (!isNaN(total_bayar) && total_bulan > 0) {
        var hasil_bayar = total_bulan * total_bayar;
        
        var formatter = new Intl.NumberFormat('ID', {
            style: 'currency',
            currency: 'idr',
        });

        // Debugging - Lihat hasil perhitungan
        console.log('Total bayar: ' + hasil_bayar);

        // Menampilkan hasil perhitungan
        $("#total_bayar").val(formatter.format(hasil_bayar));
    } else {
        // Jika data tidak valid, set total bayar ke kosong
        console.log('Data tidak valid, total bayar kosong.');
        $("#total_bayar").val('');
    }
});


</script>
@endpush