@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Schedule</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('schedule.index')}}">Schedule</a></div>
    <div class="breadcrumb-item">Generate Schedule</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <div class="card-header">
        <div class="form-group">
            <h4>Generate Schedule</h4><br>
            <label>Note: Jumlah Karyawan Per Level Harus Kelipatan 4</label>
        </div>

        {{--<a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
        <button class="btn btn-primary">Save Header</button>--}}
    </div>
    <!-- <div class="card-footer">
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="form-group col-2">
                <label>Periode</label>
                <h5>{{$arrbln[substr($schedule->bulan_scheduler,5,2)]}} {{substr($schedule->bulan_scheduler,0,4)}}</h5>
                <input type="hidden" name="bulan" id="bulan" value="{{substr($schedule->bulan_scheduler,5,2)}}">
                <input type="hidden" name="tahun" id="tahun" value="{{substr($schedule->bulan_scheduler,0,4)}}">
            </div>
            <div class="form-group col-2">
                <label>Divisi</label>
                <input type="hidden" name="divisi_karyawan" id="divisi_karyawan" value="{{$schedule->divisi_scheduler}}">
                @foreach($divisi as $div)
                    @if($div->id==$schedule->divisi_scheduler)
                        <h5>{{$div->nama_divisi}}</h5>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="form-group col-2">
                <label>Level 1</label>
                <h5>{{$hlvl1}} Orang</h5>
                @if($ctlvl1>0)
                    <button class="btn btn-danger">Data Sudah ada</button>
                @else
                    <button class="btn btn-info">Data Kosong</button>
                @endif
            </div>
            <div class="form-group col-2">
                <label>Level 2</label>
                <h5>{{$hlvl2}} Orang</h5>
                @if($ctlvl2>0)
                    <button class="btn btn-danger">Data Sudah ada</button>
                @else
                    <button class="btn btn-info">Data Kosong</button>
                @endif
            </div>
            <div class="form-group col-2">
                <label>Level 3</label>
                <h5>{{$hlvl3}} Orang</h5>
                @if($ctlvl3>0)
                    <button class="btn btn-danger">Data Sudah ada</button>
                @else
                    <button class="btn btn-info">Data Kosong</button>
                @endif
            </div>
            <div class="form-group col-2">
                <label>Level 4</label>
                <h5>{{$hlvl4}} Orang</h5>
                @if($ctlvl4>0)
                    <button class="btn btn-danger">Data Sudah ada</button>
                @else
                    <button class="btn btn-info">Data Kosong</button>
                @endif
            </div>
            <div class="form-group col-2">
                <label>Level 5</label>
                <h5>{{$hlvl5}} Orang</h5>
                @if($ctlvl5>0)
                    <button class="btn btn-danger">Data Sudah ada</button>
                @else
                    <button class="btn btn-info">Data Kosong</button>
                @endif
            </div>
            
        </div>
    </div>
    <div class="card-footer text-left">
        @if($ctlvl1>0)
            {{--<form action="{{ route('deletedetail',$idDetail) }}" method="GET">
                @csrf--}}
                <a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
                <button type="submit" id="delButton" class="btn btn-danger" onclick="deletedetail('{{$idDetail}}')">Delete Schedule</button>
            {{--</form>--}}
        @else
            <form action="{{ route('schedule.store') }}" method="POST" novalidate="">
                <input type="hidden" name="bulan" id="bulan" value="{{substr($schedule->bulan_scheduler,5,2)}}">
                <input type="hidden" name="tahun" id="tahun" value="{{substr($schedule->bulan_scheduler,0,4)}}">
                <input type="hidden" name="divisi_karyawan" id="divisi_karyawan" value="{{$schedule->divisi_scheduler}}">
                @csrf
                <input type="hidden" name="metode" id="metode" value="insertlvl">
                <a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
                <button class="btn btn-primary">Generate</button>
            </form>
        @endif
    </div>
</div>
</div>

@endsection

@push('javascript')
<script>
    function deletedetail(idDetail) {
        alertify.confirm('Warning', 'Are you sure ???', 
        function(){ 
            alertify.success('Ok') 
            $.ajax({
                data: 'idDetail='+idDetail,
                url: "{{ route('deletedetail') }}",
                type: "GET",
                datatype : "json",
                success: function(response) {
                    window.location.href = ""+idDetail;
                },
                error: function(response) {
                }
            });
        }, function(){ 
            alertify.error('Cancel')
        })
    }
</script>
@endpush