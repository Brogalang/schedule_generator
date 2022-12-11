@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Schedule</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('schedule.index')}}">Schedule</a></div>
    <div class="breadcrumb-item">Edit</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form class="needs-validation" action="{{ route('schedule.update',$schedule->id) }}" method="POST" novalidate="">
    @csrf
    @method('PUT')
    <div class="card-header">
        <h4>Add Schedule</h4><br>

        <a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
        <button class="btn btn-primary">Save Schedule</button>
    </div>
    <!-- <div class="card-footer">
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="form-group col-2">
                <label>Bulan</label>
                <select name="bulan" id="bulan" class="form-control">
                    @foreach($arrbln as $key => $val)
                        @if($key==substr($schedule->bulan_scheduler,5,2))
                            <option value="{{$key}}" selected>{{$val}}</option>
                        @else
                            <option value="{{$key}}">{{$val}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-2">
                <label>Tahun</label>
                <select name="tahun" id="tahun" class="form-control">
                   @for($i=date('Y');$i>=2015;$i--)
                        @if($i==substr($schedule->bulan_scheduler,0,4))
                            <option value="{{$i}}" selected>{{$i}}</option>
                        @else
                            <option value="{{$i}}">{{$i}}</option>
                        @endif
                   @endfor
                </select>
            </div>
        </div>

        <div class="row" hidden>
            <div class="form-group col-2">
                <label>Level 1</label>
                <input type="text" name="level1" class="form-control">
            </div>
            <div class="form-group col-2">
                <label>Level 2</label>
                <input type="text" name="level2" class="form-control">
            </div>
            <div class="form-group col-2">
                <label>Level 3</label>
                <input type="text" name="level3" class="form-control">
            </div>
            <div class="form-group col-2">
                <label>Level 4</label>
                <input type="text" name="level4" class="form-control">
            </div>
            <div class="form-group col-2">
                <label>Level 5</label>
                <input type="text" name="level5" class="form-control">
            </div>
        </div>

        <div class="row">
        <div class="form-group col-4">
            <label>Divisi</label>
            <select name="divisi_karyawan" id="divisi_karyawan" class="form-control" required="" onchange="clickdivisi()">
                <option value="">Pilih Data</option>
                @foreach($divisi as $div)
                    @if($div->id==$schedule->divisi_scheduler)
                        <option value="{{$div->id}}" selected>{{$div->nama_divisi}}</option>
                    @else
                        <option value="{{$div->id}}">{{$div->nama_divisi}}</option>
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
        </div>
    </div>
    </form>
</div>
</div>

@endsection