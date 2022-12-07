@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Schedule</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('schedule.index')}}">Schedule</a></div>
    <div class="breadcrumb-item">Show</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <div class="card-header">
        <h5>Periode {{$arrbln[substr($first->periode,5,2)]}} {{substr($first->periode,0,4)}}</h5>
    </div>
        <!-- <div class="card-footer">
            </div> -->
            
    <div class="card-body">
        <div class="row">
            <a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
            <table  class="table table-bordered table-md freezetbl">
                <thead>
                <tr>
                    <th style="text-align:center">Tanggal</th>
                    @foreach($arrshift as $key => $val)
                        <th style="text-align:center">{{$val}}</th>
                    @endforeach
                </tr>
                </thead>
                
                @foreach($data as $key => $val)
                    <tr>
                        <td style="text-align:center"><b>{{$key}}</b></td>
                        @foreach($val as $key1 => $val1)
                        <td>
                            @foreach($val1 as $key2 => $val2)
                            <table class="table table-bordered table-md">
                                <tr>
                                <td nowrap>
                                <strong>Level {{$key2}}</strong><br>
                                    @foreach($val2 as $key3 => $val3)
                                        {{$val3}}<br>
                                    @endforeach
                                </td>
                                <tr>
                            </table>
                                @endforeach
                        </td>
                        @endforeach
                    </tr>
                @endforeach
                
            </table>
        </div>
    </div>
    <!-- <div class="card-footer text-left">
        <a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
    </div> -->
</div>
</div>

@endsection