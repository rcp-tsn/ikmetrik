<?php

namespace App\Http\Controllers\Performances;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentTarget;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramType;
use App\Models\PerformanceTarget;
use App\Models\PerformanceTargetService;
use Illuminate\Http\Request;

class TargetController extends Controller
{
   public function index($type)
   {

       switch ($type)
       {
           case 'performances':
               $targets = PerformanceTarget::where('company_id',\Auth::user()->company_id)->get();
               return view('performances.programs.targets.performance.index',compact('targets'));
               break;
           case 'department':
               $targets = DepartmentTarget::where('company_id',\Auth::user()->company_id)->get();
               return view('performances.programs.targets.department.index',compact('targets'));
       }
   }
   public function create($type)
   {
       switch ($type)
       {
           case 'performances':
               $performance = PerformanceProgram::where('company_id',\Auth::user()->company_id)
                   ->where('status','1')
                   ->first();
                   if(!$performance){return back()->with('danger','Perfirmans program Bulunamadi');}
               $performance_program_types = PerformanceProgramType::where('performance_program_id',$performance->id)->get();
               return view('performances.programs.targets.performance.create',compact('performance_program_types','performance'));
               break;
           case 'department':
               $departments = Department::where('company_id',\Auth::user()->company_id)->get();
               return view('performances.programs.targets.department.create',compact('departments'));
       }

   }
   public function store(Request $request,$type)
   {
       switch ($type)
       {
           case 'performances':
           {
               foreach ($request->targets as $id => $target)
               {
                   $performance_program_type = PerformanceProgramType::where('id',$id)->first();
                   if($target[0] > $performance_program_type->puan)
                   {
                       return back()->with('danger',$performance_program_type->performance_type().' hedef değeri Puan dan Daha Yüksek');
                   }
               }

               $performance = PerformanceProgram::where('company_id',\Auth::user()->company_id)
                   ->where('status','1')
                   ->first();
               $control = PerformanceTarget::where('performance_program_id',$performance->id)->first();
               if ($control)
               {
                   return back()->with('danger','Bu Program İçin Daha Önce Hedef Belirlenmiş');
               }

               $performance_target = PerformanceTarget::create([
                   'company_id'=>\Auth::user()->company_id,
                   'sgk_company_id'=> \Auth::user()->sgk_company_id,
                   'performance_program_id'=> $performance->id
               ]);

               foreach ($request->targets as $id => $target)
               {
                   if (!empty($target[0]))
                   {
                       $performance_type = PerformanceProgramType::where('id',$id)->first();
                       $target_service = PerformanceTargetService::create([
                           'performance_program_target_id' => $performance_target->id,
                           'performance_program_type_id' => $id,
                           'performance_type_id' => $performance_type->performance_type_id,
                           'target_puan' => $target[0]
                       ]);
                   }

               }
               if ($performance_target)
               {
                   return redirect(route('target.index','performances'))->with('success','Kayıt İşlemi Başarılu');
               }
               else
               {
                   return back()->with('danger','Kayıt işlemi Başarısız');
               }

           }
           break;
           case 'department':

               foreach ($request->department as $id =>  $department)
               {
                   if (!empty($request->name[$id]) and !empty($request->targets[$id]))
                   {
                       $target_department = DepartmentTarget::create([
                           'company_id'=>\Auth::user()->company_id,
                           'sgk_company_id' =>\Auth::user()->sgk_company_id,
                           'department_id'=>$id,
                           'name' => $request->name[$id],
                           'target'=>$request->target[$id],
                           'happening' => $request->targets[$id],
                           'start_date' => '2021-01-01',
                           'finish_date' => '2021-01-01',



                       ]);
                   }

               }

              if (isset($target_department))
              {
                  return redirect(route('target.index','department'))->with('success','Kayıt İşlemi Başarılı');
              }
              else
              {
                  return back()->with('danger','Kayıt İşlemi Başarısız');
              }

       }
   }
   public function edit($id,$type)
   {

       switch ($type)
       {
           case 'performances':
               $id = HashingSlug::decodeHash($id);

               $target = PerformanceTarget::find($id);
               if (!$target)
               {
                   return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
               }
               $performance = PerformanceProgram::where('id',$target->performance_program_id)
                   ->first();
               $performance_program_types = PerformanceProgramType::where('performance_program_id',$performance->id)->get();
               $target_services =  PerformanceTargetService::where('performance_program_target_id',$id)->get()->toArray();
               return view('performances.programs.targets.performance.edit',compact('performance_program_types','performance','target','target_services'));
               break;
           case 'department':
               $id = HashingSlug::decodeHash($id);
               $target = DepartmentTarget::find($id)->toArray();
               if (count($target) <= 0)
               {
                   return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
               }

               $targetSelected[$target['department_id']] = $target;
               $departments = Department::where('company_id',\Auth::user()->company_id)->where('id',$target['department_id'])->get();

               return view('performances.programs.targets.department.edit',compact('departments','targetSelected','target'));
       }
   }

   public function update(Request $request,$id,$type)
   {

       switch ($type)
       {
           case 'performances':
               $target_id = HashingSlug::decodeHash($id);

                $targett = PerformanceTarget::where('id',$target_id)->first();

                if (!$targett)
                {
                    return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
                }
               foreach ($request->targets as $id => $target)
               {
                   $performance_program_type = PerformanceProgramType::where('id',$id)->first();
                   if($target[0] > $performance_program_type->puan)
                   {
                       return back()->with('danger',$performance_program_type->performance_type().' hedef değeri Puan dan Daha Yüksek');
                   }
               }


               $performance_target = PerformanceTarget::where('id',$target_id)->update([
                   'company_id'=>\Auth::user()->company_id,
                   'sgk_company_id'=> \Auth::user()->sgk_company_id,
                   'performance_program_id'=> $targett->performance_program_id
               ]);
                $delete = PerformanceTargetService::where('performance_program_target_id',$target_id)->delete([]);
               foreach ($request->targets as $id => $target)
               {
                   if (!empty($target[0]))
                   {
                       $performance_type = PerformanceProgramType::where('id',$id)->first();
                       $target_service = PerformanceTargetService::create([
                           'performance_program_target_id' => $target_id,
                           'performance_program_type_id' => $id,
                           'performance_type_id' => $performance_type->performance_type_id,
                           'target_puan' => $target[0]
                       ]);
                   }

               }
               if ($performance_target)
               {
                   return redirect(route('target.index','performances'))->with('success','Kayıt İşlemi Başarılu');
               }
               else
               {
                   return back()->with('danger','Kayıt işlemi Başarısız');
               }
               break;
               case 'department':
               dd($request);
       }
   }
}
