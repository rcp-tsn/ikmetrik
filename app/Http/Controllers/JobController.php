<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\JobsDataTable;
use App\Http\Requests\JobRequest;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends ApplicationController
{
    /**
     * @var bool
     */
    protected $hash_id = true;
    /**
     * Display a listing of the resource.
     *
     * @param JobsDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(JobsDataTable $dataTable)
    {
        return $dataTable->render('standards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        $data = [
            'job' => new Job(),
            'currentJob' => null,
        ];

        return view('standards.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param JobRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(JobRequest $request)
    {
        $class = new Job();
        return $this->createFlashRedirect($class, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        $data = [
            'job' => $job,
            'currentJob' => $job->title,
        ];

        return view('standards.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Job $job
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Job $job)
    {
        if ($request->isMethod('put')) {

            $messages = array(
                'name.required' => 'Meslek adı alanı gereklidir',
                'name.unique' => 'Meslek adı sistemde mevcutdur.',
            );
            $this->validate($request, [
                'name' => 'required|unique:jobs,name,'.$job->id,
            ], $messages);
        }
        return $this->saveFlashRedirect($job, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Job $job
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Job $job)
    {
        return $this->destroyFlashRedirect($job);
    }
}
