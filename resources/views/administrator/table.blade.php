@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Administrator</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
    <div class="breadcrumb-item">Administrator</a></div>
    </div>
</div>

<div class="section-body">
<!--Start main-->
    <div class="col-sm-2">
        <a href="{{ route('administrator.create') }}" class="btn btn-info">Tambah Data</a><br>
    </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped" id="table-1">
        <thead>                                 
            <tr>
            <th class="text-center">No</th>
            <th style="text-align:center">Nama</th>
            <th style="text-align:center">Email</th>
            <th style="text-align:center">Action</th>
            </tr>
        </thead>
        <tbody>        
        @foreach($arruser as $role)
        <tr>
            <td style="text-align:center">{{ ++$i }}</td>
            <td style="text-align:center">{{$role->name}}</td>
            <td style="text-align:center">{{$role->email}}</td>
            <td style="text-align:center">
                {{--<form action="{{ route('divisi.destroy',$role->id) }}" method="POST">
                    @csrf
                    @method('DELETE')--}}
                    <a class="btn btn-info" href="{{ route('administrator.edit',$role->id) }}" ><i class="fa fa-edit" title="Edit Data"></i></a>
                    <a class="btn btn-info" href="{{ route('rolemenu',$role->id) }}" ><i class="fa fa-plus" title="Tambah Role"></i></a>
                    <button type="submit" class="btn btn-danger" onclick="deletediv('{{$role->id}}')"><i class="fa fa-trash" title="Delete Data"></i></button>
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
<!-- <script>
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
</script> -->
@endpush