<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\M_karyawan;
use App\Models\M_divisi;
use DB;

class Karyawan extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // $karyawan = M_karyawan::paginate(10);
        // $divisi = M_divisi::all();
        $karyawan = DB::table('karyawan')
                    ->select('karyawan.id as id','divisi.nama_divisi as nama_divisi','nama_karyawan','level_karyawan','divisi_karyawan')
                    ->join('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')
                    ->paginate(10);
        if (Auth::check()) {
            return view('karyawan.table',compact('karyawan'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        }else{
            return redirect('/');
        }
    }

   
    public function create()
    {
        $divisi = M_divisi::all();
        return view('karyawan.add',compact('divisi'));
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'level_karyawan' => 'required',
            'divisi_karyawan' => 'required',
        ]);
        // var_dump($request->kode_divisi);
    
        M_karyawan::create($request->all());
     
        return redirect()->route('karyawan.index')
                        ->with('success','Product created successfully.');
    }

    
    public function show(Product $product)
    {
        $menus = Menu::where('parent_id', '=', 0)->get();
        return view('datakaryawan.show',compact('product','menus'));
    }

   
    public function edit(M_karyawan $karyawan)
    {
        $divisi = M_divisi::all();
        return view('karyawan.edit',compact('karyawan','divisi'));
    }

    public function update(Request $request, M_karyawan $karyawan)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'level_karyawan' => 'required',
            'divisi_karyawan' => 'required',
        ]);
    
        $karyawan->update($request->all());
    
        return redirect()->route('karyawan.index')
                        ->with('success','Product updated successfully');
    }

    public function destroy($id)
    {
        M_karyawan::where('id', '=', $id)->delete();
        return redirect()->route('karyawan.index')
                        ->with('success','Product deleted successfully');
    }
}
