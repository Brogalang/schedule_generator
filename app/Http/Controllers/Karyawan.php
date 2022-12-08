<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\M_karyawan;
use App\Models\M_divisi;
use DB;
use DateTime;

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
                    ->select('karyawan.id as id','divisi.nama_divisi as nama_divisi','nama_karyawan','level_karyawan','divisi_karyawan','jabatan','alamat','jk','tanggallahir','tmptlahir','tglmasuk')
                    ->join('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')
                    ->orderby('level_karyawan','ASC')
                    ->orderby('nama_karyawan','ASC')
                    ->get();

        $tanggal = new DateTime('1993-01-15');

        $today = new DateTime('today');
                    
        foreach ($karyawan as $key => $value) {
            $tglmasuk[$value->id] = new DateTime($value->tglmasuk);
            $thnlamanya[$value->id] = $today->diff($tglmasuk[$value->id])->y;
            if ($thnlamanya[$value->id]<1) {
                $thnlamanya[$value->id]="< 1";
            }else{
                $thnlamanya[$value->id]=$thnlamanya[$value->id].' Tahun';
            }

        }
      
        if (Auth::check()) {
            return view('karyawan.table',compact('karyawan','thnlamanya'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        }else{
            return redirect('/');
        }
    }

   
    public function create()
    {
        $jabatan = M_karyawan::select('jabatan')
                            ->distinct()
                            ->get();
        $divisi = M_divisi::all();
        return view('karyawan.add',compact('divisi','jabatan'));
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
        
        Alert::success('Congrats', 'You\'ve Successfully Saved Data');
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
        $jabatan = M_karyawan::select('jabatan')
                            ->distinct()
                            ->get();
        $divisi = M_divisi::all();
        $jnskelamin=array("Laki-laki" => "Laki - laki","Perempuan" => "Perempuan");
        return view('karyawan.edit',compact('karyawan','divisi','jabatan','jnskelamin'));
    }

    public function update(Request $request, M_karyawan $karyawan)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'level_karyawan' => 'required',
            'divisi_karyawan' => 'required',
        ]);
    
        $karyawan->update($request->all());
        
        Alert::success('Congrats', 'You\'ve Successfully Updated Data');
        return redirect()->route('karyawan.index')
                        ->with('success','Product updated successfully');
    }

    public function destroy($id)
    {
        M_karyawan::where('id', '=', $id)->delete();
        return redirect()->route('karyawan.index')
                        ->with('success','Product deleted successfully');
    }

    public function deletekary(Request $request)
    {
        M_karyawan::where('id', '=', $request->idKary)->delete();
        Alert::success('Congrats', 'Data Berhasil dihapus');
        return redirect()->route('karyawan.index')
                        ->with('success','Product deleted successfully');
    }
}
