@extends('template.template')

@section('content')
@include('sweetalert::alert')
<div class="section-header">
    <h1>Schedule</h1>
    <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{route('home')}}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{route('karyawan.index')}}">Schedule</a></div>
    <div class="breadcrumb-item">Add</div>
    </div>
</div>

<div class="section-body">
<div class="card">
    <form class="needs-validation" action="{{ route('schedule.store') }}" method="POST" novalidate="">
    @csrf
    <div class="card-header">
        <h4>Schedule</h4><br>

        <a class="btn btn-secondary" href="{{ route('schedule.index') }}">Back</a>
        <button class="btn btn-primary">Save Schedule</button>
    </div>
    <input type="hidden" name="metode" id="metode" value="insertheader">
    <!-- <div class="card-footer">
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="form-group col-2">
                <label>Bulan</label>
                <select name="bulan" id="bulan" class="form-control">
                    @foreach($arrbln as $key => $val)
                        <option value="{{$key}}">{{$val}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-2">
                <label>Tahun</label>
                <select name="tahun" id="tahun" class="form-control">
                   @for($i=date('Y');$i>=2015;$i--)
                        <option value="{{$i}}">{{$i}}</option>
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
                    <option value="{{$div->id}}">{{$div->nama_divisi}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                Kok masih kosong?
            </div>
            <div class="valid-feedback">
                Nah gitu dong
            </div>
        </div>
        </div>
        <!--Table untuk show Divisi-->
        <label>Table Karyawan Divisi</label>
        <div class="table-responsive">
            <table class="table table-bordered table-md">
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Level Karyawan</th>
                </tr>
                <tbody id="showdiv">

                </tbody>
            </table>
        </div>
    </div>
    </form>
</div>
</div>

@endsection

@push('javascript')
<script>
    $(document).ready(function(){   
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    function clickdivisi() {
        hasil=document.getElementById("divisi_karyawan").value;
        // alert(hasil);
        $.ajax({
            data: 'hasil='+hasil,
            url: "{{ route('showdivisi') }}",
            type: "GET",
            datatype : "json",
            success: function(response) {
                $('#showdiv').html("");
                var x = 0;
                $.each(response.listdata,function(key,item){
                    x++;
                    $('#showdiv').append(
                        '<tr>\
                            <td align=center>'+x+'</td>\
                            <td align=left>'+item.nama_karyawan+'</td>\
                            <td align=left>'+item.level_karyawan+'</td>\
                        </tr>'
                    );
                });
            },
            error: function(response) {
                console.log('Error:', response);
            }
        });
    }
</script>
@endpush