<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\M_roleMenu;
use App\Models\M_menu;
use App\Models\User;
use App\Models\M_divisi;
use DB;
use Auth;
use Redirect;

class RoleMenu extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $rolemenu = M_roleMenu::get();
        Auth::user()->divisi;
        if (Auth::user()->role!='') {
            $arruser = DB::table('users')
                        ->select('users.id as id','users.divisi as divisi','users.name as name','divisi.nama_divisi as nama_divisi','users.jabatan as jabatan','users.email as email')
                        ->leftjoin('divisi', 'divisi.id', '=', 'users.divisi')
                        ->orderby('users.created_at','ASC')
                        ->get();
        }else{
            $arruser = DB::table('users')
                        ->select('users.id as id','users.divisi as divisi','users.name as name','divisi.nama_divisi as nama_divisi','users.jabatan as jabatan','users.email as email')
                        ->leftjoin('divisi', 'divisi.id', '=', 'users.divisi')
                        ->where('role','!=','superadmin')
                        ->orderby('users.created_at','ASC')
                        ->get();
        }
        // $arruser= User::where('role','!=','superadmin')->get();
        return view('administrator.table',compact('rolemenu','arruser'))
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $jabatan = User::select('jabatan')
        ->distinct()
        ->get();
        $divisi = M_divisi::all();
        return view('administrator.add',compact('divisi','jabatan'));
    }

    public function store(Request $request)
    {
        if ($request->metode=='rolesmenu') {
            $ctmenu= M_menu::where('status','=','1')->count();
            $arrtitle=$request->title;
            $arradd=$request->add;
            $arrupdate=$request->update;
            $arrdelete=$request->delete;
            $arrexport=$request->export;
            for ($i=0; $i < $ctmenu ; $i++) { 
                M_roleMenu::where('karyawanid', '=', $request->iduser)->where('menuid','=',$arrtitle[$i],)->delete();
                if ($arradd[$i]) {
                    $add[$i]=1;
                }else{
                    $add[$i]=0;
                }
                if ($arrupdate[$i]) {
                    $update[$i]=1;
                }else{
                    $update[$i]=0;
                }
                if ($arrdelete[$i]) {
                    $delete[$i]=1;
                }else{
                    $delete[$i]=0;
                }
                if ($arrexport[$i]) {
                    $export[$i]=1;
                }else{
                    $export[$i]=0;
                }

                if ($add[$i]==1 || $update[$i]==1 || $delete[$i]==1 || $export[$i]==1) {
                    $akses[$i]=1;
                }else{
                    $akses[$i]=0;
                }

                $M_roleMenu[$i] = new M_roleMenu();     
                $M_roleMenu[$i]->karyawanid = $request->iduser;       
                $M_roleMenu[$i]->menuid = $arrtitle[$i];    
                $M_roleMenu[$i]->akses = $akses[$i];    
                $M_roleMenu[$i]->add = $add[$i];    
                $M_roleMenu[$i]->update = $update[$i];    
                $M_roleMenu[$i]->delete = $delete[$i];    
                $M_roleMenu[$i]->export = $export[$i];    
                $M_roleMenu[$i]->save();
            }
            
        }else{
            User::create([
                'email' => $request->email,
                'name' => $request->name,
                'jabatan' => $request->jabatan,
                'divisi' => $request->divisi,
                'password' => Hash::make($request->password),
                'role' => ''
            ]);
        }
        // die();
        Alert::success('Congrats', 'You\'ve Successfully Saved Data');
        // return Redirect::back();
        return redirect()->route('administrator.index')
                        ->with('success','Product created successfully.');
    }

    public function edit($id)
    {
        $jabatan = User::select('jabatan')
        ->distinct()
        ->get();
        $divisi = M_divisi::all();
        $arruser= User::where('id','=',$id)->first();
        return view('administrator.edit',compact('arruser','jabatan','divisi'));
    }

    public function rolemenu($id)
    {
        
        $arruser= User::where('id','=',$id)->first();
        $arrmenu= M_menu::where('status','=','1')->get();
        // $arrmenu = DB::table('role_menu')
        //             ->leftjoin('menu', 'menu.id', '=', 'role_menu.menuid')
        //             ->where('karyawanid','=',$id)
        //             ->orderby('menu.order','ASC')
        //             ->get();
        $arrrole= M_roleMenu::where('karyawanid','=',$id)->get();
        $ctrole= M_roleMenu::where('karyawanid','=',$id)->count();
        $dataadd=array();
        $dataupdate=array();
        $datadelete=array();
        $dataexport=array();
        if ($ctrole>0) {
            foreach ($arrrole as $key => $val) {
                $dataadd[$val->menuid]=$val->add;
                $dataupdate[$val->menuid]=$val->update;
                $datadelete[$val->menuid]=$val->delete;
                $dataexport[$val->menuid]=$val->export;
            }
        }
        // echo"<pre>";
        // print_r($arrmenu);
        // die();
        $i=1;
        $j=0;
        return view('administrator.addrole',compact('arruser','arrmenu','i','j','dataadd','dataupdate','datadelete','dataexport','ctrole'));
    }

    public function update(Request $request, User $users)
    {
        // $users->update($request->all());
        DB::statement("UPDATE users set name= '".$request->name."',divisi='".$request->divisi."',jabatan='".$request->jabatan."', email='".$request->email."',password='".Hash::make($request->password)."' WHERE id = '".$request->idEdit."'");
        
        Alert::success('Congrats', 'You\'ve Successfully Updated Data');
        return redirect()->route('administrator.index')
                        ->with('success','Product updated successfully');
    }

    public function destroy($id)
    {
        M_divisi::where('id', '=', $id)->delete();
        return redirect()->route('divisi.index')
                        ->with('success','Product deleted successfully');
    }

    public function deletedata(Request $request)
    {
        User::where('id', '=', $request->idData)->delete();
        Alert::success('Congrats', 'Data Berhasil dihapus');
        return redirect()->route('administrator.index')
                        ->with('success','Product deleted successfully');
    }
}
