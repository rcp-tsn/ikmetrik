<?php

namespace App\Http\Controllers\Performances;


use App\Base\ApplicationController;
use App\DataTables\SelectWorkingTitleDataTable;
use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\PerformanceWorkTitle;
use App\Models\SeniorityWorkTitle;
use App\Models\Statu;
use App\Models\WorkingTitleSettingType;
use App\Models\WorkTitle;
use App\Models\TopWorkTitle;
use Illuminate\Http\Request;


class CareerController extends ApplicationController
{
    protected $hashId = true;
    /**
     * Display a listing of the resource.
     *
     * @param SelectWorkingTitleDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {

        $working_types = WorkingTitleSettingType::all();
        return view('performances.careers.index',compact('working_types'));
    }
    public function selectWorkTitle(SelectWorkingTitleDataTable $dataTable)
    {

      return   $dataTable->render('performances.careers.selectedWorkTitles.index');

    }
    public function selectWorkTitleSelect($id)
    {
        $working_title = WorkTitle::find(HashingSlug::decodeHash($id));

        if (!$working_title) {
            return $this->flashRedirect(route('carerr.index'), 'danger', 'İşlem gerçekleştirilemedi. Kayıt bulunamadı!');
        }

        session(['working_title_id' => $working_title->toArray()]);

        return $this->flashRedirect(route('carerr.index'), 'success', 'Ünvan Seçimi başarılı bir şekilde gerçekleşti.');
    }
    public function workTitleUnset()
    {
        session(['working_title_id'=>null]);
        session(['selectWorkTitleName'=>null]);
       return $this->flashRedirect(route('carerr.index'), 'success', 'Ünvan Seçimi başarılı bir şekilde kaldırılmıştır.');

    }

    public function work_titles_setting($slug)
    {
        $work_title_id = session()->get('working_title_id');
        switch ($slug)
        {
            case 'top_working_title':


                $work_title = WorkTitle::where('id',$work_title_id)->first();

                if (!$work_title)
                {
                    return back()->with('danger','Ünvan Bulunamadı');
                }

                $work_titles = WorkTitle::where('sgk_company_id',\Auth::user()->sgk_company_id)->where('company_id',\Auth::user()->company_id)->whereNotIn('id',[$work_title_id])->get()->pluck('name','id');

                return view('performances.careers.top_work_titles.edit',compact('work_title','work_title_id','work_titles','slug'));
                break;
            case 'seniority':
                $work_title = WorkTitle::where('id',$work_title_id)->first();

                if (!$work_title)
                {
                    return back()->with('danger','Ünvan Bulunamadı');
                }

                return view('performances.careers.seniorities.edit',compact('work_title','work_title_id','slug'));
                break;
            case 'performance_puan':

                $work_title = WorkTitle::where('id',$work_title_id)->first();

                if (!$work_title)
                {
                    return back()->with('danger','Ünvan Bulunamadı');
                }

                return view('performances.careers.performances.edit',compact('work_title','work_title_id','slug'));
                break;
            case 'language':
                $work_title = WorkTitle::where('id',$work_title_id)->first();

                if (!$work_title)
                {
                    return back()->with('danger','Ünvan Bulunamadı');
                }
                return view('performances.careers.languages.edit',compact('work_title','work_title_id','slug'));


        }

    }
    public function work_titles_setting_store(Request $request,$slug)
    {
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $work_title_id = session()->get('working_title_id');

        switch ($slug)
        {
            case 'top_working_title':
                $id = $request->work_title_id;
                $work_titles = WorkTitle::where('sgk_company_id',$sgk_company_id)
                    ->where('id',$work_title_id['id'])
                    ->first();
                if (!$work_titles)
                {
                    return back()->with('danger','Beklenmeyen Bir Sorunla Karşılaşıldı');
                }
                TopWorkTitle::where('sgk_company_id',$sgk_company_id)
                    ->where('work_title_id',$work_title_id['id'])
                    ->delete([]);
                $work_title = TopWorkTitle::create([
                    'sgk_company_id' => $sgk_company_id,
                    'work_title_id'=> $work_title_id['id'],
                    'work_top_title_id'=>$request->top_work_title
                ]);

                if ($work_title)
                {
                    return back()->with('success','İşlem Başarıyla Gerçekleştirildi');
                }
                else
                {
                    return back()->with('danger','Hata İşlem Gerçekleştirilemedi');
                }
                break;

            case 'seniority':
                $id = $request->work_title_id;
                $work_titles = WorkTitle::where('sgk_company_id',$sgk_company_id)
                    ->where('id',$work_title_id['id'])
                    ->first();
                if (!$work_titles)
                {
                    return back()->with('danger','Beklenmeyen Bir Sorunla Karşılaşıldı');
                }
                SeniorityWorkTitle::where('sgk_company_id',$sgk_company_id)
                    ->where('work_title_id',$work_title_id['id'])
                    ->delete([]);
                $work_title = SeniorityWorkTitle::create([
                    'sgk_company_id' => $sgk_company_id,
                    'work_title_id'=> $work_title_id['id'],
                    'year'=>$request->senority_year
                ]);

                if ($work_title)
                {
                    return back()->with('success','İşlem Başarıyla Gerçekleştirildi');
                }
                else
                {
                    return back()->with('danger','Hata İşlem Gerçekleştirilemedi');
                }
                break;
            case 'performance_puan':
                $id = $request->work_title_id;
                $work_titles = WorkTitle::where('sgk_company_id',$sgk_company_id)
                    ->where('id',$work_title_id['id'])
                    ->first();
                if (!$work_titles)
                {
                    return back()->with('danger','Beklenmeyen Bir Sorunla Karşılaşıldı');
                }
                PerformanceWorkTitle::where('sgk_company_id',$sgk_company_id)
                    ->where('work_title_id',$work_title_id['id'])
                    ->delete([]);
                $work_title = PerformanceWorkTitle::create([
                    'sgk_company_id' => $sgk_company_id,
                    'work_title_id'=> $work_title_id['id'],
                    'puan'=>$request->performance_puan
                ]);

                if ($work_title)
                {
                    return back()->with('success','İşlem Başarıyla Gerçekleştirildi');
                }
                else
                {
                    return back()->with('danger','Hata İşlem Gerçekleştirilemedi');
                }
                break;



        }
    }
}
