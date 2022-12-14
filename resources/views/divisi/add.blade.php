@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Unit Kerja</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('divisi.index')}}">Unit Kerja</a></div>
    <div class="breadcrumb-item">Add</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form class="needs-validation" action="{{ route('divisi.store') }}" method="POST" novalidate="">
    @csrf
    <div class="card-header">
        <h4>Tambah Unit Kerja</h4>
    </div>
    <div class="card-body">
        <div class="form-group">
        <label>Kode Unit Kerja</label>
        <input type="text" class="form-control" name="kode_divisi" required="">
        <div class="invalid-feedback">
            Belum diisi !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>
        <div class="form-group">
        <label>Nama Unit Kerja</label>
        <input type="text" class="form-control" name="nama_divisi" required="">
        <div class="invalid-feedback">
            Belum diisi !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <a class="btn btn-secondary" href="{{ route('divisi.index') }}">Back</a>
        <button class="btn btn-primary">Submit</button>
    </div>
    </form>
</div>
</div>

@endsection