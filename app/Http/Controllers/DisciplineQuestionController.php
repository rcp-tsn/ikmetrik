<?php

namespace App\Http\Controllers;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\CompanyDisciplinaryOffenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisciplineQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = CompanyDisciplinaryOffenses::where('company_id',Auth::user()->company_id)->get();
        return view('disciplines.questions.index',compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questions = CompanyDisciplinaryOffenses::where('company_id',Auth::user()->company_id)->get();
        return view('disciplines.questions.create',compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function company_question_store (Request $request)
    {
        $delete = CompanyDisciplinaryOffenses::where('company_id',Auth::user()->company_id)->delete([]);
        $company_questions = false;
        foreach ($request->sorular as $key => $soru) {
           if (isset( $request->sorular[$key][0]))
           {
               if (!empty( $request->sorular[$key][0]))
               {

                   $company_questions = CompanyDisciplinaryOffenses::create([
                       'company_id' => Auth::user()->company_id,
                       'name' => $soru[0],
                       'type' => $request->questions[$key][0]
                   ]);
               }
           }

        }

        if ($company_questions)
        {
            return response()->json([
                'type'=>'success',
                'message' => 'Kayıt İşlemi başarılı!'
            ]);
        }
        else
        {
            return response()->json([
                'type'=>'error',
                'message' => 'Kayıt İşlemi başarısız!'
            ]);
        }
    }

    public function questionDelete($id)
    {
        $id = HashingSlug::decodeHash($id);
        $discipline = CompanyDisciplinaryOffenses::where('id',$id)->delete([]);
        if ($discipline)
        {
            return back()->with('success','Silme İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Silme İşlemi Başarısız');
        }


    }
}
