@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Administrator</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('divisi.index')}}">Administrator</a></div>
    <div class="breadcrumb-item">Edit User</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form method="POST" action="{{ route('administrator.update',$arruser->id) }}">
    @csrf
    @method('PUT')
    <div class="card-header">
        <h4>Tambah User</h4>
    </div>
    <div class="card-body">
        <div class="form-group">
        <label for="name">Name {{$arruser->name}}</label>
        <input id="name" type="text" value="{{$arruser->name}}" class="form-control @error('name') is-invalid @enderror" name="name" required autocomplete="name" autofocus>
        </div>
        <div class="form-group">
        <label>Divisi</label>
        <select name="divisi" id="divisi" class="form-control" required="">
            <option value="">Pilih Data</option>
            @foreach($divisi as $div)
                @if($div->id==$arruser->divisi)
                    <option value="{{$div->id}}" selected>{{$div->kode_divisi}} - {{$div->nama_divisi}}</option>
                @else
                    <option value="{{$div->id}}">{{$div->kode_divisi}} - {{$div->nama_divisi}}</option>
                @endif
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
                @if($jab->jabatan=$arruser->jabatan)
                    <option value="{{$jab->jabatan}}" selected>{{$jab->jabatan}}</option>
                @else
                    <option value="{{$jab->jabatan}}" selected>{{$jab->jabatan}}</option>
                @endif
            @endforeach
        </select>
        </div>
        <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$arruser->email}}" required autocomplete="email">
        </div>
        <div class="form-group">
        <label for="password" class="d-block">Password</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        </div>
    </div>
    <div class="card-footer text-right">
        <input type="text" id="idEdit" name="idEdit" value="{{$arruser->id}}" hidden>
        <a class="btn btn-secondary" href="{{ route('administrator.index') }}">Back</a>
        <button class="btn btn-primary">Submit</button>
    </div>
    </form>
</div>
</div>
@endsection