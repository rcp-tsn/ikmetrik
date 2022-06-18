<?php

namespace App\Http\Middleware;

use App\Models\WorkTitle;
use Closure;

class WorkingTitle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!session()->has('working_title_id')) {

            session()->flash('danger', "Devam etmek için Ünvan seçmelisiniz.");
            return redirect(route('carerr_select_worktitle'));
        }
         $work_title = session()->has('working_title_id') ? session()->get('working_title_id') : 0 ;

           $selectedWorking_title = WorkTitle::find($work_title['id'])->toArray();


        if (!$selectedWorking_title) {
            session()->flash('danger', "Devam etmek için Ünvan seçmelisiniz.");
            return redirect(route('carerr_select_worktitle'));
        }
        session(['selectWorkTitleName' => $selectedWorking_title['name']]);

        return $next($request);
    }
}
