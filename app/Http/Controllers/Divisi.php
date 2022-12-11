<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\M_divisi;

class Divisi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $divisi = M_divisi::get();
        return view('divisi.table',compact('divisi'))
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

   
    public function create()
    {
        return view('divisi.add');
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'kode_divisi' => 'required',
            'nama_divisi' => 'required',
        ]);
        // var_dump($request->kode_divisi);
    
        M_divisi::create($request->all());
     
        Alert::success('Congrats', 'You\'ve Successfully Saved Data');
        return redirect()->route('divisi.index')
                        ->with('success','Product created successfully.');
    }

    public function edit(M_divisi $divisi)
    {
        return view('divisi.edit',compact('divisi'));
    }

    public function update(Request $request, M_divisi $divisi)
    {
        $request->validate([
            'kode_divisi' => 'required',
            'nama_divisi' => 'required',
        ]);
    
        $divisi->update($request->all());
        
        Alert::success('Congrats', 'You\'ve Successfully Updated Data');
        return redirect()->route('divisi.index')
                        ->with('success','Product updated successfully');
    }

    public function destroy($id)
    {
        M_divisi::where('id', '=', $id)->delete();
        return redirect()->route('divisi.index')
                        ->with('success','Product deleted successfully');
    }

    public function deletediv(Request $request)
    {
        M_divisi::where('id', '=', $request->idDiv)->delete();
        Alert::success('Congrats', 'Data Berhasil dihapus');
        return redirect()->route('divisi.index')
                        ->with('success','Product deleted successfully');
    }
}
