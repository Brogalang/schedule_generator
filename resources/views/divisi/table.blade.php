@extends('template.template')

@section('content')
<div class="section-header">
    <h1>Divisi</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
    <div class="breadcrumb-item">Divisi</a></div>
    </div>
</div>

<div class="section-body">
<!--Start main-->
<div class="card">
    <div class="card-body">
    <div class="table-responsive">
        <a href="{{ route('divisi.create') }}" class="btn btn-info">Tambah Data</a>
        <table class="table table-bordered table-md">
        <tr>
            <th>No</th>
            <th>Kode Divisi</th>
            <th>Nama Divisi</th>
            <th>Action</th>
        </tr>
        @foreach($divisi as $div)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{$div->kode_divisi}}</td>
            <td>{{$div->nama_divisi}}</td>
            <td>
                <form action="{{ route('divisi.destroy',$div->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('divisi.edit',$div->id) }}" ><i class="fa fa-edit" title="Edit Data"></i></a>
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
        {{$divisi->links()}}
    </nav>
    </div>
</div>
<!--End main-->
</div>

@endsection