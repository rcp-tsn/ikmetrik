<?php

namespace App\Http\Controllers;

use App\Helpers\SelectLists;
use App\Mail\NewUserMail;
use App\User;
use App\Models\Department;
use App\Models\WorkTitle;
use App\Models\Job;
use DB;
use Illuminate\Http\Request;
use Mail;
use Auth;

class InsertModalController extends Controller
{
    use SelectLists;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        return view('insert_modals.' . $type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|false
     */
    public function store(Request $request, $type)
    {
        $selectId = null;
        if (isset($request->selector_id)) {
            $selectId = $request->selector_id;
            $request->offsetUnset('selector_id');
        }

        switch ($type) {
            case ('department'):
                $messages = array(
                    'name.required' => 'Departman adı bilgisi gereklidir.',
                );
                $this->validate($request, [
                    'name' => 'required',
                ], $messages);

                $department = Department::create($request->all());

                return response()->json([
                    'addType' => 'select',
                    'addValue' => '<option value="' . $department->id . '" selected>' . $department->name . '</option>',
                    'selectorId' => $selectId ? $selectId : 'department_id',
                ]);
                break;
            case ('job'):
                $this->validate($request, [
                    'name' => 'required',
                ]);

                $job = Job::create($request->all());

                return response()->json([
                    'addType' => 'select',
                    'addValue' => '<option value="' . $job->id . '" selected>' . $job->name . '</option>',
                    'selectorId' => 'job_id',
                ]);
                break;
            case ('work_title'):
                $this->validate($request, [
                    'name' => 'required',
                ]);

                $workTitle = WorkTitle::create($request->all());

                return response()->json([
                    'addType' => 'select',
                    'addValue' => '<option value="' . $workTitle->id . '" selected>' . $workTitle->name . '</option>',
                    'selectorId' => $selectId ? $selectId : 'work_title_id',
                ]);
                break;

            case ('user'):
                $this->validate($request, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6|confirmed',
                ]);

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'department_id' => $request->department_id,
                    'job_id' => $request->job_id,
                ]);

                // Send mail
                Mail::to([$request->email])->send(new NewUserMail(
                    null, $user, $request->password
                ));

                return response()->json([
                    'addType' => 'select',
                    'addValue' => '<option value="' . $user->id . '" selected>' . $user->name . '</option>',
                    'selectorId' => $selectId ? $selectId : 'user',
                ]);
                break;
        }

        return false;
    }
}
