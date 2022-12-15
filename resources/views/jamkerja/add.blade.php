@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Jam Kerja</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item">Jam Kerja</a></div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form class="needs-validation" action="{{ route('jamkerja.store') }}" method="POST" novalidate="">
    @csrf
    <div class="card-header">
        <h4>Setting Jam Kerja</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-3">
            <label>Pagi</label>
            <input type="number" class="form-control" name="pagi" required="" value="{{$pagi}}">
            <div class="invalid-feedback">
                Belum diisi !!
            </div>
            <div class="valid-feedback">
                Oke
            </div>
            </div>

            <div class="form-group col-3">
            <label>Siang</label>
            <input type="number" class="form-control" name="siang" required="" value="{{$siang}}">
            <div class="invalid-feedback">
                Belum diisi !!
            </div>
            <div class="valid-feedback">
                Oke
            </div>
            </div>

            <div class="form-group col-3">
            <label>Malam</label>
            <input type="number" class="form-control" name="malam" required="" value="{{$malam}}">
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
        <input type="text" name="idEdit" value="{{$id}}" hidden>
        <button class="btn btn-primary">Submit</button>
    </div>
    </form>
</div>
</div>

@endsection