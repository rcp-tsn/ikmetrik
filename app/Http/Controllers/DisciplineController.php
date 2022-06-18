<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyDisciplinaryOffenses;
use App\Models\DisciplineService;
use App\Models\Employee;
use App\Models\EmployeeDisciplineFile;
use App\Models\EmployeeDisciplineWitnee;
use App\Models\EmployeePersonalInfo;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceEmployeeDiscipline;
use App\Models\PerformanceProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use PDF;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $disciplines = PerformanceEmployeeDiscipline::where('company_id',Auth::user()->company_id)->get();
        $company_disciplines = CompanyDisciplinaryOffenses::where('company_id',Auth::user()->company_id)->get();
        return view('disciplines.discipline',compact('disciplines','company_disciplines'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $subordinates = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
        $disciplines = PerformanceEmployeeDiscipline::where('company_id',Auth::user()->company_id)->get();
        $company_disciplines = CompanyDisciplinaryOffenses::where('company_id',Auth::user()->company_id)->get();
        return view('disciplines.discipline_create',compact('disciplines','subordinates','company_disciplines'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        $employee = Employee::find($request->employee);
        if (!$employee)
        {
            return back()->with('danger','Çalışan Bulunmadı');
        }

//        $file = $request->file('files');
//        if ($file) {
//            $destinationPath = 'uploads/' . 'disciplines/'.$employee->id;
//            $fileName = time() . '-' . $file->getClientOriginalName();
//            $fullFilePath = $destinationPath . '/' . $fileName;
//            $file->move($destinationPath, $fileName);
//        }
//        if (!$request->file('files'))
//        {
//            return back()->with('danger','Doysa Yüklenmedi');
//        }
//

        $request->validate([
            'employee' => 'required',
            'date' => 'required',
            'files' => 'required',
            'definition' => 'required',
            'accept_type' => 'required',
            'map' => 'required',
            'disciplines' => 'required'
        ],
            [
                'employee' => 'Personel seçimi yapmalısınız.',
                'date.required' => 'Olay tarihi boş olamaz.',
                'files.required' => 'Olay ile alakalı görsel alanı boş olamaz.',
//                'files.mimes' => 'Olay ile alakalı görsel JPG veya PNG olmalıdır.',
                'definition.required' => 'Olay tanımı alanı boş olamaz.',
                'accept_type.required' => 'Onaylama yönetimi seçiniz.',
                'map.required' => 'Olay yeri boş olamaz.',
                'disciplines.required' => 'Disiplin suçu seçimi yapmalısınız.'
            ]);

        foreach ($request->disciplines as $key => $cipline) {
            $name = CompanyDisciplinaryOffenses::find($key);
            $disciplines[] = $name->name;
        }
        $company = Company::find(Auth::user()->company_id);
        if (!$company)
        {
            return back()->with('danger', 'Firma Bulunamadı');
        }
        $randoms = rand(0,99999999);
        $file_namem = '/uploads/disciplines/'.$employee->id.'/'.$randoms.'.pdf';



        $discipline =  PerformanceEmployeeDiscipline::create([
            'company_id' => Auth::user()->company_id,
            'employee_id' => $request->employee,
            'discipline_date' => $request->date,
            'file'=> $file_namem,
            'proposed'=>$request->proposed,
            'definition'=>$request->definition,
            'accept_type'=>$request->accept_type,
            'map'=>$request->map
        ]);


        foreach ($request->files as $files)
        {
            foreach ($files as $file)
            {
                        if ($file) {
            $destinationPath = 'uploads/' . 'disciplines/'.$employee->id;
            $fileName2 = time() . '-' . $file->getClientOriginalName();
            $fullFilePath = $destinationPath . '/' . $fileName2;
            $file->move($destinationPath, $fileName2);
        }
               EmployeeDisciplineFile::create([
                   'performance_employee_discipline_id'=>$discipline->id,
                   'file'=> $destinationPath.'/'.$fileName2,
                   'type'=>'Tutanak Ek'
               ]);
            }
        }

        $sahit1 = Employee::find($request->sahit1);
        $sahit2 = Employee::find($request->sahit2);
        $tutanakEk = EmployeeDisciplineFile::where('performance_employee_discipline_id',$discipline->id)->get();
        $pdf = PDF::loadView('disciplines.pdfs.test',compact('employee','request','disciplines','company','discipline','tutanakEk','sahit1','sahit2'));
        $pdf->save(public_path().$file_namem);

        EmployeeDisciplineFile::create([
            'performance_employee_discipline_id'=>$discipline->id,
            'file'=> $file_namem,
            'type'=>'Tutanak'
        ]);

        foreach ($request->disciplines as $key => $cipline)
        {
            DisciplineService::create([
                'performance_employee_discipline_id' => $discipline->id,
                'company_id' => Auth::user()->company_id,
                'sgk_company_id'=> $employee->sgk_company_id,
                'employee_id'=>$request->employee,
                'date'=>Date::now(),
                'discipline_id'=>$key,
            ]);
        }

        if ($request->accept_type == 2)
        {

            EmployeeDisciplineWitnee::create([
                'performance_employee_discipline_id' => $discipline->id,
                'employee_id'=>$request->sahit1,
            ]);

            EmployeeDisciplineWitnee::create([
                'performance_employee_discipline_id' => $discipline->id,
                'employee_id'=>$request->sahit2,
            ]);
        }


        if ($discipline)
        {
            return redirect(route('disciplines.index'))->with('success','Kayıt İşlemi Başarılı');
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

    public function fileUpload(Request $request)
    {
        $discipline = PerformanceEmployeeDiscipline::find($request->id);
        if (!$discipline)
        {
            return back()->with('danger','Kayıt Bulunamadı');
        }
               $file = $request->file('file');
             if ($file) {
                 $destinationPath = 'uploads/' . 'disciplines/' . $discipline->employee_id;
                 $fileName2 = time() . '-' . $file->getClientOriginalName();
                 $fullFilePath = $destinationPath . '/' . $fileName2;
                 $file->move($destinationPath, $fileName2);
             }
             else
             {
                 return back()->with('danger','Dosya Bulunamadı');
             }
            if ($discipline->status == 0)
            {
                $value =   PerformanceEmployeeDiscipline::where('id',$request->id)->update([
                    'status'=>'1',
                    'file'=>$fullFilePath
                ]);
                $sonuc = PerformanceEmployeeDiscipline::where('id',$request->id)->first();

                EmployeeDisciplineFile::create([
                    'file'=>$fullFilePath,
                    'type'=> $sonuc->status == '1' ? 'Doldurulmuş Tutanak' : 'Onaylı Form'
                ]);

                if ($value)
                {
                    return redirect(route('disciplines.index'))->with('success','Yükleme İşlemi Başarılı');
                }
                else
                {
                    return back()->with('danger','İşlem Başarısız');
                }
            }
            else
            {
                $value =   PerformanceEmployeeDiscipline::where('id',$request->id)->update([
                    'status'=>'2',
                    'file'=>$fullFilePath
                ]);
                $sonuc = PerformanceEmployeeDiscipline::where('id',$request->id)->first();

                EmployeeDisciplineFile::create([
                    'file'=>$fullFilePath,
                    'type'=> $sonuc->status == '1' ? 'Doldurulmuş Tutanak' : 'Onaylı Form'
                ]);

                if ($value)
                {
                    return redirect(route('disciplines.index'))->with('success','Yükleme İşlemi Başarılı');
                }
                else
                {
                    return back()->with('danger','İşlem Başarısız');
                }
            }








    }


    public function reportIndex($id)
    {

        $employees = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
        $employees[0] = 'TÜM PERSONELLER';
        $employee = Employee::find($id);
        if (!$employee and $id != 0)
        {
            return back()->with('Çalışlan bulunamadı');
        }
        if ($id == 0)
        {
            $disciplines = PerformanceEmployeeDiscipline::where('company_id',Auth::user()->company_id)->get();
            $infos  =  [];
            foreach ($disciplines as $discipline)
            {
                if (isset($infos[date('m',strtotime($discipline->discipline_date))]))
                {
                    $infos[date('m',strtotime($discipline->discipline_date))] =  $infos[date('m',strtotime($discipline->discipline_date))] +1;
                }
                else
                {
                    $infos[date('m',strtotime($discipline->discipline_date))] = 1;
                }

            }
        }
        else
        {
            $disciplines = PerformanceEmployeeDiscipline::where('company_id',Auth::user()->company_id)->where('employee_id',$id)->get();
            $infos  =  [];
            foreach ($disciplines as $discipline)
            {
                if (isset($infos[date('m',strtotime($discipline->discipline_date))]))
                {
                    $infos[date('m',strtotime($discipline->discipline_date))] =  $infos[date('m',strtotime($discipline->discipline_date))] +1;
                }
                else
                {
                    $infos[date('m',strtotime($discipline->discipline_date))] = 1;
                }

            }


        }

        return view('disciplines.reports.index',compact('disciplines','infos','employees','id','employee'));

    }


    public function reportIndexPdf($id)
    {

        $employees = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
        $employees[0] = 'TÜM PERSONELLER';
        $employee = Employee::find($id);
        if (!$employee and $id != 0)
        {
            return back()->with('Çalışlan bulunamadı');
        }
        if ($id == 0)
        {
            $disciplines = PerformanceEmployeeDiscipline::where('company_id',Auth::user()->company_id)->get();
            $infos  =  [];
            foreach ($disciplines as $discipline)
            {
                if (isset($infos[date('m',strtotime($discipline->discipline_date))]))
                {
                    $infos[date('m',strtotime($discipline->discipline_date))] =  $infos[date('m',strtotime($discipline->discipline_date))] +1;
                }
                else
                {
                    $infos[date('m',strtotime($discipline->discipline_date))] = 1;
                }

            }
        }
        else
        {
            $disciplines = PerformanceEmployeeDiscipline::where('company_id',Auth::user()->company_id)->where('employee_id',$id)->get();
            $infos  =  [];
            foreach ($disciplines as $discipline)
            {
                if (isset($infos[date('m',strtotime($discipline->discipline_date))]))
                {
                    $infos[date('m',strtotime($discipline->discipline_date))] =  $infos[date('m',strtotime($discipline->discipline_date))] +1;
                }
                else
                {
                    $infos[date('m',strtotime($discipline->discipline_date))] = 1;
                }

            }


        }
        $company = Company::find(Auth::user()->company_id);
        $pdf = PDF::loadView('disciplines.reports.pdf',compact('disciplines','infos','id','employee','employees','company'));
        $pdf->setOption('enable-javascript', true);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('no-stop-slow-scripts', true);
        $pdf->setOption('enable-smart-shrinking', true);
        $pdf->setOption('lowquality', false);
        $pdf->setOption('margin-top', 20);
        $pdf->setOption('margin-right', 6);
        $pdf->setOption('margin-left', 6);
        $pdf->setOption('margin-bottom', 20);

        return  $pdf->stream();


    }

    public function excelUpload(Request $request)
    {

        if (strtolower($request->file->getClientOriginalExtension()) == "xls") {
            $excel = \SimpleXLS::parse($request->file);
        } elseif (strtolower($request->file->getClientOriginalExtension()) == "xlsx") {
            $excel = \SimpleXLSX::parse($request->file);
        }
        $hatalar = null;
        $discipline = false;
        $xls = $excel->rows();
        unset($xls[0]);
       foreach ($xls as $xl)
       {
           if (!empty($xl[3]) and  !empty($xl[2]) and !empty($xl[5]) and !empty($xl[3]) and !empty($xl[4]))
           {

               $employeetc = EmployeePersonalInfo::where('identity_number',$xl[2])->first();

               if ($employeetc )
               {
                   $employee = Employee::where('id',$employeetc->employee_id)->where('company_id',Auth::user()->company_id)->first();
                   if ($employee)
                   {

                       $discipline =  PerformanceEmployeeDiscipline::create([
                           'company_id' => Auth::user()->company_id,
                           'performance_program_id' =>0,
                           'employee_id' => $employee->id,
                           'discipline_date' => \date('Y-m-d',strtotime($xl[3])),
                           'map'=>$xl[4],
                           'definition' => $xl[5]
                       ]);

                       $say = count($xl);
                       $say = $say - 6;

                       for ($i = 1;$i <= $say;$i++)
                       {

                           if (isset($xl[$i+5]))
                           {
                               $companyOfenses = CompanyDisciplinaryOffenses::where('name',$xl[$i + 5])->where('company_id',Auth::user()->company_id)->count();
                               if($companyOfenses == 0)
                               {
                                   $ofenses =  CompanyDisciplinaryOffenses::create([
                                       'name'=>$xl[$i + 5],
                                       'company_id'=>Auth::user()->company_id,
                                       'type'=>'ceza',
                                       'status'=>1
                                   ]);
                               }
                               else
                               {
                                   $ofenses = CompanyDisciplinaryOffenses::where('name',$xl[$i + 5])->where('company_id',Auth::user()->company_id)->first();
                               }
                               DisciplineService::create([
                                   'performance_employee_discipline_id' =>  $discipline->id,
                                   'company_id' => Auth::user()->company_id,
                                   'sgk_company_id'=> $employee->sgk_company_id,
                                   'employee_id'=>$employee->id,
                                   'date'=>\date('Y-m-d',strtotime($xl[3])),
                                   'discipline_id'=>$ofenses->id,
                               ]);
                           }

                       }
                   }
                   else
                   {
                       $hatalar = $hatalar.' '.$xl[2];
                   }

               }
           }
           else
           {
               return redirect(route('question_evalation',['type'=>'discipline','id'=>createHashId(0),'page'=>'1']))->with('danger','Yükleme işlemi başarısız Excel Formatı yada Doldurulmayan alanlar mevcut kontrol ediniz!');
           }



       }

        if ($discipline)
        {
            if (!empty($hatalar))
            {
                return redirect(route('question_evalation',['type'=>'discipline','id'=>createHashId(0),'page'=>'1']))->with('danger','Bazı Personellerin Kayıtları Oluşturulamadı Personel Sicil Kartlarında Kaydı Yoktur.'.$hatalar);
            }
            return redirect(route('question_evalation',['type'=>'discipline','id'=>createHashId(0),'page'=>'1']));
        }
        else
        {
            return back()->with('danger','Kayıt İşlemi Başarısız');
        }

    }
}
