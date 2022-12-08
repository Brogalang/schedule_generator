@extends('template.template')

@section('content')
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
    <div class="card-body">
    <div class="table-responsive">
        <a href="{{ route('karyawan.create') }}" class="btn btn-info">Tambah Data</a>
        <table class="table table-bordered table-md">
        <tr>
            <th>No</th>
            <th>Nama Kayawan</th>
            <th>Level</th>
            <th>Divisi</th>
            <th>Jabatan</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Lahir</th>
            <th>Tanggal Masuk</th>
            <th>Alamat</th>
            <th>Action</th>
        </tr>
        @foreach($karyawan as $kar)
        <tr>
            <td>{{ ++$i }}</td>
            <td><b>{{$kar->nama_karyawan}}</b> <br> Lama Bekerja {{$thnlamanya[$kar->id]}}</td>
            <td>{{$kar->level_karyawan}}</td>
            <td>{{$kar->nama_divisi}}</td>
            <td>{{$kar->jabatan}}</td>
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
                <form action="{{ route('karyawan.destroy',$kar->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('karyawan.edit',$kar->id) }}" ><i class="fa fa-edit" title="Edit Data"></i></a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash" title="Delete Data"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
        
        </table>
    </div>
    </div>
    <div class="card-footer text-right">
    <nav class="d-inline-block">
        {{$karyawan->links()}}
    </nav>
    </div>
</div>
<!--End main-->
</div>

@endsection