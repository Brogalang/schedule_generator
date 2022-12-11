@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Administrator</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('divisi.index')}}">Administrator</a></div>
    <div class="breadcrumb-item">Add User</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form method="POST" action="{{ route('administrator.store') }}">
    @csrf
    <div class="card-header">
        <h4>{{$arruser->name}}</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <th>No</th>
                <th>Menu</th>
                <th>Add</th>
                <th>Update</th>
                <th>Delete</th>
                <th>Export</th>
            </thead>
            <tbody>
            @foreach($arrmenu as $arr)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$arr->title}} <input type="text" id="title[{{$j}}]" name="title[{{$j}}]" value="{{$arr->id}}" hidden></td>
                <td>
                    <input type="hidden" name="add[{{$j}}]" name="add[{{$j}}]" value="0">
                    @if($ctrole>0)
                        @if($dataadd[$arr->id]==1)
                            <input type="checkbox" id="add[{{$j}}]" name="add[{{$j}}]" checked>
                        @else
                            <input type="checkbox" id="add[{{$j}}]" name="add[{{$j}}]">
                        @endif
                    @else
                        <input type="checkbox" id="add[{{$j}}]" name="add[{{$j}}]">
                    @endif
                </td>
                <td>
                    <input type="hidden" name="update[{{$j}}]" name="update[{{$j}}]" value="0">
                    @if($ctrole>0)
                        @if($dataupdate[$arr->id]==1)
                            <input type="checkbox" id="update[{{$j}}]" name="update[{{$j}}]" checked>
                        @else
                            <input type="checkbox" id="update[{{$j}}]" name="update[{{$j}}]">
                        @endif
                    @else
                        <input type="checkbox" id="update[{{$j}}]" name="update[{{$j}}]">
                    @endif
                </td>
                <td>
                    <input type="hidden" name="delete[{{$j}}]" name="delete[{{$j}}]" value="0">
                    @if($ctrole>0)
                        @if($datadelete[$arr->id]==1)
                            <input type="checkbox" id="delete[{{$j}}]" name="delete[{{$j}}]" checked>
                        @else
                            <input type="checkbox" id="delete[{{$j}}]" name="delete[{{$j}}]">
                        @endif
                    @else
                        <input type="checkbox" id="delete[{{$j}}]" name="delete[{{$j}}]">
                    @endif
                </td>
                @if($arr->title != 'Schedule')
                    <td>
                        <input type="hidden" name="export[{{$j}}]" name="export[{{$j}}]" value="0">
                        <input type="checkbox" id="export[{{$j}}]" name="export[{{$j}}]" disabled>
                    </td>
                @else
                    <td>
                        <input type="hidden" name="export[{{$j}}]" name="export[{{$j}}]" value="0">
                        @if($ctrole>0)
                            @if($dataexport[$arr->id]==1)
                                <input type="checkbox" id="export[{{$j}}]" name="export[{{$j}}]" checked>
                            @else
                                <input type="checkbox" id="export[{{$j}}]" name="export[{{$j}}]">
                            @endif
                        @else
                            <input type="checkbox" id="export[{{$j}}]" name="export[{{$j}}]">
                        @endif
                    </td>
                @endif
            </tr>
            <td hidden>{{$j++}}</td>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer text-right">
        <input type="text" id="metode" name="metode" value="rolesmenu" hidden>
        <input type="text" id="iduser" name="iduser" value="{{$arruser->id}}" hidden>
        <a class="btn btn-secondary" href="{{ route('administrator.index') }}">Back</a>
        <button class="btn btn-primary">Submit</button>
    </div>
    </form>
</div>
</div>
@endsection