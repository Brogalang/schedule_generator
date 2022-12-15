<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\M_karyawan;
use App\Models\M_divisi;
use App\Models\M_schedule;
use App\Models\M_scheduleDetail;
use App\Models\M_JamKerja;
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
        if (Auth::user()->divisi !='') {
            $schedule =  DB::table('schedule')
                        ->select('schedule.bulan_scheduler as bulan_scheduler','divisi.nama_divisi as nama_divisi','schedule.id as id')
                        ->leftjoin('divisi', 'divisi.id', '=', 'schedule.divisi_scheduler')
                        ->where('divisi_scheduler','=',Auth::user()->divisi)
                        ->orderby('divisi.id','ASC')
                        ->orderby('schedule.bulan_scheduler','ASC')
                        ->get();
        }else{
            $schedule =  DB::table('schedule')
                        ->select('schedule.bulan_scheduler as bulan_scheduler','divisi.nama_divisi as nama_divisi','schedule.id as id')
                        ->leftjoin('divisi', 'divisi.id', '=', 'schedule.divisi_scheduler')
                        ->orderby('divisi.id','ASC')
                        ->orderby('schedule.bulan_scheduler','ASC')
                        ->get();
        }
        foreach ($schedule as $key => $value) {
            $hNon1[$value->id] =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','Non Shift 1')->where('schedule.id','=',$value->id)->count();
            $hNon2[$value->id] =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','Non Shift 2')->where('schedule.id','=',$value->id)->count();
            $hlvl1[$value->id] =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','1')->where('schedule.id','=',$value->id)->count();
            $hlvl2[$value->id] =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','2')->where('schedule.id','=',$value->id)->count();
            $hlvl3[$value->id] =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','3')->where('schedule.id','=',$value->id)->count();
            $hlvl4[$value->id] =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','4')->where('schedule.id','=',$value->id)->count();
            $hlvl5[$value->id] =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule_detail.tanda','=','5')->where('schedule.id','=',$value->id)->count();
        }
        
        $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        if (Auth::check()) {
            return view('schedule.table',compact('schedule','hlvl1','hlvl2','hlvl3','hlvl4','hlvl5','arrbln','hNon1','hNon2'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
        }else{
            return redirect('/');
        }
    }

   
    public function create()
    {
        if (Auth::user()->divisi !='') {
            $divisi = M_divisi::where('id','=',Auth::user()->divisi)->get();
        }else{
            $divisi = M_divisi::all();
        }
        $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        return view('schedule.add',compact('divisi','arrbln'));
    }

    public function showdivisi(Request $request)
    {
        $listdata= DB::select("
                    select nama_karyawan,
                    case 
                    when level_karyawan = 1 then 0 
                    when level_karyawan = 2 then 1 
                    when level_karyawan = 3 then 2 
                    when level_karyawan = 4 then 3 
                    when level_karyawan = 5 then 4 
                    when level_karyawan = 'Non Shift 1' then 'Non Shift 1'
                    when level_karyawan = 'Non Shift 2' then 'Non Shift 2'
                    end as 'level_karyawan'
                    from karyawan
                    where divisi_karyawan = '".$request->hasil."' order by level_karyawan asc
                    ");
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
                $idHeader=M_schedule::insertGetId([
                    'bulan_scheduler' => $request->tahun.'-'.$request->bulan,
                    'level_scheduler' => sprintf("%02d",$request->level1).'-'.sprintf("%02d",$request->level2).'-'.sprintf("%02d",$request->level3).'-'.sprintf("%02d",$request->level4).'-'.sprintf("%02d",$request->level5),
                    'divisi_scheduler' => $request->divisi_karyawan,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')
                ]);
                Alert::success('Congrats', 'You\'ve Successfully Saved Data');
                return redirect()->route('schedule.show',$idHeader);
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
            function insertNonSh($shid,$karID,$tgl,$sh,$tanda,$periodeGlobal){ ## Query Insert Detail
                M_scheduleDetail::insert([
                    'schedule_id' => $shid,
                    'karyawanid' => $karID,
                    'tanggal' => $tgl,
                    'shift' => $sh,
                    'tanda' => $tanda,
                    'periode' => $periodeGlobal,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')
                ]);
            }
            $periodeGlobal=$request->tahun.'-'.$request->bulan;
            $hari= cal_days_in_month(CAL_GREGORIAN,$request->bulan,$request->tahun);
    
            $Non1=cekjml($request->divisi_karyawan,'Non Shift 1');
            $Non2=cekjml($request->divisi_karyawan,'Non Shift 2');
            $lvl1=cekjml($request->divisi_karyawan,'1');
            $lvl2=cekjml($request->divisi_karyawan,'2');
            $lvl3=cekjml($request->divisi_karyawan,'3');
            $lvl4=cekjml($request->divisi_karyawan,'4');
            $lvl5=cekjml($request->divisi_karyawan,'5');
    
            $hNon1=$Non1->count();
            $hNon2=$Non2->count();
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
            for ($i=1; $i <=7 ; $i++) { 
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
                }elseif ($method=='insertlvl6') {
                    if ($hNon1%4!=0) {
                        Alert::error('Gagal', 'Jumlah Karyawan bukan kelipatan 4 Level 5!!!');
                        return Redirect::back();
                    }
                    for ($g=1; $g <=$hari ; $g++) { 
                        foreach ($Non1 as $nmkar) {
                            $dayend[$g]=date('D', strtotime($periodeGlobal.'-'.sprintf("%02d",$g)));
                            if ($dayend[$g]!='Sun' && $dayend[$g]!='Sat') {
                                insertNonSh($idsch,$nmkar->id,$g,'Pagi0','Non Shift 1',$periodeGlobal);
                            }else{
                                insertNonSh($idsch,$nmkar->id,$g,'Libur','Non Shift 1',$periodeGlobal);
                            }    
                        }
                    }
                }elseif ($method=='insertlvl7') {
                    if ($hNon2%4!=0) {
                        Alert::error('Gagal', 'Jumlah Karyawan bukan kelipatan 4 Level 5!!!');
                        return Redirect::back();
                    }
                    for ($x=1; $x <=$hari ; $x++) { 
                        foreach ($Non2 as $nmkar2) {
                            $dayend2[$x]=date('D', strtotime($periodeGlobal.'-'.sprintf("%02d",$x)));
                            if ($dayend2[$x]!='Sun') {
                                insertNonSh($idsch,$nmkar2->id,$x,'Pagi0','Non Shift 2',$periodeGlobal);
                            }else{
                                insertNonSh($idsch,$nmkar2->id,$x,'Libur','Non Shift 2',$periodeGlobal);
                            }    
                        }
                    }
                    // echo"<pre>";
                    // print_r($Non2);
                    // echo"<pre>";
                    // print_r($method);
                    // die();
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
                }elseif ($method=='insertlvl6') {
                    $bagian=$hNon1/4;
                    $tanda=4;
                    $empty=1;
                }elseif ($method=='insertlvl7') {
                    $bagian=$hNon2/4;
                    $tanda=4;
                    $empty=1;
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
                }elseif ($method=='insertlvl6') {
                    $pengali=$hNon1*2;
                }elseif ($method=='insertlvl7') {
                    $pengali=$hNon2*2;
                }
                ## END Untuk Hitung Jumlah dikali 2

                // echo"<pre>";
                // print_r($person);
                // die();

                if ($empty==0) {
                    generateShift ($person,$bagian,$idsch,$hari,$pengali,$tanda,$periodeGlobal); ## HARUS DINYALAKAN LAGI KALAU INI DI COMMENT
                }
            }
            Alert::success('Congrats', 'Berhasil Generate');

            // return Redirect::back();
            return redirect()->route('calendarview',$idsch);  ## HARUS DINYALAKAN LAGI KALAU INI DI COMMENT
        } 
        ### END IF
    
        die();
    }

    
    public function show(M_schedule $schedule)
    {
        $divisi = M_divisi::all();
        $hNon1 =  DB::table('karyawan')->leftjoin('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','Non Shift 1')->count();
        $hNon2 =  DB::table('karyawan')->leftjoin('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','Non Shift 2')->count();
        $hlvl1 =  DB::table('karyawan')->leftjoin('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','1')->count();
        $hlvl2 =  DB::table('karyawan')->leftjoin('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','2')->count();
        $hlvl3 =  DB::table('karyawan')->leftjoin('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','3')->count();
        $hlvl4 =  DB::table('karyawan')->leftjoin('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','4')->count();
        $hlvl5 =  DB::table('karyawan')->leftjoin('divisi', 'divisi.id', '=', 'karyawan.divisi_karyawan')->where('divisi.id','=',$schedule->divisi_scheduler)->where('karyawan.level_karyawan','=','5')->count();

        $ctNon1 =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','Non Shift 1')->count();
        $ctNon2 =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','Non Shift 2')->count();
        $ctlvl1 =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','1')->count();
        $ctlvl2 =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','2')->count();
        $ctlvl3 =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','3')->count();
        $ctlvl4 =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','4')->count();
        $ctlvl5 =  DB::table('schedule')->leftjoin('schedule_detail', 'schedule_detail.schedule_id', '=', 'schedule.id')->where('schedule.id','=',$schedule->id)->where('schedule_detail.tanda','=','5')->count();

        $idDetail=$schedule->id;
        // echo"<pre>";
        // print_r($ctlvl1);
        // die();

        $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        
        return view('schedule.show',compact('schedule','divisi','arrbln','hlvl1','hlvl2','hlvl3','hlvl4','hlvl5','ctlvl1','ctlvl2','ctlvl3','ctlvl4','ctlvl5','idDetail','hNon1','hNon2','ctNon1','ctNon2'));
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
        return view('schedule.showjadwal',compact('data','arrshift','hari','first','arrbln','id'));
    }
    public function calendarview($id)
    {
        $schedule = M_scheduleDetail::where('schedule_id','=',$id)
                            ->get();
        $first2 = M_scheduleDetail::where('schedule_id','=',$id)
                            ->orderby('tanggal','DESC')
                            ->first();
        $query= DB::select("
                    select karyawan.nama_karyawan as nama,
                    (case when shift_new!='' then case when tanggal=1 then shift_new else 'asu' end else case when tanggal=1 then shift else 'asu' end end ) as '1',
                    (case when shift_new!='' then case when tanggal=2 then shift_new else 'asu' end else case when tanggal=2 then shift else 'asu' end end ) as '2',
                    (case when shift_new!='' then case when tanggal=3 then shift_new else 'asu' end else case when tanggal=3 then shift else 'asu' end end ) as '3',
                    (case when shift_new!='' then case when tanggal=4 then shift_new else 'asu' end else case when tanggal=4 then shift else 'asu' end end ) as '4',
                    (case when shift_new!='' then case when tanggal=5 then shift_new else 'asu' end else case when tanggal=5 then shift else 'asu' end end ) as '5',
                    (case when shift_new!='' then case when tanggal=6 then shift_new else 'asu' end else case when tanggal=6 then shift else 'asu' end end ) as '6',
                    (case when shift_new!='' then case when tanggal=7 then shift_new else 'asu' end else case when tanggal=7 then shift else 'asu' end end ) as '7',
                    (case when shift_new!='' then case when tanggal=8 then shift_new else 'asu' end else case when tanggal=8 then shift else 'asu' end end ) as '8',
                    (case when shift_new!='' then case when tanggal=9 then shift_new else 'asu' end else case when tanggal=9 then shift else 'asu' end end ) as '9',
                    (case when shift_new!='' then case when tanggal=10 then shift_new else 'asu' end else case when tanggal=10 then shift else 'asu' end end ) as '10',
                    (case when shift_new!='' then case when tanggal=11 then shift_new else 'asu' end else case when tanggal=11 then shift else 'asu' end end ) as '11',
                    (case when shift_new!='' then case when tanggal=12 then shift_new else 'asu' end else case when tanggal=12 then shift else 'asu' end end ) as '12',
                    (case when shift_new!='' then case when tanggal=13 then shift_new else 'asu' end else case when tanggal=13 then shift else 'asu' end end ) as '13',
                    (case when shift_new!='' then case when tanggal=14 then shift_new else 'asu' end else case when tanggal=14 then shift else 'asu' end end ) as '14',
                    (case when shift_new!='' then case when tanggal=15 then shift_new else 'asu' end else case when tanggal=15 then shift else 'asu' end end ) as '15',
                    (case when shift_new!='' then case when tanggal=16 then shift_new else 'asu' end else case when tanggal=16 then shift else 'asu' end end ) as '16',
                    (case when shift_new!='' then case when tanggal=17 then shift_new else 'asu' end else case when tanggal=17 then shift else 'asu' end end ) as '17',
                    (case when shift_new!='' then case when tanggal=18 then shift_new else 'asu' end else case when tanggal=18 then shift else 'asu' end end ) as '18',
                    (case when shift_new!='' then case when tanggal=19 then shift_new else 'asu' end else case when tanggal=19 then shift else 'asu' end end ) as '19',
                    (case when shift_new!='' then case when tanggal=20 then shift_new else 'asu' end else case when tanggal=20 then shift else 'asu' end end ) as '20',
                    (case when shift_new!='' then case when tanggal=21 then shift_new else 'asu' end else case when tanggal=21 then shift else 'asu' end end ) as '21',
                    (case when shift_new!='' then case when tanggal=22 then shift_new else 'asu' end else case when tanggal=22 then shift else 'asu' end end ) as '22',
                    (case when shift_new!='' then case when tanggal=23 then shift_new else 'asu' end else case when tanggal=23 then shift else 'asu' end end ) as '23',
                    (case when shift_new!='' then case when tanggal=24 then shift_new else 'asu' end else case when tanggal=24 then shift else 'asu' end end ) as '24',
                    (case when shift_new!='' then case when tanggal=25 then shift_new else 'asu' end else case when tanggal=25 then shift else 'asu' end end ) as '25',
                    (case when shift_new!='' then case when tanggal=26 then shift_new else 'asu' end else case when tanggal=26 then shift else 'asu' end end ) as '26',
                    (case when shift_new!='' then case when tanggal=27 then shift_new else 'asu' end else case when tanggal=27 then shift else 'asu' end end ) as '27',
                    (case when shift_new!='' then case when tanggal=28 then shift_new else 'asu' end else case when tanggal=28 then shift else 'asu' end end ) as '28',
                    (case when shift_new!='' then case when tanggal=29 then shift_new else 'asu' end else case when tanggal=29 then shift else 'asu' end end ) as '29',
                    (case when shift_new!='' then case when tanggal=30 then shift_new else 'asu' end else case when tanggal=30 then shift else 'asu' end end ) as '30',
                    (case when shift_new!='' then case when tanggal=31 then shift_new else 'asu' end else case when tanggal=31 then shift else 'asu' end end ) as '31'
                    from schedule_detail
                    left join karyawan on schedule_detail.karyawanid=karyawan.id
                    where schedule_detail.schedule_id = '".$id."'
                    ");
        // $kary = M_karyawan::orderby('level_karyawan','ASC')
        //                 ->get();
        $arrbln2=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        if ($first2) {

        }else{
            Alert::error('Gagal', 'Schedule belum ada !!!');
            return Redirect::back();
        }
        $data2=array();
        $arrakary=array();
        // foreach ($kary as $key => $val) {
        //     $arrakary[$val->level_karyawan][$val->id]=$val->nama_karyawan;
        // }
        

        $hari2=$first2->tanggal;
        foreach ($query as $key => $val) {
            for ($i=1; $i <= $hari2 ; $i++) { 
                if ($val->$i!='asu') {
                    $data2[$val->nama][$i][$val->$i]=$val->$i;
                }
            }
        }

        $ctjam=M_JamKerja::where('deleted_at','=',NULL)->count();
        if ($ctjam==0) {
            
        }else{
            $jamkerja=M_JamKerja::where('deleted_at','=',NULL)->first();
            $arrjam['Pagi0']=$jamkerja->pagi;
            $arrjam['Siang']=$jamkerja->siang;
            $arrjam['Malam']=$jamkerja->malam;
            $arrjam['Libur']=0;
            $arrjam['CT']=0;
            $arrjam['A']=0;
            $arrjam['Sakit']=0;
            foreach ($query as $key => $val) {
                for ($i=1; $i <= $hari2 ; $i++) { 
                    if ($val->$i!='asu') {
                        $arrperjam[$val->nama][$i][$val->$i]=$arrjam[substr($val->$i,0,5)];
                        @$arrjmljam[$val->nama]+=$arrperjam[$val->nama][$i][$val->$i];
                    }
                }
            }
        }
       
        // }
        // echo"<pre>";
        // print_r($arrjmljam);
        // die();
        $j=1;
        return view('schedule.calendar',compact('data2','first2','hari2','arrbln2','id','query','j','arrjmljam'));
    }

    public function editcalendar($id)
    {
        $schedule = M_schedule::find($id);
        $optkar = DB::select("select b.karyawanid,a.nama_karyawan,b.tanggal,b.tanda from karyawan a left join schedule_detail b on a.id=b.karyawanid where b.schedule_id = '".$id."' group by b.karyawanid order by b.tanda asc");
        $divisi = M_divisi::all();
        $level=array('Non Shift 1'=>'Non Shift 1','Non Shift 2'=>'Non Shift 2','1'=>'0','2'=>'1','3'=>'2','4'=>'3','5'=>'4');
        $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
        $first = M_scheduleDetail::where('schedule_id','=',$id)
                            ->orderby('tanggal','DESC')
                            ->first();
        $hari=$first->tanggal;

        // echo"<pre>";
        // print_r($optkar);
        // die();

        return view('schedule.editschedule',compact('schedule','arrbln','divisi','optkar','hari','id','level'));
    }
    public function getshift(Request $request)
    {
        $listdata = M_scheduleDetail::where('karyawanid', '=', $request->nmkar)
                                ->where('tanggal', '=', $request->tglsch)
                                ->where('schedule_id', '=', $request->id)
                                ->get();
        return response()->json([
            'listdata' => $listdata,
        ]);
    }
    public function getkaryawan(Request $request)
    {
        if ($request->gantishift==1) {
            if ($request->roll==1) {
                $listdata= DB::select("
                        select *
                        from schedule_detail a
                        left join karyawan b on b.id=a.karyawanid
                        where a.tanda='".$request->lvl."' and a.tanggal='".$request->tgl."' and a.schedule_id='".$request->id."' and a.shift like '%%'
                        order by b.nama_karyawan asc
                        ");
            }else{
                $listdata= DB::select("
                        select *
                        from schedule_detail a
                        left join karyawan b on b.id=a.karyawanid
                        where a.tanda='".$request->lvl."' and a.tanggal='".$request->tgl."' and a.schedule_id='".$request->id."' and a.shift like '%Libur%'
                        order by b.nama_karyawan asc
                        ");
            }
        }else{
            if ($request->roll==1) {
                $listdata= DB::select("
                        select *
                        from schedule_detail a
                        left join karyawan b on b.id=a.karyawanid
                        where a.tanda='".$request->lvl."' and a.tanggal='".$request->tgl."' and a.schedule_id='".$request->id."' and a.shift not like '%%'
                        order by b.nama_karyawan asc
                        ");
            }else{
                $listdata= DB::select("
                        select *
                        from schedule_detail a
                        left join karyawan b on b.id=a.karyawanid
                        where a.tanda='".$request->lvl."' and a.tanggal='".$request->tgl."' and a.schedule_id='".$request->id."' and a.shift not like '%".$request->shift."%'
                        order by b.nama_karyawan asc
                        ");
            }
        }
        // echo"<pre>";
        // print_r($request->gantishift);
        // die();
        return response()->json([
            'listdata' => $listdata,
        ]);
    }

   
    public function edit(M_schedule $schedule)
    {
        if (Auth::user()->divisi !='') {
            $divisi = M_divisi::where('id','=',Auth::user()->divisi)->get();
        }else{
            $divisi = M_divisi::all();
        }
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
        $ceksch =  DB::table('schedule_detail')->where('schedule_id','=',$request->idEdit)->count();
        if ($ceksch==0) {
            DB::statement("UPDATE schedule SET bulan_scheduler = '".$request->tahun."-".$request->bulan."',divisi_scheduler = '".$request->divisi_karyawan."' WHERE id = '".$request->idEdit."' ");
            Alert::success('Congrats', 'You\'ve Successfully Updated Data');
        }else {
            Alert::warning('Warning', 'Data Generate Schedule sudah ada mohon hapus terlebih dahulu !!!');
        }
        return redirect()->route('schedule.index')
                        ->with('success','Product updated successfully');
    }

    public function updateshift(Request $request)
    {
        if ($request->rollback) {
            DB::statement("UPDATE schedule_detail SET shift_new = '".$request->shiftganti."' WHERE schedule_id = '".$request->iddetail."' AND karyawanid = '".$request->nmkar."' AND tanggal = '".$request->tglsch."' "); ## Ganti Karyawan Awal ke status seperti CT dll
        }else{
            if ($request->gantish) {
                DB::statement("UPDATE schedule_detail SET shift_new = '".$request->shiftsch."' WHERE schedule_id = '".$request->iddetail."' AND karyawanid = '".$request->nmkar."' AND tanggal = '".$request->tglsch."' "); ## Ganti Karyawan Awal ke status seperti CT dll
    
                DB::statement("UPDATE schedule_detail SET shift_new = '".$request->shiftganti."' WHERE schedule_id = '".$request->iddetail."' AND karyawanid = '".$request->nmkarganti."' AND tanggal = '".$request->tglsch."' "); ## Ganti Status Karyawan pengganti menjadi shift karyawan awal
            }else{
                $ctsh=M_scheduleDetail::where('karyawanid','=',$request->nmkarganti)
                                        ->where('schedule_id','=',$request->iddetail)
                                        ->where('tanggal','=',$request->tglsch)
                                        ->first();
                DB::statement("UPDATE schedule_detail SET shift_new = '".$ctsh->shift."' WHERE schedule_id = '".$request->iddetail."' AND karyawanid = '".$request->nmkar."' AND tanggal = '".$request->tglsch."' "); ## Ganti Karyawan Awal ke status seperti CT dll
    
                DB::statement("UPDATE schedule_detail SET shift_new = '".$request->shiftganti."' WHERE schedule_id = '".$request->iddetail."' AND karyawanid = '".$request->nmkarganti."' AND tanggal = '".$request->tglsch."' "); ## Ganti Status Karyawan pengganti menjadi shift karyawan awal
                // print_r($ctsh->shift);
            }
        }
        // print_r($request->shiftsch);
        // die();

        Alert::success('Congrats', 'You\'ve Successfully Updated Data');
        // return redirect()->route('calendarview',$request->iddetail);
    }

    public function destroy($id)
    {
        M_schedule::where('id', '=', $id)->delete();
        Alert::success('Congrats', 'You\'ve Successfully Deleted Data');
        return redirect()->route('schedule.index')
                        ->with('success','Product deleted successfully');
    }
    public function deletedetail(Request $request)
    {
        M_scheduleDetail::where('schedule_id', '=', $request->idDetail)->delete();
        Alert::success('Congrats', 'You\'ve Successfully Deleted Data');
        return Redirect::back();
    }
    public function deletedata(Request $request)
    {
        M_schedule::where('id', '=', $request->id_task)->delete();
        Alert::success('Congrats', 'You\'ve Successfully Deleted Data');
        return redirect()->route('schedule.index')
                        ->with('success','Product deleted successfully');
    }
}
