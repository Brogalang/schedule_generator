@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Karyawan</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('karyawan.index')}}">Karyawan</a></div>
    <div class="breadcrumb-item">Add</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form class="needs-validation" action="{{ route('karyawan.store') }}" method="POST" novalidate="">
    @csrf
    <div class="card-header">
        <h4>Tambah Karyawan</h4>
    </div>
    <div class="card-body">
        <div class="form-group">
        <label>Nama Karyawan</label><span class="text-danger" aria-hidden="true">&starf;</span>
        <input type="text" class="form-control" name="nama_karyawan" required="">
        <div class="invalid-feedback">
            Belum diisi !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>

        <div class="form-group">
        <label>Pendidikan</label>
        <select name="pendidikan" id="pendidikan" class="form-control select2">
            <option value="">Pilih Data</option>
            @foreach($pend as $key => $val)
                <option value="{{$val}}">{{$val}}</option>
            @endforeach
        </select>
        </div>

        <div class="form-group">
        <label>Level Karyawan</label><span class="text-danger" aria-hidden="true">&starf;</span>
        <select name="level_karyawan" id="level_karyawan" class="form-control" required="">
            @for($i=1;$i<=5;$i++)
                <option value="{{$i}}">{{$i-1}}</option>
            @endfor
        </select>
        <div class="invalid-feedback">
            Belum diisi !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>

        <div class="form-group">
        <label>Divisi Karyawan</label><span class="text-danger" aria-hidden="true">&starf;</span>
        <select name="divisi_karyawan" id="divisi_karyawan" class="form-control select2bs4" required="">
            <option value="">Pilih Data</option>
            @foreach($divisi as $div)
                <option value="{{$div->id}}">{{$div->kode_divisi}} - {{$div->nama_divisi}}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">
            Belum diisi !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>
        <!---->
        <div class="form-group">
        <label>Jabatan</label>
        <select name="jabatan" id="jabatan" class="form-control select2bs4">
            @foreach($jabatan as $jab)
                <option value="{{$jab->jabatan}}">{{$jab->jabatan}}</option>
            @endforeach
        </select>
        </div>
        <!---->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    <label>Tempat Lahir</label>
                    <input type="text" class="form-control" name="tmptlahir" id="tmptlahir">
                </div>
                <div class="col-sm-4">
                    <label>Tanggal Lahir</label>
                    <input type="text" class="form-control datepicker" name="tanggallahir" id="tanggallahir">
                </div>
            </div>
        </div>
        <!---->
        <div class="form-group">
        <label>Tanggal masuk</label>
        <input type="text" class="form-control datepicker" name="tglmasuk" id="tglmasuk">
        </div>
        <!---->
        <div class="form-group">
        <label>Jenis Kelamin</label>
        <select name="jk" id="jk" class="form-control">
            <option value="">Pilih Data</option>
            <option value="Laki-laki">Laki - laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>
        </div>
        <!---->
        <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" id="alamat" class="form-control"></textarea>
        </div>
        
    </div>
    <div class="card-footer text-right">
        <a class="btn btn-secondary" href="{{ route('karyawan.index') }}">Back</a>
        <button class="btn btn-primary">Submit</button>
    </div>
    </form>
</div>
</div>

@endsection

@push('javascript')
@endpush