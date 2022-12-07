@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Schedule</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
    <div class="breadcrumb-item">Schedule</a></div>
    </div>
</div>

<div class="section-body">
<!--Start main-->
<div class="card">
    <div class="card-body">
    <div class="table-responsive">
        <a href="{{ route('schedule.create') }}" class="btn btn-info">Tambah Data</a>
        <table class="table table-bordered table-md">
        <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Divisi</th>
            {{--<th>Status</th>--}}
            <th>Action</th>
        </tr>
        @foreach($schedule as $sch)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{$sch->bulan_scheduler}}</td>
            <td>{{$sch->nama_divisi}}</td>
            {{--<td>
                @if($hlvl1!=0)
                    <span class="badge badge-success">
                        Level 1 <br>
                        Done
                    </span>
                @endif
                @if($hlvl2!=0)
                    <span class="badge badge-success">
                        Level 2 <br>
                        Done
                    </span>
                @endif
                @if($hlvl3!=0)
                    <span class="badge badge-success">
                        Level 3 <br>
                        Done
                    </span>
                @endif
                @if($hlvl4!=0)
                    <span class="badge badge-success">
                        Level 4 <br>
                        Done
                    </span>
                @endif
                @if($hlvl5!=0)
                    <span class="badge badge-success">
                        Level 5 <br>
                        Done
                    </span>
                @endif
            </td>--}}
            <td>
                <form action="{{ route('schedule.destroy',$sch->id) }}" method="POST">
                    <a class="btn btn-outline-success" href="{{ route('schedule.show',$sch->id) }}" ><i class="fa fa-eye" title="Show Data"></i></a>
                    <a class="btn btn-outline-dark" href="{{ route('showcalendar',$sch->id) }}" ><i class="fa fa-calendar" title="Show Generate"></i></a>
                    <a class="btn btn-info" href="{{ route('schedule.edit',$sch->id) }}" ><i class="fa fa-edit" title="Edit Data"></i></a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash" title="Delete Data"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
        
        </table>
    </div>
    </div>
    <div class="card-footer text-right">
    <nav class="d-inline-block">
        {{$schedule->links()}}
    </nav>
    </div>
</div>
<!--End main-->
</div>

@endsection