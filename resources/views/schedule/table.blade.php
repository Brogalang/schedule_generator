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
    <div class="col-sm-2">
        @if($addsess[13]==1)
            <a href="{{ route('schedule.create') }}" class="btn btn-info">Tambah Data</a><br>
        @endif
    </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped" id="table-1">
        <thead>                                 
            <tr>
            <th class="text-center" style="text-align:center">No</th>
            <th style="text-align:center">Bulan</th>
            <th style="text-align:center">Divisi</th>
            <th style="text-align:center">Status</th>
            <th style="text-align:center">Action</th>
            </tr>
        </thead>
        <tbody>        
        @foreach($schedule as $sch)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{$arrbln[substr($sch->bulan_scheduler,5,2)]}} {{substr($sch->bulan_scheduler,0,4)}}</td>
            <td>{{$sch->nama_divisi}}</td>
            <td>
                @if($hlvl1[$sch->id]!=0)
                    <span class="badge badge-success">
                        Level 0 <br>
                        Done
                    </span>
                @endif
                @if($hlvl2[$sch->id]!=0)
                    <span class="badge badge-success">
                        Level 1 <br>
                        Done
                    </span>
                @endif
                @if($hlvl3[$sch->id]!=0)
                    <span class="badge badge-success">
                        Level 2 <br>
                        Done
                    </span>
                @endif
                @if($hlvl4[$sch->id]!=0)
                    <span class="badge badge-success">
                        Level 3 <br>
                        Done
                    </span>
                @endif
                @if($hlvl5[$sch->id]!=0)
                    <span class="badge badge-success">
                        Level 4 <br>
                        Done
                    </span>
                @endif
            </td>
            <td style="text-align:center">
                {{--<form action="{{ route('schedule.destroy',$sch->id) }}" method="POST">--}}
                    @if($addsess[13]==1)
                        <a class="btn btn-outline-success" href="{{ route('schedule.show',$sch->id) }}" ><i class="fa fa-eye" title="Show Data"></i></a>
                    @endif
                    @if($exportsess[13]==1)
                        <a class="btn btn-outline-dark" href="{{ route('calendarview',$sch->id) }}" ><i class="fa fa-calendar" title="Show Generate"></i></a>
                    @endif
                    @if($updatesess[13]==1)
                        <a class="btn btn-info" href="{{ route('schedule.edit',$sch->id) }}" ><i class="fa fa-edit" title="Edit Data"></i></a>
                    @endif
                    {{--@csrf
                    @method('DELETE')--}}
                    @if($deletesess[13]==1)
                        <button type="submit" class="btn btn-danger" onClick="deletetask('{{$sch->id}}')"><i class="fa fa-trash" title="Delete Data"></i></button>
                    @endif
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    </div>
    {{--<div class="card-footer text-right">
    <nav class="d-inline-block">
        {{$schedule->links()}}
    </nav>
    </div>--}}
</div>
<!--End main-->
</div>

@endsection
@push('javascript')
<script>
    function deletetask(id_task) {
        alertify.confirm('Warning', 'Are you sure ??', 
        function(){ 
            alertify.success('Ok') 
            $.ajax({
                data: 'id_task='+id_task,
                url: "{{ route('deletedata') }}",
                type: "GET",
                datatype : "json",
                success: function(response) {
                    window.location.href = "schedule";
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