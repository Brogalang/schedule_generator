@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Administrator</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('divisi.index')}}">Administrator</a></div>
    <div class="breadcrumb-item">Add User</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form method="POST" action="{{ route('administrator.store') }}">
    @csrf
    <div class="card-header">
        <h4>Tambah User</h4>
    </div>
    <div class="card-body">
        <div class="form-group">
        <label for="name">Name</label>
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        </div>
        <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
        </div>
        <div class="form-group">
        <label for="password" class="d-block">Password</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        </div>
    </div>
    <div class="card-footer text-right">
        <a class="btn btn-secondary" href="{{ route('administrator.index') }}">Back</a>
        <button class="btn btn-primary">Submit</button>
    </div>
    </form>
</div>
</div>
@endsection