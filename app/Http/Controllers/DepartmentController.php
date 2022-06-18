<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\DataTables\DepartmentsDataTable;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends ApplicationController
{
    /**
     * @var bool
     */
    protected $hash_id = true;
    /**
     * Display a listing of the resource.
     *
     * @param DepartmentsDataTable $dataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(DepartmentsDataTable $dataTable)
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
            'department' => new Department(),
            'currentDepartment' => null,
        ];

        return view('standards.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DepartmentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(DepartmentRequest $request)
    {
        $class = new Department();
        return $this->createFlashRedirect($class, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $data = [
            'department' => $department,
            'currentDepartment' => $department->name,
        ];

        return view('standards.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param
     * Request $request
     * @param Department $department
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Department $department)
    {
        if ($request->isMethod('put')) {
            $messages = array(
                'name.required' => 'Departman adı alanı gereklidir',
                'name.unique' => 'Departman adı sistemde mevcutdur.',
            );
            $this->validate($request, [
                'name' => 'required|unique:departments,name,'.$department->id,
            ], $messages);
        }
        return $this->saveFlashRedirect($department, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Department $department
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Department $department)
    {
        return $this->destroyFlashRedirect($department);
    }
}
