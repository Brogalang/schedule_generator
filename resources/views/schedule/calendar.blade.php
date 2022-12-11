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
    <a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
    {{--<a class="btn btn-secondary" href="{{ route('showcalendar',$id) }}">Shift View</a>--}}
    @if($exportsess[13]==1)
        <button class="btn btn-success" id="excelButton">Excel</button>
    @endif
    @if($updatesess[13]==1)
        <a class="btn btn-info" href="{{ route('editcalendar',$id) }}">Edit</a>
    @endif
    <!-- <div class="card-header">
        <h5>Periode {{$arrbln2[substr($first2->periode,5,2)]}} {{substr($first2->periode,0,4)}}</h5>
    </div> -->
    <div class="card-body">
    <div class="table-responsive" style="overflow-y:auto">
        <table class="table table-striped" id="excelTable">
            <thead>  
            <tr>
                <td colspan=5 style="text-align:left"><strong><h5>Periode {{$arrbln2[substr($first2->periode,5,2)]}} {{substr($first2->periode,0,4)}}</h5></strong></td>
            </tr>                               
                <tr>
            <th style="text-align:center">No</th>
            <th style="text-align:center">Nama</th>
            @for($i=1; $i <= $hari2 ; $i++)
                <th style="text-align:center">{{$i}}</th>
            @endfor
            </tr>
        </thead>
        <tbody>        
        @foreach($data2 as $key => $val)
            <tr>
                <td>{{$j++}}</td>
                <td nowrap>{{$key}}</td>
                @foreach($val as $key1 => $val1)
                    @foreach($val1 as $key2 => $val2)
                        @if(substr($val2,0,5)=='Pagi0')
                            <td>Pagi</td>
                        @elseif(substr($val2,0,5)=='Siang')
                            <td>{{substr($val2,0,5)}}</td>
                        @elseif(substr($val2,0,5)=='Malam')
                            <td>{{substr($val2,0,5)}}</td>
                        @else
                            <td style="background-color: red;"><font color="black">{{substr($val2,0,5)}}</font></td>
                        @endif
                    @endforeach
                @endforeach
                {{--@for($i=1; $i <= $hari2 ; $i++)
                    @if(substr($val->$i,0,5)=='Pagi0')
                        <td>Pagi</td>
                    @else
                        <td>{{substr($val->$i,0,5)}}</td>
                    @endif
                @endfor--}}
            </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    </div>
</div>

@endsection

@push('javascript')
<script>
    $("#excelButton").click(function(){
        $("#excelTable").table2excel({
            name: "Generate_Schedule"
        });
        
    });
</script>
@endpush