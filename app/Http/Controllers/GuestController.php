<?php

namespace App\Http\Controllers;

use App\Helpers\HashingSlug;
use App\Models\Answer;
use App\Models\Employee;
use App\User;
use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\Survey;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{


    public function live($id, Request $request)
    {

        $orj = $id;
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        if(Auth::user()->email) {
            $employee = Employee::where('email', Auth::user()->email)->first();
            if(isset($employee->id))
            {
                $has = Entry::where('survey_id', $survey->id)->where('employee_id', $employee->id)->first();
                if ($has) {
                    return redirect(route('surveys.completed', [$orj, 0]))->with('success', '');
                }
            }
            else
            {
                return back()->with('danger','Kişi Çalışan Olarak Gözükmemektedir.');
            }

        }

        if($survey->active == 0) {
            return redirect(route('surveys.completed', [$orj, 2]))->with('success', '');
        }
        return view('surveys.live', compact('survey','request'));
    }

    public function surveyCompleted($id, $type)
    {
        $orj = $id;
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        return view('surveys.survey_last', [
            'survey' => $survey,
            'type' => $type
        ]);
    }


    public function livePost($id, Request $request)
    {
        $orj = $id;
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        if(!$survey) {
            return back()->with('danger','İşlem gerçekleştirilemedi. Kayıt bulunamadı!!!');
        }
        $employee = Employee::where('email', $request->email)->first();

      $entry =   Entry::create([
            'survey_id'=>$id,
            'employee_id'=>$employee->id
        ]);
      if ($entry)
      {
          if(isset($request->radio))
          {
              foreach ($request->radio as $key=>$radio)
              {
                  Answer::create([
                      'question_id'=>$key,
                      'entry_id'=>$entry->id,
                      'value'=>$radio
                  ]);
              }
          }
      }



        return redirect(route('surveys.completed', [$orj, 1]))->with('success', '');

    }

}
