<?php

namespace App\Http\Controllers;

use App\DataTables\PermissionDataTable;
use App\Base\ApplicationController;
use App\Http\Requests\PermissionRequest;
use Spatie\Permission\Models\Permission;

class PermissionController extends ApplicationController
{
    protected $hashId = true;

    public function index(PermissionDataTable $dataTable)
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
            'permission' => new Permission,
            'currentPermission' => null,
            'hideCreateButton' => false,
        ];

        return view('standards.create', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param PermissionRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PermissionRequest $request)
    {
        $class = new Permission();

        return $this->createFlashRedirect($class, $request);
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
     * @param  Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('standards.edit', ['permission' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PermissionRequest $request
     * @param Permission $permission
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        return $this->saveFlashRedirect($permission, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Permission $permission)
    {
        return $this->destroyFlashRedirect($permission);
    }
}
