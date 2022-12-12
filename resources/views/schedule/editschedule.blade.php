@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Schedule Detail</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('schedule.index')}}">Schedule</a></div>
    <div class="breadcrumb-item">Edit Detail</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form class="needs-validation" action="{{ route('updateshift') }}" method="GET" novalidate="">
    @csrf
    @method('PUT')
    <div class="card-header">
        <h4>Edit Schedule Detail</h4><br>
    </div>
    <!-- <div class="card-footer">
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="form-group col-3">
                <label>Nama Karyawan</label>
                <select name="nmkar" id="nmkar" class="form-control select2" onchange="getshift('{{$id}}')" required="">
                    <option value="">Pilih Data</option>
                    @foreach($optkar as $key => $val)
                        <option value="{{$val->karyawanid}}">{{$val->nama_karyawan}}</option>
                    @endforeach
                </select>

                <div class="invalid-feedback">
                    Belum diisi !!
                </div>
                <div class="valid-feedback">
                    Oke
                </div>
            </div>
            <div class="form-group col-1">
                <label>Tanggal</label>
                <select name="tglsch" id="tglsch" class="form-control select2" onchange="getshift('{{$id}}')" required="">
                    <option value="">Pilih</option>
                    @for($i=1;$i<=$hari;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>

                <div class="invalid-feedback">
                    Belum diisi !!
                </div>
                <div class="valid-feedback">
                    Oke
                </div>
            </div>
            <div class="form-group col-2">
                <label>Shift Awal</label>
                <input id="labelshift" class="form-control" disabled>
            </div>
            <div class="form-group col-2">
                <label>Shift</label>
                <select name="shiftsch" id="shiftsch" class="form-control select2" required="">
                    <option value="">Pilih Data</option>
                </select>
                <div class="invalid-feedback">
                    Belum diisi !!
                </div>
                <div class="valid-feedback">
                    Oke
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-3">
                <label>Nama Karyawan Pengganti (Shift Libur)</label>
                <select name="nmkarganti" id="nmkarganti" class="form-control select2" required="">
                    <option value="">Pilih Data</option>
                </select>
                <div class="invalid-feedback">
                    Belum diisi !!
                </div>
                <div class="valid-feedback">
                    Oke
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-left">
        <input type="hidden" name="iddetail" id="iddetail" value="{{$id}}">
        <input type="hidden" name="shiftganti" id="shiftganti">
        <a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
        <button class="btn btn-primary">Save</button>
    </div>
    </form>
</div>
</div>

@endsection

@push('javascript')
<script>
    $(document).ready(function(){   
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    function getshift(id) {
        nmkar=document.getElementById("nmkar").value;
        tglsch=document.getElementById("tglsch").value;
        var shiftsch = document.getElementById("shiftsch");
        // alert(nmkar);
        if (nmkar=='') {
            alertify.alert('Warning', 'Mohon isi Nama Karyawan terlebih dahulu !!!');
            return;
        }
        $.ajax({
            data: 'nmkar='+nmkar+'&tglsch='+tglsch+'&id='+id,
            url: "{{ route('getshift') }}",
            type: "GET",
            datatype : "json",
            success: function(response) {
                $('#shiftsch').html("");
                $.each(response.listdata,function(key,item){
                    document.getElementById("shiftganti").value=item.shift;
                    if (item.shift.substring(0,5) == 'Pagi0') {
                        var valueopt="Pagi";
                    }else{
                        var valueopt=item.shift.substring(0,5);
                    }

                    var opt = document.createElement('option');
                    opt.disabled = true;
                    opt.css="font-weight: bold";
                    opt.value = item.shift;
                    opt.innerHTML = valueopt;
                    shiftsch.appendChild(opt);

                    var opt2 = document.createElement('option');
                    opt2.value = "Cuti";
                    opt2.innerHTML = "Cuti";
                    shiftsch.appendChild(opt2);

                    var opt3 = document.createElement('option');
                    opt3.value = "Sakit";
                    opt3.innerHTML = "Sakit";
                    shiftsch.appendChild(opt3);

                    var opt4 = document.createElement('option');
                    opt4.value = "Tanpa Keterangan";
                    opt4.innerHTML = "Tanpa Keterangan";
                    shiftsch.appendChild(opt4);
                    getkaryawan(item.tanda,id,item.tanggal);

                    document.getElementById('labelshift').value=valueopt;
                });
            },
            error: function(response) {
                console.log('Error:', response);
            }
        });
    }

    function getkaryawan(lvl,id,tgl){
        var nmkarganti = document.getElementById("nmkarganti");
        $.ajax({
            data: 'lvl='+lvl+'&id='+id+'&tgl='+tgl,
            url: "{{ route('getkaryawan') }}",
            type: "GET",
            datatype : "json",
            success: function(response) {
                $('#nmkarganti').html("");
                $.each(response.listdata,function(key,item){
                    // alert(item.nama_karyawan);
                    var opt = document.createElement('option');
                    opt.value = item.karyawanid;
                    opt.innerHTML = item.nama_karyawan;
                    nmkarganti.appendChild(opt);
                });
            },
            error: function(response) {
                console.log('Error:', response);
            }
        });
    }
</script>
@endpush