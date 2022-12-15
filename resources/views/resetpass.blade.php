@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Ganti Password</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item">Ganti Password</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form class="needs-validation" action="{{ route('resetpassword.store') }}" method="POST" novalidate="">
    @csrf
    <div class="card-header">
        <h4>Ganti Password</h4>
    </div>
    <div class="card-body">
        <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required="">
        <div class="invalid-feedback">
            Belum diisi !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>
        <div class="form-group">
        <label for="password" class="d-block">Password Lama</label>
        <input id="password" type="password" class="form-control" name="password" minlength="8" required="">
        <div class="invalid-feedback">
            Minimal 8 Karakter !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>
        <div class="form-group">
        <label for="password_new" class="d-block">Password Baru</label>
        <input id="password_new" type="password" class="form-control" name="password_new" minlength="8" required="">
        <div class="invalid-feedback">
            Minimal 8 Karakter !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <button class="btn btn-primary">Submit</button>
    </div>
    </form>
</div>
</div>

@endsection