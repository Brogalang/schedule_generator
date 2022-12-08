<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\M_karyawan;
use App\Models\M_divisi;
use App\Models\M_schedule;
use App\Models\M_scheduleDetail;
use DB;
use Redirect;

class Schedule extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // $schedule = M_schedule::paginate(10);
        $schedule =  DB::table('schedule')
                    ->select('schedule.bulan_scheduler as bulan_scheduler','divisi.nama_divisi as nama_divisi','schedule.id as id')
                    ->join('divisi', 'divisi.id', '=', 'schedule.divisi_scheduler')
                    ->paginate(10);
        $lvl1 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','1')->get();
        $lvl2 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','2')->get();
        $lvl3 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','3')->get();
        $lvl4 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','4')->get();
        $lvl5 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','5')->get();
        $hlvl1 = $lvl1->count();
        $hlvl2 = $lvl2->count();
        $hlvl3 = $lvl3->count();
        $hlvl4 = $lvl4->count();
        $hlvl5 = $lvl5->count();
        if (Auth::check()) {
            return view('schedule.table',compact('schedule','hlvl1','hlvl2','hlvl3','hlvl4','hlvl5'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        }else{
            return redirect('/');
        }
    }

   
    public function create()
    {
        $divisi = M_divisi::all();
        $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        return view('schedule.add',compact('divisi','arrbln'));
    }

    public function showdivisi(Request $request)
    {
        $listdata = M_karyawan::where('divisi_karyawan', '=', $request->hasil)
                                ->orderby('level_karyawan','asc')
                                ->get();
        return response()->json([
            'listdata' => $listdata,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'divisi_karyawan' => 'required',
        ]);

        ## IF Insert Header
        if ($request->metode=='insertheader' && $request->divisi_karyawan!='') {

            function ceksch($bulan,$div){ ## Untuk Dapatkan Karyawannya
                $total=M_schedule::where('bulan_scheduler', '=', $bulan)
                            ->where('divisi_scheduler','=', $div)
                            ->get();
                return $total->count();
            }

            if (ceksch($request->tahun.'-'.$request->bulan,$request->divisi_karyawan)!=0) {
                Alert::warning('Warning', 'Data sudah ada !!!');
                return redirect()->route('schedule.index');
            }else{
                M_schedule::insertGetId([
                    'bulan_scheduler' => $request->tahun.'-'.$request->bulan,
                    'level_scheduler' => sprintf("%02d",$request->level1).'-'.sprintf("%02d",$request->level2).'-'.sprintf("%02d",$request->level3).'-'.sprintf("%02d",$request->level4).'-'.sprintf("%02d",$request->level5),
                    'divisi_scheduler' => $request->divisi_karyawan,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')
                ]);
                Alert::success('Congrats', 'You\'ve Successfully Saved Data');
                return redirect()->route('schedule.index');
            }
        } 
        ## End Insert Header

        ##Strart Else
        else{ 
            function cekjml($divisi,$level){ ## Get Karyawan Berdasarkan Level
                $lvl=M_karyawan::where('divisi_karyawan', '=', $divisi)
                            ->where('level_karyawan','=', $level)
                            ->get();
                return $lvl;
            }
    
            function cekShift($nmkar,$idsch){ ## Cek Shift Terakhir (Untuk Jadwal Pertama Kali)
                $ctsh=M_scheduleDetail::where('karyawanid','=',$nmkar)
                                    ->where('schedule_id','=',$idsch)
                                    ->orderby('tanggal','DESC')
                                    ->orderby('periode','DESC')
                                    ->first();
                if ($ctsh) {
                    return $ctsh->shift;
                }else{
                    return;
                }
            }
            function cekValid($nmkar,$periodeGlobal){ ## Cek Shift Terakhir Untuk Bulan Selanjutnya
                $bln=substr($periodeGlobal,5,2);
                $thn=substr($periodeGlobal,0,4);
                if ($bln=='01') {
                    $bln='12';
                    $thn=$thn-1;
                }else{
                    $bln=sprintf("%02d",$bln-1);
                }
                $periode=$thn.'-'.$bln;
                $ctsh=M_scheduleDetail::where('karyawanid','=',$nmkar)
                                    ->where('periode','=',$periode)
                                    ->orderby('tanggal','DESC')
                                    ->orderby('periode','DESC')
                                    ->first();
                if ($ctsh) {
                    return $ctsh->shift;
                }else{
                    return;
                }
            }
            function cektgllast($schid,$karid){ ## Untuk Stop Insert di Looping Jika Sudah Tanggal Terakhir
                $shjml=M_scheduleDetail::where('schedule_id','=',$schid)
                                    ->where('karyawanid','=',$karid)
                                    ->orderby('tanggal','DESC')
                                    ->get();
                
                return $shjml->count();
            }
            function cekorgjml($schid,$tanda){ ## Untuk Hitung Jumlah yang diinsert pertama kali sesuai dengan Algoritma tidak
                $shjml=M_scheduleDetail::where('schedule_id','=',$schid)
                                    ->where('tanda','=',$tanda)
                                    ->orderby('tanggal','DESC')
                                    ->get();
                
                return $shjml->count();
            }
            function ambilid($bulan,$tahun,$divisi){ ## Untuk ambil ID Schedule header nya
                $shjml=M_schedule::where('bulan_scheduler','=',$tahun.'-'.$bulan)
                                ->where('divisi_scheduler','=',$divisi)
                                ->first();
                
                return $shjml->id;
            }
            function insertSh($shid,$karID,$tgl,$sh,$order,$tanda,$periodeGlobal){ ## Query Insert Detail
                M_scheduleDetail::insert([
                    'schedule_id' => $shid,
                    'karyawanid' => $karID,
                    'tanggal' => $tgl,
                    'shift' => $sh.sprintf("%02d",$order),
                    'tanda' => $tanda,
                    'periode' => $periodeGlobal,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')
                ]);
            }
            $periodeGlobal=$request->tahun.'-'.$request->bulan;
            $hari= cal_days_in_month(CAL_GREGORIAN,$request->bulan,$request->tahun);
    
            $lvl1=cekjml($request->divisi_karyawan,'1');
            $lvl2=cekjml($request->divisi_karyawan,'2');
            $lvl3=cekjml($request->divisi_karyawan,'3');
            $lvl4=cekjml($request->divisi_karyawan,'4');
            $lvl5=cekjml($request->divisi_karyawan,'5');
    
            $hlvl1=$lvl1->count();
            $hlvl2=$lvl2->count();
            $hlvl3=$lvl3->count();
            $hlvl4=$lvl4->count();
            $hlvl5=$lvl5->count();
            $idsch=ambilid($request->bulan,$request->tahun,$request->divisi_karyawan);

            

            // foreach ($lvl1 as $nmkar) {
            //     $person[]=$nmkar->nama_karyawan;
            // }
            
           
            $data = array();
            function generateShift ($person, $level,$idsch,$day,$pengali,$tanda,$periodeGlobal) {
                $shift = Array("Pagi", "Siang", "Malam", "Libur");
                $getPerson =getShiftPerPerson($level);
                // $day = cal_days_in_month (CAL_GREGORIAN, 01, 2022);
    
                extractData ($day, $shift, $getPerson, $person, 0,$idsch,$pengali,$tanda,$periodeGlobal);
            }
            function getShiftPerPerson($level) {
                return $level;
            }
    
            function extractData ($day, $shift, $totalPerson, $person, $personNumber,$idsch,$pengali,$tanda,$periodeGlobal){ 
                for ($i= 1; $i <= $totalPerson; $i++) {
                    processData ($day, $shift, $person, $i, $personNumber++,$idsch,$pengali,$totalPerson,$tanda,$periodeGlobal);
                }
            }
    
            function processData ($day, $shift, $person, $running, $personNumber,$idsch,$pengali,$totalPerson,$tanda,$periodeGlobal) { 
                global $data;
                // global $idsch;
                // $data2=array();

                $run = 0;
                $shiftPerPerson = 2;
                $ct=1;
                $lopping=$totalPerson*2;
                $cb=array();
                $cbV=array();
                foreach ($shift as $key => $value) {
                    for ($i= 1; $i <= $lopping; $i++) { 
                        if ($i<=2) {

                            $validation=cekValid($person[$personNumber],$periodeGlobal);
                            if ($validation) { ## Validasi apakah dibulan sebelumnya sudah ada data belum
                                for ($i=1; $i <=$day ; $i++) { 
                                    for ($j=0; $j<count($person); $j++) {

                                        $cekV=cekValid($person[$j],$periodeGlobal);
                                        $cek=cekShift($person[$j],$idsch);
                                        if($i==1){
                                            if ($cekV=="Pagi01" || $cekV=="Libur02") {
                                                $sh="Pagi";
                                            }elseif ($cekV=="Siang01" || $cekV=="Pagi02") {
                                                $sh="Siang";
                                            }elseif ($cekV=="Malam01" || $cekV=='Siang02') {
                                                $sh="Malam";
                                            }elseif ($cekV=="Libur01" || $cekV=='Malam02') {
                                                $sh="Libur";
                                            }
                                            if (substr($cekV,-1,1)==2) {
                                                $cbV[$j]=1;
                                            }else{
                                                $cbV[$j]=2;
                                            }
                                        }else{
                                            if ($cek=="Pagi02" || $cek=='Siang01') {
                                                $sh="Siang";
                                            }elseif ($cek=="Siang02" || $cek=='Malam01') {
                                                $sh="Malam";
                                            }elseif ($cek=="Malam02" || $cek=='Libur01') {
                                                $sh="Libur";
                                            }elseif ($cek=="Libur02" || $cek=='Pagi01') {
                                                $sh="Pagi";
                                            }
                                        }
                                       
                                        $data3[$i][$j]=$person[$j].'-'.$sh.$cbV[$j].'-{'.$cekV.'}'.substr($sh.$cbV[$j],-1,1);
                                        if (cektgllast($idsch,$person[$j])<$day) {
                                            insertSh($idsch,$person[$j],$i,$sh,$cbV[$j],$tanda,$periodeGlobal); ## PENTING (Ini untuk Bulan Setelahnya jika sudah ada data di bulan sebelumnya)
                                        }
                                        $cbV[$j]++;
                                        if ($cbV[$j]>2) {
                                            $cbV[$j]=1;
                                        }
                                    }
                                }
                            }else{
                                // $data[$i][$value.sprintf("%02d",$i)][$running]=$person[$personNumber].'-'.($personNumber).'-'.$running.'-'.$pengali;
                                if (cekorgjml($idsch,$tanda)<$pengali) {
                                   insertSh($idsch,$person[$personNumber],$i,$value,$ct,$tanda,$periodeGlobal); ## PENTING (Ini untuk mapping Bulan Awal jika sebelumnya belum ada)
                                }
                            }

                            $ct++;
                            if ($ct>2) {
                                $ct=1;
                            }
                        }
                        $run++;
                        if ($run === $shiftPerPerson) {
                            $personNumber++;
                            $run = 0;
                        }
                        
                        if ($personNumber ==count($person)) { 
                            $personNumber = 0;
                        }
                    }
                }
                if (cekorgjml($idsch,$tanda)==$pengali) {
                    for ($i=3; $i <=$day ; $i++) { 
                        for ($j=0; $j<count($person); $j++) {
                            $cek=cekShift($person[$j],$idsch);
                            if ($cek=="Pagi02" || $cek=='Siang01') {
                                $sh="Siang";
                            }elseif ($cek=="Siang02" || $cek=='Malam01') {
                                $sh="Malam";
                            }elseif ($cek=="Malam02" || $cek=='Libur01') {
                                $sh="Libur";
                            }elseif ($cek=="Libur02" || $cek=='Pagi01') {
                                $sh="Pagi";
                            }
                            if($i==3){
                                $cb[$j]=1;
                            }
                            $data2[$i][$j]=$person[$j].'-'.$sh.$cb[$j];
                            if (cektgllast($idsch,$person[$j])<$day) {
                                insertSh($idsch,$person[$j],$i,$sh,$cb[$j],$tanda,$periodeGlobal); ## PENTING (Ini untuk Data setelah tanggal 3 untuk Bulan awal)
                            }
                            $cb[$j]++;
                            if ($cb[$j]>2) {
                                $cb[$j]=1;
                            }
                        }
                    }
                }
                // echo"<pre>";
                // print_r($data3);
                
            }
            $person=array();
            for ($i=1; $i <=5 ; $i++) { 
            $empty=0;
                $method=$request->metode.$i;
                // print_r($method);
                // die();
                unset($person);
                if ($method=='insertlvl1') {
                    if ($hlvl1%4!=0) {
                        Alert::error('Gagal', 'Jumlah Karyawan bukan kelipatan 4 Level 1!!!');
                        return Redirect::back();
                    }
                    foreach ($lvl1 as $nmkar) {
                        $person[]=$nmkar->id;
                    }
                }elseif ($method=='insertlvl2') {
                    if ($hlvl2%4!=0) {
                        Alert::error('Gagal', 'Jumlah Karyawan bukan kelipatan 4 Level 2!!!');
                        return Redirect::back();
                    }
                    foreach ($lvl2 as $nmkar) {
                        $person[]=$nmkar->id;
                    }
                }elseif ($method=='insertlvl3') {
                    if ($hlvl3%4!=0) {
                        Alert::error('Gagal', 'Jumlah Karyawan bukan kelipatan 4 Level 3!!!');
                        return Redirect::back();
                    }
                    foreach ($lvl3 as $nmkar) {
                        $person[]=$nmkar->id;
                    }
                }elseif ($method=='insertlvl4') {
                    if ($hlvl4%4!=0) {
                        Alert::error('Gagal', 'Jumlah Karyawan bukan kelipatan 4 Level 4!!!');
                        return Redirect::back();
                    }
                    foreach ($lvl4 as $nmkar) {
                        $person[]=$nmkar->id;
                    }
                }elseif ($method=='insertlvl5') {
                    if ($hlvl5%4!=0) {
                        Alert::error('Gagal', 'Jumlah Karyawan bukan kelipatan 4 Level 5!!!');
                        return Redirect::back();
                    }
                    foreach ($lvl5 as $nmkar) {
                        $person[]=$nmkar->id;
                    }
                }
                ## Start Untuk Hitung Jumlah Pershift nya
                if ($method=='insertlvl1') {
                    $bagian=$hlvl1/4;
                    $tanda=1;
                    if ($hlvl1==0) {
                        $empty=1;
                    }
                }elseif ($method=='insertlvl2') {
                    $bagian=$hlvl2/4;
                    $tanda=2;
                    if ($hlvl2==0) {
                        $empty=1;
                    }
                }elseif ($method=='insertlvl3') {
                    $bagian=$hlvl3/4;
                    $tanda=3;
                    if ($hlvl3==0) {
                        $empty=1;
                    }
                }elseif ($method=='insertlvl4') {
                    $bagian=$hlvl4/4;
                    $tanda=4;
                    if ($hlvl4==0) {
                        $empty=1;
                    }
                }elseif ($method=='insertlvl5') {
                    $bagian=$hlvl5/4;
                    $tanda=5;
                    if ($hlvl5==0) {
                        $empty=1;
                    }
                }
                ## END Untuk Hitung Jumlah Pershift nya

                ## Start Untuk Hitung Jumlah dikali 2
                if ($method=='insertlvl1') {
                    $pengali=$hlvl1*2;
                }elseif ($method=='insertlvl2') {
                    $pengali=$hlvl2*2;
                }elseif ($method=='insertlvl3') {
                    $pengali=$hlvl3*2;
                }elseif ($method=='insertlvl4') {
                    $pengali=$hlvl4*2;
                }elseif ($method=='insertlvl5') {
                    $pengali=$hlvl5*2;
                }
                ## END Untuk Hitung Jumlah dikali 2
                if ($empty==0) {
                    generateShift ($person,$bagian,$idsch,$hari,$pengali,$tanda,$periodeGlobal);
                }
            }
            Alert::success('Congrats', 'Berhasil Generate');

            return Redirect::back();
        } 
        ### END IF
    
        die();
    }

    
    public function show(M_schedule $schedule)
    {
        $divisi = M_divisi::all();
        $hlvl1 =  DB::table('karyawan')->join('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','1')->count();
        $hlvl2 =  DB::table('karyawan')->join('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','2')->count();
        $hlvl3 =  DB::table('karyawan')->join('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','3')->count();
        $hlvl4 =  DB::table('karyawan')->join('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','4')->count();
        $hlvl5 =  DB::table('karyawan')->join('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','5')->count();

        $ctlvl1 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','1')->count();
        $ctlvl2 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','2')->count();
        $ctlvl3 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','3')->count();
        $ctlvl4 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','4')->count();
        $ctlvl5 =  DB::table('schedule')->join('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','5')->count();

        $idDetail=$schedule->id;
        // echo"<pre>";
        // print_r($ctlvl1);
        // die();

        $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        
        return view('schedule.show',compact('schedule','divisi','arrbln','hlvl1','hlvl2','hlvl3','hlvl4','hlvl5','ctlvl1','ctlvl2','ctlvl3','ctlvl4','ctlvl5','idDetail'));
    }

    public function showcalendar($id)
    {
        $schedule = M_scheduleDetail::where('schedule_id','=',$id)
                            ->get();
        $first = M_scheduleDetail::where('schedule_id','=',$id)
                            ->orderby('tanggal','DESC')
                            ->first();
        $kary = M_karyawan::orderby('level_karyawan','ASC')
                        ->get();
        $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        if ($first) {

        }else{
            Alert::error('Gagal', 'Schedule belum ada !!!');
            return Redirect::back();
        }
        $data=array();
        $arrakary=array();
        foreach ($kary as $key => $val) {
            $arrakary[$val->level_karyawan][$val->id]=$val->nama_karyawan;
        }
        $hari=$first->tanggal;
        foreach ($schedule as $key => $val) {
            for ($i=1; $i <= $hari ; $i++) { 
                if (substr($val->shift,0,5)=='Pagi0') {
                    $shift="Pagi";
                }else{
                    $shift=substr($val->shift,0,5);
                }
                // $data[$val->tanggal][$shift][$val->tanda][$val->karyawanid]=$arrakary[$val->tanda][$val->karyawanid];
                $arrshift[$shift]=$shift;
                $arrlevel[$shift][$val->tanggal][$val->tanda]=$val->tanda;
                $arrkar[$shift][$val->tanggal][$val->tanda][$val->karyawanid]=$arrakary[$val->tanda][$val->karyawanid];
            }
        }
        foreach ($arrshift as $key => $val) {
            for ($i=1; $i <= $hari ; $i++) { 
                foreach ($arrkar[$key][$i] as $key1 => $val1) {
                    foreach ($val1 as $key2 => $val2) {
                        $data[$i][$val][$key1][$key2]=$val2;
                    }
                }
            }
        }
        // echo"<pre>";
        // print_r($data);
        // die();
        return view('schedule.showjadwal',compact('data','arrshift','hari','first','arrbln'));
    }

   
    public function edit(M_schedule $schedule)
    {
        $divisi = M_divisi::all();
        $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        return view('schedule.edit',compact('schedule','arrbln','divisi'));
    }

    public function update(Request $request, M_schedule $schedule)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'divisi_karyawan' => 'required',
        ]);
    
        $schedule->update($request->all());
        Alert::success('Congrats', 'Data Berhasil diupdate');
        return redirect()->route('schedule.index')
                        ->with('success','Product updated successfully');
    }

    public function destroy($id)
    {
        M_schedule::where('id', '=', $id)->delete();
        Alert::success('Congrats', 'Data Berhasil dihapus');
        return redirect()->route('schedule.index')
                        ->with('success','Product deleted successfully');
    }
    public function deletedetail($id)
    {
        M_scheduleDetail::where('schedule_id', '=', $id)->delete();
        Alert::success('Congrats', 'Data Berhasil dihapus');
        return Redirect::back();
    }
}
