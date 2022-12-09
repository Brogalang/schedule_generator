<?php

namespace App\Exports;

use App\Models\M_scheduleDetail;
use App\Models\M_karyawan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScheduleExport implements FromView
{
    public function view(): View
        {

            $schedule = M_scheduleDetail::get();
            $first = M_scheduleDetail::orderby('tanggal','DESC')
                                ->first();
            $kary = M_karyawan::orderby('level_karyawan','ASC')
                            ->get();
            $arrbln=array('01' => "Januari",'02' => "Februari",'03' => "Maret",'04' => "April",'05' => "Mei",'06' => "Juni",'07' => "Juli",'08' => "Agustus",'09' => "Sepetember",'10' => "Oktober",'11' => "November",'12' => "Desember");
           
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
            //export adalah file export.blade.php yang ada di folder views
            return view('schedule.showjadwal', [
                //data adalah value yang akan kita gunakan pada blade nanti
                //User::all() mengambil seluruh data user dan disimpan pada variabel data
                'data' => $data,
                'first' => $first,
                'arrbln' => $arrbln,
                'arrshift' => $arrshift,
                'hari' => $hari,
            ]);
        }
}
