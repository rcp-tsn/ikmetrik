<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\WorkTitlesDataTable;
use App\Http\Requests\WorkTitleRequest;
use App\Models\WorkTitle;
use Illuminate\Http\Request;

class WorkTitleController extends ApplicationController
{
    /**
     * @var bool
     */
    protected $hash_id = true;
    /**
     * Display a listing of the resource.
     *
     * @param WorkTitlesDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(WorkTitlesDataTable $dataTable)
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
            'work_title' => new WorkTitle(),
            'currentWorkTitle' => null,
        ];

        return view('standards.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WorkTitleRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(WorkTitleRequest $request)
    {

        //dd($request['select_type']);
        $class = new WorkTitle();
        return $this->createFlashRedirect($class, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  WorkTitle  $work_title
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkTitle $work_title)
    {
        $data = [
            'work_title' => $work_title,
            'currentWorkTitle' => $work_title->title,
        ];

        return view('standards.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param WorkTitle $work_title
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, WorkTitle $work_title)
    {
        if ($request->isMethod('put')) {

            $this->validate($request, [
                'title' => 'required|unique:work_titles, title,'.$work_title->id,
            ]);
        }
        return $this->saveFlashRedirect($work_title, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param WorkTitle $work_title
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(WorkTitle $work_title)
    {
        return $this->destroyFlashRedirect($work_title);
    }
}
