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
<div class="card" id="modal-add">
    {{--<form class="needs-validation" action="{{ route('updateshift') }}" method="GET" novalidate="">
    @csrf
    @method('PUT')--}}
    <form class="needs-validation" action="javascript:void(0)" method="POST" novalidate="">
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
                        <option value="{{$val->karyawanid}}">{{$val->nama_karyawan}} (Level {{$level[$val->tanda]}})</option>
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
                <input id="labelshift" name="labelshift" class="form-control" disabled>
            </div>
            <div class="form-group col-2">
                <label>Shift Awal Baru</label>
                <input id="labelshiftbaru" name="labelshiftbaru" class="form-control" disabled>
            </div>
            <div class="form-group col-2">
                <label>Shift</label>
                <select name="shiftsch" id="shiftsch" class="form-control select2">
                    <option value="">Pilih Data</option>
                </select>
                <div class="invalid-feedback">
                    Belum diisi !!
                </div>
                <div class="valid-feedback">
                    Oke
                </div>
            </div>
            <div class="form-group col-1">
                <label>Ganti Shift</label>
                <div class="text-center">
                    <input type="checkbox" id="gantish" name="gantish" onclick="check(this,'{{$id}}')">
                </div>
            </div>
            <div class="form-group col-1">
                <label>Roll Back</label>
                <div class="text-center">
                    <input type="checkbox" id="rollback" name="rollback" onclick="check2(this,'{{$id}}')">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-3">
                <label>Nama Karyawan Pengganti (Untuk bertukar shift)</label>
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
        <a class="btn btn-secondary" href="{{ route('calendarview',$id) }}">Back</a>
        <button class="btn btn-primary" id="saveBtn">Save</button>
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
    $('#saveBtn').click(function(e) {
        var chkganti = document.getElementById("gantish");
        var rollback = document.getElementById("rollback");
        var shiftsch = document.getElementById("shiftsch").value;
        if (chkganti.checked == true) {
            if (shiftsch=='') {
                alertify.alert("Shift masih kosong !!!");
                document.getElementById("shiftsch").required=true;
                return;
            }
        }else{
            document.getElementById("shiftsch").required=false;
        }
        if (rollback.checked == true) {
            document.getElementById("nmkarganti").required=false;
        }else{
            document.getElementById("nmkarganti").required=true;
        }

        var formdata = $("#modal-add form").serializeArray();
        var data = {};
        $(formdata).each(function(index, obj) {
            data[obj.name] = obj.value;
        });
            $.ajax({
                data: $('#modal-add form').serialize(),
                url: "{{ route('updateshift') }}",
                type: "GET",
                dataType: 'html',
                success: function(data) {
                    // $('#modal-add').modal('hide');
                    window.location.href = "";
                    // console.log('Error:', data)
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
    });
    function check(cb,id){
        if ($(cb).is(":checked")) {
            getshift(id);
        }else{
            getshift(id);
        }
    }
    function check2(cb,id){
        if ($(cb).is(":checked")) {
            document.getElementById("gantish").checked=false;
            document.getElementById("gantish").disabled=true;
            getshift(id);
        }else{
            document.getElementById("gantish").checked=false;
            document.getElementById("gantish").disabled=false;
            getshift(id);
        }
    }

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

                        // var opt5 = document.createElement('option');
                        // opt5.css="font-weight: bold";
                        // opt5.value = "Siang";
                        // opt5.innerHTML = "Siang";
                        // shiftsch.appendChild(opt5);

                        // var opt6 = document.createElement('option');
                        // opt6.css="font-weight: bold";
                        // opt6.value = "Malam";
                        // opt6.innerHTML = "Malam";
                        // shiftsch.appendChild(opt6);

                        // var opt7 = document.createElement('option');
                        // opt7.css="font-weight: bold";
                        // opt7.value = "Libur";
                        // opt7.innerHTML = "Libur";
                        // shiftsch.appendChild(opt7);
                    }else if(item.shift.substring(0,5) == 'Siang'){
                        var valueopt=item.shift.substring(0,5);

                        // var opt5 = document.createElement('option');
                        // opt5.css="font-weight: bold";
                        // opt5.value = "Pagi0";
                        // opt5.innerHTML = "Pagi";
                        // shiftsch.appendChild(opt5);

                        // var opt6 = document.createElement('option');
                        // opt6.css="font-weight: bold";
                        // opt6.value = "Malam";
                        // opt6.innerHTML = "Malam";
                        // shiftsch.appendChild(opt6);

                        // var opt7 = document.createElement('option');
                        // opt7.css="font-weight: bold";
                        // opt7.value = "Libur";
                        // opt7.innerHTML = "Libur";
                        // shiftsch.appendChild(opt7);
                    }else if(item.shift.substring(0,5) == 'Malam'){
                        var valueopt=item.shift.substring(0,5);

                        // var opt5 = document.createElement('option');
                        // opt5.css="font-weight: bold";
                        // opt5.value = "Pagi0";
                        // opt5.innerHTML = "Pagi";
                        // shiftsch.appendChild(opt5);

                        // var opt6 = document.createElement('option');
                        // opt6.css="font-weight: bold";
                        // opt6.value = "Siang";
                        // opt6.innerHTML = "Siang";
                        // shiftsch.appendChild(opt6);

                        // var opt7 = document.createElement('option');
                        // opt7.css="font-weight: bold";
                        // opt7.value = "Libur";
                        // opt7.innerHTML = "Libur";
                        // shiftsch.appendChild(opt7);
                    }else if(item.shift.substring(0,5) == 'Libur'){
                        var valueopt=item.shift.substring(0,5);

                        // var opt5 = document.createElement('option');
                        // opt5.css="font-weight: bold";
                        // opt5.value = "Pagi0";
                        // opt5.innerHTML = "Pagi";
                        // shiftsch.appendChild(opt5);

                        // var opt6 = document.createElement('option');
                        // opt6.css="font-weight: bold";
                        // opt6.value = "Siang";
                        // opt6.innerHTML = "Siang";
                        // shiftsch.appendChild(opt6);

                        // var opt7 = document.createElement('option');
                        // opt7.css="font-weight: bold";
                        // opt7.value = "Malam";
                        // opt7.innerHTML = "Malam";
                        // shiftsch.appendChild(opt7);
                    }

                    var opt = document.createElement('option');
                    // opt.disabled = true;
                    opt.css="font-weight: bold";
                    opt.value = "";
                    opt.innerHTML = "Pilih Data";
                    shiftsch.appendChild(opt);

                    var opt2 = document.createElement('option');
                    opt2.value = "CT";
                    opt2.innerHTML = "Cuti";
                    shiftsch.appendChild(opt2);

                    var opt3 = document.createElement('option');
                    opt3.value = "Sakit";
                    opt3.innerHTML = "Sakit";
                    shiftsch.appendChild(opt3);

                    var opt4 = document.createElement('option');
                    opt4.value = "A";
                    opt4.innerHTML = "Alpha";
                    shiftsch.appendChild(opt4);
                    getkaryawan(item.tanda,id,item.tanggal,item.shift.substring(0,5));

                    document.getElementById('labelshift').value=valueopt;

                    if (item.shift_new==null) {
                        document.getElementById('labelshiftbaru').value="";
                    }else{
                        if (item.shift_new.substring(0,5) == 'Pagi0') {
                        var valueoptbaru="Pagi";
                        }else{
                            var valueoptbaru=item.shift_new.substring(0,5); 
                        }
                        document.getElementById('labelshiftbaru').value=valueoptbaru;
                    }
                });
            },
            error: function(response) {
                console.log('Error:', response);
            }
        });
    }

    function getkaryawan(lvl,id,tgl,shift){
        var nmkarganti = document.getElementById("nmkarganti");
        var chkganti = document.getElementById("gantish");
        if (chkganti.checked == true) {
            gantishift = 1;
        }else{
            gantishift = 0;
        }
        var rollback = document.getElementById("rollback");
        if (rollback.checked == true) {
            roll = 1;
        }else{
            roll = 0;
        }
        $.ajax({
            data: 'lvl='+lvl+'&id='+id+'&tgl='+tgl+'&shift='+shift+'&gantishift='+gantishift+'&roll='+roll,
            url: "{{ route('getkaryawan') }}",
            type: "GET",
            datatype : "json",
            success: function(response) {
                $('#nmkarganti').html("");
                $.each(response.listdata,function(key,item){
                    // alert(item.nama_karyawan);
                    var opt = document.createElement('option');
                    opt.value = item.karyawanid;
                    if (item.shift.substring(0,5) == 'Pagi0') {
                        var shiftV="Pagi";
                    }else{
                        var shiftV=item.shift.substring(0,5);
                    }
                    opt.innerHTML = item.nama_karyawan+'Shift ('+shiftV+')';
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