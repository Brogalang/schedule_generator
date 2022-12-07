@extends('template.template')

@section('content')
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
        <label>Nama Karyawan</label>
        <input type="text" class="form-control" name="nama_karyawan" required="">
        <div class="invalid-feedback">
            Kok masih kosong?
        </div>
        <div class="valid-feedback">
            Nah gitu dong
        </div>
        </div>

        <div class="form-group">
        <label>Level Karyawan</label>
        <select name="level_karyawan" id="level_karyawan" class="form-control" required="">
            @for($i=1;$i<=5;$i++)
                <option value="{{$i}}">{{$i}}</option>
            @endfor
        </select>
        <div class="invalid-feedback">
            Kok masih kosong?
        </div>
        <div class="valid-feedback">
            Nah gitu dong
        </div>
        </div>

        <div class="form-group">
        <label>Divisi Karyawan</label>
        <select name="divisi_karyawan" id="divisi_karyawan" class="form-control" required="">
            <option value="">Pilih Data</option>
            @foreach($divisi as $div)
                <option value="{{$div->id}}">{{$div->kode_divisi}} - {{$div->nama_divisi}}</option>
            @endforeach
        </select>
        <div class="invalid-feedback">
            Kok masih kosong?
        </div>
        <div class="valid-feedback">
            Nah gitu dong
        </div>
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