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
    <form class="needs-validation" action="{{ route('karyawan.update',$karyawan->id) }}" method="POST" novalidate="">
    @csrf
    @method('PUT')
    <div class="card-header">
        <h4>Tambah Karyawan</h4>
    </div>
    <div class="card-body">
        <div class="form-group">
        <label>Nama Karyawan</label>
        <input type="text" class="form-control" name="nama_karyawan" value="{{$karyawan->nama_karyawan}}" required="">
        <div class="invalid-feedback">
            Belum diisi !!
        </div>
        <div class="valid-feedback">
            Oke
        </div>
        </div>

        <div class="form-group">
        <label>Level Karyawan</label>
        <select name="level_karyawan" id="level_karyawan" class="form-control" required="">
            @for($i=1;$i<=5;$i++)
                @if($i==$karyawan->level_karyawan)
                    <option value="{{$i}}" selected>{{$i}}</option>
                @else
                    <option value="{{$i}}">{{$i}}</option>
                @endif
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
        <label>Divisi Karyawan</label>
        <select name="divisi_karyawan" id="divisi_karyawan" class="form-control" required="">
            <option value="">Pilih Data</option>
            @foreach($divisi as $div)
                @if($div->id==$karyawan->divisi_karyawan)
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
                @if($i==$karyawan->jabatan)
                    <option value="{{$jab->jabatan}}" selected>{{$jab->jabatan}}</option>
                @else
                    <option value="{{$jab->jabatan}}" selected>{{$jab->jabatan}}</option>
                @endif
            @endforeach
        </select>
        </div>
        <!---->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    <label>Tempat Lahir</label>
                    <input type="text" class="form-control" name="tmptlahir" id="tmptlahir" value="{{$karyawan->tmptlahir}}">
                </div>
                <div class="col-sm-4">
                    <label>Tanggal Lahir</label>
                    <input type="text" class="form-control datepicker" name="tanggallahir" id="tanggallahir" value="{{$karyawan->tanggallahir}}">
                </div>
            </div>
        </div>
        <!---->
        <div class="form-group">
        <label>Tanggal masuk</label>
        <input type="text" class="form-control datepicker" name="tglmasuk" id="tglmasuk" value="{{$karyawan->tglmasuk}}">
        </div>
        <!---->
        <div class="form-group">
        <label>Jenis Kelamin</label>
        <select name="jk" id="jk" class="form-control">
            <option value="">Pilih Data</option>
            @foreach($jnskelamin as $jns => $val)
                @if($karyawan->jk==$jns)
                    <option value="{{$jns}}" selected>{{$val}}</option>
                @else
                    <option value="{{$jns}}">{{$val}}</option>
                @endif
            @endforeach
        </select>
        </div>
        <!---->
        <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" id="alamat" class="form-control" value="{{$karyawan->alamat}}">{{$karyawan->alamat}}</textarea>
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