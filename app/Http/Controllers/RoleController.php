<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use App\Base\ApplicationController;
use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends ApplicationController
{
    protected $hashId = true;

    public function index(RoleDataTable $dataTable)
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
            'role' => new Role,
            'permissions' => Permission::get(),
            'selectPermissions' => [],
        ];

        return view('standards.create', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(RoleRequest $request)
    {
        $permissions = $request->permissions;

        $request->offsetUnset('permissions');
        $role = Role::create($request->all());
        $role->syncPermissions($permissions);

        $role->id ? session()->flash('success', 'Kayıt başarılı bir şekilde oluşturuldu.') : session()->flash('danger', "Kayıt oluşturulamadı. Kontrol ederek tekrar deneyiniz.");

        return redirect(route('roles.index'));
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
     * @param  Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {

        $data = [
            'role' => $role,
            'permissions' => Permission::orderBy('name', 'ASC')->get(),
            'selectPermissions' => $role->getAllPermissions()->pluck('id')->all(),
        ];
        return view('standards.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param Role $role
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(RoleRequest $request, Role $role)
    {
        $role->syncPermissions($request->permissions);
        $request->offsetUnset('permissions');
        return $this->saveFlashRedirect($role, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Role $role)
    {
        return $this->destroyFlashRedirect($role);
    }
}
