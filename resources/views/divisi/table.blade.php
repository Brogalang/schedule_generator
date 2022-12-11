@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Divisi</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
    <div class="breadcrumb-item">Divisi</a></div>
    </div>
</div>

<div class="section-body">
<!--Start main-->
    <div class="col-sm-2">
        @if($addsess[11]==1)
            <a href="{{ route('divisi.create') }}" class="btn btn-info">Tambah Data</a><br>
        @endif
    </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped" id="table-1">
        <thead>                                 
            <tr>
            <th class="text-center">No</th>
            <th>Kode Divisi</th>
            <th>Nama Divisi</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>        
        @foreach($divisi as $div)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{$div->kode_divisi}}</td>
            <td>{{$div->nama_divisi}}</td>
            <td>
                {{--<form action="{{ route('divisi.destroy',$div->id) }}" method="POST">
                    @csrf
                    @method('DELETE')--}}
                    @if($updatesess[11]==1)
                        <a class="btn btn-info" href="{{ route('divisi.edit',$div->id) }}" ><i class="fa fa-edit" title="Edit Data"></i></a>
                    @endif
                    @if($deletesess[11]==1)
                        <button type="submit" class="btn btn-danger" onclick="deletediv('{{$div->id}}')"><i class="fa fa-trash" title="Delete Data"></i></button>
                    @endif
                {{--</form>--}}
            </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    </div>
    {{--<div class="card-footer text-right">
    <nav class="d-inline-block">
        {{$divisi->links()}}
    </nav>
    </div>--}}
</div>
<!--End main-->
</div>

@endsection

@push('javascript')
<script>
    function deletediv(idDiv) {
        alertify.confirm('Warning', 'Are you sure ??', 
        function(){ 
            alertify.success('Ok') 
            $.ajax({
                data: 'idDiv='+idDiv,
                url: "{{ route('deletediv') }}",
                type: "GET",
                datatype : "json",
                success: function(response) {
                    window.location.href = "divisi";
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