<?php

namespace App\Http\Controllers;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\Surveys\SurveyCreateRequest;
use App\Http\Requests\Surveys\SurveyUpdateRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Question;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;
use App\Models\Entry;



class SurveyController extends Controller
{
    public function index()
    {
        $employee = Employee::find(Auth::user()->employee_id);
        if (isset($employee->id))
        {
            //$entrity = Entry::where('employee_id',$employee->id)->get()->pluck('survey_id')->toArray();

            $surveys = Survey::where('company_id', auth()->user()->company_id)->orderBy('id', 'DESC')->get();
            return view('surveys.index', [
                'surveys' => $surveys
            ]);
        }
        else
        {
            return back()->with('danger','Sayın :'.Auth::user()->name. ' Çalışanlar Listesinde Kaydınız Bulunamadı İk ile Görüşün ');
        }

    }

    public function create()
    {
        $survey_scopes = getAllEducationScopes();
        return view('surveys.create', compact('survey_scopes'));
    }

    public function store(SurveyCreateRequest $request)
    {

        if(isset($request->survey_scope) && is_array($request->survey_scope)) {
            foreach($request->survey_scope as $key => $value) {
                if($value == 0) {
                    $orj = 0;
                    break;
                } else {
                    $survey_scope_lasts[] = $value;
                }
            }
        } else {
            $orj = 0;
        }
        if(isset($orj)) {
            $req['survey_scope'] = ['0' => 0];
        } else {
            $req['survey_scope'] = $survey_scope_lasts;
        }

        $in_values = 1;
        foreach($req['survey_scope'] as $key => $value) {
            if($value == 0) {
                $in_values = 0;
                break;
            }
        }

        if($in_values == 0) {
            $scopes = 0;
        } else {
            $scopes = implode(',', $req['survey_scope']);
        }



        $survey = Survey::create(['settings' => '{"accept-guest-entries": true}', 'company_id' => Auth::user()->company_id, 'name' => $request->name, 'active' => 1, 'description' => $request->description, 'scopes' => $scopes]);





        return redirect(route('surveys.edit', createHashId($survey->id)))->with('success', 'Anket adı sisteme başarılı bir şekilde kayıt edilmiştir. Anket oluşturma işlemine devam etmektesiniz.');

    }

    public function edit($id)
    {
        $orj = $id;
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        return view('surveys.edit', [
            'survey' => $survey
        ]);
    }
    public function active($id)
    {
        $orj = $id;
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        $survey->update([
            'active' => 1
        ]);
        return back();
    }

    public function show($id)
    {
        $orj = $id;
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        return view('surveys.show', [
            'survey' => $survey
        ]);
    }

    public function deactive($id)
    {
        $orj = $id;
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        $survey->update([
            'active' => 0
        ]);
        return back();
    }


    public function update($id, SurveyUpdateRequest $request)
    {
        $orj = $id;
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        if(!$survey) {
            return back()->with('danger','İşlem gerçekleştirilemedi. Kayıt bulunamadı!!!');
        }
        $question_types = $request->question_types;
        $questions = $request->questions;
        $answers = $request->answers;

        if ($question_types == null)
        {
            return back()->with('danger','Soru Eklenmedi');
        }
        foreach($question_types as $key => $value) {
            if(strlen($questions[$key]) > 0) {
                if($value > 0) {

                           $options = explode("\n", str_replace("\r", "", $answers[$key]));
                           $deger = '';
                            foreach ($options as $option)
                            {
                                $deger = $deger.'"'.$option.'",';
                            }
                            $deger = rtrim($deger,",");
                            $deger = '['.$deger.']';
                    Question::create([
                        'survey_id'=>$survey->id,
                        'content' => $questions[$key],
                        'type' => 'radio',
                        'options' => $deger
                    ]);
                } else {
                    Question::create([
                        'survey_id'=>$survey->id,
                        'content' => $questions[$key],
                    ]);
                }
            }

        }

//        try {
//
//            $employees = Employee::where('company_id', auth()->user()->company_id)->whereIn('department_id', [$survey->scopes])->get();
//            foreach($employees as $employee) {
//                if (filter_var($employee->email, FILTER_VALIDATE_EMAIL)) {
//                    Mail::to([$employee->email])->send(new NewSurveyMail(
//                        $employee, $survey
//                    ));
//                }
//            }
//
//        } catch (Swift_TransportException $e) {
//
//        }
        return redirect(route('surveys.index'))->with('success', 'Anket başarılı bir şekilde sisteme eklenmiştir.');
    }

    public function delete($id)
    {
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        if (!$survey) {
            return back()->with('danger','İşlem gerçekleştirilemedi. Hatalı kayıt seçimi yapıldı!!!');
        }
        $q_ids = Question::where('survey_id', $survey->id)->pluck('id');
        Answer::whereIn('question_id', $q_ids)->delete();
        Question::where('survey_id', $survey->id)->delete();

        Entry::where('survey_id', $survey->id)->delete();

        $survey->delete();
        return redirect(route('surveys.index'))->with('success','Silme işlemi yapıldı.');
    }

    public function reports($id)
    {
        $id = HashingSlug::decodeHash($id);
        $survey = Survey::find($id);
        $entries = Entry::where('survey_id',$id)->get();
        if (!$survey)
        {
            return back()->with('danger','Anket Bulunamadı');
        }

        $questions = Question::where('survey_id',$id)->get();
        if (count($questions->toArray()) ==0)
        {
            return back()->with('danger','Sorular Bulunamadı');
        }

        foreach ($questions as $question)
        {
            $values = [];
            $options[] = $question->options;
            $option = explode(',',$options[0]);
           foreach ($option as $key => $value)
           {
               $deger = str_replace('["','',$value);
               $deger =  str_replace('"]','',$deger);
               $deger =  str_replace('"','',$deger);
               $values[] = trim($deger);
           }

           if (count($values) <=0)
           {
               return back()->with('danger','Sorular Bulunamadı');

           }
            $sections = $question->answer();
            $toplamCevapKey = array_keys($sections['toplamCevap']);
            $questionCevapKey = array_keys($sections['section']);
              foreach ($sections['section'] as $key => $value)
              {
                  if (isset($questionCevap[$key]))
                  {
                      $questionCevap[$key] = $questionCevap[$key] + $value;
                  }
                  else
                  {
                      $questionCevap[$key] = $value;
                  }
              }

            if (!array_key_exists(0, $toplamCevapKey))
            {

                $questionToplamCevap[$question->id] = [0];
            }
            else
            {
                $questionToplamCevap[$toplamCevapKey[0]] = $sections['toplamCevap'][$toplamCevapKey[0]];
            }
        }


        return view('surveys.reports',compact('questions','values','options','questionToplamCevap','questionCevap'));
    }

}
