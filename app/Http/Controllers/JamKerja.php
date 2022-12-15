<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Http\Request;
use App\Models\M_JamKerja;
use DB;
use Auth;
class JamKerja extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function create()
    {
        $ct=M_JamKerja::where('deleted_at','=',NULL)->count();
        if ($ct==0) {
            $pagi='';
            $siang='';
            $malam='';
            $id='';
        }else{
            $jamkerja=M_JamKerja::where('deleted_at','=',NULL)->first();
            $pagi=$jamkerja->pagi;
            $siang=$jamkerja->siang;
            $malam=$jamkerja->malam;
            $id=$jamkerja->id;
        }
        return view('jamkerja.add',compact('pagi','siang','malam','id'));
        // return view('jamkerja.add');
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'pagi' => 'required',
            'siang' => 'required',
            'malam' => 'required',
        ]);
        // var_dump($request->kode_divisi);
        $ct=M_JamKerja::where('deleted_at','=',NULL)->count();
        if ($ct!=0) {
            DB::statement("UPDATE jamkerja SET deleted_at = '".date('Y-m-d H:i:s')."' WHERE id = '".$request->idEdit."'");
        }
        M_JamKerja::create([
            'pagi' => $request->pagi,
            'siang' => $request->siang,
            'malam' => $request->malam,
            'deleted_at' => NULL,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
     
        Alert::success('Congrats', 'You\'ve Successfully Saved Data');
        return redirect()->route('jamkerja.create')
                        ->with('success','Product created successfully.');
    }
}
