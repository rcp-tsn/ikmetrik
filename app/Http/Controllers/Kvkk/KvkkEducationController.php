<?php

namespace App\Http\Controllers\KvkK;

use App\Http\Controllers\Controller;
use App\Http\Requests\EducationRequest;
use App\Models\Company;
use App\Models\KvkkEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class KvkkEducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $educations = KvkkEducation::where('company_id',Auth::user()->company_id)->get();
        return view('kvkk.educations.index',compact('educations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kvkk.educations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( EducationRequest $request)
    {

        $company = Company::find(Auth::user()->company_id);
        File::makeDirectory(public_path().'/kvkk_educations/' . $company->id,0755,true,true);
        if ($request->file) {
            $file = $request->file;
            $destinationPath = 'kvkk_educations/' . $company->id;
            $fileName = time() . '-' . $file->getClientOriginalName();
            $fullFilePath = $destinationPath . '/' . $fileName;
            $file->move($destinationPath, $fileName);

        }
        else
        {
            return back()->with('danger','Eğitim Videosu Yüklenmedi');
        }

        $education = KvkkEducation::create([
            'company_id'=>Auth::user()->company_id,
            'sgk_company_id'=>0,
            'name'=>$request->name,
            'notification'=>$request->notification,
            'file'=>$fullFilePath,
            'status'=>$request->status
        ]);
        if ($education)
        {
            return redirect(route('kvkkEducations.index'))->with('success','Kayıt İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Kayıt İşlemi Başarısız');
        }



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
}
