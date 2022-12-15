<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\M_JamKerja;
use Auth;
use DB;
use Redirect;

class GantiPassword extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function create()
    {
        return view('resetpass');
        // return view('jamkerja.add');
    }

   
    public function store(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        // var_dump($request->kode_divisi);
        if (Auth::Attempt($data)) {
            DB::statement("UPDATE users SET password = '".Hash::make($request->password_new)."' WHERE email = '".$request->email."'");
        }else{
            Alert::warning('Warning', 'Email or Password incorrect');
            return Redirect::back();
        }
        // $ct=M_JamKerja::where('deleted_at','=',NULL)->count();
        // if ($ct!=0) {
        //     DB::statement("UPDATE jamkerja SET deleted_at = '".date('Y-m-d H:i:s')."' WHERE id = '".$request->idEdit."'");
        // }
        // M_JamKerja::create([
        //     'pagi' => $request->pagi,
        //     'siang' => $request->siang,
        //     'malam' => $request->malam,
        //     'deleted_at' => NULL,
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_at' => date('Y-m-d H:i:s')
        // ]);
     
        Alert::success('Congrats', 'You\'ve Successfully Updated Data');
        Auth::logout();
        return redirect()->route('home');
    }
}
