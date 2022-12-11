<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\M_menu;
use Illuminate\Support\Facades\Auth;
// use Auth;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        view()->composer('*', function() {
            view()->share('administrator', M_menu::where('status','=', 2)->orderby('order','ASC')->get());

            if (Auth::check()) {
                view()->share('menus', 
                    DB::table('menu')
                    ->join('role_menu','role_menu.menuid','=','menu.id')
                    ->where('status','=', 1)
                    ->where('akses','=', 1)
                    ->where('karyawanid','=',auth()->user()->id)
                    ->orderby('order','ASC')
                    ->get()
                );
                $akses=array();
                $addsess=array();
                $updatesess=array();
                $deletesess=array();
                $exportsess=array();
                $arrmenu=DB::table('menu')
                        ->join('role_menu','role_menu.menuid','=','menu.id')
                        ->where('status','=', 1)
                        ->where('akses','=', 1)
                        ->where('karyawanid','=',auth()->user()->id)
                        ->orderby('order','ASC')
                        ->get();
                foreach ($arrmenu as $key => $value) {
                    $akses[$value->menuid]=$value->akses;
                    $addsess[$value->menuid]=$value->add;
                    $updatesess[$value->menuid]=$value->update;
                    $deletesess[$value->menuid]=$value->delete;
                    $exportsess[$value->menuid]=$value->export;
                }
                view()->share('akses', $akses);
                view()->share('addsess', $addsess);
                view()->share('updatesess', $updatesess);
                view()->share('deletesess', $deletesess);
                view()->share('exportsess', $exportsess);
            }
        });
        // $userId = Auth::id();
        // view()->share('userId',$userId);

        // $schedule =  DB::table('schedule')
        //             ->select('schedule.bulan_scheduler as bulan_scheduler','divisi.nama_divisi as nama_divisi','schedule.id as id')
        //             ->join('divisi', 'divisi.id', '=', 'schedule.divisi_scheduler')
        //             ->orderby('divisi.id','ASC')
        //             ->orderby('schedule.bulan_scheduler','ASC')
        //             ->get();
    }
}
