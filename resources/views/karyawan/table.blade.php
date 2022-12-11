@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Karyawan</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
    <div class="breadcrumb-item">Karyawan</a></div>
    </div>
</div>

<div class="section-body">
<!--Start main-->
<div class="card">
    <div class="col-sm-2">
        @if($addsess[12]==1)
            <a href="{{ route('karyawan.create') }}" class="btn btn-info">Tambah Data</a><br>
        @endif
    </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped" id="table-1">
        <thead>                                 
            <tr>
            <th class="text-center">
                No
            </th>
            <th>Nama Kayawan</th>
            <th>Jabatan</th>
            <th>Divisi</th>
            <th>Level</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Lahir</th>
            <th>Tanggal Masuk</th>
            <th>Alamat</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>        
        @foreach($karyawan as $kar)                         
            <tr>
            <td>{{ ++$i }}</td>
            <td><b>{{$kar->nama_karyawan}}</b> <br> Lama Bekerja {{$thnlamanya[$kar->id]}}</td>
            <td>{{$kar->jabatan}}</td>
            <td>{{$kar->nama_divisi}}</td>
            <td>{{$kar->level_karyawan}}</td>
            <td>{{$kar->jk}}</td>
            @if(date('d-m-Y',strtotime($kar->tanggallahir))=='01-01-1970')
                <td></td>
            @else
                <td>{{date('d-m-Y',strtotime($kar->tanggallahir))}}</td>
            @endif
            @if(date('d-m-Y',strtotime($kar->tglmasuk))=='01-01-1970')
                <td></td>
            @else
                <td>{{date('d-m-Y',strtotime($kar->tglmasuk))}}</td>
            @endif
            <td>{{$kar->alamat}}</td>
            <td nowrap>
                {{--<form action="{{ route('karyawan.destroy',$kar->id) }}" method="POST">
                    @csrf
                    @method('DELETE')--}}
                    @if($updatesess[12]==1)
                        <a class="btn btn-info" href="{{ route('karyawan.edit',$kar->id) }}" ><i class="fa fa-edit" title="Edit Data"></i></a>
                    @endif
                    @if($deletesess[12]==1)
                        <button type="submit" class="btn btn-danger" onClick="deletekary('{{$kar->id}}')"><i class="fa fa-trash" title="Delete Data"></i></button>
                    @endif
                {{--</form>--}}
            </td>
            </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    </div>
    {{--<div class="card-footer text-right">
    <nav class="d-inline-block">
        {{$karyawan->links()}}
    </nav>
    </div>--}}
</div>
<!--End main-->
</div>

@endsection
@push('javascript')
<script>
    $(document).ready(function () {
        $('#dtBasicExample').DataTable();
    });
</script>

<script>
    function deletekary(idKary) {
        alertify.confirm('Warning', 'Are you sure ??', 
        function(){ 
            alertify.success('Ok') 
            $.ajax({
                data: 'idKary='+idKary,
                url: "{{ route('deletekary') }}",
                type: "GET",
                datatype : "json",
                success: function(response) {
                    window.location.href = "karyawan";
                },
                error: function(response) {
                }
            });
        }, function(){ 
            alertify.error('Cancel')
        })
    }
</script>
@endpush